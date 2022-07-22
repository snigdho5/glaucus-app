<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meetingreport extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Meetingreport_model');
        $this->load->model('Web_user_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('reports/meeting_report');

		}else{
			redirect('/login');
		}
		
	}


	public function get_meeting_records_by_date()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

			$params = array(
				'dateTypeId' => $this->input->get('dateTypeId'),
				'dateTypeValue' => $this->input->get('dateTypeValue'),
				'dateTypeName' => $this->input->get('dateTypeName'),
				'fromDate' => $this->input->get('fromDate'),
				'toDate' => $this->input->get('toDate'),
				'adminId' => $this->input->get('adminId'),
				'adminName' => $this->input->get('adminName'),
				'adminParentPath' => $this->input->get('adminParentPath'),
				'adminPath' => $this->input->get('adminParentPath').'-'.$this->input->get('adminId'),
				'userId' => $this->input->get('userId'),
				'userName' => $this->input->get('userName'),
				'customerTypeId' => $this->input->get('customerTypeId'),
				'customerTypeName' => $this->input->get('customerTypeName'),
				'industryTypeId' => $this->input->get('industryTypeId'),
				'industryTypeName' => $this->input->get('industryTypeName'),
				'companyId' => $this->input->get('companyId'),
				'companyName' => $this->input->get('companyName')
			);

			$meetinglist = $this->Meetingreport_model->get_meeting_reports_by_date($params, $parent_path);

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


	public function get_all_admins()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$adminlist = $this->Meetingreport_model->get_all_admins($parent_path);

		$c=count($adminlist);

		$admins_array = [];

		$data = array(
			'adminId' => 0,
			'adminName' => 'All',
			'adminUnitNames' => 'All',
			'adminParentPath' => 'All',
			'adminParentName' => 'All'
		);

		array_push($admins_array, $data);

		for($i=0;$i<$c;$i++){

			$userunitlist= $this->Web_user_model->get_user_all_unit_details($adminlist[$i]['usrId']);
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
				'adminId' => $adminlist[$i]['usrId'],
				'adminName' => $adminlist[$i]['usrUserName'],
				'adminParentPath' => $adminlist[$i]['usrParentPath'],
				'adminUnitNames' => $user_unit_names,
				'adminParentName' => $adminlist[$i]['parentName']
			);

			array_push($admins_array, $data);

		}

		$json = json_encode($admins_array);
		echo $json;

	}


	public function get_all_admin_users()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$userlist= $this->Meetingreport_model->get_all_admin_users($parent_path, $this->input->get('id'));

		$c=count($userlist);

		$users_array = [];

		$data = array(
			'usrId' => 0,
			'usrName' => 'All',
			'usrParentName' => 'All'
		);

		array_push($users_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'usrId' => $userlist[$i]['usrId'],
				'usrName' => $userlist[$i]['usrUserName'],
				'usrParentName' => $userlist[$i]['parentName']
			);

			array_push($users_array, $data);

		}

		$json = json_encode($users_array);
		echo $json;

	}


	public function get_all_customer_types()
	{
		header('Content-Type: application/json');
		$customertypelist= $this->Meetingreport_model->get_all_customer_types_report();

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
		$industrytypelist= $this->Meetingreport_model->get_all_industry_types_report();

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
		$companylist= $this->Meetingreport_model->get_all_companies_report();

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


	public function get_all_areas()
	{
		header('Content-Type: application/json');
		$arealist= $this->Meetingreport_model->get_all_areas_report();

		$c=count($arealist);

		$areas_array = [];

		$data = array(
			'areaId' => 0,
			'areaName' => 'All'
		);

		array_push($areas_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'areaId' => $arealist[$i]['areaId'],
				'areaName' => $arealist[$i]['areaName']
			);

			array_push($areas_array, $data);

		}

		$json = json_encode($areas_array);
		echo $json;

	}



}