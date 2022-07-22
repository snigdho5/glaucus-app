<?php

class Credit_projection_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /***************************************** WEB API MODULE *****************************************/

    function get_credit_history_web()
    {
        $this->db->select('
            `credit_master`.`creditId` AS id,
            `credit_master`.`orderId`,
            `distributors`.`distributorName` AS distributor,
            `users`.`usrFirstName` AS firstName,
            `users`.`usrLastName` AS lastName,
            `credit_master`.`creditAmout` AS credit,
            `credit_master`.`creditSettled` AS settled,
            `credit_master`.`creditOutstanding` AS outstanding,
            date_format(`credit_master`.`created_at`, "%d-%b-%Y") AS creditDate,
            date_format(`credit_master`.`updated_on`, "%d-%b-%Y") AS lastPaymentDate
        ');
        $this->db->from('`credit_master`');
        $this->db->join('`users`', '`users`.`usrId` = `credit_master`.`salesUserId`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `credit_master`.`distributorid`');
        $this->db->order_by('`credit_master`.`creditId`', "DESC");

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return $query->num_rows();
        }
    }

    function get_projection_history_web()
    {
        $this->db->select('
            `payment_projection_master`.`masterProjectionId` AS id,
            `users`.`usrFirstName` AS firstName,
            `users`.`usrLastName` AS lastName,
            `payment_projection_master`.`numberOfProjection` AS projectioncount,
            `payment_projection_master`.`totalAmount` AS projectionamount,
            date_format(`payment_projection_master`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_master`');
        $this->db->join('`users`', '`users`.`usrId` = `payment_projection_master`.`salesUserId`');
        $this->db->order_by('`payment_projection_master`.`masterProjectionId`', "DESC");

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return $query->num_rows();
        }
    }

    function get_single_projection_web($id)
    {
        $this->db->select('
            `payment_projection_history`.`projectionId` AS id,
            `distributors`.`distributorName` AS distributor,
            date_format(`payment_projection_history`.`dateOfCollection`, "%d-%b-%Y") AS collectionDate,
            `payment_projection_history`.`projectionAmount` AS collectionAmount,
            date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        $this->db->where('payment_projection_history.masterProjectionId', $id);
        $this->db->order_by('`payment_projection_history`.`projectionId`', "ASC");

        $query = $this->db->get();

        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }
    }

    /***************************************** WEB API MODULE *****************************************/

    /***************************************** MOBILE API MODULE *****************************************/

    function get_exist_projection_in_cart($data)
    {
        unset($data['projectionAmount']);
        unset($data['dateOfCollection']);
        $query      =   $this->db->get_where('payment_projection_cart',$data);
        //echo $this->db->last_query(); die();
        $numRows    =   $query->num_rows();
        return $numRows;
    }

    function get_total_credit_outstanding($data)
    {
        unset($data['projectionCartId']);
        unset($data['projectionAmount']);
        unset($data['dateOfCollection']);
        $where  =   $data;

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

    function add_new_projection($data)
    {
        $this->db->insert('payment_projection_cart',$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function get_projection_cart_total($data)
    {
        $where  =   $data;

        $this->db->select_sum('projectionAmount');
        $this->db->from('payment_projection_cart');
        $this->db->where($where);
        $query = $this->db->get();

        if($query->num_rows() > 0){
            return  $query->row()->projectionAmount;
        }
        else{
            return  false;
        }
    }

    function update_projection_amount($data, $id)
    {
        $this->db->where('projectionCartId',$id);
        $this->db->update('payment_projection_cart',$data);
        return $this->db->affected_rows();
    }

    function delete_projection_data($data)
    {
        $this->db->where($data);
        $this->db->delete('payment_projection_cart');
        return $this->db->affected_rows();
    }

    function get_projection_cart_list($data)
    {
        $this->db->select('
            `payment_projection_cart`.`projectionCartId` as id,
            `payment_projection_cart`.`distributorid`,
            `distributors`.`distributorName` as distributor,
            `payment_projection_cart`.`dateOfCollection`,
            `payment_projection_cart`.`projectionAmount` as collectionAmount,
            `payment_projection_cart`.`paymentMode` as payment_mode,
             date_format(`payment_projection_cart`.`created_at`, "%Y-%m-%d") as dateOfProjection
        ');
        $this->db->from('`payment_projection_cart`');
        $this->db->join('`distributors`', '`distributors`.`distributorid` = `payment_projection_cart`.`distributorid`');
        $this->db->where('payment_projection_cart.salesUserId', $data['salesUserId']);
        $this->db->order_by('`payment_projection_cart`.`projectionCartId`', "ASC");

        $query = $this->db->get();

        $resultArray    =   $query->result_array();
        if($query->num_rows() > 0){
            foreach ($resultArray as $key => $value) {
                if($value['payment_mode'] == 0){
                    $resultArray[$key]['payment_mode']  =   'cash';
                }
                else if($value['payment_mode'] == 1){
                    $resultArray[$key]['payment_mode']  =   'cheque';
                }
                else{
                    $resultArray[$key]['payment_mode']  =   'bank transfer';
                }
            }
            return $resultArray;
        }
        else{
            return $query->num_rows();
        }
    }

    function get_cart_projections($data)
    {
        $where  =   $data;

        $this->db->select('`salesUserId`, `distributorId`, `dateOfCollection`, `order_id`, `projectionAmount`');
        $this->db->from('payment_projection_cart');
        $this->db->where($where);
        $query          =   $this->db->get();

        if($query->num_rows() > 0){
            return  $query->result_array();
        }
        else{
            return  false;
        }
    }

    function get_cart_count_projection($data)
    {
        $where  =   $data;

        $this->db->select('`projectionCartId`');
        $this->db->from('`payment_projection_cart`');
        $this->db->where($where);
        $query          =   $this->db->get();
        if($query->num_rows() > 1){
            return $query->num_rows();
        }
        else{
            return $query->num_rows();
        }
    }

    function insert_projection_master($data)
    {
        $this->db->insert('payment_projection_master',$data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function insert_projection_history($cartList,$masterId)
    {
        $orderId['masterProjectionId']     =   $masterId;
        foreach($cartList as $list){
            $list   =   array_merge($list,$orderId);
            $this->db->insert('`payment_projection_history`',$list);
        }
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function flush_cart_projections($data)
    {
        $this->db->where($data);
        $this->db->delete('payment_projection_cart');
        return $this->db->affected_rows();
        //print_r($data);
    }

    function get_projection_history($data)
    {
        $this->db->select('
            `payment_projection_master`.`masterProjectionId` AS id,
            `payment_projection_master`.`numberOfProjection`,
            `payment_projection_master`.`totalAmount`,
             date_format(`payment_projection_master`.`created_at`, "%d-%b-%Y") AS projectionDate
        ');
        $this->db->from('`payment_projection_master`');
        $this->db->where('payment_projection_master.salesUserId', $data['salesUserId']);
        $this->db->order_by('`payment_projection_master`.`masterProjectionId`', "DESC");

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return $query->num_rows();
        }
    }

    function get_projection_details($data)
    {
        $this->db->select('
            `payment_projection_history`.`projectionId` AS `id`,
            `distributors`.`distributorName` AS distributor,
            `payment_projection_history`.`dateOfCollection` AS `collectionDate`,
            `payment_projection_history`.`projectionAmount` AS amount,
            `payment_projection_history`.`paymentMode` AS payment_mode,
             date_format(`payment_projection_history`.`created_at`, "%d-%b-%Y") AS `projectionDate`
        ');
        $this->db->from('`payment_projection_history`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `payment_projection_history`.`distributorid`');
        $this->db->where('payment_projection_history.masterProjectionId', $data['masterProjectionId']);
        $this->db->where('payment_projection_history.salesUserId', $data['salesUserId']);
        $this->db->order_by('`payment_projection_history`.`projectionId`', "ASC");

        $query = $this->db->get();

        //echo $this->db->last_query(); die();

        if($query->num_rows() > 0){
            //return json_encode($query->result_array());
            $resultArray  = $query->result_array();
            foreach ($resultArray as $key => $value) {
                if($value['payment_mode'] == 0){
                    $resultArray[$key]['payment_mode']  =   'cash';
                }
                else if($value['payment_mode'] == 1){
                    $resultArray[$key]['payment_mode']  =   'cheque';
                }
                else{
                    $resultArray[$key]['payment_mode']  =   'bank transfer';
                }
            }
            return json_encode($resultArray);
        }
        else{
            return $query->num_rows();
        }
    }

    function get_credit_list($data)
    {
        $this->db->select('
            `credit_master`.`creditId` AS `id`,
            `distributors`.`distributorName` AS distributor,
            `credit_master`.`creditAmout` AS `totalCreditAmount`,
            `credit_master`.`creditSettled` AS `settledAmount`,
            `credit_master`.`creditOutstanding` AS `outstandingAmount`,
             date_format(`credit_master`.`created_at`, "%d-%b-%Y") AS `creditDate`,
             date_format(`credit_master`.`updated_on`, "%d-%b-%Y") AS `lastCollectionDate`
        ');
        $this->db->from('`credit_master`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `credit_master`.`distributorid`');
        $this->db->where('credit_master.salesUserId', $data['salesUserId']);
        $this->db->where('credit_master.creditOutstanding !=', 0);
        $this->db->order_by('`credit_master`.`creditId`', "DESC");

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return $query->num_rows();
        }
    }

    function get_single_order_credit($data)
    {
        $this->db->select('*');
        $this->db->from('`credit_master`');
        $this->db->where($data);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array()[0];
        }
        else{
            return $query->num_rows();
        }

    }

    function get_today_collection_logs($data)
    {
        $this->db->select('
            `credit_master`.`creditId`,
            `distributors`.`distributorName` AS distributor,
            `credit_master`.`creditAmout` AS `creditAmount`,
            `credit_master`.`creditSettled` AS `amountCollected`,
            `payment_projection_history`.`projectionAmount` AS `collectionAmount`,
             date_format(`credit_master`.`created_at`, "%d-%b-%Y") AS `creditDate`
        ');
        $this->db->from('`credit_master`');
        $this->db->join('`distributors`', '`distributors`.`distributorId` = `credit_master`.`distributorid`');
        $this->db->join('`payment_projection_history`', '`payment_projection_history`.`salesUserId` = `credit_master`.`salesUserId`');
        $this->db->where('credit_master.salesUserId', $data['salesUserId']);
        $this->db->where('payment_projection_history.dateOfCollection', date("Y-m-d"));
        $this->db->order_by('`credit_master`.`creditId`', "DESC");

        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        else{
            return $query->num_rows();
        }
    }

    function update_collection_order_amount($data, $id)
    {
        $this->db->where('creditId',$id);
        $this->db->update('credit_master',$data);
        return $this->db->affected_rows();
    }

    function insert_new_collection($data)
    {
        $this->db->insert('collection_master',$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    /***************************************** MOBILE API MODULE *****************************************/

}
