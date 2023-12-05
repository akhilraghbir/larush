<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-user";
		$data['title']="Users";
		$data['helptext']="This Page Is Used To Manage The Users.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'users/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'users';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/users',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
		if($formName == 'add'){
			$this->home_template->load('home_template','admin/users',$data); 
		}else{
			$this->home_template->load('home_template','admin/edit_user',$data);
		}
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('first_name','last_name','email_id','user_type','status');    
            foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }

			$this->form_validation->set_rules('first_name','First name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('last_name','Last name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('mobile_no', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_message('is_unique', 'The %s already exists');
        	$this->form_validation->set_rules('email_id', 'email id', 'required|is_unique[tbl_users.email_id]');

            if($this->form_validation->run() == FALSE){
				$this->form_validation->set_session_data($this->input->post());
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				foreach($this->input->post() as $fieldname=>$fieldvalue){
                	$data[$fieldname]= $this->input->post($fieldname);
                }
				unset($data['add']);
				$password = rand(100000,99999999);
				$data['password'] = md5($password);
				$data['created_on'] = current_datetime();
				$data['user_type'] == 'Accountant';
				$user_id = $this->Common_model->addDataIntoTable('tbl_users',$data);
				$toaddress = $data['email_id'];
				$getTemplate = $this->Common_model->getDataFromTable('tbl_emailtemplates','*', $whereField='id', $whereValue=1, $orderBy='', $order='', $limit=1, $offset=0, true);
				$Subject = $getTemplate[0]['template_subject'];
				$otherCC = $getTemplate[0]['template_otheremails'];
				$emaildata['email_body'] = $getTemplate[0]['template_body'];
				$siteUrl = base_url();
				$emaildata['email_body'] = str_replace("##NAME##",$data['first_name'],$emaildata['email_body']);
				$emaildata['email_body'] = str_replace("##SITEURL##",$siteUrl,$emaildata['email_body']);
				$emaildata['email_body'] = str_replace("##SITENAME##",SITENAME,$emaildata['email_body']);
				$emaildata['email_body'] = str_replace("##EMAIL##",$data['email_id'],$emaildata['email_body']);
				$emaildata['email_body'] = str_replace("##PASSWORD##",$password,$emaildata['email_body']);
				$enduserHTML = $this->load->view('email_template',$emaildata,true);
				$send = $this->Email_model->send($toaddress,$Subject,$enduserHTML,$otherCC);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('User Created Successfully','success');
				redirect('administrator/users');
			}
		}
			$this->loadUserForm(array(),'add');
	}

	public function edit($param1=''){
		
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('first_name','last_name','email_id','status');    
            foreach($mandatoryFields as $row){
            $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
            $this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
			$this->form_validation->set_rules('first_name','First name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('last_name','Last name', 'required|callback_alpha_dash_space');
			$this->form_validation->set_rules('phno', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('email_id', 'email id', 'required');
			$checkUser = $this->Common_model->check_exists('tbl_users','email_id',$this->input->post('email_id'),'id',$param1);
			//echo $checkUser; //exit;
			if($checkUser > 0){
				$this->messages->setMessage('User email <i>'.$this->input->post('email_id').'</i> already exist','error');
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
    				$this->messages->setMessage('User Updated Successfully','success');
    				redirect(base_url('administrator/users'));
				}
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_users','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];	
		}
		$this->loadUserForm($formData, 'edit');
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
		$role         =  $this->input->post('role');
		$indexColumn='id';
		$selectColumns=array('id','first_name','last_name','email_id','phno','user_type','status','created_on');
		$dataTableSortOrdering=array('id','first_name','email_id','phno','user_type','status','created_on');
		$table_name='tbl_users';
		$joinsArray=array();
		$wherecondition='id!='.$this->session->id." and user_type!='Client'";
		if($status=='Active'){
		    $wherecondition.=' and status = "Active"';
		}else if($status=='Inactive'){
		    $wherecondition.=' and status = "Inactive"';
		}
		if($role!='All'){
		    $wherecondition.= " and user_type='$role'";
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
				$recordListing[$i][4]= $recordData->user_type;
				if($recordData->status == 'Inactive'){
					$recordListing[$i][5]= '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				}else{
					$recordListing[$i][5]= '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                $recordListing[$i][6]= displayDateInWords($recordData->created_on);
				if($this->session->userdata('user_type') == 'Admin'){	
					if($recordData->status == 'Inactive'){
						$action.= '<a class="btn" title="Active" onclick="statusUpdate(this,'."'$recordData->id'".','."'Active'".')" style="margin-bottom: 2px;color:green;font-size: 16px;cursor:pointer;"><i class="ri-check-line"></i></a>';
					}else{
						$action.= '<a class="btn" title="Deactive" onclick="statusUpdate(this,'."'$recordData->id'".','."'Inactive'".')" style="margin-bottom: 2px;color:red;font-size: 16px;cursor:pointer;"><i class="ri-close-line"></i></a>';
					}
				}
				$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'users/edit/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
				$recordListing[$i][7]= $action;
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
	function alpha_dash_space($fullname){
		if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function getUsers()
	{
		$vmsNo = $_POST['term'];
		$accountant = $_POST['accountant'];
		$wherecondition = " user_type='Client' and status='Active' and (first_name LIKE '%" . $_POST['term'] . "%' or email_id LIKE '%" . $_POST['term'] . "%' or phno LIKE '%" . $_POST['term'] . "%')";
		if ($accountant != '') {
			$wherecondition .= " and accountant = " .$accountant;
		} 
		$vmsRefdata = $this->Common_model->getSelectedFields('tbl_users', 'id,concat(first_name," ",last_name) as name', $wherecondition, $limit = '100', $orderby = 'id', $sortby = 'DESC');
		echo json_encode($vmsRefdata);
	}
}
