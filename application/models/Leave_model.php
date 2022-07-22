<?php

 
class Leave_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_all_pending_leave_request($parent_path)
    {

        $this->db->select('*');
        $this->db->from('leave_requests');
        $this->db->like('leave_requests.lreqParentPath', $parent_path, 'after');
        $this->db->join('users', 'users.usrId = leave_requests.lreqParentId');
        $this->db->where('leave_requests.lreqStatus','pending');
        $this->db->order_by("leave_requests.lreqId", "desc");
        $query  =   $this->db->get();
        return $query->result_array();
    }


    function get_all_accepted_leave_request($parent_path)
    {

        $this->db->select('*');
        $this->db->from('leave_requests');
        $this->db->like('leave_requests.lreqParentPath', $parent_path, 'after');
        $this->db->join('users', 'users.usrId = leave_requests.lreqParentId');
        $this->db->where('leave_requests.lreqStatus','accepted');
        $query=$this->db->get();
        return $query->result_array();
    }

    function get_all_rejected_leave_request($parent_path)
    {

        $this->db->select('*');
        $this->db->from('leave_requests');
        $this->db->like('leave_requests.lreqParentPath', $parent_path, 'after');
        $this->db->join('users', 'users.usrId = leave_requests.lreqParentId');
        $this->db->where('leave_requests.lreqStatus','rejected');
        $query=$this->db->get();
        return $query->result_array();
    }


    function update_accept_pending_leave_request($id, $params)
    {
        $this->db->where('lreqId',$id);
        $this->db->update('leave_requests',$params);
    }

    function update_reject_pending_leave_request($id, $params)
    {
        $this->db->where('lreqId',$id);
        $this->db->update('leave_requests',$params);
    }
  

}