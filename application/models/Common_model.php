<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Common_model extends CI_Model{

    function __construct(){
        parent::__construct();
    }

	function countResult($table='',$field='',$value='', $limit=0,$groupBy = ''){

		if(is_array($field)){
			foreach($field as $key => $val){
				$this->db->where($key,$val);
			}
		}
		elseif($field!='' && $value!=''){
			$this->db->where($field, $value);
		}
		$this->db->from($table);
		if(!empty($groupBy)){
			$this->db->group_by($groupBy);
		}

		if($limit >0){
			$this->db->limit($limit);
		}

		 $res= $this->db->count_all_results();
		 //echo $this->db->last_query(); exit;
		 return $res;

	}


	/*
	 * @description: This function is used getDataFromTabelWhereIn
	 *
	 */

	function getDataFromTableWhereIn($table='', $field='*',  $whereField='', $whereValue='', $orderBy='', $order='ASC', $whereNotIn=0){

		$table=$table;
		 $this->db->select($field);
		 $this->db->from($table);

		if($whereNotIn > 0){
			$this->db->where_not_in($whereField, $whereValue);
		}else{
			$whereValue = stripslashes($whereValue);
			$this->db->where_in($whereField, $whereValue,false);
		}

		if(is_array($orderBy) && count($orderBy)){
			/* $orderBy treat as where condition if $orderBy is array  */
			$this->db->where($orderBy);
		}
		elseif(!empty($orderBy)){
			$this->db->order_by($orderBy, $order);
		}

		$query = $this->db->get();

		$result = $query->result_array();
		if(!empty($result)){
			return 	$result;
		}
		else{
			return FALSE;
		}
	}


	/*
	 * @description: This function is used getDataFromTabel
	 *
	 */

	function getObjectDataFromTable($table='', $field='*',  $whereField='',$whereInField='',$whereNotIn=''){

		$table=$table;
		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($whereField);
		if($whereInField!=''){
			$this->db->where_not_in($whereInField, $whereNotIn);
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->row();

		return 	$result;
	}

	/*
	 * @description: This function is used getDataFromTabelWhereWhereIn
	 *
	 */

	function getDataFromTableWhereWhereIn($table='', $field='*',  $where='',  $whereinField='', $whereinValue='', $orderBy='', $whereNotIn=0){

		$table=$table;
		 $this->db->select($field);
		 $this->db->from($table);

		if(is_array($where)){
			$this->db->where($where);
		}

		if($whereNotIn > 0){
			$this->db->where_not_in($whereinField, $whereinValue);
		}else{
			$this->db->where_in($whereinField, $whereinValue,FALSE);
		}

		if(!empty($orderBy)){
			$this->db->order_by($orderBy);
		}

		$query = $this->db->get();
		//echo $this->db->last_query();
		$result = $query->result();
		if(!empty($result)){
			return 	$result;
		}
		else{
			return FALSE;
		}
	}


	/*
	 * @description: This function is used getDataFromTabel
	 *
	 */

	public function getDataFromTable($table='', $field='*',  $whereField='', $whereValue='', $orderBy='', $order='ASC', $limit=0, $offset=0, $resultInArray=false, $groupBy='' ){


		$table=$table;
		 $this->db->select($field);
		 $this->db->from($table);

		if(is_array($whereField)){
			$this->db->where($whereField);
		}elseif(!empty($whereField) && $whereValue != ''){
			$this->db->where($whereField, $whereValue);
		}

		if(!empty($orderBy)){
			$this->db->order_by($orderBy, $order);
		}
		if(!empty($groupBy)){
			$this->db->group_by($groupBy);
		}
		if($limit > 0){
			$this->db->limit($limit,$offset);
		}
		$query = $this->db->get();

		//echo $this->db->last_query(); die;
		if($resultInArray){
			$result = $query->result_array();
		}else{
			$result = $query->result();
		}

		if(!empty($result)){
			return 	$result;
		}
		else{
			return FALSE;
		}
	}

	/*
	 * @description: This function is used addDataIntoTabel
	 *
	 */

	function addDataIntoTable($table='', $data=array()){
		$table=$table;
		if($table=='' || !count($data)){
			return false;
		}
		$inserted = $this->db->insert($table , $data);
		$this->db->last_query();
		$ID = $this->db->insert_id();
		return $ID;
	}

	/*
	 * @description: This function is used updateDataFromTabel
	 *
	 */

	function updateDataFromTable($table='', $data=array(), $field='', $ID=0){
		$table=$table;
		if(empty($table) || !count($data)){
			return false;
		}
		else{
			if(is_array($field)){

				$this->db->where($field);
			}else{
				$this->db->where($field , $ID);
			}
			return $this->db->update($table , $data);
		}
	}
	/*
	 * @description: This function is used updateDataFromTabelWhereIn
	 *
	 */

	function updateDataFromTabelWhereIn($table='', $data=array(), $where=array(), $whereInField='', $whereIn=array(), $whereNotIn=false){
		$table=$table;
		if(empty($table) || !count($data)){
			return false;
		}
		else{
			if(is_array($where) && count($where) > 0){

				$this->db->where($where);
			}

			if(is_array($whereIn) && count($whereIn) > 0 && $whereInField != ''){
				if($whereNotIn){
					$this->db->where_not_in($whereInField,$whereIn);
				}else{
					$this->db->where_in($whereInField,$whereIn);
				}
			}
			return $this->db->update($table , $data);
		}
	}


	/*
	 * @description: This function is used deleteRowFromTabel
	 *
	 */

	function deleteRowFromTable($table='', $field='', $ID=0, $limit=0){
		$table=$table;
		$Flag=false;
		if($table!='' && $field!=''){
			if(is_array($ID) && count($ID)){
				$this->db->where_in($field ,$ID);
			}elseif(is_array($field) && count($field) > 0){
				$this->db->where($field);
			}else{
				$this->db->where($field , $ID);
			}
			if($limit >0){
				$this->db->limit($limit);
			}
			if($this->db->delete($table)){
				$Flag=true;
			}
		}
		//echo $this->db->last_query();
		return $Flag;
	}

	/*
	 * @description: This function is used deletelWhereWhereIn
	 *
	 */


	function deletelWhereWhereIn($table='', $where='',  $whereinField='', $whereinValue='', $whereNotIn=0){
		$table=$table;
		if(is_array($where)){
			$this->db->where($where);
		}

		if($whereNotIn > 0){
			$this->db->where_not_in($whereinField, $whereNotIn);
		}else{
			$this->db->where_in($whereinField, $whereinValue);
		}

		if($this->db->delete($table)){
				return true;
		}else{
			return false;
		}
	}


	/*
	 * @description: This function is used deleteRow
	 *
	 */

	function deleteRow($table,$where)
	{
		$table=$table;
		$this->db->delete($table, $where);
		//echo $sql = $this->db->last_query(); die;
		return $this->db->affected_rows();
	}


     /**
     *  function for get Data From Table
     *  param $table, $field, $whereField, $whereValue, $orderBy, $order, $limit, $offset, $resultInArray
     *  return result row
     **/
	function getDataFromTabel($table, $field='*',  $whereField='', $whereValue='', $orderBy='', $order='ASC', $limit=0, $offset=0, $resultInArray=false, $join = '' , $extracondition = ''){

        $this->db->select($field);
        $this->db->from($table);

        if(is_array($whereField)){
            $this->db->where($whereField);
        }elseif(!empty($whereField) && $whereValue != ''){
            $this->db->where($whereField, $whereValue);
        }

        if(!empty($orderBy)){
            $this->db->order_by($orderBy, $order);
        }
        if($limit > 0){
            $this->db->limit($limit,$offset);
        }
        $query = $this->db->get();
        if($resultInArray){
            $result = $query->result_array();
        }else{
            $result = $query->result();
        }
		if(!empty($result)){
            return $result;
        }
        else{
            return FALSE;
        }
	}


     /**
     *  function for update data of table
     *  param $table, $data, $field, $id
     *  return result
     **/
    function updateDataFromTabel($table='', $data=array(), $field='', $id=0){
        if(empty($table) || !count($data)){
            return false;
        }
        else{
            if(is_array($field)){
                $this->db->where($field);
            }else{
                $this->db->where($field, $id);

            }
            return $this->db->update($table , $data);
        }
    }

    /*
    *
    *
    *
    */
	public function insertDataFromTable($tableName, $insertData)
	{
		$query = $this->db->insert($tableName, $insertData);
		$id = $this->db->insert_id();
		return $id;
	}


	function uploadProfileImage($dirPath,$fileName,$allowedExtensions='') {
		$data = array();
		$response=array();

        if (isset($_FILES[$fileName]["name"]) && $_FILES[$fileName]["name"]!="") {

			$shuffledStr = $this->getRandomString();
			$imageName= date("Y-m-d-h-i-s").$shuffledStr;
			$config['upload_path'] = $dirPath;
			if($allowedExtensions!=''){
				$config['allowed_types'] = "$allowedExtensions";
			}else{
				$config['allowed_types'] = 'jpg|png|jpeg|JPG|JPEG|PNG|PDF|pdf';
			}

			$config['file_name'] = $imageName;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$this->upload->do_upload($fileName);

			if($this->upload->display_errors()){
				return array("status" => 'error','message' => $this->upload->display_errors());
			}else{
				$data=$this->upload->data();
            		$file_path=$dirPath.$data['file_name'];
                    //$file_path=$restMenuFolderName.$data['file_name'];
					$this->load->library('image_lib');
					// clear config array
					$config = array();
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= $file_path;
					$config['maintain_ratio'] 	= TRUE;
					$config['create_thumb'] 	= FALSE;
					/*echo $d = $this->compress($dirPath.'/'.$data['file_name'],$dirPath.'/sdff'.$data['file_name'],50);
					exit;
					move_uploaded_file($_FILES[$fileName]['tmp_name'],$d);*/
					$response = array(
						"status" => 'success',
						"imageName" => $data['file_name'],
						"width" => $data['image_width'],
						"height" => $data['image_height']
				    );

			}
		}
		return $response;
	}

	function check_exists($table,$column,$value,$whereField,$whereValue) {
		if(is_array($column)){
      	  $this->db->where($column);
		}else{
			$this->db->where($column,$value);
		}
        if($whereValue) {
            $this->db->where_not_in($whereField, $whereValue);
        }
        return $this->db->get($table)->num_rows();
	}

	function getSelectedFields($table,$columns,$whereCondition,$limit='',$orderby='',$sortby='') {
		$this->db->select($columns);
		$this->db->from($table);
		$this->db->where($whereCondition);
		if($limit!=''){
			$this->db->limit($limit);
		}
		if($orderby!='' && $sortby!=''){
			$this->db->order_by($orderby, $sortby);
		}

		$result= $this->db->get()->result_array();
		if($limit=='1'){
			return $result[0];
		}else{
			return $result;
		}
	}

	function getRandomString(){
		return  $my_rand_strng = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"),-10);
	}



	function check_exists_based_ids($table,$whereField,$whereValue,$column1='',$value1='',$column2='',$value2='',$column3='',$value3='',$column4='',$value4='') {
		if(!empty($value1)){
        	$this->db->where($column1,$value1);
		}
		if(!empty($value2)){
			$this->db->where($column2,$value2);
		}
		if(!empty($value3)){
			$this->db->where($column3,$value3);
		}
		if(!empty($value4)){
			$this->db->where($column4,$value4);
		}
        if($whereValue) {
            $this->db->where_not_in($whereField, $whereValue);
        }
        return $this->db->get($table)->num_rows();
	}
	function create_token ($customer_id) {
		// ***** Generate Token *****
		$char = "bcdfghjkmnpqrstvzBCDFGHJKLMNPQRSTVWXZaeiouyAEIOUY!@#%";
		$token = '';
		for ($i = 0; $i < 47; $i++) $token .= $char[(rand() % strlen($char))];

		// ***** Insert into Database *****
		$sql = "INSERT INTO api_tokens SET `token` = ?, customer_id = ?;";
		$this->db->query($sql, [$token, $customer_id]);

		return array('http_code' => 200, 'token' => $token);
	}

	public function encrypt_url($string) {
		$key = "9798635433334631311335434464530535351313"; //key to encrypt and decrypts.
		$result = '';
		$test = "";
		 for($i=0; $i<strlen($string); $i++) {
		   $char = substr($string, $i, 1);
		   $keychar = substr($key, ($i % strlen($key))-1, 1);
		   $char = chr(ord($char)+ord($keychar));

		   $test[$char]= ord($char)+ord($keychar);
		   $result.=$char;
		 }

		 return base64_encode($result);
	  }

	public function decrypt_url($string) {
		$key = "9798635433334631311335434464530535351313"; //key to encrypt and decrypts.
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
		}
		return $result;
	}
	public function getCommaSeparatedFields($reqiredFields=array(),$dataArray=array()){
        $finalArray = array();
        if(isset($dataArray) && sizeof($dataArray)>0){
            $tempArray=array();
            foreach($dataArray as $eachRow){
                if(isset($eachRow['id'])){
                    $index=$eachRow['id'];
                    $finalArray['results'][$index]=$eachRow;
                    foreach($eachRow as $key=>$value){
                        if(in_array($key,$reqiredFields)){
							if ($key == 'reference_number') {
                                if ($value != '') {
                                    if (isset($tempArray[$key]) && $tempArray[$key] != '') {
                                        $tempArray[$key] = $tempArray[$key] . $value . ',';
                                    } else {
                                        $tempArray[$key] = $value . ',';
                                    }
                                }
                            } else {
                                if ($value != '' && $value > 0) {
                                    if (isset($tempArray[$key]) && $tempArray[$key] != '') {
                                        $tempArray[$key] = $tempArray[$key] . $value . ',';
                                    } else {
                                        $tempArray[$key] = $value . ',';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(isset($tempArray) && sizeof($tempArray)>0){
                foreach($tempArray as $key=>$value){
                    $trimmedValue=trim($value,',');
                    $finalArray['commaSeparated'][$key]=$trimmedValue;
                }
            }
        }

        return $finalArray;
    }

	function get_general_settings($type = '') {
		//echo 'sddf'; exit;
		$result = $this->db->get_where('tbl_settings', array(
		  'option_name' => $type
		))->row()->description;
		return $result == null ? '' : $result;
	  }

	public function convertBase64intoImage($image,$path){
		$output_file = time()."_".rand(0,999).".jpg";
		file_put_contents($path.$output_file, file_get_contents($image));
		return $output_file;
	}

	public function compress($source, $destination, $quality) {
		$info = getimagesize($source);
		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);
		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);
		imagejpeg($image, $destination, $quality);
		return $destination;
	}
	
	public function getClientIds($accountant,$type = ''){
        $where['status'] = 'Active';
        $where['user_type'] = 'Client';
        $where['accountant'] = $_POST['accountant'];
        if($type!=''){
            $where['client_type'] = $type;
        }
	    $data = $this->getDataFromTable('tbl_users','id',  $whereField=$where, $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true); 
	    if(is_array($data)){
	        foreach($data as $d){
	            $ids[] = $d['id'];
	        }
	        return join("','",$ids);
	    }else{
	        return "";
	    }
	}
	
	public function getDocs($ids = ''){
	    if(!empty($ids)){
	        $ids = "'".join("','",$ids)."'";
	        $data = $this->getDataFromTableWhereIn('tbl_bill_attachments','file',  $whereField='bill_id', $whereValue=$ids, $orderBy='id', $order='ASC', $whereNotIn=0);
	        foreach($data as $d){
	            $docs[] = $d['file'];
	        }
	        return $docs;
	    }
	}
	
	public function getFinancialYear($column){
	    if(!empty($column)){
	       $data = $this->getDataFromTable('tbl_financial_years',$column,  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true); 
	       return $data[0];
	    }
	}
	
	function firebase_notification($tokens,$message){
		$url = "https://fcm.googleapis.com/fcm/send";
		$fields = array(
			"to" => $tokens,
			"notification" => $message
		);
	//	echo json_encode($fields);exit;
		//$api_key = 'AAAAjP31C44:APA91bG2SgjpcvopKjr9ZMkyMuktgXWT8tXBT6OnxrMo-XKrsHqelJ-W38czVPcoEhU7IVg4B_nxabLEguKAVvuAMM2ZoweOOPcDywa0JyEXwzgvDdPijMDqH9riSFwIU5LsBM0NRg8o';
		$api_key =  "AAAAmXk6lsc:APA91bHr3pFBpgWcbqsipJSyR_iYRuBXa2iJ2iK6d2eCNIRBsKYey6RPEmUpq5MNKv7dAE65Tgx2z9y0F_7IDIbyoo38gTUhFWWwpYpn2Eb0dwsg6pNrzoW6WwAf9G9cPwregsNXkCM9";	
		$headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );

		$ch = curl_init();
		   curl_setopt($ch, CURLOPT_URL, $url);
		   curl_setopt($ch, CURLOPT_POST, true);
		   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		   curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		   $result = curl_exec($ch);           
		   if ($result === FALSE) {
			   die('Curl failed: ' . curl_error($ch));
		   }
		   return $result;
	}
}

