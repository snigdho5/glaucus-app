<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Web_user_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		$this->load->view('include/session_header');
		$this->load->view('session/signup');
	}

	public function user_signup()
	{
		if($this->input->post('userName',TRUE)){
			$params = array(
				'usrTypeId' => 1,
				'usrTypeName' => 'superadmin',
				'usrUserEmail' => $this->input->post('userEmail'),
				'usrUserName' => $this->input->post('userName'),
				'usrPassword' => $this->input->post('userPassword'),
				'usrUnitAddPermission' => 'true',
				'usrUnitEditPermission' => 'true',
				'usrVenueAddPermission' => 'true',
				'usrVenueEditPermission' => 'true',
				'usrParentPath' => '0',
				'usrStatusName' => 'active',
				'usrStatus' => 1
			);

			$user_duplicate = $this->Web_user_model->get_user_duplicate($params);

			if($user_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Username already exist"
				}';

			}else{

				$user_id = $this->Web_user_model->add_user_signup($params);

				if($user_id == 0){

					echo '{
						"status":"failed",
						"message":"Signup Failed"
					}';

				}else{

					echo '{
						"status":"success",
						"message":"Successfully Signup"
					}';

				}

			}

		}

	}
}
