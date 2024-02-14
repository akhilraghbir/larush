<?php

 ////////////////////////// Check Login for authenticaion //////////////////////

	if (!function_exists('check_UserSession')) {
	    function check_UserSession() {
			$ci = get_instance();
	        if ($ci->session->userdata('is_login') != TRUE) {
	            $ci->session->sess_destroy();
	            redirect(base_url('login'));
			}
	    }
	}
	if(!function_exists('loginCheck')){
		function loginCheck(){

            $CI = get_instance();
            if(loggedId()){
				return TRUE;
			}else{
                //this code is user for clear cache
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
                $CI->messages->setMessageFront('Please login first.','error');
				redirect('login');
			}
		}
    }

	if(!function_exists('logedId')){
		 function loggedId(){
			$CI = get_instance();
			$userId  = $CI->session->userdata('id');
			if($userId>0){
				return $userId;
			}
			else{
				return FALSE;
			}
		 }
	}

	if(!function_exists('emailId')){
		 function emailId(){
			$CI = get_instance();
			$userEmailId  = $CI->session->userdata('email_id');
			if($userId>0){
				return $userEmailId;
			}
			else{
				return FALSE;
			}
		 }
	}

	if(!function_exists('adminLoginCheck')){
		 function adminLoginCheck(){

			$CI = get_instance();
			$admin_type  = $CI->session->userdata('id');
			if($admin_type=='1'){
				return true;
			}else{
				$CI->session->sess_destroy();
				return false;
			}
		}
	}

	if(!function_exists('loadBreadCrumbs'))
	{
		function loadBreadCrumbs($pageTitle,$helpText,$addLink="",$icon='',$actions=array())
		{
			$data=array();
			if($icon==''){
				$data['icon_class']="fa fa-sitemap";
			}else{
				$data['icon_class']=$icon;
			}

			$data['title'] = $pageTitle;
			if($addLink!="")
			{
				$data['addLink'] = $addLink;
			}
			$data['helptext']= $helpText;

			if(isset($actions) && !empty($actions)){

			}
			$data['actions']=$actions;

			return $data;
		}
	}

	//-- current date time function
	if(!function_exists('current_datetime')){
	    function current_datetime(){

	        $dt = new DateTime('now', new DateTimezone('America/Los_Angeles'));
	        $date_time = $dt->format('Y-m-d H:i:s');
	        return $date_time;
	    }
	}
	
	if(!function_exists('current_date')){
	    function current_date(){
	        $dt = new DateTime('now', new DateTimezone('America/Los_Angeles'));
	        $date_time = $dt->format('d-m-Y');
	        return $date_time;
	    }
	}
	
	if(!function_exists('current_daydate')){
	    function current_daydate(){
	        $dt = new DateTime('now', new DateTimezone('America/Los_Angeles'));
	        $date_time = $dt->format('l, jS, F Y');
	        return $date_time;
	    }
	}
	

	if(!function_exists('displayCustomDateTime')){
	    function displayCustomDateTime($date){
	        if($date != ''){
	            $date2 = date_create($date);
	            $date_new = date_format($date2,"d M Y h:i A");
	            return $date_new;
	        }else{
	            return '';
	        }
	    }
	}


	if(!function_exists('displayDateTime')){
	    function displayDateTime($date){
			if($date != '')
			{
	            $date2 = date_create($date);
	            $date_new = date_format( $date2,"d-m-Y H:i");
	            return $date_new;
			}
			else
			{
	            return '';
	        }
	    }
	}
	
	if(!function_exists('displayDateInWords')){
	    function displayDateInWords($date){
			if($date != '')
			{
	            $date2 = date_create($date);
	            $date_new = date_format( $date2,"dS F Y");
	            return $date_new;
			}
			else
			{
	            return '';
	        }
	    }
	}

	if(!function_exists('displayDate')){
	    function displayDate($date){
			if($date != '')
			{
	            $date2 = date_create($date);
	            $date_new = date_format( $date2,"d-m-Y");
	            return $date_new;
			}
			else
			{
	            return '';
	        }
	    }
	}

	if(!function_exists('getDateDiff')){
		function getDateDiff($diffType,$dateTime){
			$currentDate = strtotime(date('Y-m-d H:i:s'));
			$createdDate = strtotime($dateTime);
			$datediff = $currentDate - $createdDate;
			$result = '';
			if($diffType=='DAYS'){
				$result = round($datediff / (60 * 60 * 24)); //returns diff in days
			}else{
				$result = round($datediff / (60 * 60)); //returns diff in hours
			}
			return $result;
		}

	}
	if(!function_exists('convertSingleQuoteString')){
		function convertSingleQuoteString($string){
			$array = array_unique(explode(',',$string));
			$result= implode(',', array_map(function($val){return sprintf("'%s'", $val);}, $array));
			return $result;
		}

	}

    function checkAuth($token){
    if(!empty($token)){
        $token = trim(str_replace("Bearer","",$token));
        $ci=& get_instance();
        $ci->load->database(); 
        $sql ="SELECT * from tbl_api_user_login where bearer_token='$token'";
        $query = $ci->db->query($sql)->num_rows();
        if($query>0){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
    }
    
    function displayfileicon($type){
        if($type == 'png' || $type == 'jpg' || $type == 'jpeg'){
            return "<i class='icon-docs f-20'></i>";
        }else{
            return "<i class='icon-docs f-20'></i>";
        }
    }
    
    function months(){
        return ['01' => 'Jan','02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' =>'Jun','07' => 'Jul', '08' => 'Aug','09' => 'Sept' , '10' => 'Oct','11' => 'Nov', '12' => 'Dec'];
    }
	function getPriorityDivClass($priority){
		$divClass = ['3'=>'border-danger','2'=>'border-warning','1'=>'border-info'];
		return $divClass[$priority];
	}

	function getPriorityBtnClass($priority){
		$divClass = ['3'=>'btn-danger','2'=>'btn-warning','1'=>'btn-info'];
		return $divClass[$priority];
	}
	