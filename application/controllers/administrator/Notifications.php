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
		$data['actions']['add'] = "";
		$data['actions']['list'] = "";
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		if($id!=''){
		    $where['id'] = $id;
		}
		$where['reciever'] = $this->session->id;
		$where['status'] = 'Active';
		$data['notifications'] = $this->Common_model->getDataFromTable('tbl_notifications','id,sender,reciever,notif_message,message,ref_id,status,created_on', $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/notifications',$data);   
	}
	
	public function updateStatus(){
	    if($_POST){
	        $id = $_POST['notifid'];
	        $status = $_POST['status'];
	        $insdata['sender'] = $_POST['reciever'];
	        $insdata['reciever'] = $_POST['sender'];
	        $insdata['ref_id'] = $_POST['refid'];
	        $insdata['created_on'] = current_datetime();
	        if($status == 'accept'){
	            $insdata['notif_message'] = 'Admin Accepted your request';
	            $insdata['message'] = 'Now you can edit the bill with Reference Number - '.$_POST['refid'];
	            $this->Common_model->updateDataFromTable('tbl_bills',['edit_option' => 'Open'],'reference_number',$_POST['refid']);
	        }else{
	            $insdata['notif_message'] = 'Admin Rejected your edit request for reference number - '.$_POST['refid'];
	            $insdata['notif_message'] = 'Please contact admin for further proceedings';
	            $this->Common_model->updateDataFromTable('tbl_bills',['edit_option' => 'Close'],'reference_number',$_POST['refid']);
	        }
	        $this->Common_model->updateDataFromTable('tbl_notifications',['status' => 'Inactive'],'id',$id);
	        $ins = $user_id = $this->Common_model->addDataIntoTable('tbl_notifications',$insdata);
	        if($ins){
	            echo json_encode(['error' => '0','message' => 'Status Updated Successfully']);
	        }else{
	            echo json_encode(['error' => '1','message' => 'Something went wrong']);
	        }exit;
	    }
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
}
?>