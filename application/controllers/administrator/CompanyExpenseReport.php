<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyExpenseReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Company Expense Report";
		$data['helptext'] = "This Page Is Used To Manage The Company Expense Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/company_expense_report',$data);   
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$date         =  $this->input->post('date');
		$indexColumn ='te.id';
		$selectColumns = ['te.id','tec.category','expense_purpose','amount','expense_date','te.created_on','te.status','te.created_by','te.expense_receipt'];
		$dataTableSortOrdering = ['te.id','tec.category','expense_purpose','amount','expense_date','te.created_on','te.status','te.created_by'];
		$table_name ='tbl_expenses as te';
		$joinsArray[] = ['table_name'=>'tbl_categories as tec','condition'=>"tec.id = te.expense_category",'join_type'=>'left'];;
		$wherecondition='te.role!="Employee"';
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and te.expense_date between '$fromDate' and '$toDate' ";
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