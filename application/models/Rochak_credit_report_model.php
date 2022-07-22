<?php

class Rochak_credit_report_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //display the projection logs
    function get_credit_logs_web()
    {
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            `payment_projection_history`.`dateOfCollection` AS collectionDate, 
            `payment_projection_history`.`projectionAmount`, 
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        $this->db->order_by('`payment_projection_history`.`projectionId`', "DESC");
        
        $query = $this->db->get();
        
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }        
    }    
    /************************Projection Report Model*************************/
    //Report section for Projection
    //function to get the records of current week
    function get_credit_report_current_week($data)
    {
        $where  =   "`credit_master`.`created_at` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            `credit_master`.`creditAmout` AS credit, 
            `credit_master`.`creditSettled` AS settled, 
            `credit_master`.`creditOutstanding` AS outstanding, 
            date_format(`credit_master`.`created_at`, "%d-%b-%Y") AS creditDate
        ');
        $this->db->from('`credit_master`');
        $this->db->join('`users`', '`users`.`usrId` = `credit_master`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `credit_master`.`distributorid`');   
        if($data['distributorid']){
            $this->db->where('`credit_master`.`distributorid`',$data['distributorid']);
        }
        if($data['salesUserId']){
            $this->db->where('`credit_master`.`salesUserId`',$data['salesUserId']);
        }
        $this->db->where($where);
        $this->db->order_by('`credit_master`.`creditId`', "DESC");
        $query  =   $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }       
    }
    //function to get the records of current month
    function get_credit_report_current_month($data)
    {
        $where  =   "`credit_master`.`created_at` between DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE()";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            `credit_master`.`creditAmout` AS credit, 
            `credit_master`.`creditSettled` AS settled, 
            `credit_master`.`creditOutstanding` AS outstanding, 
            date_format(`credit_master`.`created_at`, "%d-%b-%Y") AS creditDate
        ');
        $this->db->from('`collection_master`');
        $this->db->join('`users`', '`users`.`usrId` = `credit_master`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `credit_master`.`distributorid`');
        if($data['distributorid']){
            $this->db->where('`credit_master`.`distributorid`',$data['distributorid']);
        }
        if($data['salesUserId']){
            $this->db->where('`credit_master`.`salesUserId`',$data['salesUserId']);
        }
        $this->db->where($where);
        $this->db->order_by('`credit_master`.`creditId`', "DESC");
        $query  =   $this->db->get();
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }        
    }
    //function to get the records of current year
    function get_credit_report_current_year($data)
    {
        $where  =   "`credit_master`.`created_at` between  DATE_FORMAT(CURDATE() ,'%Y-01-01') AND CURDATE()";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            `credit_master`.`creditAmout` AS credit, 
            `credit_master`.`creditSettled` AS settled, 
            `credit_master`.`creditOutstanding` AS outstanding, 
            date_format(`credit_master`.`created_at`, "%d-%b-%Y") AS creditDate
        ');
        $this->db->from('`credit_master`');
        $this->db->join('`users`', '`users`.`usrId` = `credit_master`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `credit_master`.`distributorid`');
        if($data['distributorid']){
            $this->db->where('`credit_master`.`distributorid`',$data['distributorid']);
        }
        if($data['salesUserId']){
            $this->db->where('`credit_master`.`salesUserId`',$data['salesUserId']);
        }
        $this->db->where($where);
        $this->db->order_by('`credit_master`.`creditId`', "DESC");
        $query  =   $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }
    //filter get collections records by combinations
    function generate_credit_report($data)
    {
        $betweenClause  =   "`credit_master`.`created_at` BETWEEN '".$data['dateFrom']."' AND '".$data['dateTo']."'";
        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            `credit_master`.`creditAmout` AS credit, 
            `credit_master`.`creditSettled` AS settled, 
            `credit_master`.`creditOutstanding` AS outstanding, 
            date_format(`credit_master`.`created_at`, "%d-%b-%Y") AS creditDate
        ');
        $this->db->from('`credit_master`');
        $this->db->join('`users`', '`users`.`usrId` = `credit_master`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `credit_master`.`distributorid`');
        if($data['dateFrom'] && $data['dateTo']){
            $this->db->where($betweenClause);
        }
        if($data['distributorid']){
            $this->db->where('`credit_master`.`distributorid`',$data['distributorid']);
        }
        if($data['salesUserId']){
            $this->db->where('`credit_master`.`salesUserId`',$data['salesUserId']);
        }
        $this->db->where('`credit_master`.`creditOutstanding` !=',0);
        $this->db->order_by('`credit_master`.`creditId`', "DESC");
        $query  =   $this->db->get();
        /*echo $this->db->last_query();
        die();*/
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }
}