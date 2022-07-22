<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venuemanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->model('Unit_model');		
        $this->load->model('Venue_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('administration/venue/venue');

		}else{
			redirect('/login');
		}
		
	}

	public function add()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('administration/venue/addvenue');

		}else{
			redirect('/login');
		}
	}


	public function edit(){
		if (isset($this->session->userdata['logged_web_user'])) {
			$this->load->view('include/dashboard_header');
			$this->load->view('administration/venue/editvenue');
		}else{
			redirect('/login');
		}
	}

	public function add_venue()
	{

		if($this->input->post('venUnitId',TRUE)){

			$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

			$params = array(
				'venUnitId' => $this->input->post('venUnitId'),
				'venShortName' => $this->input->post('venShortName'),
				'venFullName' => $this->input->post('venFullName'),
				'venDescription' => $this->input->post('venDescription'),
				'venParentId' => $this->session->userdata['logged_web_user']['userId'],
				'venParentPath' => $parent_path,
				'venStatusId' => 1,
				'venStatus' => 'active'
			);

			$venue_duplicate = $this->Venue_model->get_venue_duplicate($params);

			if($venue_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Venue Short Name already exist"
				}';

			}else{

				$add_venue = $this->Venue_model->add_venue($params);

				if($add_venue == true){

					echo '{
						"status":"success",
						"message":"Venue Successfully Added"
					}';

				}else{

					echo '{
						"status":"failed",
						"message":"Add Venue Failed"
					}';

				}

			}

		}

	}


	public function update_venue()
	{
		if($this->input->post('venId',TRUE)){

			$params = array(
				'venUnitId' => $this->input->post('venUnitId'),
				'venShortName' => $this->input->post('venShortName'),
				'venFullName' => $this->input->post('venFullName'),
				'venDescription' => $this->input->post('venDescription')
			);

			$update_venue_duplicate = $this->Venue_model->get_update_venue_duplicate($this->input->post('venId'), $params);

			if($update_venue_duplicate==true){

				echo '{
					"status":"failed",
					"message":"Name already exist"
				}';

			}else{

				$venue_id = $this->Venue_model->update_venue($this->input->post('venId'), $params);
				echo '{
					"status":"success",
					"message":"Data Updated Successfully"
				}';

			}


		}

	}



	public function get_all_venues()
	{
		$account_type = $this->session->userdata['logged_web_user']['userTypeName'];

		if($account_type == "superadmin"){
			$venuelist= $this->Venue_model->get_all_venues();
		}else{
			$uservenuelist= $this->Venue_model->get_all_user_units($this->session->userdata['logged_web_user']['userId']);
			$venuelist= $this->Venue_model->get_all_user_venues($this->session->userdata['logged_web_user']['userId'], $uservenuelist);
		}

		

		header('Content-Type: application/json');

		$c=count($venuelist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'venId' => $venuelist[$i]['venId'],
				'venUnitId' => $venuelist[$i]['venUnitId'],
				'venUnitName' => $venuelist[$i]['untName'],
				'venCode' => $venuelist[$i]['venCode'],
				'venShortName' => $venuelist[$i]['venShortName'],
				'venFullName' => $venuelist[$i]['venFullName'],
				'venDescription' => $venuelist[$i]['venDescription'],
				'venStatusId' => $venuelist[$i]['venStatusId'],
				'venStatus' => $venuelist[$i]['venStatus']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}


		}

		echo ']';

	}


	public function get_venue_detail(){

		header('Content-Type: application/json');

		$params = array(
				'venId' => $this->input->get('id')
			);

		$venuedata = $this->Venue_model->get_venue_data($params);

		$data = array(
				'venId' => $venuedata['venId'],
				'venUnitId' => $venuedata['venUnitId'],
				'venCode' => $venuedata['venCode'],
				'venShortName' => $venuedata['venShortName'],
				'venFullName' => $venuedata['venFullName'],
				'venDescription' => $venuedata['venDescription'],
				'venStatusId' => $venuedata['venStatusId'],
				'venStatus' => $venuedata['venStatus']
			);

			$json = json_encode($data);

			echo $json;

	}


	public function change_status()
	{
		if($this->input->post('venId',TRUE)){

			$params = array(
				'venStatusId' => $this->input->post('venStatusId'),
				'venStatus' => $this->input->post('venStatus')
			);

			$venue_status_id = $this->Venue_model->update_status($this->input->post('venId'), $params);

			if($venue_status_id == true){

				echo '{
					"status":"success",
					"message":"Status Changed Successfully"
				}';

			}else{

				echo '{
					"status":"failed",
					"message":"Status Change Failed"
				}';

			}

		}

	}



}