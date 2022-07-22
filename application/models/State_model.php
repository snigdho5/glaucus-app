<?php

 
class State_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_states_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('states');
        $this->db->where('country_id',$id);
        $query=$this->db->get();
        return $query->result_array();
    }

    function get_all_states()
    {
        return $this->db->get('states')->result_array();
    }

    public function get_state_data($params)
    {
        return $this->db->get_where('states',$params)->row_array();
    }

    

}