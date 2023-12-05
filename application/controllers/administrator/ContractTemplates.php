<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContractTemplates extends CI_Controller {
	public function __construct(){
		parent::__construct();
		check_UserSession();
		$this->load->model('Datatables_model');
    } 

	public function loadBreadCrumbs(){
		$data = array();
		$data['icon_class']="icon-envelope";
		$data['title']="Contract Templates";
		$data['helptext']="This Page Is Used To Manage The Contract Templates.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'ContractTemplates/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'ContractTemplates';
		return $data;
	}
	public function index(){
		$data['breadcrumbs']=$this->loadBreadCrumbs();  
		$this->home_template->load('home_template','admin/contractTemplates',$data);   
	}
	public function loadMenuForm($formContent=array(), $formName=''){
		$data['breadcrumbs']=$this->loadBreadCrumbs();
		$data['data']=$formContent;
		$data['form_action']=$formName;
		$this->home_template->load('home_template','admin/contractTemplates',$data); 
	}
	public function add(){
		if(($this->input->post('add'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('template_name','template_body');  
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
				$this->Common_model->addDataIntoTable('tbl_contract_templates',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Contract Template Created Successfully','success');
				redirect(base_url('administrator/ContractTemplates'));
			}
		}
			$this->loadMenuForm(array(),'add Email Template');
	}
	public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('template_name','template_body');  
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
            $data['updated_by']= $this->session->id;
            $data['updated_on']=current_datetime();
			$this->Common_model->updateDataFromTable('tbl_contract_templates',$data,'id',$param1);
			$this->messages->setMessage('Contract Template Updated Successfully','success');
			   redirect(base_url('administrator/ContractTemplates'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_contract_templates','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];
		}
		$this->loadMenuForm($formData, 'Edit Email Template');
	}
	
	public function sendContract(){
	    if(($this->input->post('add'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('client_id','contract_id','contract');  
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
				$data['created_on'] = current_datetime();
				unset($data['add']);
				unset($data['user_id']);
				$data['reference_number'] = 'C-'.time();
				$ins = $this->Common_model->addDataIntoTable('tbl_contracts',$data);
				if($ins){
					$userData = $this->Common_model->getDataFromTable('tbl_users','first_name,email_id', $whereField='id', $whereValue=$_POST['client_id'], $orderBy='', $order='', $limit=1, $offset=0, true);
					$getTemplate = $this->Common_model->getDataFromTable('tbl_emailtemplates','*', $whereField='id', $whereValue=5, $orderBy='', $order='', $limit=1, $offset=0, true);
					$Subject = $getTemplate[0]['template_subject'];
					$otherCC = $getTemplate[0]['template_otheremails'];
					$emaildata['email_body'] = $getTemplate[0]['template_body'];
					$siteUrl = base_url();
					$curl = base_url('sign-contract/'.base64_encode($data['reference_number']));
					$emaildata['email_body'] = str_replace("##NAME##",$userData[0]['first_name'],$emaildata['email_body']);
					$emaildata['email_body'] = str_replace("##SITENAME##",SITENAME,$emaildata['email_body']);
					$emaildata['email_body'] = str_replace("##SITEURL##",$siteUrl,$emaildata['email_body']);
					$emaildata['email_body'] = str_replace("##CONTRACTURL##",$curl,$emaildata['email_body']);
					$enduserHTML = $this->load->view('email_template',$emaildata,true);
					$send = $this->Email_model->send($userData[0]['email_id'],$Subject,$enduserHTML,$otherCC);
				}
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Contract Created Successfully','success');
				redirect(base_url('administrator/ContractTemplates'));
			}
		}
	    $data['breadcrumbs'] = $this->loadSendContractBreadCrumbs();
		$data['contracts'] = $this->Common_model->getDataFromTable('tbl_contract_templates','id,template_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset='', true);
		$this->home_template->load('home_template','admin/sendContracts',$data); 
	}
	
	public function getContract(){
	    if($_POST){
	        $clientData = $this->Common_model->getDataFromTable('tbl_users','',  $whereField='id', $whereValue=$_POST['id'], $orderBy='', $order='', $limit='', $offset='', true);
	        $contractData = $this->Common_model->getDataFromTable('tbl_contract_templates','template_body',  $whereField='id', $whereValue=$_POST['cid'], $orderBy='', $order='', $limit='', $offset='', true);
	        if(!empty($clientData[0]) && !empty($contractData[0])){
	            $address = $clientData[0]['address_line1'];
	            $address.= (!empty($clientData[0]['address_line2'])) ? "<br>".$clientData[0]['address_line2'] : ""; 
	            $address.= (!empty($clientData[0]['address_line3'])) ? "<br>".$clientData[0]['address_line3'] : ""; 
	            $template = $contractData[0]['template_body'];
	            $template = str_replace("##FIRSTNAME##",$clientData[0]['first_name'],$template);
	            $template = str_replace("##LASTNAME##",$clientData[0]['last_name'],$template);
	            $template = str_replace("##LASTNAME##",$clientData[0]['last_name'],$template);
	            $template = str_replace("##EMAIL##",$clientData[0]['email_id'],$template);
	            $template = str_replace("##PHONE##",$clientData[0]['phno'],$template);
	            $template = str_replace("##SALUTATION##",$clientData[0]['salutation'],$template);
	            $template = str_replace("##ADDRESS##",$address,$template);
	            $template = str_replace("##DATE##",current_date(),$template);
	            $template = str_replace("##DAYDATE##",current_daydate(),$template);
	            $template = str_replace("##DATETIME##",current_datetime(),$template);
	            $data['status'] = 'success';
	            $data['template'] = $template;
	        }else{
	            $data['status'] = 'error';
	        }
	        echo json_encode($data);
	    }
	}
	
	public function loadSendContractBreadCrumbs(){
		$data = array();
		$data['icon_class'] = "icon-envelope";
		$data['title'] = "Contracts";
		$data['helptext'] = "This Page Is Used To Manage The Contracts.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'ContractTemplates/sendContract';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'ContractTemplates/contractsHistory';
		return $data;
	}
	public function keywords(){
	    $data['breadcrumbs'] = $this->loadSendContractBreadCrumbs();
		$data['keywords'] = $this->Common_model->getDataFromTable('tbl_keywords','name,keyword',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset='', true);
		$this->home_template->load('home_template','admin/keywords',$data); 
	}
	
	public function contractsHistory(){
		$data['breadcrumbs'] = $this->loadSendContractBreadCrumbs();
		$this->home_template->load('home_template','admin/contractsHistory',$data); 
	}


	public function contractsajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$user_id       = $this->input->post('user_id');
		$type       = $this->input->post('type');
		$indexColumn='contracts.id';
		$selectColumns=array('contracts.id','contracts.reference_number','templates.template_name','clients.first_name','contracts.signature','contracts.created_on','contracts.contract_file');
		$dataTableSortOrdering=array('contracts.reference_number','templates.template_name','clients.first_name','contracts.signature','contracts.created_on');
		$table_name='tbl_contracts as contracts';
		$joinsArray[] = ['table_name'=>'tbl_contract_templates as templates','condition'=>"templates.id = contracts.contract_id",'join_type'=>'left'];
		$joinsArray[] = ['table_name'=>'tbl_users as clients','condition'=>"clients.id = contracts.client_id",'join_type'=>'left'];
		$wherecondition='contracts.id!=""';
		if(!empty($user_id)){
			$wherecondition.= ' and client_id='.$user_id;
		}
		if($type == 'Signed'){
			$wherecondition.=' and signature!="0"';
		}else if($type == 'Unsigned'){
			$wherecondition.=" and signature='0'";
		}
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
				$url = $action='';
				$content .='[';
                $recordListing[$i][0]= $recordData->reference_number;
				$recordListing[$i][1] = $recordData->template_name;
				$recordListing[$i][2] = $recordData->first_name;
				$recordListing[$i][3] = ($recordData->signature == '0') ? '<span class="badge rounded-pill bg-danger">Unsigned</span>' : '<span class="badge rounded-pill bg-success">Signed</span>';
				$recordListing[$i][4]= displayDateInWords($recordData->created_on);
				if($recordData->signature == '0'){
					$url = base_url('load-contract-pdf/'.base64_encode($recordData->reference_number));
				}else{
					$url = base_url($recordData->contract_file);
				} 
				$recordListing[$i][5] =  '<a href="'.$url.'" target="_blank"><i class="icon-eye"></i></a>';
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

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$indexColumn='id';
		$selectColumns=array('id','template_name','status','created_on');
		$dataTableSortOrdering=array('template_name','status','created_on');
		$table_name='tbl_contract_templates';
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
				if ($recordData->status == 'Inactive') {
					$recordListing[$i][1] = '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				} else {
					$recordListing[$i][1] = '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
				$recordListing[$i][2]= displayDateInWords($recordData->created_on);
                if($this->session->userdata('user_type') == 'Admin'){			
                	$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'ContractTemplates/edit/'.$recordData->id.'" title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
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
