<?php
function signin_form( $args = null , $labelName = 'Pseudonyme' , $labelPassword ='Mot de passe' , $labelBtn = 'Se connecter' ){

	form_open( $args );

	form_input( array( 'name' => 'login-user' , 'type' => 'hidden' ) );


	div( array( 'class' => 'form-group' ) );
	form_input( array( 'class' => 'form-control' ,  'name' => 'name' , 'value' => last_value('name') ) , $labelName , 'required');
	div_close();

	div( array( 'class' => 'form-group' ) );
	form_input( array( 'class' => 'form-control' , 'type' => 'password' , 'name' => 'pass' ) , $labelPassword , 'required');
	div_close();

	form_submit( array( 'class' => 'btn btn-default' , 'value' => $labelBtn ) ); 

	form_close();
}
function signup_form( $args = null , $labelName = 'Pseudonyme' , $labelPassword = 'Mot de passe' ){

	form_open( $args );

	form_input( array( 'name' => 'signup-user' , 'type' => 'hidden' , 'value' => create_token( 'signup-user' ) ) );
	
	div( array( 'class' => 'form-group' ) );
	form_input( array( 'name' => 'name' , 'value' => last_value('name') ) , $labelName );
	div_close();

	div( array( 'class' => 'form-group' ) );
	form_input( array( 'type' => 'password' , 'name' => 'pass' ) , $labelPassword );
	div_close();

	form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'S\'inscrire' ) ); 

	form_close();
}

function search_form( $placeholder = 'Votre recherche...'){
	form_open( array( 'method' => 'post' , 'action' => 'search' , 'class' => 'form-inline' ) );
	
	form_input( array( 'type' => 'hidden' , 'name' => 'doArequest' ) );
	
	div( array( 'class' => 'form-group' ) );
	form_input( array( 'type' => 'text' , 'class' => 'form-control' , 'name' => 'search' , 'value' => last_value( 'search' ) , 'placeholder' => $placeholder ) );
	div_close();
	
	form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Go' ) ) ;
	
	form_close();
}

function comments_form(){
	$userid = is_user_online() ? get_currentuserid() : 0;

	form_open( array( 'method' => 'post' ) );
	
	form_input( array('type' => 'hidden' , 'name' => 'content_type' , 'value' => 'commentaire' ));
	
	form_input( array('type' => 'hidden' , 'name' => 'redirect' , 'value' => get_clean_url('?posted=1')  ));

	form_input( array('type'=> 'hidden' , 'name' => 'userid' , 'value' => $userid ) );

	form_input( array('type'=> 'hidden' , 'name' => 'title' , 'value' => get_currentcontentname() . ' - ' . get_username( $userid ) ) );
		
	form_input( array( 'type' => 'hidden' , 'name' => 'parentid' , 'value' => get_currentcontentid() ) );
	
	form_textarea( array( 'name' => 'message' , 'rows' => 7, 'class' => 'form-control fusion-bottom' ) , null , last_value('message') );
	
	form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Valider' ) );
	
	form_close();
}

function retrieve_form( $args =null , $label = 'Votre Email'){
	form_open( $args );

	form_input( array( 'name' => 'retrieve-user' , 'type' => 'hidden' , 'value' => '' ) );

	div( array( 'class' => 'form-group' ) );
	form_input( array( 'name' => 'email' , 'value' => last_value( 'email' ) ) , $label );
	div_close();

	form_submit( array( 'class' => 'btn btn-primary' ) );

	form_close();
}

function retrieve_form_password( $arg = array(), $title = '<h2 class="form-signin-heading">Récupération</h2>' , $labels = array() ){
	form_open( array_merge( array( 'class' => 'form-signin' ) , $arg ) );


	echo call_layers('retrieve_form_password_header_layer' , $title , $arg );

	$labels['label_password'] = null;
	$labels['label_confirm'] = null;
	$labels['placeholder_password'] = 'Mot de passe';
	$labels['placeholder_confirm'] = 'Confirmer';

	$labels = call_layers( 'retrieve_form_password_labels_layer' , $labels , $arg );

	form_hidden( array( 'name' => 'update_password' , 'value' => create_token( 'update_password' , get_pageargs(1) ) ) );

	form_password( array( 'name' => 'password' , 'class' => 'form-control' , 'placeholder' => $labels['placeholder_password'] ) , $labels['label_password'] );
	form_password( array( 'name' => 'password-confirm' , 'class' => 'form-control minus-margin-top-10' , 'placeholder' => $labels['placeholder_confirm'] ) , $labels['label_confirm'] );

	form_submit( array( 'class' => 'btn btn-primary  btn-lg btn-block' ) );

	form_close();
}

function form_unsafe_submit(){
	form_hidden( array( 'name' => 'unsafe_submit' , 'value' => create_token( 'unsafe_submit' ) ) );
}