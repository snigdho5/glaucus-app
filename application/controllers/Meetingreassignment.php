<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meetingreassignment extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Meeting_reassignment_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/meeting_reassignment/meeting_reassignment');

		}else{
			redirect('/login');
		}
		
	}

	public function get_all_meetings()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$meetinglist= $this->Meeting_reassignment_model->get_all_meetings($parent_path);

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



	public function update_meeting()
	{
		if($this->input->post('mtnId',TRUE)){
			$params = array(
				'mtnUserId' => $this->input->post('mtnUserId')
			);


			$customer_id = $this->Meeting_reassignment_model->update_meeting($this->input->post('mtnId'), $params);

			echo '{
				"status":"success",
				"message":"Meeting Updated Successfully"
			}';

		}

	}





}