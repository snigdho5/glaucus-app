<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itemmanagement extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();	
        //$this->load->model('Item_model');        
        //$this->load->helper('url');
        $this->load->model('Item_model');
        $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}
    
    public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('items/item');

		}else{
			redirect('/login');
		}
		
	}
    
    /**************************************ADD ITEM CI**************************************/
    public function add()
    {
        //echo 'Hello Soumya';
        if (isset($this->session->userdata['logged_web_user'])){
            $data   =   [];
            
            $data['packageMode']    =   $this->Item_model->get_package_mode();
            
            $data['packageSize']    =   $this->Item_model->get_package_size();

			$this->load->view('include/dashboard_header');
			$this->load->view('items/add_item',$data);

		}else{
			redirect('/login');
		}
    }
    
    public function additem()
    {        
        /*$this->form_validation->set_rules('item_name', 'Item Name', 'required');
        $this->form_validation->set_rules('package_item_price', 'Item Price', 'required|numeric');
        $this->form_validation->set_rules('mode_of_package', 'Package Mode', 'required');
        $this->form_validation->set_rules('package_size', 'Package Price', 'required');
        
        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('error', validation_errors());
            redirect('itemmanagement/add');
        }
        else{*/
            $data   =   [];

            $data   =   [
                            'item_name'             =>      $this->input->post('item_name',TRUE),
                            'mode_of_package'       =>      $this->input->post('mode_of_package',TRUE),
                            'package_size'          =>      $this->input->post('package_size',TRUE),
                            'package_item_price'    =>      $this->input->post('package_item_price',TRUE),
                            'retail_item_price'     =>      $this->input->post('retail_item_price',TRUE)
                        ];
            
            $insert['insertstatus']     =   $this->Item_model->insert_add_item($data);
            
            if($insert['insertstatus'] > 0){
                /*$this->session->set_flashdata('success', 'New Item inserted successfully');
                redirect('itemmanagement/add');*/
                $message    =   ['status' => 'success', 'message' => 'New Item Added Successfully'];
                echo json_encode($message);
            }
            else{
                /*$this->session->set_flashdata('error', 'Something went wrong!!!');
                redirect('itemmanagement/add');*/
                $message    =   ['status' => 'error', 'message' => 'Something went wrong!!!'];
                echo json_encode($message);
            }
        //}
    }
    
    public function update_status()
    {
        $data   =   [];
        
        if($this->input->post('pckg_id',TRUE)){
            $id   = $this->input->post('pckg_id');
			$data = [
                    'status'       =>  $this->input->post('status')				
			     ];
            
            $updateStatus     =   $this->Item_model->update_status($data, $id);
            if($updateStatus  ==  1){
                $message    =   ['status' => 'success'];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failed'];
                echo json_encode($message);
            }
            
        }
    }
    
    /**************************************ADD ITEM CI**************************************/
    
    /**************************************ADD ITEM CSV CI**************************************/
    
    public function addcsv()
    {
        if (isset($this->session->userdata['logged_web_user'])){
            
			$this->load->view('include/dashboard_header');
			$this->load->view('items/add_csv');

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
                    
                    $data[$x]['pckg_id']                =   $cont[0];
                    $data[$x]['item_name']              =   $cont[1];
                    $data[$x]['mode_of_package']        =   $cont[2];
                    $data[$x]['package_size']           =   $cont[3];
                    $data[$x]['package_item_price']     =   $cont[4];
                    $data[$x]['status']                 =   $cont[5];
                    
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
            $insert['insertstatus']     =   $this->Item_model->insert_add_data_csv($data);
            
            if($insert['insertstatus'] > 0){
                $this->session->set_flashdata('success', 'CSV data imported successfully');
                redirect('itemmanagement/addcsv');
            }
            else{
                $this->session->set_flashdata('error', 'Something went wrong!!!');
                redirect('itemmanagement/addcsv');
            }
            
        }
        else{
            $this->session->set_flashdata('error', 'Please Upload CSV format file!!!');
            redirect('itemmanagement/addcsv');
        }
    }

    public function update_item()
    {
        $data   =   [];
        
        if($this->input->post('id',TRUE)){
            $id   = $this->input->post('id');

			$data = [
                    'item_name'             =>  $this->input->post('item_name'),
                    'mode_of_package'       =>  $this->input->post('mode_of_package'),
                    'package_size'          =>  $this->input->post('package_size'),
                    'package_item_price'    =>  $this->input->post('package_item_price',TRUE),
                    'retail_item_price'     =>  $this->input->post('retail_item_price',TRUE)
		    ];

            $updateItem     =   $this->Item_model->update_item_data($data, $id);
            if($updateItem  ==  1){
                $message    =   ['status' => 'success', 'message' => 'Item Updated Successfully'];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failed', 'message' => 'No New Changes to update'];
                echo json_encode($message);
            }
            
        }
    }
    
    /**************************************ADD ITEM CSV CI**************************************/    
    
    public function edit()
    {
        if (isset($this->session->userdata['logged_web_user'])) {
			$this->load->view('include/dashboard_header');
			$this->load->view('items/edititem');
		}else{
			redirect('/login');
		}
    }
    
    /**************************************API VIEW ALL ITEMS**************************************/
    
    public function allitems()
    {
        $insert['getData']     =   $this->Item_model->fetch_items_data();
        echo $insert['getData'];
        //return $insert['getData'];
    }
    
    public function get_item_detail()
    {
        header('Content-Type: application/json');

		$params   =   ['pckg_id' => $this->input->get('id')];
        
        $itemdata   =   $this->Item_model->get_item_data($params);
        
        $data   =   [
                        'pckg_id'               =>      $itemdata['pckg_id'],
                        'item_name'             =>      $itemdata['item_name'],
                        'package_item_price'    =>      $itemdata['package_item_price'],
                        'package_size'          =>      $itemdata['package_size'],
                        'mode_of_package'       =>      $itemdata['mode_of_package'],
                        'retail_item_price'     =>      $itemdata['retail_item_price'],
                    ];
        echo json_encode($data);
    }
    
    public function packagemodecollection()
    {
        header('Content-Type: application/json');
        
        $data    =   $this->Item_model->get_all_packagemodes();
        
        echo json_encode($data);
    }
    
    public function packagesizecollection()
    {
        header('Content-Type: application/json');
        
        $data    =   $this->Item_model->get_all_packagesizes();
        
        echo json_encode($data);
    }
    
    public function get_all_retailer_items(){
        header('Content-Type: application/json');
        $insert['getData']     =   $this->Item_model->fetch_retailers_items_data();
        echo $insert['getData'];
    }
}