<?php

 
class Expense_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_all_expenses($parent_path)
    {

        $this->db->select('*');
        $this->db->from('expenses');
        $this->db->like('expParentPath', $parent_path, 'after');
        $this->db->join('users', 'users.usrId = expenses.expParentId');
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_expense_meeting_data($mtnId)
    {
        $this->db->select('*');
        $this->db->from('meetings');
        $this->db->where('mtnId',$mtnId);
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $query=$this->db->get();
        return $query->row_array();
    }


    function update_payment_status($id, $params)
    {
        $this->db->where('expId',$id);
        $this->db->update('expenses',$params);

        if($this->db->affected_rows() == '1'){
            return true;
        }else{
            return false;
        }
    }

}