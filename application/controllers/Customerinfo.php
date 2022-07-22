<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customerinfo extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Customerinfo_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('customerinfo/customerinfo');

		}else{
			redirect('/login');
		}
		
	}


	public function detail()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('customerinfo/customerinfodetail');

		}else{
			redirect('/login');
		}
		
	}



	public function get_all_customers()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$customerlist= $this->Customerinfo_model->get_all_customers($parent_path);

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
				'cusLandmark' => $customerlist[$i]['cusLandmark'],
				'cusPinCode' => $customerlist[$i]['cusPinCode'],
				'cusParentId' => $customerlist[$i]['cusParentId'],
				'cusManageId' => $customerlist[$i]['cusManageId'],
				'cusParentName' => $customerlist[$i]['parentName'],
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

		$customerdata = $this->Customerinfo_model->get_customer_data($params);

		$data = array(
				'cusId' => $customerdata['cusId'],
				'cusCode' => $customerdata['cusCode'],
				'cusFirstName' => $customerdata['cusFirstName'],
				'cusLastName' => $customerdata['cusLastName'],
				'cusGender' => $customerdata['cusGender'],
				'cusDOB' => $customerdata['cusDOB'],
				'cusDOA' => $customerdata['cusDOA'],
				'cusCustomerType' => $customerdata['cusCustomerType'],
				'cusCustomerTypeId' => $customerdata['cusCustomerTypeId'],
				'cusIndustryType' => $customerdata['cusIndustryType'],
				'cusIndustryTypeId' => $customerdata['cusIndustryTypeId'],
				'cusCompanyName' => $customerdata['cusCompanyName'],
				'cusDepartment' => $customerdata['cusDepartment'],
				'cusDesignation' => $customerdata['cusDesignation'],
				'cusEmail' => $customerdata['cusEmail'],
				'cusMobileNo' => $customerdata['cusMobileNo'],
				'cusAlternateNo' => $customerdata['cusAlternateNo'],
				'cusCountryId' => $customerdata['cusCountryId'],
				'cusCountry' => $customerdata['cusCountry'],
				'cusStateId' => $customerdata['cusStateId'],
				'cusState' => $customerdata['cusState'],
				'cusCityId' => $customerdata['cusCityId'],
				'cusCity' => $customerdata['cusCity'],
				'cusArea' => $customerdata['cusArea'],
				'cusAddress' => $customerdata['cusAddress'],
				'cusLandmark' => $customerdata['cusLandmark'],
				'cusPinCode' => $customerdata['cusPinCode']
			);

			$json = json_encode($data);

			echo $json;

	}


	public function get_all_customer_meetings()
	{
		header('Content-Type: application/json');
		
		$meetinglist= $this->Customerinfo_model->get_all_customer_meetings($this->input->get('id'));

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



}