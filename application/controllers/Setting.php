<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->model('Setting_model');
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('setting/setting');

		}else{
			redirect('/login');
		}
		
	}



	public function update_password()
	{
		if($this->input->post('userCurrentPassword',TRUE)){
			$params = array(
				'usrId' => $this->session->userdata['logged_web_user']['userId'],
				'usrPassword' => $this->input->post('userCurrentPassword')
			);

			$params_update = array(
				'usrPassword' => $this->input->post('userNewPassword')
			);

			$check_current_password = $this->Setting_model->check_current_password($params);

			if($check_current_password == true){

				$userdata = $this->Setting_model->update_password($this->session->userdata['logged_web_user']['userId'], $params_update);

				echo '{
					"status":"success",
					"message":"Password Changed Successfully"
				}';

			}else{

				echo '{
					"status":"failed",
					"message":"Current Password is Wrong"
				}';

			}
		}

	}





}