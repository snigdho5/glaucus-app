<?php

 
class Orderreport_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_order_records_by_date($params, $parent_path)
    {
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->join('users', 'users.usrId = orders.ordParentId');
        $this->db->join('meetings', 'meetings.mtnId = orders.ordMeetingId');
        $this->db->join('customers', 'customers.cusId = meetings.mtnCustomerId');
        if($params['adminId']==0){
            $this->db->like('orders.ordParentPath', $parent_path, 'after');
        }else{
            $this->db->like('orders.ordParentPath', $params['adminPath'], 'after'); 
        }
        if($params['userId']!=0){
            $this->db->where('meetings.mtnUserId',$params['userId']);
        }
        if($params['customerTypeId']!=0){
            $this->db->where('customers.cusCustomerTypeId',$params['customerTypeId']);
        }
        if($params['industryTypeId']!=0){
            $this->db->where('customers.cusIndustryTypeId',$params['industryTypeId']);
        }
        if($params['companyId']!=0){
            $this->db->where('customers.cusCompanyName',$params['companyName']);
        }
        if($params['orderStatusId']!=0){
            $this->db->where('orders.ordStatusId',$params['orderStatusId']);
        }
        $this->db->where('orders.ordForDate >=',$params['fromDate']);
        $this->db->where('orders.ordForDate <=',$params['toDate']);
        $query=$this->db->get();
        return $query->result_array();
    }

    

}