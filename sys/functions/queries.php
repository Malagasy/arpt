<?php

function new_query( $type , $table , $specificities = null ){
	return new Queries( $type , $table , $specificities );
}

function custom_paginate( $extension , $cp , $max ){
	Queries::paginate($extension, $cp, $max);
}