<?php

class Cronjob_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_yesterday_projection_report_web()
    {
        $this->db->select('
            `payment_projection_history`.`projectionId` AS id, 
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            SUM(`payment_projection_history`.`projectionAmount`) AS amount, 
            date_format(`payment_projection_history`.`dateOfCollection`, "%d-%b-%Y") AS collectionDate,
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_history`.`salesUserId`');
        //$this->db->where('payment_projection_history.dateOfCollection', date('Y-m-d', strtotime(' -1 day')));
        $this->db->where('payment_projection_history.dateOfCollection', date('Y-m-d'));
        $this->db->order_by('`payment_projection_history`.`projectionId`', "ASC");
        $this->db->group_by('`payment_projection_history`.`salesUserId`');
        
        $query = $this->db->get();
        
        /*echo $this->db->last_query();
        die();*/
        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return $query->num_rows();
        }
    }
    
    function get_yesterday_projection_total_web()
    {
        //$where  =   ['dateOfCollection' => date('Y-m-d', strtotime(' -1 day'))];
        $where  =   ['dateOfCollection' => date('Y-m-d')];
        
        $this->db->select_sum('projectionAmount');
        $this->db->from('payment_projection_history');
        $this->db->where($where);
        $query = $this->db->get();
        
        /*echo $this->db->last_query();
        die();*/
        
        if($query->num_rows() > 0){
            return  $query->row()->projectionAmount;
        }
        else{
            return  false;
        }
    }
    
    function get_today_distributor_birthday()
    {
        
    }
}