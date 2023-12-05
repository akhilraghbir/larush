<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bills extends CI_Controller {

	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class'] = "icon-docs";
		$data['title'] = "Bills";
		$data['helptext'] = "This Page Is Used To Manage The Bills.";
		$data['actions']['add'] = '';
		$data['actions']['list'] = CONFIG_SERVER_ADMIN_ROOT.'bills';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		if($this->session->user_type == 'Accountant'){
		    $where['accountant'] = $this->session->id;
		}
		$where['status'] = 'Active';
		$where['user_type'] = 'Client';
		$where['client_type'] = 'Firm';
		$data['clients'] = $this->Common_model->getDataFromTable('tbl_users','id,concat(first_name," ",last_name) as name', $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		if($this->session->user_type == 'Admin'){
		$data['accountants'] = $this->Common_model->getDataFromTable('tbl_users','id,concat(first_name," ",last_name) as name', $whereField=['user_type' => 'Accountant','status' => 'Active'], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		}
		$data['finanicial_years'] = $this->Common_model->getDataFromTable('tbl_financial_years','id,financial_year,status', $whereField='', $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/bills',$data);   
	}
	
	public function view($ref = ''){
	    if(!empty($ref)){
	        $ref = base64_decode($ref);
            $orderByColumn = "tb.id";
            $sortType = 'DESC';
            $indexColumn='tb.id';
            $selectColumns = ['tb.*','tc.category_name','tf.financial_year'];
            $dataTableSortOrdering='';
            $table_name='tbl_bills as tb';
            $joinsArray[] = ['table_name'=>'tbl_financial_years as tf','condition'=>"tf.id = tb.financial_year",'join_type'=>'left'];
            $joinsArray[] = ['table_name'=>'tbl_categories as tc','condition'=>"tc.id = tb.category_id",'join_type'=>'left'];
            $whereCondition = "tb.reference_number='".$ref."'";
            $listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
            $data['record'] = $listData['data'][0];
            $data['breadcrumbs'] = $this->loadBreadCrumbs(); 
            $data['attachments'] =  $this->Common_model->getDataFromTable('tbl_bill_attachments','file,file_type', $whereField='bill_id', $whereValue=$data['record']['id'], $orderBy='', $order='', $limit='', $offset=0, true);
            
	        $this->home_template->load('home_template','admin/view_bill',$data);   
	    }else{
            $this->messages->setMessage('Reference Number Param is required','danger');
            redirect(base_url('administrator/bills'));
	    }
	}
	
	
	public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('bill_title','month','category_id','bill_amount','financial_year','payment_mode','billed_on');    
			if($this->input->post('payment_mode') == 'Credit' || $this->input->post('payment_mode') == 'debit'){
			    $this->form_validation->set_rules('account_number', 'Account Number', 'required');
			}
            foreach($mandatoryFields as $row){
                $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                $this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
			if($this->form_validation->run() == FALSE){
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
            	foreach($this->input->post() as $fieldname=>$fieldvalue){
                	$data[$fieldname] = $this->input->post($fieldname);
                }
                
                $financialYear = $this->Common_model->getDataFromTable('tbl_financial_years','financial_year', $whereField='id', $whereValue=$data['financial_year'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                if (!is_dir('uploads/'.$data['client_id'])) {
                     mkdir('./uploads/' . $data['client_id'], 0777, TRUE);
                }
                if (!is_dir('uploads/'.$data['client_id'].'/'.$financialYear[0]['financial_year'])) {
                     mkdir('./uploads/' . $data['client_id'].'/'.$financialYear[0]['financial_year'], 0777, TRUE);
                }
                
                if (!is_dir('uploads/'.$data['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$data['month'])) {
                     mkdir('./uploads/'.$data['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$data['month'], 0777, TRUE);
                }
                $dirPath = 'uploads/'.$data['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$data['month'];
                $billobj = [];
                if(isset($_FILES['attachment']) && $_FILES['attachment']['name'][0]!=''){
                    for($i=0;$i<count($_FILES['attachment']['name']);$i++){
                        $_FILES['tmpName']['name'] = $_FILES['attachment']['name'][$i];
                        $_FILES['tmpName']['type'] = $_FILES['attachment']['type'][$i];
                        $_FILES['tmpName']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                        $_FILES['tmpName']['error'] = $_FILES['attachment']['error'][$i];
                        $_FILES['tmpName']['size'] = $_FILES['attachment']['size'][$i];
                        $response = $this->Common_model->uploadProfileImage($dirPath,"tmpName");
                        if(!empty($response) && $response['status']=="success") {
                          $bills['file'] = 'uploads/'.$data['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$data['month'].'/'.$response['imageName'];
                          $bills['bill_id'] = $data['id'];
                          $bills['file_type'] = pathinfo($bills['file'], PATHINFO_EXTENSION);
                          $billobj[$i] = $bills;
                          $billsins = $this->Common_model->addDataIntoTable('tbl_bill_attachments',$bills);
                        }else{
                            $this->messages->setMessage($response['message'],'danger');
                            redirect('administrator/bills/edit/'.$param1); 
                        }
                    }
                }
                $data['edit_option'] = 'Close';
                $logid = $data['log_id'];
                $logins['record'] = $data;
                $logins['attachment'] = $billobj;
                unset($data['edit']);
                unset($data['client_id']);
                unset($data['attachment']);
                unset($data['log_id']);
                $log['after_object'] = json_encode($logins);
                $this->Common_model->updateDataFromTable('tbl_bill_logs',$log,'id',$logid);
				$this->Common_model->updateDataFromTable('tbl_bills',$data,'reference_number',base64_decode($param1));
				$this->messages->setMessage('Bill with '.base64_decode($param1).' Updated Successfully','success');
				redirect(base_url('administrator/bills'));
			}
		}
		$formData=array();
		if($param1!=''){
		    $param1 = base64_decode($param1);
			$result = $this->Common_model->getDataFromTable('tbl_bills','',  $whereField='reference_number', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			if(is_array($result[0])){
    			$obj['record'] = $data['record'] = $result[0];
    			$data['categories'] = $this->Common_model->getDataFromTable('tbl_categories','id,category_name',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
    			$data['years'] = $this->Common_model->getDataFromTable('tbl_financial_years','id,financial_year',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
    			$obj['attachments'] = $data['attachments'] = $this->Common_model->getDataFromTable('tbl_bill_attachments','id,file,file_type',  $whereField='bill_id', $whereValue=$data['record']['id'], $orderBy='', $order='', $limit='', $offset=0, true);
    			if($data['record']['edit_option'] == 'Open'){
    			    $logins['before_object'] = json_encode($obj);
    			    $logins['bill_id'] = $param1;
    			    $logins['created_on'] = current_datetime();
    			    $data['log_id'] = $this->Common_model->addDataIntoTable('tbl_bill_logs',$logins);
    			    $data['breadcrumbs'] = $this->loadBreadCrumbs(); 
                    $this->home_template->load('home_template','admin/edit_bill',$data);   
    			}else{
    			    $this->messages->setMessage('Access denied to edit','danger');
                    redirect('administrator/bills');  
    			}
			}else{
			    $this->messages->setMessage('Invalid parameter','danger');
                    redirect('administrator/bills');  
			}
		}else{
            $this->messages->setMessage('Id param is required','danger');
            redirect('administrator/bills');  
	    }
	}
	
	public function requestEdit(){
	    if($_POST){
    	      if(isset($_POST['bill_id'])){
    	          $data['ref_id'] = $_POST['bill_id'];
    	          $data['message'] = $_POST['reason'];
    	          $data['notif_message'] = $this->session->name." requested permission to edit bill - ".$data['ref_id'];
    	          $data['created_on'] = current_datetime();
    	          $data['sender'] = $this->session->id;
    	          $data['reciever'] = 1;
    	          $ins = $this->Common_model->addDataIntoTable('tbl_notifications',$data);
                  if($ins){
                      $response = ['error' => '0','message' => 'Request Sent to Admin Successfully'];
                  }else{
                      $response = ['error' => '1','message' => 'Something went wrong'];
                  }
                  echo json_encode($response);
    	      }
	    }
	}
	
	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$status         =  $this->input->post('status');
		$accountant         =  $this->input->post('accountant');
		$month         =  $this->input->post('month');
		$bill_type = $this->input->post('bill_type');
		$client = $this->input->post('client');
		$reference_number = $this->input->post('reference_number');
		$financial_year = $this->input->post('financial_year');
		$indexColumn = 'tb.id';
		$selectColumns = array('tb.id','tb.reference_number','tb.bill_title','tb.bill_amount','tb.payment_mode','tb.status','tb.created_on','tb.edit_option');
		$dataTableSortOrdering = array('tb.reference_number','tb.bill_title','tb.bill_amount','tb.payment_mode','tb.status','tb.created_on');
		$table_name='tbl_bills as tb';
		$joinsArray = [];
		$wherecondition="tb.id!=''";
		if($status=='Active'){
		    $wherecondition.=' and tb.status = "Active"';
		}else if($status=='Inactive'){
		    $wherecondition.=' and tb.status = "Inactive"';
		}
		if($accountant!='All'){
		    $clientids = $this->Common_model->getClientIds($accountant,'Firm');
		    $wherecondition.=" and tb.client_id in ('$clientids')";
		}
		if($client!='All'){
		    $wherecondition.=" and tb.client_id=".$client;
		}
		if($bill_type!='All'){
		    $wherecondition.=" and tb.bill_type='".$bill_type."'";
		}
		if($month!=0){
		   $wherecondition.=" and tb.month='".$month."'";  
		}
		if($reference_number!=''){
		    $wherecondition.=" or tb.reference_number='".$reference_number."'";  
		    
		}
		$wherecondition.=" and financial_year=".$financial_year;
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
				$action="";
				$content .='[';
				$recordListing[$i][0]= $recordData->reference_number;
                $recordListing[$i][1]= '<a href="javascript:void()" class="text-info" onclick="viewBill('.$recordData->id.')">'.$recordData->bill_title."</a>";
                $recordListing[$i][2]= CURRENCY_ICON.' '.$recordData->bill_amount;
                $recordListing[$i][3]= $recordData->payment_mode;
				if($recordData->status == 'Inactive'){
					$recordListing[$i][4]= '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				}else{
					$recordListing[$i][4]= '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                $recordListing[$i][5] = displayDateInWords($recordData->created_on);
                if($recordData->status == 'Active' && $this->session->user_type == 'Accountant'){
    				if($recordData->edit_option == 'Close'){
    				   	$action.= '<a href="javascript:void()" onclick="requestEdit('.$recordData->reference_number.')" data-toggle="tooltip" data-placement="top" data-original-title="Request For Edit"><i class="icon-cursor" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp'; 
    				}else if($recordData->edit_option == 'Open'){
    				    $action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'bills/edit/'.base64_encode($recordData->reference_number).'" data-toggle="tooltip" data-placement="top" data-original-title="Edit Bill"><i class="ri-pencil-fill" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp';
    				}
                }
				$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'bills/view/'.base64_encode($recordData->reference_number).'" data-toggle="tooltip" data-placement="top" data-original-title="View Bill"><i class="icon-eye" aria-hidden="true"></i></a>';
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
	
	public function getBill(){
	    if($_POST){
	        $ref = $_POST['billId'];
            $orderByColumn = "tb.id";
            $sortType = 'DESC';
            $indexColumn='tb.id';
            $selectColumns = ['tb.*','tc.category_name','tf.financial_year'];
            $dataTableSortOrdering='';
            $table_name='tbl_bills as tb';
            $joinsArray[] = ['table_name'=>'tbl_financial_years as tf','condition'=>"tf.id = tb.financial_year",'join_type'=>'left'];
            $joinsArray[] = ['table_name'=>'tbl_categories as tc','condition'=>"tc.id = tb.category_id",'join_type'=>'left'];
            $whereCondition = "tb.id='".$ref."'";
            $listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
            if(count($listData['data'][0])>0){
                $data['record'] = $listData['data'][0];
                $data['breadcrumbs'] = $this->loadBreadCrumbs(); 
                $data['attachments'] =  $this->Common_model->getDataFromTable('tbl_bill_attachments','file,file_type', $whereField='bill_id', $whereValue=$data['record']['id'], $orderBy='', $order='', $limit='', $offset=0, true);
                $res['status'] = 0;
    	        $res['html'] = $this->load->view('admin/view_bill',$data,true);
            }else{
               $res['status'] = 1;
    	        $res['msg'] = 'No Data Found';
            }
            echo json_encode($res);
	    }
	}
	
	public function export(){
	    if($_POST){
            $status         =  $this->input->post('status');
            $accountant         =  $this->input->post('accountant');
            $month         =  $this->input->post('month');
            $client = $this->input->post('client');
            $reference_number = $this->input->post('reference_number');
            $financial_year = $this->input->post('financial_year');
            $wherecondition="tb.id!=''";
            if($status=='Active'){
                $wherecondition.=' and tb.status = "Active"';
            }else if($status=='Inactive'){
                $wherecondition.=' and tb.status = "Inactive"';
            }
            if($accountant!='All'){
                $clientids = $this->Common_model->getClientIds($accountant);
                $wherecondition.=" and tb.client_id in ('$clientids')";
            }
            if($client!='All'){
                $wherecondition.=" and tb.client_id=".$client;
            }
            if($month!=0){
                $wherecondition.=" and tb.month='".$month."'";  
            }
            if($reference_number!=''){
                $wherecondition.=" or tb.reference_number='".$reference_number."'";  
            }
            if($financial_year!='All'){
                $wherecondition.=" and tb.financial_year=".$financial_year;
            }
           // echo $wherecondition;exit;
	        if($_POST['type'] == 'Export'){
                $orderByColumn = "tb.id";
                $sortType = 'DESC';
                $indexColumn = 'tb.id';
                $selectColumns = array('tb.id','tb.reference_number','tb.month','tb.bill_title','tb.bill_type','tb.bill_amount','tb.payment_mode','tb.account_number','tc.category_name','tf.financial_year','tb.billed_on','tb.status','tb.created_on');
                $dataTableSortOrdering = array('tb.reference_number','tb.bill_title','tb.bill_amount','tb.payment_mode','tb.status','tb.created_on');
                $table_name='tbl_bills as tb';
                $joinsArray[] = ['table_name'=>'tbl_financial_years as tf','condition'=>"tf.id = tb.financial_year",'join_type'=>'left'];
                $joinsArray[] = ['table_name'=>'tbl_categories as tc','condition'=>"tc.id = tb.category_id",'join_type'=>'left'];
                $listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
                $data = $listData['data'];
                $exportCsv[0] = ['id','Reference Number','Month','Bill Title','Bill Type','Bill Amount','Payment Mode','Account Number','Category','Financial Year','Bill Date','Status','Created On'];
                if(!empty($data)){
                    $i = 1;
                    foreach($data as $row){
                       $exportCsv[$i] = [$i,($row['reference_number']!='') ? $row['reference_number'] : '-',($row['month']!='') ? $row['month'] : '-',($row['bill_title']!='') ? $row['bill_title'] : '-',($row['bill_type']!='') ? $row['bill_type'] : '-',($row['bill_amount']!='') ? $row['bill_amount'] : '-',($row['payment_mode']!='') ? $row['payment_mode'] : '-',($row['account_number']!='') ? $row['account_number'] : '-',($row['category_name']!='') ? $row['category_name'] : '-',($row['financial_year']!='') ? $row['financial_year'] : '-',($row['billed_on']!='') ? $row['billed_on'] : '-',($row['status']!='') ? $row['status'] : '-',($row['created_on']!='') ? $row['created_on'] : '-']; 
                       $i++;
                    }
                }else{
                    $this->messages->setMessage('No Data Found to export','danger');
                    redirect('administrator/bills');
                }
                $fileName = date('dmY').'_bills'.'.csv';
                ob_end_clean();
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename='.$fileName);
                $exportFile = fopen("php://output","a");
                foreach($exportCsv as $fields){
                    fputcsv($exportFile,$fields);
                }
                fclose($exportFile);
	        }else{
	            $query = $this->db->query("select id from tbl_bills as tb where ".$wherecondition)->result_array();
	            foreach($query as $q){
	                $ids[] = $q['id'];
	            }
	            if(count($ids)>0){
    	            $docs = $this->Common_model->getDocs($ids);
    	            $filename = date('dmY')."_bills";
    				$this->load->library('ziplib');
    				$this->ziplib->zipFilesAndDownload($docs,$filename);
	            }else{
	               $this->messages->setMessage('No Data Found to download','danger');
                    redirect('administrator/bills');    
	            }
	        }
	        
	    }else{
            redirect(base_url('administrator/bills'));
	    }
	}
	
}
?>