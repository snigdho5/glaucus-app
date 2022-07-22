<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavemanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Leave_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('leave_request/leave_request');

		}else{
			redirect('/login');
		}
		
	}


	public function get_all_pending_leave_request()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$plreqlist= $this->Leave_model->get_all_pending_leave_request($parent_path);

		$c=count($plreqlist);
		echo '[';

		for($i=0;$i<$c;$i++){
            
            $fromDate       =   date_create($plreqlist[$i]['lreqDateFrom']);
            $toDate         =   date_create($plreqlist[$i]['lreqDateTo']);
            $noOfDays       =   date_diff($fromDate,$toDate);
            //echo $noOfDays->d;

			$data = [
				'plreqId'               =>  $plreqlist[$i]['lreqId'],
				'plreqSubject'          =>  $plreqlist[$i]['lreqSubject'],
				'plreqDescription'      =>  $plreqlist[$i]['lreqDescription'],
				'plreqUserName'         =>  $plreqlist[$i]['usrUserName'],
				'plreqStatus'           =>  $plreqlist[$i]['lreqStatus'],
				'plreqStatusId'         =>  $plreqlist[$i]['lreqStatusId'],
				'plreqDateFrom'         =>  $plreqlist[$i]['lreqDateFrom'],
				'plreqDateTo'           =>  $plreqlist[$i]['lreqDateTo'],
				'plreqStatusMessage'    =>  $plreqlist[$i]['lreqStatusMessage'],
			 ];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_accepted_leave_request()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$alreqlist= $this->Leave_model->get_all_accepted_leave_request($parent_path);

		$c=count($alreqlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = [
				'alreqId'               =>  $alreqlist[$i]['lreqId'],
				'alreqSubject'          =>  $alreqlist[$i]['lreqSubject'],
				'alreqDescription'      =>  $alreqlist[$i]['lreqDescription'],
				'alreqUserName'         =>  $alreqlist[$i]['usrUserName'],
				'alreqStatus'           =>  $alreqlist[$i]['lreqStatus'],
				'alreqStatusId'         =>  $alreqlist[$i]['lreqStatusId'],
                'alreqDateFrom'         =>  $alreqlist[$i]['lreqDateFrom'],
				'alreqDateTo'           =>  $alreqlist[$i]['lreqDateTo'],
				'alreqStatusMessage'    =>  $alreqlist[$i]['lreqStatusMessage'],
			];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



	public function get_all_rejected_leave_request()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$rlreqlist= $this->Leave_model->get_all_rejected_leave_request($parent_path);

		$c=count($rlreqlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = [
				'rlreqId'               =>  $rlreqlist[$i]['lreqId'],
				'rlreqSubject'          =>  $rlreqlist[$i]['lreqSubject'],
				'rlreqDescription'      =>  $rlreqlist[$i]['lreqDescription'],
				'rlreqUserName'         =>  $rlreqlist[$i]['usrUserName'],
				'rlreqStatus'           =>  $rlreqlist[$i]['lreqStatus'],
				'rlreqStatusId'         =>  $rlreqlist[$i]['lreqStatusId'],
                'rlreqDateFrom'         =>  $rlreqlist[$i]['lreqDateFrom'],
				'rlreqDateTo'           =>  $rlreqlist[$i]['lreqDateTo'],
				'rlreqStatusMessage'    =>  $rlreqlist[$i]['lreqStatusMessage'],
			];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



	public function update_accept_pending_leave_request()
	{
		if($this->input->post('lreqStatus',TRUE)){
			$params = array(
				'lreqStatus' => $this->input->post('lreqStatus'),
				'lreqStatusId' => $this->input->post('lreqStatusId'),
				'lreqStatusMessage' => $this->input->post('lreqStatusMessage')
			);

			$order_id = $this->Leave_model->update_accept_pending_leave_request($this->input->post('lreqId'), $params);

			echo '{
				"status":"success",
				"message":"Leave Request Accepted Successfully"
			}';

		}

	}


	public function update_reject_pending_leave_request()
	{
		if($this->input->post('lreqStatus',TRUE)){
			$params = array(
				'lreqStatus' => $this->input->post('lreqStatus'),
				'lreqStatusId' => $this->input->post('lreqStatusId'),
				'lreqStatusMessage' => $this->input->post('lreqStatusMessage')
			);

			$order_id = $this->Leave_model->update_reject_pending_leave_request($this->input->post('lreqId'), $params);

			echo '{
				"status":"success",
				"message":"Leave Request Rejected Successfully"
			}';

		}

	}




}