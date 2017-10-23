<?php namespace App\Classes;

define('_revoMail_username', 'revoMailServer',false);
define('_revoMail_password', 'pGxRtS(2b*?zv',false);
define('_revoMail_url', 'http://revocloudserver.com/mailServer/send.php',false);

define('_revoMail_group', 'AppForManageMent',false);
define('_revoMail_fromEmail', 'noreplyrevo@gmail.com',false);
define('_revoMail_fromName', 'No-reply AppForManageMent',false);

/*
//example

// Set new Email
$mail = new revoMail();

// Set subject
$mail->subject = 'สวัสดีวันจันทร์';
$mail->subject('สวัสดีวันจันทร์');

// Set content
$mail->content = '<h3>เนื้อหาครับ เนื้อหา</h3><br>สลากกินแบ่งรัฐบาล';
$mail->content('<h3>เนื้อหาครับ เนื้อหา</h3><br>สลากกินแบ่งรัฐบาล');

// Set Attachment (fullPath,name = '');
$mail->addAttachment('https://pbs.twimg.com/IDSOMcSu_400x400.jpg','rainbow');

// if you want to reset attachment
$mail->resetAttachment();

// Set BCC ($email)
$mail->addBcc('test@test.com');
$mail->addBcc('bla@bla.com');

// if you want to reset BCC
$mail->resetBcc();

// Set email receiver
$mail->addAddress($email,$name = '')

// reset email receiver
$mail->resetAddress();

// send email (return bool)
$mail->sendMail();

*/

class RevomailClass{
	public $subject = '';							// required
	public $content = '';							// required
	public $address = array();						// required
	public $bcc = array();
	public $attachment = array();
	public $fromName = _revoMail_fromName;
	public $fromEmail = _revoMail_fromEmail;
	public $group = _revoMail_group;
	public $username = _revoMail_username;
	public $password = _revoMail_password;
	public $url = _revoMail_url;

	function subject($value){
		$this->subject = $value;
	}

	function content($value){
		$this->content = $value;
	}

	function addAddress($email,$name = ''){
		$address = array();
		$address['email'] = $email;
		$address['name'] = $name;
		$this->address[] = $address;
		return $this;
	}

	function resetAddress(){
		$this->address = array();
		return $this;
	}

	function addBcc($email){
		$this->bcc[] = $email;
		return $this;
	}

	function resetBcc(){
		$this->bcc = array();
		return $this;
	}

	function addAttachment($fullPath,$name = ''){
		$attach = array();
		$attach['url'] = $url;
		if ($name != '') {
			$attach['name'] = $name;
		}
		$this->attachment[] = $attach;
		return $this;
	}

	function resetAttachment(){
		$this->attachment = array();
		return $this;
	}

	function sendMail($outputType = 'bool'){ // output = bool,array,json
		$data = array();
		if ($this->subject == '') {
			return array('status' => false,'message' => 'No Subject');
		}		
		if ($this->content == '') {
			return array('status' => false,'message' => 'No Content');
		}
		if (!$this->address) {
			return array('status' => false,'message' => 'No Address');
		}
		$data['subject'] = $this->subject;
		$data['content'] = $this->content;
		$data['fromName'] = $this->fromName;
		$data['fromEmail'] = $this->fromEmail;
		$data['group'] = $this->group;

		foreach ($this->address as $key => $value) {			
			$data['address'][] = $value;
		}

		if ($this->bcc) {
			foreach ($this->bcc as $key => $value) {			
				$data['bcc'][] = $value;
			}
		}
		
		if ($this->attachment) {
			foreach ($this->attachment as $key => $value) {			
				$data['attachment'][] = $value;
			}
		}
		
		$returnContent = file_post_contents($this->url,$data,$this->username,$this->password); // มาเป็น json

		switch ($outputType) {
			case 'array':
				$output = json_decode($returnContent,true);
				break;

			case 'json':
				$output = $returnContent;
				break;

			default: // bool
				$output = json_decode($returnContent,true);
				$output = $output['status'];
				break;
		}
		
		return $output;
	}
}

function file_post_contents($url,$data,$username = null,$password = null){

	$ch = curl_init();

	$httpHeader = array();
	$httpHeader[] = 'Content-type: application/x-www-form-urlencoded';
	
	if($username && $password) {
		$httpHeader[] = ("Authorization: Basic " . base64_encode("$username:$password"));
	}

	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));

	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$output = curl_exec ($ch);

	curl_close ($ch);

	return $output;
}
