<?php

add_trigger( 'dev_activation' , 'init_newsletters_module' );
function init_newsletters_module(){
	add_dynamic_widget( 'Widget NewsLetters' , 'widget_newsletters' );
//	admin_add_submenu();

	if( is_adminpage() ){
		admin_add_submenu( 'arpt-modules' , 'newsletters_options' , 'Newsletters' , 'newsletters_adminpage_options' , 'manage-modules' );
		make_tinymceable( 'newsletters_options' );
	}

}

function widget_newsletters(){
	if( action_edit_widget() ) :
		form_open( );
		form_hidden( array( 'name' => 'edit-widget' , 'value' => 'widget_newsletters' ) );


		div( array( 'class' => 'form-group' ) );
		form_input( array( 'class' => 'form-control' , 'name' => option('widget_title') , 'value' => strip_tags( widget_title('widget_newsletters') ) ) , 'Titre de la section' );
		div_close();

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'class' => 'form-control' , 'name' => option('widget_newsletters_header_message') , 'value' =>  get_option('widget_newsletters_header_message') ) , 'Message d\'en tête' );
		div_close();

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'class' => 'form-control' , 'name' => option('widget_newsletters_footer_message') , 'value' =>  get_option('widget_newsletters_footer_message') ) , 'Message de fin' );
		div_close();

		form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Update' ) );
		form_close();


	return;
	endif;

	$success = false;
	
	if( valid_source('newsletters') ) :
		if( !newsletters_check_email( $_POST['newsletters_useremail'] ) && is_email( $_POST['newsletters_useremail'] ) ) :
			newsletters_add_email( $_POST['newsletters_useremail'] );
			$success = true;
		endif;

	endif;

	div( array( 'class' => 'tool-newsletters' ) );

	echo call_layers( 'newsletters_title_layer' , widget_title('widget_newsletters') );

	form_open();
	form_input( array( 'type' => 'hidden' , 'name' => 'newsletters' , 'value' => create_token( 'newsletters' ) ) );
	div( array( 'class' => 'form-group has-feedback' ) );

	form_input( array( 'class' => 'form-control' , 'type' => 'text' , 'name' => 'newsletters_useremail' , 'placeholder' => 'Entrez votre email' ) );
	echo '<i class="glyphicon glyphicon-envelope form-control-feedback"></i>'; 

	div_close();

	$submit_params = call_layers( 'newsletters_submit_params' , array( 'value' => strong(strtoupper('SIGN UP')) , 'class' => 'btn btn-primary btn-block' ) );
	form_submit( $submit_params );
	form_close();

	div_close();
	?>

	<?php if( $success ) : ?>
	<script type="text/javascript">
		jQuery(".tool-newsletters").html("<h4>Je vous remercie :-)</h4>").effect("shake",200);
	</script>
	<?php endif;
}

function newsletters_check_email( $email ){
	$emails = newsletters_get_emails();

	if( in_array( $email , $emails ) )
		return true;
	return false;
}
function newsletters_signup_form(){
	form_open();
	form_input( array( 'type' => 'hidden' , 'name' => 'newsletters' , 'value' => create_token( 'newsletters' ) ) );
	div( array( 'class' => 'input-group' ) );

	form_input( array( 'class' => 'form-control' , 'type' => 'text' , 'name' => 'newsletters_useremail' , 'placeholder' => 'Adresses emails à séparer par des virgules si plusieurs (ex: aaa@bb.fr, ccc@bb.fr)' ) );
	span( array( 'class' => 'input-group-btn' ) );
	form_submit( array( 'value' => 'Ajouter' , 'class' => 'btn btn-default' ) );
	span_close();
	div_close();
	form_close();
}

function newsletters_add_email( $email ){

	$current_emails = newsletters_get_emails();

	if( !is_array( $email ) )
		$emails = explode( ',' , $email );
	else
		$emails = $email;

	foreach( $emails as $_email ) : $_email = trim( $_email );

		if( !is_email( $email ) ) continue;

		if( newsletters_check_email( $_email ) ) continue;

		$current_emails[] = $_email;
	endforeach;

	set_option( 'module_newsletters_emails' , serialize( $current_emails ) );
}

function newsletters_remove_email( $email ){
	$current_emails = newsletters_get_emails();

	if( newsletters_check_email( $email ) ){
		$key_email = array_search( $email, $current_emails );
		unset( $current_emails[$key_email] );
	}

	set_option( 'module_newsletters_emails' , serialize( $current_emails ) );
}

function newsletters_adminpage_options(){ 
	if( valid_source( 'send_newsletters' ) ) newsletters_sendToAll( newsletters_get_emails() , $_POST['newsletters_subject'] , $_POST['newsletters_message'] );

	if( valid_source('newsletters') ) if( newsletters_add_email( $_POST['newsletters_useremail'] ) ) redirect_success();
	?>

	<?php 
	fieldset_open( 'Ajouter un email' , null , array( 'class' => 'hoverable' ) );

	div( array( 'class' => 'fade hidden' ) );
		newsletters_signup_form();
		span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'Vous pouvez aussi ajouter un utilisateur inscrit depuis l\'interface Utilisateur.' );

	div_close();

	fieldset_open( 'Liste des emails' , null , array( 'class' => 'hoverable' )  );

	div( array( 'class' => 'fade hidden' ) );
		$total_mails = newsletters_count_emails();
		$limit = 20;
		$offset = ( isset( $_GET['p'] ) ) ? ($_GET['p']-1)*$limit : 0;
		$cp = $offset == 0 ? 1 : $offset;

		div( array( 'class' => 'panel panel-default' ) );
		div( array( 'class' => 'panel-heading' ) , ' Liste des emails enregistrés ( ' . $offset . ' - ' . ($offset + $limit) . ' sur ' . $total_mails . ' ) ');
		echo '<table class="table table-hover ">';
		echo '<thead><tr>';
		echo '<th class="col-sm-3">Adresse email</th>';
		echo '<th class="col-sm-2">Compte associé</th>';
		echo '</tr></thead>';
		echo '<tbody>';
		$emails = newsletters_get_emails( $offset , $limit );
		if( $emails ) :
			foreach( $emails as $email ) :
				echo '<tr>';
				echo '<td>' . $email . '<a href="#" data-email="' . $email . '" class="label label-danger newsletters-delete-email"><span class="glyphicon glyphicon-trash"></span></a>
				</td>';
				echo '<td>';
				if( $user = user_exists( array( 'email' => $email ) ) )
					echo $user->qname();
				else
					echo ' - ';
				echo '</td>';
				echo '</tr>';
			endforeach;
		endif;
		echo '</tbody>';
		echo '</table>';
		div_close();

		echo '<nav class="text-right">';
		custom_paginate( 3 , $cp , $total_mails/$limit );
		echo '</nav>'; ?>
		<script type="text/javascript">
			jQuery(document).on("click",".newsletters-delete-email" ,function(e){
				e.preventDefault();
				if( confirm("Supprimer l'adresse email?") ){
					jQuery(this).parent().parent().remove();
					phpajax( "newsletters_remove_email" , jQuery(this).attr("data-email") );
				}
			});

		</script>

		<?php

	div_close();

	fieldset_open( 'Ecrire à tous'  , null , array( 'class' => 'hoverable' ) );

	div( array( 'class' => 'fade hidden' ) );

		form_open();
		form_input( array( 'type' => 'hidden' , 'name' => 'send_newsletters' , 'value' => create_token( 'send_newsletters' ) ) );

		div( array( 'class' => 'form-group') );
		form_input( array( 'class' => 'form-control' , 'type' => 'text' , 'name' => 'newsletters_subject' , 'placeholder' => 'Sujet du mail' ) , null , 'required' );
		div_close();
		form_textarea( array( 'class' => 'form-control' , 'name' => 'newsletters_message' ) , null , null );
		form_submit( array( 'value' => 'Envoyer' , 'class' => 'btn btn-default' ) );
		form_close();

	div_close();
}

function newsletters_sendToAll( $tos , $subject , $message ){

	if( empty( $tos ) ) return;
	
	arpt_email( $tos , $subject , $message );
}

function newsletters_get_emails( $offset = null , $limit = null ){
	$emails = unserialize( get_option( 'module_newsletters_emails' ) );

	if( !$emails ) return array();

	if( is_null( $offset ) && is_null( $limit ) )
		return $emails;

	return array_slice( $emails , $offset , $limit );


}

function newsletters_count_emails(){
	return count( newsletters_get_emails() );
}

