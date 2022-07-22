<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminmanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('App_user_model');
        $this->load->model('Web_user_model');
        $this->load->model('Unit_model');		
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('administration/admin/admin');

		}else{
			redirect('/login');
		}
		
	}

	public function add()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('administration/admin/addadmin');

		}else{
			redirect('/login');
		}
	}


	public function edit(){
		if (isset($this->session->userdata['logged_web_user'])) {
			$this->load->view('include/dashboard_header');
			$this->load->view('administration/admin/editadmin');
		}else{
			redirect('/login');
		}
	}


	public function add_admin()
	{

		if($this->input->post('userName',TRUE)){
			$user_units = [];
			$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
			$params = array(
				'usrTypeId' => 2,
				'usrTypeName' => 'admin',
				'usrFirstName' => $this->input->post('userFirstName'),
				'usrLastName' => $this->input->post('userLastName'),
				'usrUserEmail' => $this->input->post('userEmail'),
				'usrUserName' => $this->input->post('userName'),
				'usrPassword' => $this->input->post('userPassword'),
				'usrUnitAddPermission' => 'false',
				'usrUnitEditPermission' => 'false',
				'usrVenueAddPermission' => $this->input->post('userVenueAddPermission'),
				'usrVenueEditPermission' => $this->input->post('userVenueEditPermission'),
				'usrParentId' => $this->session->userdata['logged_web_user']['userId'],
				'usrParentPath' => $parent_path,
				'usrStatusName' => 'active',
				'usrStatus' => 1
			);

			$user_duplicate = $this->Web_user_model->get_user_duplicate($params);

			if($user_duplicate == true){

				$data = array(
					'status' => 'failed',
					'message' => 'Username already exist'
				);
				$json = json_encode($data);
				echo $json;

			}else{

				$user_id = $this->Web_user_model->add_user($params);

				if($user_id == 0){

					$data = array(
						'status' => 'failed',
						'message' => 'Signup Failed'
					);
					$json = json_encode($data);
					echo $json;

				}else{

					$user_units = $this->input->post('userUnits');

					if(count($user_units)>0){
						for($i=0;$i<count($user_units);$i++){

							$unit_params = array(
								'usruUserId' => $user_id,
								'usruUnitId' => $user_units[$i]['id'],
								'usruStatusId' => 1,
								'usruStatus' => 'active'
							);

							$user_units_id = $this->Web_user_model->add_user_units($unit_params);

						}
					}

					$data = array(
						'status' => 'success',
						'message' => 'Successfully Admin Created'
					);
					$json = json_encode($data);
					echo $json;

				}

			}

		}

	}


	public function update_admin()
	{
		if($this->input->post('userName',TRUE)){
			$params = array(
				'usrFirstName' => $this->input->post('userFirstName'),
				'usrLastName' => $this->input->post('userLastName'),
				'usrUserEmail' => $this->input->post('userEmail'),
				'usrUserName' => $this->input->post('userName'),
				'usrPassword' => $this->input->post('userPassword'),
				'usrVenueAddPermission' => $this->input->post('userVenueAddPermission'),
				'usrVenueEditPermission' => $this->input->post('userVenueEditPermission')
			);

			$update_user_duplicate = $this->Web_user_model->get_update_user_duplicate($this->input->post('userId'), $params);

			if($update_user_duplicate == true){

				$data = array(
					'status' => 'failed',
					'message' => 'Username already exist'
				);
				$json = json_encode($data);
				echo $json;

			}else{

				$user_units = [];
				$user_units = $this->input->post('userUnits');


				$update_id = $this->Web_user_model->update_user($this->input->post('userId'), $params);

				$userunitlist= $this->Web_user_model->get_all_user_units($this->input->post('userId'));
				$c=count($userunitlist);
				//echo $c;

				for ($i=0; $i<$c; $i++) {
					$user_unit_id = $userunitlist[$i]['usruUnitId'];
					$user_unit_check_status =  array_search($userunitlist[$i]['usruUnitId'], array_column($user_units, 'id'));
					//$user_unit_check_status = searchForId($userunitlist[$i]['usruUnitId'], $user_units);
					if($user_unit_check_status==false){

						$delete_params = array(
							'usruUserId' => $userunitlist[$i]['usruUserId'],
							'usruUnitId' => $userunitlist[$i]['usruUnitId']
						);

						$delete_user_unit = $this->Web_user_model->delete_user_unit($delete_params);

					}
				}

				if(count($user_units)>0){
					for($i=0;$i<count($user_units);$i++){

						$unit_params = array(
							'usruUserId' => $this->input->post('userId'),
							'usruUnitId' => $user_units[$i]['id'],
							'usruStatusId' => 1,
							'usruStatus' => 'active'
						);

						$user_unit_status = $this->Web_user_model->check_user_unit_exists($unit_params);

						if($user_unit_status == false){
							$user_units_id = $this->Web_user_model->add_user_units($unit_params);
						}

						

					}
				}

				$data = array(
					'status' => 'success',
					'message' => 'Data Updated Successfully'
				);
				$json = json_encode($data);
				echo $json;

			}


		}

	}

	function searchForId($id, $array) {
	   foreach ($array as $key => $val) {
	       if ($val['id'] === $id) {
	           return true;
	       }
	   }
	   return false;
	}


	public function get_all_web_users()
	{
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$appuserlist= $this->Web_user_model->get_all_web_users($parent_path);

		header('Content-Type: application/json');

		$c=count($appuserlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$userunitlist= $this->Web_user_model->get_user_all_unit_details($appuserlist[$i]['usrId']);
			$uc=count($userunitlist);

			$user_unit_names = '';

			for($j=0; $j<$uc; $j++) {

				if ($user_unit_names == '') {
					$user_unit_names = $userunitlist[$j]['untName'];
				}else{
					$user_unit_names = $user_unit_names.', '.$userunitlist[$j]['untName'];
				}
				

			}

			$data = array(
				'wusrId' => $appuserlist[$i]['usrId'],
				'wusrFirstName' => $appuserlist[$i]['usrFirstName'],
				'wusrLastName' => $appuserlist[$i]['usrLastName'],
				'wusrUserName' => $appuserlist[$i]['usrUserName'],
				'wusrUserEmail' => $appuserlist[$i]['usrUserEmail'],
				'wusrPassword' => $appuserlist[$i]['usrPassword'],
				'wusrUnitNames' => $user_unit_names,
				'wusrParentId' => $appuserlist[$i]['usrParentId'],
				'wusrParentName' => $appuserlist[$i]['parentName'],
				'wusrStatusName' => $appuserlist[$i]['usrStatusName'],
				'wusrStatus' => $appuserlist[$i]['usrStatus']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_admin_detail(){

		header('Content-Type: application/json');

		$params = array(
				'usrId' => $this->input->get('id')
			);

		$userdata = $this->Web_user_model->get_user_data($params);

		$data = array(
				'wusrId' => $userdata['usrId'],
				'wusrFirstName' => $userdata['usrFirstName'],
				'wusrLastName' => $userdata['usrLastName'],
				'wusrUserEmail' => $userdata['usrUserEmail'],
				'wusrUserName' => $userdata['usrUserName'],
				'wusrPassword' => $userdata['usrPassword'],
				'wusrVenueAddPermission' => $userdata['usrVenueAddPermission'],
				'wusrVenueEditPermission' => $userdata['usrVenueEditPermission'],
				'wusrParentId' => $userdata['usrParentId'],
				'wusrStatus' => $userdata['usrStatus']
			);

			$json = json_encode($data);

			echo $json;

	}


	public function change_status()
	{
		if($this->input->post('wusrId',TRUE)){

			$params = array(
				'usrStatus' => $this->input->post('wusrStatus'),
				'usrStatusName' => $this->input->post('wusrStatusName')
			);

			$user_status_id = $this->Web_user_model->update_status($this->input->post('wusrId'), $params);

			if($user_status_id == true){

				$data = array(
					'status' => 'success',
					'message' => 'Status Changed Successfully'
				);
				$json = json_encode($data);
				echo $json;

			}else{

				$data = array(
					'status' => 'failed',
					'message' => 'Language Status Change Failed'
				);
				$json = json_encode($data);
				echo $json;

			}

		}

	}


	public function get_all_active_units()
	{
		$account_type = $this->session->userdata['logged_web_user']['userTypeName'];
		if($account_type == "superadmin"){
			$unitlist= $this->Web_user_model->get_all_active_units();
		}else{
			$unitlist= $this->Unit_model->get_all_user_units($this->session->userdata['logged_web_user']['userId']);
		}
		

		header('Content-Type: application/json');

		$c=count($unitlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'icon' => '<img src='.base_url().'assets/dist/img/buildingicon.png'.' />',
				'name' => $unitlist[$i]['untName'],
				'maker' => ' ('.$unitlist[$i]['untCode'].')',
				'id' => $unitlist[$i]['untId'],
				'ticked' => false
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_active_edit_units()
	{
		$account_type = $this->session->userdata['logged_web_user']['userTypeName'];
		if($account_type == "superadmin"){
			$unitlist= $this->Web_user_model->get_all_active_units();
		}else{
			$unitlist= $this->Unit_model->get_all_user_units($this->session->userdata['logged_web_user']['userId']);
		}


		header('Content-Type: application/json');

		$c=count($unitlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$check_params = array(
				'usruUserId' => $this->input->get('id'),
				'usruUnitId' => $unitlist[$i]['untId']
			);

			$tiked_status = $this->Web_user_model->check_user_unit_exists($check_params);

			$data = array(
				'icon' => '<img src='.base_url().'assets/dist/img/buildingicon.png'.' />',
				'name' => $unitlist[$i]['untName'],
				'maker' => ' ('.$unitlist[$i]['untCode'].')',
				'id' => $unitlist[$i]['untId'],
				'ticked' => $tiked_status
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



}