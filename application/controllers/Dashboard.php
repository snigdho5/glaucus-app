<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Map_model');
        $this->load->model('App_user_model');
        $this->load->helper('url');
        // $this->load->library('google_map_lib');
        define('GOOGLE_MAP_API_KEY', "AIzaSyCa2Y9fI4Fn7tO6PPJreoMwgNPCdU6v3Lk");
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('dashboard/dashboard');

		}else{
			redirect('/login');
		}
		
	}

	public function route(){

		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('dashboard/route');

		}else{
			redirect('/login');
		}

	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}


	public function get_user_visited()
	{
		header('Content-Type: application/json');
		$params = array(
			'usrId' => $this->input->get('userId'),
			'currentDate' => date("Y-m-d")
		);
		$visitedlist= $this->Map_model->get_user_visited_by_user_id($params);

		$c=count($visitedlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'mtnVisited' => $visitedlist[$i]['mtnVisited'],
				'mtnVisitedDate' => $visitedlist[$i]['mtnVisitedDate'],
				'mtnVisitedTime' => $visitedlist[$i]['mtnVisitedTime'],
				'mtnVisitedLat' => $visitedlist[$i]['mtnVisitedLat'],
				'mtnVisitedLong' => $visitedlist[$i]['mtnVisitedLong'],
				'mtnVisitedCustomerName' => $visitedlist[$i]['cusFirstName'].' '.$visitedlist[$i]['cusLastName']
			);


			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_user_login_records()
	{
		header('Content-Type: application/json');
		$params = array(
			'usrId' => $this->input->get('userId'),
			'currentDate' => date("Y-m-d")
		);
		//print_r($params); die();
		$loginrecordlist= $this->Map_model->get_login_records_by_user_id($params);

		// print_r($loginrecordlist); die();

		$c=count($loginrecordlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'lgnrId' => $loginrecordlist[$i]['lgnrId'],
				'lgnrUserId' => $loginrecordlist[$i]['lgnrUserId'],
				'lgnrLoginDate' => $loginrecordlist[$i]['lgnrLoginDate'],
				'lgnrLoginTime' => $loginrecordlist[$i]['lgnrLoginTime'],
				'lgnrLoginLat' => $loginrecordlist[$i]['lgnrLoginLat'],
				'lgnrLoginLong' => $loginrecordlist[$i]['lgnrLoginLong'],
				'lgnrLogoutDate' => $loginrecordlist[$i]['lgnrLogoutDate'],
				'lgnrLogoutTime' => $loginrecordlist[$i]['lgnrLogoutTime'],
				'lgnrLogoutLat' => $loginrecordlist[$i]['lgnrLogoutLat'],
				'lgnrLogoutLong' => $loginrecordlist[$i]['lgnrLogoutLong'],
			);


			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_user_login_record_locations()
	{
		header('Content-Type: application/json');
		$params = array(
			'crlLoginRecordId' => $this->input->get('userLoginRecordId'),
			'currentDate' => date("Y-m-d")
		);
		$loginlocationlist= $this->Map_model->get_login_record_locations($params);

		$c=count($loginlocationlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'lat' => (float)$loginlocationlist[$i]['crlLat'],
				'lng' => (float)$loginlocationlist[$i]['crlLong']
			);


			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_user_trip_records()
	{
		header('Content-Type: application/json');
		$params = array(
			'trpLoginRecordId' => $this->input->get('userLoginRecordId'),
			'currentDate' => date("Y-m-d")
		);
		$triprecordlist= $this->Map_model->get_trip_records($params);

		$c=count($triprecordlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'trpId' => $triprecordlist[$i]['trpId'],
				'trpUserId' => $triprecordlist[$i]['trpUserId'],
				'trpLoginRecordId' => $triprecordlist[$i]['trpLoginRecordId'],
				'trpStartDate' => $triprecordlist[$i]['trpStartDate'],
				'trpStartTime' => $triprecordlist[$i]['trpStartTime'],
				'trpStartLat' => $triprecordlist[$i]['trpStartLat'],
				'trpStartLong' => $triprecordlist[$i]['trpStartLong'],
				'trpEndDate' => $triprecordlist[$i]['trpEndDate'],
				'trpEndTime' => $triprecordlist[$i]['trpEndTime'],
				'trpEndLat' => $triprecordlist[$i]['trpEndLat'],
				'trpEndLong' => $triprecordlist[$i]['trpEndLong'],
			);


			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_user_trip_record_locations()
	{
		header('Content-Type: application/json');
		$params = array(
			'trplTripRecordId' => $this->input->get('userTripRecordId'),
			'currentDate' => date("Y-m-d")
		);
		$triplocationlist= $this->Map_model->get_trip_record_locations($params);

		$c=count($triplocationlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'lat' => (float)$triplocationlist[$i]['trplLat'],
				'lng' => (float)$triplocationlist[$i]['trplLong']
			);


			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_active_app_users()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$appuserlist= $this->Map_model->get_all_active_app_users($parent_path);

		$c=count($appuserlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$params = array(
				'usrId' => $appuserlist[$i]['usrId']
			);

			if($appuserlist[$i]['usrLastMeetingId'] == "0"){

				$data = array(
					'usrId' => $appuserlist[$i]['usrId'],
					'usrFirstName' => $appuserlist[$i]['usrFirstName'],
					'usrLastName' => $appuserlist[$i]['usrLastName'],
					'usrContactNo' => $appuserlist[$i]['usrContactNo'],
					'usrUserEmail' => $appuserlist[$i]['usrUserEmail'],
					'usrUserName' => $appuserlist[$i]['usrUserName'],
					'usrDesignation' => $appuserlist[$i]['usrDesignation'],
					'usrParentId' => $appuserlist[$i]['usrParentId'],
					'usrParentName' => $appuserlist[$i]['parentName'],
					'usrCurrentLat' => $appuserlist[$i]['usrCurrentLat'],
					'usrCurrentLong' => $appuserlist[$i]['usrCurrentLong'],
					'usrLastMeetingId' => $appuserlist[$i]['usrLastMeetingId']
				);

			}else{

				$userdata = $this->Map_model->get_user_data($params);

				$data = array(
					'usrId' => $appuserlist[$i]['usrId'],
					'usrFirstName' => $appuserlist[$i]['usrFirstName'],
					'usrLastName' => $appuserlist[$i]['usrLastName'],
					'usrContactNo' => $appuserlist[$i]['usrContactNo'],
					'usrUserEmail' => $appuserlist[$i]['usrUserEmail'],
					'usrUserName' => $appuserlist[$i]['usrUserName'],
					'usrDesignation' => $appuserlist[$i]['usrDesignation'],
					'usrParentId' => $appuserlist[$i]['usrParentId'],
					'usrParentName' => $appuserlist[$i]['parentName'],
					'usrCurrentLat' => $appuserlist[$i]['usrCurrentLat'],
					'usrCurrentLong' => $appuserlist[$i]['usrCurrentLong'],
					'usrLastMeetingId' => $appuserlist[$i]['usrLastMeetingId'],
					'usrLastMeetingName' => $userdata['mtnName'],
					'usrLastMeetingDate' => $userdata['mtnDate'],
					'usrLastMeetingTime' => $userdata['mtnTime'],
					'usrLastMeetingVisitedDate' => $userdata['mtnVisitedDate'],
					'usrLastMeetingVisitedTime' => $userdata['mtnVisitedTime'],
					'usrLastMeetingCustomerName' => $userdata['cusFirstName'].' '.$userdata['cusLastName'],
				);

			}

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function delete_old_login_location_record(){

		$check_old_login_location = $this->App_user_model->check_old_login_location_record();

		if($check_old_login_location == true){
			echo 'record_available';
			$delete_old_login_location = $this->App_user_model->delete_old_login_location_record();
		}else{
			echo 'record_not_available';
		}

	}


	public function delete_old_trip_location_record(){

		$check_old_trip_location = $this->App_user_model->check_old_trip_location_record();

		if($check_old_trip_location == true){
			echo 'record_available';
			$delete_old_trip_location = $this->App_user_model->delete_old_trip_location_record();
		}else{
			echo 'record_not_available';
		}

	}




}