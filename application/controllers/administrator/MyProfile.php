<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyProfile extends CI_Controller {
	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-user";
		$data['title']="My Profile";
		$data['helptext']="This Page Is Used To Manage The Profile.";
		return $data;
	}



	public function index(){
		$data['breadcrumbs']=$this->loadBreadCrumbs();
		if($this->session->id){
			$result = $this->Common_model->getDataFromTable('tbl_users','',  $whereField='id', $whereValue=$this->session->id, $orderBy='', $order='', $limit=1, $offset=0, true);
			$data['data']=$result[0];
		}   
		$this->home_template->load('home_template','admin/edit_profile',$data);   
	}

	public function changePassword(){
		$breadCrumb=array();
		$breadCrumb['icon_class']="icon-lock";
		$breadCrumb['title']="Change Password";
		$breadCrumb['helptext']="This Page is Used To Change Your Password.";
		$data['breadcrumbs']=$breadCrumb;
		if(($this->input->post('changePassword'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('current_password','new_password','retype_password');
			foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
			}
			if($this->form_validation->run() == FALSE){
				$errorMessage = validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				$oldPassword  = $this->input->post('current_password');
				$mdpass = md5($oldPassword);
				$newPassword  = $this->input->post('new_password');
				$newmdPass = md5($newPassword);
				$confPassword  = $this->input->post('retype_password');
				$id = $this->session->id;
				//$result = $this->Common_model->getSelectedFields('tbl_users','password', "user_id=$id",'1');
				$result = $this->Common_model->getDataFromTable('tbl_users','',  $whereField='id', $whereValue=$id, $orderBy='', $order='', $limit='', $offset=0, true);
				$currPass = $result[0]['password'];
				$url=CONFIG_SERVER_ADMIN_ROOT.'myProfile/changePassword';
				if($mdpass == $currPass){
					if($newPassword==$confPassword){
						if($newmdPass==$currPass){
							$this->messages->setMessage("Your New Password and Current Password Both Are Same",'error');
							redirect($url);
						}else{
							$dataToBeUpdated['password'] = $newmdPass;
							$this->Common_model->updateDataFromTable('tbl_users',$dataToBeUpdated,'id',$id);
							$this->messages->setMessage("Password Changed",'success');
							redirect($url);
						}
					}else{
						$this->messages->setMessage("New Password And Confirm Password Didn't Matched",'error');
						redirect($url);
					}

				}else{
					$this->messages->setMessage("Your Current Password is Invalid",'error');
					redirect($url);
				}
			}
		}
		$this->home_template->load('home_template','admin/changePassword',$data); 
	}

	public function edit_profile(){
		$this->form_validation->checkXssValidation($this->input->post());
		$mandatoryFields=array('first_name','last_name','phno');    
		foreach($mandatoryFields as $row){
		$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
		$this->form_validation->set_rules($row, $fieldname, 'required'); 
		}
		$this->form_validation->set_rules('first_name','First name', 'required|callback_alpha_dash_space');
		$this->form_validation->set_rules('last_name','Last name', 'required|callback_alpha_dash_space');
		$this->form_validation->set_rules('phno', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
		//echo $checkUser.'assad'; //exit; 
		if($this->input->post('phno') == '0000000000'){
			$this->messages->setMessage('Mobile Number should not be accept all zero numbers','error');
			redirect('administrator/myProfile');
		}else{ 
			if($this->form_validation->run() == FALSE){ 
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				$data['first_name'] = $this->input->post('first_name');
				$data['last_name'] = $this->input->post('last_name');
				$data['phno'] = $this->input->post('phno');
				//$data['user_type'] = $this->input->post('user_type');
				$this->Common_model->updateDataFromTable('tbl_users',$data,'id',$this->session->userdata('id'));
				$this->messages->setMessage('Profile Updated Successfully','success');
			}
			redirect('administrator/myProfile');
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
