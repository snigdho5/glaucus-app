<?php
 
class Setting_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    public function check_current_password($params)
    {
	  	$this->db->where('usrId',$params['usrId']);
	  	$this->db->where('usrPassword',$params['usrPassword']);
        $this->db->where('usrTypeName','admin');
	  	$query=$this->db->get('users');
	 	$c=$query->num_rows(); 
	  	if($c>0){
	  		return true;
	  	}
	  	else{
			return false; 
		}
    }


    function update_password($id, $params)
    {
        $this->db->where('usrId',$id);
        $this->db->update('users',$params);
    }
  

}