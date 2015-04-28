<?php

function default_contents_properties( $cid ){
	insert_contentproperty( $cid , 'commentscount' , 0 );
}

function redirect_nologged_user( $params ){
	redirect( get_signup_url( 'in' ) );
}
