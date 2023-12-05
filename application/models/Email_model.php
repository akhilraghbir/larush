<?php 
    if (!defined('BASEPATH'))exit('No direct script access allowed');
    class Email_model extends CI_Model
    {
        function __construct(){
            parent::__construct();    
         
        }

        function send($toaddress,$Subject,$message,$otherCC='') {
            $this->load->library('email');
            $config['protocol']    = 'smtp';
            $config['smtp_host']    = 'smtp.gmail.com';
            //$config['smtp_host']    = 'smtp.office365.com';
            $config['smtp_port']    = '465';
            $config['smtp_port']    = '587';
            $config['smtp_crypto'] = 'tls';
            $config['smtp_timeout'] = '60';
            $config['smtp_user']    = SMTP_EMAIL;
            $config['smtp_pass']    = SMTP_PASS;
            $config['charset']    = 'utf-8';
            $config['newline']    = "\r\n";
            $config['wordwrap']    = TRUE;
            $config['crlf']    = "\r\n";
            $config['mailtype'] = 'html'; // or html
            $config['validation'] = TRUE; // bool whether to validate email or not      
            $this->email->initialize($config);
            $this->email->from(SMTP_EMAIL, SMTP_NAME);
            $this->email->to($toaddress); 
            $this->email->subject($Subject);
            $this->email->message($message);
            $this->email->set_mailtype("html");  
            if($otherCC!=''){
                $this->email->bcc($otherCC);
            }
            if($this->email->send()){
                return true;
            }else{
                echo $this->email->print_debugger();
               exit;
                return false;
            }
        }

        function sendwithAttachment($toaddress,$Subject,$message,$otherCC='',$attachment) {
            $this->load->library('email');
            $config['protocol']    = 'smtp';
            $config['smtp_host']    = 'smtp.gmail.com';
            //$config['smtp_host']    = 'smtp.office365.com';
            $config['smtp_port']    = '465';
            $config['smtp_port']    = '587';
            $config['smtp_crypto'] = 'tls';
            $config['smtp_timeout'] = '60';
            $config['smtp_user']    = SMTP_EMAIL;
            $config['smtp_pass']    = SMTP_PASS;
            $config['charset']    = 'utf-8';
            $config['newline']    = "\r\n";
            $config['wordwrap']    = TRUE;
            $config['crlf']    = "\r\n";
            $config['mailtype'] = 'html'; // or html
            $config['validation'] = TRUE; // bool whether to validate email or not      
            $this->email->initialize($config);
            $this->email->from(SMTP_EMAIL, SMTP_NAME);
            $this->email->to($toaddress); 
            $this->email->subject($Subject);
            $this->email->message($message);
            $this->email->attach($attachment);
            $this->email->set_mailtype("html");  
            if($otherCC!=''){
                $this->email->bcc($otherCC);
            }
            if($this->email->send()){
                return true;
            }else{
                echo $this->email->print_debugger();
               exit;
                return false;
            }
        }
    }

    

?>