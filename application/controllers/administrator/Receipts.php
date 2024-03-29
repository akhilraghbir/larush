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
		$data['suppliers'] = $this->Common_model->getDataFromTable('tbl_suppliers','id,supplier_name,company_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$data['employees'] = $this->Common_model->getDataFromTable('tbl_users','id,first_name,username',  $whereField=['status'=>'Active','user_type'=>'Employee'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/receipts',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
        $data['suppliers'] = $this->Common_model->getDataFromTable('tbl_suppliers','id,supplier_name,company_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
        if($this->session->warehouse_id == 0){
            $data['warehouses'] = $this->Common_model->getDataFromTable('tbl_warehouses','id,warehouse_name,gst,pst',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
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
					$is_purchase_sale_different = $data['is_purchase_sale_different'];
					unset($data['product_id']);
					unset($data['add']);
					unset($data['qty']);
					unset($data['price']);
					unset($data['gross']);
					unset($data['tare']);
					unset($data['total']);
					unset($data['gst_per']);
					unset($data['pst_per']);
					unset($data['is_purchase_sale_different']);
					$data['receipt_number'] = 'R'.time();
					$purchaseItems['created_on'] = $stockEntry['created_on'] = $data['created_on'] = current_datetime();
					$data['created_by'] = $this->session->id;
					$purchaseId = $this->Common_model->addDataIntoTable('tbl_purchases',$data);
					for($i=0;$i<count($products);$i++){
						if($is_purchase_sale_different[$i] == 'Yes'){
							$conversion[] =  $products[$i];
						}
						$purchaseItems['purchase_id'] = $purchaseId;
						$purchaseItems['warehouse_id'] = $data['warehouse_id'];
						$stockEntry['product_id'] = $purchaseItems['product_id'] = $products[$i];
						$purchaseItems['units'] = $stockEntry['quantity'] = $purchaseItems['quantity'] = $qty[$i];
						$purchaseItems['is_purchase_sale_different'] = $is_purchase_sale_different[$i];
						$purchaseItems['price'] = $price[$i];
						$purchaseItems['total'] = $total[$i];
						$this->Common_model->addDataIntoTable('tbl_purchase_items',$purchaseItems);
						$stockEntry['warehouse_id'] = $data['warehouse_id'];
						$stockEntry['type'] = 'purchase';
						$stockEntry['reference_id'] = $purchaseId;
						$this->Common_model->addDataIntoTable('tbl_stock_entries',$stockEntry);
					}
					if(is_array($conversion) && count($conversion) == 0){
						$updatepurc['is_qty_converted'] = 'Yes';
						$this->Common_model->updateDataFromTable('tbl_purchases',$updatepurc,'id',$purchaseId);
					}
					$this->form_validation->clear_field_data();
					$this->messages->setMessage('Receipt Created Successfully','success');
					redirect('administrator/Receipts');
				}
				
			}
		}
		$this->loadUserForm(array(),'add');
	}

	public function convert($param=''){
		if($param!=''){
			$data['purchase'] = $this->Common_model->getDataFromTable('tbl_purchases','',  $whereField='id', $whereValue=$param, $orderBy='', $order='', $limit='', $offset=0, true); 
			$orderByColumn = "tdi.id";
			$sortType = 'DESC';
			$indexColumn='tdi.id';
			$selectColumns = ['tdi.*','tp.product_name','tp.buyer_price'];
			$dataTableSortOrdering='';
			$table_name='tbl_purchase_items as tdi';
			$joinsArray[] = ['table_name'=>'tbl_products as tp','condition'=>"tp.id = tdi.product_id",'join_type'=>'left'];
			$whereCondition = "tdi.purchase_id='$param' and tdi.is_purchase_sale_different = 'Yes'";
			$listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
			$data['dispatch_items']  = $listData['data'];
            $data['breadcrumbs'] = $this->loadBreadCrumbs();
            // echo "<pre>";
            // print_r($data);exit;
            $this->home_template->load('home_template','admin/convertpurchase',$data);   
		}else{
			redirect('administrator/Receipts');
		}
	}

	public function updateReceipt(){
		if(($this->input->post('add'))){	
			$purchaseId = $this->input->post('purchase_id');
			$productsId = $this->input->post('product_id');
			
			$weight = $this->input->post('qty');
			for($i=0;$i<count($productsId);$i++){
				$stockupdate['quantity'] = $update['quantity'] = $weight[$i];

				$stockwhere['product_id'] = $purchaseWhere['product_id'] = $productsId[$i];
				$stockwhere['reference_id'] = $purchaseWhere['purchase_id'] = $purchaseId;
				$stockwhere['type'] = 'Purchase';

				$this->Common_model->updateDataFromTable('tbl_purchase_items',$update,$purchaseWhere,'');
				$this->Common_model->updateDataFromTable('tbl_stock_entries',$stockupdate,$stockwhere,'');
			}
			$this->Common_model->updateDataFromTable('tbl_purchases',['is_qty_converted'=>'Yes'],'id',$purchaseId);
			$this->messages->setMessage('Receipt Updated Successfully','success');
			redirect('administrator/Receipts');
		}
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$supplier      =  $this->input->post('supplier');
		$employee      =  $this->input->post('employee');
		$date      =  $this->input->post('date');
		$indexColumn = 'tr.id';
		$selectColumns = ['tr.id','tr.receipt_number','ts.supplier_name','tr.grand_total','tr.receipt_date','tr.created_on','tr.is_qty_converted'];
		$dataTableSortOrdering = ['tr.receipt_number','ts.supplier_name','tr.grand_total','tr.receipt_date','tr.created_on'];
		$table_name = 'tbl_purchases as tr';
		$joinsArray[] = ['table_name'=>'tbl_suppliers as ts','condition'=>"ts.id = tr.supplier_id",'join_type'=>'left'];;
		$wherecondition = 'tr.id!="0"';
		if($this->session->user_type == 'Employee'){
			$wherecondition = 'tr.created_by='.$this->session->id;
		}
		if($supplier!='All'){
			$wherecondition.=' and tr.supplier_id = '.$supplier;
		}
		if($employee!='All' && $this->session->user_type != 'Employee'){
			$wherecondition.=' and tr.created_by = '.$employee;
		}
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date(tr.created_on) between '$fromDate' and '$toDate' ";
        }
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
                $recordListing[$i][1]= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'receipts/print/'.$recordData->id.'" class="text-info">'.$recordData->receipt_number.'</a>';
                $recordListing[$i][2]= $recordData->supplier_name;
                $recordListing[$i][3]= $recordData->grand_total;
                $recordListing[$i][4]= displayDateInWords($recordData->receipt_date);
				$recordListing[$i][5]= displayDateInWords($recordData->created_on);
				$action.= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'receipts/print/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="ri-printer-fill" aria-hidden="true"></i></a>';
				if($recordData->is_qty_converted == 'No'){
					$action.= '&nbsp;&nbsp;&nbsp;<a href="'.CONFIG_SERVER_ADMIN_ROOT.'receipts/convert/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
				}
				$recordListing[$i][6]= $action;
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
			$data['supplier'] = $this->Common_model->getDataFromTable('tbl_suppliers','supplier_name,company_name,company_address',  $whereField='id', $whereValue=$data['purchase'][0]['supplier_id'], $orderBy='', $order='', $limit='', $offset=0, true);
			$data['user'] = $this->Common_model->getDataFromTable('tbl_users','first_name',  $whereField='id', $whereValue=$data['purchase'][0]['created_by'], $orderBy='', $order='', $limit='', $offset=0, true);
			$orderByColumn = "tpi.id";
			$sortType = 'DESC';
			$indexColumn='tpi.id';
			$selectColumns = ['tpi.*','tp.product_name'];
			$dataTableSortOrdering='';
			$table_name='tbl_purchase_items as tpi';
			$joinsArray[] = ['table_name'=>'tbl_products as tp','condition'=>"tp.id = tpi.product_id",'join_type'=>'left'];
			$whereCondition = "tpi.purchase_id='$id'";
			$listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
			$data['purchase_items']  = $listData['data'];
			$this->load->library('pdf');
			$pdfFilePath = 'invoice_'.$data['purchase'][0]['receipt_number'].'.pdf';
			$html =  $this->load->view('admin/invoice',$data,true);
			$this->pdf->loadHtml($html);
			$this->pdf->setPaper('A4', 'landscape');
			$this->pdf->render();
			$this->pdf->stream($pdfFilePath, array("Attachment"=>0));
		}else{
			redirect(base_url('receipts'));
		}
	}

}
