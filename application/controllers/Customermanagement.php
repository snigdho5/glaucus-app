<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customermanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('App_user_model');
        $this->load->model('Web_user_model');
        $this->load->model('Country_model');
        $this->load->model('State_model');
        $this->load->model('City_model');
        $this->load->model('Customer_type_model');
        $this->load->model('Data_entry_model');		
        $this->load->model('Customer_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/customer/customer');

		}else{
			redirect('/login');
		}
		
	}


	public function add()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/customer/addcustomer');

		}else{
			redirect('/login');
		}
	}

	public function addm()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/customer/addmultiplecustomer');

		}else{
			redirect('/login');
		}
	}


	public function edit()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/customer/editcustomer');

		}else{
			redirect('/login');
		}
	}



	public function add_customer()
	{

		if($this->input->post('cusFirstName',TRUE)){


			$customerAddress = $this->input->post('cusAddress').",".$this->input->post('cusCity')."-".$this->input->post('cusPinCode').",".$this->input->post('cusState').",".$this->input->post('cusCountry');


			$purl = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyDYyjwksguOsv4VhwH7gBuXzTbIxUOfDO0&address=".urlencode($customerAddress).'&sensor=false';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $purl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$response = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($response);

			if($response->status != 'OK'){

				$data = array(
					'status' => 'failed',
					'message' => 'Google Map failed to Locate Customer Address'
				);
				$json = json_encode($data);
				echo $json;

			}else{

				$plat = $response->results[0]->geometry->location->lat;
				$plong = $response->results[0]->geometry->location->lng;

				$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
				$params = array(
					'cusFirstName' => $this->input->post('cusFirstName'),
					'cusLastName' => $this->input->post('cusLastName'),
					'cusGenderId' => $this->input->post('cusGenderId'),
					'cusGender' => $this->input->post('cusGender'),
					'cusDOB' => $this->input->post('cusDOB'),
					'cusDOA' => $this->input->post('cusDOA'),
					'cusCompanyName' => $this->input->post('cusCompanyName'),
					'cusDepartment' => $this->input->post('cusDepartment'),
					'cusDesignation' => $this->input->post('cusDesignation'),
					'cusAddress' => $this->input->post('cusAddress'),
					'cusAddress2' => $this->input->post('cusAddress2'),
					'cusLandmark' => $this->input->post('cusLandmark'),
					'cusArea' => $this->input->post('cusArea'),
					'cusCountryId' => $this->input->post('cusCountryId'),
					'cusCountry' => $this->input->post('cusCountry'),
					'cusStateId' => $this->input->post('cusStateId'),
					'cusState' => $this->input->post('cusState'),
					'cusCityId' => $this->input->post('cusCityId'),
					'cusCity' => $this->input->post('cusCity'),
					'cusPinCode' => $this->input->post('cusPinCode'),
					'cusLat' => $plat,
					'cusLong' => $plong,
					'cusEmail' => $this->input->post('cusEmail'),
					'cusMobileNo' => $this->input->post('cusMobileNo'),
					'cusAlternateNo' => $this->input->post('cusAlternateNo'),
					'cusCustomerType' => $this->input->post('cusCustomerType'),
					'cusCustomerTypeId' => $this->input->post('cusCustomerTypeId'),
					'cusIndustryType' => $this->input->post('cusIndustryType'),
					'cusIndustryTypeId' => $this->input->post('cusIndustryTypeId'),
					'cusParentId' => $this->session->userdata['logged_web_user']['userId'],
					'cusManageId' => $this->input->post('cusManageId'),
					'cusParentPath' => $parent_path,
					'cusStatusName' => 'active',
					'cusStatus' => 1
				);

				$customer_duplicate = $this->Customer_model->get_customer_duplicate($params);

				if($customer_duplicate == true){

					$data = array(
						'status' => 'failed',
						'message' => 'Mobile No already exist'
					);
					$json = json_encode($data);
					echo $json;

				}else{


					$customer_status = $this->Customer_model->add_customer($params);


					if($customer_status == true){

						if($this->input->post('cusCompanyName')!=""){
							$company_params = array(
								'cmpName' => $this->input->post('cusCompanyName'),
								'cmpStatusId' => 1,
								'cmpStatus' => 'active'
							);
							$company_duplicate = $this->Data_entry_model->check_company_duplicate($company_params);
							if($company_duplicate == false){
								$company_id = $this->Data_entry_model->add_company($company_params);
							}
						}

						if($this->input->post('cusDepartment')!=""){
							$department_params = array(
								'deptName' => $this->input->post('cusDepartment'),
								'deptStatusId' => 1,
								'deptStatus' => 'active'
							);
							$department_duplicate = $this->Data_entry_model->check_department_duplicate($department_params);
							if($department_duplicate == false){
								$department_id = $this->Data_entry_model->add_department($department_params);
							}
						}

						if($this->input->post('cusDesignation')!=""){
							$designation_params = array(
								'desgName' => $this->input->post('cusDesignation'),
								'desgStatusId' => 1,
								'desgStatus' => 'active'
							);
							$designation_duplicate = $this->Data_entry_model->check_designation_duplicate($designation_params);
							if($designation_duplicate == false){
								$designation_id = $this->Data_entry_model->add_designation($designation_params);
							}
						}

						if($this->input->post('cusArea')!=""){
							$area_params = array(
								'areaName' => $this->input->post('cusArea'),
								'areaStatusId' => 1,
								'areaStatus' => 'active'
							);
							$area_duplicate = $this->Data_entry_model->check_area_duplicate($area_params);
							if($area_duplicate == false){
								$area_id = $this->Data_entry_model->add_area($area_params);
							}
						}

						$data = array(
							'status' => 'success',
							'message' => 'Data Successfully Added'
						);
						$json = json_encode($data);
						echo $json;

					}else{

						$data = array(
							'status' => 'failed',
							'message' => 'Add Data Failed'
						);
						$json = json_encode($data);
						echo $json;


					}

				}

			}

		}

	}





	public function add_multiple_customer()
	{

		if($this->input->post('cusFirstName',TRUE)){


			$customerAddress = $this->input->post('cusAddress').",".$this->input->post('cusCity')."-".$this->input->post('cusPinCode').",".$this->input->post('cusState').",".$this->input->post('cusCountry');


			$purl = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($customerAddress).'&sensor=false';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $purl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$response = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($response);

			if($response->status != 'OK'){

				$data = array(
					'status' => 'failed',
					'message' => 'Google Map failed to Locate Customer Address'
				);
				$json = json_encode($data);
				echo $json;

			}else{

				$gender_params = array(
					'gndName' => $this->input->post('cusGender')
				);
				$selected_genderdata = $this->Customer_type_model->get_gender_data($gender_params);

				$cust_params = array(
					'custName' => $this->input->post('cusCustomerType')
				);
				$selected_custdata = $this->Customer_type_model->get_customer_type_data($cust_params);

				$indt_params = array(
					'indtName' => $this->input->post('cusIndustryType')
				);
				$selected_indtdata = $this->Customer_type_model->get_industry_type_data($indt_params);

				$country_params = array(
					'name' => $this->input->post('cusCountry')
				);
				$selected_countrydata = $this->Country_model->get_country_data($country_params);

				$state_params = array(
					'name' => $this->input->post('cusState')
				);
				$selected_statedata = $this->State_model->get_state_data($state_params);

				$city_params = array(
					'name' => $this->input->post('cusCity')
				);
				$selected_citydata = $this->City_model->get_city_data($city_params);


				$plat = $response->results[0]->geometry->location->lat;
				$plong = $response->results[0]->geometry->location->lng;

				$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
				
				$params = array(
					'cusFirstName' => $this->input->post('cusFirstName'),
					'cusLastName' => $this->input->post('cusLastName'),
					'cusGenderId' => $selected_genderdata['gndId'],
					'cusGender' => $this->input->post('cusGender'),
					'cusDOB' => $this->input->post('cusDOB'),
					'cusDOA' => $this->input->post('cusDOA'),
					'cusCompanyName' => $this->input->post('cusCompanyName'),
					'cusDepartment' => $this->input->post('cusDepartment'),
					'cusDesignation' => $this->input->post('cusDesignation'),
					'cusAddress' => $this->input->post('cusAddress'),
					'cusLandmark' => $this->input->post('cusLandmark'),
					'cusArea' => $this->input->post('cusArea'),
					'cusCountryId' => $selected_countrydata['id'],
					'cusCountry' => $this->input->post('cusCountry'),
					'cusStateId' => $selected_statedata['id'],
					'cusState' => $this->input->post('cusState'),
					'cusCityId' => $selected_citydata['id'],
					'cusCity' => $this->input->post('cusCity'),
					'cusPinCode' => $this->input->post('cusPinCode'),
					'cusLat' => $plat,
					'cusLong' => $plong,
					'cusEmail' => $this->input->post('cusEmail'),
					'cusMobileNo' => $this->input->post('cusMobileNo'),
					'cusAlternateNo' => $this->input->post('cusAlternateNo'),
					'cusCustomerType' => $this->input->post('cusCustomerType'),
					'cusCustomerTypeId' => $selected_custdata['custId'],
					'cusIndustryType' => $this->input->post('cusIndustryType'),
					'cusIndustryTypeId' => $selected_indtdata['indtId'],
					'cusParentId' => $this->session->userdata['logged_web_user']['userId'],
					'cusParentPath' => $parent_path,
					'cusStatusName' => 'active',
					'cusStatus' => 1
				);

				$customer_duplicate = $this->Customer_model->get_customer_duplicate($params);

				if($customer_duplicate == true){

					$data = array(
						'status' => 'failed',
						'message' => 'Mobile No already exist'
					);
					$json = json_encode($data);
					echo $json;

				}else{


					$customer_status = $this->Customer_model->add_customer($params);


					if($customer_status == true){

						if($this->input->post('cusCompanyName')!=""){
							$company_params = array(
								'cmpName' => $this->input->post('cusCompanyName'),
								'cmpStatusId' => 1,
								'cmpStatus' => 'active'
							);
							$company_duplicate = $this->Data_entry_model->check_company_duplicate($company_params);
							if($company_duplicate == false){
								$company_id = $this->Data_entry_model->add_company($company_params);
							}
						}

						if($this->input->post('cusDepartment')!=""){
							$department_params = array(
								'deptName' => $this->input->post('cusDepartment'),
								'deptStatusId' => 1,
								'deptStatus' => 'active'
							);
							$department_duplicate = $this->Data_entry_model->check_department_duplicate($department_params);
							if($department_duplicate == false){
								$department_id = $this->Data_entry_model->add_department($department_params);
							}
						}

						if($this->input->post('cusDesignation')!=""){
							$designation_params = array(
								'desgName' => $this->input->post('cusDesignation'),
								'desgStatusId' => 1,
								'desgStatus' => 'active'
							);
							$designation_duplicate = $this->Data_entry_model->check_designation_duplicate($designation_params);
							if($designation_duplicate == false){
								$designation_id = $this->Data_entry_model->add_designation($designation_params);
							}
						}

						if($this->input->post('cusArea')!=""){
							$area_params = array(
								'areaName' => $this->input->post('cusArea'),
								'areaStatusId' => 1,
								'areaStatus' => 'active'
							);
							$area_duplicate = $this->Data_entry_model->check_area_duplicate($area_params);
							if($area_duplicate == false){
								$area_id = $this->Data_entry_model->add_area($area_params);
							}
						}

						$data = array(
							'status' => 'success',
							'message' => 'Data Successfully Added'
						);
						$json = json_encode($data);
						echo $json;

					}else{

						$data = array(
							'status' => 'failed',
							'message' => 'Add Data Failed'
						);
						$json = json_encode($data);
						echo $json;


					}

				}

			}

		}

	}





	public function update_customer()
	{
		if($this->input->post('cusFirstName',TRUE)){


			$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];


			$customerAddress = $this->input->post('cusAddress').",".$this->input->post('cusCity')."-".$this->input->post('cusPinCode').",".$this->input->post('cusState').",".$this->input->post('cusCountry');


			$purl = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyDYyjwksguOsv4VhwH7gBuXzTbIxUOfDO0&address=".urlencode($customerAddress).'&sensor=false';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $purl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$response = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($response);

			if($response->status != 'OK'){

				$data = array(
					'status' => 'failed',
					'message' => 'Google Map failed to Locate Customer Address'
				);
				$json = json_encode($data);
				echo $json;

			}else{

				$plat = $response->results[0]->geometry->location->lat;
				$plong = $response->results[0]->geometry->location->lng;

				$params = array(
					'cusFirstName' => $this->input->post('cusFirstName'),
					'cusLastName' => $this->input->post('cusLastName'),
					'cusGender' => $this->input->post('cusGender'),
					'cusGenderId' => $this->input->post('cusGenderId'),
					'cusDOB' => $this->input->post('cusDOB'),
					'cusDOA' => $this->input->post('cusDOA'),
					'cusCompanyName' => $this->input->post('cusCompanyName'),
					'cusDepartment' => $this->input->post('cusDepartment'),
					'cusDesignation' => $this->input->post('cusDesignation'),
					'cusAddress' => $this->input->post('cusAddress'),
					'cusAddress2' => $this->input->post('cusAddress2'),
					'cusLandmark' => $this->input->post('cusLandmark'),
					'cusArea' => $this->input->post('cusArea'),
					'cusCountryId' => $this->input->post('cusCountryId'),
					'cusCountry' => $this->input->post('cusCountry'),
					'cusStateId' => $this->input->post('cusStateId'),
					'cusState' => $this->input->post('cusState'),
					'cusCityId' => $this->input->post('cusCityId'),
					'cusCity' => $this->input->post('cusCity'),
					'cusPinCode' => $this->input->post('cusPinCode'),
					'cusLat' => $plat,
					'cusLong' => $plong,
					'cusEmail' => $this->input->post('cusEmail'),
					'cusMobileNo' => $this->input->post('cusMobileNo'),
					'cusAlternateNo' => $this->input->post('cusAlternateNo'),
					'cusCustomerType' => $this->input->post('cusCustomerType'),
					'cusCustomerTypeId' => $this->input->post('cusCustomerTypeId'),
					'cusIndustryType' => $this->input->post('cusIndustryType'),
					'cusIndustryTypeId' => $this->input->post('cusIndustryTypeId'),
					'cusManageId' => $this->input->post('cusManageId')
				);


				$customer_duplicate = $this->Customer_model->get_update_customer_duplicate($this->input->post('cusId'), $params);

				if($customer_duplicate == true){

					$data = array(
						'status' => 'failed',
						'message' => 'Customer Mobile No already exist'
					);
					$json = json_encode($data);
					echo $json;

				}else{

					$customer_id = $this->Customer_model->update_customer($this->input->post('cusId'), $params);

					if($this->input->post('cusCompanyName')!=""){
						$company_params = array(
							'cmpName' => $this->input->post('cusCompanyName'),
							'cmpStatusId' => 1,
							'cmpStatus' => 'active'
						);
						$company_duplicate = $this->Data_entry_model->check_company_duplicate($company_params);
						if($company_duplicate == false){
							$company_id = $this->Data_entry_model->add_company($company_params);
						}
					}

					if($this->input->post('cusDepartment')!=""){
						$department_params = array(
							'deptName' => $this->input->post('cusDepartment'),
							'deptStatusId' => 1,
							'deptStatus' => 'active'
						);
						$department_duplicate = $this->Data_entry_model->check_department_duplicate($department_params);
						if($department_duplicate == false){
							$department_id = $this->Data_entry_model->add_department($department_params);
						}
					}

					if($this->input->post('cusDesignation')!=""){
						$designation_params = array(
							'desgName' => $this->input->post('cusDesignation'),
							'desgStatusId' => 1,
							'desgStatus' => 'active'
						);
						$designation_duplicate = $this->Data_entry_model->check_designation_duplicate($designation_params);
						if($designation_duplicate == false){
							$designation_id = $this->Data_entry_model->add_designation($designation_params);
						}
					}

					if($this->input->post('cusArea')!=""){
						$area_params = array(
							'areaName' => $this->input->post('cusArea'),
							'areaStatusId' => 1,
							'areaStatus' => 'active'
						);
						$area_duplicate = $this->Data_entry_model->check_area_duplicate($area_params);
						if($area_duplicate == false){
							$area_id = $this->Data_entry_model->add_area($area_params);
						}
					}

					$data = array(
						'status' => 'success',
						'message' => 'Customer Updated Successfully'
					);
					$json = json_encode($data);
					echo $json;

				}

			}

		}

	}



	public function get_all_customers()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$customerlist= $this->Customer_model->get_all_customers($parent_path);

		$c=count($customerlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'cusId' => $customerlist[$i]['cusId'],
				'cusCode' => $customerlist[$i]['cusCode'],
				'cusFirstName' => $customerlist[$i]['cusFirstName'],
				'cusLastName' => $customerlist[$i]['cusLastName'],
				'cusGender' => $customerlist[$i]['cusGender'],
				'cusDOB' => $customerlist[$i]['cusDOB'],
				'cusDOA' => $customerlist[$i]['cusDOA'],
				'cusCustomerType' => $customerlist[$i]['cusCustomerType'],
				'cusCustomerTypeId' => $customerlist[$i]['cusCustomerTypeId'],
				'cusIndustryType' => $customerlist[$i]['cusIndustryType'],
				'cusIndustryTypeId' => $customerlist[$i]['cusIndustryTypeId'],
				'cusCompanyName' => $customerlist[$i]['cusCompanyName'],
				'cusDepartment' => $customerlist[$i]['cusDepartment'],
				'cusDesignation' => $customerlist[$i]['cusDesignation'],
				'cusEmail' => $customerlist[$i]['cusEmail'],
				'cusMobileNo' => $customerlist[$i]['cusMobileNo'],
				'cusAlternateNo' => $customerlist[$i]['cusAlternateNo'],
				'cusCountryId' => $customerlist[$i]['cusCountryId'],
				'cusCountry' => $customerlist[$i]['cusCountry'],
				'cusStateId' => $customerlist[$i]['cusStateId'],
				'cusState' => $customerlist[$i]['cusState'],
				'cusCityId' => $customerlist[$i]['cusCityId'],
				'cusCity' => $customerlist[$i]['cusCity'],
				'cusArea' => $customerlist[$i]['cusArea'],
				'cusAddress' => $customerlist[$i]['cusAddress'],
				'cusAddress2' => $customerlist[$i]['cusAddress2'],
				'cusLandmark' => $customerlist[$i]['cusLandmark'],
				'cusPinCode' => $customerlist[$i]['cusPinCode'],
				'cusParentId' => $customerlist[$i]['cusParentId'],
				'cusParentName' => $customerlist[$i]['parentName'],
				'cusManageId' => $customerlist[$i]['cusManageId'],
				'cusManageName' => $customerlist[$i]['manageName'],
				'cusParentPath' => $customerlist[$i]['cusParentPath'],
				'cusStatusName' => $customerlist[$i]['cusStatusName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_customer_detail(){

		header('Content-Type: application/json');

		$params = array(
				'cusId' => $this->input->get('id')
			);

		$customerdata = $this->Customer_model->get_customer_data($params);

		$data = array(
				'cusId' => $customerdata['cusId'],
				'cusCode' => $customerdata['cusCode'],
				'cusFirstName' => $customerdata['cusFirstName'],
				'cusLastName' => $customerdata['cusLastName'],
				'cusGender' => $customerdata['cusGender'],
				'cusGenderId' => $customerdata['cusGenderId'],
				'cusDOB' => $customerdata['cusDOB'],
				'cusDOA' => $customerdata['cusDOA'],
				'cusCompanyName' => $customerdata['cusCompanyName'],
				'cusDepartment' => $customerdata['cusDepartment'],
				'cusDesignation' => $customerdata['cusDesignation'],
				'cusAddress' => $customerdata['cusAddress'],
				'cusAddress2' => $customerdata['cusAddress2'],
				'cusLandmark' => $customerdata['cusLandmark'],
				'cusArea' => $customerdata['cusArea'],
				'cusCountryId' => $customerdata['cusCountryId'],
				'cusCountry' => $customerdata['cusCountry'],
				'cusStateId' => $customerdata['cusStateId'],
				'cusState' => $customerdata['cusState'],
				'cusCityId' => $customerdata['cusCityId'],
				'cusCity' => $customerdata['cusCity'],
				'cusPinCode' => $customerdata['cusPinCode'],
				'cusEmail' => $customerdata['cusEmail'],
				'cusMobileNo' => $customerdata['cusMobileNo'],
				'cusAlternateNo' => $customerdata['cusAlternateNo'],
				'cusCustomerType' => $customerdata['cusCustomerType'],
				'cusCustomerTypeId' => $customerdata['cusCustomerTypeId'],
				'cusIndustryType' => $customerdata['cusIndustryType'],
				'cusIndustryTypeId' => $customerdata['cusIndustryTypeId'],
				'cusManageId' => $customerdata['cusManageId']
			);

			$json = json_encode($data);

			echo $json;

	}


	public function get_all_admin_users()
	{
		
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$userlist= $this->Customer_model->get_all_admin_users($parent_path);

		$c=count($userlist);

		$users_array = [];

		$data = array(
			'usrId' => $this->session->userdata['logged_web_user']['userId'],
			'usrName' => $this->session->userdata['logged_web_user']['userName'],
			'usrTypeId' => $this->session->userdata['logged_web_user']['userTypeId'],
			'usrTypeName' => $this->session->userdata['logged_web_user']['userTypeName']
		);

		array_push($users_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'usrId' => $userlist[$i]['usrId'],
				'usrName' => $userlist[$i]['usrUserName'],
				'usrTypeId' => $userlist[$i]['usrTypeId'],
				'usrTypeName' => $userlist[$i]['usrTypeName']
			);

			array_push($users_array, $data);

		}

		$json = json_encode($users_array);
		echo $json;

	}


	public function get_all_genders()
	{
		header('Content-Type: application/json');
		$genderlist= $this->Customer_type_model->get_all_genders();

		$c=count($genderlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'gndId' => $genderlist[$i]['gndId'],
				'gndName' => $genderlist[$i]['gndName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_gender_sources()
	{
		header('Content-Type: application/json');
		$genderlist= $this->Customer_type_model->get_all_genders();

		$c=count($genderlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = $genderlist[$i]['gndName'];

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
		$custlist= $this->Customer_type_model->get_all_customer_types();

		$c=count($custlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'custId' => $custlist[$i]['custId'],
				'custName' => $custlist[$i]['custName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}

	public function get_all_customer_type_sources()
	{
		header('Content-Type: application/json');
		$custlist= $this->Customer_type_model->get_all_customer_types();

		$c=count($custlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = $custlist[$i]['custName'];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_industry_types()
	{
		header('Content-Type: application/json');
		$indtlist= $this->Customer_type_model->get_all_industry_types();

		$c=count($indtlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'indtId' => $indtlist[$i]['indtId'],
				'indtName' => $indtlist[$i]['indtName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_industry_type_sources()
	{
		header('Content-Type: application/json');
		$indtlist= $this->Customer_type_model->get_all_industry_types();

		$c=count($indtlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = $indtlist[$i]['indtName'];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_countries_sources()
	{
		header('Content-Type: application/json');
		$countrylist= $this->Country_model->get_all_countries();

		$c=count($countrylist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = $countrylist[$i]['name'];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_states_sources()
	{
		header('Content-Type: application/json');
		$statelist= $this->State_model->get_all_states();

		$c=count($statelist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = $statelist[$i]['name'];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_cities_sources()
	{
		header('Content-Type: application/json');
		$citylist= $this->City_model->get_all_cities();

		$c=count($citylist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = $citylist[$i]['name'];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_countries()
	{
		header('Content-Type: application/json');
		$countrylist= $this->Country_model->get_all_countries();

		$c=count($countrylist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'countryId' => $countrylist[$i]['id'],
				'countryName' => $countrylist[$i]['name'],
				'countrySName' => $countrylist[$i]['sortname'],
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_states_by_id()
	{
		header('Content-Type: application/json');
		$id = $this->input->get('id');
		$statelist= $this->State_model->get_states_by_id($id);

		$c=count($statelist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'stateId' => $statelist[$i]['id'],
				'stateName' => $statelist[$i]['name']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_cities_by_id()
	{
		header('Content-Type: application/json');
		$id = $this->input->get('id');
		$citylist= $this->City_model->get_cities_by_id($id);

		$c=count($citylist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'cityId' => $citylist[$i]['id'],
				'cityName' => $citylist[$i]['name']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_companies()
	{
		header('Content-Type: application/json');
		$cmplist= $this->Data_entry_model->get_all_companies();

		$c=count($cmplist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$json = json_encode($cmplist[$i]['cmpName']);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



	public function get_all_departments()
	{
		header('Content-Type: application/json');
		$deptlist= $this->Data_entry_model->get_all_departments();

		$c=count($deptlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$json = json_encode($deptlist[$i]['deptName']);

			echo $json;
			
            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_designations()
	{
		header('Content-Type: application/json');
		$desglist= $this->Data_entry_model->get_all_designations();

		$c=count($desglist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$json = json_encode($desglist[$i]['desgName']);

			echo $json;
			
            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_areas()
	{
		header('Content-Type: application/json');
		$arealist= $this->Data_entry_model->get_all_areas();

		$c=count($arealist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$json = json_encode($arealist[$i]['areaName']);

			echo $json;
			
            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}








}