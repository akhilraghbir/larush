<?php
class MY_Form_validation extends CI_Form_validation {

    public function __construct()
    {
        parent::__construct();
    }

    public function clear_field_data() {
		unset($_SESSION['recording']);
		return true;
	}

	public function set_session_data($post){
		$_SESSION['recording']=$post;
		return true;
	}

	public function get_session_data(){
		if(isset($_SESSION['recording'])){
			return $_SESSION['recording'];
		}else{
			return false;
		}

	}

	public function checkXssValidation($post,$ajax=''){
		$htmlTags = array("<script>",
					"alert(",
					"&lt;script&gt;",
					"script&gt;",
					"<iframe>",
					"&lt;iframe",
					"<iframe",
					"&lt;iframe&gt;",
					"iframe&gt;",
					"<script",
					"script>",
					"&lt;script",
					"object>",
					"<object>",
					"&lt;object",
					"&lt;html",
					"&lt;body",
					"<html",
					"<body",
					"javascript",
					"hack",
					"hacked",
					".js"

					);
		$errorMessage = '';
		foreach ($post as $fieldname => $fieldValue) {
			$string = $fieldValue;
			$harmdata='';
			if($string!='' && !is_array($string)){
			foreach ($htmlTags as $find) {
				if (preg_match("/" . preg_quote($find, '/') . "/i", $string)) {
					$harmdata=1;
				}
			}
			if($harmdata==1){
					$errorMessage.="The Field ".ucwords(str_replace('_',' ',$fieldname)). " Contains Harmful Data<br>";
			}
			}
		}

			if(!empty($errorMessage)){
				if($ajax=='ajax'){
					$errorMessage=str_replace("<br>", "", "$errorMessage");
					$message=array("error"=>'1',"msg"=>"$errorMessage");
					echo json_encode($message);
					exit;
				}else{
					$this->CI =& get_instance();
					$this->CI->messages->setMessageFront($errorMessage,'error');
					header('Location: '.$_SERVER['HTTP_REFERER']);
					exit;
				}

			}
		}

}
