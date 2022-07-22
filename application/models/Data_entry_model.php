<?php

 
class Data_entry_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function check_company_duplicate($params)
    {
        $this->db->where('cmpName',$params['cmpName']);
        $query=$this->db->get('companies');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function add_company($params)
    {
        $this->db->insert('companies',$params);
        return $this->db->insert_id();
    }

    public function get_all_companies()
    {
        return $this->db->get('companies')->result_array();
    }



    public function check_department_duplicate($params)
    {
        $this->db->where('deptName',$params['deptName']);
        $query=$this->db->get('departments');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function add_department($params)
    {
        $this->db->insert('departments',$params);
        return $this->db->insert_id();
    }

    public function get_all_departments()
    {
        return $this->db->get('departments')->result_array();
    }




    public function check_designation_duplicate($params)
    {
        $this->db->where('desgName',$params['desgName']);
        $query=$this->db->get('designations');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function add_designation($params)
    {
        $this->db->insert('designations',$params);
        return $this->db->insert_id();
    }

    public function get_all_designations()
    {
        return $this->db->get('designations')->result_array();
    }



    public function check_area_duplicate($params)
    {
        $this->db->where('areaName',$params['areaName']);
        $query=$this->db->get('areas');
        $c=$query->num_rows(); 
        if($c>0){
            return true;
        }
        else{
            return false; 
        }
    }

    public function add_area($params)
    {
        $this->db->insert('areas',$params);
        return $this->db->insert_id();
    }

    public function get_all_areas()
    {
        return $this->db->get('areas')->result_array();
    }



    

}