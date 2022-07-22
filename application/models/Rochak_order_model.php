<?php

class Rochak_order_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /***************************************** MOBILE API MODULE *****************************************/
    
    function get_exist_product_in_cart($data)
    {
        //return $this->db->get_where('order_cart',$data)->num_rows();
        $query      =   $this->db->get_where('order_cart',$data);
        $numRows    =   $query->num_rows();
        
        return $numRows;
    }
    
    function get_distributor_creditlimit($id)
    {
        $where  =   ['distributorId' => $id];
        
        $this->db->select('creditLimit');
        $this->db->from('distributors');
        $this->db->where($where);
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->row()->creditLimit;
        }
        else{
            return  false;
        }
    }
    
    function get_distributor_credit_outstanding($id)
    {
        $where  =   ['distributorid' => $id];
        
        $this->db->select_sum('creditOutstanding');
        $this->db->from('credit_master');
        $this->db->where($where);
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->row()->creditOutstanding;
        }
        else{
            return  false;
        }
    }
    
    function add_new_to_cart($data)
    {
        $this->db->insert('order_cart',$data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }
    
    function update_cart_data($data, $id)
    {
        $this->db->where('cartId',$id);
        $this->db->update('order_cart',$data);        
        return $this->db->affected_rows();
    }
    
    function delete_cart_data($data)
    {
        $this->db->where($data);
        $this->db->delete('order_cart');        
        return $this->db->affected_rows();
    }
    
    function get_cart_list($data)
    {
        $this->db->select('         
            `package_items`.`item_name`, 
            `package_size`.`package_size_name`, 
            `package_mode`.`package_name`, 
            `order_cart`.`cartItemId`, 
            `order_cart`.`cartId`, 
            `order_cart`.`cartQuantity`, 
            `order_cart`.`itemPrice`, 
            `order_cart`.`cartPrice`
        ');
        $this->db->from('`order_cart`');
        //$this->db->join('`users`', '`users`.`usrId` = `order_cart`.`salesUserId`');
        //$this->db->join('`distributors`', '`distributors`.`distributorId` = `order_cart`.`distributorId`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `order_cart`.`cartItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');
        $this->db->where('order_cart.distributorId', $data['distributorId']);
        $this->db->where('order_cart.salesUserId', $data['salesUserId']);
        $this->db->order_by('`order_cart`.`cartId`', "ASC");
        
        $query = $this->db->get();
        
        //return $this->db->last_query();
        //echo $this->db->last_query();die();
        
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            //print_r($resultArray);
            return $resultArray;
            //return json_encode($resultArray);
        }
        else{
            return $query->num_rows();
        }        
    }
    
    function get_cart_count_items($data)
    {
        $where  =   $data;
        
        $this->db->select('`cartItemId`');
        $this->db->from('`order_cart`');
        $this->db->where($where);
        $query          =   $this->db->get();
        if($query->num_rows() > 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }
    
    function get_cart_items($data)
    {
        $where  =   $data;
        
        $this->db->select('`salesUserId`, `distributorId`, `cartItemId`, `cartQuantity`, `itemPrice`, `cartPrice`');
        //$this->db->select('*');
        $this->db->from('order_cart');
        $this->db->where($where);
        $query          =   $this->db->get();
        //echo $this->db->last_query();die();
        
        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }
    
    function get_cart_total_price($data)
    {
        $where  =   $data;
        
        $this->db->select_sum('cartPrice');
        $this->db->from('order_cart');
        $this->db->where($where);
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->row()->cartPrice;
        }
        else{
            return  false;
        }
    }
    
    function get_cart_single_item_count($data)
    {
        $where  =   $data;
        
        $this->db->select('cartQuantity');
        $this->db->from('order_cart');
        $this->db->where($where);
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            return  $query->row()->cartQuantity;
        }
        else{
            return  false;
        }
    }
    
    function insert_order_master($data)
    {
        $this->db->insert('order_master',$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    
    function insert_order_history($cartList,$masterId)
    {
        $orderId['orderId']     =   $masterId;        
        foreach($cartList as $list){            
            $list   =   array_merge($list,$orderId);
            $this->db->insert('`order_history`',$list);            
        }
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    
    function insert_credit_master($data)
    {
        $this->db->insert('credit_master',$data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }
    
    function flush_cart_items($data)
    {
        $this->db->where($data);
        $this->db->delete('order_cart');        
        return $this->db->affected_rows();
        //print_r($data);
    }
    
    function get_order_history($data)
    {
        $this->db->select('
            `order_master`.`orderId`,
            `distributors`.`distributorId`,
            `distributors`.`distributorName` AS distributor,
            `order_master`.`noOfItemsOder` AS noOfItems,
            `order_master`.`ordertotal`,
             date_format(`order_master`.`createdat`, "%d-%b-%Y") AS orderDate
        ');
        $this->db->from('`order_master`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `order_master`.`distributorId`');
        //$this->db->where('order_master.distributorId', $data['distributorId']);
        $this->db->where('order_master.salesUserId', $data['salesUserId']);
        $this->db->order_by('`order_master`.`orderId`', "DESC");
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            // return json_encode($query->result_array());
            return $query->result();
        }
        else{
            return $query->num_rows();
        }
    }
    
    function get_order_details($data)
    {
        $this->db->select('
            `order_history`.`subOrderId` AS `orderId`,
            `package_items`.`item_name`, 
            `package_mode`.`package_name` AS `package`,
            `package_size`.`package_size_name` AS `size`,             
            `order_history`.`cartQuantity` AS `quantity`,
            `order_history`.`itemPrice` AS `price`,
            `order_history`.`cartPrice` AS `totalPrice`,
             date_format(`order_history`.`createdAt`, "%d-%b-%Y") AS `orderDate`
        ');
        $this->db->from('`order_history`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `order_history`.`cartItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');
        $this->db->where('order_history.distributorId', $data['distributorId']);
        $this->db->where('order_history.salesUserId', $data['salesUserId']);
        $this->db->where('order_history.orderId', $data['orderId']);
        $this->db->order_by('`order_history`.`subOrderId`', "ASC");
        
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            return json_encode($query->result_array());
        }
        else{
            return $query->num_rows();
        }
    }

    function update_invoice_amount($params)
    {
        $this->db->select('*');
        $this->db->from('`invoice_master`');
        $this->db->where('invoice_master.order_id', $params['order_id']);
        $this->db->where('invoice_master.invoice_status !=', 2);
        $queryT  =   $this->db->get();
        $resultT =   $queryT->result();
        //echo $this->db->last_query(); die();

        $getAmount      =   $params['amount'];
        $due_amount     =   '';
        $balance        =   '';

        $invoice_total          =   $this->generate_sum_of_invoice($params['order_id']);
        $invoice_paid_total     =   $this->generate_sum_of_invoice_paid($params['order_id']);

        $checkAmount    =   $invoice_total - $invoice_paid_total;
        if($checkAmount >= $getAmount){
            foreach ($resultT as $key => $result){
                if($getAmount >= $result->invoice_amount && $result->invoice_status == 0){
                    $array  =   [   
                                    'invoice_paid'      =>  $result->invoice_amount,
                                    'invoice_due'       =>  0, 
                                    'invoice_status'    =>  2
                                ];
                }
                else if($getAmount <= $result->invoice_amount  && $result->invoice_status == 0){
                    if($getAmount > 0){
                        $due_amount     =   $result->invoice_amount - $getAmount;
                        $array  =   [   
                                        'invoice_paid'      =>  $getAmount,
                                        'invoice_due'       =>  $due_amount, 
                                        'invoice_status'    =>  1
                                    ];
                    }
                }
                else if($getAmount >= $result->invoice_amount && $result->invoice_status == 1){
                    //1st single
                    if($getAmount >= $result->invoice_due){
                        $paid_amount    =   $result->invoice_paid   +   $result->invoice_due;
                        $due_amount     =   0;
                        $status         =   2;
                        $balance        =   $getAmount - $result->invoice_due;
                    }
                    else{
                        $paid_amount    =   $result->invoice_paid   +   $getAmount;
                        $due_amount     =   $result->invoice_due    -   $getAmount;
                        $status         =   1;
                    }
                    $array  =   [   
                                    'invoice_paid'      =>  $paid_amount,
                                    'invoice_due'       =>  $due_amount,
                                    'invoice_status'    =>  $status
                                ];
                }            
                else if($getAmount <= $result->invoice_amount  && $result->invoice_status == 1){
                    //2nd onwards
                    if($getAmount >= $result->invoice_due){
                        $paid_amount    =   $result->invoice_paid   +   $result->invoice_due;
                        $due_amount     =   0;
                        $status         =   2;
                        $balance        =   $getAmount - $result->invoice_due;
                    }
                    else{
                        $paid_amount    =   $result->invoice_paid   +   $getAmount;
                        $due_amount     =   $result->invoice_due    -   $getAmount;
                        $status         =   1;
                    }
                    $array  =   [   
                                    'invoice_paid'      =>  $paid_amount,
                                    'invoice_due'       =>  $due_amount,
                                    'invoice_status'    =>  $status
                                ];
                }
                if(0 > $getAmount){
                    break;
                }
                $this->db->where('invoice_id',$result->invoice_id);
                $this->db->update('invoice_master',$array);
                $getAmount  =   $getAmount - $result->invoice_amount;
                if($balance > 0){
                    $getAmount  =  $balance;
                    $balance    =   0;
                }
            }
            return true;
        }
        else{
            return false;
        }
    }

    function generate_sum_of_invoice($id)
    {
        //get the sum of project invoice
        $this->db->select_sum('`invoice_master`.`invoice_amount`');
        $this->db->from('`invoice_master`');
        $this->db->where('invoice_master.order_id', $id);
        //$this->db->where("invoice_master.paid_status IS NULL");
        $query          =   $this->db->get();
        $resultArray    =   $query->result();
         
        if($resultArray[0]->invoice_amount > 0){
            return $resultArray[0]->invoice_amount;
        }
        else{
            return false;
        }
    }

    function generate_sum_of_invoice_paid($id)
    {
        $this->db->select_sum('`invoice_master`.`invoice_paid`');
        $this->db->from('`invoice_master`');
        $this->db->where('invoice_master.order_id', $id);
        $query          =   $this->db->get();
        $resultArray    =   $query->result();
         
        if($resultArray[0]->invoice_paid > 0){
            return $resultArray[0]->invoice_paid;
        }
        else{
            return false;
        }
    }

    function fetch_invoice_data(){
        $this->db->select('*');
        $this->db->from('`invoice_master`');
        $query  =   $this->db->get();
        $result =   $query->result();

        foreach ($result as $value){
            unset($value->created_at);
            unset($value->updated_at);
        }

        return $result;
    }

    function fetch_invoice_collection_statement()
    {
        $this->db->select('*');
        $this->db->from('`invoice_collection_list`');
        $query  =   $this->db->get();
        $result =   $query->result();

        foreach ($result as $value){
            unset($value->created_at);
            unset($value->updated_at);
        }

        return $result;
    }

    function fetch_invoice_group_by_orders($distributor)
    {
        
        $this->db->select('    
            `invoice_master`.`invoice_id`,
            `order_master`.`orderId as order_id`,
            `order_master`.`distributorId as distributor_id`,
            `invoice_master`.`invoice_ins_id`,
            `invoice_master`.`invoice_date`,
            `invoice_master`.`invoice_amount`,
        ');
        $this->db->from('`invoice_master`');
        $this->db->join('`order_master`', '`order_master`.`orderId` = `invoice_master`.`order_id`');
        $this->db->where('order_master.distributorId', $distributor);
        //$this->db->where('order_master.orderId', $order);
        $this->db->group_by('`order_master`.`orderId`'); 
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        foreach ($query->result() as $value){
            $value->invoice_amount  =   $this->sum_total_order_invoice_amount($value->order_id);
        }
        return $query->result();
    }

    function fetch_invoice_data_by_distributors($order)
    {
        $this->db->select('    
            `invoice_master`.`invoice_id`,
            `invoice_master`.`order_id`,
            `order_master`.`distributorId as distributor_id`,
            `invoice_master`.`invoice_ins_id`,
            `invoice_master`.`invoice_date`,
            `invoice_master`.`invoice_amount`,
            `invoice_master`.`invoice_paid` as paid,
            `invoice_master`.`invoice_due` as outstanding_amount,
            `invoice_master`.`invoice_status` as status,
        ');
        $this->db->from('`invoice_master`');
        $this->db->join('`order_master`', '`order_master`.`orderId` = `invoice_master`.`order_id`');
        //$this->db->where('order_master.distributorId', $distributor);
        $this->db->where('order_master.orderId', $order);
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        foreach ($query->result() as $value) {
            if($value->status == 0){
                $value->status = 'Due';
            }
            else if($value->status == 1){
                $value->status = 'Partial Paid';
            }
            else{
                $value->status = 'Paid';
            }
        }
        return $query->result();
    }

    function fetch_orders_by_distributors($distributor)
    {
        $this->db->select('    
            `order_master`.`orderId` as `order_id`,
            `order_master`.`ordertotal as total`
        ');
        $this->db->from('`order_master`');
        $this->db->where('order_master.distributorId', $distributor['distributorId']);
        $this->db->where('order_master.salesUserId', $distributor['salesUserId']);
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->result();
    }

    function sum_total_order_invoice_amount($id)
    {
        $where  =   ['order_id' => $id];
        
        $this->db->select_sum('invoice_amount');
        $this->db->select_sum('invoice_paid');
        $this->db->from('invoice_master');
        $this->db->where($where);
        $query = $this->db->get();

        $amount = $query->result()[0]->invoice_amount - $query->result()[0]->invoice_paid;
        
        if($query->num_rows() > 0){
            return  $query->result()[0]->invoice_amount = $amount;
        }
        else{
            return  false;
        }
    }
    
    /***************************************** MOBILE API MODULE *****************************************/    
    
    /***************************************** WEB API MODULE *****************************************/
    
    function get_all_orders()
    {
        $this->db->select('    
            `order_master`.`orderId` AS id,
            `users`.`usrFirstName` AS firstName, 
            `users`.`usrLastName` AS lastName, 
            `distributors`.`distributorName` AS distributor, 
            `order_master`.`noOfItemsOder`, 
            `order_master`.`ordertotal` AS totalPrice,
            `order_master`.`status`
        ');
        $this->db->from('`order_master`');
        $this->db->join('`users`', '`users`.`usrId` = `order_master`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `order_master`.`distributorId`');
        $this->db->order_by('`order_master`.`orderId`', "ASC");
        
        $query = $this->db->get();
        
        //return $this->db->last_query();
        //echo $this->db->last_query();die();
        
        $resultArray    =   $query->result_array();
        
        if($query->num_rows() > 0){
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }
    }

    function get_orders_list_app($id)
    {
        $this->db->select('    
            `order_master`.`orderId` as order_id,
            `distributors`.`distributorId` as distributor_id, 
            `distributors`.`distributorName` as distributor, 
            `order_master`.`ordertotal` as totalPrice
        ');
        $this->db->from('`order_master`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `order_master`.`distributorId`');
        $this->db->order_by('`order_master`.`orderId`', "ASC");
        $this->db->where('order_master.salesUserId', $id);

        $query = $this->db->get();
        $resultArray    =   $query->result_array();
        
        if($query->num_rows() > 0){
            return $resultArray;
        }
        else{
            return false;
        }
    }
    
    function get_single_order($id)
    {
        $this->db->select('
            `package_items`.`item_name` AS itemName, 
            `package_size`.`package_size_name` AS packageSize, 
            `package_mode`.`package_name` AS packageMode, 
            `order_history`.`cartQuantity`, 
            `order_history`.`itemPrice`, 
            `order_history`.`cartPrice` AS totalItemPrice, 
             date_format(`order_history`.`createdAt`, "%d-%b-%Y") AS orderDate
        ');
        $this->db->from('`order_history`');
        $this->db->join('`package_items`', '`package_items`.`pckg_id` = `order_history`.`cartItemId`');
        $this->db->join('`package_size`', '`package_size`.`pckg_size_id` = `package_items`.`package_size`');
        $this->db->join('`package_mode`', '`package_mode`.`pckg_mode_id` = `package_items`.`mode_of_package`');
        $this->db->where('order_history.orderId', $id);
        $this->db->order_by('`order_history`.`subOrderId`', "ASC");
        
        $query = $this->db->get();
        
        //echo $this->db->last_query();die();
        
        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            //print_r($resultArray);
            return $resultArray;
            //return json_encode($resultArray);
        }
        else{
            return $query->num_rows();
        }
    }

    function insert_add_invoice($data)
    {
        $this->db->insert('invoice_master',$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function get_order_invoice($id){
        $this->db->select('*');
        $this->db->from('`invoice_master`');
        $this->db->where('invoice_master.order_id', $id);
        $this->db->order_by('`invoice_master`.`invoice_id`', "DESC");
        $query = $this->db->get();
        $result_array    =   $query->result_array();
        if($query->num_rows() > 0){
            return $result_array;
        }
        else{
            return $query->num_rows();
        }
    }

    //Update the status of the distributor
    function update_status($data,$id){
        $this->db->where('orderId',$id);
        $this->db->update('order_master',$data);
        return $this->db->affected_rows();
    }
    
    /***************************************** WEB API MODULE *****************************************/    
    
}