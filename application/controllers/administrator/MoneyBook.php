<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MoneyBook extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data = [];
		$data['icon_class']="icon-user";
		$data['title']="Money Book";
		$data['helptext']="This Page Is Used To Manage The Money Book.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'MoneyBook/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'MoneyBook';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
        $where['status'] = 'Active';
        $where['user_type'] = 'Employee';
		$data['users'] = $this->Common_model->getDataFromTable('tbl_users','',  $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/moneyBook',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
        $where['id!='] = $this->session->id;
        $where['status'] = 'Active';
		$data['users'] = $this->Common_model->getDataFromTable('tbl_users','',  $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/moneyBook',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('purpose','user_id','type','amount');    
            foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
            if($this->form_validation->run() == FALSE){
				$this->form_validation->set_session_data($this->input->post());
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				foreach($this->input->post() as $fieldname=>$fieldvalue){
                	$data[$fieldname]= $this->input->post($fieldname);
                }
                unset($data['add']);
				$data['created_by'] = $this->session->id;
				$data['created_on'] = current_datetime();
				$user_id = $this->Common_model->addDataIntoTable('tbl_moneyBook',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Record Created Successfully','success');
				redirect('administrator/moneyBook');
			}
		}
			$this->loadUserForm(array(),'add');
	}

	public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('expense_category','expense_purpose','expense_date','amount','expense_receipt');    
            foreach($mandatoryFields as $row){
            $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
            $this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
			if($this->form_validation->run() == FALSE){
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				foreach($this->input->post() as $fieldname=>$fieldvalue){
                	$data[$fieldname]= $this->input->post($fieldname);
                }
                unset($data['edit']);
				$data['updated_on'] = current_datetime();
				$this->Common_model->updateDataFromTable('tbl_expenses',$data,'id',$param1);
				$this->messages->setMessage('Expense Updated Successfully','success');
				redirect(base_url('administrator/expenses'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_expenses','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];	
		}
		$this->loadUserForm($formData, 'edit');
	}

	public function updateStatus(){
		$u_id = $this->input->post('pid');
		if($this->input->post('status') == 'Active'){
			$data['status'] = $this->input->post('status');
			$succ_message = 'Expense Actived Successfully';
		}else{
			$data['status'] = $this->input->post('status');
			$succ_message = 'Expense Inactived Successfully';
		}
		
		$this->Common_model->updateDataFromTable('tbl_expenses',$data,'id',$u_id);
		$message = ['error'=>'0','message'=>$succ_message];
        echo json_encode($message);
        exit;
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$date          =  $this->input->post('date');
		$indexColumn ='tb.id';
		$selectColumns = ['tb.id','tu.first_name','tb.purpose','tb.amount','tb.type','tb.created_on','tcu.first_name as cfirst_name'];
		$dataTableSortOrdering = ['tb.id','tb.purpose','tcu.first_name as cfirst_name','tb.amount','tb.type','tu.first_name','tb.created_on'];
		$table_name ='tbl_moneyBook as tb';
		$joinsArray[] = ['table_name'=>'tbl_users as tu','condition'=>"tu.id = tb.user_id",'join_type'=>'left'];
		$joinsArray[] = ['table_name'=>'tbl_users as tcu','condition'=>"tcu.id = tb.created_by",'join_type'=>'left'];
		$wherecondition='tb.id!="0"';
		
		if($this->session->user_type == 'Employee'){
			$wherecondition.= ' and tb.created_by = '.$this->session->id;
		}
		
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date(tb.created_on) between '$fromDate' and '$toDate' ";
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
                $recordListing[$i][1]= $recordData->purpose;
				$recordListing[$i][2]= $recordData->cfirst_name;
				$recordListing[$i][3]= floatval($recordData->amount);
				$recordListing[$i][4]= $recordData->type;
                $recordListing[$i][5]= $recordData->first_name;
                $recordListing[$i][6]= displayDateInWords($recordData->created_on);
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
