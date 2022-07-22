<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller
{
    function __construct()
    {
        parent::__construct();	
        $this->load->model('Cronjob_model');
        $this->load->library('email');
        $this->load->helper('url');
	}
    
    function generate_yesterday_projection_report_web()
    {
        /*header('Content-Type: application/json');
                
        $projectionData     =   $this->Cronjob_model->get_yesterday_projection_report_web();
        $projectionTotal    =   $this->Cronjob_model->get_yesterday_projection_total_web();
        if(count($projectionData) >= 1 && $projectionTotal > 0){
            $message    =   ['status' => 'success', 'projectiondata' => $projectionData, 'totalAmount' => $projectionTotal];
            echo json_encode($message);
        }
        else{
            $message    =   ['status' => 'failure', 'message' => 'No Projection Record found'];
            echo json_encode($message);
        }*/
        
        $projectionData     =   $this->Cronjob_model->get_yesterday_projection_report_web();
        
        require(APPPATH.'third_party/PHPExcel.php');
        require(APPPATH.'third_party/PHPExcel/Writer/Excel2007.php');
        
        $objPHPExcel = new PHPExcel();
        
        $objPHPExcel->getProperties()->setCreator();
        $objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle();
        $objPHPExcel->getProperties()->setSubject();
        $objPHPExcel->getProperties()->setDescription();
        
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sales Person');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Projection Amount');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Projection Date');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Collection Date');
                
        $row    =   2;
        
        foreach($projectionData as $projection){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $projection['firstName'].' '.$projection['lastName']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $projection['amount']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $projection['projectionDate']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $projection['collectionDate']);
            $row++;
        }
       
        //$fileName   =   "Projection-Created-on-".date("Y-m-d-H-i-s").'.xlsx';
        $fileName   =   "Projection-Created-on-".date("Y-m-d").'.xlsx';
        $objPHPExcel->getActiveSheet()->setTitle("Payment-Projection");
        
        //header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Type: application/vnd.ms-excel'); 
        header("Content-Disposition: attachment;filename=\"".$fileName."\";");
        header("Cache-Control: max-age=0");
        
        $writer     =   PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        
        //$writer->save('php://output');
        //$config['upload_path'] = "/uploads/payment_projections";
        //$writer->save(str_replace(__FILE__,$config['upload_path'].$fileName,__FILE__));
        $writer->save(FCPATH.'payment_projections/'.$fileName);
        exit();
        
        //echo FCPATH.'uploads/payment_projections/'.$fileName;        
        //$writer->save(str_replace(__FILE__,FCPATH.'uploads/payment_projections'.$fileName,__FILE__));         
    }
    
    function send_yesterday_projection_report_web()
    {
        $projectionTotal    =   $this->Cronjob_model->get_yesterday_projection_total_web();
        
        $sendFile   =   FCPATH.'payment_projections/'."Projection-Created-on-".date("Y-m-d").'.xlsx';
        
        if(file_exists($sendFile)){
            //echo 'Report Generated';
            
            $msgToemail	='<!doctype html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                </head>
                <body style="padding:0; margin:0;">
                    <div style="width:600px; margin:0 auto; border:1px solid #ad1818;">
                        <div style="width:100%; text-align:center; background-color:#ad1818; padding:15px 0;"><h3>Rochak Projection Report</h3><img src="" alt=""></div>
                        <div style="padding:20px 10px 0 20px;">
                            <h2 style="font-size:30px; font-family:">Hello Admin</h2>
                            <p style=" font-size:20px;">Please find attached Payment Projection Report for the day, from the Sales Team.</p>
                            <p style="font-family:verdana; font-size:14px; margin:35px 0 30px 0;"><strong>Total Projection Amount : </strong> Rs.'.$projectionTotal.'</p>
                        </div>
                    </div>
                </body>
            </html>';

            //echo $msgToemail;

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->to('soumyajeet.lnsel@gmail.com');
            $this->email->from('noreply@rochak.lnsel.net','Rochak Sales Force APP');
            $this->email->subject('Payment Projection For '.date("Y-m-d"));
            $this->email->message($msgToemail);
            $this->email->attach($sendFile);
            $this->email->send();

            //$this->session->set_flashdata('success', 'Your Payment Projection sent Successfully!!!');*/
            exit;
        }
        else{
            //echo 'Report Not Generated';
            $msgToemail	='<!doctype html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                </head>
                <body style="padding:0; margin:0;">
                    <div style="width:600px; margin:0 auto; border:1px solid #ad1818;">
                        <div style="width:100%; text-align:center; background-color:#ad1818; padding:15px 0;"><h3>Rochak Projection Report</h3><img src="" alt=""></div>
                        <div style="padding:20px 10px 0 20px;">
                            <h2 style="font-size:30px; font-family:">Hello Admin</h2>
                            <p style=" font-size:20px;">There is no projection found for today, from the Sales Team.</p>
                            <p style="font-family:verdana; font-size:14px; margin:35px 0 30px 0;"><strong>Message : </strong> No report generated for today.</p>
                        </div>
                    </div>
                </body>
            </html>';
        
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->to('soumyajeet.lnsel@gmail.com');
            $this->email->from('noreply@rochak.lnsel.net','Rochak Sales Force APP');
            $this->email->subject('Payment Projection For '.date("Y-m-d"));
            $this->email->message($msgToemail);
            //$this->email->attach($sendFile);
            $this->email->send();
            exit;
        }
    }
    
    function send_birthday_to_distributor()
    {
        
    }
    
    function test_cronjob_mail()
    {
        $msgToemail	='<!doctype html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                </head>
                <body style="padding:0; margin:0;">
                    <div style="width:600px; margin:0 auto; border:1px solid #ad1818;">
                        <div style="width:100%; text-align:center; background-color:#ad1818; padding:15px 0;"><h3>Rochak Projection Report</h3><img src="" alt=""></div>
                        <div style="padding:20px 10px 0 20px;">
                            <h2 style="font-size:30px; font-family:">Hello Admin</h2>
                            <p style=" font-size:20px;">Please find attached Payment Projection Report for the day, from the Sales Team.</p>
                            <p style="font-family:verdana; font-size:14px; margin:35px 0 30px 0;"><strong>Total Projection Amount : </strong> Rs.10000</p>
                        </div>
                    </div>
                </body>
            </html>';
        
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->to('soumyajeet.lnsel@gmail.com');
            $this->email->from('noreply@rochak.lnsel.net','Rochak Sales Force APP');
            $this->email->subject('Payment Projection For '.date("Y-m-d"));
            $this->email->message($msgToemail);
            //$this->email->attach($sendFile);
            $this->email->send();
            exit;
    }
}