<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expensemanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Expense_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/expense/expense');

		}else{
			redirect('/login');
		}
		
	}


	public function get_all_expenses()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$expenselist= $this->Expense_model->get_all_expenses($parent_path);

		$c=count($expenselist);
		echo '[';

		for($i=0;$i<$c;$i++){

			if($expenselist[$i]['expIsMeetingAssociated'] == "yes"){

				$meetingdata = $this->Expense_model->get_expense_meeting_data($expenselist[$i]['expMeetingId']);

				$data = array(
					'expId' => $expenselist[$i]['expId'],
					'expTitle' => $expenselist[$i]['expTitle'],
					'expAmount' => $expenselist[$i]['expAmount'],
					'expDescription' => $expenselist[$i]['expDescription'],
					'expPaymentStatus' => $expenselist[$i]['expPaymentStatus'],
					'expImageStatus' => $expenselist[$i]['expImageStatus'],
					'expImageAvailable' => $expenselist[$i]['expImageAvailable'],
					'expIsMeetingAssociated' => $expenselist[$i]['expIsMeetingAssociated'],
					'expImage' => base_url().$expenselist[$i]['expImage'],
					'expParentName' => $expenselist[$i]['usrUserName'],
					'expStatus' => $expenselist[$i]['expStatus'],
					'expCompleted' => $expenselist[$i]['expCompleted'],
					'expMeetingName' => $meetingdata['mtnName'],
					'expMeetingCustomerName' => $meetingdata['cusFirstName'].' '.$meetingdata['cusLastName'],
					'expDate' => $expenselist[$i]['expDate']

				);

				$json = json_encode($data);

				echo $json;

			}else{

				$data = array(
					'expId' => $expenselist[$i]['expId'],
					'expTitle' => $expenselist[$i]['expTitle'],
					'expAmount' => $expenselist[$i]['expAmount'],
					'expDescription' => $expenselist[$i]['expDescription'],
					'expPaymentStatus' => $expenselist[$i]['expPaymentStatus'],
					'expImageStatus' => $expenselist[$i]['expImageStatus'],
					'expImageAvailable' => $expenselist[$i]['expImageAvailable'],
					'expIsMeetingAssociated' => $expenselist[$i]['expIsMeetingAssociated'],
					'expImage' => base_url().$expenselist[$i]['expImage'],
					'expParentName' => $expenselist[$i]['usrUserName'],
					'expStatus' => $expenselist[$i]['expStatus'],
					'expCompleted' => $expenselist[$i]['expCompleted'],
					'expMeetingName' => "",
					'expMeetingCustomerName' => "",
					'expDate' => $expenselist[$i]['expDate']
				);

				$json = json_encode($data);

				echo $json;

			}


            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function change_payment_status()
	{
		if($this->input->post('expId',TRUE)){

			$params = array(
				'expPaymentStatus' => $this->input->post('expPaymentStatus')
			);

			$payment_status_id = $this->Expense_model->update_payment_status($this->input->post('expId'), $params);

			if($payment_status_id == true){

				echo '{
					"status":"success",
					"message":"Payment Status Updated Successfully"
				}';

			}else{

				echo '{
					"status":"failed",
					"message":"Payment Status Failed"
				}';

			}

		}

	}


}