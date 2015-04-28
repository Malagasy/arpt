<?php


function admin_menu_management(){ 
	if( !currentusercan( 'manage-options') ) return;
	?>
	<ul class="management-list">
		<li><?php echo a( get_admin_url() . '/category/' , 'Gestion des catégories' ); ?></li>
		<li><?php echo a( get_admin_url() . '/contents/' , 'Gestion des contenus' ); ?></li>
		<li><?php echo a( get_admin_url() . '/navmenu/' , 'Gestion des menus personnalisés' ); ?></li>
		<li><?php echo a( get_admin_url() . '/widgetmenu/' , 'Gestion du menu latéral' ); ?></li>
	</ul>

<?php
}

$arpt_adminmenus = new Chain();
function admin_add_menu( $slug , $order ){
	global $arpt_adminmenus, $arpt_adminmenus_slug;

/*	if( !is_object( $arpt_adminmenus ) ) $arpt_adminmenus = new Chain();

	if( !is_a( $arpt_adminmenus , 'Chain' ) ) $arpt_adminmenus = new Chain();*/

	$arpt_adminmenus->add( $slug , $order );
}

function admin_add_submenu( $parent_slug , $slug , $name , $function , $access , $display_to_menu = true ){
	global $arpt_adminmenus, $arpt_adminmenus_slug;
	if( !$arpt_adminmenus->check( $parent_slug ) ) return false;

	$arpt_adminmenus_slug[$parent_slug][$slug] = array( 'slug' => $slug, 'name' => $name , 'function' => $function , 'access' => $access , 'display_to_menu' => $display_to_menu );
}

function admin_get_menus(){
	global $arpt_adminmenus, $arpt_adminmenus_slug;

	$currenturl = trimslash( get_current_url() );

	foreach( $arpt_adminmenus->get() as $menu ) :
		if( isset( $arpt_adminmenus_slug[$menu] ) ) :
			$i = 1;
			$displayed_ul_html = false;
			foreach( $arpt_adminmenus_slug[$menu] as $submenu ) :
				if( $submenu['display_to_menu'] == false ) continue;

				if( currentusercan( $submenu['access'] ) && !is_null( $submenu['name'] ) ) :
					if( $i == 1 ) :
						echo '<ul class="nav nav-sidebar ' . str_replace( '/' , '-' , $submenu['slug'] ) . '">';
						$displayed_ul_html = true;
					endif;
					if( is_array( $filtering_submenu = $submenu['name'] ) )
						$class = isset( $filtering_submenu[1] ) ? $filtering_submenu[1] : '';
					else
						$class = '';

					$submenu_url =  trimslash( get_admin_url( $submenu['slug'] ) );

					$active = $submenu_url == $currenturl ? 'active' : '';

					$the_link = call_layers( 'admin_menu_link_layer' , a( $submenu_url . '/' , get_realname_submenu( $submenu['name'] ) ) , $submenu_url , get_realname_submenu( $submenu['name'] ) );

					echo '<li class="elem-' . $i . ' ' . $class . ' ' . $active . '">' . $the_link . '</li>';

					$i++;
				endif;
			endforeach;
			if( $displayed_ul_html )
				echo '</ul>';
		endif;
	endforeach;
}

function get_realname_submenu( $param ){
	if( is_array( $filtering_submenu = $param ) ) :
		if( isset( $filtering_submenu[0] ) )
			return $filtering_submenu[0];
		else 
			return '';
	endif;

	return $param;
}