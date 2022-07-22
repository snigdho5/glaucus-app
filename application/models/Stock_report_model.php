<?php

class Stock_report_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /************************Stock Report Model*************************/
    //Report section for collection    
    //function to get the records by selective interval dates & Quaterly as well
    function get_stock_filter_logs_web($data){
        
        $betweenClause  =   "`stock_history`.`created_at` BETWEEN '".$data['dateFrom']."' AND '".$data['dateTo']."'";

        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor,
            `package_items`.`item_name` AS item, 
            `package_mode`.`package_name` AS package, 
            `package_size`.`package_size_name` AS size, 
            `stock_history`.`stockQuantity` AS quantity, 
            `stock_history`.`itemPrice` AS price, 
            `stock_history`.`stockPrice` AS totalPrice, 
            date_format(`stock_history`.`created_at`, "%d-%b-%Y") AS stockDate
        ');
        $this->db->from('`stock_history`');
        $this->db->join('`users`', '`users`.`usrId` = `stock_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `stock_history`.`distributorId`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `stock_history`.`cartItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');
        $this->db->where($betweenClause);
        $this->db->order_by('`stock_history`.`subStockId`', "DESC");
        $query  =   $this->db->get();        
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }
    //function to get the records of current week
    function get_stock_report_current_week(){

        $where  =   "`stock_history`.`created_at` >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";

        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor,
            `package_items`.`item_name` AS item, 
            `package_mode`.`package_name` AS package, 
            `package_size`.`package_size_name` AS size, 
            `stock_history`.`stockQuantity` AS quantity, 
            `stock_history`.`itemPrice` AS price, 
            `stock_history`.`stockPrice` AS totalPrice, 
            date_format(`stock_history`.`created_at`, "%d-%b-%Y") AS stockDate
        ');

        $this->db->from('`stock_history`');
        $this->db->join('`users`', '`users`.`usrId` = `stock_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `stock_history`.`distributorId`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `stock_history`.`stockItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');
        $this->db->where($where);
        $this->db->order_by('`stock_history`.`subStockId`', "DESC");
        $query  =   $this->db->get();
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }
    //function to get the records of current month
    function get_stock_report_current_month(){

        $where  =   "`stock_history`.`created_at` between DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE()";

        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor,
            `package_items`.`item_name` AS item, 
            `package_mode`.`package_name` AS package, 
            `package_size`.`package_size_name` AS size, 
            `stock_history`.`stockQuantity` AS quantity, 
            `stock_history`.`itemPrice` AS price, 
            `stock_history`.`stockPrice` AS totalPrice, 
            date_format(`stock_history`.`created_at`, "%d-%b-%Y") AS stockDate
        ');

        $this->db->from('`stock_history`');
        $this->db->join('`users`', '`users`.`usrId` = `stock_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `stock_history`.`distributorId`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `stock_history`.`stockItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');
        $this->db->where($where);
        $this->db->order_by('`stock_history`.`subStockId`', "DESC");

        $query  =   $this->db->get();
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }    
    //function to get the records of current year
    function get_stock_report_current_year(){

        $where  =   "`stock_history`.`created_at` between DATE_FORMAT(CURDATE() ,'%Y-01-01') AND CURDATE()";

        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor,
            `package_items`.`item_name` AS item, 
            `package_mode`.`package_name` AS package, 
            `package_size`.`package_size_name` AS size, 
            `stock_history`.`stockQuantity` AS quantity, 
            `stock_history`.`itemPrice` AS price, 
            `stock_history`.`stockPrice` AS totalPrice, 
            date_format(`stock_history`.`created_at`, "%d-%b-%Y") AS stockDate
        ');

        $this->db->from('`stock_history`');
        $this->db->join('`users`', '`users`.`usrId` = `stock_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `stock_history`.`distributorId`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `stock_history`.`stockItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');
        $this->db->where($where);
        $this->db->order_by('`stock_history`.`subStockId`', "DESC");

        $query  =   $this->db->get();
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }    
    //filter get collections records by combinations
    function generate_stock_report($data){

        $betweenClause  =   "`stock_history`.`created_at` BETWEEN '".$data['dateFrom']."' AND '".$data['dateTo']."'";

        $this->db->select('
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor,
            `package_items`.`item_name` AS item, 
            `package_mode`.`package_name` AS package, 
            `package_size`.`package_size_name` AS size, 
            `stock_history`.`stockQuantity` AS quantity, 
            `stock_history`.`itemPrice` AS price, 
            `stock_history`.`stockPrice` AS totalPrice, 
            date_format(`stock_history`.`created_at`, "%d-%b-%Y") AS stockDate
        ');

        $this->db->from('`stock_history`');
        $this->db->join('`users`', '`users`.`usrId` = `stock_history`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `stock_history`.`distributorId`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `stock_history`.`stockItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');

        if($data['stockCartItemId']){
            $this->db->where('`stock_history`.`subStockId`',$data['stockCartItemId']);
        }
        if($data['salesUserId']){
            $this->db->where('`stock_history`.`salesUserId`',$data['salesUserId']);
        }
        if($data['distributorid']){
            $this->db->where('`stock_history`.`distributorId`',$data['distributorid']);
        }
        if($data['dateFrom'] && $data['dateTo']){
            $this->db->where($betweenClause);
        }
        $this->db->order_by('`stock_history`.`subStockId`', "DESC");
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