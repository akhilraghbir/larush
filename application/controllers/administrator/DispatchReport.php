<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DispatchReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Dispatch Report";
		$data['helptext'] = "This Page Is Used To Manage The Dispatch Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/dispatch_report',$data);   
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$selectedmonth         =  $this->input->post('month');
		$user_id = $this->input->post('user_id');
		$indexColumn ='te.id';
		$selectColumns = ['te.id','tec.category','expense_purpose','amount','expense_date','te.created_on','te.status','te.created_by','te.expense_receipt'];
		$dataTableSortOrdering = ['te.id','tec.category','expense_purpose','amount','expense_date','te.created_on','te.status','te.created_by'];
		$table_name ='tbl_expenses as te';
		$joinsArray[] = ['table_name'=>'tbl_categories as tec','condition'=>"tec.id = te.expense_category",'join_type'=>'left'];;
		$wherecondition='te.role="Employee"';
		if($selectedmonth!=''){
            $selectedmonth = explode("-",$selectedmonth);
            $month = $selectedmonth[1];
            $year = $selectedmonth[0];
            $wherecondition.=" and month(expense_date)='$month' and year(date)='$year'";
        }
        if($user_id!=''){
            $wherecondition.=" and te.created_by=".$user_id;
        }
		$getRecordListing=$this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords=$getRecordListing['recordsTotal'];
		$recordsFiltered=$getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i=0;		
		
        $srNumber=$start;	
    
        if(!empty($getRecordListing)) {
            $action = '';
            foreach($getRecordListing['data'] as $recordData) {
				$action="";
				$content .='[';
				$recordListing[$i][0]= $i+1;
                $recordListing[$i][1]= $recordData->category;
                $recordListing[$i][2]= '<a target="_blank" href="'.CONFIG_SERVER_ROOT.$recordData->expense_receipt.'" class="text-info">'.$recordData->expense_purpose.'</a>';
				$recordListing[$i][3]= floatval($recordData->amount);
				$recordListing[$i][4]= displayDateInWords($recordData->expense_date);
                $recordListing[$i][5]= displayDateInWords($recordData->created_on);
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