<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Routehistory extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Route_history_model');
        $this->load->model('Map_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if(isset($this->session->userdata['logged_web_user'])){
			$this->load->view('include/dashboard_header');
			$this->load->view('reports/route_history');
		}
		else{
			redirect('/login');
		}		
	}

	public function direction()
	{
		if(isset($this->session->userdata['logged_web_user'])){
			$this->load->view('include/dashboard_header');
			$this->load->view('reports/route_history_road');
		}
		else{
			redirect('/login');
		}		
	}



	public function get_user_visited()
	{
		header('Content-Type: application/json');
		$params = array(
			'usrId' => $this->input->get('userId'),
			'currentDate' => $this->input->get('date')
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
			'currentDate' => $this->input->get('date')
		);
		$loginrecordlist= $this->Map_model->get_login_records_by_user_id($params);

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
		$params = 	[
						'crlLoginRecordId'	=>	$this->input->get('userLoginRecordId'),
						// 'currentDate' => $this->input->get('date')
						'crlDate'			=>	$this->input->get('userLoginDate')
					];
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

	public function get_user_login_record_locations_dir_api(){

		header('Content-Type: application/json');
		$params = 	[
						//'crlLoginRecordId' => $this->input->get('userLoginRecordId'),
						'crlUserId'	=> 	$this->input->get('userId'),
						'crlDate'	=>	$this->input->get('date')
					];
		
		$loginlocationlist =	$this->Map_model->get_login_record_locations_direction($params);

		//$c =	count($loginlocationlist);
		//echo $c; die();
		/*if(count($loginlocationlist)){
			$data[0]['lat']		=	(float)reset($loginlocationlist)['crlLat'];
			$data[0]['lng']		=	(float)reset($loginlocationlist)['crlLong'];
			$data[1]['lat']		=	(float)end($loginlocationlist)['crlLat'];
			$data[1]['lng']		=	(float)end($loginlocationlist)['crlLong'];
			echo json_encode($data);
		}
		else{
			return false;
		}*/

		if(count($loginlocationlist)){
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
		else{
			$data 	=	['status' => 'failure', 'message' => 'Route record not available for this date'];
			echo json_encode($data);
		}		
	}


	public function get_user_trip_records()
	{
		header('Content-Type: application/json');
		$params = array(
			'trpLoginRecordId' => $this->input->get('userLoginRecordId'),
			'currentDate' => $this->input->get('date')
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
			'currentDate' => $this->input->get('date')
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






}