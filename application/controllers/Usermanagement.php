<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usermanagement extends CI_Controller
{
    function __construct()
    {
        parent::__construct();	
        $this->load->model('App_user_model');
        $this->load->model('Web_user_model');	
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/user/user');

		}else{
			redirect('/login');
		}
		
	}

	public function add()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('management/user/adduser');

		}else{
			redirect('/login');
		}
	}

	public function edit()
    {
		if (isset($this->session->userdata['logged_web_user'])) {
			$this->load->view('include/dashboard_header');
			$this->load->view('management/user/edituser');
		}else{
			redirect('/login');
		}
	}

	public function add_user()
	{
		/*if($this->input->post('userName',TRUE)){
			$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
			$params = array(
				'usrTypeId' => 3,
				'usrTypeName' => 'appuser',
				'usrFirstName' => $this->input->post('userFirstName'),
				'usrLastName' => $this->input->post('userLastName'),
				'usrContactNo' => $this->input->post('userContactNo'),
				'usrUserEmail' => $this->input->post('userEmail'),
				'usrUserName' => $this->input->post('userName'),
				'usrDesignation' => $this->input->post('userDesignation'),
				'usrAddress' => $this->input->post('userAddress'),
				'usrCountryId' => $this->input->post('userCountryId'),
				'usrCountry' => $this->input->post('userCountry'),
				'usrStateId' => $this->input->post('userStateId'),
				'usrState' => $this->input->post('userState'),
				'usrCityId' => $this->input->post('userCityId'),
				'usrCity' => $this->input->post('userCity'),
				'usrPassword' => $this->input->post('userPassword'),
				'usrParentId' => $this->session->userdata['logged_web_user']['userId'],
				'usrParentPath' => $parent_path,
				'usrStatusName' => 'active',
				'usrStatus' => 1,
			);

			$user_duplicate = $this->App_user_model->get_user_duplicate($params);

			if($user_duplicate == true){

				echo '{
					"status":"failed",
					"message":"Username already exist"
				}';

			}else{

				$user_id = $this->App_user_model->add_user($params);

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

		}*/
        
        $new_name                  =   time().$_FILES["file"]['name'];
	    $config['file_name']       =   $new_name;
	    $config['upload_path']     =   "uploads/kyc/salesperson/";
	    $config['allowed_types']   =   'pdf|doc|docx';
        
	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
        
        if($this->input->post('userName',TRUE)){
			$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
			$params =   [
                            'usrTypeId'         => 3,
                            'usrTypeName'       => 'appuser',
                            'usrFirstName'      => $this->input->post('userFirstName'),
                            'usrLastName'       => $this->input->post('userLastName'),
                            'usrContactNo'      => $this->input->post('userContactNo'),
                            'usrUserEmail'      => $this->input->post('userEmail'),
                            'usrUserName'       => $this->input->post('userName'),
                            'usrDesignation'    => $this->input->post('userDesignation'),
                            'usrAddress'        => $this->input->post('userAddress'),
                            'usrCountryId'      => $this->input->post('userCountryId'),
                            'usrCountry'        => $this->input->post('userCountry'),
                            'usrStateId'        => $this->input->post('userStateId'),
                            'usrState'          => $this->input->post('userState'),
                            'usrCityId'         => $this->input->post('userCityId'),
                            'usrCity'           => $this->input->post('userCity'),
                            'usrPassword'       => $this->input->post('userPassword'),
                            'usrParentId'       => $this->session->userdata['logged_web_user']['userId'],
                            'usrParentPath'     => $parent_path,
                            'usrStatusName'     => 'active',
                            'usrStatus'         => 1,
			         ];

			$user_duplicate = $this->App_user_model->get_user_duplicate($params);

			if($user_duplicate == true){
                $message    =   ['status' => 'success', 'message' => 'Username already exist'];
                echo json_encode($message);
			}else{
                //INSERT CODE
                if($this->upload->do_upload('file')){
                $uploadData          =     $this->upload->data();
                $uploadedFile        =     $uploadData['file_name'];
                $filenamewithpath    =     'uploads/kyc/salesperson/'.$uploadedFile;
                chmod($filenamewithpath, 0777);

                    if($uploadedFile != ''){
                        $parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
			            $params =   [
                            'usrTypeId'         => 3,
                            'usrTypeName'       => 'appuser',
                            'usrFirstName'      => $this->input->post('userFirstName'),
                            'usrLastName'       => $this->input->post('userLastName'),
                            'usrContactNo'      => $this->input->post('userContactNo'),
                            'usrUserEmail'      => $this->input->post('userEmail'),
                            'usrUserName'       => $this->input->post('userName'),
                            'usrDesignation'    => $this->input->post('userDesignation'),
                            'usrAddress'        => $this->input->post('userAddress'),
                            'usrCountryId'      => $this->input->post('userCountryId'),
                            'usrCountry'        => $this->input->post('userCountry'),
                            'usrStateId'        => $this->input->post('userStateId'),
                            'usrState'          => $this->input->post('userState'),
                            'usrCityId'         => $this->input->post('userCityId'),
                            'usrCity'           => $this->input->post('userCity'),
                            'usrPassword'       => $this->input->post('userPassword'),
                            'usrParentId'       => $this->session->userdata['logged_web_user']['userId'],
                            'usrParentPath'     => $parent_path,
                            'usrStatusName'     => 'active',
                            'usrStatus'         => 1,
                            'usrKycFile'        => $uploadedFile
			         ];                        
                        $user_id = $this->App_user_model->add_user($params);

                        if($user_id == 0){
                            
                            $message    =   ['status' => 'failed', 'message' => 'Signup Failed'];
                            echo json_encode($message);

                        }else{
                            
                            $message    =   ['status' => 'success', 'message' => 'Successfully Signup'];
                            echo json_encode($message);

                        }
                    }
                    else{
                        $message    =   ['status' => 'failed', 'message' => 'KYC Doc upload failed! Please try again.'];
                        echo json_encode($message);
                    }
                }
                else{
                    $message    =   ['status' => 'failed', 'message' => 'Directory Not Authorized'];
                    echo json_encode($message);
                }

			}

        }

    }

	public function update_user()
	{
        if(!empty($_FILES["file"]['name'])){
            //UPLOAD Updated File
            $id             =   $this->input->post('userId');
            $getKycFile     =   $this->App_user_model->delete_exist_file($id);
            if($getKycFile != ""){
                $oldKycfile     =   "uploads/kyc/salesperson/".$getKycFile;
                if (is_readable($oldKycfile) && unlink($oldKycfile)){
                    //Existing file will be deleted & replaced with the new one
                    $new_name                  =   time().$_FILES["file"]['name'];
                    $config['file_name']       =   $new_name;
                    $config['upload_path']     =   "uploads/kyc/salesperson/";
                    $config['allowed_types']   =   'pdf|doc|docx';

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('file')){
                        $uploadData          =     $this->upload->data();
                        $uploadedFile        =     $uploadData['file_name'];
                        $filenamewithpath    =     'uploads/kyc/salesperson/'.$uploadedFile;
                        chmod($filenamewithpath, 0777);
                        if($uploadedFile != ''){
                            if($this->input->post('userName',TRUE)){
                                $params = [
                                        'usrFirstName'      => $this->input->post('userFirstName'),
                                        'usrLastName'       => $this->input->post('userLastName'),
                                        'usrContactNo'      => $this->input->post('userContactNo'),
                                        'usrUserEmail'      => $this->input->post('userEmail'),
                                        'usrUserName'       => $this->input->post('userName'),
                                        'usrDesignation'    => $this->input->post('userDesignation'),
                                        'usrAddress'        => $this->input->post('userAddress'),
                                        'usrCountryId'      => $this->input->post('userCountryId'),
                                        'usrCountry'        => $this->input->post('userCountry'),
                                        'usrStateId'        => $this->input->post('userStateId'),
                                        'usrState'          => $this->input->post('userState'),
                                        'usrCityId'         => $this->input->post('userCityId'),
                                        'usrCity'           => $this->input->post('userCity'),
                                        'usrPassword'       => $this->input->post('userPassword'),
                                        'usrKycFile'        => $uploadedFile
                                        ];

                                $user_duplicate = $this->App_user_model->get_update_user_duplicate($this->input->post('userId'), $params);

                                if($user_duplicate == true){
                                    $message    =   ['status' => 'failed', 'message' => 'Username already exist'];
                                    echo json_encode($message);
                                }else{
                                    $mnlang_id = $this->App_user_model->update_user($this->input->post('userId'), $params);
                                    $message    =   ['status' => 'success', 'message' => 'User Updated Successfully'];
                                    echo json_encode($message);
                                }
                            }
                        }
                        else{
                            $message    =   ['status' => 'failed', 'message' => 'KYC Doc upload failed! Please try again.'];
                            echo json_encode($message);
                        }
                    }
                    else{
                        $message    =   ['status' => 'failed', 'message' => 'Directory Permission Not Authorized'];
                        echo json_encode($message);
                    }
                }
                else{
                    /*$message    =   ['status' => 'failed', 'message' => 'The file was not found or not readable and could not be deleted'];
                    echo json_encode($message);*/
                    //If the file is not found in the directory update with a new file
                    $new_name                  =   time().$_FILES["file"]['name'];
                    $config['file_name']       =   $new_name;
                    $config['upload_path']     =   "uploads/kyc/salesperson/";
                    $config['allowed_types']   =   'pdf|doc|docx';

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('file')){
                        $uploadData          =     $this->upload->data();
                        $uploadedFile        =     $uploadData['file_name'];
                        $filenamewithpath    =     'uploads/kyc/salesperson/'.$uploadedFile;
                        chmod($filenamewithpath, 0777);
                        if($uploadedFile != ''){
                            if($this->input->post('userName',TRUE)){
                                $params = [
                                            'usrFirstName'      => $this->input->post('userFirstName'),
                                            'usrLastName'       => $this->input->post('userLastName'),
                                            'usrContactNo'      => $this->input->post('userContactNo'),
                                            'usrUserEmail'      => $this->input->post('userEmail'),
                                            'usrUserName'       => $this->input->post('userName'),
                                            'usrDesignation'    => $this->input->post('userDesignation'),
                                            'usrAddress'        => $this->input->post('userAddress'),
                                            'usrCountryId'      => $this->input->post('userCountryId'),
                                            'usrCountry'        => $this->input->post('userCountry'),
                                            'usrStateId'        => $this->input->post('userStateId'),
                                            'usrState'          => $this->input->post('userState'),
                                            'usrCityId'         => $this->input->post('userCityId'),
                                            'usrCity'           => $this->input->post('userCity'),
                                            'usrPassword'       => $this->input->post('userPassword'),
                                            'usrKycFile'        => $uploadedFile
                                        ];

                            $user_duplicate = $this->App_user_model->get_update_user_duplicate($this->input->post('userId'), $params);

                            if($user_duplicate == true){
                                $message    =   ['status' => 'failed', 'message' => 'Username already exist'];
                                echo json_encode($message);
                            }else{
                                    $mnlang_id = $this->App_user_model->update_user($this->input->post('userId'), $params);
                                    $message    =   ['status' => 'success', 'message' => 'User Updated Successfully'];
                                    echo json_encode($message);
                                }
                            }
                        }
                        else{
                            $message    =   ['status' => 'failed', 'message' => 'KYC Doc upload failed! Please try again.'];
                            echo json_encode($message);
                        }
                    }
                    else{
                        $message    =   ['status' => 'failed', 'message' => 'Directory Permission Not Authorized'];
                        echo json_encode($message);
                    }
                }
            }
            else{
                //If the file is not found in the directory update with a new file
                $new_name                  =   time().$_FILES["file"]['name'];
                $config['file_name']       =   $new_name;
                $config['upload_path']     =   "uploads/kyc/salesperson/";
                $config['allowed_types']   =   'pdf|doc|docx';

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('file')){
                    $uploadData          =     $this->upload->data();
                    $uploadedFile        =     $uploadData['file_name'];
                    $filenamewithpath    =     'uploads/kyc/salesperson/'.$uploadedFile;
                    chmod($filenamewithpath, 0777);
                    if($uploadedFile != ''){
                        if($this->input->post('userName',TRUE)){
                            $params = [
                                        'usrFirstName'      => $this->input->post('userFirstName'),
                                        'usrLastName'       => $this->input->post('userLastName'),
                                        'usrContactNo'      => $this->input->post('userContactNo'),
                                        'usrUserEmail'      => $this->input->post('userEmail'),
                                        'usrUserName'       => $this->input->post('userName'),
                                        'usrDesignation'    => $this->input->post('userDesignation'),
                                        'usrAddress'        => $this->input->post('userAddress'),
                                        'usrCountryId'      => $this->input->post('userCountryId'),
                                        'usrCountry'        => $this->input->post('userCountry'),
                                        'usrStateId'        => $this->input->post('userStateId'),
                                        'usrState'          => $this->input->post('userState'),
                                        'usrCityId'         => $this->input->post('userCityId'),
                                        'usrCity'           => $this->input->post('userCity'),
                                        'usrPassword'       => $this->input->post('userPassword'),
                                        'usrKycFile'        => $uploadedFile
                                    ];

                        $user_duplicate = $this->App_user_model->get_update_user_duplicate($this->input->post('userId'), $params);

                        if($user_duplicate == true){
                            $message    =   ['status' => 'failed', 'message' => 'Username already exist'];
                            echo json_encode($message);
                        }else{
                                $mnlang_id = $this->App_user_model->update_user($this->input->post('userId'), $params);
                                $message    =   ['status' => 'success', 'message' => 'User Updated Successfully'];
                                echo json_encode($message);
                            }
                        }
                    }
                    else{
                        $message    =   ['status' => 'failed', 'message' => 'KYC Doc upload failed! Please try again.'];
                        echo json_encode($message);
                    }
                }
                else{
                    $message    =   ['status' => 'failed', 'message' => 'Directory Permission Not Authorized'];
                    echo json_encode($message);
                }
            }
        }
        else{
            if($this->input->post('userName',TRUE)){
                $params = array(
                    'usrFirstName'      => $this->input->post('userFirstName'),
                    'usrLastName'       => $this->input->post('userLastName'),
                    'usrContactNo'      => $this->input->post('userContactNo'),
                    'usrUserEmail'      => $this->input->post('userEmail'),
                    'usrUserName'       => $this->input->post('userName'),
                    'usrDesignation'    => $this->input->post('userDesignation'),
                    'usrAddress'        => $this->input->post('userAddress'),
                    'usrCountryId'      => $this->input->post('userCountryId'),
                    'usrCountry'        => $this->input->post('userCountry'),
                    'usrStateId'        => $this->input->post('userStateId'),
                    'usrState'          => $this->input->post('userState'),
                    'usrCityId'         => $this->input->post('userCityId'),
                    'usrCity'           => $this->input->post('userCity'),
                    'usrPassword'       => $this->input->post('userPassword')
                );

                $user_duplicate = $this->App_user_model->get_update_user_duplicate($this->input->post('userId'), $params);

                if($user_duplicate == true){
                    $message    =   ['status' => 'failed', 'message' => 'Username already exist'];
                    echo json_encode($message);
                }else{
                    $mnlang_id = $this->App_user_model->update_user($this->input->post('userId'), $params);
                    $message    =   ['status' => 'success', 'message' => 'User Updated Successfully'];
                    echo json_encode($message);
                }
            }
		}

	}

	public function get_all_app_users()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$appuserlist= $this->App_user_model->get_all_app_users($parent_path);

		$c=count($appuserlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = [
        				'ausrId'            => $appuserlist[$i]['usrId'],
        				'ausrFirstName'     => $appuserlist[$i]['usrFirstName'],
        				'ausrLastName'      => $appuserlist[$i]['usrLastName'],
        				'ausrContactNo'     => $appuserlist[$i]['usrContactNo'],
        				'ausrUserEmail'     => $appuserlist[$i]['usrUserEmail'],
        				'ausrUserName'      => $appuserlist[$i]['usrUserName'],
        				'ausrDesignation'   => $appuserlist[$i]['usrDesignation'],
        				'ausrPassword'      => $appuserlist[$i]['usrPassword'],
        				'ausrParentId'      => $appuserlist[$i]['usrParentId'],
        				'ausrStatusName'    => $appuserlist[$i]['usrStatusName'],
        				'ausrStatus'        => $appuserlist[$i]['usrStatus'],
        				'ausrParentName'    => $appuserlist[$i]['parentName'],
        				'ausrKycdoc'        => $appuserlist[$i]['usrKycFile'],
                        'ausrlogStatus'     => $appuserlist[$i]['login_status']
        			];

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}

	public function get_user_detail()
    {
		header('Content-Type: application/json');

		$params = array(
				'usrId' => $this->input->get('id')
			);

		$userdata = $this->App_user_model->get_user_data($params);

		$data = array(
                    'ausrId'            => $userdata['usrId'],
                    'ausrFirstName'     => $userdata['usrFirstName'],
                    'ausrLastName'      => $userdata['usrLastName'],
                    'ausrContactNo'     => $userdata['usrContactNo'],
                    'ausrUserEmail'     => $userdata['usrUserEmail'],
                    'ausrUserName'      => $userdata['usrUserName'],
                    'ausrDesignation'   => $userdata['usrDesignation'],
                    'ausrAddress'       => $userdata['usrAddress'],
                    'ausrCountryId'     => $userdata['usrCountryId'],
                    'ausrCountry'       => $userdata['usrCountry'],
                    'ausrStateId'       => $userdata['usrStateId'],
                    'ausrState'         => $userdata['usrState'],
                    'ausrCityId'        => $userdata['usrCityId'],
                    'ausrCity'          => $userdata['usrCity'],
                    'ausrPassword'      => $userdata['usrPassword'],
                    'ausrParentId'      => $userdata['usrParentId'],
                    'ausrStatus'        => $userdata['usrStatus'],  
                    'ausrKycFile'       => $userdata['usrKycFile']
			     );

			$json = json_encode($data);

			echo $json;
	}

	public function change_status()
	{
		if($this->input->post('ausrId',TRUE)){

			$params =   [
            				'usrStatus'     =>  $this->input->post('ausrStatus'),
            				'usrStatusName' =>  $this->input->post('ausrStatusName')
            			];

			$user_status_id = $this->App_user_model->update_status($this->input->post('ausrId'), $params);

			if($user_status_id == true){
                $data   =   ['status' => 'success', 'message' => 'Status Changed Successfully'];
                echo json_encode($data);
			}
            else{
                $data   =   ['status' => 'failed', 'message' => 'Language Status Change Failed'];
                echo json_encode($data);
			}
		}
	}

    public function user_app_logout()
    {
        if($this->input->post('ausrId',TRUE)){
            $params =   ['login_status'  =>  0];
            $user_status_id = $this->App_user_model->update_status($this->input->post('ausrId'), $params);

            if($user_status_id == true){
                $data   =   ['status' => 'success', 'message' => 'App user logged out Successfully'];
                echo json_encode($data);
            }
            else{
                $data   =   ['status' => 'failed', 'message' => 'App user already logged out'];
                echo json_encode($data);
            }
        }
    }

	public function get_all_admins()
	{
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$adminlist= $this->Web_user_model->get_all_web_users($parent_path);

		header('Content-Type: application/json');

		$c=count($adminlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'adminId' => $adminlist[$i]['usrId'],
				'adminFirstName' => $adminlist[$i]['usrFirstName'],
				'adminLastName' => $adminlist[$i]['usrLastName'],
				'adminUserName' => $adminlist[$i]['usrUserName'],
				'adminUserEmail' => $adminlist[$i]['usrUserEmail'],
				'adminPassword' => $adminlist[$i]['usrPassword'],
				'adminParentId' => $adminlist[$i]['usrParentId'],
				'adminParentPath' => $adminlist[$i]['usrParentPath'].'-'.$adminlist[$i]['usrId'],
				'adminParentName' => $adminlist[$i]['parentName'],
				'adminStatusName' => $adminlist[$i]['usrStatusName'],
				'adminStatus' => $adminlist[$i]['usrStatus']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}

	public function update_created_by()
	{
		if($this->input->post('userId',TRUE)){
			$params = array(
				'usrParentId' => $this->input->post('userParentId'),
				'usrParentPath' => $this->input->post('userParentPath')
			);

			$mnlang_id = $this->App_user_model->update_user($this->input->post('userId'), $params);
			echo '{
				"status":"success",
				"message":"Created By Updated Successfully"
			}';

			
		}

	}
    
    public function get_sales_kyc()
    {
        header('Content-Type: application/json');
        $id         =   $this->input->post('id');        
        $kycData   =   $this->App_user_model->get_user_kyc($id);
        if(count($kycData) == 1){
            //echo json_encode($singleOderData);
            $message    =   ['status' => 'success', 'kycdata' => $kycData];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No user info found!!!'];
            echo json_encode($message);
        }
    }
}