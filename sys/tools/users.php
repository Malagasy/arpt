<?php

function get_username( $id ){
	if( is_number( $id ) )
		return get_userinfo( $id , 'username' );
	return get_userinfo( get_userid( $id ) , 'username' );
}

function get_userslug( $id ){
	if( is_number( $id ) )
		return get_userinfo( $id , 'slug' );
	return get_userinfo( get_userid( $id ) , 'slug' );
}

function get_userid( $name ){
	return get_userinfo( $name , 'id' );

}

function get_useremail( $id ){
	if( is_number( $id ) )
		return get_userinfo( $id , 'email' );
	return get_userinfo( get_userid( $id ) , 'email' );
}

function update_password( $userid , $password ){
	return update_userinfo( $userid , 'pass' , $password );
}

function retrieve_password( $email ){
	$user = new_user( array( 'email' => $email ) );

	if( !$user->next() ) return false;

	$userid = $user->qid();

	$token_retrieve_password = create_token( 'retrieve_password' , $userid );

	$link = get_signup_url('retrieve') . '/' . $userid . '/' . $token_retrieve_password . '/';

	$message = "<p>Bonjour {$user->qname()},</p>";
	$message .= "<p>Ce message fait suite à une demande de récupération de votre mot de passe. Cliquez <a href=\"{$link}\">ici</a> : </p>";
	$message .= "<p>" . $link . "</p>";
	$message .= "<p>Si vous n'êtes pas l'auteur de cette demande, ignorez tout simpement ce mail.</p>";
	$message .= "<p>Bonne journée</p>";

	$message = call_layers( 'retrieve_password_message_layer' , $message );

	return arpt_email( $email , '[' . sitename() . '] Récupération de votre Mot de passe' , $message );
}