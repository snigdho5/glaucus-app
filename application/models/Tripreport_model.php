<?php

 
class Tripreport_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_login_records_by_date($from_date, $to_date, $parent_path)
    {
        $this->db->select('*');
        $this->db->from('login_records');
        $this->db->join('users', 'users.usrId = login_records.lgnrUserId');
        $this->db->like('users.usrParentPath', $parent_path, 'after');
        $this->db->where('lgnrLoginDate >=',$from_date);
        $this->db->where('lgnrLoginDate <=',$to_date);
        $query=$this->db->get();
        return $query->result_array();
    }


    function get_trip_records_by_date($from_date, $to_date, $parent_path, $user_id)
    {
        $this->db->select('*');
        $this->db->from('trip_records');
        $this->db->join('users', 'users.usrId = trip_records.trpUserId');
        $this->db->like('users.usrParentPath', $parent_path, 'after');
        $this->db->where('trpStartDate >=',$from_date);
        $this->db->where('trpStartDate <=',$to_date);
        $this->db->where('users.usrId',$user_id);
        $query=$this->db->get();
        return $query->result_array();
    }


    

}