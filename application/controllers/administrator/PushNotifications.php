<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PushNotifications extends CI_Controller {
	public function __construct(){
		parent::__construct();
		check_UserSession();
		$this->load->model('Datatables_model');
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-bell";
		$data['title']="Push Notifications";
		$data['helptext']="This Page Is Used To Manage The PushNotifications.";
		$data['actions']['add']='';
		$data['actions']['list']='';
		return $data;
	}
	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();  
		$this->home_template->load('home_template','admin/pushnotification',$data);   
	}
	public function add(){
    	if(($this->input->post('add'))){
    		$this->form_validation->checkXssValidation($this->input->post());
    		$mandatoryFields=array('title','message','to');  
            foreach($mandatoryFields as $row){
    			$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
    			$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
            if($this->input->post('to') == 'Only'){
                $this->form_validation->set_rules('user_id','User','required'); 
            }
            if($this->form_validation->run() == FALSE){
    			$this->form_validation->set_session_data($this->input->post());
    			$errorMessage = validation_errors();
    			$this->messages->setMessage($errorMessage,'error');
    			redirect(base_url('administrator/PushNotifications'));
    		}else{
    			if($this->input->post('to') == 'Only'){
    			    $where['id'] = $this->input->post('user_id');
    			}else if($this->input->post('to') == 'Individual'){
    			    $where['client_type'] = 'Individual';
    			}else if($this->input->post('to') == 'Firm'){
    			    $where['client_type'] = 'Firm';
    			}else{
    			    $where['user_type'] = 'Client';
    			}
    			$where['status'] = 'Active';
				//$where['device_token!'] = '';
    			$tokens = $this->Common_model->getDataFromTable('tbl_users','id,device_token', $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
    			foreach($tokens as $token){
    			    $allTokens[] = $token['device_token'];
    			}
    			if(count($allTokens)>0){
                    // $nmessage = array(
                    //     "title" => $this->input->post('title'),
                    //     "message" => $this->input->post('message'),
					// 	"data"=> array(
					// 		"click_action"=> "FLUTTER_NOTIFICATION_CLICK",
					// 		"sound"=> "default", 
					// 		 "status"=> "done"
					// 	),
					// 	'priority'      =>'high'
                    // );
					$nmessage = array(
						'body'   => $this->input->post('message'),
						'title'     => $this->input->post('title'),
					);
                    $res =	$this->Common_model->firebase_notification(implode(",",$allTokens),$nmessage);
                    $this->messages->setMessage('Notification Send Successfully','success');
                    redirect(base_url('administrator/PushNotifications'));
    			}
    		}
    	}
    }   
}