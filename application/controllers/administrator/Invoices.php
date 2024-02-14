<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data = array();
		$data['icon_class'] = "icon-user";
		$data['title'] = "Invoices";
		$data['helptext'] = "This Page Is Used To Manage The Invoices.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = CONFIG_SERVER_ADMIN_ROOT.'invoices';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['buyers'] = $this->Common_model->getDataFromTable('tbl_buyers','id,buyer_name,company_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true); 
		$this->home_template->load('home_template','admin/invoices',$data);   
	}

    public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields = ['buyer_id','invoice_date'];    
            foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
            if($this->form_validation->run() == FALSE){
				$this->form_validation->set_session_data($this->input->post());
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
                redirect($_SERVER['HTTP_REFERER']);
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
                    unset($data['price']);
                    unset($data['total']);
                    unset($data['qty']);
					$data['invoice_number'] = 'IN'.time();
					$data['created_on'] = current_datetime();
					$data['created_by'] = $this->session->id;
					$invoiceId = $this->Common_model->addDataIntoTable('tbl_invoices',$data);
					for($i=0;$i<count($products);$i++){
						$invoiceItems['invoice_id'] = $invoiceId;
						$stockEntry['product_id'] = $invoiceItems['product_id'] = $products[$i];
                        $invoiceItems['warehouse_id'] = $data['warehouse_id'];
						$stockEntry['quantity'] = $invoiceItems['quantity'] = $qty[$i];
						$invoiceItems['price'] = $price[$i];
						$invoiceItems['total'] = $total[$i];
                        $stockEntry['warehouse_id'] = $data['warehouse_id'];
						$stockEntry['type'] = 'sale';
						$stockEntry['created_on'] = current_datetime();
						$this->Common_model->addDataIntoTable('tbl_invoice_items',$invoiceItems);
                        $this->Common_model->addDataIntoTable('tbl_stock_entries',$stockEntry);
					}
                    $dispatchUpdate['is_invoice_generated'] = 'Yes';
                    $this->Common_model->updateDataFromTable('tbl_dispatch',$dispatchUpdate,'id',$data['dispatch_id']);
					$this->form_validation->clear_field_data();
					$this->messages->setMessage('Invoice Created Successfully','success');
					redirect('administrator/invoices');
				}
				
			}
		}

	}

    public function convertinvoice($id = ''){
        if($id!=''){
            $data['dispatch'] = $this->Common_model->getDataFromTable('tbl_dispatch','',  $whereField='id', $whereValue=$id, $orderBy='', $order='', $limit='', $offset=0, true); 
            $data['buyer'] = $this->Common_model->getDataFromTable('tbl_buyers','buyer_name,company_name,company_address',  $whereField='id', $whereValue=$data['dispatch'][0]['buyer_id'], $orderBy='', $order='', $limit='', $offset=0, true);
			$orderByColumn = "tdi.id";
			$sortType = 'DESC';
			$indexColumn='tdi.id';
			$selectColumns = ['tdi.*','tp.product_name','tp.buyer_price'];
			$dataTableSortOrdering='';
			$table_name='tbl_dispatch_items as tdi';
			$joinsArray[] = ['table_name'=>'tbl_products as tp','condition'=>"tp.id = tdi.product_id",'join_type'=>'left'];
			$whereCondition = "tdi.dispatch_id='$id'";
			$listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
			$data['dispatch_items']  = $listData['data'];
            $data['breadcrumbs'] = $this->loadBreadCrumbs();
            // echo "<pre>";
            // print_r($data);exit;
            $this->home_template->load('home_template','admin/convertinvoice',$data);   
        }else{
            redirect(base_url('administrator/dispatch'));
        }
    }

    public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$buyer         =  $this->input->post('buyer');
		$date         =  $this->input->post('date');
		$indexColumn = 'tin.id';
		$selectColumns = ['tin.id','tin.invoice_number','tb.buyer_name','tin.invoice_date','tin.created_on'];
		$dataTableSortOrdering = ['tin.id','tin.invoice_number','tb.buyer_name','tin.invoice_date','tin.created_on'];
		$table_name = 'tbl_invoices as tin';
		$joinsArray[] = ['table_name'=>'tbl_buyers as tb','condition'=>"tb.id = tin.buyer_id",'join_type'=>'left'];;
		$wherecondition = 'tin.id!="0"';
		if($date!=''){
			$wherecondition.= ' and date(tin.created_on) = "'.$date.'"';
		}
		if($buyer!='All'){
			$wherecondition.= ' and tin.buyer_id = '.$buyer;
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
                $recordListing[$i][1]= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'invoices/print/'.$recordData->id.'" class="text-info">'.$recordData->invoice_number.'</a>';
                $recordListing[$i][2]= $recordData->buyer_name;
                $recordListing[$i][3]= displayDateInWords($recordData->invoice_date);
				$recordListing[$i][4]= displayDateInWords($recordData->created_on);
				$action.= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'invoices/print/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="ri-printer-fill" aria-hidden="true"></i></a>';
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
			$data['invoice'] = $this->Common_model->getDataFromTable('tbl_invoices','',  $whereField='id', $whereValue=$id, $orderBy='', $order='', $limit='', $offset=0, true);
			$data['buyer'] = $this->Common_model->getDataFromTable('tbl_buyers','buyer_name,company_name,company_address',  $whereField='id', $whereValue=$data['invoice'][0]['buyer_id'], $orderBy='', $order='', $limit='', $offset=0, true);
			$data['user'] = $this->Common_model->getDataFromTable('tbl_users','first_name',  $whereField='id', $whereValue=$data['invoice'][0]['created_by'], $orderBy='', $order='', $limit='', $offset=0, true);
			$orderByColumn = "tini.id";
			$sortType = 'DESC';
			$indexColumn='tini.id';
			$selectColumns = ['tini.*','tp.product_name'];
			$dataTableSortOrdering='';
			$table_name='tbl_invoice_items as tini';
			$joinsArray[] = ['table_name'=>'tbl_products as tp','condition'=>"tp.id = tini.product_id",'join_type'=>'left'];
			$whereCondition = "tini.invoice_id='$id'";
			$listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
			$data['invoice_items']  = $listData['data'];
			$this->load->library('pdf');
			$pdfFilePath = 'invoice_'.$data['invoice'][0]['invoice_number'].'.pdf';
			$html =  $this->load->view('admin/invoice_print',$data,true);
			$this->pdf->loadHtml($html);
			$this->pdf->setPaper('A4', 'landscape');
			$this->pdf->render();
			$this->pdf->stream($pdfFilePath, array("Attachment"=>0));
		}else{
			redirect(base_url('receipts'));
		}
	}
}
?>