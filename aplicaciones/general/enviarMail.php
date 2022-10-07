<?php

function send_email($to, $from, $subject, $body) {
/*	require_once "Mail.php";
	require_once "Mail/mime.php";

	$host = "192.168.15.11";

	$headers = array (
			'From' => $from,
			'To' => $to,
			'Subject' => $subject
	);

	$mime = new Mail_mime();
	$mime->setHTMLBody($body);

	$body = $mime->get();
	$headers = $mime->headers($headers);

	$smtp = Mail::factory('smtp', array ('host' => $host));
	$mail = $smtp->send($to, $headers, $body);
	
	

	if (PEAR::isError($mail)) {
		echo $mail;
		return false;

	} else {
		return true;
	}*/
	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
	$headers .= 'From: <'.$from.'>' . "\r\n";
	
	$res = mail($to,$subject,$body,$headers);
	if ($res){
		return 'correo enviado';
	}else{
		return 'error enviando el email';
	}


}

?>