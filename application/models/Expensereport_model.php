<?php

 
class Expensereport_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_expense_records_by_date($from_date, $to_date, $parent_path, $user_id)
    {
        $this->db->select('*');
        $this->db->from('expenses');
        $this->db->join('users', 'users.usrId = expenses.expParentId');
        $this->db->like('users.usrParentPath', $parent_path, 'after');
        $this->db->where('expenses.expDate >=',$from_date);
        $this->db->where('expenses.expDate <=',$to_date);
        $this->db->where('users.usrId',$user_id);
        $query=$this->db->get();
        return $query->result_array();
    }


    function get_expense_records_by_date_and_payment($from_date, $to_date, $parent_path, $user_id, $payment_status)
    {
        $this->db->select('*');
        $this->db->from('expenses');
        $this->db->join('users', 'users.usrId = expenses.expParentId');
        $this->db->like('users.usrParentPath', $parent_path, 'after');
        $this->db->where('expenses.expDate >=',$from_date);
        $this->db->where('expenses.expDate <=',$to_date);
        $this->db->where('users.usrId',$user_id);
        $this->db->where('expenses.expPaymentStatus',$payment_status);
        $query=$this->db->get();
        return $query->result_array();
    }


    

}