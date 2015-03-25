<?php

function get_files_inside( $folder ){
	$files = scandir( $folder );

	if( !Arpt::is_ajaxcall() ) return $files;

	foreach( $files as $file ){
		if( is_dir( $file ) )
			echo '<a href="#" data-type="folder" class="list-group-item">' . $file . '</a>';
		else
			echo '<a href="#" data-type="file" class="list-group-item">' . $file . '</a>';

	}
}

