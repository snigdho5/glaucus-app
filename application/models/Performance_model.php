<?php

 
class Performance_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_all_years()
    {
        $this->db->select('*');
        $this->db->from('years');
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_all_users($parent_path)
    {

        $this->db->select('child.*, parent.usrUserName parentName');
        $this->db->from('users as child');
        $this->db->like('child.usrParentPath', $parent_path, 'after');
        $this->db->join('users as parent', 'parent.usrId = child.usrParentId');
        $this->db->where('child.usrTypeName','appuser');
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_all_user_orders_by_year($year, $parent_id)
    {
        /*$this->db->select('*');
        $this->db->from('orders');
        $this->db->where('YEAR(created_at)',$year);
        $this->db->where('ordParentId',$parent_id);*/
        
        $this->db->select('*');
        $this->db->from('order_master');
        $this->db->where('YEAR(createdat)',$year);
        $this->db->where('salesUserId',$parent_id);
        
        $query  =   $this->db->get();
        return $query->result_array();
    }


    public function get_all_user_visits_by_year($year, $parent_id)
    {
        $this->db->select('*');
        $this->db->from('meetings');
        $this->db->where('YEAR(created_at)',$year);
        $this->db->where('mtnUserId',$parent_id);
        $this->db->where('mtnVisited','yes');
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_all_user_order_amount_by_year($year, $parent_id)
    {
        /*$this->db->select('*');
        $this->db->from('orders');
        $this->db->where('YEAR(created_at)',$year);
        $this->db->where('ordParentId',$parent_id);*/
        
        $this->db->select('*');
        $this->db->from('order_master');
        $this->db->where('YEAR(createdat)',$year);
        $this->db->where('salesUserId',$parent_id);
        
        $query=$this->db->get();
        return $query->result_array();
    }


    public function get_all_user_projections_by_year($year, $parent_id)
    {
        $this->db->select('*');
        $this->db->from('payment_projection_master');
        $this->db->where('YEAR(created_at)',$year);
        $this->db->where('salesUserId',$parent_id);
        $query  =   $this->db->get();
        /*echo $this->db->last_query();
        die();*/
        return $query->result_array();
    }
    
    public function get_all_user_collections_by_year($year, $parent_id)
    {
        $this->db->select('*');
        $this->db->from('collection_master');
        $this->db->where('YEAR(created_at)',$year);
        $this->db->where('salesUserId',$parent_id);
        $query  =   $this->db->get();
        /*echo $this->db->last_query();
        die();*/
        return $query->result_array();
    }

}