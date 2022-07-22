<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();	
        $this->load->model('Web_user_model');
        $this->load->model('App_user_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		$this->load->view('include/session_header');
		$this->load->view('session/login');
	}

	public function user_login()
	{
		if($this->input->post('userName',TRUE)){
			$params = array(
				'usrUserName' => $this->input->post('userName'),
				'usrPassword' => $this->input->post('userPassword'),
				'usrStatus' => 1
			);

			$login_result = $this->Web_user_model->get_user_login($params);

			if($login_result == true){

				$login_check = $this->Web_user_model->check_user_login($params);

				if($login_check == true){

					$userdata = $this->Web_user_model->get_user_data($params);


					if($userdata['usrTypeName']=="appuser"){

						$session_data = array(
							'userId' => $userdata['usrId'],
							'userName' => $userdata['usrUserName'],
							'userTypeId' => $userdata['usrTypeId'],
							'userTypeName' => $userdata['usrTypeName'],
							'userParentPath' => $userdata['usrParentPath']
						);

						$this->session->set_userdata('logged_app_user', $session_data);

						$data = array(
							'status' => 'success',
							'message' => 'Login Successfully',
							'userId' => $userdata['usrId'],
							'userName' => $userdata['usrUserName'],
							'userTypeId' => $userdata['usrTypeId'],
							'userTypeName' => $userdata['usrTypeName']
						);
						$json = json_encode($data);
						echo $json;

					}else{

						$session_data = array(
							'userId' => $userdata['usrId'],
							'userName' => $userdata['usrUserName'],
							'userTypeId' => $userdata['usrTypeId'],
							'userTypeName' => $userdata['usrTypeName'],
							'userParentPath' => $userdata['usrParentPath'],
							'userUnitAddPermission' => $userdata['usrUnitAddPermission'],	
							'userUnitEditPermission' => $userdata['usrUnitEditPermission'],	
							'userVenueAddPermission' => $userdata['usrVenueAddPermission'],	
							'userVenueEditPermission' => $userdata['usrVenueEditPermission']
						);

						$this->session->set_userdata('logged_web_user', $session_data);

						$check_old_login_location = $this->App_user_model->check_old_login_location_record();

						if($check_old_login_location == true){
							$delete_old_login_location = $this->App_user_model->delete_old_login_location_record();

							$check_old_trip_location = $this->App_user_model->check_old_trip_location_record();

							if($check_old_trip_location == true){
								$delete_old_trip_location = $this->App_user_model->delete_old_trip_location_record();
							}
						}

						$data = array(
							'status' => 'success',
							'message' => 'Login Successfully',
							'userId' => $userdata['usrId'],
							'userName' => $userdata['usrUserName'],
							'userTypeId' => $userdata['usrTypeId'],
							'userTypeName' => $userdata['usrTypeName']
						);
						$json = json_encode($data);
						echo $json;

					}


					

				}else{

					echo '{
						"status":"failed",
						"message":"Account is deactive, Please contact Head Admin"
					}';

				}



			}else{

				echo '{
					"status":"failed",
					"message":"Username or Password is wrong"
				}';

			}
		}

	}







}