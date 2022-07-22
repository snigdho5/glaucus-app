<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tripreport extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Tripreport_model');	
        $this->load->model('App_user_model');
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('reports/trip_report');

		}else{
			redirect('/login');
		}
		
	}


	public function get_login_records_by_date()
	{
		header('Content-Type: application/json');
		$from_date = $this->input->get('fromDate');
		$to_date = $this->input->get('toDate');

		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

		$lgnrlist= $this->Tripreport_model->get_login_records_by_date($from_date, $to_date, $parent_path);
		

		$c=count($lgnrlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			if($lgnrlist[$i]['lgnrLogoutDate'] == "" || $lgnrlist[$i]['lgnrLogoutDate'] == null){
				$total_time = 'NA';

			}else{
				$date1 = new DateTime($lgnrlist[$i]['lgnrLoginDate'].'T'.$lgnrlist[$i]['lgnrLoginTime'].':00');
				$date2 = new DateTime($lgnrlist[$i]['lgnrLogoutDate'].'T'.$lgnrlist[$i]['lgnrLogoutTime'].':00');

				$diff = $date2->diff($date1);

				$hours = $diff->h;
				$hours = $hours + ($diff->days*24);
				$total_time = $hours. ' hours';
			}

			$data = array(
				'lgnrSNo' => $i+1,
				'lgnrUserName' => $lgnrlist[$i]['usrUserName'],
				'lgnrLoginDate' => $lgnrlist[$i]['lgnrLoginDate'],
				'lgnrLoginTime' => $lgnrlist[$i]['lgnrLoginTime'],
				'lgnrLogoutDate' => $lgnrlist[$i]['lgnrLogoutDate'],
				'lgnrLogoutTime' => $lgnrlist[$i]['lgnrLogoutTime'],
				'lgnrTotalTime' => $total_time,
				'lgnrLoginStatus' => $lgnrlist[$i]['lgnrLoginStatus']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



	public function get_trip_records_by_date()
	{
		header('Content-Type: application/json');
		$from_date = $this->input->get('fromDate');
		$to_date = $this->input->get('toDate');
		$user_id = $this->input->get('userId');

		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

		$trprlist= $this->Tripreport_model->get_trip_records_by_date($from_date, $to_date, $parent_path, $user_id);
		

		$c=count($trprlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			if($trprlist[$i]['trpEndDate'] == "" || $trprlist[$i]['trpEndDate'] == null){
				$total_time = 'NA';
				$distance = 'NA';

			}else{
				$date1 = new DateTime($trprlist[$i]['trpStartDate'].'T'.$trprlist[$i]['trpStartTime'].':00');
				$date2 = new DateTime($trprlist[$i]['trpEndDate'].'T'.$trprlist[$i]['trpEndTime'].':00');

				$diff = $date2->diff($date1);

				$hours = $diff->h;
				$minutes = $diff->format('%i');
				$hours = $hours + ($diff->days*24);
				$total_time = $hours. ' hours and '.$minutes.' minutes';


				$lat1 = $trprlist[$i]['trpStartLat'];
				$lon1 = $trprlist[$i]['trpStartLong'];

				$lat2 = $trprlist[$i]['trpEndLat'];
				$lon2 = $trprlist[$i]['trpEndLong'];

				$theta = $lon1 - $lon2;
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
				$dist = acos($dist);
				$dist = rad2deg($dist);
				$miles = $dist * 60 * 1.1515;

				$distance = $miles * 1.609344;
				$distance = $distance.' KM';
			}

			$data = array(
				'trpSNo' => $i+1,
				'trpUserName' => $trprlist[$i]['usrUserName'],
				'trpStartDate' => $trprlist[$i]['trpStartDate'],
				'trpStartTime' => $trprlist[$i]['trpStartTime'],
				'trpEndDate' => $trprlist[$i]['trpEndDate'],
				'trpEndTime' => $trprlist[$i]['trpEndTime'],
				'trpTotalTime' => $total_time,
				'trpDistance' => $distance
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_users()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$appuserlist= $this->App_user_model->get_all_app_users($parent_path);

		$data = array();

		$c=count($appuserlist);
		echo '[';


		for($i=0;$i<$c;$i++){

			$data = array(
				'usrId' => $appuserlist[$i]['usrId'],
				'usrUserName' => $appuserlist[$i]['usrUserName'],
				'usrParentName' => $appuserlist[$i]['parentName']
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