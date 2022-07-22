<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rochakorders extends CI_Controller
{
    function __construct()
    {
        parent::__construct();	
        $this->load->model('Rochak_order_model');
        $this->load->helper('url');
	}
    
    /***************************************** CI View MODULE *****************************************/
    
    public function index()
	{
        if (isset($this->session->userdata['logged_web_user'])){
            $this->load->view('include/dashboard_header');
			$this->load->view('rochakorders/order');            
		}
        else{
			redirect('/login');
		}
	}

    public function addinvoice()
    {
        if(isset($this->session->userdata['logged_web_user'])){
            $this->load->view('include/dashboard_header');
            $this->load->view('rochakorders/addinvoice');
        }
        else{
            redirect('/login');
        }
    }
    
    /***************************************** ANGULAR API MODULE *****************************************/
        
    public function get_all_orders()
    {
        header('Content-Type: application/json');
        $ordersList              =   $this->Rochak_order_model->get_all_orders();        
        if(count($ordersList) >= 1){
            echo json_encode($ordersList);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Orders placed!!!'];
            echo json_encode($message);
        }
    }

    public function fetch_orders_data_invoice()
    {
        header('Content-Type: application/json');
        $id         =   $this->input->post('user_id');
        $ordersList              =   $this->Rochak_order_model->get_orders_list_app($id);
        
        //if(count($ordersList) >= 1 || $ordersList == true){
        if(!empty($ordersList)){
            // echo json_encode($ordersList);
            $message    =   ['status' => 'success', 'message' => $ordersList];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No data found!!!'];
            echo json_encode($message);
        }
    }
    
    public function get_single_order()
    {
        header('Content-Type: application/json');
		//$params   =   ['orderId' => $this->input->post('id')];
        $id         =   $this->input->post('id');
        
        $singleOderData   =   $this->Rochak_order_model->get_single_order($id);
        
        if(count($singleOderData) >= 1){
            //echo json_encode($singleOderData);
            $message    =   ['status' => 'success', 'orderdata' => $singleOderData];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Orders placed!!!'];
            echo json_encode($message);
        }
    }

    public function insert_invoice_data()
    {
        $data   =   [
                        'order_id'          =>  $this->input->post('order_id',TRUE),
                        'invoice_ins_id'    =>  $this->input->post('invoice_ins_id',TRUE),
                        'invoice_date'      =>  $this->input->post('invoice_date',TRUE),
                        'invoice_amount'    =>  $this->input->post('invoice_amount',TRUE)
                    ];

        $insert['insertstatus']     =   $this->Rochak_order_model->insert_add_invoice($data);

        if($insert['insertstatus'] > 0){            
            $message    =   ['status' => 'success', 'message' => 'Invoice Added Successfully'];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'error', 'message' => 'Something went wrong!!!'];
            echo json_encode($message);
        }
    }

    public function fetch_order_invoice(){
        $id     =   $this->input->post('id');
        $singleInvoiceData   =   $this->Rochak_order_model->get_order_invoice($id);
        if(count($singleInvoiceData) >= 1){
            $message    =   ['status' => 'success', 'invoicedata' => $singleInvoiceData];
            echo json_encode($message);
        }
        else{
            $array      =   [];
            $message    =   ['status' => 'failure', 'invoicedata' => $array];
            echo json_encode($message);
        }
    }

    public function add_invoice_data()
    {
        header('Content-Type: application/json');
        $id     =   $this->input->post('order_id');
        $input  =   $this->input->post('amount');
        $total  =   $this->Rochak_order_model->generate_sum_of_invoice($id);
        if($total != false){
            if($total >= $input){
                // echo $input;
                $data['order_id']   =   $this->input->post('order_id');
                $data['amount']     =   $this->input->post('amount');
                $response           =   $this->Rochak_order_model->update_invoice_amount($data);
                if($response){
                    $message    =   ['status' => 'success', 'message' => 'Invoices updated Successfully'];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'Due amount exceeds'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Input amount exceeds'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Data Found'];
            echo json_encode($message);
        }
    }

    public function get_order_invoice(){
        header('Content-Type: application/json');
        $response   =   $this->Rochak_order_model->fetch_invoice_data();
        $message    =   ['status' => 'success', 'message' => $response];
        echo json_encode($message);
    }

    public function distributor_invoice_payment_statement(){
        header('Content-Type: application/json');
        $response   =   $this->Rochak_order_model->fetch_invoice_collection_statement();
        $message    =   ['status' => 'success', 'message' => $response];
        echo json_encode($message);
    }

    public function group_invoice_data_by_distributors()
    {
        header('Content-Type: application/json');
        $distributor_id     =   $this->input->post('distributor_id');
        //$order_id     =   $this->input->post('order_id');
        $response           =   $this->Rochak_order_model->fetch_invoice_group_by_orders($distributor_id);
        
        if(count($response)){
            $message    =   ['status' => 'success', 'message' => $response];
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No data found'];
        }
        echo json_encode($message);
    }

    public function get_invoice_data_by_distributors()
    {
        header('Content-Type: application/json');
        //$distributor_id     =   $this->input->post('distributor_id');
        $order_id   =   $this->input->post('order_id');
        $response   =   $this->Rochak_order_model->fetch_invoice_data_by_distributors($order_id);
        
        if(count($response)){
            $message    =   ['status' => 'success', 'message' => $response];
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No data found'];
        }
        echo json_encode($message);
    }

    public function get_order_id_by_dist_sales()
    {
        header('Content-Type: application/json');
        $data['distributorId']  =   $this->input->post('distributor_id');
        $data['salesUserId']    =   $this->input->post('sales_user_id');
        $response               =   $this->Rochak_order_model->fetch_orders_by_distributors($data);
        
        if(count($response)){
            $message            =   ['status' => 'success', 'message' => $response];
        }
        else{
            $message            =   ['status' => 'failure', 'message' => 'No data found'];
        }
        echo json_encode($message);
    }
    //change & update order status
    public function update_status()
    {
        $data   =   [];
        
        if($this->input->post('order_id',TRUE)){
            $id   = $this->input->post('order_id');
            $data = [
                    'status'       =>  $this->input->post('status')             
                ];
            
            $updateOrder     =   $this->Rochak_order_model->update_status($data, $id);
            if($updateOrder  ==  1){
                $message    =   ['status' => 'success', 'message' => 'Order Status Changed Successfully'];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                echo json_encode($message);
            }
            
        }
    }
}