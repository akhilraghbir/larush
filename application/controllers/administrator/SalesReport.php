<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Sales  Report";
		$data['helptext'] = "This Page Is Used To Manage The Sales Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$data['buyers'] = $this->Common_model->getDataFromTable('tbl_buyers','',  $whereField=['status'=>'Active'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/sales_report',$data);   
	}

	public function getReport(){
		$selectedmonth  =  $this->input->post('month');
		$buyer_id = $this->input->post('buyer_id');
		$wherecondition = " id!=0";
		if(!empty($buyer_id)){
			$wherecondition.= ' and buyer_id='.$buyer_id;
		}
		if($selectedmonth!=''){
            $selectedmonth = explode("-",$selectedmonth);
            $month = $selectedmonth[1];
            $year = $selectedmonth[0];
            $wherecondition.=" and month(created_on)='$month' and year(created_on)='$year'";
        }
		$sql = "select id from tbl_invoices where ".$wherecondition;
		$res = $this->db->query($sql)->result_array();
		$ids  = join("','",array_column($res,'id'));
		$invoices = "select product_id,sum(quantity) as qty from tbl_invoice_items where invoice_id in ('$ids') group by product_id";
		$report = $this->db->query($invoices)->result_array();
		if(is_array($report) && count($report)>0){
			$products = $this->Common_model->getDataFromTable('tbl_products','',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
			$productsArray = array_column($products,'product_name','id');
			$html='';
			$i = 0;
			foreach($report as $resp){
				$html.="<tr>";
				$html.="<td>".++$i."</td>";
				$html.="<td>".$productsArray[$resp['product_id']]."</td>";
				$html.="<td>".$resp['qty']."</td>";
				$html.="</tr>";
			}
			$response['html'] = $html;
			$response['error'] = 0;
		}else{
			$response['html'] = '';
			$response['error'] = 1;
		}
		echo json_encode($response);
	}
}
?>