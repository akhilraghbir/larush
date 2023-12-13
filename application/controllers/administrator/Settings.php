<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 
    
    public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-settings";
		$data['title'] = "Settings";
		$data['helptext'] = "This Page Is Used To Manage The Settings.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
		$data['settings'] = $this->Common_model->getDataFromTable('tbl_settings','',  $whereField='', $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/settings',$data); 
	}

	public function edit(){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			unset($data['edit']);
			foreach($this->input->post() as $fieldname=>$fieldvalue){
				$data['value']= $this->input->post($fieldname);
				$this->Common_model->updateDataFromTable('tbl_settings',$data,'key',$fieldname);
			}
			$this->messages->setMessage('Settings Updated Successfully','success');
			redirect(base_url('administrator/Settings'));
		}
	}
	
	// public function privacy_policy(){
	//     if($_POST){
	//         $data['description'] = $_POST['description_p'];
	//         $id = $_POST['id'];
    //         $this->Common_model->updateDataFromTable('tbl_settings',$data,'id',$id);
    //         $this->messages->setMessage('Privacy Policy Updated Successfully','success');
    //         redirect(base_url('administrator/settings/privacy_policy'));
	//     }
	// 	$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
	// 	$data['policy'] = $this->Common_model->getDataFromTable('tbl_settings','id,description', $whereField=['context' => 'privacy_policy'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
	// 	$this->home_template->load('home_template','admin/privacy_policy',$data);   
	// }
	// public function terms_and_conditions(){
	//     if($_POST){
	//         $data['description'] = $_POST['description_p'];
	//         $id = $_POST['id'];
    //         $this->Common_model->updateDataFromTable('tbl_settings',$data,'id',$id);
    //         $this->messages->setMessage('Terms And Conditions Updated Successfully','success');
    //         redirect(base_url('administrator/settings/terms_and_conditions'));
	//     }
	// 	$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
	// 	$data['terms'] = $this->Common_model->getDataFromTable('tbl_settings','id,description', $whereField=['context' => 'terms'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
	// 	$this->home_template->load('home_template','admin/terms_and_conditions',$data);   
	// }
}
?>