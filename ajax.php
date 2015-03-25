<?php
session_start();

include_once 'loader.php';

$arpt = Arpt::getInstance();

$arpt->activation();

$arpt->development_activation();

$p1 = isset( $_POST['param1'] ) ? $_POST['param1'] : null;
$p2 = isset( $_POST['param2'] ) ? $_POST['param2'] : null;
$p3 = isset( $_POST['param3'] ) ? $_POST['param3'] : null;
$p4 = isset( $_POST['param4'] ) ? $_POST['param4'] : null;


call_user_func( $_POST['action'] , $p1 , $p2 , $p3 , $p4 );