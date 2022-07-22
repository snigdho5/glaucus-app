<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privacypolicy extends CI_Controller {

    function __construct()
    {
        parent::__construct();		
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	public function index()
	{
		$this->load->view('include/session_header');
		$this->load->view('about/privacypolicy');
	}




}