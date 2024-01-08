<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-user";
		$data['title']="Categories";
		$data['helptext']="This Page Is Used To Manage The Categories.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'categories/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'categories';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/categories',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
		$this->home_template->load('home_template','admin/categories',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('category');    
            foreach($mandatoryFields as $row){
				$fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
				$this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
            if($this->form_validation->run() == FALSE){
				$this->form_validation->set_session_data($this->input->post());
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				foreach($this->input->post() as $fieldname=>$fieldvalue){
                	$data[$fieldname]= $this->input->post($fieldname);
                }
                unset($data['add']);
				$data['created_by'] = $this->session->id;
				$data['created_on'] = current_datetime();
				$user_id = $this->Common_model->addDataIntoTable('tbl_categories',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Category Created Successfully','success');
				redirect('administrator/categories');
			}
		}
			$this->loadUserForm(array(),'add');
	}

	public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('category');    
            foreach($mandatoryFields as $row){
            $fieldname = ucwords(strtolower(str_replace("_", " ", $row)));
            $this->form_validation->set_rules($row, $fieldname, 'required'); 
            }
			if($this->form_validation->run() == FALSE){
				$errorMessage=validation_errors();
				$this->messages->setMessage($errorMessage,'error');
			}else{
				foreach($this->input->post() as $fieldname=>$fieldvalue){
                	$data[$fieldname]= $this->input->post($fieldname);
                }
                unset($data['edit']);
				$data['updated_on'] = current_datetime();
				$this->Common_model->updateDataFromTable('tbl_categories',$data,'id',$param1);
				$this->messages->setMessage('Category Updated Successfully','success');
				redirect(base_url('administrator/categories'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_categories','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];	
		}
		$this->loadUserForm($formData, 'edit');
	}

	public function updateStatus(){
		$u_id = $this->input->post('pid');
		if($this->input->post('status') == 'Active'){
			$data['status'] = $this->input->post('status');
			$succ_message = 'Category Actived Successfully';
		}else{
			$data['status'] = $this->input->post('status');
			$succ_message = 'Category Inactived Successfully';
		}
		
		$this->Common_model->updateDataFromTable('tbl_categories',$data,'id',$u_id);
		$message = ['error'=>'0','message'=>$succ_message];
        echo json_encode($message);
        exit;
	}


	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$status         =  $this->input->post('status');
		$indexColumn ='id';
		$selectColumns = ['id','category','status','created_on'];
		$dataTableSortOrdering = ['category','status','created_on'];
		$table_name ='tbl_categories';
		$joinsArray = [];
		$wherecondition='id!="0"';
		if($status=='Active'){
		    $wherecondition.=' and status = "Active"';
		}else if($status=='Inactive'){
		    $wherecondition.=' and status = "Inactive"';
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
				$action="";
				$content .='[';
				$recordListing[$i][0]= $i+1;
                $recordListing[$i][1]= $recordData->category;
				if($recordData->status == 'Inactive'){
					$recordListing[$i][2]= '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				}else{
					$recordListing[$i][2]= '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                $recordListing[$i][3]= displayDateInWords($recordData->created_on);
				if($this->session->userdata('user_type') == 'Admin'){	
					if($recordData->status == 'Inactive'){
						$action.= '<a class="btn" title="Active" onclick="statusUpdate(this,'."'$recordData->id'".','."'Active'".')" style="margin-bottom: 2px;color:green;font-size: 16px;cursor:pointer;"><i class="ri-check-line"></i></a>';
					}else{
						$action.= '<a class="btn" title="Deactive" onclick="statusUpdate(this,'."'$recordData->id'".','."'Inactive'".')" style="margin-bottom: 2px;color:red;font-size: 16px;cursor:pointer;"><i class="ri-close-line"></i></a>';
					}
				}
				$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'categories/edit/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
				$recordListing[$i][4]= $action;
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

}
