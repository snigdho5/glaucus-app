<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distributors extends CI_Controller
{    
    function __construct()
    {
        parent::__construct();
        $this->load->model('Distributor_model');
        $this->load->helper(array('form', 'url','file'));
		$this->load->library('form_validation');
        $this->load->library('upload');
	}
    
    public function index($page = 'distributors')
    {
        if (isset($this->session->userdata['logged_web_user'])){            
            if(!file_exists(APPPATH . 'views/distributor/' . $page . '.php')){
                show_404();
            }
            else{
                $this->load->view('include/dashboard_header');
			    $this->load->view('distributor/distributors');
            }
		}
        else{
			redirect('/login');
		}
    }
    
    /**************************************ADD Distributors CI**************************************/
    
    public function alldistricts()
    {
        echo $this->Distributor_model->get_districts_list();
    }
    
    public function add()
    {
        if (isset($this->session->userdata['logged_web_user'])) {
			$this->load->view('include/dashboard_header');
			$this->load->view('distributor/add_distributor');
		}
        else{
			redirect('/login');
		}
    }
    
    public function add_distributor()
    {
        $data   =   [];
        
        $new_name                  =   time().$_FILES["file"]['name'];
	    $config['file_name']       =   $new_name;
	    $config['upload_path']     =   "uploads/kyc/distributor/";
	    $config['allowed_types']   =   'pdf|doc|docx';
        
	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
        
        /********************Restrict Redundant Phone & GSTIN Update********************/
        //ALL TABLE Redundant
        $wherePhone     =   ['distributorPhone' => $this->input->post('distributorPhone')];
        $whereGstin     =   ['distributorGstNo' => $this->input->post('distributorGstNo')];
        $whereEmail     =   ['distributorEmail' => $this->input->post('distributorEmail')];
        
        $redundantPhone     =   '';
        $redundantGstin     =   '';
        $redundantEmail     =   '';
        $redundantPhone     =   $this->Distributor_model->check_on_add_redundant_phone($wherePhone);
        if($this->input->post('distributorGstNo')){
            $redundantGstin     =   $this->Distributor_model->check_on_add_redundant_gstin($whereGstin);
        }
        if($this->input->post('distributorEmail')){
            $redundantEmail     =   $this->Distributor_model->check_on_add_redundant_email($whereEmail);
        }
        
        if($redundantGstin == 1){
            $message    =   ['status' => 'failed', 'message' => 'GSTIN already exists'];
            echo json_encode($message);
            die();
        }
        if($redundantPhone == 1){
            $message    =   ['status' => 'failed', 'message' => 'Phone Number already exists'];
            echo json_encode($message);
            die();
        }
        if($redundantEmail == 1){
            $message    =   ['status' => 'failed', 'message' => 'Email already exists'];
            echo json_encode($message);
            die();
        }
        else{
            //INSERT CODE
            if($this->upload->do_upload('file')){
            $uploadData          =     $this->upload->data();
            $uploadedFile        =     $uploadData['file_name'];
            $filenamewithpath    =     'uploads/kyc/distributor/'.$uploadedFile;
            chmod($filenamewithpath, 0777);

                if($uploadedFile != ''){

                   if($this->input->post('distributorName',TRUE)){
                        if($this->input->post('creditLimit')){
                            $creditLimit    =   $this->input->post('creditLimit');
                        }
                       else{
                           $creditLimit     =   0;
                       }
                        $data = [
                                    'distributorName'       =>  $this->input->post('distributorName'),
                                    'district'              =>  $this->input->post('district'),
                                    'distributorType'       =>  $this->input->post('distributorType'),
                                    'distributorPhone'      =>  $this->input->post('distributorPhone'),
                                    'distributorEmail'      =>  $this->input->post('distributorEmail'),
                                    'distributorAddress'    =>  $this->input->post('distributorAddress'),
                                    'distributorGstNo'      =>  $this->input->post('distributorGstNo'),
                                    'creditLimit'           =>  $creditLimit,
                                    'distributorDob'        =>  $this->input->post('distributorDob'),
                                    'discount'              =>  $this->input->post('distributordiscount'),
                                    'distributorKycFile'    =>  $uploadedFile
                                ];
                        $insertDistributor     =   $this->Distributor_model->insert_distributor($data);
                        if($insertDistributor  >  0){
                            $message    =   ['status' => 'success', 'message' => 'Distributor Added Successfully'];
                            echo json_encode($message);
                        }
                        else{
                            $message    =   ['status' => 'failed', 'message' => 'No New Distributor to Add'];
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
                $message    =   ['status' => 'failed', 'message' => 'Directory Not Authorized'];
                echo json_encode($message);
            }
        }
                
        /********************Restrict Redundant Phone & GSTIN Update********************/
        
        
        /*if($this->input->post('distributorName',TRUE)){
			$data = [
                    'distributorName'       => $this->input->post('distributorName'),
                    'district'              => $this->input->post('district'),
                    'distributorType'       => $this->input->post('distributorType'),
                    'distributorPhone'      => $this->input->post('distributorPhone'),
                    'distributorAddress'    => $this->input->post('distributorAddress'),
                    'distributorGstNo'      => $this->input->post('distributorGstNo')
			     ];
            
            $insertDistributor     =   $this->Distributor_model->insert_distributor($data);
            if($insertDistributor  >  0){
                $message    =   ['status' => 'success', 'message' => 'Distributor Added Successfully'];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failed', 'message' => 'No New Distributor to Add'];
                echo json_encode($message);
            }
            
        }*/
    } 
    
    public function list_all_distributors()
    {
        header('Content-Type: application/json');
        $get_sales_id       =   $this->input->post('user_id');
        if($get_sales_id != 0){
            $distributorlist    =   $this->Distributor_model->get_distributors_list_app($get_sales_id);
            if($distributorlist)
                $message    =   ['status' => 'success', 'message' => $distributorlist];
            else
                $message    =   ['status' => 'failed', 'message' => 'No Distributor Found'];
            echo json_encode($message);
        }
        else{
            $distributorlist    =   $this->Distributor_model->get_distributors_list();
            echo $distributorlist;
        }
        //echo $distributorlist;
    }
    
    /**************************************ADD Distributors  CI**************************************/
    
    /**************************************EDIT Distributors CI**************************************/
    
    
    public function get_distributor_detail()
    {
        header('Content-Type: application/json');

		$params   =   ['distributorId' => $this->input->get('id')];
        
        $distributorData   =   $this->Distributor_model->get_distributor_data($params);
        
        $data   =   [
                        'distributorId'         =>      $distributorData['distributorId'],
                        'distributorName'       =>      $distributorData['distributorName'],
                        'district'              =>      $distributorData['district'],
                        'distributorType'       =>      $distributorData['distributorType'],
                        'distributorPhone'      =>      $distributorData['distributorPhone'],
                        'distributorEmail'      =>      $distributorData['distributorEmail'],
                        'distributorAddress'    =>      $distributorData['distributorAddress'],
                        'distributorGstNo'      =>      $distributorData['distributorGstNo'],
                        'distributorDob'        =>      $distributorData['distributorDob'],
                        'creditLimit'           =>      $distributorData['creditLimit'],
                        'distributorKyc'        =>      $distributorData['distributorKycFile'],
                        'discount'              =>      $distributorData['discount']
                    ];
        echo json_encode($data);
    }    
    
    public function edit()
    {
        if (isset($this->session->userdata['logged_web_user'])) {
			$this->load->view('include/dashboard_header');
			$this->load->view('distributor/edit');
		}
        else{
			redirect('/login');
		}
    }
    
    public function update_distributor()
    {
        $data           =   [];
        $uploadPath     =   "uploads/kyc/distributor/";
        
        if(!empty($_FILES["file"]['name'])){
            //UPLOAD Updated File
            $id             =   $this->input->post('distributorId');
            $getKycFile     =   $this->Distributor_model->delete_exist_file($id);
            
        if($getKycFile != ""){
            $oldKycfile     =   "uploads/kyc/distributor/".$getKycFile;
            
            if (is_readable($oldKycfile) && unlink($oldKycfile)){
                //Existing file will be deleted & replaced with the new one
                $new_name                  =   time().$_FILES["file"]['name'];
                $config['file_name']       =   $new_name;
                $config['upload_path']     =   "uploads/kyc/distributor/";
                $config['allowed_types']   =   'pdf|doc|docx';

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('file')){
                    $uploadData          =     $this->upload->data();
                    $uploadedFile        =     $uploadData['file_name'];
                    $filenamewithpath    =     'uploads/kyc/distributor/'.$uploadedFile;
                    chmod($filenamewithpath, 0777);

                    if($uploadedFile != ''){

                        if($this->input->post('distributorName',TRUE)){
                            $data = [
                                    'distributorName'       =>  $this->input->post('distributorName'),
                                    'district'              =>  $this->input->post('district'),
                                    'distributorType'       =>  $this->input->post('distributorType'),
                                    'distributorPhone'      =>  $this->input->post('distributorPhone'),
                                    'distributorEmail'      =>  $this->input->post('distributorEmail'),
                                    'distributorAddress'    =>  $this->input->post('distributorAddress'),
                                    'distributorGstNo'      =>  $this->input->post('distributorGstNo'),
                                    'distributorDob'        =>  $this->input->post('distributorDob'),
                                    'creditLimit'           =>  $this->input->post('creditLimit'),
                                    'discount'              =>  $this->input->post('discount'),
                                    'distributorKycFile'    =>  $uploadedFile
                                 ];
                            /***************************Normal Casual Update******************************/
                            /*$updateDistributor     =   $this->Distributor_model->update_distributor_data($data, $id);
                            if($updateDistributor  ==  1){
                                $message    =   ['status' => 'success', 'message' => 'Distributor Updated Successfully'];
                                echo json_encode($message);
                            }
                            else{
                                $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                                echo json_encode($message);
                            }*/
                            /*****************************************************************************/
                            
                            /********************Restrict Redundant Phone & GSTIN Update********************/
                            
                            //Variable Declarations
                            $wherePhone             =       '';
                            $whereGstin             =       '';
                            $whereEmail             =       '';
                            $redundantPhone         =       '';
                            $redundantGstin         =       '';
                            $redundantEmail         =       '';

                            //ALL TABLE Redundant
                            $wherePhone     =   ['distributorId' => $id, 'distributorPhone' => $this->input->post('distributorPhone')];
                            if($this->input->post('distributorGstNo')){
                                $whereGstin     =   ['distributorId' => $id, 'distributorGstNo' => $this->input->post('distributorGstNo')];
                            }
                            if($this->input->post('distributorEmail')){
                                $whereEmail     =   ['distributorId' => $id, 'distributorEmail' => $this->input->post('distributorEmail')];
                            }

                            $redundantPhone     =   $this->Distributor_model->check_redundant_phone($wherePhone);
                            if($this->input->post('distributorGstNo')){
                                $redundantGstin     =   $this->Distributor_model->check_redundant_gstin($whereGstin);
                            }
                            if($this->input->post('distributorEmail')){
                                $redundantEmail     =   $this->Distributor_model->check_redundant_email($whereEmail);
                            }

                            if($redundantPhone <= 1 && $redundantGstin <= 1 && $redundantEmail <= 1){
                                if($redundantGstin == 1){
                                    $message    =   ['status' => 'failed', 'message' => 'GSTIN already exists'];
                                    unlink($uploadPath.$uploadedFile);
                                    echo json_encode($message);
                                    die();
                                }
                                if($redundantPhone == 1){
                                    $message    =   ['status' => 'failed', 'message' => 'Phone Number already exists'];
                                    unlink($uploadPath.$uploadedFile);
                                    echo json_encode($message);
                                    die();
                                }
                                if($redundantEmail == 1){
                                    $message    =   ['status' => 'failed', 'message' => 'Email already exists 222'];
                                    unlink($uploadPath.$uploadedFile);
                                    echo json_encode($message);
                                    die();
                                }
                                else{
                                    $updateDistributor     =   $this->Distributor_model->update_distributor_data($data, $id);
                                    if($updateDistributor  ==  1){
                                        $message    =   ['status' => 'success', 'message' => 'Distributor Updated Successfully'];
                                        echo json_encode($message);
                                    }
                                    else{
                                        $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                                        echo json_encode($message);
                                    }
                                }
                            }
                            else{
                                $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                                echo json_encode($message);
                            }
                            /********************Restrict Redundant Phone & GSTIN Update********************/
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
                //If file name in DB & file is not found in directory then this function will trigger for single file change
                $new_name                  =   time().$_FILES["file"]['name'];
                $config['file_name']       =   $new_name;
                $config['upload_path']     =   "uploads/kyc/distributor/";
                $config['allowed_types']   =   'pdf|doc|docx';

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('file')){
                    $uploadData          =     $this->upload->data();
                    $uploadedFile        =     $uploadData['file_name'];
                    $filenamewithpath    =     'uploads/kyc/distributor/'.$uploadedFile;
                    chmod($filenamewithpath, 0777);

                    if($uploadedFile != ''){

                        if($this->input->post('distributorName',TRUE)){
                            $data = [
                                    'distributorName'       => $this->input->post('distributorName'),
                                    'district'              => $this->input->post('district'),
                                    'distributorType'       => $this->input->post('distributorType'),
                                    'distributorPhone'      => $this->input->post('distributorPhone'),
                                    'distributorEmail'      => $this->input->post('distributorEmail'),
                                    'distributorAddress'    => $this->input->post('distributorAddress'),
                                    'distributorGstNo'      => $this->input->post('distributorGstNo'),
                                    'distributorDob'        => $this->input->post('distributorDob'),
                                    'creditLimit'           => $this->input->post('creditLimit'),
                                    'discount'              =>  $this->input->post('discount'),
                                    'distributorKycFile'    => $uploadedFile
                                ];                            
                            
                        /********************Restrict Redundant Phone, Email & GSTIN  Update********************/
                        //Variable Declarations
                        $wherePhone             =       '';
                        $whereGstin             =       '';
                        $whereEmail             =       '';
                        $redundantPhone         =       '';
                        $redundantGstin         =       '';
                        $redundantEmail         =       '';
                        
                        //ALL TABLE Redundant
                        $wherePhone     =   ['distributorId' => $id, 'distributorPhone' => $this->input->post('distributorPhone')];
                        if($this->input->post('distributorGstNo')){
                            $whereGstin     =   ['distributorId' => $id, 'distributorGstNo' => $this->input->post('distributorGstNo')];
                        }
                        if($this->input->post('distributorEmail')){
                            $whereEmail     =   ['distributorId' => $id, 'distributorEmail' => $this->input->post('distributorEmail')];
                        }

                        $redundantPhone     =   $this->Distributor_model->check_redundant_phone($wherePhone);
                        if($this->input->post('distributorGstNo')){
                            $redundantGstin     =   $this->Distributor_model->check_redundant_gstin($whereGstin);
                        }
                        if($this->input->post('distributorEmail')){
                            $redundantEmail     =   $this->Distributor_model->check_redundant_email($whereEmail);
                        }

                        if($redundantPhone <= 1 && $redundantGstin <= 1 && $redundantEmail <= 1){
                            if($redundantGstin == 1){
                                $message    =   ['status' => 'failed', 'message' => 'GSTIN already exists'];
                                unlink($uploadPath.$uploadedFile);
                                echo json_encode($message);
                                die();
                            }
                            if($redundantPhone == 1){
                                $message    =   ['status' => 'failed', 'message' => 'Phone Number already exists'];
                                unlink($uploadPath.$uploadedFile);
                                echo json_encode($message);
                                die();
                            }
                            if($redundantEmail == 1){
                                $message    =   ['status' => 'failed', 'message' => 'Email already exists 222'];
                                unlink($uploadPath.$uploadedFile);
                                echo json_encode($message);
                                die();
                            }
                            else{
                                $updateDistributor     =   $this->Distributor_model->update_distributor_data($data, $id);
                                if($updateDistributor  ==  1){
                                    $message    =   ['status' => 'success', 'message' => 'Distributor Updated Successfully'];
                                    echo json_encode($message);
                                }
                                else{
                                    $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                                    echo json_encode($message);
                                }
                            }
                        }
                        else{
                            $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                            echo json_encode($message);
                        }
                        /********************Restrict Redundant Phone & GSTIN Update********************/
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
            //If file & file name is not found in DB & directory then this function will trigger for single file change
            $new_name                  =   time().$_FILES["file"]['name'];
            $config['file_name']       =   $new_name;
            $config['upload_path']     =   "uploads/kyc/distributor/";
            $config['allowed_types']   =   'pdf|doc|docx';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if($this->upload->do_upload('file')){
                $uploadData          =     $this->upload->data();
                $uploadedFile        =     $uploadData['file_name'];
                $filenamewithpath    =     'uploads/kyc/distributor/'.$uploadedFile;
                chmod($filenamewithpath, 0777);

                if($uploadedFile != ''){

                    if($this->input->post('distributorName',TRUE)){
                        $data = [
                                    'distributorName'       => $this->input->post('distributorName'),
                                    'district'              => $this->input->post('district'),
                                    'distributorType'       => $this->input->post('distributorType'),
                                    'distributorPhone'      => $this->input->post('distributorPhone'),
                                    'distributorEmail'      => $this->input->post('distributorEmail'),
                                    'distributorAddress'    => $this->input->post('distributorAddress'),
                                    'distributorGstNo'      => $this->input->post('distributorGstNo'),
                                    'distributorDob'        => $this->input->post('distributorDob'),
                                    'creditLimit'           => $this->input->post('creditLimit'),
                                    'discount'              =>  $this->input->post('discount'),
                                    'distributorKycFile'    => $uploadedFile
                                ];
                            
                        /********************Restrict Redundant Phone, Email & GSTIN Update********************/
                        //Variable Declarations
                            $wherePhone             =       '';
                            $whereGstin             =       '';
                            $whereEmail             =       '';
                            $redundantPhone         =       '';
                            $redundantGstin         =       '';
                            $redundantEmail         =       '';                            

                            //ALL TABLE Redundant
                            $wherePhone     =   ['distributorId' => $id, 'distributorPhone' => $this->input->post('distributorPhone')];
                            if($this->input->post('distributorGstNo')){
                                $whereGstin     =   ['distributorId' => $id, 'distributorGstNo' => $this->input->post('distributorGstNo')];
                            }
                            if($this->input->post('distributorEmail')){
                                $whereEmail     =   ['distributorId' => $id, 'distributorEmail' => $this->input->post('distributorEmail')];
                            }

                            $redundantPhone     =   $this->Distributor_model->check_redundant_phone($wherePhone);
                            if($this->input->post('distributorGstNo')){
                                $redundantGstin     =   $this->Distributor_model->check_redundant_gstin($whereGstin);
                            }
                            if($this->input->post('distributorEmail')){
                                $redundantEmail     =   $this->Distributor_model->check_redundant_email($whereEmail);
                            }

                            if($redundantPhone <= 1 && $redundantGstin <= 1 && $redundantEmail <= 1){
                                if($redundantGstin == 1){
                                    $message    =   ['status' => 'failed', 'message' => 'GSTIN already exists'];
                                    unlink($uploadPath.$uploadedFile);
                                    echo json_encode($message);
                                    die();
                                }
                                if($redundantPhone == 1){
                                    $message    =   ['status' => 'failed', 'message' => 'Phone Number already exists'];
                                    unlink($uploadPath.$uploadedFile);
                                    echo json_encode($message);
                                    die();
                                }
                                if($redundantEmail == 1){
                                    $message    =   ['status' => 'failed', 'message' => 'Email already exists 333'];
                                    unlink($uploadPath.$uploadedFile);
                                    echo json_encode($message);
                                    die();
                                }
                                else{
                                    $updateDistributor     =   $this->Distributor_model->update_distributor_data($data, $id);
                                    if($updateDistributor  ==  1){
                                        $message    =   ['status' => 'success', 'message' => 'Distributor Updated Successfully'];
                                        echo json_encode($message);
                                    }
                                    else{
                                        $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                                        echo json_encode($message);
                                    }
                                }
                            }
                        /********************Restrict Redundant Phone & GSTIN Update********************/
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
            //if no new file upload only changes in the fields data then this condition will be triggered
            if($this->input->post('distributorId',TRUE)){
            $id   = $this->input->post('distributorId');
			$data = [
                    'distributorName'       =>  $this->input->post('distributorName'),
                    'district'              =>  $this->input->post('district'),
                    'distributorType'       =>  $this->input->post('distributorType'),
                    'distributorPhone'      =>  $this->input->post('distributorPhone'),
                    'distributorEmail'      =>  $this->input->post('distributorEmail'),
                    'distributorAddress'    =>  $this->input->post('distributorAddress'),
                    'distributorGstNo'      =>  $this->input->post('distributorGstNo'),
                    'distributorDob'        =>  $this->input->post('distributorDob'),
                    'creditLimit'           =>  $this->input->post('creditLimit'),
                    'discount'              =>  $this->input->post('discount'),
			     ];
                
                /********************Restrict Redundant Phone & GSTIN Update********************/
                
                $wherePhone             =       '';
                $whereGstin             =       '';
                $whereEmail             =       '';
                $redundantPhone         =       '';
                $redundantGstin         =       '';
                $redundantEmail         =       '';
                
                //ALL TABLE Redundant
                $wherePhone     =   ['distributorId' => $id, 'distributorPhone' => $this->input->post('distributorPhone')];
                if($this->input->post('distributorGstNo')){
                    $whereGstin     =   ['distributorId' => $id, 'distributorGstNo' => $this->input->post('distributorGstNo')];
                }
                if($this->input->post('distributorEmail')){
                    $whereEmail     =   ['distributorId' => $id, 'distributorEmail' => $this->input->post('distributorEmail')];
                }
                
                $redundantPhone     =   $this->Distributor_model->check_redundant_phone($wherePhone);
                if($this->input->post('distributorGstNo')){
                    $redundantGstin     =   $this->Distributor_model->check_redundant_gstin($whereGstin);
                }
                if($this->input->post('distributorEmail')){
                    $redundantEmail     =   $this->Distributor_model->check_redundant_email($whereEmail);
                }
                
                if($redundantGstin <= 1 && $redundantPhone <= 1 && $redundantEmail <= 1){
                    if($redundantGstin == 1){
                        $message    =   ['status' => 'failed', 'message' => 'GSTIN already exists'];
                        echo json_encode($message);
                        die();
                    }
                    if($redundantPhone == 1){
                        $message    =   ['status' => 'failed', 'message' => 'Phone Number already exists'];
                        echo json_encode($message);
                        die();
                    }
                    if($redundantEmail == 1){
                        $message    =   ['status' => 'failed', 'message' => 'Email already exists 444'];
                        echo json_encode($message);
                        die();
                    }
                    else{
                        $updateDistributor     =   $this->Distributor_model->update_distributor_data($data, $id);
                        if($updateDistributor  ==  1){
                            $message    =   ['status' => 'success', 'message' => 'Distributor Updated Successfully'];
                            echo json_encode($message);
                        }
                        else{
                            $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                            echo json_encode($message);
                        }
                    }
                }
                else{
                    $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                    echo json_encode($message);
                }
                /********************Restrict Redundant Phone & GSTIN Update********************/
            }
        }
    }
    
    public function update_status()
    {
        $data   =   [];
        
        if($this->input->post('distributorId',TRUE)){
            $id   = $this->input->post('distributorId');
			$data = [
                    'status'       =>  $this->input->post('status')				
			     ];
            
            $updateDistributor     =   $this->Distributor_model->update_status($data, $id);
            if($updateDistributor  ==  1){
                $message    =   ['status' => 'success', 'message' => 'Distributor Status Changed Successfully'];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                echo json_encode($message);
            }            
        }
    }

    public function addsales()
    {
        if(isset($this->session->userdata['logged_web_user'])) {
            $this->load->view('include/dashboard_header');
            $this->load->view('distributor/addsales');
        }
        else{
            redirect('/login');
        }
    }

    function fetch_sales_persons(){
        // header('Content-Type: application/json');
        $distributor_id      =   $this->input->get('dist');
        $sales_person_data   =   $this->Distributor_model->get_sales_persons($distributor_id);
        if(count($sales_person_data) > 0){
            echo json_encode($sales_person_data);
        }
    }

    function add_sales()
    {
        if($this->input->post('distributor_id',TRUE)){
            $id                 =   $this->input->post('distributor_id');
            $collect_sales_user =   [];
            if(count($this->input->post('sales_users')) > 0){
                foreach ($this->input->post('sales_users') as $key => $sales_users) {
                    $collect_sales_user[$key]['distributor_id'] =   $this->input->post('distributor_id',TRUE);
                    $collect_sales_user[$key]['sales_id']       =   $sales_users['id'];
                }
                $sales_person_data   =   $this->Distributor_model->map_sales_users($collect_sales_user);
                if(!empty($sales_person_data)){
                    $message    =   ['status' => 'success', 'message' => 'Sales users mapped with current distributor'];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'success', 'message' => 'Sales users Updated'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failed', 'message' => 'No sales users found'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failed', 'message' => 'No Distributor found'];
            echo json_encode($message);
        }
    }
    
    /**************************************EDIT Distributors CI**************************************/
    
    
    /**************************************ADD Distributors CSV CI**************************************/
    
    public function addcsv()
    {
        if (isset($this->session->userdata['logged_web_user'])){
            
			$this->load->view('include/dashboard_header');
			$this->load->view('distributor/add_csv');

		}else{
			redirect('/login');
		}
    }
    
    public function add_csv()
    {
        //print_r($_FILES['csvfile']);
        $checkExtension     =   pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION);
        if($checkExtension == 'csv'){
            $csvfile    =   rtrim($_FILES['csvfile']['tmp_name'],".csv");
            $handle     =   fopen($csvfile,"r");
            $x = 0;
            $file       =   $_FILES['csvfile']['name'];
            
            $data   =   [];
            
            while(($cont =   fgetcsv($handle,'1000',","))!==false){
                
                /*echo $pckg_id            =   $cont[0].'<br>';
                echo $item_name          =   $cont[1].'<br>';
                echo $mode_of_package    =   $cont[2].'<br>';
                echo $package_size       =   $cont[3].'<br>';
                echo $package_item_price =   $cont[4].'<br>';*/
                if($x > 0){
                    
                    $data[$x]['distributorId']      =   $cont[0];
                    $data[$x]['distributorName']    =   $cont[1];
                    $data[$x]['district']           =   $cont[2];
                    $data[$x]['distributorType']    =   $cont[3];
                    $data[$x]['distributorPhone']   =   $cont[4];
                    $data[$x]['distributorEmail']   =   $cont[5];
                    $data[$x]['distributorAddress'] =   $cont[6];
                    $data[$x]['distributorGstNo']   =   $cont[7];
                    $data[$x]['distributorDob']     =   $cont[8];
                    $data[$x]['creditLimit']        =   $cont[9];
                    $data[$x]['distributorKycFile'] =   $cont[10];
                    $data[$x]['status']             =   $cont[11];
                    
                }
                /*else{
                    echo 'Soumya';
                    /*$checkQuery     =   $this->Item_model->check_package_items($cont[0]);
                    echo $checkQuery;
                }*/
                
                $x++;
            }
            //$insertCsvData  =   $this->Item_model->insert_add_data_csv($data);
            //print_r($data);
            $insert['insertstatus']     =   $this->Distributor_model->insert_add_data_csv($data);
            
            if($insert['insertstatus'] > 0){
                $this->session->set_flashdata('success', 'CSV data imported successfully');
                redirect('distributors/addcsv');
            }
            else{
                $this->session->set_flashdata('error', 'Something went wrong!!!');
                redirect('distributors/addcsv');
            }
            
        }
        else{
            $this->session->set_flashdata('error', 'Please Upload CSV format file!!!');
            redirect('distributors/addcsv');
        }
    }
    
    /**************************************ADD Distributors CSV CI**************************************/

    /**************************************RETAILERS CSV CI**************************************/


    public function list_all_retailers(){
        header('Content-Type: application/json');
        $retailersList     =   $this->Distributor_model->get_retailers_list();
        echo $retailersList;
    }
}