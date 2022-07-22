<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordermanagement extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Order_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('orders/order');

		}else{
			redirect('/login');
		}
		
	}


	public function edit()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('orders/editorder');

		}else{
			redirect('/login');
		}
	}


	public function update_order()
	{
		if($this->input->post('ordId',TRUE)){
			$params = array(
				'ordName' => $this->input->post('ordName'),
				'ordUnitId' => $this->input->post('ordUnitId'),
				'ordUnit' => $this->input->post('ordUnit'),
				'ordVenueId' => $this->input->post('ordVenueId'),
				'ordVenue' => $this->input->post('ordVenue'),
				'ordQuantity' => $this->input->post('ordQuantity'),
				'ordAmount' => $this->input->post('ordAmount'),
				'ordForDate' => $this->input->post('ordForDate'),
				'ordDescription' => $this->input->post('ordDescription'),
				'ordStatusId' => $this->input->post('ordStatusId'),
				'ordStatus' => $this->input->post('ordStatus')
			);

			$order_duplicate = $this->Order_model->get_edit_order_name_duplicate($this->input->post('ordId'), $this->input->post('ordName'));

			if($order_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Username already exist"
				}';

			}else{

				$order_id = $this->Order_model->update_order($this->input->post('ordId'), $params);
				echo '{
					"status":"success",
					"message":"User Updated Successfully"
				}';

			}
		}

	}


	public function get_all_orders()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$ordlist= $this->Order_model->get_all_orders($parent_path);

		$c=count($ordlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'ordId' => $ordlist[$i]['ordId'],
				'ordName' => $ordlist[$i]['ordName'],
				'ordUnitId' => $ordlist[$i]['ordUnitId'],
				'ordUnit' => $ordlist[$i]['ordUnit'],
				'ordVenueId' => $ordlist[$i]['ordVenueId'],
				'ordVenue' => $ordlist[$i]['ordVenue'],
				'ordQuantity' => $ordlist[$i]['ordQuantity'],
				'ordAmount' => $ordlist[$i]['ordAmount'],
				'ordDescription' => $ordlist[$i]['ordDescription'],
				'ordStatusId' => $ordlist[$i]['ordStatusId'],
				'ordStatus' => $ordlist[$i]['ordStatus'],
				'ordForDate' => $ordlist[$i]['ordForDate'],
				'ordDate' => $ordlist[$i]['ordDate'],
				'ordTime' => $ordlist[$i]['ordTime'],
				'ordDateTime' => $ordlist[$i]['ordDate'].' '.$ordlist[$i]['ordTime'],
				'ordParentName' => $ordlist[$i]['usrUserName'],
				'ordMeetingName' => $ordlist[$i]['mtnName'],
				'ordCustomerName' => $ordlist[$i]['cusFirstName'].' '.$ordlist[$i]['cusLastName'],
				'ordCustomerCompanyName' => $ordlist[$i]['cusCompanyName'],
				'ordCustomerAddress' => $ordlist[$i]['cusAddress'].', '.$ordlist[$i]['cusCity'].' - '.$ordlist[$i]['cusPinCode']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



	public function get_order_detail(){

		header('Content-Type: application/json');

		$params = array(
				'ordId' => $this->input->get('id')
			);

		$orderdata = $this->Order_model->get_order_data($params);

		$data = array(
				'ordId' => $orderdata['ordId'],
				'ordName' => $orderdata['ordName'],
				'ordUnitId' => $orderdata['ordUnitId'],
				'ordUnit' => $orderdata['ordUnit'],
				'ordVenueId' => $orderdata['ordVenueId'],
				'ordVenue' => $orderdata['ordVenue'],
				'ordQuantity' => $orderdata['ordQuantity'],
				'ordAmount' => $orderdata['ordAmount'],
				'ordForDate' => $orderdata['ordForDate'],
				'ordDescription' => $orderdata['ordDescription'],
				'ordParentId' => $orderdata['ordParentId'],
				'ordStatusId' => $orderdata['ordStatusId'],
				'ordStatus' => $orderdata['ordStatus']
			);

			$json = json_encode($data);

			echo $json;

	}


	public function get_all_user_units()
	{
		header('Content-Type: application/json');

		$params = array(
				'usrId' => $this->input->get('ordParentId')
			);

		$userdata = $this->Order_model->get_user_data($params);

		$unitlist= $this->Order_model->get_user_units($userdata['usrParentId']);

		$c=count($unitlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'untId' => $unitlist[$i]['untId'],
				'untName' => $unitlist[$i]['untName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_venues_by_unit()
	{
		header('Content-Type: application/json');
		$id = $this->input->get('id');
		$venuelist= $this->Order_model->get_venues_by_unit($id);

		$c=count($venuelist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'venId' => $venuelist[$i]['venId'],
				'venShortName' => $venuelist[$i]['venShortName'],
				'venFullName' => $venuelist[$i]['venFullName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}



	public function get_all_order_status_types()
	{
		header('Content-Type: application/json');

		$ostlist= $this->Order_model->get_order_status_types();

		$c=count($ostlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'ostId' => $ostlist[$i]['ostId'],
				'ostName' => $ostlist[$i]['ostName']
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