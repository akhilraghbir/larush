<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RoundOffReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Round Off Report";
		$data['helptext'] = "This Page Is Used To Manage The Round Off Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/roundoff_report',$data);   
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
        $date         = $this->input->post('date');
		$indexColumn = 'ta.id';
		$selectColumns = ['ta.id','ta.receipt_number','ta.final_amount','ta.created_on','ta.grand_total'];
		$dataTableSortOrdering = ['ta.id','ta.receipt_number','ta.created_on',''];
		$table_name='tbl_purchases as ta';
		$joinsArray = [];
		$wherecondition = 'ta.id!="0" and ta.final_amount!="0.00"';
        if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date(created_on) between '$fromDate' and '$toDate' ";
        }
		$getRecordListing = $this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords=$getRecordListing['recordsTotal'];
		$recordsFiltered=$getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i = $j =0;	
        $srNumber=$start;	
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing['data'] as $recordData) {
				$content .='[';
                $recordListing[$i][0]= ++$j;
                $recordListing[$i][1]= $recordData->receipt_number;
                $recordListing[$i][2]= displayDateInWords($recordData->created_on);
                $recordListing[$i][3]= number_format($recordData->final_amount - $recordData->grand_total,2);
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
?>