<?php

 
class Meeting_reassignment_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_all_meetings($parent_path)
    {
        $this->db->select('*, parent.usrUserName parentName, user.usrUserName userName');
        $this->db->from('meetings');
        $this->db->like('mtnParentPath', $parent_path, 'after');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $this->db->join('users as user', 'user.usrId = meetings.mtnUserId');
        $this->db->join('users as parent', 'parent.usrId = meetings.mtnParentId');
        $this->db->where('mtnCompleted','no');
        $query=$this->db->get();
        return $query->result_array();
    }


    function update_meeting($id, $params)
    {
        $this->db->where('mtnId',$id);
        $this->db->update('meetings',$params);
    }

}