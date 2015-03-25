<?php
session_start();

include_once 'loader.php';

$arpt = Arpt::getInstance();

$arpt->activation();

$arpt->development_activation();

$arpt->routing();
