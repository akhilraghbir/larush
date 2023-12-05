<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-people";
		$data['title'] = "Clients";
		$data['helptext'] = "This Page Is Used To Manage The Clients.";
		$data['actions']['add'] = CONFIG_SERVER_ADMIN_ROOT.'clients/add';
		$data['actions']['list'] = CONFIG_SERVER_ADMIN_ROOT.'clients';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$data['accountants'] = $this->Common_model->getDataFromTable('tbl_users','id,first_name', $whereField=['status' => 'Active','user_type' => 'Accountant'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/clients',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['accountants'] = $this->Common_model->getDataFromTable('tbl_users','id,first_name', $whereField=['status' => 'Active','user_type' => 'Accountant'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$data['form_action'] = $formName;
		$this->home_template->load('home_template','admin/clients',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('first_name','last_name','email_id','accountant','address_line1','client_type','status');    
            foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }

			$this->form_validation->set_rules('first_name','First name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('last_name','Last name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('client_type','Client Type', 'required');
			$this->form_validation->set_rules('accountant','Accountant', 'required');
			$this->form_validation->set_rules('phno', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_message('is_unique', 'The %s already exists');
        	$this->form_validation->set_rules('email_id', 'email id', 'required|is_unique[tbl_users.email_id]');

            if($this->form_validation->run() == FALSE){
				$this->form_validation->set_session_data($this->input->post());
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				$result = array();
                foreach($this->input->post() as $fieldname=>$fieldvalue){
                	$data[$fieldname]= $this->input->post($fieldname);
                }
                unset($data['add']);
				$password = rand(100000,99999999);
				$toaddress = $data['username'] = $data['email_id'];
				$data['password'] = md5($password);
				$data['created_on'] = current_datetime();
				$data['user_type'] = 'Client';
				$user_id = $this->Common_model->addDataIntoTable('tbl_users',$data);
				$getTemplate = $this->Common_model->getDataFromTable('tbl_emailtemplates','*', $whereField='id', $whereValue=3, $orderBy='', $order='', $limit=1, $offset=0, true);
				$Subject = $getTemplate[0]['template_subject'];
				$otherCC = $getTemplate[0]['template_otheremails'];
				$emaildata['email_body'] = $getTemplate[0]['template_body'];
				$siteUrl = base_url();
				$emaildata['email_body'] = str_replace("##NAME##",$data['first_name'],$emaildata['email_body']);
				$emaildata['email_body'] = str_replace("##SITENAME##",SITENAME,$emaildata['email_body']);
				$emaildata['email_body'] = str_replace("##EMAIL##",$data['email_id'],$emaildata['email_body']);
				$emaildata['email_body'] = str_replace("##PASSWORD##",$password,$emaildata['email_body']);
				$enduserHTML = $this->load->view('email_template',$emaildata,true);
				$send = $this->Email_model->send($toaddress,$Subject,$enduserHTML,$otherCC);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('User Created Successfully','success');
				redirect('administrator/clients');
			}
		}
			$this->loadUserForm(array(),'add');
	}

	public function edit($param1=''){
	    if($this->session->user_type == 'Admin'){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('first_name','last_name','email_id','address_line1','status');    
            foreach($mandatoryFields as $row){
            $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
            $this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
			$this->form_validation->set_rules('first_name','First name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('last_name','Last name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('phno', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('email_id', 'email id', 'required');
            $this->form_validation->set_rules('client_type','Client Type', 'required');
            $this->form_validation->set_rules('accountant','Accountant', 'required');
			$checkUser = $this->Common_model->check_exists('tbl_users','email_id',$this->input->post('email_id'),'id',$param1);
			if($checkUser > 0){
				$this->messages->setMessage('Client email <i>'.$this->input->post('email_id').'</i> already exist','error');
			}else{ 
				if($this->form_validation->run() == FALSE){
    				$errorMessage=validation_errors();
    				$this->messages->setMessage($errorMessage,'error');
				}else{
                	foreach($this->input->post() as $fieldname=>$fieldvalue){
                    	$data[$fieldname]= $this->input->post($fieldname);
                    }
                    unset($data['edit']);
    				$this->Common_model->updateDataFromTable('tbl_users',$data,'id',$param1);
    				$this->messages->setMessage('Client Updated Successfully','success');
    				redirect(base_url('administrator/clients'));
				}
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_users','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];	
		}
		$this->loadUserForm($formData, 'edit');
	    }else{
            $this->messages->setMessage('You dont have permission to edit client','danger');
            redirect('administrator/clients');  
	    }
	}

	public function updateStatus(){
		$u_id = $this->input->post('user_id');
		if($this->input->post('status') == 'Active'){
			$data['status'] = $this->input->post('status');
			$succ_message = 'User Active Successfully';
		}else{
			$data['status'] = $this->input->post('status');
			$succ_message = 'User Inactive Successfully';
		}
		
		$this->Common_model->updateDataFromTable('tbl_users',$data,'id',$u_id);
		$message=array('error'=>'0','message'=>$succ_message);
        echo json_encode($message);
        exit;
	}

    public function delete_user($id)
    {
        $del = $this->Common_model->delete_user($id);
        if($del)
        {
            $message=array('error'=>'0','message'=>'User Deleted Successfully');
			echo json_encode($message);
			exit;
        }
    }

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$status         =  $this->input->post('status');
		$accountant         =  $this->input->post('accountant');
		$client_type         =  $this->input->post('client_type');
		$indexColumn = 'tu.id';
		$selectColumns = array('tu.id','tu.first_name','tu.last_name','tu.email_id','tu.phno','tur.first_name as accountant_name','tu.client_type','tu.status','tu.created_on');
		$dataTableSortOrdering = array('tu.id','tu.first_name','tu.email_id','tu.phno','tur.first_name','tu.client_type','tu.status','tu.created_on');
		$table_name='tbl_users as tu';
		$joinsArray[] = ['table_name'=>'tbl_users as tur','condition'=>"tur.id = tu.accountant",'join_type'=>'left'];
		$wherecondition="tu.user_type='Client'";
		if($status=='Active'){
		    $wherecondition.=' and tu.status = "Active"';
		}else if($status=='Inactive'){
		    $wherecondition.=' and tu.status = "Inactive"';
		}
		if($accountant!='All'){
		    $wherecondition.=" and tu.accountant=".$accountant;
		}
		if($client_type!='All'){
		    $wherecondition.= " and tu.client_type='".$client_type."'";
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
				$action="";
				$content .='[';
				$recordListing[$i][0]= $i+1;
                $recordListing[$i][1]= $recordData->first_name.' '.$recordData->last_name;
                $recordListing[$i][2]= $recordData->email_id;
                $recordListing[$i][3]= $recordData->phno;
				$recordListing[$i][4]= $recordData->accountant_name;
				$recordListing[$i][5]= '<span class="badge badge-pill badge-info">'.$recordData->client_type.'</span>';
				if($recordData->status == 'Inactive'){
					$recordListing[$i][6]= '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				}else{
					$recordListing[$i][6]= '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                $recordListing[$i][7] = displayDateInWords($recordData->created_on);
				if($this->session->userdata('user_type') == 'Admin'){	
					if($recordData->status == 'Inactive'){
						$action.= '<a class="btn" onclick="statusUpdate(this,'."'$recordData->id'".','."'Active'".')" style="margin-bottom: 2px;color:green;font-size: 16px;cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Active"><i class="icon-check"></i></a>';
					}else{
						$action.= '<a class="btn" onclick="statusUpdate(this,'."'$recordData->id'".','."'Inactive'".')" style="margin-bottom: 2px;color:red;font-size: 16px;cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="Inactive"><i class="icon-close"></i></a>';
					}
					 $action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'clients/edit/'.$recordData->id.'" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
				}
				$recordListing[$i][8]= $action;
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
	
	public function getClientsByAccountant(){
	    if($_POST){
	        if(isset($_POST['accountant'])){
	           if($_POST['accountant']!='All'){
	            $where['accountant'] = $_POST['accountant'];
	           }
	           if($_POST['type']!=''){
	               $where['client_type'] = $_POST['type'];
	           }
	           $where['status'] = 'Active';
	           $where['user_type'] = 'Client';
	           $data = $this->Common_model->getDataFromTable('tbl_users','id,concat(first_name," ",last_name) as name',  $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true); 
	           if(is_array($data)){
    	           $option = "<option value='All'>All</option>";
    	           foreach($data as $d){
    	            $option.= "<option value='".$d["id"]."'>".$d["name"]."</option>";    
    	           }
    	           $res['html'] = $option;
	           }else{
	               $res['html'] = "<option value='All'>No Clients Found</option>";;
	           }
	           echo json_encode($res);
	        }
	    }
	}
	
	function alpha_dash_space($fullname){
		if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
