<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockcontroller extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->helper('url');
        $this->load->helper('form');
	} 

	function add_stock(){
		header('Content-Type: application/json');
		if($this->input->post('salesUserId',TRUE)){

			$params_authenticate = array(
				'usrId' => $this->input->post('salesUserId')
			);

			$authenticate_user = $this->Api_model->authenticate_user($params_authenticate);

			if($authenticate_user == true){
				$params = 	[
					'salesUserId' 			=> 	$this->input->post('salesUserId'),
					'distributorId' 		=> 	$this->input->post('distributorId'),
					'stockCartItemId' 		=> 	$this->input->post('stockCartItemId'),
					'stockCartQuantity' 	=> 	$this->input->post('stockCartQuantity'),						
					'itemPrice' 			=> 	$this->input->post('itemPrice'),
					'cartPrice' 			=> 	$this->input->post('itemPrice') * $this->input->post('stockCartQuantity')
				];

				$checkExistItem['stockCartItemId']	=       $params['stockCartItemId'];
	            $checkExistItem['salesUserId']      =       $params['salesUserId'];
	            $checkExistItem['distributorId']    =       $params['distributorId'];
	            //to check any redundant product will not add to cart
	            $get_duplicate_item     =   $this->Api_model->get_exist_product_in_cart($checkExistItem);

	            if($get_duplicate_item == 0){
					$stock_id	=	$this->Api_model->add_stock_data($params);
					if($stock_id == 0){
						$data = ['status' => 'failed', 'message' => 'Stock Added Failed'];
						echo json_encode($data);
					}
					else{
						$data = ['status' => 'success', 'message' => 'Stock Added Successfully'];
						echo json_encode($data);
					}
				}
				else{
	                $message    =   ['status' => 'failure', 'message' => 'Item already in cart'];
	                echo json_encode($message);
	            }
			}
			else{
				$data = ['status' => 'failed', 'message' => 'User not authenticated'];
				echo json_encode($data);
			}
		}
		else{
			$data = ['status' => 'failed', 'message' => 'user id missing'];
			echo json_encode($data);
		}
	}

	function update_stock_cart(){
		header('Content-Type: application/json');
		if($this->input->post('stockCartId',TRUE)){
			$stockId		=	$this->input->post('stockCartId');

			$params = 	[
					'salesUserId'          => 	$this->input->post('salesUserId'),
					'distributorId'        => 	$this->input->post('distributorId'),
					'stockCartItemId'      => 	$this->input->post('stockCartItemId'),
					'stockCartQuantity'    => 	$this->input->post('stockCartQuantity'),						
					'itemPrice'            => 	$this->input->post('itemPrice'),
					'cartPrice'            => 	$this->input->post('itemPrice') * $this->input->post('stockCartQuantity')
				];

				$stock_id	=	$this->Api_model->update_stock_data($stockId,$params);

				if($stock_id == 0){
					$data	=	['status' => 'failed', 'message' => 'Nothing to update the stock'];
					echo json_encode($data);
				}
				else{
					$data 	=	['status' => 'success', 'message' => 'Stock Updated Successfully'];
					echo json_encode($data);
				}
		}
		else{
			$data 	=	['status' 	=> 'failed', 'message' => 'stock id missing'];
			echo json_encode($data);
		}
	}

    function delete_stock_cart_item(){
        header('Content-Type: application/json');
        if($this->input->post('stockCartId',TRUE)){            
            $data['stockCartId']        =   $this->input->post('stockCartId',TRUE);
            $data['stockCartItemId']    =   $this->input->post('stockCartItemId',TRUE);
            $data['salesUserId']        =   $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =   $this->input->post('distributorId',TRUE);
            
            $get_cart_item     =   $this->Api_model->get_exist_product_in_cart($data);
            
            if($get_cart_item   ==  1){            
                $updateCartData     =   $this->Api_model->delete_cart_data($data);                
                if($updateCartData == 1){
                    $message    =   ['status' => 'success', 'message' => 'stock deleted successfully'];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No stock to delete'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No stock in the list'];
                echo json_encode($message);
            }            
        }
    }

	function confirm_stock_data(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE)){

            $data['salesUserId']	=   $this->input->post('salesUserId',TRUE);
            $data['distributorId']	=	$this->input->post('distributorId',TRUE);
            
            $get_cart_list          =   $this->Api_model->get_cart_items($data);
            $numberOfItemsInCart    =   $this->Api_model->get_cart_count_items($data);
            $getCartTotalPrice      =   $this->Api_model->get_cart_total_price($data);
            
            if($numberOfItemsInCart > 0 && $getCartTotalPrice > 0){
                $data['noOfItemsStock']	=   $numberOfItemsInCart;
                $data['stocktotal']     =   $getCartTotalPrice;
                
                $oderMasterId   =   $this->Api_model->insert_stock_master($data);
                $oderHistory    =   $this->Api_model->insert_stock_history($get_cart_list,$oderMasterId);
                
                if($oderHistory >= 1){
                    $flushData['salesUserId']       =   $data['salesUserId'];
                    $flushData['distributorId']     =   $data['distributorId'];
                    $flushCart      =   $this->Api_model->flush_cart_items($flushData);
                    
                    if($flushCart >= 1){
                        $message    =   ['status' => 'success', 'message' => 'Stock added successfully'];
                        echo json_encode($message);
                    }
                    else{
                        $message	=   ['status' => 'failure', 'message' => 'Something went wrong!!!'];
                        echo json_encode($message);
                    }
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No new items to add in stocks!!!'];
                    echo json_encode($message);
                }
            }
            else{
                $message	=   ['status' => 'failure', 'message' => 'No items in!!!'];
                echo json_encode($message);
            }
        }
    }

	function get_stock_cart_count(){
        header('Content-Type: application/json');        
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE)){
            $data['salesUserId'] 	=   $this->input->post('salesUserId',TRUE);
            $data['distributorId']  =	$this->input->post('distributorId',TRUE);
            
            $cart_list 	=   $this->Api_model->get_cart_count_items($data);
            
            $message    =   ['status' => 'success', 'cart_items_count' => "".$cart_list.""];
            echo json_encode($message);
        }
        else{
        	$message	=	['status' => 'failure', 'message' => 'Invalid user or distributor id'];
        	echo json_encode($message);
        }
    }

    function get_stock_cart_list(){
        header('Content-Type: application/json');        
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE)){
            $data['salesUserId']	=   $this->input->post('salesUserId',TRUE);
            $data['distributorId']  =	$this->input->post('distributorId',TRUE);
            
            $cart_list              =	$this->Api_model->get_stock_cart_list($data);
            $getCartTotalPrice      =   $this->Api_model->get_cart_total_price($data);

            $result['cartlist']     =   $cart_list;
            $result['carTotal']     =   $getCartTotalPrice;
            if($cart_list > 0 && $getCartTotalPrice > 0){
                $result['status']	=   'success';
                echo json_encode($result);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Cart is empty!!!'];
                echo json_encode($message);
            }
        }
    }

    function get_stock_history(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']	=	$this->input->post('salesUserId',TRUE);
            $cart_list 		=	$this->Api_model->get_stock_history($data);
            if($cart_list){
                echo $cart_list;
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No stock found yet!!!'];
                echo json_encode($message);
            }
        }
    }

    function get_stock_details(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE) && $this->input->post('stockId',TRUE)){
            $data['salesUserId']	=	$this->input->post('salesUserId',TRUE);
            $data['distributorId']  =	$this->input->post('distributorId',TRUE);
            $data['stockId']        =	$this->input->post('stockId',TRUE);
            $orderDetails 	=	$this->Api_model->fetch_stock_details($data);
            if($orderDetails){
                echo $orderDetails;
            }
            else{
                $message	=   ['status' => 'failure', 'message' => 'No stocks found yet!!!'];
                echo json_encode($message);
            }
        }
        else{
        	$message	=   ['status' => 'failure', 'message' => 'Invalid parameters'];
            echo json_encode($message);
        }
    }

    function get_stock_details_by_distributor(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE)){
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['distributorId']  =   $this->input->post('distributorId',TRUE);
            //$data['stockId']        =   $this->input->post('stockId',TRUE);
            $orderDetails   =   $this->Api_model->fetch_stock_details_by_distributor($data);
            if($orderDetails){
                // echo $orderDetails;
                $message    =   ['status' => 'success', 'message' => $orderDetails];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No stocks found yet!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid parameters'];
            echo json_encode($message);
        }
    }

    function get_stock_details_by_item_distributor(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE) && $this->input->post('itemId',TRUE)){
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $data['distributorId']  =   $this->input->post('distributorId',TRUE);
            $data['stockId']        =   $this->input->post('itemId',TRUE);
            $orderDetails   =   $this->Api_model->fetch_stock_details_by_distributor_item($data);
            if($orderDetails){
                //echo $orderDetails;
                $message    =   ['status' => 'success', 'data' => $orderDetails];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No stocks found yet!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid parameters'];
            echo json_encode($message);
        }
    }
}