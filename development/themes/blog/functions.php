<?php

function blog_header_setup(){
	add_css_script( get_theme_dir() . '/css/bootstrap.min.css' );
	add_css_script( get_theme_dir() . '/css/blog-home.css' );
}
add_trigger( 'theme_setup' , 'blog_header_setup' );

function blog_config_setup(){
	create_new_navmenu( 'header' , 'Menu de navigation' , 'page' , 'Menu de navigation d\'en-tête' );
}
add_trigger( 'config_setup' , 'blog_config_setup' );
