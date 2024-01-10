<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data = [];
		$data['icon_class']="icon-user";
		$data['title']="Expenses";
		$data['helptext']="This Page Is Used To Manage The Expenses.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'expenses/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'expenses';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$data['categories'] = $this->Common_model->getDataFromTable('tbl_categories','',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/expenses',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
		$data['categories'] = $this->Common_model->getDataFromTable('tbl_categories','',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/expenses',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('expense_category','expense_purpose','expense_date','amount','expense_receipt');    
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
				$user_id = $this->Common_model->addDataIntoTable('tbl_expenses',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Expense Created Successfully','success');
				redirect('administrator/expenses');
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
		$status         =  $this->input->post('status');
		$expense_category = $this->input->post('expense_category');
		$indexColumn ='te.id';
		$selectColumns = ['te.id','tec.category','expense_purpose','amount','expense_date','te.created_on','te.status','te.created_by','te.expense_receipt'];
		$dataTableSortOrdering = ['te.id','tec.category','expense_purpose','amount','expense_date','te.created_on','te.status','te.created_by'];
		$table_name ='tbl_expenses as te';
		$joinsArray[] = ['table_name'=>'tbl_categories as tec','condition'=>"tec.id = te.expense_category",'join_type'=>'left'];;
		$wherecondition='te.id!="0"';
		if($status=='Active'){
		    $wherecondition.=' and te.status = "Active"';
		}else if($status=='Inactive'){
		    $wherecondition.=' and te.status = "Inactive"';
		}
		if($this->session->user_type == 'Employee'){
			$wherecondition.= ' and te.created_by = '.$this->session->id;
		}
		if($expense_category!='All'){
			$wherecondition.= 'and te.expense_category='.$expense_category;
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
				if($recordData->status == 'Inactive'){
					$recordListing[$i][6]= '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				}else{
					$recordListing[$i][6]= '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
				if($this->session->userdata('user_type') == 'Admin'){	
					if($recordData->status == 'Inactive'){
						$action.= '<a class="btn" title="Active" onclick="statusUpdate(this,'."'$recordData->id'".','."'Active'".')" style="margin-bottom: 2px;color:green;font-size: 16px;cursor:pointer;"><i class="ri-check-line"></i></a>';
					}else{
						$action.= '<a class="btn" title="Deactive" onclick="statusUpdate(this,'."'$recordData->id'".','."'Inactive'".')" style="margin-bottom: 2px;color:red;font-size: 16px;cursor:pointer;"><i class="ri-close-line"></i></a>';
					}
				}
				$recordListing[$i][7]= $action;
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
