<?php

function get_queried(){
	global $arpt; 
	return $arpt->get_queried();
}
function qtitle(){
	return get_queried()->qtitle();
}
function qtitlelink(){
	return get_queried()->qtitlelink();
}

function qcontent(){
	return get_queried()->qcontent();
}

function qid(){
	return get_queried()->qid();
}

function qpid(){
	return get_queried()->qpid();
}

function qauthorlink(){
	return get_queried()->qauthorlink();
}
function qauthor(){
	return get_queried()->qauthor();
}

function qhas(){
	return get_queried()->qhas();
}

function qlink(){
	return get_queried()->qlink();
}

function qtag(){
	return get_queried()->qtag();
}

function qfree(){
	return get_queried()->free();
}

function qpagination( $extension = 3 ){
	return get_queried()->pagination( $extension );
}

function qminiature( $formatname = 'classic' ){
	return get_queried()->qminiature( $formatname );
}

function qtype(){
	return get_queried()->qtype();
}

function qdate(){
	return get_queried()->qdate();
}

function qdate_notformated(){
	return get_queried()->qdate_notformated();
}
function qnext(){
	return get_queried()->next();
}

function qreset(){
	return get_queried()->reset();
}

function qcategory(){
	return get_queried()->qcategory();
}

function qlastedit(){
	if( ( $date = get_queried()->qproperty( 'last_edit' ) ) !== false )
		return arpt_date( $date );
	return qdate();
}

function qsumup(){
	return get_queried()->qsumup();
}
function qproperty( $name ){
	return get_queried()->qproperty( $name );
}


function queried_comments(){
	if( $ccid = qid() )
		return get_comments( array( 'parent_id' => $ccid , 'orderby' => 'id' , 'ob_suffix' => ' DESC ' ) );
	return false;
}

function set_queried( $specificities ){
	global $arpt;
	$arpt->set_queried( $specificities );
}

function qcommentscount(){
	return (int)get_queried()->qproperty( 'commentscount' );
}