<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Notifications";
		$data['helptext'] = "This Page Is Used To Manage The Notifications.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'Notifications/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'Notifications';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		if($id!=''){
		    $where['id'] = $id;
		}
		$this->home_template->load('home_template','admin/notifications',$data);   
	}
	
	public function loadMenuForm($formContent=array(), $formName=''){
		$data['breadcrumbs']=$this->loadBreadCrumbs();
		$data['data']=$formContent;
		$data['form_action']=$formName;
        $data['employees'] = $this->Common_model->getDataFromTable('tbl_users','id,first_name,username',  $whereField=['status'=>'Active','user_type'=>'Employee'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/notifications',$data); 
	}
	
	public function add(){
		if(($this->input->post('add'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('title','employee_id','notif_description');  
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
				$this->Common_model->addDataIntoTable('tbl_notifications',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Notification Created Successfully','success');
				redirect(base_url('administrator/Notifications'));
			}
		}
			$this->loadMenuForm(array(),'add Email Template');
	}
	
    public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('title','employee_id','notif_description');  
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
                $this->Common_model->updateDataFromTable('tbl_notifications',$data,'id',$param1);
                $this->messages->setMessage('Notification Updated Successfully','success');
                redirect(base_url('administrator/Notifications'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_notifications','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];
		}
		$this->loadMenuForm($formData, 'Edit Email Template');
	}
    

	public function markasread(){
	    if($_POST){
	        $id = $_POST['notifid'];
	        $upd = $this->Common_model->updateDataFromTable('tbl_notifications',['status' => 'Inactive'],'id',$id);
	        if($upd){
	            echo json_encode(['error' => '0','message' => 'Status Updated Successfully']);
	        }else{
	            echo json_encode(['error' => '1','message' => 'Something went wrong']);
	        }exit;
	    }
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$indexColumn = 'tt.id';
		$selectColumns = ['tt.id','tt.title','tt.status','tt.created_on','tu.first_name'];
		$dataTableSortOrdering = ['tt.id','tt.title','tt.status','tt.created_on','tu.first_name'];
		$table_name='tbl_notifications as tt';
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
                $recordListing[$i][1]= $recordData->title;
                $recordListing[$i][2]= $recordData->first_name;
				if ($recordData->status == 'Not Seen') {
					$recordListing[$i][3] = '<span class="badge rounded-pill bg-warning">'.$recordData->status.'</span>';
				} else {
					$recordListing[$i][3] = '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                $recordListing[$i][4]= displayDateInWords($recordData->created_on);
                if($this->session->userdata('user_type') == 'Admin' && $recordData->status == 'Not Seen'){			
                	$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'Notifications/edit/'.$recordData->id.'" title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
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
?>