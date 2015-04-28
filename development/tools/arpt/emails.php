<?php

function contact_form_email( $email , $sujet , $content ){
	$to = 'andyralanto@gmail.com';

	$message = '<p>Message envoyé par : ' . $email . '</p>';
	$message .= '<p>Sujet du message : ' . $sujet . '</p>';
	$message .= '<hr>';
	$message .= $content;
	$message .= '<hr>';
	$message .= 'Envoyé le ' . arpt_date( time() );

	die( arpt_email( $to , '[ARPT][Contact] Nouveau message' , $message ) );
}