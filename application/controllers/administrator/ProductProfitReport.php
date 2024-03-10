<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductProfitReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Product Wise Profit Report";
		$data['helptext'] = "This Page Is Used To Manage The Product Wise Profit Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
        $data['products'] = $this->Common_model->getDataFromTable('tbl_products','',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$date = '';
		if($_POST){
			$date = $_POST['date'];
		}
        $data['productsales'] = $this->Common_model->productsSales($date);
        $data['productpurchase'] = $this->Common_model->productsPurchase($date);
		$this->home_template->load('home_template','admin/product_profit_report',$data);   
	}

}
?>