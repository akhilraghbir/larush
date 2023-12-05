<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Common_upload extends CI_Controller
{   
    public function __construct(){
		parent::__construct();
		//check_UserSession();
    }
    public function ImageUpload(){
       if(!empty($_FILES['file'])){
            $path = $config['upload_path'] = $_POST['path'];
            $config['allowed_types'] = 'png|jpg|jpeg|svg|pdf|doc|docx';   
            $shuffledStr = $this->Common_model->getRandomString();
            $config['file_name']  = date("Y-m-d-h-i-s").$shuffledStr;
            $this->load->library('upload',$config);
            $this->upload->initialize($config);
            if($this->upload->do_upload('file')){
                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];
                $url = CONFIG_SERVER_ROOT.$path.$filename;
                $data['file_name'] = $path.$filename;
                $data['uploadedFile'] = "<a href='javascript:void(0)' onclick=openimage("."'".$url."'".") finalfile=".$filename.">View</a>";
                $data['status'] = 'success';
                if(isset($_POST['oldfile']) && $_POST['oldfile'] != ''){
                    if(file_exists("./".$_POST['path'].$_POST['oldfile'])){
                        unlink("./".$_POST['path'].$_POST['oldfile']);
                    }
                }
            }else{
                $data['status'] = 'error';
                $data['msg']  = 'Uploded file error - '.$this->upload->display_errors();
            }
            echo json_encode($data);
       }
    }

    public function DocsUpload(){
        if(!empty($_FILES['file'])){
            $path = $config['upload_path'] = $_POST['path'];
            if(!is_dir($path)){
                mkdir($path);
            }
            $config['allowed_types'] = '*';   
            $config['file_name']  = $_FILES['file']['name'];
            $this->load->library('upload',$config);
            $this->upload->initialize($config);
            if($this->upload->do_upload('file')){
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];
            $url = CONFIG_SERVER_ROOT.$path.$filename;
            $data['uploadedFile'] = "<a href=".$url." finalfile=".$path.$filename." download>View</a>";
            $data['status'] = 'success';
                if(!empty($_POST['old_file'])){
                    $old_path ="./".$path.$_POST['old_file'];
                    unlink($old_path);
                }
            }else{
                $data['status'] = 'error';
                $data['msg']  = 'Uploded file error - '.$this->upload->display_errors();
            }
            echo json_encode($data);
        }
     }
}

?>