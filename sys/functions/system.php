<?php


function get_base_var( $separator = null ){
	global $arpt;
	if( !$arpt->get_base_url() ) return false;
	return $separator . $arpt->get_base_url() . $separator;
}

function get_modelspage(){
	$models_path  = glob_recursive( get_theme_dir() . '/model-*.php' ) ;

	$cut_path_to = strlen( get_theme_dir() );

	$models[] = 'Pas de modèle';

	foreach( $models_path as $path ) :
		$pagename = substr( $path , $cut_path_to );
		$models[ $pagename ] = $pagename;
	endforeach;

	return $models;

}

function get_uploaded( $base = '/' ){
	$output = array();

	$uploads = glob_recursive( get_upload_dir() . $base . '*' );

	if( !$uploads ) return array();
	
	foreach( $uploads as $upload ) :
		if( !is_image( $upload ) ) continue;

		$output[] = $upload;
	endforeach;

	return $output;
}

/*thx to stackoverflow */
function glob_recursive($pattern, $flags = 0){
	$files = glob($pattern, $flags);
	foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
		$files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));

	return $files;
}

function file_extension( $string ){
	return pathinfo( $string , PATHINFO_EXTENSION );
}

function enabled_signup(){
	return get_setting( 'enable_signup' );
}

function get_available_themes(){
	$themes = array_filter( glob( './development/themes/*' , GLOB_ONLYDIR ) );

	$available_themes = array();

	if( !$themes ) array();

	foreach( $themes as $theme ) :
		if( !(array_filter( glob( $theme . '/index.php' ) ) ) && !array_filter( glob( $theme . '/' . HOME_FILE ) ) ) continue;
		if( !array_filter( glob( $theme . '/functions.php' ) ) ) continue;
		if( !array_filter( glob( $theme . '/contents.php' ) ) ) continue;

		$path = array_filter( explode( '/' , $theme ) );
		$dirname = end( $path );

		if( in_array( $dirname , array( 'uploads' ) ) ) continue;

		$available_themes[$dirname] = strtoupper( $theme );

	endforeach;

	return $available_themes;
}

function get_available_tools(){
	$tools = array_filter( glob( './development/tools/*' , GLOB_ONLYDIR ) );

	$available_tools = array();

	if( !$tools ) array();

	foreach( $tools as $tool ) :
		$path = array_filter( explode( '/' , $tool ) );
		$dirname = end( $path );

		if( !(array_filter( glob( $tool . '/' . $dirname . '.php' ) ) ) ) continue;
		if( !(array_filter( glob( $tool . '/tool.xml' ) ) ) ) continue;

		$available_tools[$dirname]['name'] = ucwords( $dirname );
		$available_tools[$dirname]['dirname'] = $dirname;


		if( !(array_filter( glob( $tool . '/tool.xml' ) ) ) ) continue;

		if( ( $xml_contents = simplexml_load_file( $tool . '/tool.xml' ) ) === false ) continue;
		$available_tools[$dirname] = array_merge($available_tools[$dirname] , (array)$xml_contents);

	endforeach;

	return $available_tools;
}
function get_active_tools(){
	if( ( $tools = unserialize( get_option('active_tools') ) ) === false )
		return array();

	return $tools;
}

/*http://stackoverflow.com/questions/3349753/delete-directory-with-files-in-it*/
function delete_upload_dir_recur($path)
{
	if( $path == '/' ) return false;
	$path = get_upload_dir() . '/' . $path;

    if (! is_dir($path)) {
        throw new InvalidArgumentException("$path must be a directory");
    }
    if (substr($path, strlen($path) - 1, 1) != '/') {
        $path .= '/';
    }
    $files = glob($path . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            delete_upload_dir_recur($file);
        } else {
            unlink($file);
        }
    }
   return rmdir($path);
}