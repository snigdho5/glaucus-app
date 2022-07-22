<?php

 
class Customer_type_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
/*
    function get_all_industry_types()
    {
        return $this->db->get('industry_types')->result_array();
    }*/

    public function get_all_industry_types()
    {
        $this->db->select('*');
        $this->db->from('industry_types');
        //$this->db->order_by('indtName ASC');
        $query=$this->db->get();
        return $query->result_array();
    }

/*
    public function get_all_customer_types()
    {
        return $this->db->get('customer_types')->result_array();
    }*/

    public function get_all_customer_types()
    {
        $this->db->select('*');
        $this->db->from('customer_types');
        //$this->db->order_by('custName ASC');
        $query=$this->db->get();
        return $query->result_array();
    }

        function get_all_genders()
    {
        return $this->db->get('genders')->result_array();
    }


    public function get_gender_data($params)
    {
        return $this->db->get_where('genders',$params)->row_array();
    }

    public function get_customer_type_data($params)
    {
        return $this->db->get_where('customer_types',$params)->row_array();
    }

    public function get_industry_type_data($params)
    {
        return $this->db->get_where('industry_types',$params)->row_array();
    }
    

}