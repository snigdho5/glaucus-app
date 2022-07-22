<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockreport extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Orderreport_model');
        $this->load->model('Meetingreport_model');
        $this->load->model('Web_user_model');
        $this->load->model('Order_model');
        $this->load->model('Stock_report_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('reports/stock_report');

		}
		else{
			redirect('/login');
		}
		
	}
    
    //Soumyajeet Module for Rochak
    
    function get_stock_filter_web_by_combinations()
    {
        header('Content-Type: application/json');
        if($this->input->post('timevalue',TRUE));
        {
            $quaterValue    =   $this->input->post('timevalue',TRUE);
            if($quaterValue == 1){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-3-31'." 23:59:59";
                $orderRecords  =   $this->Stock_report_model->get_stock_filter_logs_web($data);
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
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-4-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $orderRecords  =   $this->Stock_report_model->get_stock_filter_logs_web($data);
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
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']      =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']        =   false;
                    $data['stockCartItemId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-7-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-9-30'." 23:59:59";
                $orderRecords  =   $this->Stock_report_model->get_stock_filter_logs_web($data);
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
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-10-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-12-31'." 23:59:59";
                $orderRecords  =   $this->Stock_report_model->get_stock_filter_logs_web($data);
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
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $orderRecords  =   $this->Stock_report_model->get_stock_report_current_week();
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
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $orderRecords  =   $this->Stock_report_model->get_stock_report_current_month();
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
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $orderRecords  =   $this->Stock_report_model->get_stock_filter_logs_web($data);
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
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                    $data['stockCartItemId']     =   false;
                }
                else if($this->input->post('stockCartItemId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                    $data['stockCartItemId']     =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $orderRecords  =   $this->Stock_report_model->get_stock_report_current_year($data);
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
            if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
                $data['stockCartItemId']     = false;
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
            else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
                $data['distributorid']  = false;
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
            else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){               
                $data['salesUserId']    = false;
                $data['distributorid']      =   $this->input->post('distributorid',TRUE);
                $data['stockCartItemId']         =   $this->input->post('stockCartItemId',TRUE);
                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
                $data['stockCartItemId']     = false;
                $data['distributorid']  = false;
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
                $data['stockCartItemId']     = false;
                $data['salesUserId']    = false;
                $data['distributorid']      =   $this->input->post('distributorid',TRUE);
                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
            else if($this->input->post('stockCartItemId',TRUE)){
                $data['salesUserId']    = false;
                $data['distributorid']  = false;
                $data['stockCartItemId']   =   $this->input->post('stockCartItemId',TRUE);

                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
                $data['stockCartItemId']     = false;
                $data['salesUserId']    = false;
                $data['distributorid']  = false;
                $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
        else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['distributorid']  =   $this->input->post('distributorid',TRUE);
            $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
            $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
            $data['stockCartItemId']     =   false;
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['distributorid']  =   $this->input->post('distributorid',TRUE);
            $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
        else if($this->input->post('salesUserId',TRUE) && $this->input->post('stockCartItemId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['distributorid']  =   false;
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
            $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
        else if($this->input->post('distributorid',TRUE) && $this->input->post('stockCartItemId',TRUE)){
            $data['dateFrom']           =   false;
            $data['dateTo']             =   false;
            $data['salesUserId']        =   false;
            $data['distributorid']      =   $this->input->post('distributorid',TRUE);
            $data['stockCartItemId']         =   $this->input->post('stockCartItemId',TRUE);
            $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
            $data['stockCartItemId']     =   false;
            
            $data['salesUserId']   =   $this->input->post('salesUserId',TRUE);
            
            $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
            $data['stockCartItemId']     =   false;
            $data['distributorid']   =   $this->input->post('distributorid',TRUE);
            
            $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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
        else if($this->input->post('stockCartItemId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   false;
            $data['distributorid']  =   false;
            $data['stockCartItemId']     =   $this->input->post('stockCartItemId',TRUE);
            
            $orderRecords  =   $this->Stock_report_model->generate_stock_report($data);
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