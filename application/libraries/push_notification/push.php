<?php
 
/**
 * @author Md Isfaque
 * @link URL Tutorial link
 */
class Push {
 
    // push message title
    private $title;
    private $message;
    private $details;
    private $image;
    // push message payload
    private $data;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;
    private $type;
 
    function __construct() {
         
    }
 
    public function setTitle($title) {
        $this->title = $title;
    }
 
    public function setMessage($message) {
        $this->message = $message;
    }

    public function setDetails($details) {
        $this->details = $details;
    }
 
    public function setImage($imageUrl) {
        $this->image = $imageUrl;
    }
 
    public function setPayload($data) {
        $this->data = $data;
    }
 
    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }

    public function setType($type) {
        $this->type = $type;
    }
 
    public function getPush() {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['message'] = $this->message;
        $res['data']['details'] = $this->details;
        $res['data']['image'] = $this->image;
        $res['data']['payload'] = $this->data;
        $res['data']['timestamp'] = date('Y-m-d G:i:s');
        $res['data']['type'] = $this->type;
        return $res;
    }
 
}