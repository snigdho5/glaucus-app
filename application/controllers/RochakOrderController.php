<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RochakOrderController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();	
        $this->load->model('Rochak_order_model');
        $this->load->helper('url');
	}
    
    /***************************************** MOBILE API MODULE *****************************************/
    
    function add_to_cart()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('cartItemId',TRUE)){
            $data   =   [];
            
            $data['cartItemId']         =       $this->input->post('cartItemId',TRUE);
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            $data['cartQuantity']       =       $this->input->post('cartQuantity',TRUE);
            $data['itemPrice']          =       $this->input->post('cartPrice',TRUE);
            $data['cartPrice']          =       $data['cartQuantity'] * $data['itemPrice'];
            
            $checkExistItem['cartItemId']       =       $data['cartItemId'];
            $checkExistItem['salesUserId']      =       $data['salesUserId'];
            $checkExistItem['distributorId']    =       $data['distributorId'];
            //to check any redundant product will not add to cart
            $get_duplicate_item     =   $this->Rochak_order_model->get_exist_product_in_cart($checkExistItem);
            //Calculation whereas sales person will not able to place any order for distributor in respective to the credit limit
            $distributorCreditLimit =   $this->Rochak_order_model->get_distributor_creditlimit($data['distributorId']);
            $getOutstandingAmount   =   $this->Rochak_order_model->get_distributor_credit_outstanding($data['distributorId']);
            
            $cartPriceArray['salesUserId']      =   $data['salesUserId'];
            $cartPriceArray['distributorId']    =   $data['distributorId'];
            $getCartTotalPrice      =   $this->Rochak_order_model->get_cart_total_price($cartPriceArray);
            
            $creditAvailable    =   $distributorCreditLimit - $getOutstandingAmount;
            
            $creditAvailable    =   $creditAvailable - $getCartTotalPrice;
            
            if($get_duplicate_item == 0){
                $add_to_item     =   $this->Rochak_order_model->add_new_to_cart($data);
                $message    =   ['status' => 'success', 'message' => 'Item added to cart'];
                echo json_encode($message);
                /*if($creditAvailable >= $data['cartPrice'])
                {
                    $add_to_item     =   $this->Rochak_order_model->add_new_to_cart($data);
                    $message    =   ['status' => 'success', 'message' => 'Item added to cart'];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'Credit Balance exceeds'];
                    echo json_encode($message);
                }*/
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Item already in cart'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Something went wrong!!!'];
            echo json_encode($message);
        } 
    }
    
    function update_item_cart()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('cartId',TRUE)){
            
            $data['cartId']             =       $this->input->post('cartId',TRUE);
            $data['cartItemId']         =       $this->input->post('cartItemId',TRUE);
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            //to check if the product exist in the cart
            $get_cart_item     =   $this->Rochak_order_model->get_exist_product_in_cart($data);
            
            //Calculation whereas sales person will not able to place any order for distributor in respective to the credit limit
            $distributorCreditLimit =   $this->Rochak_order_model->get_distributor_creditlimit($data['distributorId']);
            $getOutstandingAmount   =   $this->Rochak_order_model->get_distributor_credit_outstanding($data['distributorId']);
            
            $cartPriceArray['salesUserId']      =   $data['salesUserId'];
            $cartPriceArray['distributorId']    =   $data['distributorId'];
            $getCartTotalPrice      =   $this->Rochak_order_model->get_cart_total_price($cartPriceArray);
            $getCartItemCount       =   $this->Rochak_order_model->get_cart_single_item_count($data);
            
            $creditAvailable    =   $distributorCreditLimit - $getOutstandingAmount;
            
            //$creditAvailable    =   $creditAvailable - $getCartTotalPrice;
            
            if($get_cart_item   ==  1){
                $cartId                         =       $data['cartId'];  
                $updateData['cartQuantity']     =       $this->input->post('cartQuantity',TRUE);
                $updateData['cartPrice']        =       $updateData['cartQuantity'] * $this->input->post('cartPrice',TRUE);
                
                if($this->input->post('cartQuantity',TRUE) < $getCartItemCount){
                    $updateCartData     =   $this->Rochak_order_model->update_cart_data($updateData,$cartId);
                    if($updateCartData == 1){
                        $message    =   ['status' => 'success', 'message' => 'Cart updated successfully'];
                        echo json_encode($message);
                    }
                    else{
                        $message    =   ['status' => 'failure', 'message' => 'No new changes to update'];
                        echo json_encode($message);
                    }
                }
                else{
                    $updateCartData     =   $this->Rochak_order_model->update_cart_data($updateData,$cartId);
                    $message    =   ['status' => 'success', 'message' => 'Cart updated successfully'];
                    echo json_encode($message);
                    //if($creditAvailable >= $this->input->post('cartPrice',TRUE)){
                    /*if($creditAvailable >= $updateData['cartPrice']){
                        $updateCartData     =   $this->Rochak_order_model->update_cart_data($updateData,$cartId);
                        $creditAvailable    =   $creditAvailable - $getCartTotalPrice;
                        if($updateCartData == 1){
                            $message    =   ['status' => 'success', 'message' => 'Cart updated successfully'];
                            echo json_encode($message);
                        }
                        else{
                            $message    =   ['status' => 'failure', 'message' => 'No new changes to update'];
                            echo json_encode($message);
                        }
                    }
                    else{
                        $message    =   ['status' => 'failure', 'message' => 'Credit Balance exceeds'];
                        echo json_encode($message);
                    }*/
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No Item in the cart'];
                echo json_encode($message);
            }
        }        
    }
    
    function delete_cart_item()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('cartId',TRUE)){
            
            $data['cartId']             =       $this->input->post('cartId',TRUE);
            $data['cartItemId']         =       $this->input->post('cartItemId',TRUE);
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            
            $get_cart_item     =   $this->Rochak_order_model->get_exist_product_in_cart($data);
            
            if($get_cart_item   ==  1){
                //$cartId                         =       $data['cartId'];  
                
                $updateCartData     =   $this->Rochak_order_model->delete_cart_data($data);
                
                if($updateCartData == 1){
                    $message    =   ['status' => 'success', 'message' => 'Item deleted successfully'];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No item changes to delete'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No Item in the cart'];
                echo json_encode($message);
            }
            
        }
    }
    
    function get_cart_list()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE)){
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            
            $cart_list              =   $this->Rochak_order_model->get_cart_list($data);
            $getCartTotalPrice      =   $this->Rochak_order_model->get_cart_total_price($data);
            
            /*echo $getCartTotalPrice;
            print_r($cart_list);*/
            $result['cartlist']     =   $cart_list;
            $result['carTotal']     =   $getCartTotalPrice;
            if($cart_list > 0 && $getCartTotalPrice > 0){
                $result['status']     =   'success';
                echo json_encode($result);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Cart is empty!!!'];
                echo json_encode($message);
            }
        }
    }
    
    function get_cart_count()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('salesUserId',TRUE) && $this->input->post('salesUserId',TRUE)){
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            
            $cart_list     =   $this->Rochak_order_model->get_cart_count_items($data);
            
            //echo $cart_list;
            $message    =   ['status' => 'success', 'cart_items_count' => "".$cart_list.""];
            echo json_encode($message);
        }
    }    
    
    function place_order()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE)){
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            
            $get_cart_list          =   $this->Rochak_order_model->get_cart_items($data);
            $numberOfItemsInCart    =   $this->Rochak_order_model->get_cart_count_items($data);
            $getCartTotalPrice      =   $this->Rochak_order_model->get_cart_total_price($data);
            
            if($numberOfItemsInCart > 0 && $getCartTotalPrice > 0){
                $data['noOfItemsOder']  =   $numberOfItemsInCart;
                $data['ordertotal']     =   $getCartTotalPrice;
                
                $oderMasterId   =   $this->Rochak_order_model->insert_order_master($data);
                $oderHistory    =   $this->Rochak_order_model->insert_order_history($get_cart_list,$oderMasterId);
                //insert to credit table
                $creditData['orderId']              =   $oderMasterId;
                $creditData['distributorId']        =   $this->input->post('distributorId',TRUE);
                $creditData['salesUserId']          =   $this->input->post('salesUserId',TRUE);        
                $creditData['creditAmout']          =   $getCartTotalPrice;
                $creditData['creditSettled']        =   0;
                $creditData['creditOutstanding']    =   $getCartTotalPrice;
                $creditMaster   =   $this->Rochak_order_model->insert_credit_master($creditData);
                
                if($oderHistory >= 1){
                    $flushData['salesUserId']       =   $data['salesUserId'];
                    $flushData['distributorId']     =   $data['distributorId'];
                    $flushCart      =   $this->Rochak_order_model->flush_cart_items($flushData);
                    
                    if($flushCart >= 1){
                        $message    =   ['status' => 'success', 'message' => 'Order Placed successfully'];
                        echo json_encode($message);
                    }
                    else{
                        $message    =   ['status' => 'failure', 'message' => 'Something went wrong!!!'];
                        echo json_encode($message);
                    }
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No new items to place order!!!'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No items in cart!!!'];
                echo json_encode($message);
            }
        }
    }
    
    function get_order_history()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            //$data['distributorId']      =       $this->input->post('distributorId',TRUE);
            
            $cart_list              =   $this->Rochak_order_model->get_order_history($data);
            if($cart_list){
                // echo $cart_list;
                $message    =   ['status' => 'success', 'message' => $cart_list];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No orders found yet!!!'];
                echo json_encode($message);
            }
        }
    }
    
    function get_order_details()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE) && $this->input->post('orderId',TRUE)){
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            $data['orderId']            =       $this->input->post('orderId',TRUE);
            
            $orderDetails               =       $this->Rochak_order_model->get_order_details($data);
            if($orderDetails){
                echo $orderDetails;
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No orders found yet!!!'];
                echo json_encode($message);
            }
        }
    }
    
    function get_distributor_credit()
    {
        header('Content-Type: application/json');
        
        if($this->input->post('distributorId',TRUE)){
            $distributorId  =   $this->input->post('distributorId',TRUE);
            $creditLimit        =   $this->Rochak_order_model->get_distributor_creditlimit($distributorId);
            $outstandingAmount  =   $this->Rochak_order_model->get_distributor_credit_outstanding($distributorId);
            $creditAvailable    =   $creditLimit - $outstandingAmount;
            
            if($outstandingAmount == false){
                $outstandingAmount  =   0;
            }
            $creditInformation['status']        =   'success';
            $creditInformation['creditinfo']    =   ['creditbalance' => $creditLimit, 'creditOutstanding' => $outstandingAmount, 'creditAvailable' => $creditAvailable];
            
            echo json_encode($creditInformation);
        }
    }
    
    /***************************************** MOBILE API MODULE *****************************************/
    
}