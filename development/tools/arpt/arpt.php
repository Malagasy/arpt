<?php

add_trigger( 'dev_activation' , 'init_arpt_plugin' );
function init_arpt_plugin(){
	arpt_new_contents();
	arpt_menu(); 
}

function arpt_menu(){
	create_new_navmenu( 'header' , "Menu de navigation" , 'page' , "Menu de navigation fixed-to-top" );
	create_new_navmenu( 'footer' , "Liens d'informations bas de page" , 'page' , "Est affiché en bas de chaque page" );
}


function arpt_new_contents(){
	add_new_content('documentation');
	add_new_content('fonction');
	add_new_content('fichier');

	add_field_content( 'fonction' , array( 'type' => 'textarea' , 'id' => 'prototype' , 'name' => 'prototype' , 'label' => 'Usage et paramètres' ) );
	add_field_content( 'fonction' , array( 'type' => 'textarea' , 'id' => 'return' , 'name' => 'return' , 'label' => 'Valeurs de retour' ) );
	add_field_content( 'fonction' , array( 'type' => 'textarea' , 'id' => 'example' , 'name' => 'example' , 'label' => 'Exemples' ) );
	

	add_field_content( 'documentation' , array( 'type' => 'textarea' , 'id' => 'usecase' , 'name' => 'example' , 'label' => 'Cas d\'utilisation' ) );
	add_field_content( 'documentation' , array( 'type' => 'textarea' , 'id' => 'devinfo' ,'name' => 'infos_development' , 'label' => 'Informatons de développement' ) );
	add_field_content( 'documentation' , array( 'type' => 'textarea' , 'id' => 'moreinfo' ,'name' => 'more_infos' , 'label' => 'Plus d\'infos' ) );

	add_field_content( 'fichier' , array( 'type' => 'textarea' , 'id' => 'tracker' ,'name' => 'tracker_file' , 'label' => 'Notes relatives au fichier' ) );
}