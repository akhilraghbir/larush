<?php
    if (!defined('BASEPATH'))exit('No direct script access allowed');
    class User_model extends CI_Model
    {
        function __construct()
        {
            parent::__construct();

        }
        function validate_user(){
            $this->db->select('*');
            $this->db->from('tbl_users');
            $this->db->where('email_id', $this->input->post('username'));
            $this->db->where('password', md5($this->input->post('password')));
            $this->db->limit(1);
            $query = $this->db->get();
            if($query->num_rows() == 1){
               return $query->result();
            }
            else{
                return false;
            }
        }
		/*** update last login time ***/
		function updateLastLogin($postData,$UserId)
		{
			$this->db->where('id',$UserId);
            $this->db->update('tbl_users',$postData);
		}
    }


?>