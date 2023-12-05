<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailTemplates extends CI_Controller {
	public function __construct(){
		parent::__construct();
		check_UserSession();
		$this->load->model('Datatables_model');
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-envelope";
		$data['title']="Email Templates";
		$data['helptext']="This Page Is Used To Manage The Email Templates.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'emailTemplates/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'emailTemplates';
		return $data;
	}
	public function index(){
		$data['breadcrumbs']=$this->loadBreadCrumbs();  
		$this->home_template->load('home_template','admin/emailTemplates',$data);   
	}
	public function loadMenuForm($formContent=array(), $formName=''){
		$data['breadcrumbs']=$this->loadBreadCrumbs();
		$data['data']=$formContent;
		$data['form_action']=$formName;
		$this->home_template->load('home_template','admin/emailTemplates',$data); 
	}
	public function add(){
		if(($this->input->post('add'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('template_name','template_subject','template_body');  
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
				$this->Common_model->addDataIntoTable('tbl_emailtemplates',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Email Template Created Successfully','success');
				redirect(base_url('administrator/emailTemplates'));
			}
		}
			$this->loadMenuForm(array(),'add Email Template');
	}
	public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('template_name','template_subject','template_body');  
            foreach($mandatoryFields as $row){
            $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
            $this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
			//$this->form_validation->set_rules('template_otheremails', 'Send to Other Email', 'required|valid_email');
            if($this->form_validation->run() == FALSE){
			$errorMessage=validation_errors();
			$this->messages->setMessage($errorMessage,'error');

			}else{
            foreach($this->input->post() as $fieldname=>$fieldvalue){
                    $data[$fieldname]= $this->input->post($fieldname);
			} 
			unset($data['edit']);

			$this->Common_model->updateDataFromTable('tbl_emailtemplates',$data,'id',$param1);
			$this->messages->setMessage('Email Template Updated Successfully','success');
			   redirect(base_url('administrator/emailTemplates'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_emailtemplates','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];
		}
		$this->loadMenuForm($formData, 'Edit Email Template');
	}
	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$indexColumn='id';
		$selectColumns=array('id','template_name','template_subject','status');
		$dataTableSortOrdering=array('template_name','template_subject','status');
		$table_name='tbl_emailtemplates';
		$joinsArray=array();
		$wherecondition='status="Active"';
		$getRecordListing=$this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords=$getRecordListing['recordsTotal'];
		$recordsFiltered=$getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i=0;	
        $srNumber=$start;	
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing['data'] as $recordData) {
				$action='';
				$content .='[';
                $recordListing[$i][0]= $recordData->template_name;

                $recordListing[$i][1]= $recordData->template_subject;
				

				if ($recordData->status == 'Inactive') {
					$recordListing[$i][2] = '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				} else {
					$recordListing[$i][2] = '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                if($this->session->userdata('user_type') == 'Admin'){			
                	$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'emailTemplates/edit/'.$recordData->id.'" title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
                }
				$recordListing[$i][3]= $action;
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
