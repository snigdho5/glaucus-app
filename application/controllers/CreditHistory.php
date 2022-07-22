<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CreditHistory extends CI_Controller
{
    function __construct()
    {
        parent::__construct();	
        $this->load->model('Credit_projection_model');
        $this->load->helper('url');
	}
    /***************************************** WEB API MODULE *****************************************/
    
    function index()
    {
        if (isset($this->session->userdata['logged_web_user'])){
            $this->load->view('include/dashboard_header');
			$this->load->view('credithistory/index');            
		}else{
			redirect('/login');
		}
    }
    
    /***************************************** WEB API MODULE *****************************************/
    
    function get_credit_history_web()
    {
        header('Content-Type: application/json');
        
        $creditList              =   $this->Credit_projection_model->get_credit_history_web();        
        if(count($creditList) >= 1){
            echo json_encode($creditList);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Credit History found'];
            echo json_encode($message);
        }
    }
}