<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Home_template {
		function load($template = '', $view = '' , $view_data = array(), $return = FALSE){
			$CI = get_instance();
			//define constant
			define("BASEURL",base_url());
			$data 				= 	$view_data;
			$data['content'] = $CI->load->view($view, $data,true );
			$CI->load->view($template, $data);
		}
	}
