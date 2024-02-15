<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Purchase  Report";
		$data['helptext'] = "This Page Is Used To Manage The Purchase Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$data['suppliers'] = $this->Common_model->getDataFromTable('tbl_suppliers','',  $whereField=['status'=>'Active'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/purchase_report',$data);   
	}

	public function getReport(){
		$date  =  $this->input->post('date');
		$supplier_id = $this->input->post('supplier_id');
		$wherecondition = " id!=0";
		if(!empty($supplier_id)){
			$wherecondition.= ' and supplier_id='.$supplier_id;
		}
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date(created_on) between '$fromDate' and '$toDate' ";
        }
		$sql = "select id from tbl_purchases where ".$wherecondition;
		$res = $this->db->query($sql)->result_array();
		$ids  = join("','",array_column($res,'id'));
		$purchases = "select product_id,sum(quantity) as qty from tbl_purchase_items where purchase_id in ('$ids') group by product_id";
		$report = $this->db->query($purchases)->result_array();
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