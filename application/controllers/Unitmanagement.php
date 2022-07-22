<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unitmanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Unit_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('administration/unit/unit');

		}else{
			redirect('/login');
		}
		
	}

	public function add()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('administration/unit/addunit');

		}else{
			redirect('/login');
		}
	}


	public function edit(){
		if (isset($this->session->userdata['logged_web_user'])) {
			$this->load->view('include/dashboard_header');
			$this->load->view('administration/unit/editunit');
		}else{
			redirect('/login');
		}
	}

	public function add_unit()
	{

		if($this->input->post('untName',TRUE)){

			$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

			$params = array(
				'untName' => $this->input->post('untName'),
				'untDescription' => $this->input->post('untDescription'),
				'untParentId' => $this->session->userdata['logged_web_user']['userId'],
				'untParentPath' => $parent_path,
				'untStatusId' => 1,
				'untStatus' => 'active'
			);

			$unit_duplicate = $this->Unit_model->get_unit_duplicate($params);

			if($unit_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Unit Name already exist"
				}';

			}else{

				$add_unit = $this->Unit_model->add_unit($params);

				if($add_unit == true){

					echo '{
						"status":"success",
						"message":"Unit Successfully Added"
					}';

				}else{

					echo '{
						"status":"failed",
						"message":"Add Unit Failed"
					}';

				}

			}

		}

	}


	public function update_unit()
	{
		if($this->input->post('untId',TRUE)){

			$params = array(
				'untName' => $this->input->post('untName'),
				'untDescription' => $this->input->post('untDescription')
			);

			$update_unit_duplicate = $this->Unit_model->get_update_unit_duplicate($this->input->post('untId'), $params);

			if($update_unit_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Name already exist"
				}';

			}else{

				$unit_id = $this->Unit_model->update_unit($this->input->post('untId'), $params);
				echo '{
					"status":"success",
					"message":"Data Updated Successfully"
				}';

			}


		}

	}



	public function get_all_units()
	{
		$account_type = $this->session->userdata['logged_web_user']['userTypeName'];
		if($account_type == "superadmin"){
			$unitlist= $this->Unit_model->get_all_units();
		}else{
			$unitlist= $this->Unit_model->get_all_user_units($this->session->userdata['logged_web_user']['userId']);
		}
		

		header('Content-Type: application/json');

		$c=count($unitlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'untId' => $unitlist[$i]['untId'],
				'untCode' => $unitlist[$i]['untCode'],
				'untName' => $unitlist[$i]['untName'],
				'untDescription' => $unitlist[$i]['untDescription'],
				'untStatusId' => $unitlist[$i]['untStatusId'],
				'untStatus' => $unitlist[$i]['untStatus']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_unit_detail(){

		header('Content-Type: application/json');

		$params = array(
				'untId' => $this->input->get('id')
			);

		$unitdata = $this->Unit_model->get_unit_data($params);

		$data = array(
				'untId' => $unitdata['untId'],
				'untCode' => $unitdata['untCode'],
				'untName' => $unitdata['untName'],
				'untDescription' => $unitdata['untDescription'],
				'untStatusId' => $unitdata['untStatusId'],
				'untStatus' => $unitdata['untStatus']
			);

			$json = json_encode($data);

			echo $json;

	}


	public function change_status()
	{
		if($this->input->post('untId',TRUE)){

			$params = array(
				'untStatusId' => $this->input->post('untStatusId'),
				'untStatus' => $this->input->post('untStatus')
			);

			$unit_status_id = $this->Unit_model->update_status($this->input->post('untId'), $params);

			if($unit_status_id == true){

				echo '{
					"status":"success",
					"message":"Status Changed Successfully"
				}';

			}else{

				echo '{
					"status":"failed",
					"message":"Language Status Change Failed"
				}';

			}

		}

	}



}