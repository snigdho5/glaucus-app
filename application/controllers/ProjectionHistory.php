<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProjectionHistory extends CI_Controller
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
			$this->load->view('projectionhistory/index');            
		}else{
			redirect('/login');
		}
    }
    
    /***************************************** WEB API MODULE *****************************************/
    
    function get_projection_history_web()
    {
        header('Content-Type: application/json');
        
        $projectionList              =   $this->Credit_projection_model->get_projection_history_web();        
        if(count($projectionList) >= 1){
            $message    =   ['status' => 'success', 'projectiondata' => $projectionList];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Projection History found'];
            echo json_encode($message);
        }
    }
    
    function get_single_projection_web()
    {
        header('Content-Type: application/json');
        
        $id         =   $this->input->post('masterProjectionId');
        
        $singleProjectionData   =   $this->Credit_projection_model->get_single_projection_web($id);
        
        if(count($singleProjectionData) >= 1){
            $message    =   ['status' => 'success', 'projectiondata' => $singleProjectionData];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Orders placed!!!'];
            echo json_encode($message);
        }
    }
}