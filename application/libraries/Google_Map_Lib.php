<?php
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		Payment Gateway Nagad 
 * @author		Soumyajeet Seal
 * @since		Version 1.0.0
 * @filesource
 */

// ------------------------------------------------------------------------

class Google_Map_Lib{

    var $CI;
    public function __construct($config = array()){
        $this->CI =& get_instance();
        define('GOOGLE_MAP_API_KEY', "AIzaSyCa2Y9fI4Fn7tO6PPJreoMwgNPCdU6v3Lk");
    }

}