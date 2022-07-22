<?php
 
class Customer_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function add_customer($params)
    {
        if($this->db->insert('customers', $params)) {
            $insert_id = $this->db->insert_id();
            $data['cusCode'] = 'LN000' . $insert_id;
            $this->db->where('cusId', $insert_id);
            $this->db->update('customers', $data);
            return true;
        } else {
            return false;
        }
    }


    public function get_customer_duplicate($params)
    {
        $this->db->where('cusMobileNo',$params['cusMobileNo']);
        $query=$this->db->get('customers');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }


    public function get_customer_code_duplicate($params)
    {
        $this->db->where('cusCode',$params['cusCode']);
        $query=$this->db->get('customers');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }


    public function get_update_customer_duplicate($id, $params)
    {
        $this->db->where('cusMobileNo',$params['cusMobileNo']);
        $this->db->where_not_in('cusId', $id);
        $query=$this->db->get('customers');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }


    public function get_update_customer_code_duplicate($id, $params)
    {
        $this->db->where('cusCode',$params['cusCode']);
        $this->db->where_not_in('cusId', $id);
        $query=$this->db->get('customers');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function get_customer_data($params)
    {
        return $this->db->get_where('customers',$params)->row_array();
    }

    function get_all_customers($parent_path)
    {

        $this->db->select('cust.*, parent.usrUserName parentName, manage.usrUserName manageName');
        $this->db->from('customers as cust');
        $this->db->like('cust.cusParentPath', $parent_path, 'after');
        $this->db->join('users as manage', 'manage.usrId = cust.cusManageId');
        $this->db->join('users as parent', 'parent.usrId = cust.cusParentId');
        $this->db->order_by('cust.cusId DESC');
        $query=$this->db->get();
        return $query->result_array();
    }


    function update_customer($id, $params)
    {
        $this->db->where('cusId',$id);
        $this->db->update('customers',$params);
    }


    public function get_all_admin_users($parent_path)
    {
        $this->db->select('child.*, parent.usrUserName parentName');
        $this->db->from('users as child');
        $this->db->like('child.usrParentPath', $parent_path, 'after');
        $this->db->join('users as parent', 'parent.usrId = child.usrParentId');
        $query=$this->db->get();
        return $query->result_array();
    }
    

}