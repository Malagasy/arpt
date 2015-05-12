<?php

function pre_signup_user(){
	if( !valid_source( 'signup-user' ) ) return;

	$validator = new FormValidation();
	$validator->required( 'name' , 'username' );
	$validator->required( 'pass' , 'password' );
	
	if( $validator->isValid() ) :
		if( $user = insert_new_user( $_POST ) ) :
			if( sessionning_user( $user ) ) :
				redirect( get_home_url() );
			else :
				redirect_failure();
			endif;
		else :
			redirect_failure();
		endif;
	else :
		redirect_failure();
	endif;

}

function pre_add_user(){
	if( !valid_source( 'add-user' ) ) return;

	$validator = new FormValidation();
	$validator->required( 'name' , 'username' );
	$validator->required( 'pass' , 'password' );
	
	if( $validator->isValid() ) :
		if( insert_new_user( $_POST ) ) :
			redirect_success();
		else :
			redirect_failure();
		endif;
	else :
		redirect_failure();
	endif;

}

function search_redirect(){
	if( filter_input( INPUT_POST , 'search' ) != '' && isset( $_POST['doArequest'] ) ) :
		redirect( get_search_url() . do_slug( $_POST['search'] ) . '/' );
	endif;
}

function pre_edit_general_settings(){
	if( !valid_source( 'edit_general_setting' ) ) return;
	
	$generalValidator = new FormValidation();
	$generalValidator->required('sitename');
	$generalValidator->required('description');
	
	if( $generalValidator->isValid() ){
		if( edit_general_settings() )					
			redirect( get_admin_url('/general/success/') );
	}else{ redirect_failure(); }
	
 }

function pre_add_navmenu(){
	if( !valid_source( 'navmenu-add' ) ) return;
	if( !navmenu_exists( $_POST['navslug'] ) ) return;

	$validator = new FormValidation;
	$validator->notequals( 'id-add' , 0 );

	if( !$validator->isValid() ) redirect( get_admin_url() . '/navmenu/' . $_POST['navslug'] . '/failure/' );

	if( add_navmenu( $_POST['navslug'] , $_POST['id-add'] ) )	
		redirect( get_admin_url('/navmenu/' . $_POST['navslug'] . '/success/') );
	else
		redirect( get_admin_url('/navmenu/' . $_POST['navslug'] . '/failure/') );
}

function pre_remove_navmenu(){
	if( !is_arg('navmenu') ) return;
	/*if( !valid_source( 'navmenu-remove') ) return;

	$validator = new FormValidation;
	$validator->notequals( 'id-remove' , 0 );

	if( !$validator->isValid() ) redirect_failure();*/
	if( !isset($_GET['delete']) || !isset( $_GET['token'] ) ) return;
	if( ( $navslug = get_pageargs(1) ) == '' ) $navslug = key( get_navmenus() );

	if( !navmenu_exists( $navslug ) ) redirect_failure();

	$id = $_GET['delete'];
	$token = $_GET['token'];

	if( !check_token( 'delete_navmenu_' . $id , $token ) ) redirect_failure();

	if( remove_navmenu( $navslug , $id ) )
		redirect_success();
	else
		redirect_failure();
}

function pre_edit_widgetoption(){
	if( !isset( $_POST['edit-widget'] ) ) return;
	if( !widget_exists( $_POST['edit-widget'] ) ) return;

	$widget_title_opt = option( 'widget_title' );

	$datas = $_POST;

	$datas[  $widget_title_opt . '_' . $datas['edit-widget'] ] = $datas[ $widget_title_opt ];
	unset( $datas[$widget_title_opt] );

	add_options_array( $datas );

	redirect_success();
}

function pre_add_widgetmenu(){
	if( isset( $_POST['widgetmenu-add'] ) ) :
		if( $_POST['widget-add'] == '0') return;

		if( add_widgetmenu( $_POST['widget-add'] ) )
			redirect_success();
		else
			redirect_failure();
	endif;
}

function pre_remove_widgetmenu(){
	if( !is_arg( 'widgetmenu' ) ) return;

	if( !isset($_GET['delete']) || !isset( $_GET['token'] ) ) return;

	$id = $_GET['delete'];
	$token = $_GET['token'];

	if( !check_token( 'delete_widgetmenu_' . $id , $token ) ) redirect_failure();

	if( remove_widgetmenu( $id ) )
		redirect_success();
	else
		redirect_failure();
}


function pre_insert_new_content(){
	if( is_systempage() ){
		if( !is_arg( 'add-content' ) ) return;
		if( !isset( $_REQUEST['useOfToken'] ) ) return;
	}else{
		if( !post_submit() ) return;
		$_REQUEST['useOfToken'] = uniqid();
	}


	if(	!valid_source( 'add-content_' . $_REQUEST['useOfToken'] )  ) :
		if( filter_has_var( INPUT_POST , 'content_type' ) ) :
			if( $_REQUEST['content_type'] != 'commentaire') :
				redirect_failure('/?theTokenIsInvalid=1');
			endif;
			$_REQUEST = $_POST;
		else :
			return;
		endif;
	endif;
	$contentValidator = new FormValidation(); 
	$contentValidator->required('title');
	if( !is_systempage() )
		$contentValidator->required('message');
	
	$contentValidator = call_layers( 'pre_inc_' . $_REQUEST['content_type'] , $contentValidator , $_REQUEST );

	if( $contentValidator->isValid() && is_active_content( $_REQUEST['content_type'] ) ) 
		if( $newly_id_inserted = insert_new_content(  $_REQUEST['content_type'] , $_REQUEST  ) )
			redirect( ( isset( $_REQUEST['redirect'] ) ) ? $_REQUEST['redirect'] : get_admin_url('/edit-content/'.$_REQUEST['content_type'].'/'. get_contentslug( $newly_id_inserted ) .'/success/') );
	redirect_failure();
	
}

function pre_edit_content(){
	if( !is_arg( 'edit-content') || !post_submit() ) return;

	if(	!valid_source( 'edit-content_' . get_pageargs(2) )  ) :
		if( filter_has_var( INPUT_POST , 'content_type' ) ) :
			if( $_REQUEST['content_type'] != 'commentaire') :
				redirect_failure('/?theTokenIsInvalid=1');
			endif;
		else :
			redirect_failure();
		endif;
	endif;
	
	$contentValidator = new FormValidation(); 
	$contentValidator->required('title');
	$contentValidator->required('slug');

	
	$contentValidator = call_layers( 'pre_inc_' . $_REQUEST['content_type'] , $contentValidator , $_REQUEST );
	if( $contentValidator->isValid() && is_active_content( $_REQUEST['content_type'] ) ) 
		if( update_content( $_REQUEST['cid'] , $_REQUEST  ) )
			redirect( ( isset( $_REQUEST['redirect'] ) ) ? $_REQUEST['redirect'] : get_admin_url('/edit-content/'. $_REQUEST['content_type'] . '/' . get_contentslug(  $_REQUEST['cid'] ) . '/success/') );
	redirect_failure();
}

function pre_connecting_user(){

	if( !filter_has_var( INPUT_POST , 'login-user' ) ) return;
	
	$validator = new FormValidation();
	$validator->required( 'name' , 'username' );
	$validator->required( 'pass' , 'password');
	
	if( $validator->isValid() ){
		if( $user = authenticate_user( $_POST['name'] , $_POST['pass'] ) ){
			if( sessionning_user( $user ) )
				redirect( get_admin_url());
		}else{
			log_for_user( 'L\'identifiant et le mot de passe ne correspondent pas.' );	
		}
	}else{
			log_for_user( 'Veuillez renseigner tous les champs.' );
	}
}

function pre_edit_contentmangement(){
	if( !valid_source( 'contents-management') ) return;

	add_options_array( $_POST );

	redirect_success();
}
function pre_add_new_category(){
	if( !valid_source( 'add-category') ) 
		return;
	$validator = new FormValidation();
	$validator->required( 'catname' );
	$validator->notequals( 'cattype' , '0' );

	if( $validator->isValid() ) :
		if( insert_new_category( $_POST['catname'] , $_POST['cattype'] , $_POST['catdescr'] ) ) :
			redirect_success();
		else :
			redirect_failure('/?cantInsertCategory=1');
		endif;
	else :
		redirect_failure('/?invalid=1');
	endif;
}

function pre_edit_category(){
	if( !valid_source( 'edit-category') ) 
		return;
	$validator = new FormValidation();
	$validator->required( 'catname' );

	if( $validator->isValid() ) :
		if( edit_category( $_POST['category_id'] , $_POST['catname'] , $_POST['catdescr'] ) ) :
			redirect_success();
		else :
			redirect_failure();
		endif;
	else :
		redirect_failure();
	endif;
}

function sanitize_POSTvar(){
	if(  !post_submit() ) return;
	if( !safe_submit() ) return;
	
	$the_original_postvar = $_POST;
	
	foreach( $_POST as $key => $value ) :
		setcookie( 'postvar_' . $key , $value , time()+10 );
		$_POST[$key] = nl2br( sanitize_str( $value ) );
		$_REQUEST[$key] =  sanitize_str( htmlentities( $value ) );
	endforeach;
}
function pre_delete_category(){
	if( !is_arg( 'category' ) ) return;

	if( isset( $_GET['delete'] ) && isset( $_GET['token'] ) ) :
		$id = $_GET['delete'];
		$token = $_GET['token'];
		if( check_token( 'delete_category_' . $id , $token ) ) :
			if( delete_category( $id ) ) :
				redirect_success();
			else :
				redirect_failure('/?cantDeleteCategory=1');
			endif;
		else :
			redirect_failure('/?invalidToken=1');
		endif;
	endif;
}

function pre_delete_content(){
	if( !is_arg( 'list-contents' ) ) return;

	if( isset( $_GET['delete'] ) && isset( $_GET['token'] ) ) :
		$id = $_GET['delete'];
		$token = $_GET['token'];
		if( check_token( 'delete_content_' . $id , $token ) ) :
			if( delete_content( $id ) ) :
				redirect_success();
			else :
				redirect_failure();
			endif;
		else :
			redirect_failure();
		endif;
	endif;
}

function pre_delete_comment_frontend(){
	if( isset( $_GET['delete'] ) && isset( $_GET['token'] ) && isset( $_GET['type'] ) ) :
		if( !check_token( 'delete_comment_' . $_GET['delete'] , $_GET['token'] ) ) return;

		if( delete_content( $_GET['delete'] ) )
			redirect_success();
		else
			redirect_failure();
	endif;
}

function pre_delete_user(){
	if( !is_arg( 'users' ) ) return;

	if( isset( $_GET['delete'] ) && isset( $_GET['token'] ) ) :
		$id = $_GET['delete'];
		$token = $_GET['token'];
		if( check_token( 'delete_user_' . $id , $token ) ) :
			if( delete_user( $id ) ) :
				redirect_success();
			else :
				redirect_failure();
			endif;
		else :
			redirect_failure();
		endif;
	endif;
}

function pre_edit_user(){
	if( !valid_source( 'edit-user' ) ) return;

	$validator = new FormValidation;

	$validator->required( 'username' );

	if( !$validator->isValid() ) redirect_failure();

	if( edit_user( $_POST ) )
		redirect_success();
	else
		redirect_failure();
}

function pre_add_tools(){
	if( !is_arg( 'tools' ) ) return;

	if( isset( $_GET['action'] ) && isset( $_GET['tool'] ) && isset( $_GET['token'] ) ) :
		$tool = $_GET['tool'];
		$token = $_GET['token'];
		if( $_GET['action'] == 'add' ) :
			if( check_token( 'add_tool_' . $tool , $token ) ) :
				$tools = get_active_tools();
				$tools[] = $tool;
				$tools = array_unique( $tools );
				set_option( 'active_tools' , serialize( $tools ) );
				redirect_success();
			else :
				redirect_failure();
			endif;
		elseif( $_GET['action'] == 'rm' ) :
			if( check_token( 'delete_tool_' . $tool , $token ) ) :
				$tools = get_active_tools();

				$tools = array_unique( delete_entry( $tool , $tools ) );

				set_option( 'active_tools' , serialize( $tools ) );
				redirect_success();
			else :
				redirect_failure();
			endif;

		else :
			redirect_failure();
		endif;
	endif;
}

function add_upload_directory(){
	if( !is_arg('multimedia') ) return;

	if( !isset( $_GET['new_dir'] ) ) return;

	if( isset( $_REQUEST['parent_directory'] ) && isset( $_REQUEST['directoryname'] ) ) :
		$parent_dir = utf8_decode( $_POST['parent_directory'] );
		$directoryname = utf8_decode( $_POST['directoryname'] );

		$the_dir =  get_upload_dir() . $parent_dir . $directoryname;

		if( file_exists( $the_dir ) ) redirect_failure('/?directoryAlreadyExists=1');
		$back = $parent_dir . $directoryname . '/';
		$back = str_replace( '/', '%2F' , $back);
		$back = str_replace( ' ', '+' , $back);

		if( mkdir($the_dir) ) redirect_success('?dir=' . utf8_encode( $back ) );
		else redirect_failure('?mkdirOnDirectoryFailed');


//%2Féhéhéh%2Féhéhéhé+ahaa%2F
	endif;
}

function rm_upload_directory(){
	if( !is_arg('multimedia') ) return;

	if( !isset( $_GET['delete'] ) || !isset( $_GET['token'] ) || !isset( $_GET['type'] ) ) return;

	$dirname = utf8_decode($_GET['delete']);
	$token = $_GET['token'];
	$type = $_GET['type'];

	if( $type != 'directory' ) return;

//%C3%A9h%C3%A9hahah%C3%A9h%C3%A9%2F%C3%A9h%C3%A9h%C3%A9+hdf+h%C3%A9h%C3%A9
//	$back = str_replace( '/', '%2F' , $dirname);
	//$back = str_replace( ' ', '+' , $back);


	if( !check_token( 'delete_directory_' . urlencode( utf8_encode( $dirname ) ) , $token ) ) redirect_failure('?tokenInvalid=1');
	if( delete_upload_dir_recur( $dirname ) ) redirect_success();
	else redirect_failure('?rmdirOnDirectoryFailed');
}

function upload_files(){
	if( !is_arg('multimedia') ) return;

	if( !valid_source( 'upload_files' ) ) return;
/*
l'upload des fichiers devrait etre plus souple.. refaire ce code / en parallèle avec l'upload des miniatures
tout  concentré dans une classe Uploader ?

*/
	$parentdir = utf8_decode( $_POST['parent_directory'] );

	if( !isset( $_FILES ) ) return;
	$files = array();
    $file_count = count($_FILES['the_file']['name']);
    $file_keys = array_keys($_FILES['the_file']);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $files[$i][$key] = $_FILES['the_file'][$key][$i];
        }
    }

    if( upload_medias( $files , $parentdir ) ) redirect_success('/?dir=' . urlencode($parentdir) );
    else redirect_failure('/?uploadMediasFailed=1');


}