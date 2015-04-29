<?php

class Arpt{
	
	private function __construct(){
		$this->systempage = array( 'admin', 'signup' , 'quit' );
		$this->systemtable = array( 'arpt_categories' , 'arpt_contents' , 'arpt_contents_categories' , 'arpt_contents_properties' , 'arpt_general' , 'arpt_keywords' , 'arpt_options' , 'arpt_users' , 'arpt_users_properties' );
		$this->entity = array();

		$this->complete_installation = $this->is_installed();

	}
	
	
	public static function getInstance(){
		global $arpt;
		if( is_null($arpt) )
			return new Arpt();
		return $arpt;
	}
	
	public function activation(){	
		$this->config = Config::getInstance();
		$this->pageinfo = $this->pageinfo();
		Siteconfiguration::getInstance();

		$this->tools_activation();

		if( !$this->complete_installation ) $this->installation();

		if( !is_user_online() ) return;

		$this->currentuser = new Users( get_currentuserid() );

		call_triggers('arpt_actived');

	}

	private function tools_activation(){
		$this->tools = get_active_tools();
		foreach( $this->tools as $tool ){
			if( file_exists( get_tool_dir() . '/' . $tool . '/' . $tool . '.php' ) )
				$this->load( get_tool_dir() . '/' . $tool . '/' . $tool . '.php' );
		}
		call_triggers('tools_activated');
	}

	public function pageinfo(){
		$url = ltrim( $_SERVER['REQUEST_URI'] );
		
		if( defined('BASE_URL' ) )
			$this->base_url = BASE_URL;
		elseif( $base_url_from_db = get_setting('base_url') )
			$this->base_url = $base_url_from_db;
		else
			$this->base_url = '';

		$the_url = array_filter( explode( '/' , strtok( substr( trimslash( $url ) , strlen( $this->base_url ) ) , '?' ) ) );


		return Pageinfo::getInstance( $the_url );
	}

	
	public function development_activation(){

		call_triggers('dev_activation');

		
		if( file_exists( get_theme_dir() . '/functions.php' ) )
			$this->load( get_theme_dir() . '/functions.php' );

		$this->queried = $this->loadquery();

		call_triggers( 'config_setup' );

		if( !is_systempage() ) call_triggers( 'theme_setup' );

	
	}
		
	public function loadquery(){
		//$was_null = ( is_null( $specificities ) ) ? true : false;
		if( is_systempage() ) return null;

		$limit = get_option('contents_per_page');
		$o = the_offset();

		if( is_queriablepage() ) :

				if( is_homepage() ) 
					$default = array( 	'selection' => '*',
										'type' 	=> 'article',
										'id'	=> null,
										'userid' => null,
										'parent_id'	=> null,
										'slug'	=> null,
										'orderby' => 1,
										'ob_suffix' => ' DESC ',
										'limit'	=> $limit,
										'offset' => $o,
										'specified_ids' => null,
										'year' => null,
										'month' => null,
										'day' => null,
										'search' => null,
										'category' => null
											);
				elseif( is_contentpage() )
					$default = array( 	'selection' => '*',
										'type'	=> get_pagetype(),
										'id'	=> null,
										'userid' => null,
										'parent_id'	=> null,
										'slug'	=> get_pageargs(0),
										'orderby' => 1,
										'ob_suffix' => null,
										'limit' => $limit,
										'offset' => $o,
										'specified_ids' => null,
										'year' => null,
										'month' => null,
										'day' => null,
										'search' => null,
										'category' => null
										);
				elseif( is_searchpage() )	
					$default = array( 	'selection' => '*',
										'type' 	=> null,
										'id'	=> null,
										'userid' => null,
										'parent_id'	=> null,
										'slug'	=> null,
										'orderby' => 1,
										'ob_suffix' => null,
										'limit'	=> $limit,
										'offset' => $o,
										'specified_ids' => get_ids_by_tag( do_slug(get_pageargs(0)) , 'string' ),
										'year' => null,
										'month' => null,
										'day' => null ,
										'search' => do_slug(get_pageargs(0)),
										'category' => null
											);			
				elseif( is_keywordspage() )
					$default = array( 	'selection' => '*',
										'type' 	=> null,
										'id'	=> null,
										'userid' => null,
										'parent_id'	=> null,
										'slug'	=> null,
										'orderby' => 1,
										'ob_suffix' => null,
										'limit'	=> $limit,
										'offset' => $o,
										'specified_ids' => get_ids_by_tag( do_slug(get_pageargs(0)) , 'string' ),
										'year' => null,
										'month' => null,
										'day' => null,
										'search' => null,
										'category' => null
											);	
				elseif( is_archivepage() && filter_is_year( get_pagetype() ) )
					$default = array( 	'selection' => '*',
										'type' 	=> 'article',
										'id'	=> null,
										'userid' => null,
										'parent_id'	=> null,
										'slug'	=> null,
										'orderby' => 1,
										'ob_suffix' => ' DESC ',
										'limit'	=> $limit,
										'offset' => $o,
										'specified_ids' => null,
										'year' => get_pagetype(),
										'month' => get_pageargs(0),
										'day' => get_pageargs(1),
										'search' => null,
										'category' => null
											);							
				elseif( is_archivepage() && ( get_pagetype() == routing_author() ) )
					$default = array( 	'selection' => '*',
										'type' 	=> 'article',
										'id'	=> null,
										'userid' => ( !( $id = get_userid( get_pageargs(0) ) ) ) ? 666666 : $id,
										'parent_id'	=> null,
										'slug'	=> null,
										'orderby' => 1,
										'ob_suffix' => ' DESC ',
										'limit'	=> $limit,
										'offset' => $o,
										'specified_ids' => null,
										'year' => null,
										'month' => null,
										'day' => null,
										'search' => null,
										'category' => null
											);	
				elseif( is_categorypage() )
					$default = array( 	'selection' => '*',
										'type' 	=> null,
										'id'	=> null,
										'userid' => null,
										'parent_id'	=> null,
										'slug'	=> get_pageargs(0),
										'orderby' => 'content_title',
										'ob_suffix' => ' ASC ',
										'limit'	=> $limit,
										'offset' => $o,
										'specified_ids' => null,
										'year' => null,
										'month' => null,
										'day' => null,
										'search' => null,
										'category' => get_pagetype()
											);	

				$content = new_content( $default );
			else :
				$default = array( 	'selection' => '*',
										'type' 	=> null,
										'id'	=> null,
										'userid' => null,
										'parent_id'	=> null,
										'slug'	=> get_pagetype(),
										'orderby' => null,
										'ob_suffix' =>null,
										'limit'	=> $limit,
										'offset' => $o,
										'specified_ids' => null,
										'year' => null,
										'month' => null,
										'day' => null,
										'search' => null,
										'category' => null
											);	
				$content = new_content($default);
				if( $content->qhas() ) :
					$content->qnext();
					$this->pageinfo->set_pagetype( $content->qtype() );
					$content->qreset();
				else :
					$this->pageinfo->set_pagetype( 'error' );
				endif;
			endif;

			$content->qnext();

			// doing some test here
			if( $content->qstatus() == 'not-public' )
				$content = new_content( array( 'slug' => 'thisIsAPrivateContent' ) );


			$content->qreset();




			return $content;
	}

	public function get_systempage(){
		return $this->systempage;
	}
	
	
	public function get_config(){
		return $this->config;
	}
	
	public function get_pageinfo(){
		return $this->pageinfo;
	}
	
	public function get_base_url(){
		return $this->base_url;
	}

	public function get_queried(){
		return $this->queried;
	}
	public function set_queried( $specificities ){
		$this->queried = new_content( $specificities );
	}
	
	public function get_currentuser(){
		return $this->currentuser;
	}

	public function get_entity( $name ){
		if( isset( $this->entity[$name]) )
			return $this->entity[$name];
		return array();
	}
	public function set_entity( $name , $entity ){
		$this->entity[$name] = $entity;
	}

	public static function is_ajaxcall(){
		if( defined('THE_AJAX_CALL' ) )
			return THE_AJAX_CALL === true ? true : false;
		return false;
	}

	private function checkurl(){ 
		//if( is_adminpage() ){	
			//if( !currentusercan( 'view-backend' ) || ( is_arg( 'general' ) && !currentusercan( 'manage-settings') ) ||  ( is_arg( array( 'add-content' , 'list-contents' , 'edit-content' ) ) && !currentusercan( 'manage-contents') ) || ( is_arg( array( 'category' , 'contents' , 'navmenu' , 'widgetmenu' ) ) && !currentusercan( 'manage-options') )  )
				//critical_error( 'Vous ne pouvez pas accéder à cette page.' );
	//	}
		$allargs =  $this->pageinfo->get_params();
		
		$url = '';
		foreach( $allargs as $arg ) :
			if( empty( $url ) )
				$url .= $arg;
			else
				$url .= '/' . $arg;
			if( $restriction = get_restriction( trimslash( $url ) ) ) : 
				if( !currentusercan( $restriction ) ) :
					critical_error( 'Vous ne pouvez pas accéder à cette page.' );
					break;
				endif;
			endif;
		endforeach;

		check_critical_error();

		if( is_contentpage() || is_categorypage() ) :
			if( !$this->queried->qhas() )  $this->pageinfo->set_pagetype('error');

			$this->pageinfo = call_layers( 'checkurl_layer' , $this->pageinfo , $this->queried );
		endif;

		call_triggers( 'after_checkurl' );
			
	}
	
	public function routing(){
		
		$this->checkurl();

		call_triggers( 'before_routing' );

		$this->pageinfo->seo();

		call_triggers( 'the_routing' );

		switch( $page = $this->pageinfo->get_pagetype() ) {
			case routing_home():
				if( file_exists( page_dir( HOME_FILE ) ) )
					$this->load( page_dir( HOME_FILE ) );
				else
					$this->load( page_dir('index.php') );
				break;
			case filter_is_active_content( $page ):
				if( $this->queried->total == 1 && $this->queried->qmodel() && file_exists( page_dir( $this->queried->qmodel() ) ) ) :	
					$this->load( page_dir( $this->queried->qmodel() ) );
				elseif( file_exists( page_dir( $page . '.php' ) ) ) :
					$this->load( page_dir( $page . '.php' ) );
				else:
					$this->load( page_dir('contents.php') );
				endif;
				break;
			case routing_search():
				$this->load( page_dir( 'search.php' ) );
				break;
			case routing_keywords():
				if( file_exists( page_dir( 'keywords.php' ) ) )
					$this->load( page_dir( 'keywords.php' ) );
				else
					$this->load( page_dir( 'error.php' ) );
				break;				
			case routing_author():
				if( file_exists( page_dir( 'archive.php' ) ) )
					$this->load( page_dir( 'archive.php' ) );
				else
					$this->load( page_dir( 'error.php' ) );
				break;
			case filter_is_year( $page ) :
				if( file_exists( page_dir( 'archive.php' ) ) )
					$this->load( page_dir( 'archive.php' ) );
				else
					$this->load( page_dir( 'error.php' ) );
				break;
			case filter_is_category( $page ) :
				if( $this->queried->qmodel() && file_exists( page_dir( $this->queried->qmodel() ) ) )
					$this->load( page_dir( $this->queried->qmodel() ) );
				elseif( file_exists( page_dir( 'category-' . $page . '.php' ) ) )
					$this->load( page_dir( 'category-' . $page . '.php' ) );
				elseif( file_exists( page_dir( 'category.php' ) ) )
					$this->load( page_dir( 'category.php' ) );
				else
					$this->load( page_dir( 'contents.php') );
				break;
			case 'admin':
				$this->load( 'admin.php' );
				break;
			case 'signup':
				if( !is_user_online() )
					$this->load( 'signup.php' );
				else
					$this->redirect( get_home_url() );
				break;
			case 'error':
				 $this->load( page_dir( 'error.php' ) );
				break;
			case 'quit':
				session_destroy();
				$this->redirect( get_home_url() );
				break;
			default:
				if( $this->queried->total == 1 && file_exists( get_theme_dir() . '/' . $page . '.php' ) )
					$this->load( get_theme_dir() . '/' . $page . '.php' );
				else
					$this->load( page_dir( 'error.php' ) );
		}
		call_triggers( 'after_routing' );
	}
	
	public function load( $path ){
		include $path;
	}
	
	public function redirect( $path ){
		if( !headers_sent() ) :
			header( 'Location:' . $path );
		else :
			$string = '<script type="text/javascript">';
	        $string .= 'window.location = "' . $path . '"';
	        $string .= '</script>';
        	echo $string;
        endif;
        exit(1);
	}

	private function is_installed(){

		@$link = new mysqli(MYSQLI_LOCALHOST,MYSQLI_ROOT,MYSQLI_PASSWORD,null);
		if( !@$link->ping() )
			die('Nous ne pouvons pas vous connecter, verifiez vos informations de connexion.' );


		if( MYSQLI_DATABASE == null ) die( 'Spécifiez une base de données pour votre site.' );
		else $check_db = $link->query( "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '". MYSQLI_DATABASE ."' ");

		if( $check_db->num_rows == 0 ) die( 'La base de donnees specifiee dans le fichier <strong>settings.php</strong> n\'existe pas.' );

		$link->select_db( MYSQLI_DATABASE );

		foreach( $this->systemtable as $table ) :
			$check_table = $link->query( "SHOW TABLES LIKE '" . $table . "'" );
			if( $check_table->num_rows == 0 ) return false;
		endforeach;

		return true;

	}

	private function installation(){
		session_destroy();

		if( !isset( $_GET['step'] ) ) $this->redirect( get_current_url( '?step=welcome' ) );

		$link = new mysqli(MYSQLI_LOCALHOST,MYSQLI_ROOT,MYSQLI_PASSWORD,MYSQLI_DATABASE);
		$check_general_table = $link->query( "SHOW TABLES LIKE 'arpt_general'" );
		if( ( $_GET['step'] == 'welcome' ) && $check_general_table->num_rows == 1 ) $this->redirect( get_clean_url() . '?step=config' );
		
		$user = $link->query("SELECT * FROM arpt_users WHERE id=1");
		if( $_GET['step'] == 'end' && !$user->num_rows && !($_SERVER['REQUEST_METHOD'] == 'POST') ) $this->redirect( get_clean_url() . '?step=welcome' );

		$message_error = false;

		if( $_GET['step'] == 'config' && !empty( $_POST ) ){
			if( isset( $_POST['step_config'] ) ){
				$validat = new FormValidation;

				$validat->required('sitename');
				$validat->required('sitedescription');
				$validat->email('email');
				$validat->required('username');
				$validat->required('password');

				if( !$validat->isValid() ){
					$message_error = true;
				}else{			
					set_setting( 'sitename' , $_POST['sitename'] );
					set_setting( 'description' , $_POST['sitedescription'] );
					set_setting( 'admin_email' , $_POST['email'] );

					$userdatas['name'] = $_POST['username'];
					$userdatas['pass'] = $_POST['password'];
					$userdatas['email'] = $_POST['email'];
					update_user( $userdatas , 1 );
					$this->redirect( get_clean_url() . '?step=end' );
				}

			}
		}

		?>
		<!doctype html>
		<html lang="fr-FR">
			<head>
				<meta charset="utf-8">
				<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
				<title>
					ARpt - Installation
				</title>
				<link href="<?php echo get_admin_css('bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
				<link href="<?php echo get_admin_css('bootstrap/signin.css') ?>" rel="stylesheet">
			</head>	
			<body>

			<div class="container">
			<div class="jumbotron">
		<?php


		if( $_GET['step'] == 'welcome' ) :
			echo '<h1>Welcome</h1>';
			echo '<p>Bienvenue sur la page d\'installation de ARpt. Rassurez-vous, ça ne prendra que quelques secondes. </p>';
			echo '<button class="btn btn-primary btn-lg" onCLick="window.location.href=\'?step=config\'">C\'est parti !</button>';
		elseif( $_GET['step'] == 'config' ) :
			$link->query("CREATE TABLE IF NOT EXISTS `arpt_categories` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(50) NOT NULL,`content_type` varchar(50) NOT NULL,`description` text NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
			$link->query("CREATE TABLE `arpt_contents` ( `id` int(11) NOT NULL AUTO_INCREMENT, `parent_id` int(11) NOT NULL, `user_id` int(11) NOT NULL DEFAULT '1', `content_model` varchar(50) NOT NULL, `content_type` varchar(20) NOT NULL, `content_title` varchar(70) CHARACTER SET utf8 NOT NULL, `content_slug` varchar(70) CHARACTER SET utf8 NOT NULL, `content_status` varchar(20) NOT NULL, `content_content` text NOT NULL,`content_date` datetime NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
			$link->query("CREATE TABLE IF NOT EXISTS `arpt_contents_categories` (`content_id` int(11) NOT NULL,`cat_id` int(11) NOT NULL,PRIMARY KEY (`content_id`,`cat_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$link->query("CREATE TABLE IF NOT EXISTS `arpt_contents_properties` ( `id` int(11) NOT NULL AUTO_INCREMENT, `parent_id` int(11) NOT NULL, `label` varchar(70) NOT NULL, `value` text NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
			$link->query("CREATE TABLE IF NOT EXISTS `arpt_general` ( `id` int(11) NOT NULL AUTO_INCREMENT, `setting` varchar(50) NOT NULL, `value` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
			$link->query("CREATE TABLE IF NOT EXISTS `arpt_keywords` ( `id` int(11) NOT NULL AUTO_INCREMENT, `content_id` int(11) NOT NULL, `name` varchar(30) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
			$link->query("CREATE TABLE IF NOT EXISTS `arpt_options` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `value` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
			$link->query("CREATE TABLE `arpt_users` (`id` int(11) NOT NULL AUTO_INCREMENT,`email` varchar(50) NOT NULL,`username` varchar(30) NOT NULL,`slug` varchar(30) NOT NULL,`pass` varchar(70) NOT NULL,`date_registered` datetime NOT NULL,`status` int(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");

			echo '<h1>Configuration</h1>';
			if( $message_error ){
				echo '<p class="text-danger">Tous les champs doivent être renseignés correctement.</p>';
			}
			$f_sitename = get_setting( 'sitename' ) ? get_setting( 'sitename' ) : last_value( 'sitename' );
			$f_description = get_setting( 'description' ) ? get_setting( 'description' ) : last_value( 'sitedescription' );
			$f_email = get_setting( 'admin_email' ) ? get_setting( 'admin_email' ) : last_value( 'email' );

			form_open();
			fieldset_open('Infos générales de votre site');

			form_hidden( array( 'name' => 'step_config' ) );

			div( array( 'class' => 'form-group' ) );
			form_input( array( 'class' => 'form-control' , 'name' => 'sitename' , 'value' => $f_sitename ) , 'Nom de votre site' );
			div_close();

			div( array( 'class' => 'form-group' ) );
			form_input( array( 'class' => 'form-control' ,  'name' => 'sitedescription' , 'value' => $f_description ) , 'Description de votre site' );
			div_close();

			div( array( 'class' => 'form-group' ) );
			form_input( array( 'class' => 'form-control' ,  'name' => 'email' , 'value' => $f_email ) , 'Adresse email du Webmaster' );
			div_close();

			fieldset_close();

			fieldset_open('Compte administrateur');

			div( array( 'class' => 'form-group' ) );
			form_input( array( 'class' => 'form-control' , 'name' => 'username' , 'value' => last_value('username') ) , 'Nom de compte' );
			div_close();
			

			div( array( 'class' => 'form-group' ) );
			form_password( array( 'class' => 'form-control' , 'name' => 'password' ) , 'Mot de passe' );
			div_close();

			fieldset_close();

			form_submit( array( 'value' => 'Etape suivante' , 'class' => 'btn btn-default' ) );

			form_close();
		elseif( $_GET['step'] == 'end' ) :

			set_setting( 'enable_signup' , 1 );
			set_setting( 'current_theme' , 'blog' );

			if( ( $base_url = trimslash( remove_params( $_SERVER['REQUEST_URI'] ) ) ) != '' )
				set_setting( 'base_url' , $base_url );

			set_option( 'contents_per_page' , 6 );
			set_option( 'allow_visitor_to_comment' , 0 );

			echo '<h1>C\'est presque terminé..</h1>';
			echo '<p>Les modifications ont été effectuées.</p>';
			if( false != ( $base_url = get_setting( 'base_url' ) ) ) :
				echo '<p>ARpt a détecté un dossier persistent sur votre adresse. Ajoutez dans votre fichier <b>settings.php</b> la ligne suivante : </p>';
				echo '<code> define( \'BASE_URL\' , \'' . $base_url . '\' ); </code>';
			else :
				echo '<p>Votre site est casiment prêt, il ne vous reste plus qu\'à valider ! :-) </p>';
			endif;
				echo '<p><button class="btn btn-default" onClick="window.location.href=\'?step=config\'">Revenir</button>';
			echo '<button class="btn btn-primary" onClick="window.location.href=\'?step=end-2\'">Valider</button></p>';
		elseif( $_GET['step'] == 'end-2' ) :
			if( $user->num_rows ) :
				$link->query("CREATE TABLE IF NOT EXISTS `arpt_users_properties` ( `id` int(11) NOT NULL AUTO_INCREMENT, `parent_id` int(11) NOT NULL, `label` varchar(100) NOT NULL, `value` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");

				add_user_a_role( 1 , 'Administrateur' );		

				$args['parentid'] = 0;
				$args['userid'] = 1;
				$args['title'] = 'Bienvenue sur votre site !';
				$args['modelpage'] = 0;
				$args['message'] = '<p>Ce site a été crée le ' . date('d-m-Y') . ', c\'est une date importante !</p>';
				$cid = insert_new_content( 'article' , $args );
				insert_contentproperty( $cid , 'extrafields_comments_actived' , 1 );	

				$args['parentid'] = 2;
				$args['userid'] = 1;
				$args['title'] = 'Commentaire pour le premier article';
				$args['modelpage'] = 0;
				$args['message'] = '<p>Un commentaire pour le premier article du site !..</p>';
				$cid = insert_new_content( 'commentaire' , $args );
				insert_contentproperty( $cid , 'extrafields_comments_actived' , 0 );

				$args['parentid'] = 0;
				$args['userid'] = 1;
				$args['title'] = 'Ceci est un article';
				$args['modelpage'] = 0;
				$args['message'] = '<p>Hello, </p>';
				$args['message'] .= '<p>A cet endroit se trouve les articles de votre site. Celui-ci a été crée automatiquement en votre nom. </p>';
				$args['message'] .= '<p>Vous pouvez modifier votre site en vous rendant ' . a( get_admin_url() , 'sur cette page' ) . ' :-) </p>';
				$args['message'] .= '<p><b>Bonne visite ! </b></p>';
				$cid = insert_new_content( 'article' , $args );
				insert_contentproperty( $cid , 'extrafields_comments_actived' , 1 );	

				$args['parentid'] = 0;
				$args['userid'] = 1;
				$args['title'] = 'Exemple de page';
				$args['modelpage'] = 0;
				$args['message'] = '<p>Ceci est une page, vous pouvez utiliser les pages pour différentes raisons : expliquer les objectifs de votre site par exemple.</p>';
				$args['message'] .= '<p>Chaque page possède sa propre entité, elles respectent tous un format standard mais il vous est possible de les personnaliser.</p>';
				$args['message'] .= '<p>Prenez le temps de comprendre le fonctionnement du CMS en visitant le site officiel.</p>';
				$args['message'] .= '<p><b>L\'équipe</b></p>';
				$cid = insert_new_content( 'page' , $args );
				insert_contentproperty( $cid , 'extrafields_comments_actived' , 1 );
				add_navmenu( 'header' , $cid );

				$args['parentid'] = 0;
				$args['userid'] = 1;
				$args['title'] = 'A propos';
				$args['modelpage'] = 0;
				$args['message'] = '<p>Voici la deuxième page. Rassurez-vous, toutes les pages de votre site ne sont pas affichés ici :p Cette page appartient au menu de navigation d\'en tête !</p>';
				$args['message'] .= '<p>Pourquoi ne pas parler de vous ici par exemple ?</p>';
				$args['message'] .= '<p>Prenez le temps de comprendre le fonctionnement du CMS en visitant le site.</p>';
				$args['message'] .= '<p><b>L\'équipe</b></p>';
				$cid = insert_new_content( 'page' , $args );
				insert_contentproperty( $cid , 'extrafields_comments_actived' , 1 );
				add_navmenu( 'header' , $cid );

				add_widgetmenu( 'widget_userprofile' );
				add_widgetmenu( 'widget_last_articles' );
				set_option( 'widget_last_articles_articlestodisplay' , 5 );
				add_widgetmenu( 'widget_last_comments' );
				add_widgetmenu( 'widget_navsearchform' );
				set_option( 'widget_last_comments_commentstodisplay' , 5 );
				set_option( 'widget_last_12_months_contents_monthstodisplay' , 12 );
			endif;
			$this->redirect( get_clean_url() );
		endif;
		?>
			</div>
		</div>
		</body>
		</html>
		<?php

		die();
	}

}
