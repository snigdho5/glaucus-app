<?php

 
class Meeting_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function add_meeting($params)
    {

        if($this->db->insert('meetings', $params)) {
            $insert_id = $this->db->insert_id();
            $data['mtnCode'] = 'LNMTN00' . $insert_id;
            $this->db->where('mtnId', $insert_id);
            $this->db->update('meetings', $data);
            return true;
        } else {
            return false;
        }

    }


    public function get_meeting_name_duplicate($params)
    {
        $this->db->where('mtnName',$params['mtnName']);
        $query=$this->db->get('meetings');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }


    public function get_update_meeting_name_duplicate($id, $params)
    {
        $this->db->where('mtnName',$params['mtnName']);
        $this->db->where_not_in('mtnId', $id);
        $query=$this->db->get('meetings');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }


    public function get_all_meetings($parent_path)
    {
        $this->db->select('*, parent.usrUserName parentName, user.usrUserName userName');
        $this->db->from('meetings');
        $this->db->like('mtnParentPath', $parent_path, 'after');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $this->db->join('users as user', 'user.usrId = meetings.mtnUserId');
        $this->db->join('users as parent', 'parent.usrId = meetings.mtnParentId');
        $this->db->order_by('meetings.mtnId DESC');
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_meeting_data($params)
    {
        $this->db->select('*');
        $this->db->from('meetings');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $this->db->where('mtnId',$params['mtnId']);
        $query=$this->db->get();
        return $query->row_array();
    }


    public function update_meeting($id, $params)
    {
        $this->db->where('mtnId',$id);
        $this->db->update('meetings',$params);
    }

/*
    public function get_all_meeting_types()
    {
        return $this->db->get('meeting_types')->result_array();
    }*/

    public function get_all_meeting_types()
    {
        $this->db->select('*');
        $this->db->from('meeting_types');
        $this->db->order_by('mtntName ASC');
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_user_data($params)
    {
        return $this->db->get_where('users',$params)->row_array();
    }


    public function get_all_admin_users($parent_path, $id)
    {
        $this->db->select('child.*, parent.usrUserName parentName');
        $this->db->from('users as child');
        //$this->db->like('child.usrParentPath', $parent_path, 'after');
        $this->db->join('users as parent', 'parent.usrId = child.usrParentId');
        //$this->db->where('child.usrTypeName','appuser');
        $query=$this->db->get();
        return $query->result_array();
    }

}