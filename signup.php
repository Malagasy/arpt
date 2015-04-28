<!doctype html>
<html lang="fr-FR">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php admin_head(); ?>
		<title>
			<?php echo 'Espace utilisateur - ' . sitename() ; ?>
		</title>
		<link href="<?php echo get_admin_css('bootstrap/signin.css') ?>" rel="stylesheet">
	</head>	
	<body>

	<div class="container">
		<div class="signup">
			<div style="margin-bottom:20px"></div>

<?php
if( is_arg( 'new' ) ) :
	form_open( array( 'class' => 'form-signin' ) );

	echo '<h2 class="form-signin-heading">Inscrire un compte</h2>';

	form_input( array( 'name' => 'signup-user' , 'type' => 'hidden' , 'value' => create_token( 'signup-user' ) ) );
	
	form_input( array( 'name' => 'name' , 'value' => last_value('name') , 'class' => 'form-control' , 'placeholder' => 'Identifiant' ) , null );

	form_input( array( 'type' => 'password' , 'name' => 'pass' , 'class' => 'form-control' , 'placeholder' => 'Mot de passe'  ) , null );

	form_submit( array( 'value' => 'Inscrire un compte !', 'class' => 'btn btn-lg btn-primary btn-block'  ) ); 

	div( array( 'class' => 'list-group  margin-top-20' ) );
	echo  a( get_signup_url('in') , 'Je suis déjà inscrit' , array( 'class' => 'list-group-item' ) );
	div_close();

	form_close();
elseif( is_arg('in') || is_arg(null) ) :

	if( isset( $_GET['retrieved'] ) )
		echo '<p class="alert-success logforuser text-center">Le mot de passe a bien été modifié. Essayez de vous connecter.</p>';

	form_open( array( 'class' => 'form-signin' ) );

	echo '<h2 class="form-signin-heading">Connectez-vous SVP</h2>';

	form_input( array( 'name' => 'login-user' , 'type' => 'hidden' ) );

	form_input( array( 'name' => 'name' , 'value' => last_value( 'name' ) , 'class' => 'form-control' , 'placeholder' => 'Identifiant' ) , null , 'required autofocus' );

	form_password( array( 'name' => 'pass', 'class' => 'form-control' , 'placeholder' => 'Password' ) , null , 'required' );

	form_submit( array( 'value' => 'Se connecter' , 'class' => 'btn btn-lg btn-primary btn-block' ) ); 

	div( array( 'class' => 'list-group  margin-top-20' ) );
	echo a( get_signup_url('new') , 'Pas encore inscrit ?' , array( 'class' => 'list-group-item' ) );
	echo a( get_signup_url('retrieve') , 'Mot de passe oublié ?' , array( 'class' => 'list-group-item' ) );
	div_close();

	form_close();


elseif( is_arg('retrieve') ) :
	$validate = new FormValidation;

	if( isset( $_POST['retrieve-user'] ) ) :

		$validate->email('email');

		if( $validate->isValid() ) :
			if( retrieve_password( $_POST['email'] ) === true )
				echo '<p class="alert-success logforuser text-center">Un email a été envoyé à l\'adresse indiqué.</p>';
			else
				echo '<p class="alert-danger logforuser text-center">L\'email n\'appartient à aucun compte.</p>';
		else :
			echo $validate->get_first();
		endif;

	elseif( valid_source( 'update_password' , get_pageargs(1) ) ) :
		$userid = get_pageargs(1);

		$validate->required('password');
		$validate->required('password-confirm');
		$validate->equals( 'password' , $_POST['password-confirm'] );

		if( $validate->isValid() ) :
			if( update_password( $userid , $_POST['password'] ) ) :
				delete_token( 'retrieve_password' , null , $userid );
				redirect( get_signup_url('?retrieved=1') );
			endif;
		else :
			echo '<p class="alert-danger logforuser text-center">Les mots de passe ne correspondent pas.</p>';
		endif;

	endif;

	if( count_args() == 3 ) :
		if( check_token( 'retrieve_password' , get_pageargs(2) , get_pageargs(1) , false ) ) :
			retrieve_form_password();
		else :
			redirect( get_signup_url() . 'retrieve/' );
		endif;
	else :
		form_open(  array( 'class' => 'form-signin' )  );

		echo '<h2 class="form-signin-heading">Récupération</h2>';

		form_input( array( 'name' => 'retrieve-user' , 'type' => 'hidden' , 'value' => '' ) );

		form_input( array( 'type' => 'email' , 'name' => 'email' , 'value' => last_value( 'email' ) , 'class' => 'form-control fusion-bottom' , 'placeholder' => 'Adresse email' ) ,  null );

		form_submit( array( 'class' => 'btn btn-lg btn-primary btn-block fusion-top'  ) );


		div( array( 'class' => 'list-group  margin-top-20' ) );
		echo a( get_signup_url('in') , 'Se connecter' , array( 'class' => 'list-group-item' ) );
		echo a( get_home_url() , 'Revenir sur le site' , array( 'class' => 'list-group-item' ) );
		div_close();

		form_close();
	endif;
endif;

echo '</div></div></body></html>';