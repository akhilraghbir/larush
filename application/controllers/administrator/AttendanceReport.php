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
		$selectColumns = ['ta.id','tu.first_name','ta.date','ta.clock_in','ta.clock_out'];
		$dataTableSortOrdering = ['ta.id','tu.first_name','ta.date','ta.clock_in','ta.clock_out'];
		$table_name='tbl_attendance as ta';
		$joinsArray[] = ['table_name'=>'tbl_users as tu','condition'=>"tu.id = ta.user_id",'join_type'=>'left'];
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
				$recordListing[$i][1]= $recordData->first_name;
                $recordListing[$i][2]= $recordData->date;
                $recordListing[$i][3]= $recordData->clock_in;
				$recordListing[$i][4]= $recordData->clock_out;
				$recordListing[$i][5]= timeDifference($recordData->clock_in,$recordData->clock_out);
				$recordListing[$i][6]= '<a href="javascript:void()" onclick="getAttendance('.$recordData->id.')"><i class="ri-timer-line"></i></a>';
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

	public function getAttendance(){
		if($_POST['id']){
			$data = $this->Common_model->getDataFromTable('tbl_attendance','',  $whereField=['id'=>$_POST['id']], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
			if(is_array($data) && count($data)>0){
				$res['error'] = 0;
				$res['data'] = $data[0];
			}else{
				$res['error'] = 1;
				$res['msg'] = 'No Data Found';
			}
			echo json_encode($res);exit;
		}
	}

	public function updateAttendance(){
		if($_POST){
			$mandatoryFields= ['clock_in','clock_out','date'];    
            foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
            if($this->form_validation->run() == FALSE){
				$errorMessage=validation_errors();
				$res['error'] = 1;
				$res['msg'] = $errorMessage;
			}else{
				$id = $_POST['id'];
				$update['clock_in'] = $_POST['clock_in'];
				$update['clock_out'] = $_POST['clock_out'];
				$upd = $this->Common_model->updateDataFromTable('tbl_attendance',$update,'id',$id);
				if($upd){
					$res['error'] = 0;
					$res['msg'] = 'Attendance Updated successfully';
				}
			}
			echo json_encode($res);exit;
		}
	}
}
?>