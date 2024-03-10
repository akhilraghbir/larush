<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-bell";
		$data['title'] = "Attendance";
		$data['helptext'] = "This Page Is Used To Manage The Attendance.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = '';
		return $data;
	}

	public function index($id = ''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		if($id!=''){
		    $where['id'] = $id;
		}
		$this->home_template->load('home_template','admin/attendance',$data);   
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$indexColumn = 'ta.id';
		$selectColumns = ['ta.id','ta.date','ta.clock_in','ta.clock_out'];
		$dataTableSortOrdering = ['ta.id','ta.date','ta.clock_in','ta.clock_out'];
		$table_name='tbl_attendance as ta';
		$joinsArray = [];
		
		$wherecondition = 'ta.id!="0" and ta.user_id='.$this->session->id;
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
				$action='';
				$content .='[';
                $recordListing[$i][0]= ++$j;
                $recordListing[$i][1]= $recordData->date;
                $recordListing[$i][2]= $recordData->clock_in;
				$recordListing[$i][3]= $recordData->clock_out;
				$recordListing[$i][4]= $recordListing[$i][4]= timeDifference($recordData->clock_in,$recordData->clock_out);
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

    
    public function clockinout(){
		$type = $this->input->post('type');
        $where['date'] = date("Y-m-d");
        $where['user_id'] = $this->session->id;
        $count = $this->Common_model->check_exists('tbl_attendance',$where,'','','');
		if($type == 'Clock In'){
            if($count == 0){
                $data['date'] = date("Y-m-d");
                $data['user_id'] = $this->session->id;
                $data['clock_in'] = current_datetime();
                $this->Common_model->addDataIntoTable('tbl_attendance',$data);
                $message = ['error'=>'0','message'=>'You are logged in successfully'];
            }else{
                $message = ['error'=>'1','message'=>'Already logged in today'];
            }
		}else if($type == 'Clock Out'){
            if($count==1){
                $data['clock_out'] = current_datetime();
                $this->Common_model->updateDataFromTable('tbl_attendance',$data,$where,'');
                $message = ['error'=>'0','message'=>'You are logged out successfully'];
            }else{
                $message = ['error'=>'1','message'=>'You are not logged in today'];
            }
		}
        echo json_encode($message);
        exit;
	}
}
?>