<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orderreport extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Orderreport_model');
        $this->load->model('Meetingreport_model');
        $this->load->model('Web_user_model');
        $this->load->model('Order_model');
        $this->load->model('Rochak_order_report_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('reports/order_report');

		}else{
			redirect('/login');
		}
		
	}
    
    //Isfaque Module for LNSEL APP
    
	public function get_order_records_by_date()
	{
		header('Content-Type: application/json');

		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];

		$params = array(
			'fromDate' => $this->input->get('fromDate'),
			'toDate' => $this->input->get('toDate'),
			'orderStatusId' => $this->input->get('orderStatusId'),
			'orderStatus' => $this->input->get('orderStatus'),
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
			'companyName' => $this->input->get('companyName')
		);

		$ordrlist= $this->Orderreport_model->get_order_records_by_date($params, $parent_path);

		$c=count($ordrlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'ordSNo' => $i+1,
				'ordDate' => $ordrlist[$i]['ordDate'],
				'ordForDate' => $ordrlist[$i]['ordForDate'],
				'ordUserName' => $ordrlist[$i]['usrUserName'],
				'ordName' => $ordrlist[$i]['ordName'],
				'ordDescription' => $ordrlist[$i]['ordDescription'],
				'ordStatusId' => $ordrlist[$i]['ordStatusId'],
				'ordStatus' => $ordrlist[$i]['ordStatus'],
				'ordAmount' => $ordrlist[$i]['ordAmount']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}

	public function get_all_admins()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$adminlist = $this->Meetingreport_model->get_all_admins($parent_path);

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
		$userlist= $this->Meetingreport_model->get_all_admin_users($parent_path, $this->input->get('id'));

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
		$customertypelist= $this->Meetingreport_model->get_all_customer_types_report();

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
		$industrytypelist= $this->Meetingreport_model->get_all_industry_types_report();

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
		$companylist= $this->Meetingreport_model->get_all_companies_report();

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

	public function get_all_order_status_types()
	{

		header('Content-Type: application/json');
		$ostlist= $this->Order_model->get_order_status_types();

		$c=count($ostlist);

		$orders_array = [];

			$data = array(
				'ostId' => 0,
				'ostName' => 'All'
			);

		array_push($orders_array, $data);

		for($i=0;$i<$c;$i++){

			$data = array(
				'ostId' => $ostlist[$i]['ostId'],
				'ostName' => $ostlist[$i]['ostName']
			);

			array_push($orders_array, $data);

		}

		$json = json_encode($orders_array);
		echo $json;

	}
    
    //Soumyajeet Module for Rochak
    
    function distributor_list()
    {
        header('Content-Type: application/json');
        $distributorlist     =   $this->Rochak_order_report_model->get_distributors();
        echo $distributorlist;
    }
    
    function app_user_list()
    {
        header('Content-Type: application/json');
        $appUserlist     =   $this->Rochak_order_report_model->get_appusers();
        echo $appUserlist;
    }
    
    function get_order_filter_web_by_combinations()
    {
        header('Content-Type: application/json');
        if($this->input->post('timevalue',TRUE));
        {
            $quaterValue    =   $this->input->post('timevalue',TRUE);
            if($quaterValue == 1){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-3-31'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the first quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            elseif($quaterValue == 2){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-4-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the second quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            elseif($quaterValue == 3){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-7-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-9-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the third quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            elseif($quaterValue == 4){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-10-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-12-31'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the fourth quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            //function to get the records of current week
            elseif($quaterValue == 5){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_week();
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for current week'];
                    echo json_encode($message);
                    exit();
                }
            }
            //function to get the records of current month
            elseif($quaterValue == 6){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_month();
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for current month'];
                    echo json_encode($message);
                    exit();
                }
            }
            //function to get the records of half yearly
            elseif($quaterValue == 7){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the half yearly'];
                    echo json_encode($message);
                    exit();
                }
            }
            //function to get the records of Anually
            elseif($quaterValue == 8){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['cartItemId']     =   false;
                }
                else if($this->input->post('cartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['cartItemId']     =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_year($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No collection data found for current year'];
                    echo json_encode($message);
                    exit();
                }    
            }
        }
        if($this->input->post('dateFrom',TRUE) && $this->input->post('dateTo',TRUE)){
            $data['dateFrom']   =   $this->input->post('dateFrom',TRUE)." 00:00:00";
            $data['dateTo']     =   $this->input->post('dateTo',TRUE)." 23:59:59";
            //filter get collections records by salesperson & distributor & items with date range
            if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for this'];
                    echo json_encode($message);
                }
            }
            //filter get collections records by salesperson & distributor with date range
            else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                $data['cartItemId']     = false;
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for this'];
                    echo json_encode($message);
                }
            }
            //filter get collections records by salesperson & items with date range
            else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
                $data['distributorid']  = false;
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for this'];
                    echo json_encode($message);
                }
            }
            //filter get collections records by distributor & items with date range
            else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){               
                $data['salesUserId']    = false;
                $data['distributorid']      =   $this->input->post('distributorid',TRUE);
                $data['cartItemId']         =   $this->input->post('cartItemId',TRUE);
                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for this'];
                    echo json_encode($message);
                }
            }
            //filter get collections records by salesperson with date range
            else if($this->input->post('salesUserId',TRUE)){
                $data['cartItemId']     = false;
                $data['distributorid']  = false;
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for this sales person from the selective date'];
                    echo json_encode($message);
                }
            }
            //filter get projections records by distributor with date range
            else if($this->input->post('distributorid',TRUE)){
                $data['cartItemId']     = false;
                $data['salesUserId']    = false;
                $data['distributorid']      =   $this->input->post('distributorid',TRUE);
                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for this distributor from the selective date'];
                    echo json_encode($message);
                }
            }
            //filter get collections records by items with date range
            else if($this->input->post('cartItemId',TRUE)){
                $data['salesUserId']    = false;
                $data['distributorid']  = false;
                $data['cartItemId']   =   $this->input->post('cartItemId',TRUE);

                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for this items'];
                    echo json_encode($message);
                }
            }
            else{
                $data['cartItemId']     = false;
                $data['salesUserId']    = false;
                $data['distributorid']  = false;
                $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the date range'];
                    echo json_encode($message);
                }
            }
        }
        //filter get collections records by salesperson & distributor & items
        else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['distributorid']  =   $this->input->post('distributorid',TRUE);
            $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this'];
                echo json_encode($message);
            }
        }
        //filter get collections records by salesperson & distributor
        else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['cartItemId']     =   false;
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['distributorid']  =   $this->input->post('distributorid',TRUE);
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this'];
                echo json_encode($message);
            }
        }
        //filter get collections records by salesperson & items
        else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['distributorid']  =   false;
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found!!!'];
                echo json_encode($message);
            }
        }
        //filter get collections records by distributor & items
        else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
            $data['dateFrom']           =   false;
            $data['dateTo']             =   false;
            $data['salesUserId']        =   false;
            $data['distributorid']      =   $this->input->post('distributorid',TRUE);
            $data['cartItemId']         =   $this->input->post('cartItemId',TRUE);
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this'];
                echo json_encode($message);
            }
        }
        //filter get collections records by salesperson
        else if($this->input->post('salesUserId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['distributorid']  =   false;
            $data['cartItemId']     =   false;
            
            $data['salesUserId']   =   $this->input->post('salesUserId',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this sales person'];
                echo json_encode($message);
            }
        }
        //filter get projections records by distributor
        else if($this->input->post('distributorid',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   false;
            $data['cartItemId']     =   false;
            $data['distributorid']   =   $this->input->post('distributorid',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this distributor'];
                echo json_encode($message);
            }
        }
        //filter get collections records by items
        else if($this->input->post('cartItemId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   false;
            $data['distributorid']  =   false;
            $data['cartItemId']     =   $this->input->post('cartItemId',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this items'];
                echo json_encode($message);
            }
        }        
        /*else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid Search'];
            echo json_encode($message);
        }*/
    }
}