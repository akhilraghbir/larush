<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockTransfer extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-user";
		$data['title']="Stock Transfer";
		$data['helptext']="This Page Is Used To Manage The Stock Transfer.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'StockTransfer/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'StockTransfer';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/stocktransfer',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
		$data['products'] = $this->Common_model->getDataFromTable('tbl_products','',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/stocktransfer',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('source_warehouse_id','destination_warehouse_id','product_id','quantity');    
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
				unset($data['available_quantity']);
				$data['created_by'] = $this->session->id;
				$stockDebit['created_on'] = $stockCredit['created_on'] = $data['created_on'] = current_datetime();
				$stockDebit['product_id'] = $stockCredit['product_id'] = $data['product_id'];
				$stockDebit['quantity'] = $stockCredit['quantity'] = $data['quantity'];
				$stockCredit['type'] = 'Transfer_Credit';
				$stockCredit['warehouse_id'] = $data['destination_warehouse_id'];
				$stockDebit['type'] = 'Transfer_Debit';
				$stockDebit['warehouse_id'] = $data['source_warehouse_id'];
				$id = $this->Common_model->addDataIntoTable('tbl_stock_transfer',$data);
				if($id){
					$stockDebit['reference_id'] = $stockCredit['reference_id'] = $id;
					$this->Common_model->addDataIntoTable('tbl_stock_entries',$stockDebit);
					$this->Common_model->addDataIntoTable('tbl_stock_entries',$stockCredit);
				}
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Stock Transfered Successfully','success');
				redirect('administrator/StockTransfer');
			}
		}
			$this->loadUserForm(array(),'add');
	}

	public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('business_name','contact_person_name','contact_person_number','address','purpose','visited_date');    
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
				$this->Common_model->updateDataFromTable('tbl_leads',$data,'id',$param1);
				$this->messages->setMessage('Lead Updated Successfully','success');
				redirect(base_url('administrator/Leads'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_leads','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];	
		}
		$this->loadUserForm($formData, 'edit');
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$indexColumn ='tst.id';
		$selectColumns = ['tst.*','tsw.warehouse_name as s_warehouse_name','tdw.warehouse_name as d_warehouse_name','tp.product_name'];
		$dataTableSortOrdering = ['tst.id','tsw.warehouse_name as s_warehouse_name','tdw.warehouse_name as d_warehouse_name','tp.product_name','tst.quantity','tst.created_on'];
		$table_name ='tbl_stock_transfer as tst';
		$joinsArray[] = ['table_name'=>'tbl_products as tp','condition'=>"tp.id = tst.product_id",'join_type'=>'left'];;
		$joinsArray[] = ['table_name'=>'tbl_warehouses as tsw','condition'=>"tsw.id = tst.source_warehouse_id",'join_type'=>'left'];;
		$joinsArray[] = ['table_name'=>'tbl_warehouses as tdw','condition'=>"tdw.id = tst.destination_warehouse_id",'join_type'=>'left'];;
		$wherecondition='tst.id!="0"';
		$getRecordListing=$this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords=$getRecordListing['recordsTotal'];
		$recordsFiltered=$getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i=0;		
		
        $srNumber=$start;	
    
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing['data'] as $recordData) {
				$content .='[';
				$recordListing[$i][0]= $i+1;
                $recordListing[$i][1]= $recordData->s_warehouse_name;
                $recordListing[$i][2]= $recordData->d_warehouse_name;
				$recordListing[$i][3]= $recordData->product_name;
                $recordListing[$i][4]= $recordData->quantity;
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
