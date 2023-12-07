<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {


	public function __construct(){
		parent::__construct();
		check_UserSession();
    } 

	public function loadBreadCrumbs(){
		$data=array();
		$data['icon_class']="icon-user";
		$data['title']="Inventory";
		$data['helptext']="This Page Is Used To Manage The Inventory.";
		$data['actions']['add']=CONFIG_SERVER_ADMIN_ROOT.'inventory/add';
		$data['actions']['list']=CONFIG_SERVER_ADMIN_ROOT.'inventory';
		return $data;
	}

	public function index(){
		$data['breadcrumbs'] = $this->loadBreadCrumbs(); 
		$this->home_template->load('home_template','admin/products',$data);   
	}

	public function loadUserForm($formContent=array(), $formName=''){
		$data['breadcrumbs'] = $this->loadBreadCrumbs();
		$data['data'] = $formContent;
		$data['form_action'] = $formName;
		$data['units'] = $this->Common_model->getDataFromTable('tbl_units','',  $whereField='status', $whereValue='Active', $orderBy='', $order='', $limit='', $offset=0, true);
		$this->home_template->load('home_template','admin/products',$data); 
	}

	public function add(){
		if(($this->input->post('add'))){		
			$this->form_validation->set_session_data($this->input->post());
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('product_name','units','is_ferrous','buyer_price','tier_price');    
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
				$user_id = $this->Common_model->addDataIntoTable('tbl_products',$data);
				$this->form_validation->clear_field_data();
				$this->messages->setMessage('Product Created Successfully','success');
				redirect('administrator/inventory');
			}
		}
			$this->loadUserForm(array(),'add');
	}

	public function edit($param1=''){
		if(($this->input->post('edit'))){
			$this->form_validation->checkXssValidation($this->input->post());
			$mandatoryFields=array('product_name','units','is_ferrous','buyer_price','tier_price');    
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
				$this->Common_model->updateDataFromTable('tbl_products',$data,'id',$param1);
				$this->messages->setMessage('Product Updated Successfully','success');
				redirect(base_url('administrator/inventory'));
			}
		}
		$formData=array();
		if($param1!=''){
			$result = $this->Common_model->getDataFromTable('tbl_products','',  $whereField='id', $whereValue=$param1, $orderBy='', $order='', $limit=1, $offset=0, true);
			$formData=$result[0];	
		}
		$this->loadUserForm($formData, 'edit');
	}

	public function updateStatus(){
		$u_id = $this->input->post('pid');
		if($this->input->post('status') == 'Active'){
			$data['status'] = $this->input->post('status');
			$succ_message = 'Product Actived Successfully';
		}else{
			$data['status'] = $this->input->post('status');
			$succ_message = 'Product Inactived Successfully';
		}
		
		$this->Common_model->updateDataFromTable('tbl_products',$data,'id',$u_id);
		$message = ['error'=>'0','message'=>$succ_message];
        echo json_encode($message);
        exit;
	}

	public function addProduct(){
		$pid = $this->input->post('id');
		if($pid!=''){
			$product = $this->Common_model->getDataFromTable('tbl_products','',  $whereField='id', $whereValue=$pid, $orderBy='', $order='', $limit=1, $offset=0, true);
			if(is_array($product) && count($product)>0){
				$elementid = time();
				$html='<tr class="tr_'.$elementid.'">';
				$html.='<td>'.$product[0]['product_name'].'</td>';
				$html.='<td >'.$product[0]['tier_price'].'</td>';
				$html.='<td><input type="text" maxlength="5" onkeyup="calculateTotal('.$product[0]['tier_price'].','.$elementid.')" class="qty_'.$elementid.' form-control Onlynumbers" placeholder="Enter Quantity"></td>';
				$html.='<td><input type="text" readonly class="total_'.$elementid.' form-control Onlynumbers" placeholder="Enter Total"></td>';
				$html.='<td><button type="button" onclick="removeRow('.$elementid.')" class="btn btn-sm btn-danger"><i class="ri-delete-bin-3-fill"></i></button></td>';
				$html.='</tr>';
				$res['error'] = 0;
				$res['html'] = $html;
			}else{
				$res['error'] = 1;
				$res['html'] = '';
			}
			echo json_encode($res);exit;
		}
	}

	public function ajaxListing(){
		$draw          =  $this->input->post('draw');
		$start         =  $this->input->post('start');
		$status         =  $this->input->post('status');
		$indexColumn ='tp.id';
		$selectColumns = ['tp.id','product_name','tu.unit_name','is_ferrous','buyer_price','tier_price','main_image','wide_image','zoom_image','tp.status','tp.created_on'];
		$dataTableSortOrdering = ['product_name','tu.unit_name','is_ferrous','buyer_price','tier_price','main_image','wide_image','zoom_image','tp.status','tp.created_on'];
		$table_name ='tbl_products as tp';
		$joinsArray[] = ['table_name'=>'tbl_units as tu','condition'=>"tu.id = tp.units",'join_type'=>'left'];;
		$wherecondition='tp.id!="0"';
		if($status=='Active'){
		    $wherecondition.=' and tp.status = "Active"';
		}else if($status=='Inactive'){
		    $wherecondition.=' and tp.status = "Inactive"';
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
                $recordListing[$i][1]= $recordData->product_name;
                $recordListing[$i][2]= $recordData->unit_name;
				$recordListing[$i][3]= $recordData->is_ferrous;
				$recordListing[$i][4]= floatval($recordData->buyer_price);
				$recordListing[$i][5]= floatval($recordData->tier_price);
                // $recordListing[$i][6]= '<a href="'.base_url($recordData->main_image).'" target="_blank" class="btn btn-sm btn-info">View</a>';
				// $recordListing[$i][7]= '<a href="'.base_url($recordData->zoom_image).'" target="_blank" class="btn btn-sm btn-info">View</a>';
				// $recordListing[$i][8]= '<a href="'.base_url($recordData->wide_image).'" target="_blank" class="btn btn-sm btn-info">View</a>';
				if($recordData->status == 'Inactive'){
					$recordListing[$i][6]= '<span class="badge rounded-pill bg-danger">'.$recordData->status.'</span>';
				}else{
					$recordListing[$i][6]= '<span class="badge rounded-pill bg-success">'.$recordData->status.'</span>';
				}
                $recordListing[$i][7]= displayDateInWords($recordData->created_on);
				if($this->session->userdata('user_type') == 'Admin'){	
					if($recordData->status == 'Inactive'){
						$action.= '<a class="btn" title="Active" onclick="statusUpdate(this,'."'$recordData->id'".','."'Active'".')" style="margin-bottom: 2px;color:green;font-size: 16px;cursor:pointer;"><i class="ri-check-line"></i></a>';
					}else{
						$action.= '<a class="btn" title="Deactive" onclick="statusUpdate(this,'."'$recordData->id'".','."'Inactive'".')" style="margin-bottom: 2px;color:red;font-size: 16px;cursor:pointer;"><i class="ri-close-line"></i></a>';
					}
				}
				$action.= '<a href="'.CONFIG_SERVER_ADMIN_ROOT.'inventory/edit/'.$recordData->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="ri-pencil-fill" aria-hidden="true"></i></a>';
				$recordListing[$i][8]= $action;
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
	function alpha_dash_space($fullname){
		if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function getProducts()
	{
		$term = $_POST['term'];
		$wherecondition = " status='Active' and product_name LIKE '%" . $term . "%'";
		$vmsRefdata = $this->Common_model->getSelectedFields('tbl_products', 'id,product_name as name' , $wherecondition, $limit = '100', $orderby = 'id', $sortby = 'DESC');
		echo json_encode($vmsRefdata);
	}
}
