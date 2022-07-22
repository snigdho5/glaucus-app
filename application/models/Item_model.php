<?php 
class Item_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_package_mode()
    {
        $where  =   '1=1';
        $this->db->select('`pckg_mode_id`, `package_name`');
        $this->db->from('package_mode');
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query();
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            //print_r($resultArray);
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }
        //die();
    }
    
    function get_package_size()
    {
        $where  =   '1=1';
        $this->db->select('`pckg_size_id`, `package_size_name`');
        $this->db->from('package_size');
        //$this->db->where($where);
        $query = $this->db->get();
        $resultArray    =   $query->result_array();
        //echo $this->db->last_query();
        if($query->num_rows() > 0){
            //echo '<pre>';
            //print_r($resultArray);
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }
    }
    
    function insert_add_item($data)
    {
        $this->db->insert('package_items',$data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }
    
    function insert_add_data_csv($data)
    {
        /*echo '<pre>';
        print_r($data);
        echo '<pre>';*/
        //Import CSV Data Existing Data will update & new Data will insert
        foreach($data as $csvData){
            
            $where  =   '`pckg_id` = '.$csvData['pckg_id'];
        
            $this->db->select('`pckg_id`');
            $this->db->from('package_items');
            $this->db->where($where);
            $query = $this->db->get();
            //echo $this->db->last_query().'<br>';
            
            $checkRecords   =   $query->num_rows();
            if($query->num_rows() > 0){
                //Update exsiting CSV DATA
                $this->db->where('`pckg_id`', $csvData['pckg_id']);
                $this->db->update('`package_items`', $csvData);
                //echo 'Existing Data'.'<br>';
                //echo $this->db->last_query().'<br>';
            }
            else{
                //Update exsiting CSV DATA
                $this->db->insert('`package_items`',$csvData);
                //echo 'New Data'.'<br>';
            }
        }
        
        
        /*foreach($data as $csvData){
            $this->db->insert('package_items',$csvData);
        }*/
        $insert_id = $this->db->insert_id();
        //echo $insert_id;
        return  $insert_id;
        //die();
    }
    
    function fetch_items_data()
    {
        $this->db->select('
        `package_items`.`pckg_id`, 
        `package_items`.`item_name`, 
        `package_mode`.`package_name`, 
        `package_size`.`package_size_name`,
        `package_items`.`package_item_price`,
        `package_items`.`status`,
        `package_items`.`created_at`');
        $this->db->from('`package_items`');
        $this->db->join('`package_mode`', '`package_items`.`mode_of_package` = `package_mode`.`pckg_mode_id`');
        $this->db->join('`package_size`', '`package_items`.`package_size` = `package_size`.`pckg_size_id`');
        $this->db->order_by('`package_items`.`pckg_id`', "ASC");
        
        $query = $this->db->get();        
        //echo $this->db->last_query();
        
        if($query->num_rows() > 0){
            $resultArray    =   $query->result_array();
            return json_encode($resultArray);
            //echo json_encode(['status' => 200 , 'data' => $resultArray]);
        }
        else{
            return json_encode(['status' => 404]);
        }
        //die();
    }

    function get_all_packagemodes()
    {
        return $this->db->get('package_mode')->result_array();
    }
    
    function get_all_packagesizes()
    {
        return $this->db->get('package_size')->result_array();
    }
    
    function get_item_data($params)
    {
        return $this->db->get_where('package_items',$params)->row_array();
    }
    
    function update_item_data($data, $id)
    {
        $this->db->where('pckg_id',$id);
        $this->db->update('package_items',$data);
        
        return $this->db->affected_rows();
    }
    
    function update_status($data,$id)
    {
        $this->db->where('pckg_id',$id);
        $this->db->update('package_items',$data);
        
        return $this->db->affected_rows();
    } 

    function fetch_retailers_items_data()
    {
        $this->db->select('
        `package_items`.`pckg_id`, 
        `package_items`.`item_name`, 
        `package_mode`.`package_name`, 
        `package_size`.`package_size_name`,
        `package_items`.`retail_item_price`,
        `package_items`.`status`,
        `package_items`.`created_at`');
        $this->db->from('`package_items`');
        $this->db->join('`package_mode`', '`package_items`.`mode_of_package` = `package_mode`.`pckg_mode_id`');
        $this->db->join('`package_size`', '`package_items`.`package_size` = `package_size`.`pckg_size_id`');
        $this->db->order_by('`package_items`.`pckg_id`', "ASC");
        
        $query = $this->db->get();        
        //echo $this->db->last_query();        
        if($query->num_rows() > 0){
            $resultArray    =   $query->result_array();
            return json_encode($resultArray);
        }
        else{
            return json_encode(['status' => 404]);
        }
    }
    
}