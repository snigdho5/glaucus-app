<?php

 
class Country_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_countries()
    {
        return $this->db->get('countries')->result_array();
    }

    public function get_country_data($params)
    {
        return $this->db->get_where('countries',$params)->row_array();
    }

    

}