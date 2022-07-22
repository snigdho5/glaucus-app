<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Api_model');
		$this->load->helper('url');
		$this->load->helper('form');
		// $this->load->library('google_map_lib');
		define('GOOGLE_MAP_API_KEY', "AIzaSyCTNEZO6ODA9x9z0MDb9fPGSgtYI0mqvUo");
	}

	//*************************************** Login API (start) **************************************************//	
	public function user_login()
	{
		header('Content-Type: application/json');
		if ($this->input->get('userName', TRUE)) {
			$params = 	[
				'usrUserName' 	=>	$this->input->get('userName'),
				'usrPassword' 	=> 	$this->input->get('userPassword'),
				'usrStatus'		=> 	1
			];

			$login_result = $this->Api_model->get_user_login($params);


			//if($login_result == true){
			if ($login_result == 1) {

				$login_check = $this->Api_model->check_user_login($params);

				if ($login_check == true) {

					$userdata = $this->Api_model->get_user_data($params);
					//update user login long lat with login_status on login


					$loginrecordlist = $this->Api_model->get_user_login_record_by_date($userdata['usrId'], date('Y-m-d'), date('Y-m-d'));

					$c = count($loginrecordlist);

					if ($c < 1) {

						$update_user_on_login	=	[
							'login_status' => 1,
							'user_id'		=>	$userdata['usrId'],
							'usrCurrentLat'	=>	$this->input->get('loginLat'),
							'usrCurrentLong' =>	$this->input->get('loginLong')
						];
						$on_login 	=	$this->Api_model->update_user_record_on_login($update_user_on_login);
						// die();

						$unique_lid = $userdata['usrId'] . DTIME . random_strings(5);

						$params_login_record = [
							'lgnrUserId' 		=> 	$userdata['usrId'],
							'lgnrLoginDate' 	=> 	$this->input->get('loginDate'),
							'lgnrLoginTime' 	=> 	$this->input->get('loginTime'),
							'lgnrLoginLat' 		=> 	$this->input->get('loginLat'),
							'lgnrLoginLong' 	=> 	$this->input->get('loginLong'),
							'lgnrLoginStatus' 	=> 	'active',
							'lgnrCurrentLat' 	=> 	$this->input->get('loginLat'),
							'lgnrCurrentLong' 	=>	$this->input->get('loginLong'),
							'uniq_login_id' 	=> 	encrypt_it($unique_lid)
						];

						$login_record_id = $this->Api_model->add_login_record($params_login_record);
					} else {
						$data = array(
							'status' => 'failed',
							'message' => 'Login/logout completed for today!'
						);
						$json = json_encode($data);
						echo $json;
					}



					if ($login_record_id == 0) {
						$data = [
							'status' 	=> 	'failed',
							'message'	=>	'Login record not added'
						];
						echo json_encode($data);
					} else {

						$params_login_location = [
							'crlUserId' 		=> 	$userdata['usrId'],
							'crlLoginRecordId' 	=> 	$login_record_id,
							'crlDate' 			=> 	$this->input->get('loginDate'),
							'crlTime' 			=> 	$this->input->get('loginTime'),
							'crlLat' 			=> 	$this->input->get('loginLat'),
							'crlLong' 			=>	$this->input->get('loginLong')
						];

						$add_location_id = $this->Api_model->add_current_location($params_login_location);


						if ($add_location_id == 0) {
							$data = [
								'status' => 'failed',
								'message' => 'Login location not added'
							];
							echo json_encode($data);
						} else {

							$params_user_location = [
								'usrCurrentLat' 	=> $this->input->get('loginLat'),
								'usrCurrentLong' 	=> $this->input->get('loginLong'),
								'usrCurrentActive' 	=> 'yes',
							];


							$update_user_location = $this->Api_model->update_user_current_location($userdata['usrId'], $params_user_location);

							$data = [
								'status' 			=> 	'success',
								'message' 			=> 	'Login Successfully',
								'userId' 			=> 	$userdata['usrId'],
								'userPushToken' 	=> 	$userdata['usrPushToken'],
								'userLoginRecordId'	=> 	$login_record_id,
								'userName' 			=> 	$userdata['usrUserName'],
								'userPassword' 		=> 	$userdata['usrPassword'],
								'userFirstName' 	=> 	$userdata['usrFirstName'],
								'userLastName' 		=> 	$userdata['usrLastName'],
								'userEmail' 		=> 	$userdata['usrUserEmail'],
								'userContactNo' 	=> 	$userdata['usrContactNo'],
								'userDesignation' 	=>	$userdata['usrDesignation'],
								'userParentId' 		=> 	$userdata['usrParentId'],
								'userParentPath' 	=>	$userdata['usrParentPath'],
								'userkycurl'		=>	$userdata['usrKycFile']
							];
							echo json_encode($data);
						}
					}
				} else {
					$data = [
						'status' => 'failed',
						'message' => 'Account is Deactivated'
					];
					echo json_encode($data);
				}
			} else if ($login_result == 2) {
				$data = [
					'status' 	=> 	'failed',
					'message' 	=>	'User already logged in'
				];
				echo json_encode($data);
			} else {
				$data = [
					'status' 	=> 	'failed',
					'message' 	=>	'Username or password is wrong'
				];
				echo json_encode($data);
			}
		}
	}

	//*************************************** Login API (end) **************************************************//

	//*************************************** Get Meetings API (start) **************************************************//

	public function get_meetings()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'usrParentPath' => $this->input->get('userParentPath')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$meetinglist = $this->Api_model->get_all_meetings($params);
				//print_r($meetinglist);
				//die();
				$c = count($meetinglist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						if ($meetinglist[$i]['cusAddress2'] == "" || $meetinglist[$i]['cusAddress2'] == null) {
							$customerAddress = $meetinglist[$i]['cusAddress'] . ', ' . $meetinglist[$i]['cusCity'] . '-' . $meetinglist[$i]['cusPinCode'];
						} else {
							$customerAddress = $meetinglist[$i]['cusAddress'] . ', ' . $meetinglist[$i]['cusAddress2'] . ', ' . $meetinglist[$i]['cusCity'] . '-' . $meetinglist[$i]['cusPinCode'];
						}

						/*if($meetinglist[$i]['mtnTelecallerId']==0){
							$mtnTelecallerName = "No Telecaller";
						}else{
							$userdata = $this->Api_model->get_user_data_by_id($this->input->get($meetinglist[$i]['mtnTelecallerId']));
							$mtnTelecallerName = $userdata['usrUserName'];
						}*/

						$data = array(
							'mtnId' => $meetinglist[$i]['mtnId'],
							'mtnName' => $meetinglist[$i]['mtnName'],
							'mtnMeetingTypeId' => $meetinglist[$i]['mtnMeetingTypeId'],
							'mtnMeetingType' => $meetinglist[$i]['mtnMeetingType'],
							'mtnVisited' => $meetinglist[$i]['mtnVisited'],
							'mtnNextVisit' => $meetinglist[$i]['mtnNextVisit'],
							'mtnSignature' => $meetinglist[$i]['mtnSignature'],
							'mtnPicture' => $meetinglist[$i]['mtnPicture'],
							'mtnRemarks' => $meetinglist[$i]['mtnRemarks'],
							'mtnCompleted' => $meetinglist[$i]['mtnCompleted'],
							'mtnCustomerId' => $meetinglist[$i]['mtnCustomerId'],
							'mtnCustomerName' => $meetinglist[$i]['cusFirstName'] . ' ' . $meetinglist[$i]['cusLastName'],
							'mtnCustomerCompanyName' => $meetinglist[$i]['cusCompanyName'],
							'mtnCustomerMobileNo' => $meetinglist[$i]['cusMobileNo'],
							'mtnCustomerAddress' => $customerAddress,
							'mtnCustomerLat' => $meetinglist[$i]['cusLat'],
							'mtnCustomerLong' => $meetinglist[$i]['cusLong'],
							'mtnUserId' => $meetinglist[$i]['mtnUserId'],
							'mtnUserName' => $meetinglist[$i]['userName'],
							'mtnDate' => $meetinglist[$i]['mtnDate'],
							'mtnTime' => $meetinglist[$i]['mtnTime'],
							//'mtnComments' => $meetinglist[$i]['mtnComments'],
							//'mtnTelecallerName' => $mtnTelecallerName,
							'mtnParentName' => $meetinglist[$i]['parentName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Meetings API (end) **************************************************//


	//*************************************** Get Meetings API (start) **************************************************//

	public function get_visited_meetings()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'usrParentPath' => $this->input->get('userParentPath')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$meetinglist = $this->Api_model->get_all_visited_meetings($params);

				$c = count($meetinglist);

				if ($c < 1) {

					$data = array(
						'status'  => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'mtnId'                  => $meetinglist[$i]['mtnId'],
							'mtnName'                => $meetinglist[$i]['mtnName'],
							'mtnVisited'             => $meetinglist[$i]['mtnVisited'],
							'mtnNextVisit'           => $meetinglist[$i]['mtnNextVisit'],
							'mtnSignature'           => $meetinglist[$i]['mtnSignature'],
							'mtnPicture'             => $meetinglist[$i]['mtnPicture'],
							'mtnRemarks'             => $meetinglist[$i]['mtnRemarks'],
							'mtnCompleted'           => $meetinglist[$i]['mtnCompleted'],
							'mtnCustomerId'          => $meetinglist[$i]['mtnCustomerId'],
							'mtnCustomerName'        => $meetinglist[$i]['cusFirstName'] . ' ' . $meetinglist[$i]['cusLastName'],
							'mtnCustomerShopName'    => $meetinglist[$i]['cusCompanyName'],
							'mtnCustomerMobileNo'    => $meetinglist[$i]['cusMobileNo'],
							'mtnCustomerAddress'     => $meetinglist[$i]['cusAddress'] . ', ' . $meetinglist[$i]['cusCity'] . '-' . $meetinglist[$i]['cusPinCode'],
							'mtnCustomerLat'         => $meetinglist[$i]['cusLat'],
							'mtnCustomerLong'        => $meetinglist[$i]['cusLong'],
							'mtnUserId'              => $meetinglist[$i]['mtnUserId'],
							'mtnUserName'            => $meetinglist[$i]['userName'],
							'mtnDate'                => $meetinglist[$i]['mtnDate'],
							'mtnTime'                => $meetinglist[$i]['mtnTime'],
							'mtnParentName'          => $meetinglist[$i]['parentName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Meetings API (end) **************************************************//


	//*************************************** Get Meeting Details API (start) **************************************************//

	public function get_meeting_details()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'mtnId' => $this->input->get('meetingId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {

				$meeting_check = $this->Api_model->check_meeting_data($params);

				if ($meeting_check == true) {

					$meetingdata = $this->Api_model->get_meeting_data($params);

					if ($meetingdata['cusAddress2'] == "" || $meetingdata['cusAddress2'] == null) {
						$customerAddress = $meetingdata['cusAddress'] . ', ' . $meetingdata['cusCity'] . '-' . $meetingdata['cusPinCode'];
					} else {
						$customerAddress = $meetingdata['cusAddress'] . ', ' . $meetingdata['cusAddress2'] . ', ' . $meetingdata['cusCity'] . '-' . $meetingdata['cusPinCode'];
					}

					/*if($meetingdata['mtnTelecallerId']==0){
						$mtnTelecallerName = "No Telecaller";
					}else{
						$userdata = $this->Api_model->get_user_data_by_id($meetingdata['mtnTelecallerId']);
						
						$mtnTelecallerName = $userdata['usrUserName'];
					}*/

					$data = array(
						'status' => 'success',
						'message' => 'Successfully Get Data',
						'mtnId' => $meetingdata['mtnId'],
						'mtnName' => $meetingdata['mtnName'],
						'mtnMeetingTypeId' => $meetingdata['mtnMeetingTypeId'],
						'mtnMeetingType' => $meetingdata['mtnMeetingType'],
						'mtnVisited' => $meetingdata['mtnVisited'],
						'mtnNextVisit' => $meetingdata['mtnNextVisit'],
						'mtnSignature' => $meetingdata['mtnSignature'],
						'mtnPicture' => $meetingdata['mtnPicture'],
						'mtnRemarks' => $meetingdata['mtnRemarks'],
						'mtnCompleted' => $meetingdata['mtnCompleted'],
						'mtnCustomerId' => $meetingdata['mtnCustomerId'],
						'mtnCustomerName' => $meetingdata['cusFirstName'] . ' ' . $meetingdata['cusLastName'],
						'mtnCustomerGenderId' => $meetingdata['cusGenderId'],
						'mtnCustomerGender' => $meetingdata['cusGender'],
						'mtnCustomerDOB' => $meetingdata['cusDOB'],
						'mtnCustomerDOA' => $meetingdata['cusDOA'],
						'mtnCustomerTypeId' => $meetingdata['cusCustomerTypeId'],
						'mtnCustomerType' => $meetingdata['cusCustomerType'],
						'mtnCustomerIndustryTypeId' => $meetingdata['cusIndustryType'],
						'mtnCustomerIndustryType' => $meetingdata['cusIndustryType'],
						'mtnCustomerCompanyName' => $meetingdata['cusCompanyName'],
						'mtnCustomerDepartment' => $meetingdata['cusDepartment'],
						'mtnCustomerDesignation' => $meetingdata['cusDesignation'],
						'mtnCustomerEmail' => $meetingdata['cusEmail'],
						'mtnCustomerMobileNo' => $meetingdata['cusMobileNo'],
						'mtnCustomerAlternateNo' => $meetingdata['cusAlternateNo'],
						'mtnCustomerAddress' => $meetingdata['cusAddress'] . ', ' . $meetingdata['cusCity'] . ' - ' . $meetingdata['cusPinCode'],
						'mtnCustomerLandmark' => $meetingdata['cusLandmark'],
						'mtnCustomerArea' => $meetingdata['cusArea'],
						'mtnCustomerLat' => $meetingdata['cusLat'],
						'mtnCustomerLong' => $meetingdata['cusLong'],
						'mtnUserId' => $meetingdata['mtnUserId'],
						'mtnDate' => $meetingdata['mtnDate'],
						'mtnTime' => $meetingdata['mtnTime'],
						//'mtnTelecallerName' => $mtnTelecallerName,
						//'mtnTelecallerId' => $meetingdata['mtnTelecallerId'],
						//'mtnComments' => $meetingdata['mtnComments']
					);

					$json = json_encode($data);

					echo $json;
				} else {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Meeting Details API (end) **************************************************//



	//*************************************** Update Current Location API (start) **********************************************//

	public function update_current_location()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'crlUserId'            => $this->input->get('userId'),
					'crlLoginRecordId'     => $this->input->get('userLoginRecordId'),
					'crlDate'              => $this->input->get('crlDate'),
					'crlTime'              => $this->input->get('crlTime'),
					'crlLat'               => $this->input->get('crlLat'),
					'crlLong'              => $this->input->get('crlLong'),
					'appTokenid'           => $this->input->get('appTokenid')
				);

				$add_location_id = $this->Api_model->add_current_location($params);

				if ($add_location_id == 0) {

					$data = array(
						'status' => 'failed',
						'message' => 'Location not added'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$params_user_location = array(
						'usrCurrentLat'   => $this->input->get('crlLat'),
						'usrCurrentLong'  => $this->input->get('crlLong')
					);


					$update_user_location = $this->Api_model->update_user_current_location($this->input->get('userId'), $params_user_location);

					if ($this->input->get('isTripStart') == "true") {

						$params_trip = array(
							'trplUserId'         => $this->input->get('userId'),
							'trplLoginRecordId'  => $this->input->get('userLoginRecordId'),
							'trplTripRecordId'   => $this->input->get('trpId'),
							'trplDate'           => $this->input->get('crlDate'),
							'trplTime'           => $this->input->get('crlTime'),
							'trplLat'            => $this->input->get('crlLat'),
							'trplLong'           => $this->input->get('crlLong')
						);

						$add_trip_location_id = $this->Api_model->add_trip_location($params_trip);

						if ($add_location_id == 0) {

							$data = array(
								'status'    => 'failed',
								'message'   => 'Trip Location not added'
							);

							$json = json_encode($data);
							echo $json;
						} else {

							$data = array(
								'status'    => 'success',
								'message'   => 'Current Location & Trip Location added Successfully'
							);

							$json = json_encode($data);
							echo $json;
						}
					} else {

						if ($this->input->get('appTokenid')) {
							$data = ['status'     => 'success', 'tokenid' => $this->input->get('appTokenid'), 'message'    => 'Location Added Successfully'];
						} else {
							$data = ['status'     => 'success', 'message'    => 'Location Added Successfully'];
						}
						$json = json_encode($data);
						echo $json;
					}
				}
			} else {

				$data = array(
					'status'   => 'failed',
					'message'  => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}


	//*************************************** Update Current Location API (end) **********************************************//


	//*************************************** Update Meeting Visited API (start) *********************************************//

	public function update_meeting_visited()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'mtnVisited'           => 'yes',
					'mtnVisitedMessage'    => $this->input->post('mtnVisitedMessage'),
					'mtnVisitedDate'       => $this->input->post('mtnVisitedDate'),
					'mtnVisitedTime'       => $this->input->post('mtnVisitedTime'),
					'mtnvisitedLat'        => $this->input->post('mtnVisitedLat'),
					'mtnVisitedLong'       => $this->input->post('mtnVisitedLong')
				);

				$params_last_meeting = array(
					'usrLastMeetingId' => $this->input->post('mtnId')
				);

				$update_meeting_visited = $this->Api_model->update_meeting_visited($this->input->post('mtnId'), $params);

				$update_last_visited_meeting = $this->Api_model->update_last_visited_meeting($this->input->post('userId'), $params_last_meeting);

				$data = array(
					'status'   => 'success',
					'message'  => 'Meeting Visited Updated Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status'   => 'failed',
					'message'  => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Update Meeting Visited API (end) **********************************************//


	//*************************************** Update Next Visit API (start) **************************************************//

	public function update_next_visit()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'mtnNextVisit' => 'yes'
				);

				$params_meeting = array(
					'mtnCustomerId' => $this->input->post('mtnCustomerId'),
					'mtnUserId' => $this->input->post('userId'),
					'mtnName' => $this->input->post('mtnName'),
					'mtnMeetingTypeId' => $this->input->post('mtnMeetingTypeId'),
					'mtnMeetingType' => $this->input->post('mtnMeetingType'),
					'mtnDate' => $this->input->post('mtnDate'),
					'mtnTime' => $this->input->post('mtnTime'),
					'mtnNextVisit' => 'no',
					'mtnCompleted' => 'no',
					'mtnVisited' => 'no',
					'mtnSignature' => 'no',
					'mtnPicture' => 'no',
					'mtnRemarks' => 'no',
					'mtnParentId' => $this->input->post('userId'),
					'mtnParentPath' => $this->input->post('userParentPath')
				);

				/*
				$meeting_name_duplicate = $this->Api_model->get_meeting_name_duplicate($this->input->post('mtnName'));


				if($meeting_name_duplicate == true){

					$data = array(
						'status' => 'failed',
						'message' => 'Meeting Name already exist'
					);

					$json = json_encode($data);
					echo $json;

				}else{*/


				$meeting_id = $this->Api_model->add_meeting($params_meeting);


				if ($meeting_id == false) {

					$data = array(
						'status' => 'failed',
						'message' => 'Add Meeting Failed'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$update_meeting_visited = $this->Api_model->update_meeting_visited($this->input->post('mtnId'), $params);

					$data = array(
						'status' => 'success',
						'message' => 'Meeting Created Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}

				//}





			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}


	//*************************************** Update Next Visit API (end) **************************************************//

	//******************************* Update Meeting Visited Message API (start) **********************************************//

	public function update_meeting_visited_message()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'mtnVisitedMessage' => $this->input->post('mtnVisitedMessage')
				);

				$update_meeting_visited = $this->Api_model->update_meeting_visited($this->input->post('mtnId'), $params);

				$data = array(
					'status' => 'success',
					'message' => 'Meeting Visited Message Updated Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update Meeting Visited Message API (end) *********************************************//

	//*************************************** Update Meeting Remarks API (start) **********************************************//

	public function update_meeting_remarks()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'mtnRemarks' => 'yes',
					//'mtnCompleted' => 'yes',
					'mtnRemarksMessage' => $this->input->post('mtnRemarksMessage'),
					'mtnRemarksDate' => $this->input->post('mtnRemarksDate'),
					'mtnRemarksTime' => $this->input->post('mtnRemarksTime'),
					'mtnRemarksLat' => $this->input->post('mtnRemarksLat'),
					'mtnRemarksLong' => $this->input->post('mtnRemarksLong')
				);

				$update_meeting_visited = $this->Api_model->update_meeting_remarks($this->input->post('mtnId'), $params);

				$data = array(
					'status' => 'success',
					'message' => 'Meeting Remarks Updated Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Update Meeting Remarks API (end) ***********************************************//


	//*************************************** Update Meeting Completed API (start) *********************************************//

	public function update_meeting_completed()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'mtnCompleted' => $this->input->post('mtnCompleted'),
				);

				$update_meeting_completed = $this->Api_model->update_meeting_completed($this->input->post('mtnId'), $params);

				$data = array(
					'status' => 'success',
					'message' => 'Meeting Completed Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Update Meeting Completed API (end) **********************************************//

	//******************************* Update Meeting Signature API (start) **********************************************//

	public function update_meeting_signature()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$signatureImage = $this->input->post('mtnSignatureImage');
				$signatureImageName = $this->input->post('mtnSignatureImageName');

				$path = "uploads/signatures/$signatureImageName.png";

				$params = array(
					'mtnSignature' => 'yes',
					'mtnSignatureImage' => $path,
					'mtnSignatureDate' => $this->input->post('mtnSignatureDate'),
					'mtnSignatureTime' => $this->input->post('mtnSignatureTime'),
					'mtnSignatureLat' => $this->input->post('mtnSignatureLat'),
					'mtnSignatureLong' => $this->input->post('mtnSignatureLong')
				);

				$update_meeting_visited = $this->Api_model->update_meeting_visited($this->input->post('mtnId'), $params);

				file_put_contents($path, base64_decode($signatureImage));

				$data = array(
					'status' => 'success',
					'message' => 'Meeting Signature Updated Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update Meeting Signature API (end) *********************************************//


	//******************************* Update Meeting Picture API (start) **********************************************//

	public function update_meeting_picture()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$pictureImage = $this->input->post('mtnPictureImage');
				$pictureImageName = $this->input->post('mtnPictureImageName');

				$path = "uploads/pictures/$pictureImageName.png";

				$params = array(
					'mtnPicture' => 'yes',
					'mtnPictureImage' => $path,
					'mtnPictureDate' => $this->input->post('mtnPictureDate'),
					'mtnPictureTime' => $this->input->post('mtnPictureTime'),
					'mtnPictureLat' => $this->input->post('mtnPictureLat'),
					'mtnPictureLong' => $this->input->post('mtnPictureLong')
				);

				$update_meeting_picture = $this->Api_model->update_meeting_visited($this->input->post('mtnId'), $params);

				file_put_contents($path, base64_decode($pictureImage));

				$data = array(
					'status' => 'success',
					'message' => 'Meeting Picture Updated Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update Meeting Picture API (end) *********************************************//




	//*************************************** Add Customer API (start) **************************************************//

	public function add_customer()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {


				$customerAddress = $this->input->post('cusAddress') . "," . $this->input->post('cusCity') . "-" . $this->input->post('cusPinCode') . "," . $this->input->post('cusState') . "," . $this->input->post('cusCountry');


				// $purl = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyDLzEQ6FcQtf9oZNBsDLf_trm_RCto_IJg&address=".urlencode($customerAddress).'&sensor=false';
				$purl = "https://maps.google.com/maps/api/geocode/json?key=" . GOOGLE_MAP_API_KEY . "&address=" . urlencode($customerAddress) . '&sensor=false';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $purl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				$response = curl_exec($ch);
				curl_close($ch);
				$response = json_decode($response);

				if ($response->status != 'OK') {

					$data = array(
						'status' => 'failed',
						'message' => 'Google Map Failed to Located Cutomer Address'
					);

					$json = json_encode($data);
					echo $json;
				} else {



					$plat = $response->results[0]->geometry->location->lat;
					$plong = $response->results[0]->geometry->location->lng;

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
						'cusParentId' => $this->input->post('userId'),
						'cusManageId' => $this->input->post('userId'),
						'cusParentPath' => $this->input->post('userParentPath'),
						'cusStatusName' => 'active',
						'cusStatus' => 1
					);



					$customer_duplicate = $this->Api_model->get_customer_duplicate($params);

					if ($customer_duplicate == true) {

						$data = array(
							'status' => 'failed',
							'message' => 'Customer Mobile No already exist'
						);

						$json = json_encode($data);
						echo $json;
					} else {


						$meeting_name_duplicate = $this->Api_model->get_meeting_name_duplicate($this->input->post('mtnName'));


						if ($meeting_name_duplicate == true) {

							$data = array(
								'status' => 'failed',
								'message' => 'Meeting Name already exist'
							);

							$json = json_encode($data);
							echo $json;
						} else {



							$customer_id = $this->Api_model->add_customer_id($params);


							if ($customer_id == 0) {

								$data = array(
									'status' => 'failed',
									'message' => 'Add Customer Failed'
								);

								$json = json_encode($data);
								echo $json;
							} else {

								$params_meeting = array(
									'mtnCustomerId' => $customer_id,
									'mtnUserId' => $this->input->post('userId'),
									'mtnName' => $this->input->post('mtnName'),
									'mtnMeetingTypeId' => $this->input->post('mtnMeetingTypeId'),
									'mtnMeetingType' => $this->input->post('mtnMeetingType'),
									'mtnDate' => $this->input->post('mtnDate'),
									'mtnTime' => $this->input->post('mtnTime'),
									'mtnNextVisit' => 'no',
									'mtnCompleted' => 'no',
									'mtnVisited' => 'no',
									'mtnSignature' => 'no',
									'mtnPicture' => 'no',
									'mtnRemarks' => 'no',
									'mtnParentId' => $this->input->post('userId'),
									'mtnParentPath' => $this->input->post('userParentPath')
								);


								$meeting_id = $this->Api_model->add_meeting($params_meeting);


								if ($meeting_id == false) {

									$data = array(
										'status' => 'failed',
										'message' => 'Add Meeting Failed'
									);

									$json = json_encode($data);
									echo $json;
								} else {

									$data = array(
										'status' => 'success',
										'message' => 'Data added Successfully'
									);

									$json = json_encode($data);
									echo $json;
								}
							}
						}
					}
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Add Customer API (end) **************************************************//



	//*************************************** Update Start Trip Record API (start) *************************************//

	public function update_start_trip_record()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'trpUserId' => $this->input->post('userId'),
					'trpLoginRecordId' => $this->input->post('trpLoginRecordId'),
					'trpStartDate' => $this->input->post('trpStartDate'),
					'trpStartTime' => $this->input->post('trpStartTime'),
					'trpStartLat' => $this->input->post('trpStartLat'),
					'trpStartLong' => $this->input->post('trpStartLong')
				);

				$add_start_trip_id = $this->Api_model->add_start_trip_record($params);

				if ($add_start_trip_id == 0) {

					$data = array(
						'status' => 'failed',
						'message' => 'Trip Record not added'
					);

					$json = json_encode($data);
					echo $json;
				} else {


					$params_trip = array(
						'trplUserId' => $this->input->post('userId'),
						'trplLoginRecordId' => $this->input->post('trpLoginRecordId'),
						'trplTripRecordId' => $add_start_trip_id,
						'trplDate' => $this->input->post('trpStartDate'),
						'trplTime' => $this->input->post('trpStartTime'),
						'trplLat' => $this->input->post('trpStartLat'),
						'trplLong' => $this->input->post('trpStartLong')
					);

					$add_trip_location_id = $this->Api_model->add_trip_location($params_trip);

					if ($add_trip_location_id == 0) {

						$data = array(
							'status' => 'failed',
							'message' => 'Trip location not added'
						);

						$json = json_encode($data);
						echo $json;
					} else {

						$data = array(
							'status' => 'success',
							'trpId' => $add_start_trip_id,
							'message' => 'Trip Record and Location Added Successfully'
						);

						$json = json_encode($data);
						echo $json;
					}
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Update Start Trip Record API (end) ******************************************//


	//*************************************** Update Stop Trip Record API (start) *****************************************//

	public function update_stop_trip_record()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'trpEndDate' => $this->input->post('trpEndDate'),
					'trpEndTime' => $this->input->post('trpEndTime'),
					'trpEndLat' => $this->input->post('trpEndLat'),
					'trpEndLong' => $this->input->post('trpEndLong')
				);


				$params_trip = array(
					'trplUserId' => $this->input->post('userId'),
					'trplLoginRecordId' => $this->input->post('trpLoginRecordId'),
					'trplTripRecordId' => $this->input->post('trpId'),
					'trplDate' => $this->input->post('trpEndDate'),
					'trplTime' => $this->input->post('trpEndTime'),
					'trplLat' => $this->input->post('trpEndLat'),
					'trplLong' => $this->input->post('trpEndLong')
				);

				$add_trip_location_id = $this->Api_model->add_trip_location($params_trip);

				if ($add_trip_location_id == 0) {

					$data = array(
						'status' => 'failed',
						'message' => 'Trip Location not Updated'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$update_stop_trip = $this->Api_model->update_stop_trip_record($this->input->post('trpId'), $params);


					$data = array(
						'status' => 'success',
						'message' => 'Trip Record and Location Updated Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Update Stop Trip Record API (end) *********************************************//

	//*************************************** Update Logout Record API (start) ************************************************//

	public function update_logout_record()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate	=	[
				'usrId' => $this->input->post('userId')
			];

			$authenticate_user 	=	$this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				//update user login long lat with status on login
				$update_user_on_login	=	[
					'user_id'			=>	$this->input->post('userId'),
					'login_status'		=>	0,
					'usrCurrentLat'		=>	$this->input->post('lgnrLogoutLat'),
					'usrCurrentLong'	=>	$this->input->post('lgnrLogoutLong')
				];
				$on_login 	=	$this->Api_model->update_user_record_on_login($update_user_on_login);
				// die();
				$params_login_location 	=	[
					'crlUserId' 		=> 	$this->input->post('userId'),
					'crlLoginRecordId' 	=> 	$this->input->post('lgnrId'),
					'crlDate' 			=> 	$this->input->post('lgnrLogoutDate'),
					'crlTime' 			=> 	$this->input->post('lgnrLogoutTime'),
					'crlLat' 			=> 	$this->input->post('lgnrLogoutLat'),
					'crlLong' 			=>	$this->input->post('lgnrLogoutLong')
				];

				$add_location_id 	=	$this->Api_model->add_current_location($params_login_location);

				if ($add_location_id == 0) {

					$data = [
						'status'	=> 	'failed',
						'message' 	=>	'User Location not updated'
					];
					echo json_encode($data);
				} else {

					$params =	[
						'lgnrLogoutDate'	=> 	$this->input->post('lgnrLogoutDate'),
						'lgnrLogoutTime' 	=> 	$this->input->post('lgnrLogoutTime'),
						'lgnrLogoutLat' 	=> 	$this->input->post('lgnrLogoutLat'),
						'lgnrLogoutLong' 	=>	$this->input->post('lgnrLogoutLong')
					];

					$update_logout_record = $this->Api_model->update_logout_record($this->input->post('lgnrId'), $params);

					$params_user_location = [
						'usrCurrentLat' 	=> $this->input->post('lgnrLogoutLat'),
						'usrCurrentLong' 	=> $this->input->post('lgnrLogoutLong'),
						'usrCurrentActive' 	=> 'no',
					];

					$update_user_location = $this->Api_model->update_user_current_location($this->input->post('userId'), $params_user_location);

					$data 	= 	[
						'status' => 'success',
						'message' => 'Logout Record Updated Successfully'
					];
					echo json_encode($data);
				}
			} else {

				$data 	= 	[
					'status' => 'failed',
					'message' => 'User not authenticated'
				];
				echo json_encode($data);
			}
		}
	}

	//*************************************** Update Logout Record API (end) ************************************************//

	//*************************************** Get All Customers API (start) ************************************************//

	public function get_all_customers()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'usrParentPath' => $this->input->get('userParentPath')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			$userdata = $this->Api_model->get_user_data_by_id($this->input->get('userId'));

			$user_parent_path = $userdata['usrParentPath'];

			if ($authenticate_user == true) {
				$customerlist = $this->Api_model->get_all_customers($user_parent_path);

				$c = count($customerlist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'cusId' => $customerlist[$i]['cusId'],
							'cusCode' => $customerlist[$i]['cusCode'],
							'cusFirstName' => $customerlist[$i]['cusFirstName'],
							'cusLastName' => $customerlist[$i]['cusLastName'],
							'cusGenderId' => $customerlist[$i]['cusGenderId'],
							'cusGender' => $customerlist[$i]['cusGender'],
							'cusDOB' => $customerlist[$i]['cusDOB'],
							'cusDOA' => $customerlist[$i]['cusDOA'],
							'cusCustomerTypeId' => $customerlist[$i]['cusCustomerTypeId'],
							'cusCustomerType' => $customerlist[$i]['cusCustomerType'],
							'cusIndustryTypeId' => $customerlist[$i]['cusIndustryTypeId'],
							'cusIndustryType' => $customerlist[$i]['cusIndustryType'],
							'cusCompanyName' => $customerlist[$i]['cusCompanyName'],
							'cusDepartment' => $customerlist[$i]['cusDepartment'],
							'cusDesignation' => $customerlist[$i]['cusDesignation'],
							'cusAddress' => $customerlist[$i]['cusAddress'],
							'cusAddress2' => $customerlist[$i]['cusAddress2'],
							'cusLandmark' => $customerlist[$i]['cusLandmark'],
							'cusArea' => $customerlist[$i]['cusArea'],
							'cusCountryId' => $customerlist[$i]['cusCountryId'],
							'cusCountry' => $customerlist[$i]['cusCountry'],
							'cusStateId' => $customerlist[$i]['cusStateId'],
							'cusState' => $customerlist[$i]['cusState'],
							'cusCityId' => $customerlist[$i]['cusCityId'],
							'cusCity' => $customerlist[$i]['cusCity'],
							'cusPinCode' => $customerlist[$i]['cusPinCode'],
							'cusEmail' => $customerlist[$i]['cusEmail'],
							'cusMobileNo' => $customerlist[$i]['cusMobileNo'],
							'cusAlternateNo' => $customerlist[$i]['cusAlternateNo'],
							'cusParentId' => $customerlist[$i]['cusParentId'],
							'cusParentName' => $customerlist[$i]['usrUserName'],
							'cusParentPath' => $customerlist[$i]['cusParentPath'],
							'cusStatusName' => $customerlist[$i]['cusStatusName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get All Customers API (end) **************************************************//


	//*************************************** Get Customer Detail API (start) ************************************************//

	public function get_customer_detail()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {

				$params_customer = array(
					'cusId' => $this->input->get('cusId')
				);


				$customerdata = $this->Api_model->get_customer_data($params_customer);

				$data = array(
					'cusId' => $customerdata['cusId'],
					'cusCode' => $customerdata['cusCode'],
					'cusFirstName' => $customerdata['cusFirstName'],
					'cusLastName' => $customerdata['cusLastName'],
					'cusShopName' => $customerdata['cusCompanyName'],
					'cusAddress' => $customerdata['cusAddress'],
					'cusAddress2' => $customerdata['cusAddress2'],
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
					'cusCustomerTypeId' => $customerdata['cusCustomerTypeId']
				);

				$json = json_encode($data);

				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Customer Detail API (end) **************************************************//


	//*************************************** Get Customer Meetings (start) **************************************************//

	public function get_customer_meetings()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$meetinglist = $this->Api_model->get_customer_meetings($this->input->get('cusId'));

				$c = count($meetinglist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Meeting not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'mtnId' => $meetinglist[$i]['mtnId'],
							'mtnName' => $meetinglist[$i]['mtnName'],
							'mtnCustomerId' => $meetinglist[$i]['mtnCustomerId'],
							'mtnCustomerName' => $meetinglist[$i]['cusFirstName'] . ' ' . $meetinglist[$i]['cusLastName'],
							'mtnUserId' => $meetinglist[$i]['mtnUserId'],
							'mtnUserName' => $meetinglist[$i]['userName'],
							'mtnDate' => $meetinglist[$i]['mtnDate'],
							'mtnTime' => $meetinglist[$i]['mtnTime'],
							'mtnVisited' => $meetinglist[$i]['mtnVisited'],
							'mtnVisitedDate' => $meetinglist[$i]['mtnVisitedDate'],
							'mtnVisitedTime' => $meetinglist[$i]['mtnVisitedTime'],
							'mtnVisitedLat' => $meetinglist[$i]['mtnVisitedLat'],
							'mtnVisitedLong' => $meetinglist[$i]['mtnVisitedLong'],
							'mtnVisitedMessage' => $meetinglist[$i]['mtnVisitedMessage'],
							'mtnRemarks' => $meetinglist[$i]['mtnRemarks'],
							'mtnRemarksDate' => $meetinglist[$i]['mtnRemarksDate'],
							'mtnRemarksTime' => $meetinglist[$i]['mtnRemarksTime'],
							'mtnRemarksLat' => $meetinglist[$i]['mtnRemarksLat'],
							'mtnRemarksLong' => $meetinglist[$i]['mtnRemarksLong'],
							'mtnRemarksMessage' => $meetinglist[$i]['mtnRemarksMessage'],
							'mtnSignature' => $meetinglist[$i]['mtnSignature'],
							'mtnSignatureDate' => $meetinglist[$i]['mtnSignatureDate'],
							'mtnSignatureTime' => $meetinglist[$i]['mtnSignatureTime'],
							'mtnSignatureLat' => $meetinglist[$i]['mtnSignatureLat'],
							'mtnSignatureLong' => $meetinglist[$i]['mtnSignatureLong'],
							'mtnSignatureImage' => base_url() . $meetinglist[$i]['mtnSignatureImage'],
							'mtnPicture' => $meetinglist[$i]['mtnPicture'],
							'mtnPictureDate' => $meetinglist[$i]['mtnPictureDate'],
							'mtnPictureTime' => $meetinglist[$i]['mtnPictureTime'],
							'mtnPictureLat' => $meetinglist[$i]['mtnPictureLat'],
							'mtnPictureLong' => $meetinglist[$i]['mtnPictureLong'],
							'mtnPictureImage' => base_url() . $meetinglist[$i]['mtnPictureImage'],
							'mtnNextVisit' => $meetinglist[$i]['mtnNextVisit'],
							'mtnCompleted' => $meetinglist[$i]['mtnCompleted'],
							'mtnParentName' => $meetinglist[$i]['parentName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Customer Meetings API (end) **************************************************//

	//*************************************** Get Customer Meetings (start) **************************************************//

	public function get_customer_completed_meetings()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$meetinglist = $this->Api_model->get_customer_completed_meetings($this->input->get('cusId'));

				$c = count($meetinglist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Meeting History not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'mtnId' => $meetinglist[$i]['mtnId'],
							'mtnName' => $meetinglist[$i]['mtnName'],
							'mtnCustomerId' => $meetinglist[$i]['mtnCustomerId'],
							'mtnCustomerName' => $meetinglist[$i]['cusFirstName'] . ' ' . $meetinglist[$i]['cusLastName'],
							'mtnUserId' => $meetinglist[$i]['mtnUserId'],
							'mtnUserName' => $meetinglist[$i]['userName'],
							'mtnDate' => $meetinglist[$i]['mtnDate'],
							'mtnTime' => $meetinglist[$i]['mtnTime'],
							'mtnVisited' => $meetinglist[$i]['mtnVisited'],
							'mtnVisitedDate' => $meetinglist[$i]['mtnVisitedDate'],
							'mtnVisitedTime' => $meetinglist[$i]['mtnVisitedTime'],
							'mtnVisitedLat' => $meetinglist[$i]['mtnVisitedLat'],
							'mtnVisitedLong' => $meetinglist[$i]['mtnVisitedLong'],
							'mtnVisitedMessage' => $meetinglist[$i]['mtnVisitedMessage'],
							'mtnRemarks' => $meetinglist[$i]['mtnRemarks'],
							'mtnRemarksDate' => $meetinglist[$i]['mtnRemarksDate'],
							'mtnRemarksTime' => $meetinglist[$i]['mtnRemarksTime'],
							'mtnRemarksLat' => $meetinglist[$i]['mtnRemarksLat'],
							'mtnRemarksLong' => $meetinglist[$i]['mtnRemarksLong'],
							'mtnRemarksMessage' => $meetinglist[$i]['mtnRemarksMessage'],
							'mtnSignature' => $meetinglist[$i]['mtnSignature'],
							'mtnSignatureDate' => $meetinglist[$i]['mtnSignatureDate'],
							'mtnSignatureTime' => $meetinglist[$i]['mtnSignatureTime'],
							'mtnSignatureLat' => $meetinglist[$i]['mtnSignatureLat'],
							'mtnSignatureLong' => $meetinglist[$i]['mtnSignatureLong'],
							'mtnSignatureImage' => base_url() . $meetinglist[$i]['mtnSignatureImage'],
							'mtnPicture' => $meetinglist[$i]['mtnPicture'],
							'mtnPictureDate' => $meetinglist[$i]['mtnPictureDate'],
							'mtnPictureTime' => $meetinglist[$i]['mtnPictureTime'],
							'mtnPictureLat' => $meetinglist[$i]['mtnPictureLat'],
							'mtnPictureLong' => $meetinglist[$i]['mtnPictureLong'],
							'mtnPictureImage' => base_url() . $meetinglist[$i]['mtnPictureImage'],
							'mtnNextVisit' => $meetinglist[$i]['mtnNextVisit'],
							'mtnCompleted' => $meetinglist[$i]['mtnCompleted'],
							'mtnParentName' => $meetinglist[$i]['parentName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Customer Meetings API (end) *************************************************//

	//******************************* Add Expense API (start) **********************************************//

	public function add_expense()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				if ($this->input->post('expImageStatus') == "true") {

					$pictureImage 		= 	$this->input->post('expImage');
					$pictureImageName 	=	$this->input->post('expImageName');

					$path 	=	"uploads/expenseimages/$pictureImageName.png";

					$params = 	array(
						'expImageStatus' 			=> 	$this->input->post('expImageStatus'),
						'expImageAvailable' 		=> 	$this->input->post('expImageAvailable'),
						'expImage' 					=> 	$path,
						'expDate' 					=> 	date("Y-m-d"),
						'expTitle' 					=> 	$this->input->post('expTitle'),
						'expAmount' 				=> 	$this->input->post('expAmount'),
						'expDescription' 			=> 	$this->input->post('expDescription'),
						'expIsMeetingAssociated' 	=> 	$this->input->post('expIsMeetingAssociated'),
						'expMeetingId' 				=> 	$this->input->post('expMeetingId'),
						'expParentId' 				=> 	$this->input->post('userId'),
						'expParentPath' 			=> 	$this->input->post('userParentPath'),
						'expStatusId' 				=> 	1,
						'expStatus' 				=> 	'active',
						'expCompleted' 				=> 	'no',
						'expPaymentStatus' 			=> 	'pending'
					);

					$expense_id = $this->Api_model->add_expense($params);

					if ($expense_id == 0) {
						$data = array(
							'status' 	=> 'failed',
							'message' 	=> 'Add Expense Failed'
						);
						$json = json_encode($data);
						echo $json;
					} else {
						file_put_contents($path, base64_decode($pictureImage));
						$data = array(
							'status' => 'success',
							'message' => 'Expense Added Successfully'
						);
						$json = json_encode($data);
						echo $json;
					}
				} else {

					$params = array(
						'expImageStatus' 			=>	$this->input->post('expImageStatus'),
						'expImageAvailable' 		=>	$this->input->post('expImageAvailable'),
						'expDate' 					=>	date("Y-m-d"),
						'expTitle' 					=>	$this->input->post('expTitle'),
						'expAmount' 				=>	$this->input->post('expAmount'),
						'expDescription' 			=>	$this->input->post('expDescription'),
						'expIsMeetingAssociated' 	=>	$this->input->post('expIsMeetingAssociated'),
						'expMeetingId' 				=>	$this->input->post('expMeetingId'),
						'expParentId' 				=>	$this->input->post('userId'),
						'expParentPath' 			=>	$this->input->post('userParentPath'),
						'expStatusId' 				=>	1,
						'expStatus' 				=>	'active',
						'expCompleted' 				=>	'no',
						'expPaymentStatus' 			=>	'pending'
					);

					$expense_id = $this->Api_model->add_expense($params);

					if ($expense_id == 0) {
						$data = array(
							'status' 	=> 	'failed',
							'message' 	=>	'Add Expense Failed'
						);
						$json = json_encode($data);
						echo $json;
					} else {
						$data = array(
							'status' => 'success',
							'message' => 'Expense Added Successfully'
						);
						$json = json_encode($data);
						echo $json;
					}
				}
			} else {
				$data = array(
					'status' 	=> 'failed',
					'message' 	=> 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Add Expense API (end) *********************************************//

	//*************************************** Get Expenses API (start) **************************************************//

	public function get_expenses()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'expParentPath' => $this->input->get('userParentPath')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$expenselist = $this->Api_model->get_expenses($params);

				$c = count($expenselist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						if ($expenselist[$i]['expIsMeetingAssociated'] == "yes") {

							$meetingdata = $this->Api_model->get_expense_meeting_data($expenselist[$i]['expMeetingId']);

							$data = array(
								'expId' => $expenselist[$i]['expId'],
								'expTitle' => $expenselist[$i]['expTitle'],
								'expAmount' => $expenselist[$i]['expAmount'],
								'expDescription' => $expenselist[$i]['expDescription'],
								'expImage' => base_url() . $expenselist[$i]['expImage'],
								'expImageStatus' => $expenselist[$i]['expImageStatus'],
								'expImageAvailable' => $expenselist[$i]['expImageAvailable'],
								'expIsMeetingAssociated' => $expenselist[$i]['expIsMeetingAssociated'],
								'expParentId' => $expenselist[$i]['expParentId'],
								'expParentPath' => $expenselist[$i]['expParentPath'],
								'expStatus' => $expenselist[$i]['expStatus'],
								'expMeetingName' => $meetingdata['mtnName'],
								'expMeetingCustomerName' => $meetingdata['cusFirstName'] . ' ' . $meetingdata['cusLastName'],
								'expPaymentStatus' => $expenselist[$i]['expPaymentStatus']
							);

							$json = json_encode($data);

							echo $json;
						} else {

							$data = array(
								'expId' => $expenselist[$i]['expId'],
								'expTitle' => $expenselist[$i]['expTitle'],
								'expAmount' => $expenselist[$i]['expAmount'],
								'expDescription' => $expenselist[$i]['expDescription'],
								'expImage' => base_url() . $expenselist[$i]['expImage'],
								'expImageStatus' => $expenselist[$i]['expImageStatus'],
								'expImageAvailable' => $expenselist[$i]['expImageAvailable'],
								'expIsMeetingAssociated' => $expenselist[$i]['expIsMeetingAssociated'],
								'expParentId' => $expenselist[$i]['expParentId'],
								'expParentPath' => $expenselist[$i]['expParentPath'],
								'expStatus' => $expenselist[$i]['expStatus'],
								'expMeetingName' => "",
								'expMeetingCustomerName' => "",
								'expPaymentStatus' => $expenselist[$i]['expPaymentStatus']
							);

							$json = json_encode($data);

							echo $json;
						}



						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Expenses API (end) **************************************************//


	//******************************* Update Expense API (start) **********************************************//

	public function update_expense()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				if ($this->input->post('expImageStatus') == "true") {

					$params_check = array(
						'expId' => $this->input->post('expId')
					);

					$expdata = $this->Api_model->get_expense_data($params_check);

					$oldImagePath = $expdata['expImage'];

					$pictureImage = $this->input->post('expImage');
					$pictureImageName = $this->input->post('expImageName');

					$path = "uploads/expenseimages/$pictureImageName.png";

					$params = array(
						'expImage' => $path,
						'expTitle' => $this->input->post('expTitle'),
						'expAmount' => $this->input->post('expAmount'),
						'expDescription' => $this->input->post('expDescription'),
						'expImageStatus' => $this->input->post('expImageStatus'),
						'expImageAvailable' => $this->input->post('expImageAvailable')
					);

					$expense_update = $this->Api_model->update_expense($this->input->post('expId'), $params);

					file_put_contents($path, base64_decode($pictureImage));

					if ($oldImagePath != null || $oldImagePath != "") {
						unlink($oldImagePath);
					}


					$data = array(
						'status' => 'success',
						'message' => 'Expense Added Successfully'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$params = array(
						'expTitle' => $this->input->post('expTitle'),
						'expAmount' => $this->input->post('expAmount'),
						'expDescription' => $this->input->post('expDescription')
					);

					$expense_update = $this->Api_model->update_expense($this->input->post('expId'), $params);

					$data = array(
						'status' => 'success',
						'message' => 'Expense Updated Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update Expense API (end) *********************************************//


	//*************************************** Update Expense Completed API (start) ****************************************//

	public function update_expense_completed()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'expCompleted' => $this->input->post('expCompleted'),
				);

				$update_expense_completed = $this->Api_model->update_expense_completed($this->input->post('expId'), $params);

				$data = array(
					'status' => 'success',
					'message' => 'Expense Completed Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Update Expense Completed API (end) *****************************************//


	//******************************* Delete Expense API (start) **********************************************//

	public function delete_expense()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'expId' => $this->input->post('expId')
				);

				$expdata = $this->Api_model->get_expense_data($params);

				$expImageStatus = $expdata['expImageStatus'];
				$expImagePath = $expdata['expImage'];

				$delete_expense = $this->Api_model->delete_expense($params);

				if ($expImageStatus == "true") {
					unlink($expImagePath);
				}

				$data = array(
					'status' => 'success',
					'message' => 'Deleted Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Delete Expense API (end) *********************************************//


	//*************************************** Add Customer Meeting API (start) **************************************************//

	public function add_customer_meeting()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params_meeting = array(
					'mtnCustomerId' => $this->input->post('mtnCustomerId'),
					'mtnUserId' => $this->input->post('userId'),
					'mtnName' => $this->input->post('mtnName'),
					'mtnMeetingTypeId' => $this->input->post('mtnMeetingTypeId'),
					'mtnMeetingType' => $this->input->post('mtnMeetingType'),
					'mtnDate' => $this->input->post('mtnDate'),
					'mtnTime' => $this->input->post('mtnTime'),
					'mtnNextVisit' => 'no',
					'mtnCompleted' => 'no',
					'mtnVisited' => 'no',
					'mtnSignature' => 'no',
					'mtnPicture' => 'no',
					'mtnRemarks' => 'no',
					'mtnParentId' => $this->input->post('userId'),
					'mtnParentPath' => $this->input->post('userParentPath')
				);

				/*
				$meeting_name_duplicate = $this->Api_model->get_meeting_name_duplicate($this->input->post('mtnName'));


				if($meeting_name_duplicate == true){

					$data = array(
						'status' => 'failed',
						'message' => 'Meeting Name already exist'
					);

					$json = json_encode($data);
					echo $json;

				}else{*/


				$meeting_id = $this->Api_model->add_meeting($params_meeting);


				if ($meeting_id == false) {

					$data = array(
						'status' => 'failed',
						'message' => 'Add Meeting Failed'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$data = array(
						'status' => 'success',
						'message' => 'Meeting Created Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}

				//}


			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Add Customer Meeting API (end) **************************************************//

	//*************************************** Add New Customer API (start) **************************************************//

	public function add_new_customer()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {


				$customerAddress = $this->input->post('cusAddress') . "," . $this->input->post('cusCity') . "-" . $this->input->post('cusPinCode') . "," . $this->input->post('cusState') . "," . $this->input->post('cusCountry');


				//$purl = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyDLzEQ6FcQtf9oZNBsDLf_trm_RCto_IJg&address=".urlencode($customerAddress).'&sensor=false';
				$purl = "https://maps.google.com/maps/api/geocode/json?key=" . GOOGLE_MAP_API_KEY . "&address=" . urlencode($customerAddress) . '&sensor=false';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $purl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				$response = curl_exec($ch);
				curl_close($ch);
				$response = json_decode($response);

				if ($response->status != 'OK') {

					$data = array(
						'status' => 'failed',
						'message' => 'Google Map Failed to Located Cutomer Address'
					);

					$json = json_encode($data);
					echo $json;
				} else {



					$plat = $response->results[0]->geometry->location->lat;
					$plong = $response->results[0]->geometry->location->lng;

					$params = array(
						'cusFirstName' => $this->input->post('cusFirstName'),
						'cusLastName' => $this->input->post('cusLastName'),
						'cusGenderId' => $this->input->post('cusGenderId'),
						'cusGender' => $this->input->post('cusGender'),
						'cusDOB' => $this->input->post('cusDOB'),
						'cusDOA' => $this->input->post('cusDOA'),
						'cusCustomerTypeId' => $this->input->post('cusCustomerTypeId'),
						'cusCustomerType' => $this->input->post('cusCustomerType'),
						'cusIndustryTypeId' => $this->input->post('cusIndustryTypeId'),
						'cusIndustryType' => $this->input->post('cusIndustryType'),
						'cusCompanyName' => $this->input->post('cusCompanyName'),
						'cusDepartment' => $this->input->post('cusDepartment'),
						'cusDesignation' => $this->input->post('cusDesignation'),
						'cusMobileNo' => $this->input->post('cusMobileNo'),
						'cusAlternateNo' => $this->input->post('cusAlternateNo'),
						'cusEmail' => $this->input->post('cusEmail'),
						'cusCountryId' => $this->input->post('cusCountryId'),
						'cusCountry' => $this->input->post('cusCountry'),
						'cusStateId' => $this->input->post('cusStateId'),
						'cusState' => $this->input->post('cusState'),
						'cusCityId' => $this->input->post('cusCityId'),
						'cusCity' => $this->input->post('cusCity'),
						'cusAddress' => $this->input->post('cusAddress'),
						'cusAddress2' => $this->input->post('cusAddress2'),
						'cusLandmark' => $this->input->post('cusLandmark'),
						'cusArea' => $this->input->post('cusArea'),
						'cusPinCode' => $this->input->post('cusPinCode'),
						'cusLat' => $plat,
						'cusLong' => $plong,
						'cusParentId' => $this->input->post('userId'),
						'cusManageId' => $this->input->post('userId'),
						'cusParentPath' => $this->input->post('userParentPath'),
						'cusStatusName' => 'active',
						'cusStatus' => 1
					);
					/*

					$customer_code_duplicate = $this->Api_model->get_customer_code_duplicate($params);

					if($customer_code_duplicate == true){

						$data = array(
							'status' => 'failed',
							'message' => 'Customer Code already exist'
						);

						$json = json_encode($data);
						echo $json;

					}else{*/


					$customer_duplicate = $this->Api_model->get_customer_duplicate($params);

					if ($customer_duplicate == true) {

						$data = array(
							'status' => 'failed',
							'message' => 'Customer Mobile No already exist'
						);

						$json = json_encode($data);
						echo $json;
					} else {

						/*
							$meeting_name_duplicate = $this->Api_model->get_meeting_name_duplicate($this->input->post('mtnName'));


							if($meeting_name_duplicate == true){

								$data = array(
									'status' => 'failed',
									'message' => 'Meeting Name already exist'
								);

								$json = json_encode($data);
								echo $json;

							}else{*/



						$customer_id = $this->Api_model->add_customer($params);


						if ($customer_id == false) {

							$data = array(
								'status' => 'failed',
								'message' => 'Add Customer Failed'
							);

							$json = json_encode($data);
							echo $json;
						} else {

							$data = array(
								'status' => 'success',
								'message' => 'Customer Added Successfully'
							);

							$json = json_encode($data);
							echo $json;
						}

						//}

					}

					//}

				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Add New Customer API (end) **************************************************//



	//*************************************** Update New Customer API (start) **************************************************//

	public function edit_new_customer()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {


				$customerAddress = $this->input->post('cusAddress') . "," . $this->input->post('cusCity') . "-" . $this->input->post('cusPinCode') . "," . $this->input->post('cusState') . "," . $this->input->post('cusCountry');


				// $purl = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyDLzEQ6FcQtf9oZNBsDLf_trm_RCto_IJg&address=".urlencode($customerAddress).'&sensor=false';
				$purl = "https://maps.google.com/maps/api/geocode/json?key=" . GOOGLE_MAP_API_KEY . "&address=" . urlencode($customerAddress) . '&sensor=false';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $purl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				$response = curl_exec($ch);
				curl_close($ch);
				$response = json_decode($response);

				if ($response->status != 'OK') {

					$data = array(
						'status' => 'failed',
						'message' => 'Google Map Failed to Located Cutomer Address'
					);

					$json = json_encode($data);
					echo $json;
				} else {



					$plat = $response->results[0]->geometry->location->lat;
					$plong = $response->results[0]->geometry->location->lng;

					$params = array(
						'cusFirstName' => $this->input->post('cusFirstName'),
						'cusLastName' => $this->input->post('cusLastName'),
						'cusGenderId' => $this->input->post('cusGenderId'),
						'cusGender' => $this->input->post('cusGender'),
						'cusDOB' => $this->input->post('cusDOB'),
						'cusDOA' => $this->input->post('cusDOA'),
						'cusCustomerTypeId' => $this->input->post('cusCustomerTypeId'),
						'cusCustomerType' => $this->input->post('cusCustomerType'),
						'cusIndustryTypeId' => $this->input->post('cusIndustryTypeId'),
						'cusIndustryType' => $this->input->post('cusIndustryType'),
						'cusCompanyName' => $this->input->post('cusCompanyName'),
						'cusDepartment' => $this->input->post('cusDepartment'),
						'cusDesignation' => $this->input->post('cusDesignation'),
						'cusMobileNo' => $this->input->post('cusMobileNo'),
						'cusAlternateNo' => $this->input->post('cusAlternateNo'),
						'cusEmail' => $this->input->post('cusEmail'),
						'cusCountryId' => $this->input->post('cusCountryId'),
						'cusCountry' => $this->input->post('cusCountry'),
						'cusStateId' => $this->input->post('cusStateId'),
						'cusState' => $this->input->post('cusState'),
						'cusCityId' => $this->input->post('cusCityId'),
						'cusCity' => $this->input->post('cusCity'),
						'cusAddress' => $this->input->post('cusAddress'),
						'cusLandmark' => $this->input->post('cusLandmark'),
						'cusArea' => $this->input->post('cusArea'),
						'cusPinCode' => $this->input->post('cusPinCode'),
						'cusLat' => $plat,
						'cusLong' => $plong,
						//'cusParentId' => $this->input->post('userId'),
						//'cusParentPath' => $this->input->post('userParentPath'),
						'cusStatusName' => 'active',
						'cusStatus' => 1
					);
					/*

					$customer_code_duplicate = $this->Api_model->get_customer_code_duplicate($params);

					if($customer_code_duplicate == true){

						$data = array(
							'status' => 'failed',
							'message' => 'Customer Code already exist'
						);

						$json = json_encode($data);
						echo $json;

					}else{*/


					$customer_duplicate = $this->Api_model->get_update_customer_duplicate($this->input->post('cusId'), $params);

					if ($customer_duplicate == true) {

						$data = array(
							'status' => 'failed',
							'message' => 'Customer Mobile No already exist'
						);

						$json = json_encode($data);
						echo $json;
					} else {

						/*
							$meeting_name_duplicate = $this->Api_model->get_meeting_name_duplicate($this->input->post('mtnName'));


							if($meeting_name_duplicate == true){

								$data = array(
									'status' => 'failed',
									'message' => 'Meeting Name already exist'
								);

								$json = json_encode($data);
								echo $json;

							}else{*/



						$customer_id = $this->Api_model->update_customer($this->input->post('cusId'), $params);


						$data = array(
							'status' => 'success',
							'message' => 'Customer Updated Successfully'
						);

						$json = json_encode($data);
						echo $json;




						//}

					}

					//}

				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Update New Customer API (end) **************************************************//


	//*************************************** Add Leave Request API (start) **************************************************//

	public function add_leave_request()
	{

		header('Content-Type: application/json');

		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = ['usrId' => $this->input->post('userId')];

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params_request = 	[
					'lreqSubject'          =>  $this->input->post('lreqSubject'),
					'lreqDescription'      =>  $this->input->post('lreqDescription'),
					'lreqStatusId'         =>  1,
					'lreqStatus'           =>  'pending',
					'lreqParentId'         =>  $this->input->post('userId'),
					'lreqDateFrom'         =>  $this->input->post('lreqDateFrom'),
					'lreqDateTo'           =>  $this->input->post('lreqDateTo'),
					'lreqParentPath'       =>  $this->input->post('userParentPath')
				];

				// print_r($params_request); die();

				$request_id = $this->Api_model->add_leave_request($params_request);

				if ($request_id == 0) {
					$data  =   ['status' => 'failed', 'message' => 'Add Leave Request Failed'];
				} else {
					$data = ['status' => 'success', 'message' => 'Leave Request Added Successfully'];
				}
			} else {
				$data = ['status' => 'failed', 'message' => 'User not authenticated'];
			}
			echo json_encode($data);
		}
	}

	//*************************************** Add Leave Request API (end) **************************************************//

	//*************************************** Get Leave Request API (start) **************************************************//

	public function get_leave_request()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'expParentPath' => $this->input->get('userParentPath')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$lreqlist = $this->Api_model->get_leave_request($params);

				$c = count($lreqlist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = [
							'lreqId'             =>  $lreqlist[$i]['lreqId'],
							'lreqSubject'        =>  $lreqlist[$i]['lreqSubject'],
							'lreqDescription'    =>  $lreqlist[$i]['lreqDescription'],
							'lreqStatus'         =>  $lreqlist[$i]['lreqStatus'],
							'lreqDateFrom'       =>  $lreqlist[$i]['lreqDateFrom'],
							'lreqDateTo'         =>  $lreqlist[$i]['lreqDateTo'],
							'lreqStatusMessage'  =>  $lreqlist[$i]['lreqStatusMessage']
						];

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Leave Request API (end) **************************************************//


	//******************************* Update Leave Request API (start) **********************************************//

	public function update_leave_request()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params_check = array(
					'lreqId' => $this->input->post('lreqId')
				);

				$Leave_request_data = $this->Api_model->get_leave_request_data($params_check);

				$leave_request_status = $Leave_request_data['lreqStatus'];

				if ($leave_request_status == 'accepted' || $leave_request_status == 'rejected') {

					$data = array(
						'status' => 'failed',
						'message' => 'Status is not pending, you can not edit'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$params = [
						'lreqSubject'     =>  $this->input->post('lreqSubject'),
						'lreqDateFrom'    =>  $this->input->post('lreqDateFrom'),
						'lreqDateTo'      =>  $this->input->post('lreqDateTo'),
						'lreqDescription' =>  $this->input->post('lreqDescription')
					];

					$leave_request_update = $this->Api_model->update_leave_request($this->input->post('lreqId'), $params);

					$data = array(
						'status'  =>  'success',
						'message' =>  'Leave Request Updated Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update Leave Request API (end) *********************************************//


	//******************************* Delete Leave Request API (start) **********************************************//

	public function delete_leave_request()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'lreqId' => $this->input->post('lreqId')
				);

				$Leave_request_data = $this->Api_model->get_leave_request_data($params);

				$leave_request_status = $Leave_request_data['lreqStatus'];

				if ($leave_request_status == 'accepted' || $leave_request_status == 'rejected') {

					$data = array(
						'status' => 'failed',
						'message' => 'Status is not pending, you can not delete'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$delete_leave_request = $this->Api_model->delete_leave_request($params);

					$data = array(
						'status' => 'success',
						'message' => 'Deleted Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Delete Leave Request API (end) *********************************************//


	//*************************************** Get User Login Record API (start) ********************************************//

	public function get_user_login_record()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$loginrecordlist = $this->Api_model->get_user_login_record_by_date($this->input->get('userId'), $this->input->get('fromDate'), $this->input->get('toDate'));

				$c = count($loginrecordlist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						if ($loginrecordlist[$i]['lgnrLogoutDate'] == "" || $loginrecordlist[$i]['lgnrLogoutDate'] == null) {
							$total_time = 'NA';
						} else {
							$date1 = new DateTime($loginrecordlist[$i]['lgnrLoginDate'] . 'T' . $loginrecordlist[$i]['lgnrLoginTime'] . ':00');
							$date2 = new DateTime($loginrecordlist[$i]['lgnrLogoutDate'] . 'T' . $loginrecordlist[$i]['lgnrLogoutTime'] . ':00');

							$diff = $date2->diff($date1);

							$hours = $diff->h;
							$minutes = $diff->format('%i');
							$hours = $hours + ($diff->days * 24);
							$total_time = $hours . ' hours and ' . $minutes . ' minutes';
						}

						$data = array(
							'lgnrSNo' => $i + 1,
							'lgnrUserName' => $loginrecordlist[$i]['usrUserName'],
							'lgnrLoginDate' => $loginrecordlist[$i]['lgnrLoginDate'],
							'lgnrLoginTime' => $loginrecordlist[$i]['lgnrLoginTime'],
							'lgnrLogoutDate' => $loginrecordlist[$i]['lgnrLogoutDate'],
							'lgnrLogoutTime' => $loginrecordlist[$i]['lgnrLogoutTime'],
							'lgnrTotalTime' => $total_time,
							'lgnrLoginStatus' => $loginrecordlist[$i]['lgnrLoginStatus']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get User Login Record API (end) **************************************************//


	//*************************************** Add Order API (start) **************************************************//

	public function add_order()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$order_duplicate = $this->Api_model->get_order_name_duplicate($this->input->post('ordName'));

				if ($order_duplicate == true) {

					$data = array(
						'status' => 'failed',
						'message' => 'Order Name Already Exist'
					);

					$json = json_encode($data);
					echo $json;
				} else {


					$params_request = array(
						'ordName' => $this->input->post('ordName'),
						'ordUnitId' => $this->input->post('ordUnitId'),
						'ordUnit' => $this->input->post('ordUnit'),
						'ordVenueId' => $this->input->post('ordVenueId'),
						'ordVenue' => $this->input->post('ordVenue'),
						'ordQuantity' => $this->input->post('ordQuantity'),
						'ordAmount' => $this->input->post('ordAmount'),
						'ordDescription' => $this->input->post('ordDescription'),
						'ordForDate' => $this->input->post('ordForDate'),
						'ordDate' => $this->input->post('ordDate'),
						'ordTime' => $this->input->post('ordTime'),
						'ordMeetingId' => $this->input->post('ordMeetingId'),
						'ordParentId' => $this->input->post('userId'),
						'ordParentPath' => $this->input->post('userParentPath'),
						'ordStatusId' => $this->input->post('ordStatusId'),
						'ordStatus' => $this->input->post('ordStatus')
					);


					$order_id = $this->Api_model->add_order($params_request);


					if ($order_id == false) {

						$data = array(
							'status' => 'failed',
							'message' => 'Add Order Failed'
						);

						$json = json_encode($data);
						echo $json;
					} else {

						$data = array(
							'status' => 'success',
							'message' => 'Order Added Successfully'
						);

						$json = json_encode($data);
						echo $json;
					}
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Add Order API (end) **************************************************//


	//*************************************** Get Orders API (start) **************************************************//

	public function get_orders()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$ordlist = $this->Api_model->get_orders($params);

				$c = count($ordlist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'ordId' => $ordlist[$i]['ordId'],
							'ordCode' => $ordlist[$i]['ordCode'],
							'ordName' => $ordlist[$i]['ordName'],
							'ordUnitId' => $ordlist[$i]['ordUnitId'],
							'ordUnit' => $ordlist[$i]['ordUnit'],
							'ordVenueId' => $ordlist[$i]['ordVenueId'],
							'ordVenue' => $ordlist[$i]['ordVenue'],
							'ordQuantity' => $ordlist[$i]['ordQuantity'],
							'ordAmount' => $ordlist[$i]['ordAmount'],
							'ordDescription' => $ordlist[$i]['ordDescription'],
							'ordForDate' => $ordlist[$i]['ordForDate'],
							'ordStatusId' => $ordlist[$i]['ordStatusId'],
							'ordStatus' => $ordlist[$i]['ordStatus'],
							'ordDate' => $ordlist[$i]['ordDate'],
							'ordTime' => $ordlist[$i]['ordTime'],
							'ordMeetingName' => $ordlist[$i]['mtnName'],
							'ordCustomerName' => $ordlist[$i]['cusFirstName'] . ' ' . $ordlist[$i]['cusLastName'],
							'ordCompanyName' => $ordlist[$i]['cusCompanyName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Orders API (end) **************************************************//


	//******************************* Update Order API (start) **********************************************//

	public function update_order()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$order_update_duplicate = $this->Api_model->get_update_order_name_duplicate($this->input->post('ordId'), $this->input->post('ordName'));

				if ($order_update_duplicate == true) {

					$data = array(
						'status' => 'failed',
						'message' => 'Order Name Already Exist'
					);

					$json = json_encode($data);
					echo $json;
				} else {


					$params = array(
						'ordName' => $this->input->post('ordName'),
						'ordUnitId' => $this->input->post('ordUnitId'),
						'ordUnit' => $this->input->post('ordUnit'),
						'ordVenueId' => $this->input->post('ordVenueId'),
						'ordVenue' => $this->input->post('ordVenue'),
						'ordQuantity' => $this->input->post('ordQuantity'),
						'ordAmount' => $this->input->post('ordAmount'),
						'ordDescription' => $this->input->post('ordDescription'),
						'ordForDate' => $this->input->post('ordForDate'),
						'ordStatusId' => $this->input->post('ordStatusId'),
						'ordStatus' => $this->input->post('ordStatus')
					);

					$order_update = $this->Api_model->update_order($this->input->post('ordId'), $params);

					$data = array(
						'status' => 'success',
						'message' => 'Order Updated Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update Order API (end) *********************************************//


	//******************************* Delete Order API (start) **********************************************//

	public function delete_order()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'ordId' => $this->input->post('ordId')
				);

				$delete_order = $this->Api_model->delete_order($params);

				$data = array(
					'status' => 'success',
					'message' => 'Deleted Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Delete Order API (end) *********************************************//


	//**************************** Get Visits & Order Analysis API (start) ********************************************//

	public function get_visits_and_orders_analysis_by_user()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {

				$current_year = $this->input->get('currentYear');
				$orderlist = $this->Api_model->get_all_user_orders_by_year($current_year, $this->input->get('userId'));
				$visitlist = $this->Api_model->get_all_user_visits_by_year($current_year, $this->input->get('userId'));

				$c = count($orderlist);
				$d = count($visitlist);

				$order_month[1]     =   $visit_month[1]     =   0;
				$order_month[2]     =   $visit_month[2]     =   0;
				$order_month[3]     =   $visit_month[3]     =   0;
				$order_month[4]     =   $visit_month[4]     =   0;
				$order_month[5]     =   $visit_month[5]     =   0;
				$order_month[6]     =   $visit_month[6]     =   0;
				$order_month[7]     =   $visit_month[7]     =   0;
				$order_month[8]     =   $visit_month[8]     =   0;
				$order_month[9]     =   $visit_month[9]     =   0;
				$order_month[10]    =   $visit_month[10]    =   0;
				$order_month[11]    =   $visit_month[11]    =   0;
				$order_month[12]    =   $visit_month[12]    =   0;


				for ($i = 0; $i < $c; $i++) {

					//$created_at = $orderlist[$i]['ordDate'];
					$created_at        = $orderlist[$k]['createdat'];
					$cr_time = strtotime($created_at);
					$cr_monthNumber = (int)date('m', $cr_time);
					$order_month[$cr_monthNumber] = $order_month[$cr_monthNumber] + 1;
				}


				for ($i = 0; $i < $d; $i++) {

					$created_at = $visitlist[$i]['mtnVisitedDate'];
					$cr_time = strtotime($created_at);
					$cr_monthNumber = (int)date('m', $cr_time);
					$visit_month[$cr_monthNumber] = $visit_month[$cr_monthNumber] + 1;
				}


				$data = array(

					'status' => 'success',
					'message' => 'Data Loaded Successfully',

					'order_JAN' => $order_month[1],
					'order_FEB' => $order_month[2],
					'order_MAR' => $order_month[3],
					'order_APR' => $order_month[4],
					'order_MAY' => $order_month[5],
					'order_JUN' => $order_month[6],
					'order_JUL' => $order_month[7],
					'order_AUG' => $order_month[8],
					'order_SEP' => $order_month[9],
					'order_OCT' => $order_month[10],
					'order_NOV' => $order_month[11],
					'order_DEC' => $order_month[12],

					'visit_JAN' => $visit_month[1],
					'visit_FEB' => $visit_month[2],
					'visit_MAR' => $visit_month[3],
					'visit_APR' => $visit_month[4],
					'visit_MAY' => $visit_month[5],
					'visit_JUN' => $visit_month[6],
					'visit_JUL' => $visit_month[7],
					'visit_AUG' => $visit_month[8],
					'visit_SEP' => $visit_month[9],
					'visit_OCT' => $visit_month[10],
					'visit_NOV' => $visit_month[11],
					'visit_DEC' => $visit_month[12],
				);

				$json = json_encode($data);

				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************** Get Visits & Order Analysis API (end) ***************************************//


	//**************************** Get Order Amount Analysis API (start) ********************************************//

	public function get_order_amount_analysis_by_user()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {

				$current_year = $this->input->get('currentYear');
				$orderamountlist = $this->Api_model->get_all_user_order_amount_by_year($current_year, $this->input->get('userId'));

				$c = count($orderamountlist);

				$order_amount_month[1] = 0;
				$order_amount_month[2] = 0;
				$order_amount_month[3] = 0;
				$order_amount_month[4] = 0;
				$order_amount_month[5] = 0;
				$order_amount_month[6] = 0;
				$order_amount_month[7] = 0;
				$order_amount_month[8] = 0;
				$order_amount_month[9] = 0;
				$order_amount_month[10] = 0;
				$order_amount_month[11] = 0;
				$order_amount_month[12] = 0;


				for ($i = 0; $i < $c; $i++) {

					$created_at = $orderamountlist[$i]['ordDate'];
					$amount = $orderamountlist[$i]['ordAmount'];
					$cr_time = strtotime($created_at);
					$cr_monthNumber = (int)date('m', $cr_time);
					$order_amount_month[$cr_monthNumber] = $order_amount_month[$cr_monthNumber] + $amount;
				}


				$data = array(

					'status' => 'success',
					'message' => 'Data Loaded Successfully',

					'order_amount_JAN' => $order_amount_month[1],
					'order_amount_FEB' => $order_amount_month[2],
					'order_amount_MAR' => $order_amount_month[3],
					'order_amount_APR' => $order_amount_month[4],
					'order_amount_MAY' => $order_amount_month[5],
					'order_amount_JUN' => $order_amount_month[6],
					'order_amount_JUL' => $order_amount_month[7],
					'order_amount_AUG' => $order_amount_month[8],
					'order_amount_SEP' => $order_amount_month[9],
					'order_amount_OCT' => $order_amount_month[10],
					'order_amount_NOV' => $order_amount_month[11],
					'order_amount_DEC' => $order_amount_month[12]
				);

				$json = json_encode($data);

				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************** Get Order Amount Analysis API (end) ***************************************//

	//**************************** Get Visit Order Pie Analysis API (start) ********************************************//

	public function get_visit_order_pie_analysis_by_user()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {

				$current_year = $this->input->get('currentYear');
				$orderlist = $this->Api_model->get_all_user_orders_by_year($current_year, $this->input->get('userId'));
				$visitlist = $this->Api_model->get_all_user_visits_by_year($current_year, $this->input->get('userId'));
				$orderamountlist = $this->Api_model->get_all_user_order_amount_by_year($current_year, $this->input->get('userId'));

				$c = count($orderlist);
				$d = count($visitlist);
				$e = count($orderamountlist);

				$order_amount_total = $order_number_total = $visit_number_total = 0;

				for ($i = 0; $i < $c; $i++) {
					$created_at = $orderlist[$i]['ordDate'];
					$order_number_total = $order_number_total + 1;
				}


				for ($i = 0; $i < $d; $i++) {
					$created_at = $visitlist[$i]['mtnVisitedDate'];
					$visit_number_total = $visit_number_total + 1;
				}


				for ($i = 0; $i < $e; $i++) {
					$amount = $orderamountlist[$i]['ordAmount'];
					$order_amount_total = $order_amount_total + $amount;
				}


				$data = array(

					'status' => 'success',
					'message' => 'Data Loaded Successfully',

					'order_number_TOTAL' => $order_number_total,
					'visit_number_TOTAL' => $visit_number_total,
					'order_amount_TOTAL' => $order_amount_total
				);

				$json = json_encode($data);

				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************** Get Visit Order Pie Analysis API (end) ***************************************//

	//**************************** Get Visit Order List Analysis API (start) ********************************************//

	public function get_visit_order_list_analysis_by_user()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {

				$current_year = $this->input->get('currentYear');
				$visitlist = $this->Api_model->get_all_user_visits_by_year($current_year, $this->input->get('userId'));
				$orderlist = $this->Api_model->get_all_user_orders_by_year($current_year, $this->input->get('userId'));
				$orderamountlist = $this->Api_model->get_all_user_order_amount_by_year($current_year, $this->input->get('userId'));

				$c = count($visitlist);
				$d = count($orderlist);
				$e = count($orderamountlist);

				$visit_month[1] = $order_month[1] = $order_amount_month[1] = 0;
				$visit_month[2] = $order_month[2] = $order_amount_month[2] = 0;
				$visit_month[3] = $order_month[3] = $order_amount_month[3] = 0;
				$visit_month[4] = $order_month[4] = $order_amount_month[4] = 0;
				$visit_month[5] = $order_month[5] = $order_amount_month[5] = 0;
				$visit_month[6] = $order_month[6] = $order_amount_month[6] = 0;
				$visit_month[7] = $order_month[7] = $order_amount_month[7] = 0;
				$visit_month[8] = $order_month[8] = $order_amount_month[8] = 0;
				$visit_month[9] = $order_month[9] = $order_amount_month[9] = 0;
				$visit_month[10] = $order_month[10] = $order_amount_month[10] = 0;
				$visit_month[11] = $order_month[11] = $order_amount_month[11] = 0;
				$visit_month[12] = $order_month[12] = $order_amount_month[12] = 0;

				echo '{"status":"success","message":"Data available", "data":[';

				for ($j = 0; $j < 3; $j++) {

					if ($j == 0) {

						for ($i = 0; $i < $c; $i++) {

							$created_at = $visitlist[$i]['mtnVisitedDate'];
							$cr_time = strtotime($created_at);
							$cr_monthNumber = (int)date('m', $cr_time);
							$visit_month[$cr_monthNumber] = $visit_month[$cr_monthNumber] + 1;
						}

						$data = array(
							'perId' => '1',
							'perName' => 'visit',
							'perJAN' => $visit_month[1],
							'perFEB' => $visit_month[2],
							'perMAR' => $visit_month[3],
							'perAPR' => $visit_month[4],
							'perMAY' => $visit_month[5],
							'perJUN' => $visit_month[6],
							'perJUL' => $visit_month[7],
							'perAUG' => $visit_month[8],
							'perSEP' => $visit_month[9],
							'perOCT' => $visit_month[10],
							'perNOV' => $visit_month[11],
							'perDEC' => $visit_month[12]
						);

						$json = json_encode($data);

						echo $json;

						echo ",";
					} else if ($j == 1) {

						for ($i = 0; $i < $d; $i++) {

							$created_at = $orderlist[$i]['ordDate'];
							$cr_time = strtotime($created_at);
							$cr_monthNumber = (int)date('m', $cr_time);
							$order_month[$cr_monthNumber] = $order_month[$cr_monthNumber] + 1;
						}

						$data = array(
							'perId' => '2',
							'perName' => 'order',
							'perJAN' => $order_month[1],
							'perFEB' => $order_month[2],
							'perMAR' => $order_month[3],
							'perAPR' => $order_month[4],
							'perMAY' => $order_month[5],
							'perJUN' => $order_month[6],
							'perJUL' => $order_month[7],
							'perAUG' => $order_month[8],
							'perSEP' => $order_month[9],
							'perOCT' => $order_month[10],
							'perNOV' => $order_month[11],
							'perDEC' => $order_month[12]
						);

						$json = json_encode($data);

						echo $json;

						echo ",";
					} else {

						for ($i = 0; $i < $e; $i++) {

							$created_at = $orderamountlist[$i]['ordDate'];
							$amount = $orderamountlist[$i]['ordAmount'];
							$cr_time = strtotime($created_at);
							$cr_monthNumber = (int)date('m', $cr_time);
							$order_amount_month[$cr_monthNumber] = $order_amount_month[$cr_monthNumber] + $amount;
						}


						$data = array(
							'perId' => '3',
							'perName' => 'order_amount',
							'perJAN' => $order_amount_month[1],
							'perFEB' => $order_amount_month[2],
							'perMAR' => $order_amount_month[3],
							'perAPR' => $order_amount_month[4],
							'perMAY' => $order_amount_month[5],
							'perJUN' => $order_amount_month[6],
							'perJUL' => $order_amount_month[7],
							'perAUG' => $order_amount_month[8],
							'perSEP' => $order_amount_month[9],
							'perOCT' => $order_amount_month[10],
							'perNOV' => $order_amount_month[11],
							'perDEC' => $order_amount_month[12]
						);

						$json = json_encode($data);

						echo $json;
					}
				}

				echo ']}';
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************** Get Visit Order List Analysis API (end) ***************************************//

	//*************************************** Add Contact API (start) **************************************************//

	public function add_contact()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {


				$contact_duplicate = $this->Api_model->get_contact_no_duplicate($this->input->post('userId'), $this->input->post('cntContactNo'));

				if ($contact_duplicate == true) {

					$data = array(
						'status' => 'failed',
						'message' => 'Contact No Already Exist'
					);

					$json = json_encode($data);
					echo $json;
				} else {


					$params_request = array(
						'cntPersonName' => $this->input->post('cntPersonName'),
						'cntContactNo' => $this->input->post('cntContactNo'),
						'cntAddress' => $this->input->post('cntAddress'),
						'cntOtherDetails' => $this->input->post('cntOtherDetails'),
						'cntParentId' => $this->input->post('userId'),
						'cntParentPath' => $this->input->post('userParentPath'),
						'cntStatusId' => 1,
						'cntStatus' => 'active'
					);


					$order_id = $this->Api_model->add_contact($params_request);


					if ($order_id == 0) {

						$data = array(
							'status' => 'failed',
							'message' => 'Add Contact Failed'
						);

						$json = json_encode($data);
						echo $json;
					} else {

						$data = array(
							'status' => 'success',
							'message' => 'Contact Added Successfully'
						);

						$json = json_encode($data);
						echo $json;
					}
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Add Contact API (end) **************************************************//

	//*************************************** Get Contacts API (start) **************************************************//

	public function get_contacts()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$contactlist = $this->Api_model->get_contacts($params);

				$c = count($contactlist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'cntId' => $contactlist[$i]['cntId'],
							'cntPersonName' => $contactlist[$i]['cntPersonName'],
							'cntContactNo' => $contactlist[$i]['cntContactNo'],
							'cntAddress' => $contactlist[$i]['cntAddress'],
							'cntOtherDetails' => $contactlist[$i]['cntOtherDetails'],
							'cntStatus' => $contactlist[$i]['cntStatus']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Contacts API (end) **************************************************//

	//******************************* Delete Contact API (start) **********************************************//

	public function delete_contact()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'cntId' => $this->input->post('cntId')
				);

				$delete_contact = $this->Api_model->delete_contact($params);

				$data = array(
					'status' => 'success',
					'message' => 'Deleted Successfully'
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Delete Contact API (end) *********************************************//

	//******************************* Update Contact API (start) **********************************************//

	public function update_contact()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$contact_update_duplicate = $this->Api_model->get_update_contact_no_duplicate($this->input->post('userId'), $this->input->post('cntId'), $this->input->post('cntContactNo'));

				if ($contact_update_duplicate == true) {

					$data = array(
						'status' => 'failed',
						'message' => 'Contact No Already Exist'
					);

					$json = json_encode($data);
					echo $json;
				} else {


					$params = array(
						'cntPersonName' => $this->input->post('cntPersonName'),
						'cntContactNo' => $this->input->post('cntContactNo'),
						'cntAddress' => $this->input->post('cntAddress'),
						'cntOtherDetails' => $this->input->post('cntOtherDetails')
					);

					$order_update = $this->Api_model->update_contact($this->input->post('cntId'), $params);

					$data = array(
						'status' => 'success',
						'message' => 'Contact Updated Successfully'
					);

					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update Contact API (end) *********************************************//

	//******************************* Update User Password API (start) **********************************************//

	public function update_user_password()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params_check = array(
					'usrPassword' => $this->input->post('userCurrentPassword')
				);

				$user_password_check = $this->Api_model->check_user_password($this->input->post('userId'), $params_check);

				if ($user_password_check == true) {

					$params = array(
						'usrPassword' => $this->input->post('userNewPassword')
					);

					$update_user_password = $this->Api_model->update_user_password($this->input->post('userId'), $params);

					$data = array(
						'status' => 'success',
						'message' => 'Password Updated Successfully'
					);

					$json = json_encode($data);
					echo $json;
				} else {

					$data = array(
						'status' => 'failed',
						'message' => 'Current Password is Wrong'
					);

					$json = json_encode($data);
					echo $json;
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update User Password API (end) *********************************************//

	//*************************************** Get User Units API (start) **************************************************//

	public function get_user_units()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'usrParentId' => $this->input->get('userParentId'),
				'usrParentPath' => $this->input->get('userParentPath')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$unitlist = $this->Api_model->get_user_units($this->input->get('userParentId'));

				$c = count($unitlist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'untId' => $unitlist[$i]['untId'],
							'untName' => $unitlist[$i]['untName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get User Units API (end) **************************************************//

	//*************************************** Get Venues by Unit API (start) **************************************************//

	public function get_venues_by_unit()
	{

		header('Content-Type: application/json');
		if ($this->input->get('userId', TRUE)) {

			$params = array(
				'usrId' => $this->input->get('userId'),
				'usrParentId' => $this->input->get('userParentId'),
				'usrParentPath' => $this->input->get('userParentPath'),
				'usrUnitId' => $this->input->get('userUnitId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params);

			if ($authenticate_user == true) {
				$venuelist = $this->Api_model->get_venues_by_unit($this->input->get('userUnitId'));

				$c = count($venuelist);

				if ($c < 1) {

					$data = array(
						'status' => 'failed',
						'message' => 'Data not available'
					);
					$json = json_encode($data);
					echo $json;
				} else {

					echo '{"status":"success","message":"Data available", "data":[';

					for ($i = 0; $i < $c; $i++) {

						$data = array(
							'venId' => $venuelist[$i]['venId'],
							'venShortName' => $venuelist[$i]['venShortName'],
							'venFullName' => $venuelist[$i]['venFullName']
						);

						$json = json_encode($data);

						echo $json;

						if ($c - 1 != $i) {
							echo ",";
						}
					}

					echo ']}';
				}
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//*************************************** Get Venues by Unit API (end) **************************************************//

	//******************************* Update User Push Token API (start) **********************************************//

	public function update_user_push_token()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$params = array(
					'usrPushToken' => $this->input->post('userPushToken')
				);

				$update_user_password = $this->Api_model->update_user_push_token($this->input->post('userId'), $params);

				$data = array(
					'status' => 'success',
					'message' => 'Password Updated Successfully',
					'userPushToken' => $this->input->post('userPushToken')
				);

				$json = json_encode($data);
				echo $json;
			} else {

				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);

				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Update User Push Token API (end) *********************************************//

	//******************************* Check App Version API (start) **********************************************//

	public function check_app_version()
	{

		header('Content-Type: application/json');
		if ($this->input->post('userId', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('userId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {

				$current_app_version = 6;
				$user_app_version = $this->input->post('userAppVersion');

				if ($user_app_version < $current_app_version) {

					$data = array(
						'status' => 'success',
						'updateStatus' => 'required',
						'message' => 'Update Required',
						'updateMainURL' => 'https://play.google.com/store/apps/details?id=apps.lnsel.com.salesmanagement',
						'updateStoreURL' => 'market://details?id=apps.lnsel.com.salesmanagement'
					);

					$json = json_encode($data);
					echo $json;
				} else {
					$data = array(
						'status' => 'success',
						'updateStatus' => 'not_required',
						'message' => 'Update not Required'
					);
					$json = json_encode($data);
					echo $json;
				}
			} else {
				$data = array(
					'status' => 'failed',
					'message' => 'User not authenticated'
				);
				$json = json_encode($data);
				echo $json;
			}
		}
	}

	//********************************** Check App Version API (end) *********************************************//

	//******************************* Activity API (start) **********************************************//

	public function add_activity()
	{

		header('Content-Type: application/json');
		if ($this->input->post('user_id', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('user_id')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {
				if ($this->input->post('activityImageStatus') == "true") {

					$pictureImage 		= 	$this->input->post('activityImage');
					$pictureImageName 	=	rand() . $this->input->post('activityImageName');

					$path 	=	"uploads/activityimages/$pictureImageName.png";

					file_put_contents($path, base64_decode($pictureImage));

					$params = 	[
						'user_id' 				=> 	$this->input->post('user_id'),
						'activityTitle' 		=> 	$this->input->post('activityTitle'),
						'activity' 				=> 	$this->input->post('activity'),
						'activityImageStatus' 	=> 	$this->input->post('activityImageStatus'),
						'activityImage' 		=> 	$path,
						'activityStatus' 		=> 	$this->input->post('activityStatus'),
					];

					$expense_id = $this->Api_model->add_activity($params);

					if ($expense_id == 0) {
						$data = [
							'status' 	=> 'failed',
							'message' 	=> 'Add Activity Failed'
						];
						echo json_encode($data);
					} else {
						$data = [
							'status' 	=> 'success',
							'message' 	=> 'Activity Added Successfully'
						];
						echo json_encode($data);
					}
				} else {
					$params = 	[
						'user_id' 				=> 	$this->input->post('user_id'),
						'activityTitle' 		=> 	$this->input->post('activityTitle'),
						'activity' 				=> 	$this->input->post('activity'),
						'activityImageStatus' 	=> 	$this->input->post('activityImageStatus'),
						'activityImage' 		=> 	'NA',
						'activityStatus' 		=> 	$this->input->post('activityStatus'),
					];

					$expense_id = $this->Api_model->add_activity($params);

					if ($expense_id == 0) {
						$data = array(
							'status' 	=> 	'failed',
							'message' 	=>	'Add Activity Failed'
						);
						echo json_encode($data);
					} else {
						$data = array(
							'status' 	=> 'success',
							'message' 	=> 'Activity Added Successfully'
						);
						echo json_encode($data);
					}
				}
			} else {
				$data = array(
					'status' 	=> 'failed',
					'message' 	=> 'User not authenticated'
				);
				echo json_encode($data);
			}
		} else {
			$data = [
				'status' 	=> 'failed',
				'message' 	=> 'user id missing'
			];
			echo json_encode($data);
		}
	}

	public function update_activity()
	{

		header('Content-Type: application/json');
		if ($this->input->post('user_id', TRUE)) {

			$params_authenticate = [
				'usrId' => $this->input->post('user_id')
			];

			$authenticate_user	=	$this->Api_model->authenticate_user($params_authenticate);

			$activityId		=	$this->input->post('activity_id');

			if ($authenticate_user == true) {
				if ($this->input->post('activityImageStatus') == "true") {

					$pictureImage 		= 	$this->input->post('activityImage');
					$pictureImageName 	=	$this->input->post('activityImageName');

					$path 	=	"uploads/activityimages/$pictureImageName.png";

					file_put_contents($path, base64_decode($pictureImage));

					$params = 	[
						'user_id' 				=> 	$this->input->post('user_id'),
						'activityTitle' 		=> 	$this->input->post('activityTitle'),
						'activity' 				=> 	$this->input->post('activity'),
						'activityImageStatus' 	=> 	$this->input->post('activityImageStatus'),
						'activityImage' 		=> 	$path,
						'activityStatus' 		=> 	$this->input->post('activityStatus'),
					];

					$expense_id = $this->Api_model->update_activity_data($activityId, $params);

					if ($expense_id == 0) {
						$data = [
							'status' 	=> 'failed',
							'message' 	=> 'Update Activity Failed'
						];
						echo json_encode($data);
					} else {
						$data = [
							'status' 	=> 'success',
							'message' 	=> 'Activity Updated Successfully'
						];
						echo json_encode($data);
					}
				} else {
					$params = 	[
						'user_id' 				=> 	$this->input->post('user_id'),
						'activityTitle' 		=> 	$this->input->post('activityTitle'),
						'activity' 				=> 	$this->input->post('activity'),
						'activityImageStatus' 	=> 	$this->input->post('activityImageStatus'),
						'activityStatus' 		=> 	$this->input->post('activityStatus'),
					];

					$expense_id = $this->Api_model->update_activity_data($activityId, $params);

					if ($expense_id == 0) {
						$data = [
							'status' 	=> 	'failed',
							'message' 	=>	'Update Activity Failed'
						];
						echo json_encode($data);
					} else {
						$data = [
							'status' 	=> 'success',
							'message' 	=> 'Activity Updated Successfully'
						];
						echo json_encode($data);
					}
				}
			} else {
				$data = [
					'status' 	=> 'failed',
					'message' 	=> 'User not authenticated'
				];
				echo json_encode($data);
			}
		} else {
			$data = [
				'status' 	=> 'failed',
				'message' 	=> 'user id missing'
			];
			echo json_encode($data);
		}
	}

	public function delete_activity()
	{
		header('Content-Type: application/json');
		if ($this->input->post('user_id', TRUE)) {

			$params_authenticate = [
				'usrId' => $this->input->post('user_id')
			];

			$authenticate_user	=	$this->Api_model->authenticate_user($params_authenticate);

			$activity 			=	['activity_id'	=>	$this->input->post('activity_id')];

			if ($authenticate_user == true) {
				$delete_activity 	=	$this->Api_model->delete_activity_data($activity);
				if ($delete_activity == false) {
					$data = [
						'status' 	=> 	'failed',
						'message' 	=>	'Delete Activity Failed'
					];
					echo json_encode($data);
				} else {
					$data = [
						'status' 	=> 'success',
						'message' 	=> 'Activity Deleted Successfully'
					];
					echo json_encode($data);
				}
			} else {
				$data = [
					'status' 	=> 'failed',
					'message' 	=> 'User not authenticated'
				];
				echo json_encode($data);
			}
		} else {
			$data = [
				'status' 	=> 'failed',
				'message' 	=> 'user id missing'
			];
			echo json_encode($data);
		}
	}

	public function get_activity_list()
	{
		header('Content-Type: application/json');
		if ($this->input->post('user_id', TRUE)) {

			$params_authenticate = array(
				'usrId' => $this->input->post('user_id')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if ($authenticate_user == true) {
				$activityList 	=	$this->Api_model->fetch_activity_list($params_authenticate['usrId']);
				$data 	=	[
					'status' 		=> 	'success',
					'activitylist' 	=>	$activityList
				];
				echo json_encode($data);
			} else {
				$data 	= 	[
					'status' 	=> 'failed',
					'message' 	=> 'User not authenticated'
				];
				echo json_encode($data);
			}
		}
	}

	//********************************** Activity (end) *********************************************//
}
