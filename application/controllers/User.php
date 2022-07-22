<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('User_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_app_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('user/user');

		}else{
			redirect('/login');
		}
		
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}


	public function get_meeting_records_by_date()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_app_user']['userParentPath']."-".$this->session->userdata['logged_app_user']['userId'];

			$params = array(
				'dateTypeId' => $this->input->get('dateTypeId'),
				'dateTypeValue' => $this->input->get('dateTypeValue'),
				'dateTypeName' => $this->input->get('dateTypeName'),
				'fromDate' => $this->input->get('fromDate'),
				'toDate' => $this->input->get('toDate'),
				'userId' => $this->session->userdata['logged_app_user']['userId'],
				'customerTypeId' => $this->input->get('customerTypeId'),
				'customerTypeName' => $this->input->get('customerTypeName'),
				'industryTypeId' => $this->input->get('industryTypeId'),
				'industryTypeName' => $this->input->get('industryTypeName'),
				'companyId' => $this->input->get('companyId'),
				'companyName' => $this->input->get('companyName')
			);

			$meetinglist = $this->User_model->get_meeting_reports_by_date($params, $parent_path);

			$c=count($meetinglist);
			echo '[';

			for($i=0;$i<$c;$i++){


				$data = array(
					'mtnSNo' => $i+1,
					'mtnCode' => $meetinglist[$i]['mtnCode'],
					'mtnName' => $meetinglist[$i]['mtnName'],
					'mtnDate' => $meetinglist[$i]['mtnDate'],
					'mtnTime' => $meetinglist[$i]['mtnTime'],
					'mtnMeetingType' => $meetinglist[$i]['mtnMeetingType'],
					'mtnCustomerName' => $meetinglist[$i]['cusFirstName'].' '.$meetinglist[$i]['cusLastName'],
					'mtnUserName' => $meetinglist[$i]['usrUserName'],
					'mtnVisited' => $meetinglist[$i]['mtnVisited'],
					'mtnCompleted' => $meetinglist[$i]['mtnCompleted'],
					'mtnVisitedMessage' => $meetinglist[$i]['mtnVisitedMessage'],
					'mtnRemarks' => $meetinglist[$i]['mtnRemarks'],
					'mtnRemarksMessage' => $meetinglist[$i]['mtnRemarksMessage'],
					'7 day previous' => date('Y-m-d', strtotime('-7 days'))
				);

				$json = json_encode($data);

				echo $json;

	            if($c-1!=$i){
					echo ",";
				}

			}

			echo ']';

	}






	public function get_all_customer_types()
	{
		header('Content-Type: application/json');
		$customertypelist= $this->User_model->get_all_customer_types_report();

		$c=count($customertypelist);

		$customertypes_array = [];

		$data = array(
			'custId' => 0,
			'custName' => 'All'
		);

		array_push($customertypes_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'custId' => $customertypelist[$i]['custId'],
				'custName' => $customertypelist[$i]['custName']
			);

			array_push($customertypes_array, $data);

		}

		$json = json_encode($customertypes_array);
		echo $json;

	}


	public function get_all_industry_types()
	{
		header('Content-Type: application/json');
		$industrytypelist= $this->User_model->get_all_industry_types_report();

		$c=count($industrytypelist);

		$industrytypes_array = [];

		$data = array(
			'indtId' => 0,
			'indtName' => 'All'
		);

		array_push($industrytypes_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'indtId' => $industrytypelist[$i]['indtId'],
				'indtName' => $industrytypelist[$i]['indtName']
			);

			array_push($industrytypes_array, $data);

		}

		$json = json_encode($industrytypes_array);
		echo $json;

	}


	public function get_all_companies()
	{
		header('Content-Type: application/json');
		$companylist= $this->User_model->get_all_companies_report();

		$c=count($companylist);

		$companies_array = [];

		$data = array(
			'cmpId' => 0,
			'cmpName' => 'All'
		);

		array_push($companies_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'cmpId' => $companylist[$i]['cmpId'],
				'cmpName' => $companylist[$i]['cmpName']
			);

			array_push($companies_array, $data);

		}

		$json = json_encode($companies_array);
		echo $json;

	}


}