<?php
/* 
 * Generated by CRUDigniter v2.1 Beta 
 * www.crudigniter.com
 */
 
class Web_user_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function add_user_signup($params)
    {
    	$this->db->insert('users',$params);
    	return $this->db->insert_id();
    }

    public function add_user($params)
    {
        $this->db->insert('users',$params);
        return $this->db->insert_id();
    }

    public function get_user_login($params)
    {
        $usertype_array = array('admin','superadmin','appuser');
	  	$this->db->where('usrUserName',$params['usrUserName']);
	  	$this->db->where('usrPassword',$params['usrPassword']);
        $this->db->where_in('usrTypeName',$usertype_array);
	  	$query=$this->db->get('users');
	 	$c=$query->num_rows(); 
	  	if($c>0){
	  		return true;
	  	}
	  	else{
			return false; 
		}
    }


    public function check_user_login($params)
    {
        $this->db->where('usrStatus',$params['usrStatus']);
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


    function get_all_web_users($parent_path)
    {

        $this->db->select('child.*, parent.usrUserName parentName');
        $this->db->from('users as child');
        $this->db->like('child.usrParentPath', $parent_path, 'after');
        $this->db->join('users as parent', 'parent.usrId = child.usrParentId');
        $this->db->where('child.usrTypeName','admin');
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


    function get_all_active_units()
    {
        $this->db->select('*');
        $this->db->from('units');
        $this->db->where('untStatus','active');
        $query=$this->db->get();
        return $query->result_array();
    }

    function get_all_user_units($id)
    {
        $this->db->select('*');
        $this->db->from('user_units');
        $this->db->where('usruUserId',$id);
        $query=$this->db->get();
        return $query->result_array();
    }

    function get_user_all_unit_details($id)
    {
        $this->db->select('*');
        $this->db->from('user_units');
        $this->db->join('units', 'units.untId = user_units.usruUnitId');
        $this->db->where('user_units.usruUserId',$id);
        $this->db->order_by("units.untName", "asc"); 
        $query=$this->db->get();
        return $query->result_array();
    }


    public function add_user_units($params)
    {
        $this->db->insert('user_units',$params);
        return $this->db->insert_id();
    }

    public function check_user_unit_exists($params)
    {
        $this->db->where('usruUserId',$params['usruUserId']);
        $this->db->where('usruUnitId',$params['usruUnitId']);
        $query=$this->db->get('user_units');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function delete_user_unit($params)
    {
        $this->db->where('usruUserId',$params['usruUserId']);
        $this->db->where('usruUnitId',$params['usruUnitId']);
        $this->db->delete('user_units');
    }

    

}