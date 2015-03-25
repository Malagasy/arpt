<?php

class Siteconfiguration{
	private function Siteconfiguration(){
		$this->create_contents();
		$this->activate_widgets();
		$this->manage_roles();
		$this->backend_mecanism();
		$this->mixed_mecanism();
		$this->frontend_mecanism();
		$this->security_mecanism();
		$this->customize_contents();
		$this->critical_error();
		$this->default_setting();
	
	}
	public static function getInstance(){
		global $siteconfig;
		if( is_null($siteconfig) )
			return new Siteconfiguration();
		return $siteconfig;
	}

	private function create_contents(){
		add_new_content( 'article' );
		add_new_content( 'page' );	
		add_new_content( 'commentaire' );
	}
	
	private function customize_contents(){
		add_field_content( 'article' , array( 'type' => 'radio' , 'name' => 'comments_actived' , 'value' => '1' , 'label' => 'Activer les commentaires' , 'extras' => 'checked' ) );		
		add_field_content( 'article' , array( 'type' => 'radio' , 'name' => 'comments_actived' , 'value' => '0' , 'label' => 'Désactiver' ) );			

		add_field_content( 'page' , array( 'type' => 'radio' , 'name' => 'comments_actived' , 'value' => '1' , 'label' => 'Activer les commentaires' , 'extras' => 'checked' ) );		
		add_field_content( 'page' , array( 'type' => 'radio' , 'name' => 'comments_actived' , 'value' => '0' , 'label' => 'Désactiver' ) );		

		add_field_user( array( 'type' => 'select' , 'name' => 'user_role' , 'label' => 'Rôle' , 'options' => array( 0 => 'Pas de rôle' ) + parseArray_get_roles() ) );
	}

	private function activate_widgets(){
		add_dynamic_widget( 'Archive des articles' , 'widget_last_12_months_contents' );
		add_dynamic_widget( 'Articles récents' , 'widget_last_articles' );
		add_dynamic_widget( 'Formulaire de connexion' , 'widget_userprofile' );
		add_dynamic_widget( 'Commentaires récents' , 'widget_last_comments' );
		add_dynamic_widget( 'Blog Search' , 'widget_navsearchform' );
		add_dynamic_widget( 'Bloc HTML' , 'widget_bloc_html' );
	}

	private function manage_roles(){
		create_new_role( 'Administrateur' );
		add_access_to_role( 'Administrateur' , 'view-backend' );
		add_access_to_role( 'Administrateur' , 'manage-settings' );
		add_access_to_role( 'Administrateur' , 'manage-contents' );
		add_access_to_role( 'Administrateur' , 'manage-options' );
		add_access_to_role( 'Administrateur' , 'manage-modules' );
		add_access_to_role( 'Administrateur' , 'manage-users' );

		create_new_role( 'Modérateur' );
		add_access_to_role( 'Modérateur' , 'view-backend' );
		add_access_to_role( 'Modérateur' , 'manage-contents' );
		add_access_to_role( 'Modérateur' , 'manage-options' );

	}

	private function backend_mecanism(){
		if( !is_adminpage() && !is_signuppage() ) return;

		add_restriction( 'admin' , 'view-backend' );
		if( !enabled_signup() ) add_restriction( 'signup/new' , 'view-backend' );
	
		admin_add_menu( 'general-list' , 0 );
		admin_add_menu( 'content' , 1 );
		admin_add_menu( 'management-tools' , 2 );
		admin_add_menu( 'arpt-modules' , 4 );
		admin_add_menu( 'development' , 3 );

		add_trigger( 'admin_before_loading_script' , 'load_js_script' );
		add_trigger( 'admin_before_loading_script' , 'load_css_script' );

		add_trigger( 'before_routing' , 'pre_signup_user' );
		add_trigger( 'before_routing' , 'pre_edit_general_settings' );
		add_trigger( 'before_routing' , 'pre_add_navmenu' );
		add_trigger( 'before_routing' , 'pre_remove_navmenu' );
		add_trigger( 'before_routing' , 'pre_add_widgetmenu' );
		add_trigger( 'before_routing' , 'pre_edit_widgetoption' );
		add_trigger( 'before_routing' , 'pre_remove_widgetmenu' );
		add_trigger( 'before_routing' , 'pre_edit_content' );
		add_trigger( 'before_routing' , 'pre_edit_contentmangement' );
		add_trigger( 'before_routing' , 'pre_add_new_category' );
		add_trigger( 'before_routing' , 'pre_delete_category' );
		add_trigger( 'before_routing' , 'pre_delete_content' );
		add_trigger( 'before_routing' , 'pre_delete_user' );
		add_trigger( 'before_routing' , 'pre_add_user' );
		add_trigger( 'before_routing' , 'pre_edit_user' );
		add_trigger( 'before_routing' , 'pre_edit_category' );
		add_trigger( 'before_routing' , 'pre_add_tools' );
		add_trigger( 'before_routing' , 'add_upload_directory' );
		add_trigger( 'before_routing', 'rm_upload_directory' );
		add_trigger( 'before_routing' , 'upload_files' );

		add_trigger( 'inserted_content' , 'default_contents_properties' );

		add_trigger( 'account_info_edit_user' , 'display_extra_fields_user' );

		add_format( 'miniature-small' , 200 , 200 );
		add_format( 'miniature-large' , 500 , 500 );

		make_tinymceable( 'add-content' );
		make_tinymceable( 'edit-content' );

	}

	private function mixed_mecanism(){
		//add_trigger( 'before_routing' , 'clean_tokens' );
		add_trigger( 'before_routing' , 'pre_insert_new_content' ); // since comments are considered as public content
		add_trigger( 'inserted_commentaire' , 'update_commentscount' );
		add_trigger( 'before_routing' , 'pre_connecting_user' );

	}
	private function frontend_mecanism(){
		if( is_systempage() ) return;

		add_trigger( 'meta_data' , 'meta_configuration' );

		add_trigger( 'before_routing' , 'search_redirect' );
		add_trigger( 'before_routing' , 'pre_delete_comment_frontend' );

		add_trigger( 'after_routing' , 'check_log_for_user' );
	}

	private function security_mecanism(){
		add_trigger( 'dev_activation' , 'sanitize_POSTvar' );
	}

	private function critical_error(){
		add_trigger( 'critical_error_admin' , 'redirect_nologged_user' );
	}
	
	private function default_setting(){
		if( !file_exists(get_theme_dir()) ) set_setting( 'current_theme' , 'blog' );
	}


}

