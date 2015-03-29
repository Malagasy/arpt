<?php
/**
	* Récupère les catégories d'un type donné.
	*
	* Récupère les catégories du type de contenu $value.
	* Cette fonction est un raccourci de get_category_by().
	*
	* @param String $value Le type de la catégorie recherchée.
	* @param String $what Elément recherché.
	* @return Mixed
	* Retourne FALSE si aucune catégorie n'est récupérée.
	* Retourne String si $what spécifié et qu'une seule catégorie récupérée.
	* Sinon, retourne objet Categories.
*/
function get_category_by_type( $value , $what = '*' ){
	return get_category_by( 'content_type' , $value , $what );
}

/**
	* Récupère une catégorie par son ID.
	*
	* Récupère la catégorie d'ID $value.
	* Cette fonction est un raccourci de get_category_by().
	*
	* @param Int $value L'ID de la catégorie recherchée.
	* @param String $what Elément recherché.
	* @return Mixed
	* Retourne FALSE si aucune catégorie n'est récupérée.
	* Retourne String si $what spécifié et qu'une seule catégorie récupérée.
	* Sinon, retourne objet Categories.
*/
function get_category_by_id( $value , $what = '*' ){
	return get_category_by( 'id' , $value , $what );
}

/**
	* Récupère une catégorie par son nom.
	*
	* Récupère la catégorie de nom $value.
	* Cette fonction est un raccourci de get_category_by().
	*
	* @param String $value Le nom de la catégorie recherchée.
	* @param String $what Elément recherché.
	* @return Mixed
	* Retourne FALSE si aucune catégorie n'est récupérée.
	* Retourne String si $what spécifié et qu'une seule catégorie récupérée.
	* Sinon, retourne objet Categories.
*/
function get_category_by_name( $value , $what = '*' ){
	return get_category_by( 'name' , $value , $what );
}

function get_catid_by_name( $value , $what = '*' ){
	$r = get_category_by_name( $value , $what );
	
	if( $r === false ) return false;

	if( $r->next() )
		return $r->qdatas()->id;
}

function get_currentcategorypage(){
	if( is_categorypage() )
		return get_pagetype();
	return false;
}
/**
	* Vérifie l'existence d'une catégorie.
	*
	* Vérifie l'existence de la catégorie définie par $id. $id peut prendre
	* l'ID de la catégorie mais également le nom.
	*
	* @param String $value L'ID ou le nom de la catégorie recherchée.
	* @param String $what Elément recherché.
	* @return Boolean
	* Retourne TRUE si la catégorie existe, sinon FALSE.
*/
function category_exists( $id ){
	if( is_number( $id ) ) :
		if( get_category_by_id( $id ) === false )
			return false;
		return true;
	else :
		if( get_category_by_name( $id ) === false )
			return false;
		return true;
	endif;
}
?>