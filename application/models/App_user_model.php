<?php
 
class App_user_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function add_user($params)
    {
    	$this->db->insert('users',$params);
    	return $this->db->insert_id();
    }

    public function get_user_duplicate($params)
    {
        $this->db->where('usrUserName',$params['usrUserName']);
        $query=$this->db->get('users');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function get_update_user_duplicate($id, $params)
    {
        $this->db->where('usrUserName',$params['usrUserName']);
        $this->db->where_not_in('usrId', $id);
        $query=$this->db->get('users');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function get_user_data($params)
    {
        return $this->db->get_where('users',$params)->row_array();
    }

    function get_all_app_users($parent_path)
    {
        $this->db->select('child.*, parent.usrUserName parentName');
        $this->db->from('users as child');
        $this->db->like('child.usrParentPath', $parent_path, 'after');
        $this->db->join('users as parent', 'parent.usrId = child.usrParentId');
        $this->db->where('child.usrTypeName','appuser');
        $query=$this->db->get();
        return $query->result_array();
    }

    function update_status($id, $params)
    {
        $this->db->where('usrId',$id);
        $this->db->update('users',$params);

        if($this->db->affected_rows() == '1'){
            return true;
        }else{
            return false;
        }
    }

    function update_user($id, $params)
    {
        $this->db->where('usrId',$id);
        $this->db->update('users',$params);
    }

    public function check_old_login_location_record()
    {
        $today = date('Y-m-d'); 
        //$this->db->where('crlDate <',$today);
        $this->db->where('datediff(now(), crlDate) >',40);
        $query=$this->db->get('current_locations');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function delete_old_login_location_record()
    {
        $today = date('Y-m-d'); 
        //$this->db->where('crlDate <',$today);
        $this->db->where('datediff(now(), crlDate) >',40);
        $this->db->delete('current_locations');
    }

    public function check_old_trip_location_record()
    {
        $today = date('Y-m-d'); 
        //$this->db->where('trplDate <',$today);
        $this->db->where('datediff(now(), trplDate) >',40);
        $query=$this->db->get('trip_locations');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function delete_old_trip_location_record()
    {
        $today = date('Y-m-d'); 
        //$this->db->where('trplDate <',$today);
        $this->db->where('datediff(now(), trplDate) >',40);
        $this->db->delete('trip_locations');
    }
    
    //Fetch the filename from DB to unlink file to replace with new update file
    function delete_exist_file($id)
    {
        $where  =   '`usrId` = '.$id;
        
        $this->db->select('`usrKycFile`');
        $this->db->from('users');
        $this->db->where($where);
        $query          =   $this->db->get();
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            return $resultArray[0]['usrKycFile'];
        }
        else{
            return $query->num_rows();
        }
    }
    
    
    function get_user_kyc($id)
    {
        $this->db->select('
            `users.usrFirstName`, 
            `users.usrLastName`, 
            `users.usrContactNo`, 
            `users.usrUserEmail`, 
            `users.usrDesignation`, 
            `users.usrAddress`, 
            `users.usrKycFile`            
            ');
        $this->db->from('`users`');
        $this->db->where('users.usrId', $id);
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            //return json_encode($query->result_array());
            return $query->result_array();
        }
        else{
            return $query->num_rows();
        }
    }

}
