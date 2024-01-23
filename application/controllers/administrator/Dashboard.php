<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_UserSession();
		$this->load->model('Datatables_model');
	}
	
	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-home";
		$data['title'] = "Dashboard";
		$data['helptext'] = "This Page Is Used To Manage The Dashboard.";
		return $data;
	}

    public function index()
	{
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
        if($this->session->user_type == 'Employee'){
            $data['tasks'] = $this->Common_model->getDataFromTable('tbl_tasks','',  $whereField=['employee_id'=>$this->session->id,'status'=>'Pending'], $whereValue='', $orderBy='priority', $order='desc', $limit='', $offset=0, true);
        }
		$this->home_template->load('home_template','admin/dashboard',$data);
    }
    
    public function getDashboardCounts(){
        if($_POST['role'] == 'Admin' || $_POST['role'] == 'Master'){
            $data['total_clients'] = $this->Common_model->check_exists('tbl_users',['status' => 'Active','user_type' => 'Client'],'','','');
            $data['total_users'] = $this->Common_model->check_exists('tbl_users',['status' => 'Active','user_type!=' => 'Client'],'','','');
            $data['total_bills'] = $this->Common_model->check_exists('tbl_bills',['status' => 'Active'],'','','');
            $data['total_tforms'] = $this->Common_model->check_exists('tbl_tforms',['status' => 'Active'],'','','');
            echo json_encode($data);
        }else if($_POST['role'] == 'Accountant'){
            $data['total_clients'] = $this->Common_model->check_exists('tbl_users',['status' => 'Active','user_type' => 'Client','accountant' => $this->session->id],'','','');
            $data['total_bills'] = $this->Common_model->check_exists('tbl_bills',['status' => 'Active'],'','','');
            $data['total_tforms'] = $this->Common_model->check_exists('tbl_tforms',['status' => 'Active'],'','','');
            echo json_encode($data);
        }
    }
    
    public function getNotifCount(){
        $where['reciever'] = $this->session->id;
        $where['status'] = 'Active';
        try{
            $data = $this->Common_model->getDataFromTable('tbl_notifications','id', $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
            if(!empty($data)){
                $response = ['error' => '0','count' => count($data)];
            }else{
                $response = ['error' => '0','count' => '0'];
            }
            echo json_encode($response);
        }catch(Exception $e){
            $response = ['error' => '1','message' => $e->getMessage()];
            echo json_encode($response);
        }
    }
    
    public function getNotifications(){
        $where['reciever'] = $this->session->id;
        $where['status'] = 'Active';
        try{
            $html = '';
            $data = $this->Common_model->getDataFromTable('tbl_notifications','id,notif_message,message,created_on', $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
            if(!empty($data)){
                foreach($data as $d){
                    $html.='<li>
                            <a href="'.base_url('administrator/notifications/index/'.$d['id']).'"><div class="media">
                                <div class="media-body">
                                   <h5 class="notification-user">'.$d['notif_message'].'</h5>
                                        <p class="notification-msg">'.substr($d['message'],0,30).'..</p>
                                    <span class="notification-time">'.$d['created_on'].'</span>
                                </div>
                            </div></a>
                        </li>';
                }
                $html.='<li><a href="'.base_url('administrator/notifications/index/').'" class="view_all">View All Notifications</a></li>';
            }else{
                $html.='<li>
                            <div class="media">
                                <div class="media-body">
                                    <p class="notification-msg">No Notification Found.</p>
                                </div>
                            </div>
                        </li>';
            }
            $response['error'] = 0;
            $response['html'] = $html;
            echo json_encode($response);
        }catch(Exception $e){
            $response = ['error' => 1,'message' => $e->getMessage()];
            echo json_encode($response);
        }
    }
    
    public function getDonut(){
        $financialYear = $this->Common_model->getFinancialYear('id')['id'];
        if($_POST['get'] == 'expense-donut'){
            $data = $this->db->query("SELECT sum(bill_amount) as value,category_name as label FROM `tbl_bills` LEFT JOIN tbl_categories on tbl_categories.id = tbl_bills.category_id where financial_year = '$financialYear' and bill_type = 'Expense' GROUP by category_id")->result();
            if(count($data)>0){
                $res['error'] = 0;
                $res['data'] = $data;
                $res['colors'] = RAND_COLORS;
            }else{
                $res['error'] = 1;
            }
            echo json_encode($res);
        }else if($_POST['get'] == 'income-donut'){
            $data = $this->db->query("SELECT sum(bill_amount) as value,category_name as label FROM `tbl_bills` LEFT JOIN tbl_categories on tbl_categories.id = tbl_bills.category_id where financial_year = '$financialYear' and bill_type = 'Income' GROUP by category_id")->result();
            if(count($data)>0){
                $res['error'] = 0;
                $res['data'] = $data;
                $res['colors'] = RAND_COLORS;
            }else{
                $res['error'] = 1;
            }
            echo json_encode($res);
        }
    }
    
    public function areaGraph(){
        $financialYear = $this->Common_model->getFinancialYear('id')['id'];
        if($_POST['get'] == 'areaGraph'){
            $data = $this->db->query("SELECT month(created_on) as period,sum(case when bill_type = 'Expense' then bill_amount else 0 end) as Expenses,sum(case when bill_type = 'Income' then bill_amount else 0 end) as Income  from tbl_bills where financial_year = '$financialYear' GROUP by month(created_on)")->result_array();
            if(count($data)>0){
                $res['error'] = 0;
                $res['data'] = $data;
            }else{
                $res['error'] = 0;
                $res['data'] = [];
            }
            echo json_encode($res);
        }else{
            $data = $this->db->query("SELECT month(created_on) as Month,count(case when bill_type = 'Expense' then bill_amount else 0 end) as Expense,count(case when bill_type = 'Income' then bill_amount else 0 end) as Income  from tbl_bills where financial_year = '$financialYear' GROUP by month(created_on)")->result_array();
            if(count($data)>0){
                $res['error'] = 0;
                $res['data'] = $data;
            }else{
                $res['error'] = 0;
                $res['data'] = [];
            }
            echo json_encode($res);
        }
    }

    public function updateTask(){
		$u_id = $this->input->post('tid');
        $data['status'] = 'Completed';
        $data['completed_on'] = current_datetime();
        $succ_message = 'Task Updated Successfully';		
		$this->Common_model->updateDataFromTable('tbl_tasks',$data,'id',$u_id);
		$message = ['error'=>'0','message'=>$succ_message,'id'=>$u_id];
        echo json_encode($message);
        exit;
	}
    // public function checkSession(){
	// 	if($_POST){
	// 		if($this->session->userdata('is_login') != TRUE){
	// 			$res['status'] = 'error';
	// 			$res['msg'] = 'Session Expired';
	// 		}else if($this->session->id!=''){
	// 			$existsession = $this->Common_model->getDataFromTable('tbl_user_sessions','user_id,token',  $whereField='user_id', $whereValue=$this->session->id, $orderBy='id', $order='desc', $limit='', $offset=0, true);
	// 			if($existsession[0]['token'] != $this->session->session_token){
	// 				$res['status'] = 'error';
	// 				$res['msg'] = 'Login detected in another session';
	// 			}else{
	// 				$res['status'] = 'success';
	// 				$res['msg'] = 'session is continuing';
	// 			}
	// 		}
	// 		echo json_encode($res);
	// 	}
	// }
    
}
?>