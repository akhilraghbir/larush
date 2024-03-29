<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispatch extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-user";
		$data['title'] = "Dispatch";
		$data['helptext'] = "This Page Is Used To Manage The Dispatch.";
		$data['actions']['add'] = CONFIG_SERVER_ADMIN_ROOT.'dispatch/add';
		$data['actions']['list'] = CONFIG_SERVER_ADMIN_ROOT.'dispatch';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['buyers'] = $this->Common_model->getDataFromTable('tbl_buyers','id,buyer_name,company_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true); 
		$this->home_template->load('home_template','admin/dispatch',$data);   
	}

	public function loadUserForm($formContent=array(), $formName='',$param=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
        $data['buyers'] = $this->Common_model->getDataFromTable('tbl_buyers','id,buyer_name,company_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$data['warehouses'] = $this->Common_model->getDataFromTable('tbl_warehouses','id,warehouse_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		if($param!=''){
			$data['dispatch']  = $this->Common_model->getDataFromTable('tbl_dispatch','',  $whereField='id', $whereValue=$param, $orderBy='', $order='', $limit=1, $offset=0, true);
			$data['dispatch_items']  = $this->Common_model->getDataFromTable('tbl_dispatch_items','',  $whereField='dispatch_id', $whereValue=$param, $orderBy='', $order='', $limit='', $offset=0, true);
			$products = $this->Common_model->getDataFromTable('tbl_products','',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
			$data['products'] = array_column($products,'product_name','id');
		 }
		$this->home_template->load('home_template','admin/dispatch',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields = ['buyer_id','dispatch_date'];    
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
                    $gross = $data['gross'];
                    $net = $data['net'];
                    $tare = $data['tare'];
					unset($data['product_id']);
					unset($data['add']);
					unset($data['gross']);
					unset($data['net']);
					unset($data['tare']);
					$data['dispatch_number'] = 'D'.time();
					$dispatchItems['created_on'] = $data['created_on'] = current_datetime();
					$data['created_by'] = $this->session->id;
					$dispatchId = $this->Common_model->addDataIntoTable('tbl_dispatch',$data);
					for($i=0;$i<count($products);$i++){
						$dispatchItems['dispatch_id'] = $dispatchId;
						$dispatchItems['product_id'] = $products[$i];
						$dispatchItems['gross'] = $gross[$i];
						$dispatchItems['tare'] = $tare[$i];
						$dispatchItems['net'] = $net[$i];
						$this->Common_model->addDataIntoTable('tbl_dispatch_items',$dispatchItems);
					}
					$this->form_validation->clear_field_data();
					$this->messages->setMessage('Dispatch Created Successfully','success');
					redirect('administrator/dispatch');
				}
				
			}
		}
			$this->loadUserForm(array(),'add','');
	}


	public function edit($param = ''){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields = ['buyer_id','dispatch_date'];    
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
				if(!isset($data['product_up_id'])){
					$this->messages->setMessage('Please select atleast one product','error');
				}else{
					$products = $data['product_id'];
					$productupid = $data['product_up_id'];
                    $gross_up = $data['gross_up'];
					$tare_up = $data['tare_up'];
                    $net_up = $data['net_up'];
					$up_ids = $data['up_ids'];
					$gross = $data['gross'];
					$tare = $data['tare'];
                    $net = $data['net'];
					unset($data['product_id']);
					unset($data['tare_up']);
					unset($data['tare']);
					unset($data['add']);
					unset($data['gross']);
					unset($data['net']);
					unset($data['gross_up']);
					unset($data['up_ids']);
					unset($data['net_up']);
					unset($data['product_up_id']);
					$dispatchupItems['updated_on'] = $dispatchItems['created_on'] = $data['updated_on'] = current_datetime();
					$dispatchId = $this->Common_model->updateDataFromTable('tbl_dispatch',$data,'id',$param);
					if(is_array($products) && count($products)>0){
						for($i=0;$i<count($products);$i++){
							$dispatchItems['dispatch_id'] = $param;
							$dispatchItems['product_id'] = $products[$i];
							$dispatchItems['gross'] = $gross[$i];
							$dispatchItems['tare'] = $tare[$i];
							$dispatchItems['net'] = $net[$i];
							$this->Common_model->addDataIntoTable('tbl_dispatch_items',$dispatchItems);
						}
					}
					if(is_array($productupid) && count($productupid)>0){
						for($i=0;$i<count($productupid);$i++){
							$dispatchupItems['product_id'] = $productupid[$i];
							$dispatchupItems['gross'] = $gross_up[$i];
							$dispatchupItems['tare'] = $tare_up[$i];
							$dispatchupItems['net'] = $net_up[$i];
							$this->Common_model->updateDataFromTable('tbl_dispatch_items',$dispatchupItems,'id',$up_ids[$i]);
						}
					}
					$this->form_validation->clear_field_data();
					$this->messages->setMessage('Dispatch Updated Successfully','success');
					redirect('administrator/Dispatch');
				}
			}
		}
        $formData=array();
		$this->loadUserForm($formData, 'edit',$param);
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$buyer         =  $this->input->post('buyer');
		$date         =  $this->input->post('date');
		$indexColumn = 'td.id';
		$selectColumns = ['td.id','td.dispatch_number','tb.buyer_name','td.dispatch_date','td.created_on','td.is_invoice_generated'];
		$dataTableSortOrdering = ['td.id','td.dispatch_number','tb.buyer_name','td.dispatch_date','td.created_on'];
		$table_name = 'tbl_dispatch as td';
		$joinsArray[] = ['table_name'=>'tbl_buyers as tb','condition'=>"tb.id = td.buyer_id",'join_type'=>'left'];;
		$wherecondition = 'td.id!="0"';
		if($date!=''){
            $date = explode("-",$date);
            $fromDate = date("Y-m-d",strtotime($date[0]));
            $toDate = date("Y-m-d",strtotime($date[1]));
            $wherecondition.=" and date(td.created_on) between '$fromDate' and '$toDate' ";
        }
		if($buyer!='All'){
			$wherecondition.= ' and td.buyer_id = '.$buyer;
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
                $recordListing[$i][1]= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'dispatch/print/'.$recordData->id.'" class="text-info" onclick="getDetails('.$recordData->id.')">'.$recordData->dispatch_number.'</a>';
                $recordListing[$i][2]= $recordData->buyer_name;
                $recordListing[$i][3]= displayDateInWords($recordData->dispatch_date);
				$recordListing[$i][4]= displayDateInWords($recordData->created_on);
				$action.= '<a target="_blank" href="'.CONFIG_SERVER_ADMIN_ROOT.'dispatch/print/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="ri-printer-fill" aria-hidden="true"></i></a>';
				if($this->session->user_type == 'Admin' && $recordData->is_invoice_generated == 'No'){
					$action.= '&nbsp;&nbsp;&nbsp;<a href="'.CONFIG_SERVER_ADMIN_ROOT.'Dispatch/edit/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
					$action.= '&nbsp;&nbsp;&nbsp;<a href="'.CONFIG_SERVER_ADMIN_ROOT.'invoices/convertinvoice/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="ri-file-list-3-line" aria-hidden="true"></i></a>';
				}
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
			$data['dispatch'] = $this->Common_model->getDataFromTable('tbl_dispatch','',  $whereField='id', $whereValue=$id, $orderBy='', $order='', $limit='', $offset=0, true);
			$data['buyer'] = $this->Common_model->getDataFromTable('tbl_buyers','buyer_name,company_name,company_address',  $whereField='id', $whereValue=$data['dispatch'][0]['buyer_id'], $orderBy='', $order='', $limit='', $offset=0, true);
			$data['user'] = $this->Common_model->getDataFromTable('tbl_users','first_name',  $whereField='id', $whereValue=$data['dispatch'][0]['created_by'], $orderBy='', $order='', $limit='', $offset=0, true);
			$orderByColumn = "tdi.id";
			$sortType = 'DESC';
			$indexColumn='tdi.id';
			$selectColumns = ['tdi.*','tp.product_name'];
			$dataTableSortOrdering='';
			$table_name='tbl_dispatch_items as tdi';
			$joinsArray[] = ['table_name'=>'tbl_products as tp','condition'=>"tp.id = tdi.product_id",'join_type'=>'left'];
			$whereCondition = "tdi.dispatch_id='$id'";
			$listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
			$data['dispatch_items']  = $listData['data'];
			$this->load->library('pdf');
			$pdfFilePath = 'dispatch_'.$data['dispatch'][0]['dispatch_number'].'.pdf';
			$html =  $this->load->view('admin/dispatch_print',$data,true);
			$this->pdf->loadHtml($html);
			$this->pdf->setPaper('A4', 'landscape');
			$this->pdf->render();
			$this->pdf->stream($pdfFilePath, array("Attachment"=>0));
		}else{
			redirect(base_url('receipts'));
		}
	}

    public function dispatchProduct(){
        $pid = $this->input->post('id');
		if($pid!=''){
			$product = $this->Common_model->getDataFromTable('tbl_products','',  $whereField='id', $whereValue=$pid, $orderBy='', $order='', $limit=1, $offset=0, true);
			if(is_array($product) && count($product)>0){
				$elementid = time();
				$html='<tr class="tr_'.$elementid.'">';
				$html.='<td>'.substr($product[0]['product_name'],0,5).'..</td>';
                $html.='<td><input type="hidden" value="'.$product[0]['id'].'" name="product_id[]"><input type="text" data-pname="'.$product[0]['product_name'].'" maxlength="12" onkeyup="calculateTotal('.$elementid.')" class="gross_'.$elementid.' gross form-control Onlynumbers" name="gross[]" placeholder="Enter Gross"></td>';
				$html.='<td><input type="text" maxlength="12" onkeyup="calculateTotal('.$elementid.')" name="tare[]" class="tare_'.$elementid.' tare form-control Onlynumbers" value="0" placeholder="Enter Tare"></td>';
                $html.='<td><input type="text" readonly maxlength="12"  name="net[]" class="net_'.$elementid.' net form-control Onlynumbers" placeholder="Enter Net"></td>';
				$html.='<td><button type="button" onclick="removeRow('.$elementid.')" class="btn btn-sm btn-danger"><i class="ri-delete-bin-3-fill"></i></button>
                <button type="button" onclick="print('.$elementid.')" class="btn btn-sm btn-info"><i class="ri-printer-fill"></i></button></td>';
				$html.='</tr>';
				$res['error'] = 0;
				$res['html'] = $html;
			}else{
				$res['error'] = 1;
				$res['html'] = '';
			}
			echo json_encode($res);exit;
		}
    }

    public function packing_slip($id=''){
        if($id!=''){
            $data = json_decode(base64_decode($id),true);
            $this->load->view('admin/packing_slip',$data);
        }
    }

}
