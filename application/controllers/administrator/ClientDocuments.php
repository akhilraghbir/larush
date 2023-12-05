<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientDocuments extends CI_Controller {
	public function __construct(){
		parent::__construct();
		check_UserSession();
		$this->load->model('Datatables_model');
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-docs";
		$data['title']="Client Documents";
		$data['helptext']="This Page Is Used To Manage The Client Documents.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'ClientDocuments/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'ClientDocuments';
		return $data;
	}
	public function index(){
		$data['breadcrumbs']=$this->loadBreadCrumbs();  
		$this->home_template->load('home_template','admin/client_documents',$data);   
	}
	public function loadMenuForm($formContent=array(), $formName=''){
		$data['breadcrumbs']=$this->loadBreadCrumbs();
		$data['data']=$formContent;
		$data['financial_years'] = $this->Common_model->getDataFromTable('tbl_financial_years','id,financial_year,status',  $whereField="", $whereValue='', $orderBy='', $order='', $limit='', $offset=0, true);
		$data['form_action']=$formName;
		$this->home_template->load('home_template','admin/client_documents',$data); 
	}
	
	public function add(){
		$this->loadMenuForm(array(),'add Client Documents');
	}
	
	
	public function DocsUpload(){
	    if(!empty($_FILES['docs'])){
	        $count_uploaded_files = count( $_FILES['docs']['name'] );
	        $upfile = '';
	        for($i=0;$i<$count_uploaded_files;$i++){
				if (!is_dir('uploads/'.$_POST['client_id'])) {
					mkdir('./uploads/' . $_POST['client_id'], 0777, TRUE);
				}
                $path = $config['upload_path'] = 'uploads/'.$_POST['client_id'].'/';
                $config['allowed_types'] = '|PNG|png|jpg|jpeg|pdf|docx|doc|xls|xlsx|csv|txt';   
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                $_FILES['tmpName']['name'] = $_FILES['docs']['name'][$i];
                $_FILES['tmpName']['type'] = $_FILES['docs']['type'][$i];
                $_FILES['tmpName']['tmp_name'] = $_FILES['docs']['tmp_name'][$i];
                $_FILES['tmpName']['error'] = $_FILES['docs']['error'][$i];
                $_FILES['tmpName']['size'] = $_FILES['docs']['size'][$i];
                if($this->upload->do_upload('tmpName')){
                    $uploadData = $this->upload->data();
                    $filename = $uploadData['file_name'];
                    $url = CONFIG_SERVER_ROOT.$path.$filename;
                    $insdata['client_id'] = $_POST['client_id'];
                    $insdata['document'] = $url;
                    $insdata['document_name'] = $filename;
                    $insdata['created_on'] = current_datetime();
                    $ins = $this->Common_model->addDataIntoTable('tbl_client_docs',$insdata);
					if($ins){
                    	$upfile.= '<div class="uploadedFileDiv" id="div'.$ins.'"><a href="'.$url.'" class="" target="_blank">'.$filename.'</a>&nbsp;&nbsp;<i onclick="deleteDoc('.$ins.')" class="icon-trash"></i></div>';
						$data['status'] = 'success';
					}
                }else{
                    $data['errmsg']  = 'Uploded file error - '.$this->upload->display_errors();
                }
                 $data['uploadedFile'] = $upfile;
	        }
            echo json_encode($data);exit;
        }
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$client_id         =  $this->input->post('client_id');
		$document_name         =  $this->input->post('document_name');
		$uploaded_by         =  $this->input->post('uploaded_by');
		$indexColumn = 'tcd.id';
		$selectColumns = ['tcd.id','cl.first_name','tcd.document_name','tcd.document','tcd.created_on'];
		$dataTableSortOrdering = ['cl.first_name','tcd.document_name','tcd.document','tcd.created_on'];
		$table_name = 'tbl_client_docs as tcd';
		$joinsArray[] = ['table_name'=>'tbl_users as cl','condition'=>"cl.id = tcd.client_id",'join_type'=>'left'];
		$wherecondition = "tcd.id!=''";
        if($client_id!=''){
            $wherecondition.=' and tcd.client_id = '.$client_id;
        }
		if($document_name!=''){
            $wherecondition.=' and document_name like  "%'.$document_name.'%"';
        }
		if($uploaded_by!='All'){
			$wherecondition.= ' and uploaded_by="'.$uploaded_by.'"';
		}
		$getRecordListing=$this->Datatables_model->datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn,'','POST');
		$totalRecords=$getRecordListing['recordsTotal'];
		$recordsFiltered=$getRecordListing['recordsFiltered'];
		$recordListing = array();
        $content='[';
		$i=0;	
        $srNumber=$start;	
        if(!empty($getRecordListing)) {
            $actionContent = '';
            foreach($getRecordListing['data'] as $recordData) {
				$action='';
				$content .='[';
                $recordListing[$i][0]= $recordData->first_name;
				$recordListing[$i][1] = "<a href='$recordData->document' target='_blank'>".$recordData->document_name."</a>";
				$recordListing[$i][2] = displayDateInWords($recordData->created_on);
				$recordListing[$i][3]= $action;
				$i++;
                $srNumber++;
            }
            $content .= ']';
            $final_data = json_encode($recordListing);
        } else {
            $final_data = '[]';
        }	
         echo '{"draw":'.$draw.',"recordsTotal":'.$recordsFiltered.',"recordsFiltered":'.$recordsFiltered.',"data":'.$final_data.'}';
	}

	public function deleteFile(){
		if($_POST){
			$getDoc = $this->Common_model->getDataFromTable('tbl_client_docs','document',  $whereField="id", $whereValue=$_POST['id'], $orderBy='', $order='', $limit='', $offset=0, true);
			if(!empty($getDoc)){
				unlink('./uplaods/'.$getDoc[0]['clien_id'].'/'.$getDoc[0]['document_name']);
				$del = $this->Common_model->deleteRowFromTable($table='tbl_client_docs', $field='id', $_POST['id'], $limit=0);
				if($del){
					$res['error'] = 0;
					$res['id'] = $_POST['id'];
					$res['type'] = $_POST['type'];
					$res['msg'] = 'File deleted successfully';
				}else{
					$res['error'] = 0;
					$res['msg'] = 'Something went wrong';
				}
				echo json_encode($res);exit;
			}
		}
	}
}
?>