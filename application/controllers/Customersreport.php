<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customersreport extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Customersreport_model');
        $this->load->model('Web_user_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('reports/customers_report');

		}else{
			redirect('/login');
		}
		
	}


	public function get_customer_records()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

			$params = array(
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
				'companyName' => $this->input->get('companyName'),
				'areaId' => $this->input->get('areaId'),
				'areaName' => $this->input->get('areaName'),
				'dob' => $this->input->get('dob'),
				'doa' => $this->input->get('doa')
			);

			$customerlist = $this->Customersreport_model->get_customer_reports($params, $parent_path);

			$c=count($customerlist);
			echo '[';

			for($i=0;$i<$c;$i++){


				$meetinglist = $this->Customersreport_model->get_all_customer_meetings($customerlist[$i]['cusId']);

				$mcount = count($meetinglist);
				if($mcount>0){
					$last_mtnName = $meetinglist[0]['mtnName'];
					$last_mtnDate = $meetinglist[0]['mtnDate'];
					$last_mtnTime = $meetinglist[0]['mtnTime'];
				}else{
					$last_mtnName = "NA";
					$last_mtnDate = "NA";
					$last_mtnTime = "NA";
				}


				$data = array(
					'cusSNo' => $i+1,
					'cusCode' => $customerlist[$i]['cusCode'],
					'cusFirstName' => $customerlist[$i]['cusFirstName'],
					'cusLastName' => $customerlist[$i]['cusLastName'],
					'cusGender' => $customerlist[$i]['cusGender'],
					'cusDOB' => $customerlist[$i]['cusDOB'],
					'cusDOA' => $customerlist[$i]['cusDOA'],
					'cusCompanyName' => $customerlist[$i]['cusCompanyName'],
					'cusDepartment' => $customerlist[$i]['cusDepartment'],
					'cusDesignation' => $customerlist[$i]['cusDesignation'],
					'cusCountry' => $customerlist[$i]['cusCountry'],
					'cusState' => $customerlist[$i]['cusState'],
					'cusCity' => $customerlist[$i]['cusCity'],
					'cusAddress' => $customerlist[$i]['cusAddress'],
					'cusLandmark' => $customerlist[$i]['cusLandmark'],
					'cusArea' => $customerlist[$i]['cusArea'],
					'cusPinCode' => $customerlist[$i]['cusPinCode'],
					'cusEmail' => $customerlist[$i]['cusEmail'],
					'cusMobileNo' => $customerlist[$i]['cusMobileNo'],
					'cusAlternateNo' => $customerlist[$i]['cusAlternateNo'],
					'cusCustomerType' => $customerlist[$i]['cusCustomerType'],
					'cusIndustryType' => $customerlist[$i]['cusIndustryType'],
					'cusLastMeetingName' => $last_mtnName,
					'cusLastMeetingDate' => $last_mtnDate,
					'cusLastMeetingTime' => $last_mtnTime,
					'cusLastMeetingDateTime' => $last_mtnDate.' '.$last_mtnTime
				);

				$json = json_encode($data);

				echo $json;

	            if($c-1!=$i){
					echo ",";
				}

			}

			echo ']';

	}


	public function get_all_user_units()
	{
		header('Content-Type: application/json');
		$usertype = $this->session->userdata['logged_web_user']['userTypeName'];
		if($usertype == "admin"){
			$unitlist= $this->Customersreport_model->get_all_user_units($this->session->userdata['logged_web_user']['userId']);
		}else{
			$unitlist= $this->Customersreport_model->get_all_units();
		}
		

		$c=count($unitlist);

		$units_array = [];

		$data = array(
			'untId' => 0,
			'untName' => 'All'
		);

		array_push($units_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'untId' => $unitlist[$i]['untId'],
				'untName' => $unitlist[$i]['untName']
			);

			array_push($units_array, $data);

		}

		$json = json_encode($units_array);
		echo $json;

	}


	public function get_all_unit_venues()
	{
		header('Content-Type: application/json');
		$venuelist= $this->Customersreport_model->get_all_unit_venues($this->input->get('id'));

		$c=count($venuelist);

		$venues_array = [];

		$data = array(
			'venId' => 0,
			'venName' => 'All'
		);

		array_push($venues_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'venId' => $venuelist[$i]['venId'],
				'venName' => $venuelist[$i]['venShortName']
			);

			array_push($venues_array, $data);

		}

		$json = json_encode($venues_array);
		echo $json;

	}



	public function get_all_admins()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$adminlist = $this->Customersreport_model->get_all_admins($parent_path);

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
		$userlist= $this->Customersreport_model->get_all_admin_users($parent_path, $this->input->get('id'));

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
		$customertypelist= $this->Customersreport_model->get_all_customer_types_report();

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
		$industrytypelist= $this->Customersreport_model->get_all_industry_types_report();

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
		$companylist= $this->Customersreport_model->get_all_companies_report();

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
		$arealist= $this->Customersreport_model->get_all_areas_report();

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