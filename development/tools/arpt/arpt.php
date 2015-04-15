<?php

define('ARPT_TOOLS' , get_tool_dir() . '/arpt' );

require_once( ARPT_TOOLS . '/migration.php' );

add_trigger( 'dev_activation' , 'init_arpt_plugin' );
function init_arpt_plugin(){
	arpt_new_contents();
	arpt_menu(); 
	admin_add_submenu( 'development' , 'arpt-tools' , '<span class="glyphicon glyphicon-pencil"></span> ARpt Tool' , 'arpt_adminpage_tools' , 'manage-settings' );
	//get_prototype_functions();
}

function arpt_menu(){
	create_new_navmenu( 'header' , "Menu de navigation" , 'page' , "Menu de navigation fixed-to-top" );
	create_new_navmenu( 'footer' , "Liens d'informations bas de page" , 'page' , "Est affiché en bas de chaque page" );
}

function arpt_new_contents(){
	add_new_content('documentation');
	add_new_content('fonction');
	add_new_content('fichier');

	add_new_content('question');

	add_field_content( 'fonction' , array( 'type' => 'textarea' , 'id' => 'prototype' , 'name' => 'prototype' , 'label' => 'Usage et paramètres' ) );
	add_field_content( 'fonction' , array( 'type' => 'textarea' , 'id' => 'return' , 'name' => 'return' , 'label' => 'Valeurs de retour' ) );
	add_field_content( 'fonction' , array( 'type' => 'textarea' , 'id' => 'example' , 'name' => 'example' , 'label' => 'Exemples' ) );
	

	add_field_content( 'documentation' , array( 'type' => 'textarea' , 'id' => 'usecase' , 'name' => 'example' , 'label' => 'Cas d\'utilisation' ) );
	add_field_content( 'documentation' , array( 'type' => 'textarea' , 'id' => 'devinfo' ,'name' => 'infos_development' , 'label' => 'Informatons de développement' ) );
	add_field_content( 'documentation' , array( 'type' => 'textarea' , 'id' => 'moreinfo' ,'name' => 'more_infos' , 'label' => 'Plus d\'infos' ) );

	add_field_content( 'fichier' , array( 'type' => 'textarea' , 'id' => 'tracker' ,'name' => 'tracker_file' , 'label' => 'Notes relatives au fichier' ) );
}

function arpt_adminpage_tools(){ ?>
	<a href="<?php echo get_current_url('?update_function_documentation=1'); ?>">Mettre à jour la documentation des fonctions</a>.
<?php
}