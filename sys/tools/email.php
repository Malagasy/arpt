<?php

include 'php/class.phpmailer.php';


function arpt_email( $to , $subject , $message ){
	$email = new PHPMailer;

	$email->setFrom( get_setting('admin_email') , 'Admin' );

	$email->isHTML(true);

	$email->Subject = $subject;
	$email->Body = $message;

	if( is_array( $to ) ) :
		foreach( $to as $recipient )
			$email->addAddress( $recipient );
	else :
		$email->addAddress( $to );
	endif;
	
	if( !$email->send() )
		return "Mailer Error : " . $email->ErrorInfo;
	else
		return true;
}