<?php

 
class Order_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    public function get_user_data($params)
    {
        return $this->db->get_where('users',$params)->row_array();
    }


    function get_all_orders($parent_path)
    {

        $this->db->select('*');
        $this->db->from('orders');
        $this->db->like('orders.ordParentPath', $parent_path, 'after');
        $this->db->join('users', 'users.usrId = orders.ordParentId');
        $this->db->join('meetings', 'meetings.mtnId = orders.ordMeetingId');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_order_data($params)
    {
        return $this->db->get_where('orders',$params)->row_array();
    }

    public function get_user_units($id)
    {
        $this->db->select('*');
        $this->db->from('user_units');
        $this->db->join('units', 'units.untId = user_units.usruUnitId');
        $this->db->where('user_units.usruUserId',$id);
        $query=$this->db->get();
        return $query->result_array();
    }

    public function get_venues_by_unit($id)
    {
        $this->db->select('*');
        $this->db->from('venues');
        $this->db->join('units', 'units.untId = venues.venUnitId');
        $this->db->where('venues.venUnitId',$id);
        $query=$this->db->get();
        return $query->result_array();
    }


    function get_order_status_types()
    {
        return $this->db->get('order_status_types')->result_array();
    }


    public function get_edit_order_name_duplicate($id, $name)
    {
        $this->db->where('ordName',$name);
        $this->db->where_not_in('ordId', $id);
        $query=$this->db->get('orders');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }


    function update_order($id, $params)
    {
        $this->db->where('ordId',$id);
        $this->db->update('orders',$params);
    }

    

}