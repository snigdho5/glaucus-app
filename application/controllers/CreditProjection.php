<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CreditProjection extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(['Credit_projection_model','Rochak_order_model']);
        $this->load->helper('url');
	}

    /***************************************** WEB API MODULE *****************************************/

    /***************************************** WEB API MODULE *****************************************/

    /***************************************** MOBILE API MODULE *****************************************/

    function add_projection(){
        header('Content-Type: application/json');
        if($this->input->post('projectionAmount',TRUE) && $this->input->post('salesUserId',TRUE) && $this->input->post('distributorId',TRUE)){

            $paymentMode                =       strtolower($this->input->post('paymentMode',TRUE));
            if($paymentMode == 'cash'){
                $paymentMode = 0;
            }
            else if($paymentMode == 'cheque'){
                $paymentMode = 1;
            }
            else{
                $paymentMode = 2;
            }

            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =       $this->input->post('distributorId',TRUE);
            $data['dateOfCollection']   =       date('Y-m-d', strtotime(' +1 day'));
            $data['projectionAmount']   =       $this->input->post('projectionAmount',TRUE);
            $data['paymentMode']        =       $paymentMode;
            $data['order_id']           =       $this->input->post('order_id',TRUE);

            $get_duplicate_projection   =   $this->Credit_projection_model->get_exist_projection_in_cart($data);
            //$getTotalCreditAmount       =   $this->Credit_projection_model->get_total_credit_outstanding($data);

            if($get_duplicate_projection == 0){
                /*if($data['projectionAmount'] > $getTotalCreditAmount){
                    $message    =   ['status' => 'failure', 'message' => 'Projection amount exceeds than credit amount'];
                    echo json_encode($message);
                }else{*/
                    $add_to_item     =   $this->Credit_projection_model->add_new_projection($data);
                    $message    =   ['status' => 'success', 'message' => 'Projection added'];
                    echo json_encode($message);
                //}
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Projection already added for this distributor for tommorow'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Please Enter Collection Amount!!!'];
            echo json_encode($message);
        }
    }

    function update_projection_amount(){
        header('Content-Type: application/json');
        if($this->input->post('projectionCartId',TRUE)){

            $paymentMode    =   strtolower($this->input->post('paymentMode',TRUE));
            if($paymentMode == 'cash'){
                $paymentMode    =   0;
            }
            else if($paymentMode == 'cheque'){
                $paymentMode    =   1;
            }
            else{
                $paymentMode    =   2;
            }

            $data['projectionCartId']   =   $this->input->post('projectionCartId',TRUE);
            $data['salesUserId']        =   $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =   $this->input->post('distributorId',TRUE);
            $data['dateOfCollection']   =   date('Y-m-d', strtotime(' +1 day'));

            $get_cart_info              =   $this->Credit_projection_model->get_exist_projection_in_cart($data);
            $getTotalCreditAmount       =   $this->Credit_projection_model->get_total_credit_outstanding($data);

            if($get_cart_info   ==  1){
                $cartId                         =   $data['projectionCartId'];
                $updateData['projectionAmount'] =   $this->input->post('projectionAmount',TRUE);
                $updateData['paymentMode']      =   $paymentMode;

                //if($updateData['projectionAmount'] < $getTotalCreditAmount){
                    $updateCartInfo     =   $this->Credit_projection_model->update_projection_amount($updateData,$cartId);
                    if($updateCartInfo == 1){

                        $message    =   ['status' => 'success', 'message' => 'Projection updated successfully'];
                        echo json_encode($message);
                    }
                    else{
                        $message    =   ['status' => 'failure', 'message' => 'No new changes to update'];
                        echo json_encode($message);
                    }
                /*}
                else{
                    $message    =   ['status' => 'failure', 'message' => 'Projection amount exceeds than credit amount'];
                    echo json_encode($message);
                }*/
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No Projection found'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Please Select A Projection!!!'];
            echo json_encode($message);
        }
    }

    function delete_projection(){
        header('Content-Type: application/json');

        if($this->input->post('projectionCartId',TRUE)){

            $data['projectionCartId']   =   $this->input->post('projectionCartId',TRUE);
            $data['salesUserId']        =   $this->input->post('salesUserId',TRUE);
            $data['distributorId']      =   $this->input->post('distributorId',TRUE);

            $get_cart_info      =   $this->Credit_projection_model->get_exist_projection_in_cart($data);

            if($get_cart_info   ==  1){
                $deleteProjectionData   =   $this->Credit_projection_model->delete_projection_data($data);
                if($deleteProjectionData == 1){
                    $message    =   ['status' => 'success', 'message' => 'Projection deleted successfully'];
                    echo json_encode($message);
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'No Projection to delete'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Invalid Projection to delete'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Projection amount to delete!!!'];
            echo json_encode($message);
        }
    }

    function get_projection_cart_list(){
        header('Content-Type: application/json');

        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);

            $cart_list              =   $this->Credit_projection_model->get_projection_cart_list($data);
            $getCartTotalPrice      =   $this->Credit_projection_model->get_projection_cart_total($data);

            $result['cartlist']     =   $cart_list;
            $result['carTotal']     =   $getCartTotalPrice;
            if($cart_list > 0 && $getCartTotalPrice > 0){
                $result['status']   =   'success';
                echo json_encode($result);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'Projection list is empty!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid User'];
            echo json_encode($message);
        }
    }

    function place_projection(){
        header('Content-Type: application/json');

        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);

            $get_cart_list          =   $this->Credit_projection_model->get_cart_projections($data);
            $numberOfItemsInCart    =   $this->Credit_projection_model->get_cart_count_projection($data);
            $getCartTotalPrice      =   $this->Credit_projection_model->get_projection_cart_total($data);

            if($numberOfItemsInCart > 0 && $getCartTotalPrice > 0){
                $data['numberOfProjection'] =   $numberOfItemsInCart;
                $data['totalAmount']        =   $getCartTotalPrice;
                
                //for invoice master
                //$invoice_master['order_id'] =   $get_cart_list[0]['order_id'];
                //$invoice_master['amount']   =   $get_cart_list[0]['projectionAmount'];
                //$update_invoice_master      =   $this->Rochak_order_model->update_invoice_amount($invoice_master);

                $order_id   =   $get_cart_list[0]['order_id'];
                $input      =   $get_cart_list[0]['projectionAmount'];
                $total      =   $this->Rochak_order_model->generate_sum_of_invoice($order_id);
                //echo $total;
                //die();
                if($total != false){
                    if($total >= $input){
                        $data['order_id']   =   $order_id;
                        $data['amount']     =   $input;
                        $response           =   $this->Rochak_order_model->update_invoice_amount($data);

                        if(!$response){
                            $message    =   ['status' => 'failure', 'message' => 'Due amount exceeds'];
                            echo json_encode($message);
                        }
                        else{
                            unset($data['order_id']);
                            $data['totalAmount']    =   $data['amount'];
                            unset($data['amount']);
                            $oderMasterId   =   $this->Credit_projection_model->insert_projection_master($data);
                            $oderHistory    =   $this->Credit_projection_model->insert_projection_history($get_cart_list,$oderMasterId);

                            if($oderHistory >= 1){
                                $flushData['salesUserId']   =   $data['salesUserId'];
                                $flushCart      =   $this->Credit_projection_model->flush_cart_projections($flushData);

                                if($flushCart >= 1){
                                    $message    =   ['status' => 'success', 'message' => 'Projection added successfully'];
                                    echo json_encode($message);
                                }
                                else{
                                    $message    =   ['status' => 'failure', 'message' => 'Something went wrong!!!'];
                                    echo json_encode($message);
                                }
                            }
                            else{
                                $message    =   ['status' => 'failure', 'message' => 'No new Projection to add!!!'];
                                echo json_encode($message);
                            }
                        }
                    }
                    else{
                        $message    =   ['status' => 'failure', 'message' => 'Input amount exceeds'];
                        echo json_encode($message);
                    }
                }
                else{
                    $message    =   ['status' => 'failure', 'message' => 'Amount already settled'];
                    echo json_encode($message);
                }
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No projection to add!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid User'];
            echo json_encode($message);
        }
    }

    function get_projection_history(){
        header('Content-Type: application/json');

        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']        =       $this->input->post('salesUserId',TRUE);

            $cart_list              =   $this->Credit_projection_model->get_projection_history($data);
            if($cart_list){
                $message    =   ['status' => 'success', 'message' => $cart_list];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No orders found yet!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid User'];
            echo json_encode($message);
        }
    }

    function get_projection_details(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE) && $this->input->post('masterProjectionId',TRUE)){
            $data['salesUserId']            =       $this->input->post('salesUserId',TRUE);
            $data['masterProjectionId']     =       $this->input->post('masterProjectionId',TRUE);
            $orderDetails               =       $this->Credit_projection_model->get_projection_details($data);
            if($orderDetails){
                echo $orderDetails;
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No projections found!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid Projection'];
            echo json_encode($message);
        }
    }

    function get_credit_data(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $credit_list            =   $this->Credit_projection_model->get_credit_list($data);
            if($credit_list){
                $message    =   ['status' => 'success', 'message' => $credit_list];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No credit information found!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid user id!!!'];
            echo json_encode($message);
        }
    }

    function get_today_collection_logs(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE)){
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);
            $credit_list            =   $this->Credit_projection_model->get_today_collection_logs($data);
            if($credit_list){
                $message    =   ['status' => 'success', 'message' => $credit_list];
                echo json_encode($message);
            }
            else{
                $message    =   ['status' => 'failure', 'message' => 'No Collection records found for today!!!'];
                echo json_encode($message);
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid user id!!!'];
            echo json_encode($message);
        }
    }

    function update_credit_collection_order(){
        header('Content-Type: application/json');
        if($this->input->post('salesUserId',TRUE) && $this->input->post('creditId',TRUE) && $this->input->post('creditSettled',TRUE)){
            $data['creditId']       =   $this->input->post('creditId',TRUE);
            $data['salesUserId']    =   $this->input->post('salesUserId',TRUE);

            $getSingleOrderCredit   =   $this->Credit_projection_model->get_single_order_credit($data);

            $creditAmount           =   $getSingleOrderCredit['creditAmout'];
            $creditSettled          =   $getSingleOrderCredit['creditSettled'];
            $creditOutstanding      =   $getSingleOrderCredit['creditOutstanding'];
            //$creditLastUpdateDate   =   strtotime($getSingleOrderCredit['updated_on']);

            /***************************Collection Data*********************************/
            $collection['creditId']             =   $getSingleOrderCredit['creditId'];
            $collection['orderId']              =   $getSingleOrderCredit['orderId'];
            $collection['distributorid']        =   $getSingleOrderCredit['distributorid'];
            $collection['salesUserId']          =   $getSingleOrderCredit['salesUserId'];
            $collection['collectionAmount ']    =   $this->input->post('creditSettled',TRUE);

            //echo date('Y-m-d H:i:s', $creditLastUpdateDate);
            if($creditOutstanding == 0){
                $message    =   ['status' => 'success', 'message' => 'Collection settled for this order!!!'];
                echo json_encode($message);
            }
            else{
                if($creditOutstanding < $this->input->post('creditSettled',TRUE)){
                    $message    =   ['status' => 'failure', 'message' => 'Collection amount is exceeds than projection amount!!!'];
                    echo json_encode($message);
                }
                else{
                    $data['creditSettled']    =   $this->input->post('creditSettled',TRUE);

                    $updatedata['creditSettled']        =   $creditSettled + $data['creditSettled'];
                    $updatedata['creditOutstanding']    =   $creditOutstanding - $data['creditSettled'];
                    $getSingleOrderCredit   =   $this->Credit_projection_model->update_collection_order_amount($updatedata,$this->input->post('creditId',TRUE));

                    //$this->Credit_projection_model->insert_new_collection($collection);

                    if($getSingleOrderCredit){
                        $this->Credit_projection_model->insert_new_collection($collection);
                        $message    =   ['status' => 'success', 'message' => 'Collection received!!!'];
                        echo json_encode($message);
                    }
                    else{
                        $message    =   ['status' => 'failure', 'message' => 'No Collection collected!!!'];
                        echo json_encode($message);
                    }
                }
            }
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'Invalid hit!!!'];
            echo json_encode($message);
        }
    }

    /***************************************** MOBILE API MODULE *****************************************/

}
