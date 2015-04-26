<?php


function get_categories( $specificities = null ){
	return new Categories( $specificities );
}

/**
	* Récupère une ou plusieurs catégories.
	*
	* Récupérer une ou plusieurs catégories à partir de l'ID, du nom ou du type de la catégorie.
	* Rechercher à l'aide du type de la catégorie peut vous permettre de récupérer plusieurs résultats. Il correspond
	* à un type de contenu.
	*
	* Rechercher sur l'ID ou le nom retourne au mieux une seule catégorie.
	*
	* @param String $label Le filtre à utiliser. 
	* Valeurs possibles : id|name|type.
	* @param String $value Valeur correspond à $label.
	* @param String $what Element à récupérer dans les résultats.
	*
	* @return Mixed
	* Retourne FALSE si aucune catégorie n'est récupéré.
	* Retourne String si $what spécifié et qu'une seule catégorie récupérée.
	* Sinon, retourne objet Categories.
*/
function get_category_by( $label , $value , $what = '*' ){
	if( $value == '' ) return false;

	$label = ( samestr( $label , 'type' ) ) ? 'content_type' : $label;

	$r = get_categories( array( $label => $value , 'selection' => $what ) );

	if( $r->has() ) :
		if( $r->total == 1 )
			if( $what == '*' ) :
				return $r;
			else :
				$r->next();
				return $r->datas->$what;
			endif;
		else
			return $r;
	endif;

	return false;
}
/**
	* Récupère l'ID de la catégorie.
	*
	* Récuperer l'ID de la catégorie du contenu spécifié par $cid.
	*
	* @param int $cid ID du contenu.
	*
	* @return Mixed
	* Retourne FALSE si le contenu n'a pas de catégorie.
	* Sinon, retourne l'ID de la catégorie.
*/

function get_content_category( $cid ){
	$cat = new_query( 'select' , 'arpt_contents_categories' , array( 'selection' => 'cat_id' , 'where' => 'content_id=\''.$cid.'\'' ) );

	if( $cat->next() )
		if( category_exists( $cat->datas->cat_id ) )
			return $cat->datas->cat_id;
	return false;
}

function get_contentsids_by_categoryid( $catid ){
	if( $catid == '' ) return array();

	$cat = new_query( 'select' , 'arpt_contents_categories' , array( 'selection' => 'content_id' , 'where' => 'cat_id=\''.$catid.'\'' ) );

	if( $cat->has() ) :
		while( $cat->next() )
			$r[] = $cat->qdatas()->content_id;
		return $r;
	endif;
	return array();
}

function insert_content_category( $cid , $catid ){
	if( get_content_category( $cid ) )
		return false;
	return new_query( 'insert' , 'arpt_contents_categories' , array( 'content_id' => $cid , 'cat_id' => $catid ) );
}

function update_content_category( $cid , $catid ){

	if( get_content_category( $cid ) === false )
		return insert_content_category( $cid , $catid );
	return new_query( 'update' , 'arpt_contents_categories' , array( 'set' => 'cat_id=\''.$catid.'\'' , 'where' => 'content_id=\''.$cid.'\'' ) );

}

function delete_category( $id ){
	call_triggers( 'before_delete_category' , $id );

	new Categories( $id , 'delete' );
	new Queries( 'update' , 'arpt_contents_categories' , array( 'set' => clause_where( 'cat_id' , '=' , 0 ) , 'where' => clause_where( 'cat_id' , '=' , $id ) ) );

	return true;

}