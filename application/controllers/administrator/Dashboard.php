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
        }else{
            $data['employees'] = $this->Common_model->check_exists('tbl_users',['user_type'=>'Employee'],'','','');
            $data['buyers'] = $this->Common_model->check_exists('tbl_suppliers',['status'=>'Active'],'','','');
            $data['suppliers'] = $this->Common_model->check_exists('tbl_buyers',['status'=>'Active'],'','','');
            $data['products'] = $this->Common_model->check_exists('tbl_products',['status'=>'Active'],'','','');
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
        $where['employee_id'] = $this->session->id;
        $where['status'] = 'Not Seen';
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
        $where['employee_id'] = $this->session->id;
        $where['status'] = 'Not Seen';
        try{
            $html = '';
            $data = $this->Common_model->getDataFromTable('tbl_notifications','id,title,notif_description,created_on', $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
            if(!empty($data)){
                foreach($data as $d){
                    $html.='<a href="#" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="ri-information-line"></i>
                                    </span>
                                </div>
                                <div class="flex-1">
                                <h6 class="mb-1">'.$d['title'].'</h6>
                                <p class="mb-1">'.substr($d['notif_description'],0,30).'..</p>
                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i>'.$d['created_on'].'</p>
                                </div>
                            </div>
                            </a>';
                }
            }else{
                $html.='<a href="#" class="text-reset notification-item">
                <div class="d-flex">
                    <div class="flex-1">
                        <p class="mb-1">No Notifications...</p>
                    </div>
                </div>
                </a>';
            }
            $response['error'] = 0;
            $response['html'] = $html;
            echo json_encode($response);
        }catch(Exception $e){
            $response = ['error' => 1,'message' => $e->getMessage()];
            echo json_encode($response);
        }
    }
    
    public function getSalesReport(){
        $date = $this->input->post('date');
		$ferousproducts = $this->Common_model->getDataFromTable('tbl_products','id',  $whereField=['is_catalytic'=>'No','is_ferrous'=>'Yes'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$nonferousproducts = $this->Common_model->getDataFromTable('tbl_products','id',  $whereField=['is_catalytic'=>'No','is_ferrous'=>'No'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$ferrousIds = join("','",array_column($ferousproducts,'id'));
		$nonferrousIds = join("','",array_column($nonferousproducts,'id'));
		$wherecondition = '';
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date(created_on) between '$fromDate' and '$toDate' ";
        }
		$ferrousInvoices = $this->db->query("select sum(quantity) as totqty from tbl_invoice_items where product_id in ('$ferrousIds') $wherecondition")->result_array();
		$nonferrousInvoices = $this->db->query("select sum(quantity) as totqty from tbl_invoice_items where product_id in ('$nonferrousIds') $wherecondition")->result_array();
		$data['error'] = 0;
		$res[] = (float)$ferrousInvoices[0]['totqty'];
		$res[] = (float)$nonferrousInvoices[0]['totqty'];
		$data['data'] = $res;
		echo json_encode($data);exit;
    }

    public function getPurchaseReport(){
        $date = $this->input->post('date');
		$ferousproducts = $this->Common_model->getDataFromTable('tbl_products','id',  $whereField=['is_catalytic'=>'No','is_ferrous'=>'Yes'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$nonferousproducts = $this->Common_model->getDataFromTable('tbl_products','id',  $whereField=['is_catalytic'=>'No','is_ferrous'=>'No'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$ferrousIds = join("','",array_column($ferousproducts,'id'));
		$nonferrousIds = join("','",array_column($nonferousproducts,'id'));
		$wherecondition = '';
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date(created_on) between '$fromDate' and '$toDate' ";
        }
		$ferrousInvoices = $this->db->query("select sum(quantity) as totqty from tbl_purchase_items where product_id in ('$ferrousIds') $wherecondition")->result_array();
		$nonferrousInvoices = $this->db->query("select sum(quantity) as totqty from tbl_purchase_items where product_id in ('$nonferrousIds') $wherecondition")->result_array();
		$data['error'] = 0;
		$res[] = (float)$ferrousInvoices[0]['totqty'];
		$res[] = (float)$nonferrousInvoices[0]['totqty'];
		$data['data'] = $res;
		echo json_encode($data);exit;
    }

    public function getSalesVsPurchases(){
        $date = $this->input->post('date');
        $wherecondition = '';
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" date(created_on) between '$fromDate' and '$toDate' ";
        }
        $sales = $this->db->query("select sum(grand_total) as sale_total from tbl_invoices where $wherecondition")->result_array();
		$purchases = $this->db->query("select sum(grand_total) as purchase_total from tbl_purchases where $wherecondition")->result_array();
        $data['error'] = 0;
		$res[] = (float)$sales[0]['sale_total'];
		$res[] = (float)$purchases[0]['purchase_total'];
		$data['data'] = $res;
		echo json_encode($data);exit;
    }

    public function getExpenses(){
        $date = $this->input->post('date');
        $wherecondition = '';
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" date(expense_date) between '$fromDate' and '$toDate' ";
        }
        $expenseCategories = $this->Common_model->getDataFromTable('tbl_categories','id,category',  $whereField='', $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
        $expenseCategories = array_column($expenseCategories,'category','id');
        $expenses = $this->db->query("select sum(amount) as total,expense_category from tbl_expenses where role='Admin' and $wherecondition group by expense_category")->result_array();
        $i=0;
        foreach($expenses as $expense){
            $data['amount'][$i] = (float)$expense['total'];
            $data['category'][$i] = $expenseCategories[$expense['expense_category']];
            $i++;
        }
        $data['error'] = 0;
        echo json_encode($data);exit;
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

    public function ajaxListingAttendance(){
        $draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
        $today = date("Y-m-d");
		$indexColumn = 'ta.id';
		$selectColumns = ['ta.id','tu.first_name','ta.clock_in'];
		$dataTableSortOrdering = ['ta.id','tu.first_name','ta.clock_in'];
		$table_name='tbl_attendance as ta';
		$joinsArray[] = ['table_name'=>'tbl_users as tu','condition'=>"tu.id = ta.user_id",'join_type'=>'left'];
		$wherecondition = 'ta.id!="0" and ta.date="'.$today.'"';
		$getRecordListing = $this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords = $getRecordListing['recordsTotal'];
		$recordsFiltered = $getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content = '[';
		$i = $j =0;	
        $srNumber=$start;	
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing['data'] as $recordData) {
				$action='';
				$content .='[';
                $recordListing[$i][0]= ++$j;
                $recordListing[$i][1]= $recordData->first_name;
                $recordListing[$i][2]= $recordData->clock_in;
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