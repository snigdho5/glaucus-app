<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meetingmanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Meeting_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/meeting/meeting');

		}else{
			redirect('/login');
		}
		
	}

	public function add()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			if($this->input->get('id')==null){

				redirect('/meetingmanagement');
			}else{

				$this->load->view('include/dashboard_header');
				$this->load->view('management/meeting/addmeeting');	
			}



		}else{
			redirect('/login');
		}
	}


	public function edit()
	{
		if (isset($this->session->userdata['logged_web_user'])){
			$this->load->view('include/dashboard_header');
			$this->load->view('management/meeting/editmeeting');
		}else{
			redirect('/login');
		}
	}



	public function add_meeting()
	{

		require_once(APPPATH . "/libraries/push_notification/firebase.php");
		require_once(APPPATH . "/libraries/push_notification/push.php");

		if($this->input->post('mtnDate',TRUE)){
			$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
			$params = array(
				'mtnCustomerId' => $this->input->post('mtnCustomerId'),
				'mtnUserId' => $this->input->post('mtnUserId'),
				'mtnName' => $this->input->post('mtnName'),
				'mtnDate' => $this->input->post('mtnDate'),
				'mtnTime' => $this->input->post('mtnTime'),
				'mtnMeetingType' => $this->input->post('mtnMeetingType'),
				'mtnMeetingTypeId' => $this->input->post('mtnMeetingTypeId'),
				'mtnTelecallerId' => $this->input->post('mtnTelecallerId'),
				'mtnComments' => $this->input->post('mtnComments'),
				'mtnNextVisit' => 'no',
				'mtnCompleted' => 'no',
				'mtnVisited' => 'no',
				'mtnSignature' => 'no',
				'mtnPicture' => 'no',
				'mtnRemarks' => 'no',
				'mtnParentId' => $this->session->userdata['logged_web_user']['userId'],
				'mtnParentPath' => $parent_path
			);

/*
			$meeting_name_duplicate = $this->Meeting_model->get_meeting_name_duplicate($params);

			if($meeting_name_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Meeting Name Already exist"
				}';

			}else{*/

				$meeting_id = $this->Meeting_model->add_meeting($params);


				if($meeting_id == false){

					echo '{
						"status":"failed",
						"message":"Add Data Failed"
					}';

				}else{

					$user_params = array(
						'usrId' => $this->input->post('mtnUserId')
					);

					$userdata = $this->Meeting_model->get_user_data($user_params);

					if($userdata['usrPushToken']!=""){

						$admin_params = array(
							'usrId' => $this->session->userdata['logged_web_user']['userId']
						);

						$admindata = $this->Meeting_model->get_user_data($admin_params);

				        $firebase = new Firebase();
				        $push = new Push();
				 
				        $payload = array();

						$payload['userName'] = $userdata['usrUserName'];
						$payload['adminName'] = $admindata['usrUserName'];
						$payload['mtnName'] = $this->input->post('mtnName');
						$payload['mtnDate'] = $this->input->post('mtnDate');
						$payload['mtnTime'] = $this->input->post('mtnTime');
						$payload['mtnId'] = $meeting_id;
						$message = $admindata['usrUserName']." assigned Meeting for Date: ".$this->input->post('mtnDate')." and Time: ".$this->input->post('mtnTime');
						$details = $admindata['usrUserName']." assigned Meeting for Date: ".$this->input->post('mtnDate')." and Time: ".$this->input->post('mtnTime');

						$push->setTitle($this->input->post('mtnName'));
						$push->setMessage($message);
						$push->setDetails($details);
						$push->setImage('');
						$push->setIsBackground(FALSE);
						$push->setPayload($payload);
						$push->setType("notification");

						$json = $push->getPush();
						$regId = $userdata['usrPushToken'];
						$response = $firebase->send($regId, $json);

					}



					echo '{
						"status":"success",
						"message":"Data Successfully Added"
					}';

				}

			//}




		}

	}



	public function update_meeting()
	{
		if($this->input->post('mtnId',TRUE)){
			$params = array(
				'mtnUserId' => $this->input->post('mtnUserId'),
				'mtnName' => $this->input->post('mtnName'),
				'mtnDate' => $this->input->post('mtnDate'),
				'mtnTime' => $this->input->post('mtnTime'),
				'mtnMeetingType' => $this->input->post('mtnMeetingType'),
				'mtnMeetingTypeId' => $this->input->post('mtnMeetingTypeId'),
				'mtnTelecallerId' => $this->input->post('mtnTelecallerId'),
				'mtnComments' => $this->input->post('mtnComments')
			);
/*
			$meeting_name_duplicate = $this->Meeting_model->get_update_meeting_name_duplicate($this->input->post('mtnId'), $params);

			if($meeting_name_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Meeting Name Already exist"
				}';

			}else{*/

				$customer_id = $this->Meeting_model->update_meeting($this->input->post('mtnId'), $params);
				echo '{
					"status":"success",
					"message":"Meeting Updated Successfully"
				}';

			//}



		}

	}


	public function get_all_meetings()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$meetinglist= $this->Meeting_model->get_all_meetings($parent_path);

		$c=count($meetinglist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'mtnId' => $meetinglist[$i]['mtnId'],
				'mtnName' => $meetinglist[$i]['mtnName'],
				'mtnCustomerId' => $meetinglist[$i]['mtnCustomerId'],
				'mtnCustomerName' => $meetinglist[$i]['cusFirstName'].' '.$meetinglist[$i]['cusLastName'],
				'mtnUserId' => $meetinglist[$i]['mtnUserId'],
				'mtnUserName' => $meetinglist[$i]['userName'],
				'mtnDate' => $meetinglist[$i]['mtnDate'],
				'mtnTime' => $meetinglist[$i]['mtnTime'],
				'mtnMeetingTypeId' => $meetinglist[$i]['mtnMeetingTypeId'],
				'mtnMeetingType' => $meetinglist[$i]['mtnMeetingType'],
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
				'mtnSignatureImage' => base_url().$meetinglist[$i]['mtnSignatureImage'],
				'mtnPicture' => $meetinglist[$i]['mtnPicture'],
				'mtnPictureDate' => $meetinglist[$i]['mtnPictureDate'],
				'mtnPictureTime' => $meetinglist[$i]['mtnPictureTime'],
				'mtnPictureLat' => $meetinglist[$i]['mtnPictureLat'],
				'mtnPictureLong' => $meetinglist[$i]['mtnPictureLong'],
				'mtnPictureImage' => base_url().$meetinglist[$i]['mtnPictureImage'],
				'mtnNextVisit' => $meetinglist[$i]['mtnNextVisit'],
				'mtnCompleted' => $meetinglist[$i]['mtnCompleted'],
				'mtnParentName' => $meetinglist[$i]['parentName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



	public function get_meeting_detail(){

		header('Content-Type: application/json');

		$params = array(
				'mtnId' => $this->input->get('id')
			);

		$meetingdata = $this->Meeting_model->get_meeting_data($params);

		$data = array(
				'mtnId' => $meetingdata['mtnId'],
				'mtnCustomerId' => $meetingdata['mtnCustomerId'],
				'mtnCustomerFirstName' => $meetingdata['cusFirstName'],
				'mtnCustomerLastName' => $meetingdata['cusLastName'],
				'mtnUserId' => $meetingdata['mtnUserId'],
				'mtnName' => $meetingdata['mtnName'],
				'mtnDate' => $meetingdata['mtnDate'],
				'mtnTime' => $meetingdata['mtnTime'],
				'mtnMeetingTypeId' => $meetingdata['mtnMeetingTypeId'],
				'mtnMeetingType' => $meetingdata['mtnMeetingType'],
				'mtnComments' => $meetingdata['mtnComments'],
				'mtnTelecallerId' => $meetingdata['mtnTelecallerId']
			);

			$json = json_encode($data);

			echo $json;

	}


	public function get_all_meeting_types()
	{
		header('Content-Type: application/json');
		$mtntlist= $this->Meeting_model->get_all_meeting_types();

		$c=count($mtntlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'mtntId' => $mtntlist[$i]['mtntId'],
				'mtntName' => $mtntlist[$i]['mtntName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_admin_users()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$userlist= $this->Meeting_model->get_all_admin_users($parent_path, $this->input->get('id'));

		$c=count($userlist);

		$users_array = [];

		$data = array(
			'usrId' => 0,
			'usrName' => 'No Telecaller',
			'usrParentName' => 'None',
			'usrTypeName' => 'None'
		);

		array_push($users_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'usrId' => $userlist[$i]['usrId'],
				'usrName' => $userlist[$i]['usrUserName'],
				'usrParentName' => $userlist[$i]['parentName'],
				'usrTypeName' => $userlist[$i]['usrTypeName']
			);

			array_push($users_array, $data);

		}

		$json = json_encode($users_array);
		echo $json;

	}



}