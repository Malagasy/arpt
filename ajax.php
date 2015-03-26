<?php
$ajax_vars = array();
$ajax_vars[] = isset( $_POST['param1'] ) ? $_POST['param1'] : null;
$ajax_vars[] = isset( $_POST['param2'] ) ? $_POST['param2'] : null;
$ajax_vars[] = isset( $_POST['param3'] ) ? $_POST['param3'] : null;
$ajax_vars[] = isset( $_POST['param4'] ) ? $_POST['param4'] : null;



session_start();

DEFINE('THE_AJAX_CALL',true);

include_once 'loader.php';

$arpt = Arpt::getInstance();

$arpt->activation();

$arpt->development_activation();


call_user_func( $_POST['action'] , $ajax_vars[0] , $ajax_vars[1] , $ajax_vars[2] , $ajax_vars[3] );