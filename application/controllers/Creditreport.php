<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creditreport extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Rochak_order_report_model');
        $this->load->model('Rochak_credit_report_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){
			$this->load->view('include/dashboard_header');
			$this->load->view('reports/credit_report');
		}else{
			redirect('/login');
		}		
	}
    //Soumyajeet Module for Rochak  
    function get_credit_logs_web()
    {
        header('Content-Type: application/json');
        $creditRecords  =   $this->Rochak_credit_report_model->get_credit_logs_web();
        if($creditRecords){
            $message    =   ['status' => 'success', 'message' => $creditRecords];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Credit logs empty'];
            echo json_encode($message);
        }        
    }
    
    function get_credit_filter_web_by_combinations()
    {
        header('Content-Type: application/json');
        if($this->input->post('timevalue',TRUE));
        {
            $quaterValue    =   $this->input->post('timevalue',TRUE);
            //function to get the records of 4 Quaters
            if($quaterValue == 1){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-3-31'." 23:59:59";                
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for the first quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            else if($quaterValue == 2){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']   =   date("Y").'-4-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";                
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for the second quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            else if($quaterValue == 3){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']   =   date("Y").'-7-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-9-30'." 23:59:59";                
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for the third quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            else if($quaterValue == 4){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']   =   date("Y").'-10-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-12-31'." 23:59:59";                
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for the fourth quater'];
                    echo json_encode($message);
                    exit();
                }
            }
            //function to get the records of current week
            else if($quaterValue == 5){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                
                $creditRecords  =   $this->Rochak_credit_report_model->get_credit_report_current_week($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for current week'];
                    echo json_encode($message);
                    exit();
                }
                exit();
            }
            //function to get the records of current month
            else if($quaterValue == 6){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $creditRecords  =   $this->Rochak_credit_report_model->get_credit_report_current_month($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for current month'];
                    echo json_encode($message);
                    exit();
                }
            }
            //function to get the records of half yearly
            else if($quaterValue == 7){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for the half yearly'];
                    echo json_encode($message);
                    exit();
                }
            }
            //function to get the records of Anually
            else if($quaterValue == 8){
                if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else if($this->input->post('distributorid',TRUE)){
                    $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                    $data['salesUserId']    =   false;
                }
                else if($this->input->post('salesUserId',TRUE)){
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                }
                else{
                    $data['distributorid']  =   false;
                    $data['salesUserId']    =   false;
                }
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $creditRecords  =   $this->Rochak_credit_report_model->get_credit_report_current_year($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                    exit();
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for current year'];
                    echo json_encode($message);
                    exit();
                }
            }            
        }
        if($this->input->post('dateFrom',TRUE) && $this->input->post('dateTo',TRUE)){
            $data['dateFrom']   =   $this->input->post('dateFrom',TRUE)." 00:00:00";
            $data['dateTo']     =   $this->input->post('dateTo',TRUE)." 23:59:59";
            //filter get credit records by salesperson & distributor with date range
            if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
                $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
                $data['distributorid']  =   $this->input->post('distributorid',TRUE);
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for this'];
                    echo json_encode($message);
                }
            }
            else if($this->input->post('salesUserId',TRUE)){
                $data['distributorid']  = false;
                $data['salesUserId']   =   $this->input->post('salesUserId',TRUE);
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for this sales person with the selected date range'];
                    echo json_encode($message);
                }
            }
            //filter get credit records by distributor with date range
            else if($this->input->post('distributorid',TRUE)){
                $data['salesUserId']    = false;
                $data['distributorid']      =   $this->input->post('distributorid',TRUE);
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for this distributor with the selected date range'];
                    echo json_encode($message);
                }
            }
            else{
                $data['salesUserId']    = false;
                $data['distributorid']  = false;
                $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
                if($creditRecords){
                    $message    =   ['status' => 'success', 'message' => $creditRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No credit data found for the date range'];
                    echo json_encode($message);
                }
            }
        }
        //filter get credit records by salesperson & distributor
        else if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorid',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['distributorid']  =   $this->input->post('distributorid',TRUE);
            $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
            if($creditRecords){
                $message    =   ['status' => 'success', 'message' => $creditRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No credit data found for this'];
                echo json_encode($message);
            }
        }
        //filter get credit records by salesperson with date range
        else if($this->input->post('salesUserId',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['distributorid']  =   false;
            $data['salesUserId']   =   $this->input->post('salesUserId',TRUE);
            $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
            if($creditRecords){
                $message    =   ['status' => 'success', 'message' => $creditRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No credit data found for this sales person'];
                echo json_encode($message);
            }
    }
        //filter get credit records by distributor with date range
        else if($this->input->post('distributorid',TRUE)){
            $data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   false;
            $data['distributorid']      =   $this->input->post('distributorid',TRUE);
            $creditRecords  =   $this->Rochak_credit_report_model->generate_credit_report($data);
            if($creditRecords){
                $message    =   ['status' => 'success', 'message' => $creditRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No credit data found for this distributor'];
                echo json_encode($message);
            }
        }
    }
}