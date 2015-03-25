<?php
/*used word : category*/
function create_new_navmenu( $id , $name , $ctype , $description = null ){
	global $arpt;
	$navmenus = get_navmenus();
	$navmenus[$id] = array( 'id' => $id , 'name' => $name , 'contenttype' => $ctype , 'description' => $description );
	$arpt->set_entity( 'navmenus' , $navmenus );

/*	if( get_option( 'navmenu_'.$id) === false )
		set_option( 'navmenu_'.$id , serialize( array() ) ); */
}

function get_navmenus(){
	global $arpt;
	return (array)$arpt->get_entity('navmenus');
}

function get_navmenu( $id ){
	$navmenu = get_navmenus();
	if( isset( $navmenu[$id] ) )
		return $navmenu[$id];
	return false;
}

function navmenu_exists( $id ){
	if( get_navmenu( $id ) ) return true;
	return false;
}


function get_navmenu_links( $slug ){
	$r = get_option( 'navmenu_'.$slug ); //logr($r);
	if( !$r ) return array();
	
	$t = (array)unserialize( $r );
	if( reset( $t ) == '' ) return array();
	//logr($t);
	return $t;
}
