<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct()
	{
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        
        $check = checkAuth($this->input->server('HTTP_AUTHORIZATION'));
        if(!$check){
            $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => 'Invalid Authorization Headers'];
            $this->response($message);exit;
        }
	} 
	
	public function financial_years(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='GET'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $years = $this->Common_model->getDataFromTable('tbl_financial_years','id,financial_year,status', $whereField='', $whereValue='',$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'Data Found','data' => $years];
                $this->response($response);
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function categories(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='GET'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $years = $this->Common_model->getDataFromTable('tbl_categories','id,category_name,status', $whereField='status', $whereValue='Active',$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'Data Found','data' => $years];
                $this->response($response);
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function addBill(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $errmsg = '';
                $mandatoryFields = ['client_id','bill_title','financial_year','bill_amount','bill_type','billed_on','payment_mode','month'];
                if($this->input->post('bill_type') == 'Expense'){
                    $this->form_validation->set_rules('category_id','Category','required');
                }
                foreach($mandatoryFields as $row){
                	$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                	$this->form_validation->set_rules($row, $fieldname, 'required'); 
                }
                if(($this->input->post('payment_mode') == 'credit' || $this->input->post('payment_mode') == 'dedit')){
                    if($this->input->post('account_number') == ''){
                        $this->form_validation->set_rules('account_number', 'Account Number', 'required'); 
                    }
                }
                if(!isset($_FILES['attachment'])){
                	$this->form_validation->set_rules('attachment','Attachment', 'required'); 
                }
                if($this->form_validation->run() == FALSE){
                    $errorMessage = strip_tags(validation_errors());
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errorMessage]));
                }else{
                    $checkClient = $this->Common_model->check_exists('tbl_users','id',$_POST['client_id'],'','');
                    if($checkClient == 1){
                        foreach($this->input->post() as $fieldname=>$fieldvalue){
                            $insdata[$fieldname]= $this->input->post($fieldname);
                        }
                        $insdata['reference_number'] = time();
                        $insdata['created_on'] =  current_datetime();
                        $ins = $this->Common_model->addDataIntoTable('tbl_bills',$insdata);
                        $financialYear = $this->Common_model->getDataFromTable('tbl_financial_years','financial_year', $whereField='id', $whereValue=$insdata['financial_year'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                        $msg = "Bill Submitted Successfully";
                        if (!is_dir('uploads/'.$insdata['client_id'])) {
                             mkdir('./uploads/' . $insdata['client_id'], 0777, TRUE);
                        }
                        if (!is_dir('uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'])) {
                             mkdir('./uploads/' . $insdata['client_id'].'/'.$financialYear[0]['financial_year'], 0777, TRUE);
                        }
                        
                        if (!is_dir('uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'])) {
                             mkdir('./uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'], 0777, TRUE);
                        }
                        $dirPath = 'uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'];
                        for($i=0;$i<count($_FILES['attachment']['name']);$i++){
                            $_FILES['tmpName']['name'] = $_FILES['attachment']['name'][$i];
                            $_FILES['tmpName']['type'] = $_FILES['attachment']['type'][$i];
                            $_FILES['tmpName']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                            $_FILES['tmpName']['error'] = $_FILES['attachment']['error'][$i];
                            $_FILES['tmpName']['size'] = $_FILES['attachment']['size'][$i];
                            $response = $this->Common_model->uploadProfileImage($dirPath,"tmpName");
                            if(!empty($response) && $response['status']=="success") {
                              $bills['file'] = 'uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'].'/'.$response['imageName'];
                              $bills['bill_id'] = $ins;
                              $bills['file_type'] = pathinfo($bills['file'], PATHINFO_EXTENSION);
                              $billsins = $this->Common_model->addDataIntoTable('tbl_bill_attachments',$bills);
                            }else{
                             $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => $response['message']];
                             $this->response($response);exit;
                            }
                        }
                        if($ins){
                         $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => $msg];
                         $this->response($response);
                        }
                    }else{
                        $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Invalid Client Id'];
                        $this->response($response);
                    }
                }
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function updateBill(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $errmsg = '';
                $mandatoryFields = ['bill_id','client_id','bill_title','financial_year','bill_amount','bill_type','billed_on','payment_mode','month'];
                if($this->input->post('bill_type') == 'Expense'){
                    $this->form_validation->set_rules('category_id','Category','required');
                }
                foreach($mandatoryFields as $row){
                	$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                	$this->form_validation->set_rules($row, $fieldname, 'required'); 
                }
                if(($this->input->post('payment_mode') == 'credit' || $this->input->post('payment_mode') == 'dedit')){
                    if($this->input->post('account_number') == ''){
                        $this->form_validation->set_rules('account_number', 'Account Number', 'required'); 
                    }
                }
                if($this->form_validation->run() == FALSE){
                    $errorMessage = strip_tags(validation_errors());
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errorMessage]));
                }else{
                $checkAttachments = $this->Common_model->check_exists('tbl_bill_attachments','bill_id',$_POST['bill_id'],'','');
                if(!isset($_FILES['attachment']) && $checkAttachments == 0){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Attachments are required']));
                }
                    $checkClient = $this->Common_model->check_exists('tbl_users','id',$_POST['client_id'],'','');
                    if($checkClient == 1){
                        foreach($this->input->post() as $fieldname=>$fieldvalue){
                            $insdata[$fieldname]= $this->input->post($fieldname);
                        }
                        $billid = $insdata['bill_id'];
                        unset($insdata['bill_id']);
                        $insdata['updated_on'] =  current_datetime();
                        $ins = $this->Common_model->updateDataFromTabel('tbl_bills',$insdata,'id',$billid);
                        $financialYear = $this->Common_model->getDataFromTable('tbl_financial_years','financial_year', $whereField='id', $whereValue=$insdata['financial_year'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                        $msg = "Bill Updated Successfully";
                        if (!is_dir('uploads/'.$insdata['client_id'])) {
                             mkdir('./uploads/' . $insdata['client_id'], 0777, TRUE);
                        }
                        if (!is_dir('uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'])) {
                             mkdir('./uploads/' . $insdata['client_id'].'/'.$financialYear[0]['financial_year'], 0777, TRUE);
                        }
                        
                        if (!is_dir('uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'])) {
                             mkdir('./uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'], 0777, TRUE);
                        }
                        $dirPath = 'uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'];
                        if(isset($_FILES['attachment'])){
                            for($i=0;$i<count($_FILES['attachment']['name']);$i++){
                                $_FILES['tmpName']['name'] = $_FILES['attachment']['name'][$i];
                                $_FILES['tmpName']['type'] = $_FILES['attachment']['type'][$i];
                                $_FILES['tmpName']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                                $_FILES['tmpName']['error'] = $_FILES['attachment']['error'][$i];
                                $_FILES['tmpName']['size'] = $_FILES['attachment']['size'][$i];
                                $response = $this->Common_model->uploadProfileImage($dirPath,"tmpName");
                                if(!empty($response) && $response['status']=="success") {
                                  $bills['file'] = 'uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$insdata['month'].'/'.$response['imageName'];
                                  $bills['bill_id'] = $billid;
                                  $bills['file_type'] = pathinfo($bills['file'], PATHINFO_EXTENSION);
                                  $billsins = $this->Common_model->addDataIntoTable('tbl_bill_attachments',$bills);
                                }else{
                                 $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => $response['message']];
                                 $this->response($response);exit;
                                }
                            }
                        }
                        if($ins){
                         $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => $msg];
                         $this->response($response);
                        }
                    }else{
                        $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Invalid Client Id'];
                        $this->response($response);
                    }
                }
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function listBills(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $postData = json_decode(file_get_contents('php://input'),1);
                if(!empty($postData['client_id'])){
                    $orderByColumn = "tb.id";
                    $sortType = 'DESC';
                    $indexColumn='tb.id';
                    $selectColumns = ['tb.id','tb.bill_title','tb.reference_number','tb.month','tb.created_on','tc.category_name','tf.financial_year'];
                    $dataTableSortOrdering='';
                    $table_name='tbl_bills as tb';
                    $joinsArray[] = ['table_name'=>'tbl_financial_years as tf','condition'=>"tf.id = tb.financial_year",'join_type'=>'left'];
                    $joinsArray[] = ['table_name'=>'tbl_categories as tc','condition'=>"tc.id = tb.category_id",'join_type'=>'left'];
                    $whereCondition = "tb.id!='' and tb.status ='Active' and tb.client_id=".$postData['client_id'];
                    if(!empty($postData['year'])){
                        $whereCondition.=" and tb.financial_year=".$postData['year'];
                    }
                    if(!empty($postData['month'])){
                        $whereCondition.=" and tb.month=".$postData['month'];
                    }
                    $listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
                    $data = $listData['data'];
                    if(count($data)>0){
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Found','data' => $data];
                    }else{
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Not Found'];
                    }
                    $this->response($response);
                    
                }else{
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Client Id is required']));
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function getBill(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['client_id','bill_id'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                    $orderByColumn = "tb.id";
                    $sortType = 'DESC';
                    $indexColumn='tb.id';
                    $selectColumns = ['tb.*','tc.category_name','tf.financial_year as year'];
                    $dataTableSortOrdering='';
                    $table_name='tbl_bills as tb';
                    $joinsArray[] = ['table_name'=>'tbl_financial_years as tf','condition'=>"tf.id = tb.financial_year",'join_type'=>'left'];
                    $joinsArray[] = ['table_name'=>'tbl_categories as tc','condition'=>"tc.id = tb.category_id",'join_type'=>'left'];
                    $whereCondition = "tb.status ='Active' and tb.client_id=".$postData['client_id']." and tb.id=".$postData['bill_id'];
                    $listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
                    if(count($listData['data'])>0){
                        $data = $listData['data'][0];
                        $attachments = $this->Common_model->getDataFromTable('tbl_bill_attachments','id,file,file_type', $whereField='bill_id', $whereValue=$postData['bill_id'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                        $i=0;
                        foreach($attachments as $attachment){
                            $ar[$i]['attachment_id'] = $attachment['id'];
                            $ar[$i]['attachment'] = base_url($attachment['file']);
                            $ar[$i]['type'] = $attachment['file_type'];
                            $i++;
                        }
                        $data['attachments'] = $ar;
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Found','data' => $data];
                    }else{
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Invalid Bill Id'];
                    }
                    
                    $this->response($response);
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function deleteBill(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['bill_id'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                    $update['status'] = 'Deleted';
                    $update['updated_on'] = current_datetime();
                    $up =  $ins = $this->Common_model->updateDataFromTabel('tbl_bills',$update,'id',$postData['bill_id']);
                    if($up){
                       $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Bill Deleted Successfully']; 
                    }
                    $this->response($response);exit;
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function deleteAttachment(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['attachment_id'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                     $attachments = $this->Common_model->getDataFromTable('tbl_bill_attachments','id,file,file_type', $whereField='id', $whereValue=$postData['attachment_id'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                     if(count($attachments)>0){
                         $file = $attachments[0]['file'];
                         if(file_exists($file)){
                            unlink($file);
                         }
                         $del = $this->Common_model->deleteRowFromTable('tbl_bill_attachments','id',$postData['attachment_id'],'');
                         if($del){
                            $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Attachment Delete Successfully'];
                         }
                         $this->response($response);
                     }else{
                         throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Invalid Attachment Id']));
                     }
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function getProfile(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['user_id'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                     $profile = $this->Common_model->getDataFromTable('tbl_users','id,first_name,last_name,username,email_id,phno', $whereField='id', $whereValue=$postData['user_id'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                     if(count($profile)>0){
                     $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Found','data'=>$profile[0]];
                     $this->response($response);
                     }else{
                         throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Invalid User Id']));
                     }
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function updateProfile(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $errmsg = '';
                $mandatoryFields = ['user_id','first_name','last_name','username','phno'];
                foreach($mandatoryFields as $row){
                	$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                	$this->form_validation->set_rules($row, $fieldname, 'required'); 
                }
                if($this->form_validation->run() == FALSE){
                    $errorMessage = strip_tags(validation_errors());
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errorMessage]));
                }else{
                    foreach($this->input->post() as $fieldname=>$fieldvalue){
                     $data[$fieldname]= $this->input->post($fieldname);
                    }
                    $userid = $data['user_id'];
                    unset($data['user_id']);
                    unset($data['email_id']);
                    $update = $this->Common_model->updateDataFromTabel('tbl_users',$data,'id',$userid);
                    if($update){
                     $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Profile Updated Successfully'];
                     $this->response($response);
                    }
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	
	public function updatePasswod(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $errmsg = '';
                $mandatoryFields = ['current_password','password','confirm_password','user_id'];
                foreach($mandatoryFields as $row){
                	$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                	$this->form_validation->set_rules($row, $fieldname, 'required'); 
                }
                if($this->form_validation->run() == FALSE){
                    $errorMessage = strip_tags(validation_errors());
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errorMessage]));
                }else{
                    if($this->input->post('password')!=$this->input->post('confirm_password')){
                        throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Password & Confirm Password are doesnt matched']));
                    }else{
                        $getData = $this->Common_model->getDataFromTable('tbl_users','id,password', $whereField=['id'=>$this->input->post('user_id'),'password' => md5($this->input->post('current_password'))], $whereValue='',$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                        if(isset($getData[0]['id'])){
                            $data['password'] = md5($this->input->post('password'));  
                            $userid = $this->input->post('user_id');
                            $update = $this->Common_model->updateDataFromTabel('tbl_users',$data,'id',$userid);
                            if($update){
                                 $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Password Updated Successfully'];
                                 $this->response($response);
                            }
                        }
                        else{
                           throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' =>'Invalid Current Password'])); 
                        }
                    }
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function postEnquiry(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $errmsg = '';
                $mandatoryFields = ['client_id','subject','message'];
                foreach($mandatoryFields as $row){
                	$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                	$this->form_validation->set_rules($row, $fieldname, 'required'); 
                }
                if($this->form_validation->run() == FALSE){
                    $errorMessage = strip_tags(validation_errors());
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errorMessage]));
                }else{
                    foreach($this->input->post() as $fieldname=>$fieldvalue){
                     $data[$fieldname]= $this->input->post($fieldname);
                    }
                    $data['created_on'] = current_datetime();
                    $ins = $this->Common_model->addDataIntoTable('tbl_enquiries',$data);
                    if($ins){
                     $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Enquiry Submitted Successfully'];
                     $this->response($response);
                    }
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function getSettings($type = ''){
	    try{
            if($this->input->server('REQUEST_METHOD')!='GET'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                if($type==''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => "Some param is missing"]));
                }else{
                    $data = $this->Common_model->getDataFromTable('tbl_settings','description', $whereField=['context' => $type], $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
                    if(is_array($data)){
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Found','data'=>$data[0]];
                         $this->response($response);
                    }else{
                        throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => "Invalid param"]));
                    }
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function addTform(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $errmsg = '';
                $mandatoryFields = ['client_id','financial_year'];
                foreach($mandatoryFields as $row){
                	$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                	$this->form_validation->set_rules($row, $fieldname, 'required'); 
                }
                if(!isset($_FILES['attachment'])){
                	$this->form_validation->set_rules('attachment','Attachment', 'required'); 
                }
                if($this->form_validation->run() == FALSE){
                    $errorMessage = strip_tags(validation_errors());
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errorMessage]));
                }else{
                    $checkClient = $this->Common_model->check_exists('tbl_users','id',$_POST['client_id'],'','');
                    if($checkClient == 1){
                        foreach($this->input->post() as $fieldname=>$fieldvalue){
                            $insdata[$fieldname]= $this->input->post($fieldname);
                        }
                        
                        $financialYear = $this->Common_model->getDataFromTable('tbl_financial_years','financial_year', $whereField='id', $whereValue=$insdata['financial_year'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);

                        if (!is_dir('uploads/'.$insdata['client_id'])) {
                             mkdir('./uploads/' . $insdata['client_id'], 0777, TRUE);
                        }
                        if (!is_dir('uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'])) {
                             mkdir('./uploads/' . $insdata['client_id'].'/'.$financialYear[0]['financial_year'], 0777, TRUE);
                        }
                        
                        $dirPath = 'uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'];
                        $_FILES['tmpName']['name'] = $_FILES['attachment']['name'];
                        $_FILES['tmpName']['type'] = $_FILES['attachment']['type'];
                        $_FILES['tmpName']['tmp_name'] = $_FILES['attachment']['tmp_name'];
                        $_FILES['tmpName']['error'] = $_FILES['attachment']['error'];
                        $_FILES['tmpName']['size'] = $_FILES['attachment']['size'];
                        $response = $this->Common_model->uploadProfileImage($dirPath,"tmpName");
                        if(!empty($response) && $response['status']=="success") {
                          $insdata['document'] = 'uploads/'.$insdata['client_id'].'/'.$financialYear[0]['financial_year'].'/'.$response['imageName'];
                        }else{
                         $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => $response['message']];
                         $this->response($response);exit;
                        }
                        $twhere['client_id'] = $insdata['client_id'];
                        $twhere['financial_year'] = $insdata['financial_year'];
                        $checkTform = $this->Common_model->check_exists('tbl_tforms',$twhere,'','','');
                        if($checkTform == 1){
                            $insdata['updated_on'] =  current_datetime();
                            $ins = $this->Common_model->updateDataFromTabel('tbl_tforms',$insdata,$twhere,'');
                            $msg = "Tform Updated Successfully";
                        }else{
                            $insdata['created_on'] =  current_datetime();
                            $ins = $this->Common_model->addDataIntoTable('tbl_tforms',$insdata);
                            $msg = "Tform Submitted Successfully";
                        }
                        if($ins){
                         $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => $msg];
                         $this->response($response);
                        }
                    }else{
                        $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Invalid Client Id'];
                        $this->response($response);
                    }
                }
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function getTform(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['client_id','form_id'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                    $where['client_id'] = $postData['client_id'];
                    $where['id'] = $postData['form_id'];
                    $where['status'] = 'Active';
                    $tform = $this->Common_model->getDataFromTable('tbl_tforms','id,client_id,financial_year,document', $whereField=$where, $whereValue='',$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                    if(is_array($tform) && count($tform)>0){
                        $data = $tform[0];
                        $data['document'] = base_url($data['document']);
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Found','data' => $data];
                    }else{
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Invalid Form Id'];
                    }
                    
                    $this->response($response);
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function listTforms(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $url = base_url();
                $postData = json_decode(file_get_contents('php://input'),1);
                if(!empty($postData['client_id'])){
                    $orderByColumn = "tfs.id";
                    $sortType = 'DESC';
                    $indexColumn='tfs.id';
                    $selectColumns = ['tfs.id','concat("'.$url.'",tfs.document) as document','tfs.created_on','tfs.status','tf.financial_year'];
                    $dataTableSortOrdering='';
                    $table_name='tbl_tforms as tfs';
                    $joinsArray[] = ['table_name'=>'tbl_financial_years as tf','condition'=>"tf.id = tfs.financial_year",'join_type'=>'left'];
                    $whereCondition = "tfs.id!='' and tfs.client_id=".$postData['client_id'];
                    
                    $listData = $this->Datatables_model->getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$whereCondition,$indexColumn,'',$orderByColumn,$sortType,true,'POST');
                    $data = $listData['data'];
                    if(count($data)>0){
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Found','data' => $data];
                    }else{
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' =>'Data Not Found'];
                    }
                    $this->response($response);
                    
                }else{
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Client Id is required']));
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function dashboardPiechart(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $postData = json_decode(file_get_contents('php://input'),1);
                $financialYear = $this->Common_model->getFinancialYear('id')['id'];
                if(isset($postData['client_id']) && !empty($postData['client_id'])){
                    $data = $this->db->query("SELECT sum(bill_amount) as value,category_name as title FROM `tbl_bills` LEFT JOIN tbl_categories on tbl_categories.id = tbl_bills.category_id where financial_year = '$financialYear' and client_id='".$postData['client_id']."' and bill_type = 'Expense' GROUP by category_id")->result_array();
                    if(count($data)>0){
                        $i = 0;
                        $resp['response_code'] = 0;
                        $resp['statuscode'] = 200;
                        foreach($data as $d){
                            $res[$i]['value'] = $d['value'];
                            $res[$i]['title'] = $d['title'];
                            $res[$i]['color'] = RAND_COLORS[$i];
                            $i++;
                        }
                        $resp['pie1'] = $res;
                    }else{
                        $resp['response_code'] = 1;
                        $resp['statuscode'] = 200;
                    }
                    $data2 = $this->db->query("SELECT sum(bill_amount) as value,category_name as title FROM `tbl_bills` LEFT JOIN tbl_categories on tbl_categories.id = tbl_bills.category_id where financial_year = '1' and client_id='".$postData['client_id']."' and bill_type = 'Income' GROUP by category_id")->result_array();
                    if(count($data2)>0){
                        $i = 0;
                        $resp['response_code'] = 0;
                        $resp['statuscode'] = 200;
                        foreach($data2 as $dd){
                            $res[$i]['value'] = $dd['value'];
                            $res[$i]['title'] = $dd['title'];
                            $res[$i]['color'] = RAND_COLORS[$i];
                            $i++;
                        }
                        $resp['pie2'] = $res;
                    }else{
                        $resp['response_code'] = 1;
                        $resp['statuscode'] = 200;
                    }
                    echo json_encode($resp);
                }else{
                  throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Client Id is required']));  
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	
	public function dashboardBargraph(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $postData = json_decode(file_get_contents('php://input'),1);
                $financialYear = $this->Common_model->getFinancialYear('id')['id'];
                if(isset($postData['client_id']) && !empty($postData['client_id'])){
                    $data = $this->db->query("SELECT month(created_on) as period,sum(case when bill_type = 'Expense' then bill_amount else 0 end) as Expenses,sum(case when bill_type = 'Income' then bill_amount else 0 end) as Income  from tbl_bills where financial_year = '$financialYear' and client_id = '".$postData['client_id']."' GROUP by month(created_on)")->result_array();
                    if(count($data)>0){
                        $i = 0;
                        $resp['response_code'] = 0;
                        $resp['statuscode'] = 200;
                        foreach($data as $d){
                            $res[$i]['income'] = $d['Income'];
                            $res[$i]['expense'] = $d['Expenses'];
                            $res[$i]['month'] = $d['period'];
                            $i++;
                        }
                        $resp['chart'] = $res;
                    }else{
                        $resp['response_code'] = 1;
                        $resp['statuscode'] = 200;
                    }
                    
                    echo json_encode($resp);
                }else{
                  throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Client Id is required']));  
                }
            }
	    }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
    public function addAttachments(){
	    try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'IPnvalid HTTP Request Method']));
            }else{
                $errmsg = '';
                $mandatoryFields = ['client_id','document_name'];
                
                foreach($mandatoryFields as $row){
                	$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                	$this->form_validation->set_rules($row, $fieldname, 'required'); 
                }
                if($this->form_validation->run() == FALSE){
                    $errorMessage = strip_tags(validation_errors());
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errorMessage]));
                }else{
                if(!isset($_FILES['attachment'])){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Attachments are required']));
                }
                    $checkClient = $this->Common_model->check_exists('tbl_users','id',$_POST['client_id'],'','');
                    if($checkClient == 1){
                        if (!is_dir('uploads/'.$_POST['client_id'])) {
                             mkdir('./uploads/' . $_POST['client_id'], 0777, TRUE);
                        }
                        $dirPath = 'uploads/'.$_POST['client_id'];
                        if(isset($_FILES['attachment'])){
                            for($i=0;$i<count($_FILES['attachment']['name']);$i++){
                                $_FILES['tmpName']['name'] = $_FILES['attachment']['name'][$i];
                                $_FILES['tmpName']['type'] = $_FILES['attachment']['type'][$i];
                                $_FILES['tmpName']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                                $_FILES['tmpName']['error'] = $_FILES['attachment']['error'][$i];
                                $_FILES['tmpName']['size'] = $_FILES['attachment']['size'][$i];
                                $response = $this->Common_model->uploadProfileImage($dirPath,"tmpName");
                                if(!empty($response) && $response['status']=="success") {
                                  $bills['document'] = base_url('uploads/'.$_POST['client_id'].'/'.$response['imageName']);
                                  $bills['client_id'] = $_POST['client_id'];
                                  $bills['document_name'] = $_POST['document_name'];
                                  $bills['uploaded_by'] = 'Client';
                                  $bills['created_on'] = current_datetime();
                                  $billsins = $this->Common_model->addDataIntoTable('tbl_client_docs',$bills);
                                }else{
                                 $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => $response['message']];
                                 $this->response($response);exit;
                                }
                            }
                        }
                        if($billsins){
                         $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'Attachment Uploaded Successfully'];
                         $this->response($response);
                        }
                    }else{
                        $response = ['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Invalid Client Id'];
                        $this->response($response);
                    }
                }
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = ['response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage()];
                $this->response($message);exit;
            }
        }
	}
	
	public function response($responseArray){
        $statuscode = '200';
        $message = 'OK';
        if($statuscode != '200'){
            $message = 'Error';
        }
        header("HTTP/1.1 ". $statuscode . " ". $message);
        header("Content-Type: application/json");
        echo json_encode($responseArray);
        unset($responseArray['statuscode']);
        exit;
    }
}