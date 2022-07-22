<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rochak_order_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();	
        $this->load->model('Rochak_order_report_model');
        $this->load->helper('url');
	}
    
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
    
    function items_list()
    {
        
    }
    
    /*********Order Report Module**********/
    //function to get the records by selective interval dates
    /*function get_order_logs_web_by_date_range()
    {
        header('Content-Type: application/json');
        if($this->input->post('dateFrom',TRUE) && $this->input->post('dateTo',TRUE)){
            $data['dateFrom']   =   $this->input->post('dateFrom',TRUE)." 00:00:00";
            $data['dateTo']     =   $this->input->post('dateTo',TRUE)." 23:59:59";
            
            $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
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
    //function to get the records of current week
    function get_order_logs_web_current_week()
    {
        header('Content-Type: application/json');
        $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_week();
        if($orderRecords){
            $message    =   ['status' => 'success', 'message' => $orderRecords];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No order data found for current week'];
            echo json_encode($message);
        }
    }
    //function to get the records of current month
    function get_order_logs_web_current_month()
    {
        header('Content-Type: application/json');
        $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_month();
        if($orderRecords){
            $message    =   ['status' => 'success', 'message' => $orderRecords];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No order data found for current month'];
            echo json_encode($message);
        }
    }
    //function to get the records of four Quaters
    function get_order_logs_web_current_quater()
    {
        header('Content-Type: application/json');
        if($this->input->post('quater',TRUE)){
            $quaterValue    =   $this->input->post('quater',TRUE);
            if($quaterValue == 1){
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-3-31'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the first quater'];
                    echo json_encode($message);
                }
            }
            elseif($quaterValue == 2){
                $data['dateFrom']   =   date("Y").'-4-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the second quater'];
                    echo json_encode($message);
                }
            }
            elseif($quaterValue == 3){
                $data['dateFrom']   =   date("Y").'-7-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-9-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the third quater'];
                    echo json_encode($message);
                }
            }
            elseif($quaterValue == 4){
                $data['dateFrom']   =   date("Y").'-10-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-12-31'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the fourth quater'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Invalid Quater Selected'];
                    echo json_encode($message);
            }
        }
    }
    //function to get the records of half yearly
    function get_order_logs_web_half_yearly()
    {
        header('Content-Type: application/json');
        $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
        $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
        $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
        if($orderRecords){
            $message    =   ['status' => 'success', 'message' => $orderRecords];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No order data found for the half yearly'];
            echo json_encode($message);
        }
    }
    //function to get the records of anually
    function get_order_logs_web_current_year()
    {
        header('Content-Type: application/json');
        $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_year();
        if($orderRecords){
            $message    =   ['status' => 'success', 'message' => $orderRecords];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No collection data found for current year'];
            echo json_encode($message);
        }
    }
    //filter get projections records by distributor
    function get_order_logs_web_by_distributor()
    {
        header('Content-Type: application/json');
        if($this->input->post('distributorid',TRUE)){
            $data['distributorid']   =   $this->input->post('distributorid',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->get_order_records_by_distributor_web($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this distributor'];
                echo json_encode($message);
            }
        }
    }
    //filter get projections records by distributor with date range
    function get_order_logs_web_by_distributor_date_range()
    {
        header('Content-Type: application/json');
        if($this->input->post('distributorid',TRUE) && $this->input->post('dateFrom',TRUE) && $this->input->post('dateTo',TRUE)){
            $data['dateFrom']           =   $this->input->post('dateFrom',TRUE)." 00:00:00";
            $data['dateTo']             =   $this->input->post('dateTo',TRUE)." 23:59:59";
            $data['distributorid']      =   $this->input->post('distributorid',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->get_order_records_by_distributor_web($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this distributor from the selective date'];
                echo json_encode($message);
            }
        }
    }    
    //filter get collections records by salesperson
    function get_order_logs_web_by_salesperson()
    {
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']   =   $this->input->post('salesUserId',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->get_order_records_by_salesperson_web($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this sales person'];
                echo json_encode($message);
            }
        }
    }
    //filter get collections records by salesperson with date range
    function get_order_logs_web_by_salesperson_date_range()
    {
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE) && $this->input->post('dateFrom',TRUE) && $this->input->post('dateTo',TRUE)){
            $data['dateFrom']       =   $this->input->post('dateFrom',TRUE)." 00:00:00";
            $data['dateTo']         =   $this->input->post('dateTo',TRUE)." 23:59:59";
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->get_order_records_by_salesperson_web($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this sales person from the selective date'];
                echo json_encode($message);
            }
        }
    }
    //filter get collections records by items
    function get_order_logs_web_by_items()
    {
        header('Content-Type: application/json');
        if($this->input->post('cartItemId',TRUE)){
            $data['cartItemId']   =   $this->input->post('cartItemId',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->get_order_records_by_items_web($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this items'];
                echo json_encode($message);
            }
        }
    }*/
    //function to get the records by several combinations
    function get_order_filter_web_by_combinations()
    {
        header('Content-Type: application/json');
        if($this->input->post('timevalue',TRUE));
        {
            $quaterValue    =   $this->input->post('timevalue',TRUE);
            if($quaterValue == 1){
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-3-31'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the first quater'];
                    echo json_encode($message);
                }
            }
            elseif($quaterValue == 2){
                $data['dateFrom']   =   date("Y").'-4-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the second quater'];
                    echo json_encode($message);
                }
            }
            elseif($quaterValue == 3){
                $data['dateFrom']   =   date("Y").'-7-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-9-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the third quater'];
                    echo json_encode($message);
                }
            }
            elseif($quaterValue == 4){
                $data['dateFrom']   =   date("Y").'-10-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-12-31'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the fourth quater'];
                    echo json_encode($message);
                }
            }
            //function to get the records of current week
            elseif($quaterValue == 5){
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $data['distributorid']  =   false;
                $data['cartItemId']     =   false;
                $data['salesUserId']    =   false;
                $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_week();
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for current week'];
                    echo json_encode($message);
                }
            }
            //function to get the records of current month
            elseif($quaterValue == 6){
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $data['distributorid']  =   false;
                $data['cartItemId']     =   false;
                $data['salesUserId']    =   false;
                $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_month();
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for current month'];
                    echo json_encode($message);
                }
            }
            //function to get the records of half yearly
            elseif($quaterValue == 7){
                $data['distributorid']  =   false;
                $data['cartItemId']     =   false;
                $data['salesUserId']    =   false;
                $data['dateFrom']   =   date("Y").'-1-01'." 00:00:00";
                $data['dateTo']     =   date("Y").'-6-30'." 23:59:59";
                $orderRecords  =   $this->Rochak_order_report_model->get_order_filter_logs_web($data);
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No order data found for the half yearly'];
                    echo json_encode($message);
                }
            }
            elseif($quaterValue == 8){
                $data['dateFrom']       =   false;
                $data['dateTo']         =   false;
                $data['distributorid']  =   false;
                $data['cartItemId']     =   false;
                $data['salesUserId']    =   false;
                $orderRecords  =   $this->Rochak_order_report_model->get_order_report_current_year();
                if($orderRecords){
                    $message    =   ['status' => 'success', 'message' => $orderRecords];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No collection data found for current year'];
                    echo json_encode($message);
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
        else if($this->input->post('distributorid',TRUE) && $this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
            echo 'OK 1';
            /*$data['dateFrom']       =   false;
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
            }*/
        }
        //filter get collections records by salesperson & distributor with date range
        else if($this->input->post('distributorid',TRUE) && $this->input->post('salesUserId',TRUE)){
            echo 'OK 2';
            /*$data['dateFrom']       =   false;
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
            }*/
        }
        //filter get collections records by distributor & items
        else if($this->input->post('distributorid',TRUE) && $this->input->post('cartItemId',TRUE)){
            echo 'OK 3';
            /*$data['dateFrom']           =   false;
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
            }*/
        }
        //filter get collections records by salesperson & items
        else if($this->input->post('salesUserId',TRUE) && $this->input->post('cartItemId',TRUE)){
            echo 'NOT 4';
            /*
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
            */
        }
        //filter get collections records by salesperson
        else if($this->input->post('salesUserId',TRUE)){
            echo 'OK 5';
            /*$data['dateFrom']       =   false;
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
            }*/
        }
        //filter get projections records by distributor
        else if($this->input->post('distributorid',TRUE)){
            echo 'OK 6';
            /*$data['dateFrom']       =   false;
            $data['dateTo']         =   false;
            $data['salesUserId']    =   false;
            $data['cartItemId']     =   false;
            $data['distributorid']  =   $this->input->post('distributorid',TRUE);
            
            $orderRecords  =   $this->Rochak_order_report_model->generate_order_report($data);
            if($orderRecords){
                $message    =   ['status' => 'success', 'message' => $orderRecords];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No order data found for this distributor'];
                echo json_encode($message);
            }*/
        }
        //filter get collections records by items
        else if($this->input->post('cartItemId',TRUE)){
            echo 'OK 7';
            /*$data['dateFrom']       =   false;
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
            }*/
        }        
        /*else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid Search'];
            echo json_encode($message);
        }*/
    }
}