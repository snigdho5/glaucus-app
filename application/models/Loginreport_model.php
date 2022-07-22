<?php

 
class Loginreport_model extends CI_Model
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
        //$this->db->join('leave_requests', 'leave_requests.lreqParentId = login_records.lgnrUserId','left');
        // $this->db->join('leave_requests', 'leave_requests.lreqParentId = login_records.lgnrUserId');
        $this->db->like('users.usrParentPath', $parent_path, 'after');
        //$this->db->like('leave_requests.lreqParentPath', $parent_path, 'after');
        $this->db->where('lgnrLoginDate >=',$from_date);
        $this->db->where('lgnrLoginDate <=',$to_date);
        // $this->db->group_by('login_records.lgnrUserId');
        $query=$this->db->get();
        //echo $this->db->last_query();die();
        return $query->result_array();
    }

    function get_lat_long_by_user_date($data){
        $tokenClause    =   "`appTokenid` IS NOT NULL";
        $crlDate        =   "`crlDate` LIKE '".$data['date']."'";

        $this->db->select('`crlLat` as latitude, `crlLong` as longitude');
        $this->db->from('current_locations');
        $this->db->where('crlUserId =',$data['user_id']);
        $this->db->where($tokenClause);
        // $this->db->where('uniqLoginId =',$data['uniqLoginId']);
        $this->db->where($crlDate);
        $this->db->order_by('crlTime asc');
        $query  =   $this->db->get();
        
        // echo $this->db->last_query();die();

        return $query->result_array();
    }
}