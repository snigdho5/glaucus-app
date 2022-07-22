<?php
 
class Customerinfo_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_all_customers($parent_path)
    {

        $this->db->select('cust.*, parent.usrUserName parentName, manage.usrUserName manageName');
        $this->db->from('customers as cust');
        $this->db->like('cust.cusParentPath', $parent_path, 'after');
        $this->db->join('users as manage', 'manage.usrId = cust.cusManageId');
        $this->db->join('users as parent', 'parent.usrId = cust.cusParentId');
        $query=$this->db->get();
        return $query->result_array();



    }  

    public function get_customer_data($params)
    {
        return $this->db->get_where('customers',$params)->row_array();
    }

    function get_all_customer_meetings($id)
    {
        $this->db->select('*, parent.usrUserName parentName, user.usrUserName userName');
        $this->db->from('meetings');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $this->db->join('users as user', 'user.usrId = meetings.mtnUserId');
        $this->db->join('users as parent', 'parent.usrId = meetings.mtnParentId');
        $this->db->where('meetings.mtnCustomerId',$id);
        $query=$this->db->get();
        return $query->result_array();
    }

}