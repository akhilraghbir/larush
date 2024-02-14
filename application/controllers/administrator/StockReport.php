<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockReport extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data = [];
		$data['icon_class'] = "icon-user";
		$data['title'] = "Stock Report";
		$data['helptext'] = "This Page Is Used To Manage The Stock Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$data['warehouses'] = $this->Common_model->getDataFromTable('tbl_warehouses','id,warehouse_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/stock_report',$data);   
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$warehouse     =  $this->input->post('warehouse');
		if($warehouse=='All'){
			$warehouse = '';
		}
        $stockqty  = $this->Common_model->getStockQty('',$warehouse);
		$indexColumn ='tp.id';
		$selectColumns = ['tp.id','product_name','tu.unit_name'];
		$dataTableSortOrdering = ['tp.id','product_name','tu.unit_name','tp.id'];
		$table_name ='tbl_products as tp';
		$joinsArray[] = ['table_name'=>'tbl_units as tu','condition'=>"tu.id = tp.units",'join_type'=>'left'];;
		$wherecondition = 'tp.id!="0"';
		$getRecordListing = $this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords = $getRecordListing['recordsTotal'];
		$recordsFiltered = $getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i=0;
        $srNumber = $start;	
        if(!empty($getRecordListing)) {
            foreach($getRecordListing['data'] as $recordData) {
				$content.='[';
				$recordListing[$i][0]= $i+1;
                $recordListing[$i][1]= $recordData->product_name;
                $recordListing[$i][2]= $recordData->unit_name;
                $recordListing[$i][3]= (isset($stockqty[$recordData->id])) ? $stockqty[$recordData->id] : '0.00';
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

}
