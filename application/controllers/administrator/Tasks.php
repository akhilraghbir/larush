<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {
	public function __construct(){
		parent::__construct();
		check_UserSession();
		$this->load->model('Datatables_model');
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-list";
		$data['title']="Tasks";
		$data['helptext']="This Page Is Used To Manage The Tasks.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'tasks/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'tasks';
		return $data;
	}
	public function index(){
		$data['breadcrumbs']=$this->loadBreadCrumbs();  
		$this->home_template->load('home_template','admin/tasks',$data);   
	}
	public function loadMenuForm($formContent=array(), $formName=''){
		$data['breadcrumbs']=$this->loadBreadCrumbs();
		$data['data']=$formContent;
		$data['form_action']=$formName;
        $data['employees'] = $this->Common_model->getDataFromTable('tbl_users','id,first_name,username',  $whereField=['status'=>'Active','user_type'=>'Employee'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/tasks',$data); 
	}
	public function add(){
		if(($this->input->post('add'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('task_title','employee_id','priority','task_description');  
            foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
            if($this->form_validation->run() == FALSE){
				$this->form_validation->set_session_data($this->input->post());
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				foreach($this->input->post() as $fieldname=>$fieldvalue){
					$data[$fieldname]= $this->input->post($fieldname);
				} 
				$data['created_by']= $this->session->id;
				$data['created_on']=current_datetime();
				unset($data['add']);
				$this->Common_model->addDataIntoTable('tbl_tasks',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Task Created Successfully','success');
				redirect(base_url('administrator/Tasks'));
			}
		}
			$this->loadMenuForm(array(),'add Email Template');
	}
	
    public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('task_title','employee_id','priority','task_description');  
            foreach($mandatoryFields as $row){
                $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                $this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
            if($this->form_validation->run() == FALSE){
                $errorMessage=validation_errors();
                $this->messages->setMessage($errorMessage,'error');
			}else{
                foreach($this->input->post() as $fieldname=>$fieldvalue){
                    $data[$fieldname]= $this->input->post($fieldname);
                } 
                unset($data['edit']);
                $this->Common_model->updateDataFromTable('tbl_tasks',$data,'id',$param1);
                $this->messages->setMessage('Task Updated Successfully','success');
                redirect(base_url('administrator/Tasks'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_tasks','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];
		}
		$this->loadMenuForm($formData, 'Edit Email Template');
	}
    
	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$indexColumn = 'tt.id';
		$selectColumns = ['tt.id','tt.task_title','tt.status','tt.created_on','tu.first_name'];
		$dataTableSortOrdering = ['tt.id','tt.task_title','tt.status','tt.created_on','tu.first_name'];
		$table_name='tbl_tasks as tt';
		$joinsArray[] = ['table_name'=>'tbl_users as tu','condition'=>"tu.id = tt.employee_id",'join_type'=>'left'];
		$wherecondition = 'tt.id!="0"';
		$getRecordListing = $this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords=$getRecordListing['recordsTotal'];
		$recordsFiltered=$getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i = $j =0;	
        $srNumber=$start;	
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing['data'] as $recordData) {
				$action='';
				$content .='[';
                $recordListing[$i][0]= ++$j;
                $recordListing[$i][1]= $recordData->task_title;
                $recordListing[$i][2]= $recordData->first_name;
				if ($recordData->status == 'Pending') {
					$recordListing[$i][3] = '<span class="badge rounded-pill bg-warning">'.$recordData->status.'</span>';
				} else {
					$recordListing[$i][3] = '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                $recordListing[$i][4]= displayDateInWords($recordData->created_on);
                if($this->session->userdata('user_type') == 'Admin' && $recordData->status == 'Pending'){			
                	$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'tasks/edit/'.$recordData->id.'" title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
                }
				$recordListing[$i][5]= $action;
				$i++;
                $srNumber++;
            }
            $content .= ']';
            $final_data = json_encode($recordListing);
        } else {
            $final_data = '[]';
        }	
         echo '{"draw":'.$draw.',"recordsTotal":'.$recordsFiltered.',"recordsFiltered":'.$recordsFiltered.',"data":'.$final_data.'}';
       	
	}	
}
