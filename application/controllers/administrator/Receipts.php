<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipts extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-user";
		$data['title'] = "Receipts";
		$data['helptext'] = "This Page Is Used To Manage The Receipts.";
		$data['actions']['add'] = CONFIG_SERVER_ADMIN_ROOT.'receipts/add';
		$data['actions']['list'] = CONFIG_SERVER_ADMIN_ROOT.'receipts';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/receipts',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
        $data['suppliers'] = $this->Common_model->getDataFromTable('tbl_suppliers','id,supplier_name,company_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
        if($this->session->warehouse_id == 0){
            $data['warehouses'] = $this->Common_model->getDataFromTable('tbl_warehouses','id,warehouse_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
        }
		$this->home_template->load('home_template','admin/receipts',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('warehouse_id','supplier_id','receipt_date');    
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
				
				if(!isset($data['product_id'])){
					$this->messages->setMessage('Please select atleast one product','error');
				}else{
					$products = $data['product_id'];
					$qty = $data['qty'];
					$price = $data['price'];
					$total = $data['total'];
					unset($data['product_id']);
					unset($data['add']);
					unset($data['qty']);
					unset($data['price']);
					unset($data['total']);
					$data['receipt_number'] = 'R'.time();
					$data['created_on'] = current_datetime();
					$data['created_by'] = $this->session->id;
					$purchaseId = $this->Common_model->addDataIntoTable('tbl_purchases',$data);
					for($i=0;$i<count($products);$i++){
						$purchaseItems['purchase_id'] = $purchaseId;
						$purchaseItems['warehouse_id'] = $data['warehouse_id'];
						$purchaseItems['product_id'] = $products[$i];
						$purchaseItems['quantity'] = $qty[$i];
						$purchaseItems['price'] = $price[$i];
						$purchaseItems['total'] = $total[$i];
						$this->Common_model->addDataIntoTable('tbl_purchase_items',$purchaseItems);
					}
					$this->form_validation->clear_field_data();
					$this->messages->setMessage('Receipt Created Successfully','success');
					redirect('administrator/Receipts');
				}
				
			}
		}
			$this->loadUserForm(array(),'add');
	}



	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$indexColumn = 'tr.id';
		$selectColumns = ['tr.id','tr.receipt_number','ts.supplier_name','tr.grand_total','tr.receipt_date'];
		$dataTableSortOrdering = ['tr.receipt_number','ts.supplier_name','tr.grand_total','tr.receipt_date'];
		$table_name = 'tbl_purchases as tr';
		$joinsArray[] = ['table_name'=>'tbl_suppliers as ts','condition'=>"ts.id = tr.supplier_id",'join_type'=>'left'];;
		$wherecondition = 'tr.id!="0"';
		$getRecordListing = $this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords = $getRecordListing['recordsTotal'];
		$recordsFiltered = $getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i=0;		
        $srNumber=$start;	
        if(!empty($getRecordListing)) {
            foreach($getRecordListing['data'] as $recordData) {
				$action="";
				$content .='[';
				$recordListing[$i][0]= $i+1;
                $recordListing[$i][1]= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'receipt/print/'.$recordData->id.'" class="text-info" onclick="getDetails('.$recordData->id.')">'.$recordData->receipt_number.'</a>';
                $recordListing[$i][2]= $recordData->supplier_name;
                $recordListing[$i][3]= $recordData->grand_total;
                $recordListing[$i][4]= displayDateInWords($recordData->receipt_date);
				$action.= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'receipts/print/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="ri-printer-fill" aria-hidden="true"></i></a>';
				$recordListing[$i][5]= $action;
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
	

	public function print($id = ''){
		if($id!=''){
			$data['settings'] = $this->Common_model->getDataFromTable('tbl_settings','',  $whereField='', $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
			$data['purchase'] = $this->Common_model->getDataFromTable('tbl_purchases','',  $whereField='id', $whereValue=$id, $orderBy='', $order='', $limit='', $offset=0, true);
			$data['purchase_items'] = $this->Common_model->getDataFromTable('tbl_purchase_items','',  $whereField='purchase_id', $whereValue=$id, $orderBy='', $order='', $limit='', $offset=0, true);
			$this->load->view('admin/invoice',$data);
		}else{
			redirect(base_url('receipts'));
		}
	}

}
