<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expensereport extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Expensereport_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('reports/expense_report');

		}else{
			redirect('/login');
		}
		
	}


	public function get_expense_records_by_date()
	{
		header('Content-Type: application/json');
		$from_date = $this->input->get('fromDate');
		$to_date = $this->input->get('toDate');
		$user_id = $this->input->get('userId');
		$payment_status = $this->input->get('paymentStatus');

		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

		if($payment_status == "all"){
			$exprlist= $this->Expensereport_model->get_expense_records_by_date($from_date, $to_date, $parent_path, $user_id);
		}else{
			$exprlist= $this->Expensereport_model->get_expense_records_by_date_and_payment($from_date, $to_date, $parent_path, $user_id, $payment_status);
		}
		

		

		$c=count($exprlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'expSNo' => $i+1,
				'expDate' => $exprlist[$i]['expDate'],
				'expUserName' => $exprlist[$i]['usrUserName'],
				'expName' => $exprlist[$i]['expTitle'],
				'expDescription' => $exprlist[$i]['expDescription'],
				'expPaymentStatus' => $exprlist[$i]['expPaymentStatus'],
				'expAmount' => $exprlist[$i]['expAmount']
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