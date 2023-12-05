<?php 
if (!defined('BASEPATH'))exit('No direct script access allowed');
class Login_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();    
        
    }
    function validate_user(){            
        $password = md5($this->input->post('password'));
        $username = $this->input->post('username');
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('username',$username); 
        $this->db->where('password',$password);
        $this->db->limit(1);
        $query = $this->db->get();   
        if($query->num_rows() == 1){                 
            $data = $query->result();
            if($data[0]->status=='Inactive')
            {
                return "Status Error";
            }else{
                $logindata['name'] = $data[0]->first_name;
                $logindata['client_type'] = $data[0]->client_type;
                
                $logindata['id'] = $data[0]->id;
                $logindata['is_login'] = TRUE;
                $this->session->set_userdata($logindata);
                return 'Success';
            }
        }
        else{
            return 'Error';
        }
    }
    
    function validate_user_api($postdata){            
        $password = md5($postdata['password']);
        $username = $postdata['username'];
        $device_token = $postdata['device_token'];
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('username',$username); 
        $this->db->where('password',$password);
        $this->db->where('user_type','Client');
        $this->db->limit(1);
        $query = $this->db->get();   
        if($query->num_rows() == 1){                 
            $data = $query->result();
            if($data[0]->status=='Inactive')
            {
                return "Status Error";
            }else{
                $this->db->where('id',$data[0]->id);
                $this->db->update('tbl_users',['device_token' => $device_token]);
                $logindata['name'] = $data[0]->first_name;
                $logindata['client_type'] = $data[0]->client_type;
                $logindata['id'] = $data[0]->id;
                $logindata['client_type'] = $data[0]->client_type;
                $logindata['is_login'] = TRUE;
                $this->session->set_userdata($logindata);
                return 'Success';
            }
        }
        else{
            return 'Error';
        }
    }
    
}
?>