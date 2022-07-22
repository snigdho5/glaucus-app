<?php 
class Distributor_model extends CI_Model
{
    function __construct(){
        parent::__construct();
    }
    
    function get_districts_list(){
        $where  =   '1=1';
        $this->db->select('`districtId`, `districtCode`, `districtName`');
        $this->db->from('district_of_wb');
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query();
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            //print_r($resultArray);
            //return $resultArray;
            return json_encode($resultArray);
        }
        else{
            return $query->num_rows();
        }
        //die();
    }
    
    function insert_distributor($data){
        $this->db->insert('distributors',$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    
    function insert_add_data_csv($data){
        /*echo '<pre>';
        print_r($data);
        echo '<pre>';*/
        //Import CSV Data Existing Data will update & new Data will insert
        foreach($data as $csvData){
            
            $where  =   '`distributorId` = '.$csvData['distributorId'];
        
            $this->db->select('`distributorId`');
            $this->db->from('distributors');
            $this->db->where($where);
            $query = $this->db->get();
            //echo $this->db->last_query().'<br>';
            
            $checkRecords   =   $query->num_rows();
            if($query->num_rows() > 0){
                //Update exsiting CSV DATA
                $this->db->where('`distributorId`', $csvData['distributorId']);
                $this->db->update('`distributors`', $csvData);
                //echo 'Existing Data'.'<br>';
                //echo $this->db->last_query().'<br>';
            }
            else{
                //Update exsiting CSV DATA
                $this->db->insert('`distributors`',$csvData);
                //echo 'New Data'.'<br>';
            }
        }
        
        $insert_id = $this->db->insert_id();
        //echo $insert_id;
        return  $insert_id;
        //die();
    }
    
    //SQL to create distributors view
    function get_distributors_list(){
        $this->db->select('
            `distributors`.`distributorId`, 
            `distributors`.`distributorName`, 
             
            `distributors`.`distributorPhone`, 
            `distributors`.`distributorEmail`, 
            `distributors`.`distributorAddress`, 
            `distributors`.`distributorGstNo`, 
            `distributors`.`distributorKycFile`, 
            `distributors`.`distributorDob`, 
            `distributors`.`creditLimit`, 
            `distributors`.`status`, 
            `district_of_wb`.`districtName`, 
            `distributors`.`created_at`
        ');
        $this->db->from('`distributors`');
        $this->db->join('`district_of_wb`', '`distributors`.`district` = `district_of_wb`.`districtId`');
        $this->db->order_by('`distributors`.`distributorId`', "ASC");
        $this->db->where('`distributors`.`distributorType`', '1');
        
        $query = $this->db->get();        
        //$this->db->last_query(); die();
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            return json_encode($resultArray);
        }
        else{
            return $query->num_rows();
        }
    }

    //fetch dirstributor for the app
    function get_distributors_list_app($sales_id){
        $this->db->select('
            `distributors`.`distributorId`, 
            `distributors`.`distributorName`, 
             
            `distributors`.`distributorPhone`, 
            `distributors`.`distributorEmail`, 
            `distributors`.`distributorAddress`, 
            `distributors`.`distributorGstNo`, 
            `distributors`.`distributorKycFile`, 
            `distributors`.`distributorDob`, 
            `distributors`.`creditLimit`, 
            `distributors`.`status`, 
            `district_of_wb`.`districtName`, 
            `distributors`.`created_at`
        ');
        $this->db->from('`distributors`');
        $this->db->join('`district_of_wb`', '`distributors`.`district` = `district_of_wb`.`districtId`');
        $this->db->join('`assigned_sales_user`', '`distributors`.`distributorId` = `assigned_sales_user`.`distributor_id`');
        $this->db->order_by('`distributors`.`distributorId`', "ASC");
        $this->db->where('`distributors`.`distributorType`', '1');
        $this->db->where('`assigned_sales_user`.`sales_id`', $sales_id);        
        $query = $this->db->get();        
        //$this->db->last_query(); die();
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            // return json_encode($resultArray);
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }
    }
    
    //Get the List of Distributors for ANG API
    function get_distributor_data($params){
        return $this->db->get_where('distributors',$params)->row_array();
    }
    
    function update_distributor_data($data, $id){
        $this->db->where('distributorId',$id);
        $this->db->update('distributors',$data);        
        return $this->db->affected_rows();
    }
    
    //Update the status of the distributor
    function update_status($data,$id){
        $this->db->where('distributorId',$id);
        $this->db->update('distributors',$data);        
        return $this->db->affected_rows();
    }
    
    //Fetch the filename from DB to unlink file to replace with new update file
    function delete_exist_file($id){
        $where  =   '`distributorId` = '.$id;
        
        $this->db->select('`distributorKycFile`');
        $this->db->from('distributors');
        $this->db->where($where);
        $query          =   $this->db->get();
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            return $resultArray[0]['distributorKycFile'];
        }
        else{
            return $query->num_rows();
        }
    }

    function check_on_add_redundant_phone($data){
        $this->db->select('`distributorPhone`');
        $this->db->from('distributors');
        $this->db->where('distributorPhone',$data['distributorPhone']);
        $query          =   $this->db->get();
        
        if($query->num_rows() >= 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }
    
    function check_on_add_redundant_gstin($data){
        $this->db->select('`distributorGstNo`');
        $this->db->from('distributors');
        $this->db->where('distributorGstNo',$data['distributorGstNo']);
        /*if($data['distributorId']){
            $this->db->where('distributorId !=', $data['distributorId']);
        }*/
        $query          =   $this->db->get();
        //echo $this->db->last_query();die();
        if($query->num_rows() >= 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }
    
    function check_on_add_redundant_email($data){
        $this->db->select('`distributorEmail`');
        $this->db->from('distributors');
        $this->db->where('distributorEmail',$data['distributorEmail']);
        $query          =   $this->db->get();
        //echo $this->db->last_query();die();
        if($query->num_rows() >= 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }

    function check_redundant_phone($data){
        $this->db->select('`distributorPhone`');
        $this->db->from('distributors');
        $this->db->where('distributorPhone',$data['distributorPhone']);
        if($data['distributorId']){
            $this->db->where('distributorId !=', $data['distributorId']);
        }
        $query   =  $this->db->get();        
        if($query->num_rows() >= 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }
    
    function check_redundant_gstin($data){
        $this->db->select('`distributorGstNo`');
        $this->db->from('distributors');
        $this->db->where('distributorGstNo',$data['distributorGstNo']);
        if($data['distributorId']){
            $this->db->where('distributorId !=', $data['distributorId']);
        }
        $query          =   $this->db->get();
        //echo $this->db->last_query();die();
        if($query->num_rows() >= 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }
    
    function check_redundant_email($data){
        $this->db->select('`distributorEmail`');
        $this->db->from('distributors');
        $this->db->where('distributorEmail',$data['distributorEmail']);
        if($data['distributorId']){
            $this->db->where('distributorId !=', $data['distributorId']);
        }
        $query          =   $this->db->get();
        //echo $this->db->last_query();die();
        if($query->num_rows() >= 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }

    //SQL to create retailers view
    function get_retailers_list(){
        $this->db->select('
        `distributors`.`distributorId` as retailerId, 
        `distributors`.`distributorName` as retailerName,          
        `distributors`.`distributorPhone` as retailerPhone, 
        `distributors`.`distributorEmail` as retailerEmail, 
        `distributors`.`distributorAddress` as retailerAddress, 
        `distributors`.`distributorGstNo` as retailerGstNo, 
        `distributors`.`distributorKycFile` as retailerKycFile, 
        `distributors`.`distributorDob` as retailerDob, 
        `distributors`.`creditLimit`, 
        `distributors`.`status`, 
        `district_of_wb`.`districtName`, 
        `distributors`.`created_at`');
        $this->db->from('`distributors`');
        $this->db->join('`district_of_wb`', '`distributors`.`district` = `district_of_wb`.`districtId`');
        $this->db->order_by('`distributors`.`distributorId`', "ASC");
        $this->db->where('`distributors`.`distributorType`', '2');
        
        $query = $this->db->get();        
        //$this->db->last_query(); die();
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            //print_r($resultArray);
            //return $resultArray;
            return json_encode($resultArray);
        }
        else{
            return $query->num_rows();
        }
    }

    function get_sales_persons($distributor){
        $checkarray     =   ['distributor_id' => $distributor];
        $this->db->select('`usrId` as id, `usrFirstName` as label, `usrLastName` as last_name');
        $this->db->from('users');
        $this->db->where('usrTypeId',3);
        $query = $this->db->get();
        $result_array   =   $query->result();
        foreach ($result_array as $key => $value){
            $checkarray['sales_id']     =   $value->id;
            $value->icon        =  '<img src='.base_url().'assets/dist/img/buildingicon.png'.' />';
            $value->name        =  $value->label.' '.$value->last_name;
            $value->id          =  $value->id;
            $value->ticked      =  $this->check_sales_exists($checkarray);
            unset($value->last_name);
        }
        //echo $this->db->last_query();die();
        return $result_array;
    }

    function check_sales_exists($conditions){
        $this->db->select('*');
        $this->db->from('assigned_sales_user');
        // $this->db->where('distributor_id',$distributor);
        $this->db->where($conditions);
        $query = $this->db->get();
        //echo $this->db->last_query();die();
        $count = $query->num_rows();
        if($count > 0){
            return true;
        }
        else{
            return false; 
        }
    }

    function map_sales_users($params){
        $insertCount    =   [];
        $deleteData     =   [];
        $distributor    =   '';

        foreach ($params as $key => $value) {
            $this->db->select('*');
            $this->db->from('assigned_sales_user');
            $this->db->where('distributor_id',$value['distributor_id']);
            $this->db->where('sales_id',$value['sales_id']);
            $query = $this->db->get();
            //delete data
            $distributor    =   $value['distributor_id'];
            $deleteData[]   =   $value['sales_id'];
            if($query->num_rows() == false){
                $this->db->insert('assigned_sales_user',$value);
                $insertCount[]  = $this->db->insert_id();
            }
        }
        if(count($deleteData)){
            $this->db->where('distributor_id', $distributor);
            $this->db->where_not_in('sales_id', $deleteData);
            $this->db->delete('assigned_sales_user');
            $this->db->affected_rows();
        }
        return $insertCount;
    }
}