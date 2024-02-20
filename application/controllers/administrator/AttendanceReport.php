<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceReport extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Attendance Report";
		$data['helptext'] = "This Page Is Used To Manage The Attendance Report.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$data['employees'] = $this->Common_model->getDataFromTable('tbl_users','',  $whereField=['status'=>'Active','user_type'=>'Employee'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/attendance_report',$data);   
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
        $date         = $this->input->post('date');
        $user_id         = $this->input->post('user_id');
		$indexColumn = 'ta.id';
		$selectColumns = ['ta.id','ta.date','ta.clock_in','ta.clock_out'];
		$dataTableSortOrdering = ['ta.id','ta.date','ta.clock_in','ta.clock_out'];
		$table_name='tbl_attendance as ta';
		$joinsArray = [];
		$wherecondition = 'ta.id!="0"';
        if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date between '$fromDate' and '$toDate' ";
        }
        if($user_id!=''){
            $wherecondition.=" and user_id=".$user_id;
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
                $recordListing[$i][1]= $recordData->date;
                $recordListing[$i][2]= $recordData->clock_in;
				$recordListing[$i][3]= $recordData->clock_out;
				$recordListing[$i][4]= timeDifference($recordData->clock_in,$recordData->clock_out);
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