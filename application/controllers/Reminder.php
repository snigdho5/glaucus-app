<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reminder extends CI_Controller {


    function __construct()
    {
        parent::__construct();	
        $this->load->helper('url');
        $this->load->model('Reminder_model');	
	} 

	public function index()
	{

		require_once(APPPATH . "/libraries/push_notification/firebase.php");
		require_once(APPPATH . "/libraries/push_notification/push.php");

		date_default_timezone_set('Asia/Kolkata');

		$date = date("Y-m-d");

		//$startTime = date("H:i",strtotime(date("H:i")." -5 minutes"));
		$startTime = date("H:i");
		$endTime = date("H:i",strtotime(date("H:i")." +60 minutes"));

		$startDate = DateTime::createFromFormat('H:i', $startTime);
		$endDate = DateTime::createFromFormat('H:i', $endTime);

		$meetinglist = $this->Reminder_model->get_current_meetings($date);
		$c=count($meetinglist);

		echo '[';

		for($i=0;$i<$c;$i++){

			$meetingDate = DateTime::createFromFormat('H:i', $meetinglist[$i]['mtnTime']);

			if ($meetingDate > $startDate && $meetingDate < $endDate)
			{
			   	$isUnder = "Yes";
	   			if($meetinglist[$i]['cusAddress2']=="" || $meetinglist[$i]['cusAddress2']==null){
					$customerAddress = $meetinglist[$i]['cusAddress'].', '.$meetinglist[$i]['cusCity'].'-'.$meetinglist[$i]['cusPinCode'];
				}else{
					$customerAddress = $meetinglist[$i]['cusAddress'].', '.$meetinglist[$i]['cusAddress2'].', '.$meetinglist[$i]['cusCity'].'-'.$meetinglist[$i]['cusPinCode'];
				}

		        $firebase = new Firebase();
		        $push = new Push();
		 
		        $payload = array();

				$payload['userName'] = $meetinglist[$i]['usrUserName'];
				$payload['mtnId'] = $meetinglist[$i]['mtnId'];
				$payload['mtnName'] = $meetinglist[$i]['mtnName'];
				$payload['mtnDate'] = $meetinglist[$i]['mtnDate'];
				$payload['mtnTime'] = $meetinglist[$i]['mtnTime'];
				$message = "Reminder for Meeting: ".$meetinglist[$i]['mtnName']."  Date: ".$meetinglist[$i]['mtnDate']." and Time: ".$meetinglist[$i]['mtnTime'];
				$details = "Meeting Name: ".$meetinglist[$i]['mtnName']."\n".
							"Meeting Date: ".$meetinglist[$i]['mtnDate']."\n".
							"Meeting Time: ".$meetinglist[$i]['mtnTime']."\n".
							"Customer Name: ".$meetinglist[$i]['cusFirstName']." ".$meetinglist[$i]['cusLastName']."\n".
							"Location: ".$customerAddress;

				$push->setTitle('Meeting Reminder');
				$push->setMessage($message);
				$push->setDetails($details);
				$push->setImage('');
				$push->setIsBackground(FALSE);
				$push->setPayload($payload);
				$push->setType("reminder");

				$json = $push->getPush();
				$regId = $meetinglist[$i]['usrPushToken'];
				$response = $firebase->send($regId, $json);

			}else{
				$isUnder = "No";
			}

			$data = array(
				'mtnId' => $meetinglist[$i]['mtnId'],
				'mtnName' => $meetinglist[$i]['mtnName'],
				'mtnDate' => $meetinglist[$i]['mtnDate'],
				'mtnTime' => $meetinglist[$i]['mtnTime'],
				'startTime' => $startTime,
				'endTime' => $endTime,
				'startDate' => $startDate,
				'endDate' => $endDate,
				'isUnder' => $isUnder
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';



		
	}


	public function dailyreminder()
	{

		require_once(APPPATH . "/libraries/push_notification/firebase.php");
		require_once(APPPATH . "/libraries/push_notification/push.php");

		date_default_timezone_set('Asia/Kolkata');

		$date = date("Y-m-d");

		//$startTime = date("H:i",strtotime(date("H:i")." -5 minutes"));
		$startTime = date("H:i");
		$endTime = date("H:i",strtotime(date("H:i")." +360 minutes"));

		$startDate = DateTime::createFromFormat('H:i', $startTime);
		$endDate = DateTime::createFromFormat('H:i', $endTime);

		$meetinglist = $this->Reminder_model->get_current_meetings($date);
		$c=count($meetinglist);

		echo '[';

		for($i=0;$i<$c;$i++){

			$meetingDate = DateTime::createFromFormat('H:i', $meetinglist[$i]['mtnTime']);

			if ($meetingDate > $startDate && $meetingDate < $endDate)
			{
			   	$isUnder = "Yes";
	   			if($meetinglist[$i]['cusAddress2']=="" || $meetinglist[$i]['cusAddress2']==null){
					$customerAddress = $meetinglist[$i]['cusAddress'].', '.$meetinglist[$i]['cusCity'].'-'.$meetinglist[$i]['cusPinCode'];
				}else{
					$customerAddress = $meetinglist[$i]['cusAddress'].', '.$meetinglist[$i]['cusAddress2'].', '.$meetinglist[$i]['cusCity'].'-'.$meetinglist[$i]['cusPinCode'];
				}

		        $firebase = new Firebase();
		        $push = new Push();
		 
		        $payload = array();

				$payload['userName'] = $meetinglist[$i]['usrUserName'];
				$payload['mtnId'] = $meetinglist[$i]['mtnId'];
				$payload['mtnName'] = $meetinglist[$i]['mtnName'];
				$payload['mtnDate'] = $meetinglist[$i]['mtnDate'];
				$payload['mtnTime'] = $meetinglist[$i]['mtnTime'];
				$message = "Reminder for Meeting: ".$meetinglist[$i]['mtnName']."  Date: ".$meetinglist[$i]['mtnDate']." and Time: ".$meetinglist[$i]['mtnTime'];
				$details = "Meeting Name: ".$meetinglist[$i]['mtnName']."\n".
							"Meeting Date: ".$meetinglist[$i]['mtnDate']."\n".
							"Meeting Time: ".$meetinglist[$i]['mtnTime']."\n".
							"Customer Name: ".$meetinglist[$i]['cusFirstName']." ".$meetinglist[$i]['cusLastName']."\n".
							"Location: ".$customerAddress;

				$push->setTitle('Meeting Reminder');
				$push->setMessage($message);
				$push->setDetails($details);
				$push->setImage('');
				$push->setIsBackground(FALSE);
				$push->setPayload($payload);
				$push->setType("reminder");

				$json = $push->getPush();
				$regId = $meetinglist[$i]['usrPushToken'];
				$response = $firebase->send($regId, $json);

			}else{
				$isUnder = "No";
			}

			$data = array(
				'mtnId' => $meetinglist[$i]['mtnId'],
				'mtnName' => $meetinglist[$i]['mtnName'],
				'mtnDate' => $meetinglist[$i]['mtnDate'],
				'mtnTime' => $meetinglist[$i]['mtnTime'],
				'startTime' => $startTime,
				'endTime' => $endTime,
				'startDate' => $startDate,
				'endDate' => $endDate,
				'isUnder' => $isUnder
			);

			$json = json_encode($data);

			echo $json;

            if($c-1!=$i){
				echo ",";
			}

		}

		echo ']';



		
	}




}