<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct()
	{
        parent::__construct();
        $this->load->model(array('login_model'=>'login_model'));
	} 

    // public function user_register()
    // {
    //     try{
    //         if($this->input->server('REQUEST_METHOD')!='POST'){
    //             throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
    //         }else{
    //             $errmessage = '';
    //             $postData = json_decode(file_get_contents('php://input'),1);
    //             $mandatoryfields = ['name','phone_number','username','password'];
    //             foreach($mandatoryfields as $fields){
    //                 if(!isset($postData[$fields]) || $postData[$fields]==''){
    //                   $errmessage.= str_replace("_"," ",$fields).", ";
    //                 }
    //             }
    //             if($errmessage!=''){
    //                 throw new Exception($errmessage." Fields are mandatory");
    //             }else{
    //                 $checkexists = $this->Common_model->check_exists('users','username',$postData['username'],'','');
    //                 if($checkexists>0){
    //                     throw new Exception($postData['username']." is already exists");
    //                 }else{

    //                     foreach($postData as $f=>$v){
    //                       $data[$f] = $v;
    //                     }
    //                     $data['password'] = hash('sha256',$data['password']);
    //                     $data['role'] = 'User';
    //                     $data['created_on'] = date('Y-m-d H:i:s'); 
    //                     $res =  $this->Common_model->addDataIntoTable('users',$data);
    //                     $getTemplate = $this->Common_model->getDataFromTable('tbl_emailtemplates','*', $whereField='id', $whereValue='3',$orderBy='id', $order='DESC', $limit='', $offset=0, true);
    //                     $Subject = $getTemplate[0]['subject'];
    //                     $otherCC = $getTemplate[0]['other_emails'];
    //                     $emaildata['email_body'] = $getTemplate[0]['template_body'];
    //                     $emaildata['email_body'] = str_replace("##NAME##",$data['name'],$emaildata['email_body']);
    //                     $emaildata['email_body'] = str_replace("##SITENAME##",SITENAME,$emaildata['email_body']);
    //                     $enduserHTML = $this->load->view('admin/userRegistration',$emaildata,true);
    //                     $send = $this->Email_model->send($data['username'],$Subject,$enduserHTML,$otherCC);
    //                     $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'User Registered Successfully'];
    //                     $this->response($response);
    //                 }
    //             }
    //         }
    //     }catch(Exception $e){
    //         $data = unserialize($e->getMessage());
    //         if(is_array($data)){
    //             $this->response($data);exit;
    //         }else{
    //             $message = array('response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage());
    //             $this->response($message);exit;
    //         }
    //     }
    // }
    
    
    public function user_login()
    {
        try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['username','password','device_token'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                    $response = $this->login_model->validate_user_api($postData);
                    if($response=='Error'){
                        throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Invalid Username or Password']));
                    }else if($response=='Status Error'){
                        throw new Exception(serialize(['response_code' => 1, 'statuscode' => 200, 'response_desc' => 'Your account is inactivated. Please contact admin']));
                    }else if($response=='Success'){
                        $apilog['user_id'] = $this->session->id;
                        $apilog['bearer_token'] = $this->Common_model->getRandomString();
                        $apilog['created_on'] = date('Y-m-d H:i:s'); 
                        $res =  $this->Common_model->addDataIntoTable('tbl_api_user_login',$apilog);
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'User Login Successfully','user_id' => $this->session->id, 'name' => $this->session->name,'role' => $this->session->client_type,'bearer' => $apilog['bearer_token']];
                        $this->response($response);
                    }
                }
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = array('response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage());
                $this->response($message);exit;
            }
        }
    }
    
    public function forgot_password()
    {
        try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['username'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                    $data['username'] = $postData['username'];
                    $checkuser = $this->Common_model->check_exists('tbl_users','username',$data['username'],'','');
                    if($checkuser==1)
                    {
                        $insdata['password_reset_token'] = substr(uniqid(),0,9);
                        $insdata['password_reset_created'] = date('Y-m-d h:i:s');
                        $data['record'] = $this->Common_model->getDataFromTable('tbl_users','*', $whereField='username', $whereValue=$data['username'],$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                        $getTemplate = $this->Common_model->getDataFromTable('tbl_emailtemplates','*', $whereField='id', $whereValue='4',$orderBy='id', $order='DESC', $limit='', $offset=0, true);
                        $Subject = $getTemplate[0]['template_subject'];
                        $otherCC = $getTemplate[0]['template_otheremails'];
                        $emaildata['email_body'] = $getTemplate[0]['template_body'];
                        $emaildata['email_body'] = str_replace("##NAME##",$data['record'][0]['first_name'],$emaildata['email_body']);
                        $emaildata['email_body'] = str_replace("##SITENAME##",SITENAME,$emaildata['email_body']);
                        $emaildata['email_body'] = str_replace("##OTP##",$insdata['password_reset_token'],$emaildata['email_body']);
                        $enduserHTML = $this->load->view('email_template',$emaildata,true);
                        $update =  $this->Common_model->updateDataFromTabel('tbl_users',$insdata,'id',$data['record'][0]['id']);
                        $send = $this->Email_model->send($data['record'][0]['email_id'],$Subject,$enduserHTML,$otherCC);
                        $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'Reset Token sent to your email'];
                        $this->response($response);
                    }else{
                        throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid Email Id']));
                    }
                }
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = array('response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage());
                $this->response($message);exit;
            }
        }
    }

    
     public function reset_password()
    {
        try{
            if($this->input->server('REQUEST_METHOD')!='POST'){
                throw new Exception(serialize(['response_code' => 1, 'statuscode' => 405, 'response_desc' => 'Invalid HTTP Request Method']));
            }else{
                $errmessage = '';
                $postData = json_decode(file_get_contents('php://input'),1);
                $mandatoryfields = ['username','password_reset_token','new_password','confirm_password'];
                foreach($mandatoryfields as $fields){
                    if(!isset($postData[$fields]) || $postData[$fields]==''){
                       $errmessage.= str_replace("_"," ",$fields).", ";
                    }
                }
                if($errmessage!=''){
                    throw new Exception(serialize(['response_code' => 0, 'statuscode' => 200, 'response_desc' => $errmessage." Fields are mandatory"]));
                }else{
                    $data['username'] = $postData['username'];
                    $data['password_reset_token'] = $postData['password_reset_token'];
                    $data['user_type'] = 'Client';
                    $checkuser = $this->db->get_where('tbl_users',$data);
                    if($checkuser->num_rows()==1)
                    {
                        $userdata = $checkuser->row();
                        $created_date = strtotime($userdata->password_reset_created);
                        $current_date = strtotime(date('Y-m-d h:i:s'));
                        $diff = $current_date-$created_date;
                        $hours = $diff/60/60;
                        if($hours<=24)
                        {
                            $updata['password'] = md5($postData['new_password']); 
                            $updata['password_reset_token'] = '';
                            $update =  $this->Common_model->updateDataFromTabel('tbl_users',$updata,'id',$userdata->id);
                            if($update)
                            {
                                $response = ['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'Password Updated Successfully'];
                                $this->response($response);exit;
                            }
                        }else{
                            throw new Exception(serialize(['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'One Time Access Code Expired, Please Try Again']));
                        }
                    }else{
                        throw new Exception(serialize(['response_code' => 0, 'statuscode' => 200, 'response_desc' => 'Invalid Details, Please Try Again']));
                    }
                }
            }
        }catch(Exception $e){
            $data = unserialize($e->getMessage());
            if(is_array($data)){
                $this->response($data);exit;
            }else{
                $message = array('response_code' => 1, 'statuscode' => 422, 'block' => 'Exception', 'response_desc' => $e->getMessage());
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
?>