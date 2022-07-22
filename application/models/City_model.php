<?php

 
class City_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_cities_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('cities');
        $this->db->where('state_id',$id);
        $query=$this->db->get();
        return $query->result_array();
    }

        function get_all_cities()
    {
        return $this->db->get('cities')->result_array();
    }


    public function get_city_data($params)
    {
        return $this->db->get_where('cities',$params)->row_array();
    }

    

}