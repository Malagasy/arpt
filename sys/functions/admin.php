<?php

/*

	This file concentrates all functions associated to the Admin Panel. It means that they are doing their work in the backside and you should never have to call them.

*/
function admin_routing(){
	global $arpt_adminmenus_slug;
	$currenturl = trimslash( get_clean_url() );

	$admimenus_slug = array_values( $arpt_adminmenus_slug );

	foreach( $admimenus_slug as $slug ) :
		foreach( $slug as $subslug ) :
			if( strpos( $currenturl , trimslash( get_admin_url( $subslug['slug'] ) ) ) !== false ) :
				$datas = $subslug;
				break 2;
			endif;
		endforeach;
	endforeach; 

	if( isset( $datas ) ) :
		if( currentusercan( $datas['access'] ) ) :
			echo '<div class="admin-' . strtok( $datas['slug'] , '/' ) . '" data-route="'.$datas['slug'].'">';
			echo '<h1 class="page-header">' . get_realname_submenu( $datas['name'] ) . '</h1>';
			call_user_func( $datas['function'] );
			echo '</div>';
		else :
			call_user_func( 'adminpage_overview' );
		endif;
	else :
		call_user_func( 'adminpage_overview' );
	endif;
}

function admin_header(){  	
	?>
<!doctype html>
<html lang="fr-FR">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

		<?php admin_head(); ?>
		<title><?php echo sitetitle(); ?></title>
	</head>	
	<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><?php echo sitename(); ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo get_site_url(); ?>">Mon site</a></li>
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" id="profile" aria-expanded="false"><?php echo get_currentusername(); ?> <span class="caret"></span></a>
				<ul class="dropdown-menu" aria-labelledby="profile">
					<li><a href="<?php echo get_edit_user_url(); ?>">Editer mon compte</a></li>
					<li><a href="<?php echo get_logout_url(); ?>">Me déconnecter</a></li>
				</ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
		<div class="container-fluid">
			<div class="row"><?php
}

function admin_footer(){ ?>
		<nav class="navbar navbar-default navbar-fixed-bottom">
			<div class="container">
				<p class="navbar-text navbar-right">ARpt version 1.0</p>
			</div>
		</nav>
		<?php call_triggers('admin_footer_script_trigger'); ?>
		<?php call_triggers('admin_footer_trigger'); ?>
	</body>
</html>
<?php
}


function admin_head(){
	if( get_base_var() )
		echo '<base href="/' . get_base_var() .'/">';
	else
		echo '<base href="/">';
	
	call_triggers( 'admin_before_loading_script' );

	call_triggers('css_script');
		
	call_triggers('js_script');
}

function load_css_script(){
	add_css_script( get_admin_css( 'bootstrap/bootstrap.min.css' ) );
	add_css_script( get_admin_css( 'bootstrap/dashboard.css' ) );
	add_css_script( get_admin_css( 'admin.css' ) );
}

function load_js_script(){
	add_js_script( '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js' );
	add_js_script( get_admin_js( 'tinymce/js/tinymce/tinymce.min.js' ) );
	add_js_script( get_admin_js( 'jquery-ui-1.11.3/jquery-ui.min.js' ) );
	add_js_script( get_admin_js( 'bootstrap/bootstrap.min.js' ) );
	add_js_script( get_admin_js( 'admin.js' ) );
}

function admin_message(){
	if( is_success() ) echo '<p class="admin-message alert alert-success">Les modifications ont bien été apportées.</p>';
	else echo '<p class="admin-message alert alert-success" style="display:none;">Les modifications ont bien été apportées.</p>';
	
	if( is_failure() ) echo '<p class="admin-message alert alert-danger" display="none">Une erreur s\'est produite.</p>';
	else echo '<p class="admin-message alert alert-danger" style="display:none;">Une erreur s\'est produite.</p>';

}


function edit_general_settings(){
	$settings = call_layers( 'edit_general_settings_layer' , $_POST );

	set_setting( 'sitename' , $settings['sitename'] );
	set_setting( 'slogan' , $settings['slogan'] );
	set_setting( 'description' , $settings['description'] );
	set_setting( 'admin_email' , $settings['admin_email'] );
	set_setting( 'enable_signup' , $settings['enable_signup'] );
	set_setting( 'current_theme' , $settings['sitetheme'] );

	return true;
}

function update_content( $cid , $datas ){

	$default = array(
		'parentid' => null,
		'userid' => get_currentuserid(),
		'title' => null,
		'modelpage' => null,
		'status' => 'public',
		'message' => null,
		'category' => null,
		'keywords' => null
		);

	$datas = array_merge( $default , $datas );
	 
	call_triggers('before_edit_content_'.$datas['content_type'] , $datas );
	$arpt_contents_form['id'] = $cid;
	$arpt_contents_form['parentid'] = $datas['parentid'];
	$arpt_contents_form['content_model'] = $datas['modelpage'];
	$arpt_contents_form['status'] = $datas['status'];
	if( diffstr( $datas['slug'] , get_contentslug( $cid ) ) ) $arpt_contents_form['slug'] = db_uniq_slug( 'arpt_contents' , 'content_slug' , $datas['slug']  );
	$arpt_contents_form['title'] = $datas['title'];
	$arpt_contents_form['message'] = $datas['message'];

	$datas['extrafields_last_edit'] =  date("Y-m-d H:i:s");

	$arpt_contents_form = call_layers( 'before_edit_content_layer_' . $datas['content_type'] , $arpt_contents_form );
	
	new_content( $arpt_contents_form , 'update' );

	call_triggers( 'updated_'.$datas['content_type'] , $cid , $datas['parentid'] );

	update_content_category( $cid , $datas['category'] );
	
	update_contentkeywords( $cid , $datas['keywords'] );

	$datas_2 = filter_extrafields( $datas );
	
	update_contentsproperties( $datas_2 , $cid );

	if( isset( $datas['delete_miniature'] ) )
		rm_miniature( $cid );
	
	if( isset( $_FILES['miniature'] ) && !empty( $_FILES['miniature']['name'] ) ) 
		if( !upload_miniature( $cid , $_FILES['miniature'] ) ) return false; // todo Upload class...

	return true;
}

function insert_new_content( $content_type , $datas ){

	if( !is_active_content( $content_type ) ) return false;

	$default = array(
		'parentid' => null,
		'userid' => get_currentuserid(),
		'title' => null,
		'modelpage' => null,
		'status' => 'public',
		'message' => null,
		'category' => null,
		'keywords' => null
		);

	$datas = array_merge( $default , $datas );
	 
	call_triggers( 'before_insert_new_content' , $datas );
	call_triggers( 'before_insert_new_content_' . $content_type , $datas );
	
	$arpt_contents_form['parent_id'] = $datas['parentid'];
	$arpt_contents_form['user_id'] = $datas['userid'];
	$arpt_contents_form['content_type'] = $content_type;
	$arpt_contents_form['content_title'] = $datas['title'];
	$arpt_contents_form['content_model'] = $datas['modelpage'];
	$arpt_contents_form['content_status'] = $datas['status'];
	$arpt_contents_form['content_slug'] = db_uniq_slug( 'arpt_contents' , 'content_slug' , $datas['title']  );
	$arpt_contents_form['content_content'] = $datas['message'];
	$arpt_contents_form['content_date'] = date("Y-m-d H:i:s");

	if( $arpt_contents_form['content_title'] == null ) return false;

	$arpt_contents_form = call_layers( 'before_insert_new_content_layer' , $arpt_contents_form );
	
	$id = new_content( $arpt_contents_form , 'insert' );

	call_triggers( 'inserted_' . $content_type , $id->get() , $datas['parentid'] );
	call_triggers( 'inserted_content' , $id->get() );

	update_content_category( $id->get() , $datas['category'] );
	
	update_contentkeywords( $id->get() , $datas['keywords'] );

	$datas_2 = filter_extrafields( $datas );
	
	insert_contentsproperties( $datas_2 , $id->get() );

	if( isset( $_FILES['miniature'] ) && !empty( $_FILES['miniature']['name'] )  ) 
		upload_miniature( $cid , $_FILES['miniature'] ); // todo Upload class...

	call_triggers( 'after_insert_new_content' );
	call_triggers( 'after_insert_new_content_' . $content_type );

	return $id->get();
}

function update_contentkeywords( $id , $keywords ){
	if( empty( $keywords ) ) return false;

	new_query( 'delete' , 'arpt_keywords' , array( 'where' => 'content_id=\'' . $id . '\'' ) );
	
	$args = array_unique( explode( ',' , $keywords ) );
	$r['content_id'] = $id;
	
	foreach( $args as $key ) :
		$r['name'] = do_slug( $key );
		new_query( 'insert' , 'arpt_keywords' , $r );
	endforeach;
	
	return true;

}

function get_available_navmenu( $type , $slug = null ){
	if( $type == 'category' ) :
		$cats = get_categories();

		if( !$cats->has() ) return array();

		while( $cats->next() )
			$options[] = $cats->qid();

		$navs = get_navmenu_links( $slug );
		
		$ids = array_diff( $options , $navs );

		if( empty($ids ) ) return array();

		foreach( $ids as $id )
			$return[ $id ] = get_category_by_id( $id , 'name' )  . ' ( type: ' . get_category_by_id( $id , 'content_type' ) . ' )';

		return $return;

	endif;
	//logr($slug);
	//logr($type);
	$r = get_contents( array( 'type' => $type , 'selection' => 'id' , 'orderby' => 'content_title' , 'ob_suffix' => ' ASC ') );
	
	if( $r->is_null() ) return array();

	foreach( $r->getAll() as $value )
		$options[] = $value['id'];
		
	$navs = get_navmenu_links( $slug );
	
	$ids = array_diff( $options , $navs );
	
	if( empty( $ids ) ) return array();
	
	foreach( $ids as $id )
		$v[$id] = get_contentname( $id );
		
	return $v;

}

function add_navmenu( $slug , $cid ){
	
	$v = get_navmenu_links( $slug );
	
	if( in_array( $cid , $v ) ) return false;
//	logr( $v );
	if( empty( $v ) ) $v[0] = $cid;
	else $v[] = $cid;

//	logr($v); 
	
	set_option( 'navmenu_'.$slug , serialize( $v ) );
	return true;
}

function remove_navmenu( $slug , $cid ){
	
	$v = get_navmenu_links( $slug );
	$key = array_search( $cid , $v );
	
	unset( $v[$key] );
	set_option( 'navmenu_'.$slug , serialize( $v ) );
	return true;
}

function add_widgetmenu( $widget ){
	$v = get_widgetmenu();
	if( in_array( $widget , $v ) ) return false;
	
	if( empty( $v ) ) $v[0] = $widget;
	else $v[] = $widget;
	
	set_option( 'widgetmenu' , serialize( $v ) );
	return true;
}

function remove_widgetmenu( $widget ){
	$v = get_widgetmenu();
	$key = array_search( $widget , $v );
	
	unset( $v[$key] );
	return set_option( 'widgetmenu' , serialize( $v ) );
}

function add_options_array( $datas ){
	foreach( $datas as $k => $v )
		if( $v !== '' )
			if( substr( $k , 0 , 4) == 'opt_' )
				set_option( substr( $k , 4 ) , $v );
}

function insert_new_category( $name , $type , $description ){

	$form['name'] = ucfirst( db_uniq_slug( 'arpt_categories' , 'name' , $name ) );
	$form['content_type'] = $type;
	$form['description'] = $description;

	$obj = new_query( 'insert' , 'arpt_categories' , $form );

	return $obj->get();
}
function edit_category( $catid , $catname , $catdescr = null ){
	$query_catname = get_category_by_id( $catid, 'name' );
	if( strtolower( $query_catname ) != strtolower( $catname ) ) :
		$form['name'] =  ucfirst( db_uniq_slug( 'arpt_categories' , 'name' , $catname ) );
	endif;
	$form['id'] = $catid;
	$form['description'] = $catdescr;

	new Categories( $form , 'update' );
	return true;
}

function get_delete_url( $type , $id , $token = null , $output = '<span class="glyphicon glyphicon-trash"></span>' , $css = array( 'class' => 'label label-danger confirmbox' ) ){
	if( is_null( $token ) )
		$token = create_token( 'delete_' . $type . '_' . $id );
		
	return a( get_clean_url( '?delete=' . $id . '&token=' . $token . '&type=' . $type ) , $output , (array)$css + array( 'title' => 'Supprimer' ) );
}
 
function get_edit_content_url( $cid = null){
	if( $cid === null ) $cid = get_currentcontentid();
	$contents = new_content( $cid );
	if( !$contents->has() ) return false;
	$contents->next();
	return get_admin_url('edit-content/' . $contents->qtype() . '/' . $contents->qslug());
}

function get_edit_user_url( $uid = null ){
	if( is_null( $uid ) ) $uid = get_currentuserid();
	$user = new_user( $uid );
	if( !$user->has() ) return false;
	$user->next();
	return get_admin_url('/users/' . $user->qid() );
}

function display_extra_fields_user( $user ){
		$r = get_extrafields_user();
		if( !$r ) return;
	
		foreach( $r as $field )
			decode_extrafields_user( $field , $user->qid() );
		/*$roles = array();
		foreach( get_arpt_roles() as $role => $access )
			$roles[$role] = $role;
		div( array( 'class' => 'form-group' ) );
		form_select( array( 'class' => 'form-control' , 'name' => 'userrole' ) , 'Rôle' , $roles , $user->qrole() );
		div_close();*/
}

function edit_user( $datas ){
	$default = array(
		'email' => null,
		'username' => null,
		'dateregistered' => null );

	$datas = array_merge( $default , $datas );

	call_triggers( 'before_edit_user' , $datas );

	$userid = $datas['userid'];

	$arpt_user_form['id'] = $datas['userid'];
	$arpt_user_form['email'] = $datas['email'];
	$arpt_user_form['name'] = $datas['username'];
	$arpt_user_form['date_registered'] = $datas['dateregistered'];

	if( $datas['password'] != '' ) $arpt_user_form['pass'] = $datas['password'];



	$arpt_user_form = call_layers( 'before_edit_user_layer' , $arpt_user_form );

	new_user( $arpt_user_form , 'update' );

	$datas = filter_extrafields( $datas );

	update_usersproperties( $datas , $userid );

	return true;
}

function reorganise_navmenu( $slug , $cids ){

	$is_ajaxcall = Arpt::is_ajaxcall();

	$cids = json_decode( $cids );

	if( !$cids ){
		if( $is_ajaxcall )
			die("false");
		return false;
	}

	set_option( 'navmenu_'.$slug , serialize( array() ) );

	foreach( $cids as $cid ) add_navmenu( $slug , $cid );

	if( $is_ajaxcall )
		die("true");
	return true;
}

function reorganise_widgetmenu( $widgets_id ){
	
	$is_ajaxcall = Arpt::is_ajaxcall();

	$widgets_id = json_decode( $widgets_id );

	if( !$widgets_id ){
		if( $is_ajaxcall )
			die("false");
		return false;
	}

	set_option( 'widgetmenu' , serialize( array() ) );

	foreach( $widgets_id as $wid ) add_widgetmenu( $wid );

	if( $is_ajaxcall )
		die("true");
	return true;
}