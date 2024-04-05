<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        if (($this->session->userdata('user_type') != '') && ($this->session->userdata('id') != '')) {
            redirect('administrator/dashboard');
        }
        $data['title'] = "Login";
        $this->load->view('login', $data);
    }

    public function userlogin()
    {
        if ($this->input->post()) {
            $this->form_validation->set_session_data($this->input->post());
            $this->form_validation->checkXssValidation($this->input->post());
            $this->form_validation->set_rules('username', 'email', 'required|trim');
            $this->form_validation->set_rules('password', 'password', 'required|trim');
            if ($this->form_validation->run() === FALSE) {
                $this->messages->setMessageFront('Username or password is required', 'error');
                redirect(base_url());
                return FALSE;
            } else {
                $email = strtolower($this->input->post('username'));
                $password = $this->input->post('password');
                $userdata = $this->user_model->validate_user($email, $password);
                if ($userdata) {
                    if ($userdata[0]->status != "Active") {
                        $this->messages->setMessageFront('Your Account Has Been Deactivated. Please Contact Your Administrator.', 'error');
                        redirect(base_url('login'));
                    } else {
                        foreach ($userdata as $row) {
                            $userType = $row->user_type;
                            $checkSession = $this->Common_model->getDataFromTable('tbl_user_sessions','user_id,token',  $whereField='user_id', $whereValue=$row->id, $orderBy='id', $order='DSEC', $limit='1', $offset='0', true);
							$sessionToken['user_id'] = $row->id;
							$sessionToken['token'] = $this->Common_model->getRandomString();
							if(empty($checkSession[0])){
								$sessionToken['created_on'] = current_datetime();
								$this->Common_model->addDataIntoTable('tbl_user_sessions',$sessionToken);
							}else{
								$sessionToken['updated_on'] = current_datetime();
								$this->Common_model->updateDataFromTable('tbl_user_sessions',$sessionToken,'user_id',$row->id);
							}
							$data = ['id' => $row->id,
                                'name' => $row->first_name,
                                'email' => $row->email_id,
                                'user_type' => $row->user_type,
                                'is_login' => true,
                                'session_token' => $sessionToken['token']];
                            $loggedInUserId = $row->id;
                            $updateLastLoginData = array("last_logged_on" => current_datetime());
                            $this->user_model->updateLastLogin($updateLastLoginData, $loggedInUserId);
                            $this->session->set_userdata($data);
                        }
                        $this->messages->setMessage('You have Logged in successfully !', 'success');
                        $redirectionURL = "dashboard";
                        redirect(base_url() . 'administrator/' . $redirectionURL);
                    }
                } else {
                    $this->messages->setMessageFront('Invalid email or password.', 'error');
                    redirect(base_url());
                }
            }
        } else {
            $this->messages->setMessageFront('Invalid email or password.', 'error');
            redirect(base_url());
        }
    }

    public function doLogout()
    {
        $array_items = array('id', 'user_type');
        $this->session->sess_destroy();
        $this->messages->setMessageFront($this->lang->line('You Have Logged Out Successfully.'), 'success');
        redirect(base_url('login'));
    }
    public function forgotPassword()
    {
        $this->load->model('Email_model');
        if ($this->input->post()) {
            $this->form_validation->set_session_data($this->input->post());
            $this->form_validation->checkXssValidation($this->input->post());
            $this->form_validation->set_rules('username', 'Email ID', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->form_validation->set_session_data($this->input->post());
                $errorMessage = validation_errors();
                $this->messages->setMessageFront($errorMessage, 'error');
                redirect(base_url() . 'forgot-password');
            } else {
                $email = $this->input->post('username');
                $record = $this->Common_model->getDataFromTable('tbl_users', '*', 'email_id', $email, '', '', '', '', true);
                if (!empty($record) && sizeof($record) > 0) {
                    $randomString = $this->Common_model->getRandomString();
                    $userId = $record[0]['id'];
                    if ($userId != '') {
                        $data = array();
                        $data['password_reset_token'] = $randomString;
                        $data['password_reset_created'] = current_datetime();
                        if ($this->Common_model->updateDataFromTabel('tbl_users', $data, 'id', $userId)) {
                            $getTemplate = $this->Common_model->getDataFromTable('tbl_emailtemplates','*', $whereField='id', $whereValue=2, $orderBy='', $order='', $limit=1, $offset=0, true);
                            $Subject = $getTemplate[0]['template_subject'];
                            $otherCC = $getTemplate[0]['template_otheremails'];
                            $emaildata['email_body'] = $getTemplate[0]['template_body'];
                            $siteUrl = base_url();
                            
                            $emaildata['email_body'] = str_replace("##NAME##",$record[0]['first_name'],$emaildata['email_body']);
                            $emaildata['email_body'] = str_replace("##SITENAME##",SITENAME,$emaildata['email_body']);
                            $emaildata['email_body'] = str_replace("##RESETURL##",base_url("login/resetPassword"),$emaildata['email_body']);
                            $emaildata['email_body'] = str_replace("##LOGINURL##",base_url(),$emaildata['email_body']);
                            $emaildata['email_body'] = str_replace("##OTP##",$data['password_reset_token'] ,$emaildata['email_body']);
                            $enduserHTML = $this->load->view('email_template',$emaildata,true);
                            $toaddress = $record[0]['email_id'];
                            $send = $this->Email_model->send($toaddress, $Subject, $enduserHTML, $otherCC = '');
                            if ($send) {
                                $this->form_validation->clear_field_data();
                                $this->messages->setMessageFront("Password Reset Token Sent To Your Email Please Check Your Email ", 'success');
                                redirect(base_url() . 'forgot-password');
                            } else {
                                $this->email->print_debugger();
                                exit;
                            }
                        } else {
                            $this->messages->setMessageFront("Something Went Wrong, Please Try Again ", 'error');
                            redirect(base_url() . 'forgot-password');
                        }
                    } else {
                        $this->messages->setMessageFront("Email Id Not Found", 'error');
                        redirect(base_url() . 'forgot-password');
                    }
                } else {
                    $this->messages->setMessageFront("Email Id Doesn't Exist", 'error');
                    redirect(base_url() . 'forgot-password');
                }
            }
        }
        $data['title'] = "Forgot Password";
        $this->load->view('forgotPassword', $data);
    }

    public function resetPassword()
    {
        if ($this->input->post()) {
            $this->form_validation->set_session_data($this->input->post());
            $this->form_validation->checkXssValidation($this->input->post());

            $mandatoryFields = array('username', 'security_token', 'password', 'confirm_password');
            foreach ($mandatoryFields as $row) {
                $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
                $this->form_validation->set_rules($row, $fieldname, 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                $errorMessage = validation_errors();
                $this->messages->setMessageFront($errorMessage, 'error');
                redirect(base_url() . 'login/resetPassword');
            } else {

                if ($this->input->post('password') != $this->input->post('confirm_password')) {
                    $this->messages->setMessageFront("Password and Confirm Password Didn't Matched", 'error');
                    redirect(base_url() . 'login/resetPassword');
                    exit;
                }

                $token = $this->input->post('security_token');
                $email = $this->input->post('username');
                $whereCond = "email_id='$email' and password_reset_token='$token' ";
                $checkExists = $this->Common_model->getSelectedFields('tbl_users', '*', $whereCond);
                if (!empty($checkExists) && sizeof($checkExists) > 0) {

                    if (md5($this->input->post('password')) == $checkExists[0]['password']) {
                        $this->messages->setMessageFront("Your New Password and Old Password Are Same", 'error');
                        redirect(base_url() . 'login/resetPassword');
                        exit;
                    }
                    $tokenGeneratedTime = $checkExists[0]['password_reset_created'];
                    $currentTime = current_datetime();

                    $seconds = strtotime($currentTime) - strtotime($tokenGeneratedTime);
                    $hours = $seconds / 60 / 60;
                    if ($hours > 24) {
                        $this->messages->setMessageFront("Token Expired. Please Generate A New Token", 'error');
                        redirect(base_url() . 'login/forgotPassword');
                    } else {
                        $data = array();
                        $userId = $checkExists[0]['id'];
                        $data['password'] = md5($this->input->post('password'));
                        $data['password_reset_token'] = '';
                        if ($this->Common_model->updateDataFromTabel('tbl_users', $data, 'id', $userId)) {
                            $this->form_validation->clear_field_data();
                            $this->messages->setMessageFront("Password Updated. Please Login", 'success');
                            redirect(base_url() . 'login');
                        } else {
                            $this->messages->setMessageFront("Something Went Wrong, Please Try Again", 'error');
                            redirect(base_url() . 'login/resetPassword');
                        }
                    }
                } else {
                    $this->messages->setMessageFront("Email ID And Security Token Didn't Matched", 'error');
                    redirect(base_url() . 'login/resetPassword');
                }
            }
        }
        $data['title'] = "Reset Password";
        $this->load->view('resetPassword', $data);
    }
}
