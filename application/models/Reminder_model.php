<?php

 
class Reminder_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_current_meetings($date)
    {
        $this->db->select('*');
        $this->db->from('meetings');
        $this->db->join('users', 'users.usrId = meetings.mtnUserId');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $this->db->where('mtnDate',$date);
        $query=$this->db->get();
        return $query->result_array();
    }


    

}