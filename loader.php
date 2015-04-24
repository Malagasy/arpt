<?php

$files_system = array();

$files_system[] = 'settings.php';

$files_system[] = 'sys/arpt.php';
$files_system[] = 'sys/queries.php';
$files_system[] = 'sys/contents.php';
$files_system[] = 'sys/users.php';
$files_system[] = 'sys/chain.php';
$files_system[] = 'sys/pageinfo.php';
$files_system[] = 'sys/config.php';
$files_system[] = 'sys/siteconfig.php';
$files_system[] = 'sys/errors.php';
$files_system[] = 'sys/form_validation.php';
$files_system[] = 'sys/properties.php';
$files_system[] = 'sys/categories.php';
$files_system[] = 'sys/access.php';

$files_system[] = 'sys/functions/callbacks.php';
$files_system[] = 'sys/functions/queries.php';
$files_system[] = 'sys/functions/urls.php';
$files_system[] = 'sys/functions/strings.php';
$files_system[] = 'sys/functions/contents.php';
$files_system[] = 'sys/functions/form.php';
$files_system[] = 'sys/functions/queried.php';
$files_system[] = 'sys/functions/database.php';
$files_system[] = 'sys/functions/pageinfo.php';
$files_system[] = 'sys/functions/page.php';
$files_system[] = 'sys/functions/users.php';
$files_system[] = 'sys/functions/debug.php';
$files_system[] = 'sys/functions/admin.php';
$files_system[] = 'sys/functions/system.php';
$files_system[] = 'sys/functions/contents-keyword.php';
$files_system[] = 'sys/functions/security.php';
$files_system[] = 'sys/functions/widgets.php';
$files_system[] = 'sys/functions/comments.php';
$files_system[] = 'sys/functions/tokens.php';
$files_system[] = 'sys/functions/medias.php';
$files_system[] = 'sys/functions/scripts.php';
$files_system[] = 'sys/functions/categories.php';
$files_system[] = 'sys/functions/routing.php';
$files_system[] = 'sys/functions/server.php';
$files_system[] = 'sys/functions/ajax.php';


$files_system[] = 'sys/tools/form.php';
$files_system[] = 'sys/tools/widgets.php';
$files_system[] = 'sys/tools/admin_menus.php';
$files_system[] = 'sys/tools/extrafields.php';
$files_system[] = 'sys/tools/users-properties.php';
$files_system[] = 'sys/tools/contents-properties.php';
$files_system[] = 'sys/tools/contents.php';
$files_system[] = 'sys/tools/options.php';
$files_system[] = 'sys/tools/navmenus.php';
$files_system[] = 'sys/tools/users.php';
$files_system[] = 'sys/tools/categories.php';
$files_system[] = 'sys/tools/tinymce.php';
$files_system[] = 'sys/tools/admin.php';
$files_system[] = 'sys/tools/editor.php';
$files_system[] = 'sys/tools/email.php';
$files_system[] = 'sys/tools/internal.php';


foreach( $files_system as $file_system ){
	if( !file_exists( $file_system ) ){
		if( $file_system == 'settings.php' )
			die( 'Le fichier <strong>settings.php</strong> n\'existe pas. Lisez le manuel d\'installation.' );
		die('Le fichier systeme <strong>' . $file_system . '</strong> n\'existe pas.' );
	}

	require_once $file_system;
}