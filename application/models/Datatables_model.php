<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Datatables_model extends CI_model{

	function __construct(){
		parent::__construct();

	}

    public function datatablesQuery($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn='',$resultInArray=false,$httpMethod='') {
        if($httpMethod=='POST')
		{
			$search = $this->input->post('search');
			if(isset($search['value'])){
				$search=$search['value'];
			}else{
				$search='';
			}

			$start         =  $this->input->post('start'); // get promo code Id
			$length        =  $this->input->post('length'); // get promo code Id
			$draw          =  $this->input->post('draw'); // get promo code Id
			$order   =  $this->input->post('order');
		}
		else
		{
			$search = $this->input->get('search');
			if(isset($search['value'])){
				$search=$search['value'];
			}else{
				$search='';
			}

			$start         =  $this->input->get('start'); // get promo code Id
			$length        =  $this->input->get('length'); // get promo code Id
			$draw          =  $this->input->get('draw'); // get promo code Id
			$order   =  $this->input->get('order');
		}

        //echo "<pre>";
        //print_r($search);exit;

        $selectQueryColumns=implode(',',$selectColumns);

        $this->db->select($selectQueryColumns);
        $this->db->from($table_name);
        $joinqry ='';
        if(!empty($joinsArray) && sizeof($joinsArray)>0){
           foreach($joinsArray as $each){
                $this->db->join($each['table_name'],$each['condition'],$each['join_type']);
                $joinqry.=$each['join_type']." join ".$each['table_name']." on ".$each['condition']." ";
            }
        }

        if($wherecondition!=''){
            $this->db->where($wherecondition);
        }

        if($search!=''){
            $sortFilters=array();
            foreach($selectColumns as $eachColumn){
                $tableclmn = explode(' as ',$eachColumn);
                $eachColumn = $tableclmn[0];
                $filter= "$eachColumn like '%$search%' ";
                $sortFilters[]=$filter;
            }
          $searchCond =implode('or ', $sortFilters);
          $this->db->where('('.$searchCond.')');
        }


        if(!empty($order)){
            $columnIndex=$order[0]['column'];
            $orderByColumn=$dataTableSortOrdering[$columnIndex];
            if($orderByColumn==''){
                $orderByColumn=$indexColumn;
            }
            $sortType=$order[0]['dir'];
            $this->db->order_by($orderByColumn, $sortType);
		}

       if($length!=0) {
          $this->db->limit($length,$start);
       }

      // echo $this->db->get_compiled_select();
       $query = $this->db->get();
       //echo $this->db->last_query();die;
      //  $recordsFiltered = $query->num_rows();
        //$returnData = $query->result();
        if($resultInArray){
            $returnData = $query->result_array();
        }else{
            $returnData = $query->result();
        }
        if($indexColumn!=''){
                $selectCountColumn=$indexColumn;
        }else{
            $selectCountColumn='*';
        }
        $countQuery="select count($selectCountColumn) as count from $table_name ";
        if($joinqry!=''){
			$countQuery.=$joinqry;
		}
        $countQuery.=" where 1";
		if($wherecondition!=''){
			$countQuery.=" and $wherecondition ";
		}
        if(!empty($searchCond)){
            $countQuery.=" and ($searchCond) ";
        }
        $totalCountRecords=$this->db->query($countQuery);
        $countTotal=$totalCountRecords->result_array();
        $table_total=$countTotal[0]['count'];



       $getRecordListing['data']=$returnData;
       $getRecordListing['recordsTotal']=$table_total;
       $getRecordListing['recordsFiltered']=$table_total;


       return $getRecordListing;




    }

	public function datatablesQuerywithgroupby($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn='',$groupby,$orderByColumn,$sortType,$resultInArray=false,$httpMethod='')
    {
        if($httpMethod=='POST')
		{
			$search = $this->input->post('search');
			if(isset($search['value'])){
				$search=$search['value'];
			}else{
				$search='';
			}

			$start         =  $this->input->post('start'); // get promo code Id
			$length        =  $this->input->post('length'); // get promo code Id
			$draw          =  $this->input->post('draw'); // get promo code Id
			$order   =  $this->input->post('order');
		}
		else
		{
			$search = $this->input->get('search');
			if(isset($search['value'])){
				$search=$search['value'];
			}else{
				$search='';
			}

			$start         =  $this->input->get('start'); // get promo code Id
			$length        =  $this->input->get('length'); // get promo code Id
			$draw          =  $this->input->get('draw'); // get promo code Id
			$order   =  $this->input->get('order');
		}

        //echo "<pre>";
        //print_r($search);exit;

        $selectQueryColumns=implode(',',$selectColumns);

        $this->db->select($selectQueryColumns);
        $this->db->from($table_name);
        $joinqry ='';
        if(!empty($joinsArray) && sizeof($joinsArray)>0){
           foreach($joinsArray as $each){
                $this->db->join($each['table_name'],$each['condition'],$each['join_type']);
                $joinqry.=$each['join_type']." join ".$each['table_name']." on ".$each['condition']." ";
            }
        }

        if($wherecondition!=''){
            $this->db->where($wherecondition);
        }

        if($search!=''){
            $sortFilters=array();
            foreach($selectColumns as $eachColumn){
                $filter= "$eachColumn like '%$search%' ";
                $sortFilters[]=$filter;
            }
          $searchCond =implode('or ', $sortFilters);
          $this->db->where($searchCond);
        }


        if(!empty($order)){
            $columnIndex=$order[0]['column'];
            $orderByColumn=$dataTableSortOrdering[$columnIndex];
            if($orderByColumn==''){
                $orderByColumn=$indexColumn;
            }
            $sortType=$order[0]['dir'];
            $this->db->order_by($orderByColumn, $sortType);
		}
       if($groupby!='')
       {
        $this->db->group_by($groupby);
       }
       if($orderByColumn!='')
       {
        $this->db->order_by($orderByColumn,$sortType);
       }
       if($length!=0) {
          $this->db->limit($length,$start);
       }

      // echo $this->db->get_compiled_select();
       $query = $this->db->get();
      //  echo $this->db->last_query(); exit;
      //  $recordsFiltered = $query->num_rows();
        //$returnData = $query->result();

        if($resultInArray){
            $returnData = $query->result_array();
        }else{
            $returnData = $query->result();
        }
        if($indexColumn!=''){
                $selectCountColumn=$indexColumn;
        }else{
            $selectCountColumn='*';
        }
        $countQuery="select count($selectCountColumn) as count from $table_name ";
        if($joinqry!=''){
			$countQuery.=$joinqry;
		}

		if($wherecondition!=''){
			$countQuery.=" where $wherecondition ";
		}


        $totalCountRecords=$this->db->query($countQuery);

        $countTotal=$totalCountRecords->result_array();
        $table_total=$countTotal[0]['count'];



       $getRecordListing['data']=$returnData;
       $getRecordListing['recordsTotal']=$table_total;
       $getRecordListing['recordsFiltered']=$table_total;


       return $getRecordListing;


    }
    
    public function getDataFromDB($selectColumns,$dataTableSortOrdering,$table_name,$joinsArray,$wherecondition,$indexColumn='',$groupby='',$orderByColumn='',$sortType='',$resultInArray=false,$httpMethod='') {
        $selectQueryColumns=implode(',',$selectColumns);
        $this->db->select($selectQueryColumns);
        $this->db->from($table_name);
        $joinqry ='';
        if(!empty($joinsArray) && sizeof($joinsArray)>0){
           foreach($joinsArray as $each){
                $this->db->join($each['table_name'],$each['condition'],$each['join_type']);
                $joinqry.=$each['join_type']." join ".$each['table_name']." on ".$each['condition']." ";
            }
        }

        if($wherecondition!=''){
            $this->db->where($wherecondition);
        }
        if($groupby!=''){
            $this->db->group_by($groupby);
        }
        $this->db->order_by($orderByColumn, $sortType);
        
       $query = $this->db->get();
        if($resultInArray){
            $returnData = $query->result_array();
        }else{
            $returnData = $query->result();
        }
        if($indexColumn!=''){
                $selectCountColumn=$indexColumn;
        }else{
            $selectCountColumn='*';
        }
       $getRecordListing['data']=$returnData;
       return $getRecordListing;
    }

}
?>
