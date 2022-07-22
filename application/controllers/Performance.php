<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Performance extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->model('Performance_model');
        $this->load->model('Api_model');	
        $this->load->helper('url');
	} 

	public function index()
	{
		if (isset($this->session->userdata['logged_web_user'])){

			$this->load->view('include/dashboard_header');
			$this->load->view('reports/performance');

		}else{
			redirect('/login');
		}
		
	}


	public function get_performance_record()
	{
		header('Content-Type: application/json');
		$year = $this->input->get('yearName');
		$users = [];
		$users = $this->input->get('users');

		$users_data = [];

		if(count($users)>0){

			for($i=0;$i<count($users);$i++){
                
                $projectionlist =   $this->Performance_model->get_all_user_projections_by_year($year, $users[$i]['id']);
                $collectionlist =   $this->Performance_model->get_all_user_collections_by_year($year, $users[$i]['id']);
				$orderlist  =   $this->Performance_model->get_all_user_orders_by_year($year, $users[$i]['id']);
				$orderamountlist    =   $this->Performance_model->get_all_user_order_amount_by_year($year, $users[$i]['id']);
                
				$d  =   count($orderlist);
				$e  =   count($orderamountlist);
				$f  =   count($projectionlist);
				$g  =   count($collectionlist);

				$order_month[1]    =   $order_amount_month[1]    =   0;
				$order_month[2]    =   $order_amount_month[2]    =   0;
				$order_month[3]    =   $order_amount_month[3]    =   0;
				$order_month[4]    =   $order_amount_month[4]    =   0;
				$order_month[5]    =   $order_amount_month[5]    =   0;
				$order_month[6]    =   $order_amount_month[6]    =   0;
				$order_month[7]    =   $order_amount_month[7]    =   0;
				$order_month[8]    =   $order_amount_month[8]    =   0;
				$order_month[9]    =   $order_amount_month[9]    =   0;
				$order_month[10]   =   $order_amount_month[10]   =   0;
				$order_month[11]   =   $order_amount_month[11]   =   0;
				$order_month[12]   =   $order_amount_month[12]   =   0;
                
                $collect_amount_month[1]    =    $project_amount_month[1]   =   0;
                $collect_amount_month[2]    =    $project_amount_month[2]   =   0;
                $collect_amount_month[3]    =    $project_amount_month[3]   =   0;
                $collect_amount_month[4]    =    $project_amount_month[4]   =   0;
                $collect_amount_month[5]    =    $project_amount_month[5]   =   0;
                $collect_amount_month[6]    =    $project_amount_month[6]   =   0;
                $collect_amount_month[7]    =    $project_amount_month[7]   =   0;
                $collect_amount_month[8]    =    $project_amount_month[8]   =   0;
                $collect_amount_month[9]    =    $project_amount_month[9]   =   0;
                $collect_amount_month[10]   =    $project_amount_month[10]  =   0;
                $collect_amount_month[11]   =    $project_amount_month[11]  =   0;
                $collect_amount_month[12]   =    $project_amount_month[12]  =   0;

				for($k=0;$k<$d;$k++){

					//$created_at        = $orderlist[$k]['ordDate'];
					$created_at        = $orderlist[$k]['createdat'];
					$cr_time           = strtotime($created_at);
					$cr_monthNumber    = (int)date('m', $cr_time);
					$order_month[$cr_monthNumber] = $order_month[$cr_monthNumber]+1;

				}

				for($l=0;$l<$e;$l++){

					//$created_at        = $orderamountlist[$l]['ordDate'];
					//$amount            = $orderamountlist[$l]['ordAmount'];
                    $created_at        = $orderamountlist[$l]['createdat'];
					$amount            = $orderamountlist[$l]['ordertotal'];
					$cr_time           = strtotime($created_at);
					$cr_monthNumber    = (int)date('m', $cr_time);
					$order_amount_month[$cr_monthNumber] = $order_amount_month[$cr_monthNumber]+$amount;

				}
                
                for($m = 0; $m<$f; $m++){
                    $created_at        = $projectionlist[$m]['created_at'];
					$amount            = $projectionlist[$m]['totalAmount'];
					$cr_time           = strtotime($created_at);
					$cr_monthNumber    = (int)date('m', $cr_time);
					$project_amount_month[$cr_monthNumber] = $project_amount_month[$cr_monthNumber]+$amount;
				}
                
                for($n = 0; $n<$g; $n++){
                    $created_at        = $collectionlist[$n]['created_at'];
					$amount            = $collectionlist[$n]['collectionAmount'];
					$cr_time           = strtotime($created_at);
					$cr_monthNumber    = (int)date('m', $cr_time);
					$collect_amount_month[$cr_monthNumber] = $collect_amount_month[$cr_monthNumber]+$amount;
				}

				$order_Total = $order_month[1]+$order_month[2]+$order_month[3]+$order_month[4]+$order_month[5]+$order_month[6]+$order_month[7]+$order_month[8]+$order_month[9]+$order_month[10]+$order_month[11]+$order_month[12];

				$order_amount_Total = $order_amount_month[1]+$order_amount_month[2]+$order_amount_month[3]+$order_amount_month[4]+$order_amount_month[5]+$order_amount_month[6]+$order_amount_month[7]+$order_amount_month[8]+$order_amount_month[9]+$order_amount_month[10]+$order_amount_month[11]+$order_amount_month[12];
                
                $project_amount_Total = $project_amount_month[1]+$project_amount_month[2]+$project_amount_month[3]+$project_amount_month[4]+$project_amount_month[5]+$project_amount_month[6]+$project_amount_month[7]+$project_amount_month[8]+$project_amount_month[9]+$project_amount_month[10]+$project_amount_month[11]+$project_amount_month[12];
                
                $collect_amount_Total = $collect_amount_month[1]+$collect_amount_month[2]+$collect_amount_month[3]+$collect_amount_month[4]+$collect_amount_month[5]+$collect_amount_month[6]+$collect_amount_month[7]+$collect_amount_month[8]+$collect_amount_month[9]+$collect_amount_month[10]+$collect_amount_month[11]+$collect_amount_month[12];

				$data = array(
					'userId' => $users[$i]['id'],
					'userName' => $users[$i]['name'],

					'order_JAN' => $order_month[1],
					'order_FEB' => $order_month[2],
					'order_MAR' => $order_month[3],
					'order_APR' => $order_month[4],
					'order_MAY' => $order_month[5],
					'order_JUN' => $order_month[6],
					'order_JUL' => $order_month[7],
					'order_AUG' => $order_month[8],
					'order_SEP' => $order_month[9],
					'order_OCT' => $order_month[10],
					'order_NOV' => $order_month[11],
					'order_DEC' => $order_month[12],
					'order_Total' => $order_Total,

					'order_amount_JAN' => $order_amount_month[1],
					'order_amount_FEB' => $order_amount_month[2],
					'order_amount_MAR' => $order_amount_month[3],
					'order_amount_APR' => $order_amount_month[4],
					'order_amount_MAY' => $order_amount_month[5],
					'order_amount_JUN' => $order_amount_month[6],
					'order_amount_JUL' => $order_amount_month[7],
					'order_amount_AUG' => $order_amount_month[8],
					'order_amount_SEP' => $order_amount_month[9],
					'order_amount_OCT' => $order_amount_month[10],
					'order_amount_NOV' => $order_amount_month[11],
					'order_amount_DEC' => $order_amount_month[12],
					'order_amount_Total' => $order_amount_Total,
                    
                    'project_amount_JAN' => $project_amount_month[1],
					'project_amount_FEB' => $project_amount_month[2],
					'project_amount_MAR' => $project_amount_month[3],
					'project_amount_APR' => $project_amount_month[4],
					'project_amount_MAY' => $project_amount_month[5],
					'project_amount_JUN' => $project_amount_month[6],
					'project_amount_JUL' => $project_amount_month[7],
					'project_amount_AUG' => $project_amount_month[8],
					'project_amount_SEP' => $project_amount_month[9],
					'project_amount_OCT' => $project_amount_month[10],
					'project_amount_NOV' => $project_amount_month[11],
					'project_amount_DEC' => $project_amount_month[12],
					'project_amount_Total' => $project_amount_Total,
                    
                    'collect_amount_JAN' => $collect_amount_month[1],
					'collect_amount_FEB' => $collect_amount_month[2],
					'collect_amount_MAR' => $collect_amount_month[3],
					'collect_amount_APR' => $collect_amount_month[4],
					'collect_amount_MAY' => $collect_amount_month[5],
					'collect_amount_JUN' => $collect_amount_month[6],
					'collect_amount_JUL' => $collect_amount_month[7],
					'collect_amount_AUG' => $collect_amount_month[8],
					'collect_amount_SEP' => $collect_amount_month[9],
					'collect_amount_OCT' => $collect_amount_month[10],
					'collect_amount_NOV' => $collect_amount_month[11],
					'collect_amount_DEC' => $collect_amount_month[12],
					'collect_amount_Total' => $collect_amount_Total
				);
				array_push($users_data, $data);
			}
		}
		$json = json_encode($users_data);
		echo $json;
	}


	public function get_all_years()
	{
		header('Content-Type: application/json');
		$yearlist= $this->Performance_model->get_all_years();

		$c=count($yearlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'yearId' => $yearlist[$i]['yearId'],
				'yearName' => $yearlist[$i]['yearName']
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}


	public function get_all_users()
	{
		header('Content-Type: application/json');
		$parent_path = $this->session->userdata['logged_web_user']['userParentPath']."-".$this->session->userdata['logged_web_user']['userId'];
		$userlist= $this->Performance_model->get_all_users($parent_path);

		$c=count($userlist);
		echo '[';

		for($i=0;$i<$c;$i++){

			$data = array(
				'icon' => '<img src='.base_url().'assets/dist/img/usericon.png'.' />',
				'name' => $userlist[$i]['usrUserName'],
				'maker' => ' ('.$userlist[$i]['parentName'].')',
				'id' => $userlist[$i]['usrId'],
				'ticked' => false
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';

	}

    public function get_performance_logs()
    {
        header('Content-Type: application/json');
		$year     =   $this->input->get('yearName');
		$users    =   [];
		$users    =   $this->input->get('users');
        
        
    }

}