<?php

 
class Map_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_active_app_users($parent_path)
    {

        $this->db->select('child.*, parent.usrUserName parentName');
        $this->db->from('users as child');
        $this->db->like('child.usrParentPath', $parent_path, 'after');
        $this->db->join('users as parent', 'parent.usrId = child.usrParentId');
        $this->db->where('child.usrTypeName','appuser');
        $this->db->where('child.usrStatusName','active');
        $this->db->where('child.usrCurrentActive','yes');
        $query=$this->db->get();
        return $query->result_array();
    }

    public function get_user_data($params)
    {
        $this->db->select('child.*,meetings.*,customers.*');
        $this->db->from('users as child');
        $this->db->join('meetings', 'meetings.mtnId = child.usrLastMeetingId');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $this->db->where('child.usrId',$params['usrId']);
        $query=$this->db->get();
        return $query->row_array();
    }


    function get_login_records_by_user_id($params)
    {
        $this->db->select('*');
        $this->db->from('login_records');
        $this->db->where('lgnrUserId',$params['usrId']);
        $this->db->where('lgnrLoginDate',$params['currentDate']);
        $query=$this->db->get();
        return $query->result_array();
    }


    function get_login_record_locations($params)
    {
        $this->db->select('*');
        $this->db->from('current_locations');
        //$this->db->where('crlLoginRecordId',$params['crlLoginRecordId']);
        $this->db->where($params);
        $this->db->order_by('crlTime asc');
        $query=$this->db->get();
        // echo $this->db->last_query(); die();
        return $query->result_array();
    }

    function get_login_record_locations_direction($params)
    {
        $this->db->select('*');
        $this->db->from('current_locations');
        $this->db->where($params);
        $this->db->order_by('crlTime asc');
        // $this->db->group_by('crlTime');
        $query=$this->db->get();
        // echo $this->db->last_query(); die();
        return $query->result_array();
    }


    function get_trip_records($params)
    {
        $this->db->select('*');
        $this->db->from('trip_records');
        $this->db->where('trpLoginRecordId',$params['trpLoginRecordId']);
        $this->db->order_by('trpStartTime asc');
        $query=$this->db->get();
        return $query->result_array();
    }


    function get_trip_record_locations($params)
    {
        $this->db->select('*');
        $this->db->from('trip_locations');
        $this->db->where('trplTripRecordId',$params['trplTripRecordId']);
        $this->db->order_by('trplTime asc');
        $query=$this->db->get();
        return $query->result_array();
    }


    function get_user_visited_by_user_id($params)
    {
        $this->db->select('*');
        $this->db->from('meetings');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $this->db->where('mtnUserId',$params['usrId']);
        $this->db->where('mtnVisitedDate',$params['currentDate']);
        $this->db->where('mtnVisited','yes');
        $query=$this->db->get();
        return $query->result_array();
    }

    

}