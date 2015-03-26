<?php

admin_header();

?>
<div class="admin-menu col-sm-3 col-md-2 sidebar">

	<?php


	admin_add_submenu( 'general-list' , 'dashboard' , '<span class="glyphicon glyphicon-dashboard"></span> Tableau de bord' , 'adminpage_overview' , 'view-backend' );
	admin_add_submenu( 'general-list' , 'general' , '<span class="glyphicon glyphicon-cog"></span> Paramètres généraux' , 'adminpage_option_general' , 'manage-settings' );
	admin_add_submenu( 'general-list' , 'tools' , '<span class="glyphicon glyphicon-folder-open"></span> Outils préchargés' , 'adminpage_tools' , 'manage-options' );
	admin_add_submenu( 'general-list' , 'multimedia' , '<span class="glyphicon glyphicon-film"></span> Multimédia' , 'adminpage_multimedia' , 'manage-users' );
	admin_add_submenu( 'general-list' , 'users' , '<span class="glyphicon glyphicon-user"></span> Utilisateurs' , 'adminpage_list_users' , 'manage-users' );

	foreach( get_active_contents() as $content ) :	
		admin_add_submenu( 'content' , 'list-contents/' . $content  , '<span class="glyphicon glyphicon-th-list"></span> Voir les ' . ucwords( pluralize( $content ) ), 'adminpage_list_contents' , 'manage-contents' );
	
		admin_add_submenu( 'content' , 'add-content/' . $content  , '<span class="glyphicon glyphicon-plus"></span> Ajouter' , 'adminpage_add_edit_contents' , 'manage-contents' );
		admin_add_submenu( 'content' , 'edit-content/' . $content  , 'Editer un(e) ' . $content , 'adminpage_add_edit_contents' , 'manage-contents' , false );
	endforeach;
	
	admin_add_submenu( 'management-tools' , 'category' , '<span class="glyphicon glyphicon-floppy-disk"></span> Gestion des catégories' , 'adminpage_category' ,  'manage-options' );
	admin_add_submenu( 'management-tools' , 'contents' , '<span class="glyphicon glyphicon-floppy-disk"></span> Gestion des contenus' , 'adminpage_contents' ,  'manage-options' );
	admin_add_submenu( 'management-tools' , 'navmenu' , '<span class="glyphicon glyphicon-floppy-disk"></span> Gestion des menus' , 'adminpage_menus' ,  'manage-options' );
	admin_add_submenu( 'management-tools' , 'widgetmenu' , '<span class="glyphicon glyphicon-floppy-disk"></span> Gestion des widgets' , 'adminpage_widgets' ,  'manage-options' );
	
	admin_add_submenu( 'development' , 'editor' , '<span class="glyphicon glyphicon-pencil"></span> Editeur' , 'adminpage_editor' , 'manage-settings' );

	admin_get_menus();
	?>

</div>


<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main admin-body">
<?php 
admin_message();
admin_routing();

admin_footer();
?>
</div>





<?php
function adminpage_overview(){
}

function adminpage_add_edit_contents(){
	$act = get_pageargs(0); 
	$type = get_pageargs(1);

	if( $act == 'edit-content' && filter_exists( get_pageargs(2) ) ) :
		$contents = new_content( array( 'type' => $type , 'slug' => get_pageargs(2) ) );
		$contents->next();
	endif;

	$cid = ( $act == 'edit-content' ) ? $contents->qid() : null;
	$select = ( $act == 'edit-content' ) ? $contents->qpid() : null;
	$title = ( $act == 'edit-content' ) ? $contents->qtitle() : last_value('title');
	$keywords = get_tag_list( $cid );
	$keywords_list = array();
	foreach ($keywords as $value)	$keywords_list[] = $value['name'];
	$keywords = ( $act == 'edit-content'  ) ? implode(', ' , $keywords_list ) : last_value('keywords');
	$message = ( $act == 'edit-content' ) ? $contents->qcontent() : last_value('message');
	$modelpage = ( $act == 'edit-content' ) ? $contents->qmodel() : last_value('modelpage');
	$slug = ( $act == 'edit-content' ) ? $contents->qslug() : last_value('slug');
	$status = ( $act == 'edit-content' ) ? $contents->qstatus() : last_value('status');
	$useOfToken = ( get_pageargs(2) ) ? get_pageargs(2) : uniqid();
	if( isset( $_GET['retrieve-data'] ) ) :
		if( $_GET['retrieve-data'] == 1 ) :
			$title = last_value('title');
			$keywords = last_value('keywords');
			$message = last_value('message');
			$modelpage = last_value('modelpage');
			$slug = last_value('slug');
			$status = last_value('status');
		endif;
	endif;


	if( $act == 'edit-content' && is_failure() ) :
		echo '<p>Les données de l\'envoi précédent ne sont pas perdues. ' . a( get_clean_url() . '/?retrieve-data=1' , 'Cliquez ici pour les ré-utiliser sur ce formulaire' ) . '.</p>';
	endif;

	if( $act == 'edit-content' )
		echo a( $contents->qlink() , 'Voir le contenu' , array('target'=>'_blank') );

	echo '<div class="container-fluid"><div class="row"><div class="col-md-8">';
	form_open( array( 'method' => 'post' , 'enctype' => 'multipart/form-data' ) );

	form_input( array( 'type' => 'hidden' , 'name' => 'useOfToken' , 'value' => $useOfToken ) );
	form_input( array( 'type' => 'hidden' , 'name' => $act . '_' . $useOfToken , 'value' => create_token( $act . '_' . $useOfToken ) ) );


	div( array( 'class' => 'form-group' ) );
	form_input( array( 'class' => 'form-control' , 'type' => 'hidden' , 'name' => 'content_type' , 'value' => $type ));
	div_close();


	if( $act == 'edit-content' ) :
		form_input( array( 'type' => 'hidden' , 'name' => 'cid' , 'value' => $cid ) );
	endif;
	div( array( 'class' => 'row' ) );
	div( array( 'class' => 'col-md-8' ) );
	div( array( 'class' => 'form-group' ) );
	form_input( array( 'class' => 'form-control' , 'type' => 'text' , 'name' => 'title' , 'value' => $title , 'placeholder' => 'Titre de votre ' . $type ) , 'Titre du contenu' , 'required' );
	div_close();
	div_close();

	$tab[0] = 'Aucune catégorie';

	$categories = get_category_by_type( $type );
	if( $categories )
		while( $categories->next() )
			$tab[ $categories->datas->id ]= undo_slug( $categories->datas->name );
	div( array( 'class' => 'col-md-4' ) );
	div( array( 'class' => 'form-group' ) );
	form_select( array( 'class' => 'form-control' , 'name' => 'category' ) , 'Catégorie' , $tab , get_content_category( $cid ) );
	div_close();	
	div_close();
	div_close();	

	form_textarea( array( 'class' => 'form-control' , 'name' => 'message' ) , null , $message );

	echo '</div><div class="col-md-4">';

	if( get_pageargs(1) == 'commentaire' ){
		$options = get_contents( array( 'type' => 'NOT-'.get_pageargs(1) , 'selection' => 'id,content_title,content_type') );
		//logr($options);

		$parents[0] = '-';
		foreach( $options->getAll() as $opt )
			if( $opt['id'] != $cid )
				$parents[$opt['id']] = '['. $opt['content_type'] . ']' . $opt['content_title'];
	}else{
		$options = get_contents( array( 'type' => get_pageargs(1) , 'selection' => 'id,content_title') );

		$parents[0] = 'Pas de contenu parent';
		foreach( $options->getAll() as $opt )
			if( $opt['id'] != $cid )
				$parents[$opt['id']] = $opt['content_title'];
	}

	div( array( 'class' => 'panel panel-primary' ) );
	div( array( 'class' => 'panel-heading' ) , 'Informations complémentaires' );
	div( array( 'class' => 'panel-body' ) );

		echo '<p>' . img( get_miniature( 'content' , $cid ) , get_format('miniature-small') + array( 'alt' => 'Miniature du contenu' , 'class' => 'img-responsive' ) ) . '</p>';
		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'hidden' , 'name' => 'MAX_FILE_SIZE' , 'value'  => maxsize_upload_files() ) );
		form_input( array( 'type' => 'file' , 'name' => 'miniature' ) , null );
		div_close();

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'class' => 'form-control' , 'type' => 'text' , 'name' => 'keywords' , 'value' => $keywords , 'placeholder' => 'Séparez les mots clés par des virgules'), 'Mot clés associés' );
		div_close();


		div( array( 'class' => 'form-group' ) );
		form_select( array( 'class' => 'form-control' , 'name' => 'parentid' ) , ( get_pageargs(1) == 'commentaire' ) ? 'Contenu associé' : 'Contenu parent' , $parents , $select );
		div_close();


	
		div( array( 'class' => 'form-group' ) );
		form_select( array( 'class' => 'form-control' , 'name' => 'modelpage' ) , 'Modèle de page' , get_modelspage() , $modelpage );
		div_close();	

		div( array( 'class' => 'form-group' ) );
		form_select( array( 'class' => 'form-control' , 'name' => 'status' ) , 'Statut de la page' , array( 'public' => 'Public' , 'not-public' => 'Non pulic' ) , $status );
		div_close();

		if( $act == 'edit-content' ) :
			div( array( 'class' => 'form-group' ) );
			form_input( array( 'class' => 'form-control' , 'name' => 'slug' , 'value' => $slug ) , 'Slug du contenu' , 'required');
			div_close();
		endif;
	div_close();

	div_close();
	echo '</div></div></div>';

	fieldset_open( 'Informations complémentaires', null , array( 'class' => 'hoverable' ) );

	div( array( 'class' => 'fade hidden' ) );

	div( array( 'class' => 'panel panel-info' ) );
	div( array( 'class' => 'panel-heading' ) , 'Champs personnalisés' );
	div( array( 'class' => 'panel-body container-fluid' ) );


	div( array( 'class' => 'row' ) );
	
	div( array( 'class' => 'col-md-12' ) );
	call_triggers( 'end_add-content' , $cid , $type );
	div_close();
	div_close();

	div_close();
	div_close();
	div_close();


	div_close();
	fieldset_close();
	
	form_submit( array( 'value' => ( $act == 'edit-content' ) ? 'Editer' : 'Ajouter' , 'class' => 'btn btn-primary' ) );
	
	form_close();
}

function adminpage_widgets(){
	div( array( 'class' => 'row' ) );

	$r = array();
	div( array( 'class' => 'col-md-4' ) );
	div( array( 'class' => 'panel panel-primary' ) );
	div( array( 'class' => 'panel-heading' ) , 'Gestion des Widgets' );
	div( array( 'class' => 'panel-body' ) );
	form_open();
	form_input( array( 'type' => 'hidden' , 'name' => 'widgetmenu-add' ) );
	form_select( array( 'class' => 'fusion-bottom form-control' , 'name' => 'widget-add' ) , null , array( '0' => 'Selectionner un Widget' ) + get_available_widgets() );
	
	form_submit( array( 'class' => 'btn btn-default btn-sm btn-block fusion-top' , 'value' => 'Ajouter ce widget' ) );
	form_close();

	echo '<hr/>';

	form_open();
	foreach( get_widgetmenu() as $widget ){
		$r[$widget] = get_widgets( $widget );
	}
	form_input( array( 'type' => 'hidden' , 'name' => 'widgetmenu-remove' ) );
	form_select( array( 'class' => 'fusion-bottom form-control' , 'name' => 'widget-remove' ) , null ,  array( 0 => 'Selectionner un Widget' ) + $r );
	
	form_submit( array( 'class' => 'btn btn-default btn-sm btn-block fusion-top' ,'value' => 'Supprimer ce widget' ) );
	form_close();
	div_close();
	div_close();
	div_close();


	div( array( 'class' => 'col-md-8' ) );
	div( array( 'class' => 'panel panel-info' ) );
	div( array( 'class' => 'panel-heading' ) , 'Configuration des Widgets actifs' );
	div( array( 'class' => 'panel-body' ) );
	if( $gwm = get_widgetmenu() ) :
		foreach( $gwm as $value ) : $r[$value] = get_widgets( $value );

			fieldset_open( get_widgets( $value ) , null , array('class' => 'hoverable' ));


			div( array( 'class' => 'fade hidden' ) );
			?>

			<?php
			if( call_user_func( $value ) === false )
				echo 'Ce widget n\'est pas paramétrable.';
			div_close();

			fieldset_close();
		endforeach;
	endif;
	div_close();
	div_close();
	div_close();


	div_close();
}

function adminpage_menus(){
	if( $menuslist = get_navmenus() ) :
		echo '<nav>';
		echo '<ul class="nav nav-pills">';
		$i = 0;	
		foreach( $menuslist as $k => $v ) :
			$i++;

			if( !filter_exists( $slug_menu = get_pageargs(1) ) ) :
			 	if( $i == 1 ) :
			 		$active = 'class="active"';
			 		$first_menu = $k;
				else :
					$active = '';
				endif;
			else :
				if( $k == $slug_menu )
					$active = 'class="active"';
				else
					$active ='';
			endif;
			echo '<li role="presentation" ' . $active . '>' . a( get_admin_url('/navmenu/' . $k), $v['name'] )  . '</li>';
		//: ' . $v['description'] . '</li>';
		endforeach;
		echo '</ul>';
		echo '</nav>';
	else :
		echo 'Pas de menu pour le moment.';
		return;
	endif;

	if( !filter_exists( $slug = get_pageargs(1) ) ) $slug = $first_menu;
	
	if( ( $currentnavmenu = get_navmenu( $slug ) ) === false ) :
		echo 'Menu introuvable.';
		return;
	endif;


	echo '<div class="row margin-top-20">';

	fieldset_open( '{' . ucwords( $currentnavmenu['contenttype'] ) . '} ' . $currentnavmenu['description'] );
	echo '<div class="col-md-5">';
	echo '<div class="panel panel-default">';
 	echo '<div class="panel-heading">Liens associés au menu</div>';
	if( $navlist = get_navmenu_links( $slug )  ) : 
		echo '<ul class="list-group">';
		if( $currentnavmenu['contenttype'] == 'category' ) :
			foreach( $navlist as $value)
				if( category_exists( $value ) ) :
					echo '<li href="#" class="list-group-item">' . a( get_edit_content_url( $value ) , get_category_by_id( $value , 'name' ) ) . ' ' . get_delete_url( 'navmenu' , $value ) . '</li>';
				endif;
		else :
			foreach( $navlist as $value)
				if( content_exists( $value ) ) :
					echo '<li href="#" class="list-group-item">' . a( get_edit_content_url( $value ) , get_contentname( $value )  ) . ' ' . get_delete_url( 'navmenu' , $value ) . '</li>';
				endif;
		endif;
		echo '</ul>';
	endif;
	echo '</div>';
	echo '</div>';

	echo '<div class="col-md-7">';

	form_open();
	form_input( array( 'type' => 'hidden' , 'name' => 'navmenu-add' , 'value' => create_token( 'navmenu-add' ) ) );
	form_input( array( 'type' => 'hidden' , 'name' => 'navslug' , 'value' => $slug ) );

	div( array( 'class' => 'form-group' ) );
	div( array( 'class' => 'input-group' ) );
	form_select( array( 'class' => 'form-control' , 'name' => 'id-add' ) , null , array( '0' => 'Sélectionner un élément à ajouter' ) +get_available_navmenu( $currentnavmenu['contenttype'] , $slug ) );

	span( array( 'class' => 'input-group-btn' ) );
	form_submit( array( 'value' => 'Ajouter au menu' , 'class' => 'btn btn-primary' ) );
	span_close();

	div_close();
	div_close();
	
	form_close();
	$r = array();
	$r[0] = 'Choisissez';
	if( $currentnavmenu['contenttype'] == 'category' ) :
		foreach( $navlist as $value)
			if( category_exists( $value ) ) :
				$r[$value] = get_category_by_id( $value , 'name' );
			endif;
	else :
		foreach( $navlist as $value)
			if( content_exists( $value ) ) :
				$r[$value] = get_contentname( $value  );
			endif;
	endif;
	

	echo '</div>';
	echo '</div>';

	fieldset_close();
}

function adminpage_contents(){
	form_open();

	form_input( array( 'type' => 'hidden' , 'name' => 'contents-management' , 'value' => create_token( 'contents-management' ) ) );

	for( $i = 1 ; $i <= 10 ; $i++ )
		$tab[$i] = $i;

	div( array( 'class' => 'form-group' ) );
	form_select( array( 'class' => 'form-control', 'name' => option('contents_per_page') ) , 'Nombre d\'éléments à afficher par page' , $tab , get_option('contents_per_page') );
	div_close();


	div( array( 'class' => 'form-group' ) );
	form_select( array( 'class' => 'form-control' , 'name' => option('allow_visitor_to_comment') ) , 'Autoriser les visiteurs à poster des commentaires' , array( '1' => 'Oui' , '0' => 'Non' ) , get_option('allow_visitor_to_comment') );
	div_close();


	div( array( 'class' => 'form-group' ) );
	form_select( array( 'class' => 'form-control' , 'name' => option('hide_content_type_on_url') ) , 'Cacher les types de page sur les liens des contenus' , array( '1' => 'Oui' , '0' => 'Non' ) , get_option('hide_content_type_on_url') );

	span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'Cela ne s\'applique que sur les types de page, si votre contenu appartient à une catégorie celle-ci sera toujours visible sur l\'URL. Eg: www.monsite/page/toto/ => www.monsite/toto/' );
	div_close();

	
	form_submit( array( 'value' => 'Modifier' , 'class' => 'btn btn-primary' ) );

	form_close();
}

function adminpage_category(){
	$r[0] = 'Choisissez une catégorie';
 	foreach (get_active_contents() as $value)
 		if( $value != 'commentaire' )
 			$r[$value] = ucwords( $value );

	form_open();
	if( is_active_content( ( $pcattype = get_pageargs(1) ) ) && category_exists( ($catname = get_pageargs(2) ) ) ) :
		form_input( array( 'name' => 'edit-category' , 'type' => 'hidden' , 'value' => create_token( 'edit-category' ) ) );

		fieldset_open( 'Modifier une catégorie' );
		$the_category = get_category_by_name( $catname );
		$the_category->next();
		form_input( array( 'name' => 'category_id' , 'type' => 'hidden' , 'value' => $the_category->qid() ) );

		div( array( 'class' => 'panel panel-warning' ) );
		div( array( 'class' => 'panel-heading' ) , 'Vous êtes en train de modifier la catégorie' . strong( $catname ) . '. ' . a( get_admin_url( 'category/' . $pcattype ) , 'Revenir' ) );
		div( array( 'class' => 'panel-body' ) );

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'class' => 'form-control' , 'name' => 'catname' , 'value' => $catname , 'placeholder' => 'Nom de la catégorie') , null , 'required' );

		span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'Soyez le plus concis possible, pour des raisons de lisibilités je vous conseille de nommer votre catégorie par un mot.' );
		div_close();

		div( array( 'class' => 'form-group' ) );
	 	form_input( array( 'class' => 'form-control' , 'name' => 'catdescr' , 'placeholder' => 'Quelques mots pour décrire votre catégorie' , 'value' => $the_category->qdescr() ) , null , 'required' );
	 	div_close();

	 	echo '<p>' . strong( 'Type de catégorie : ' ) . $the_category->qtype() . '</p>';

		form_submit( array( 'value' => 'Modifier' , 'class' => 'btn btn-primary' ) );

	 	div_close();
	 	div_close();
	 	div_close();

	 	fieldset_close();
	else :
		form_input( array( 'name' => 'add-category' , 'type' => 'hidden' , 'value' => create_token( 'add-category' ) ) );

		fieldset_open( 'Ajouter une nouvelle catégorie' , null , array( 'class' => 'hoverable' ) );

		div( array( 'class' => 'fade hidden' ) );


		div( array( 'class' => 'panel panel-info' ) );
		div( array( 'class' => 'panel-heading' ) , 'Création d\'une catégorie' );
		div( array( 'class' => 'panel-body' ) );

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'class' => 'form-control' , 'name' => 'catname' , 'value' => last_value('catname') , 'placeholder' => 'Nom de la catégorie') , null , 'required' );

		span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'Soyez le plus concis possible, pour des raisons de lisibilités je vous conseille de nommer votre catégorie par un mot.' );
		div_close();

		div( array( 'class' => 'form-group' ) );
	 	form_input( array( 'class' => 'form-control' , 'name' => 'catdescr' , 'placeholder' => 'Quelques mots pour décrire votre catégorie' , 'value' => last_value('catdescr') ) , null , 'required' );
	 	div_close();

		div( array( 'class' => 'form-group' ) );
	 	form_select( array( 'class' => 'form-control' , 'name' => 'cattype' ) , 'Type de catégorie' , $r );
	 	div_close();

		form_submit( array( 'value' => 'Ajouter' , 'class' => 'btn btn-primary' ) );

	 	div_close();
	 	div_close();
	 	div_close();
	 	div_close();

	 	fieldset_close();
	 endif;

 	form_close();

 	fieldset_open( 'Types de catégorie' );

 	div( array('class' => 'panel panel-default' ) );
 	div( array('class' => 'panel-heading' ) );
 	ul( array( 'class' => 'nav nav-pills' ) );
 	$i = 0;
 	array_shift( $r ); // we remove the first key 'Choisissez une catégorie'
 	foreach ($r as $content ) :
 		$i++;

		if( !filter_exists( $slug_content = get_pageargs(1) ) ) :
		 	if( $i == 1 ) :
		 		$active = 'class="active"';
		 		$first_menu = $content;
			else :
				$active = '';
			endif;
		else :
			if( $content == $slug_content )
				$active = 'class="active"';
			else
				$active ='';
		endif;

		echo '<li role="presentation" ' . $active . '>' . a( get_admin_url('category') . $content . '/' , ucwords( $content ) ) . '</li>';

 	endforeach;
 	ul_close();
 	div_close();

 	$default_article_slug = routing_article();
 	$current_content =  strtolower( remove_params( get_pageargs(1) ) );
 	if( $current_content == '' )
 		$content = $default_article_slug;
 	elseif( category_exists( $current_content ) )
 		$content = $current_content;
 	else
 		$content = $current_content;

	$cats = get_category_by_type( $content );
	echo '<table class="table table-hover ">';
	echo '<thead><tr>';
	echo '<th class="col-sm-1">#</th>';
	echo '<th class="col-sm-2">Nom de la catégorie</th>';
	echo '<th class="col-sm-3">Description</th>';
	echo '<th class="col-sm-3">Contenus associés</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	if( $cats !== false ) :
		while( $cats->next() )  :
			echo '<tr>';
	 		echo '<td scope="row">' . $cats->qid() . '</td>';
	 		echo '<td>' . a( get_admin_url('category/' . $pcattype . '/' ) . $cats->qname() , $cats->qname() ) . ' ';
			echo get_delete_url( 'category' , $cats->qid() );
	 		echo '</td>';
	 		echo '<td>' . $cats->qdescr() . '</td>';
	 		$related_ids = get_contentsids_by_categoryid( $cats->qid() );
	 		$link = array();
	 		foreach( $related_ids as $id )
	 			$link[] = a( get_edit_content_url( $id ) , get_contentname( $id ) );
	 		echo '<td>' . implode( ' <b>/</b> ' , $link ) . '</td>';
	 		echo '</tr>';
	 	endwhile;

	 	echo '</tbody>';
	 	echo '</table>';

	else :
	 	echo '</tbody>';
	 	echo '</table>';
		echo 'Pas de résultats.';
	endif;
	div_close();
	fieldset_close();

}

function adminpage_option_general(){
	form_open();

	form_input( array( 'type' => 'hidden' , 'name' => 'edit_general_setting' , 'value' => create_token('edit_general_setting') ) );

	fieldset_open( 'Identité' );

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'text', 'name' => 'sitename' , 'value' => get_setting('sitename') , 'class' => 'form-control' ) , 'Nom de votre site' );
		div_close();
		
		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'text', 'name' => 'slogan' , 'value' => get_setting('slogan') , 'class' => 'form-control' ) , 'Slogan' );
		div_close();
		
		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'text', 'name' => 'description' , 'value' => get_setting('description') , 'class' => 'form-control' ) , 'Définissez votre site (100 caractères)' );
		div_close();
		
		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'email', 'name' => 'admin_email' , 'value' => get_setting('admin_email') , 'class' => 'form-control' ) , 'Adresse email du webmaster' );
		div_close();	

	fieldset_close();

	fieldset_open( 'Paramètres du site' );
		div( array( 'class' => 'form-group') );
		get_available_themes();
		form_select( array( 'name' => 'sitetheme'  , 'class' => 'form-control' ) , 'Thème du site' , get_available_themes() , get_setting('current_theme') , get_setting('current_theme') );
		div_close();

		div( array( 'class' => 'form-group') );
		form_select( array( 'name' => 'enable_signup'  , 'class' => 'form-control' ) , 'Autoriser les utilisateurs à s\'inscrire  ? ' , array( '1' => 'Oui' , '0' => 'Non' ) , ( ( $enable_signup = get_setting('enable_signup') ) === false ) ? '1' : $enable_signup );
		div_close();
	fieldset_close();

	form_submit( array( 'value' => 'Enregistrer' , 'class' => 'btn btn-primary' ) );
	
	form_close();
}

function adminpage_list_contents(){
	$the_limit = 15;

	$s = isset( $_GET['s'] ) ? $_GET['s'] : null;

	if( $s != null )
		$contents = get_contents( array( 'type' => get_pageargs(1) , 'ob_suffix' => ' DESC ' ,  'limit' => $the_limit , 'offset' => the_offset() , 'status' => 'all' , 'search' => $s ) );
	else
		$contents = get_contents( array( 'type' => get_pageargs(1) , 'ob_suffix' => ' DESC ' ,  'limit' => $the_limit , 'offset' => the_offset() , 'status' => 'all' ) );
	form_open( array('class' => 'form-inline text-right', 'method' => 'get'  ));
	div( array('class'=>'form-group' ));
	form_input( array('name'=>'s' , 'class'=>'form-control' , 'placeholder' => 'Recherche un contenu..' ) , null );
	div_close();
	form_submit( array('class'=>'btn btn-default' , 'value'=>'Go'));
	form_close();

	div( array( 'class' => 'panel panel-default' ) );
	div( array( 'class' => 'panel-heading' ) );
	echo '<p class="pull-right">' . a( get_admin_url('/add-content/' . get_pageargs(1) ) , 'Nouveau' ) . '</p>';
	echo '<p>Liste des ' .  pluralize( get_pageargs(1) ) . ' ( ' . ( $the_limit*the_offset() ) . ' - ' . ($the_limit*the_offset()+$the_limit) . ' sur ' . count($contents->getAll()) . ' )</p>';
	div_close();
	$contents->reset();
	echo '<table class="table table-hover">';
	echo '<thead><tr>';
	echo '<th>#</th>';
	echo '<th class="col-sm-2">Titre du contenu</th>';
	echo '<th class="col-sm-1">Auteur</th>';
	echo '<th class="col-sm-1">Catégorie</th>';
	echo '<th class="col-sm-2">Date de création</th>';
	echo '<th class="col-sm-4">Message</th>';
	echo '<th class="col-sm-1">Statut</th>';
	echo '<th class="col-sm-2">Contenu parent</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	if( $contents->has() ) :
		while( $contents->next() ) :
			echo '<tr>';
			echo '<td scope="row">' . $contents->qid() . '</td>';
			echo '<td >' . a( get_edit_content_url( $contents->qid() ) , $contents->qtitle() ) . ' ';
			echo get_delete_url( 'content' , $contents->qid() );
			echo '</td>';
			echo '<td>' . $contents->qauthor() . '</td>';
			echo '<td>' . $contents->qcategory() . '</td>';
			echo '<td>' . $contents->qdate() . '</td>';
			echo '<td>' . $contents->qsumup() . '</td>';
			echo '<td>' . ucwords( $contents->qstatus() ) . '</td>';
			echo '<td>' . get_contentname( $contents->qpid() ) . '</td>';
			echo '</tr>';
		endwhile;
		echo '</tbody>';
		echo '</table>';
	else :
		echo '</tbody>';
		echo '</table>';
		echo 'Pas de résultats.';
	endif;
	div_close();

	echo '<nav class="pull-right">';
	$contents->pagination();
	echo '</nav>';
}

function adminpage_list_users(){
	if( filter_exists( get_pageargs(1) ) ) : $userid = get_pageargs(1);
		if( is_number( $userid ) )
			if( !( $user = user_exists( $userid ) ) ) : 
				redirect( get_admin_url( 'users' ) );
			endif;
		elseif( $userid != 'new' ) redirect( get_admin_url( 'users' ) );




		form_open();

		form_hidden( array( 'name' => 'edit-user' , 'value' => create_token( 'edit-user' ) ) );

		form_hidden( array( 'name' => 'userid' , 'value' => $userid ) );

		fieldset_open( 'Informations personnelles de ' . strong( $user->qname() ) );

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'text' , 'name' => 'username' , 'value' => $user->qname() , 'class' => 'form-control' ) , 'Nom de l\'utilisateur' );
		div_close();

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'text' , 'name' => 'email' , 'value' => $user->qemail() , 'class' => 'form-control' ) , 'Adresse email' );
		div_close();

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'text' , 'name' => 'dateregistered' , 'value' => $user->qdateregistered() , 'class' => 'form-control' ) , 'Membre depuis le' );
		div_close();

		div( array( 'class' => 'form-group' ) );
		form_input( array( 'type' => 'password' , 'name' => 'password' , 'class' => 'form-control' ) , 'Mot de passe' );
		div_close();

		span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'A moins que vous souhaitiez modifier le mot de passe, laissez ce champ vide.' );

		fieldset_close();	

		fieldset_open( 'Informations du compte de ' . strong( $user->qname() ) );
		div( array( 'class' => 'panel panel-info' ) );
		div( array( 'class' => 'panel-heading') , 'Informations du compte' );
		div( array( 'class' => 'panel-body' ) );
		call_triggers( 'account_info_edit_user' , $user );
		div_close();
		div_close();

		fieldset_close();

		form_submit( array( 'value' => 'Modifier' , 'class' => 'btn btn-primary' ) );

		form_close();	

		return;
	endif;

	$s = isset( $_GET['s'] ) ? $_GET['s'] : null;

	if( $s != null )
		$users = new_user( array('id'=>null, 'offset' => the_offset() , 'limit' => 20 , 'name' => $s ) );
	else
		$users = new_user( array('id'=>null, 'offset' => the_offset() , 'limit' => 20 ) );

	form_open( array('class' => 'form-inline text-right', 'method' => 'get'  ));
	div( array('class'=>'form-group' ));
	form_input( array('name'=>'s' , 'class'=>'form-control' , 'placeholder' => 'Recherche un utilisateur..' ) , null );
	div_close();
	form_submit( array('class'=>'btn btn-default' , 'value'=>'Go'));
	form_close();

	div( array( 'class' => 'panel panel-default' ) );
	div( array( 'class' => 'panel-heading' ) );
	echo 'Liste des utilisateurs ( ' . ( 20*the_offset() ) . ' - ' . (20*the_offset()+20) . ' sur ' . count($users->getAll()) . ' ) ';
	div_close();
	$users->reset();
	echo '<table class="table table-hover ">';
	echo '<thead><tr>';
	echo '<th>#</th>';
	echo '<th class="col-sm-2">Nom de l\'utilisateur</th>';
	echo '<th class="col-sm-2">Adresse email</th>';
	echo '<th class="col-sm-2">Date d\'inscription</th>';
	echo '<th class="col-sm-2">Statut</th>';
	echo '<th class="col-sm-2">Rôle</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	if( $users->has() ) :
		while( $users->next() ) :
			echo '<tr>';
			echo '<td>' . $users->qid() . '</td>';
			echo '<td>' . a( get_edit_user_url( $users->qid() ) , $users->qname()  ) . ' ' . get_delete_url( 'user' , $users->qid() ) . '</td>';
			echo '<td>' . $users->qemail() . '</td>';
			echo '<td>' . $users->qdateregistered() . '</td>';
			echo '<td>' . $users->qstatus() . '</td>';
			echo '<td>' . $users->qrole() . '</td>';
			echo '</tr>';
		endwhile;
		echo '</tbody>';
		echo '</table>';
	else :
		echo 'Pas de résultats.';
		echo '</tbody>';
		echo '</table>';

	endif;

	div_close();


	echo '<nav class="text-right">';
	$users->pagination();
	echo '</nav>';

	fieldset_open( 'Actions supplémentaires' , null , array( 'class' => 'hoverable' ) );

	div( array( 'class' => 'fade hidden' ) );

	div( array( 'class' => 'panel panel-info' ) );
	div( array( 'class' => 'panel-heading' ) , 'Ajouter un nouvel utilisateur' );
	div( array( 'class' => 'panel-body' ) );

	form_open( array( 'class' => 'form-inline' ));

	form_input( array( 'name' => 'add-user' , 'type' => 'hidden' , 'value' => create_token( 'add-user' ) ) );
	
	div( array( 'class' => 'form-group' ) );
	form_input( array( 'name' => 'name' , 'value' => last_value('name') , 'class' => 'form-control' , 'placeholder' => 'Identifiant' ) , null );
	div_close();

	div( array( 'class' => 'form-group' ) );
	form_input( array( 'type' => 'password' , 'name' => 'pass' , 'class' => 'form-control' , 'placeholder' => 'Mot de passe'  ) , null );
	div_close();

	form_submit( array( 'value' => 'Ajouter' , 'class' => 'btn btn-primary'  ) ); 

	form_close();

	div_close();
	div_close();
	div_close();

	fieldset_close();
}


function adminpage_tools(){
	echo '<p>Sur cette page vous pouvez gérer les Outils à précharger sur votre site.</p>';

	$tools = get_available_tools();
	$active_tools = get_active_tools();

	echo '<table class="table table-hover ">';
	echo '<thead><tr>';
	echo '<th class="col-sm-1">Action</th>';
	echo '<th class="col-sm-2">Nom du Tool</th>';
	echo '<th class="col-sm-1">Auteur</th>';
	echo '<th class="col-sm-4">Description</th>';
	echo '<th class="col-sm-2">Date de création</th>';
	echo '<th class="col-sm-1">Version actuelle</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	if( $tools ) :
		foreach( $tools as $data ) :
			if( in_array( $data['dirname'] , $active_tools ) ) :
				$token = create_token( 'delete_tool_' . $data['dirname'] );
			 	$action = a( get_clean_url() . '/?action=rm&tool=' . $data['dirname'] . '&token=' . $token , 'Désactiver' , array( 'class' => 'btn btn-danger btn-block' )  );
			else :
				$token = create_token( 'add_tool_' . $data['dirname'] );
			 	$action = a( get_clean_url() . '/?action=add&tool=' . $data['dirname'] . '&token=' . $token , 'Activer' , array( 'class' => 'btn btn-success btn-block' ) );
			endif;
			echo '<tr>';
			echo '<td>' . $action . '</td>';
			echo '<td>' . $data['name'] . '</td>';
			echo '<td>' . $data['author'] . '</td>';
			echo '<td><p>' . $data['description'] . '</p><p>' . a( $data['link'] , $data['link'] ) . '</p></td>';
			echo '<td>' . $data['date'] . '</td>';
			echo '<td>' . $data['version'] . '</td>';
			echo '</tr>';
		endforeach;
		echo '</tbody>';
		echo '</table>';
	else :
		echo 'Pas de résultats.';
		echo '</tbody>';
		echo '</table>';
	endif;
}

function adminpage_multimedia(){
	$start = true;
	$col = 0;
	$key = -1;
	$params_nb = -1;


	$the_filter = isset( $_GET['dir'] ) ? $_GET['dir'] : '/';
	$uploads = get_uploaded( utf8_decode( $the_filter ) );
	$nbOfUploads = count( $uploads );
	$upload_dir_str =  get_upload_dir();

	div( array( 'class' => 'row' ) );
	div( array( 'class' => 'col-md-8' ) );


	if( !file_exists( get_upload_dir() . utf8_decode( $the_filter ) ) ) :
		$position = 'Emplacement inconnu';
	else :
		$position = './' . a( get_clean_url() . '?dir=' . urlencode( '/' ) , 'uploads');
		$params = array_values( array_filter( explode( '/' , $the_filter ) ) );
		$sizeof_params = count( $params );
		foreach( $params as $param ) : $params_nb++;
			$temp = '';
			if( $params_nb == $sizeof_params - 1 ) break;
			for($i=0;$i<=$params_nb;$i++) :
				if( $i == 0 )
					$temp .= '/' . $params[$i] . '/';
				else
					$temp .= $params[$i] . '/';
			endfor;
			$position .= '/' . a( get_clean_url() . '?dir=' . urlencode( $temp ) , $param );
		endforeach;
		if( $params ) $position .= '/' . end( $params ) . '/'; else $position .= '/';
	endif;

	echo '<h2><span class="glyphicon glyphicon-map-marker"></span> Vous êtes ici <small> ' . $position . '</small></h2>';

	if( !in_array( utf8_decode( $the_filter ) , array( '/' ) ) ) :

		if( $nbOfUploads ) :
			echo '<p class="pull-right">';
			form_submit( array( 'class' => 'btn btn-link' , 'data-toggle' => 'modal' , 'data-target' => '#delete-directory' , 'value' => 'Supprimer le dossier') );
			echo '</p>';
			?>
			<div class="modal fade" id="delete-directory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title" id="myModalLabel">Il y a des fichiers dans ce dossier !</h3>
					</div>
					<div class="modal-body">
						<div class="alert alert-danger"><strong>Attention.</strong> En supprimant ce dossier vous perdrez également tous les fichiers qui s'y trouvent. Cette action est irréversible.</div>
					</div>
					<div class="modal-footer">
						<?php
						echo get_delete_url( 'directory' , urlencode( trimslash( $the_filter ) ) , null , 'J\'ai pris conscience des risques' , array( 'class' => 'btn btn-primary' ) );
						form_submit( array( 'class' => 'btn btn-default' , 'data-dismiss' => 'modal' , 'value' => 'Annuler') );
						?>
					</div>
				</div>
			</div>
			</div>
			<?php
		else :
			echo '<p class="pull-right">';
			echo get_delete_url( 'directory' , urlencode( trimslash( $the_filter ) ) , null , 'Supprimer le dossier' , null);
			echo '</p>';
		endif;

	endif;

	$dirs = glob_recursive( $upload_dir_str . '/*' , GLOB_ONLYDIR );
	$dirs_filter['/'] = 'Voir tous';
	foreach( $dirs as $dir )
		$dirs_filter[ utf8_encode( substr( $dir , strlen( $upload_dir_str ) ) ) . '/'] = utf8_encode( substr( $dir , strlen( $upload_dir_str ) ) ) . '/';

	form_open( array( 'class' => 'form-inline margin-bottom-20' , 'method' => 'get' , 'action' => get_clean_url() ) );
		div( array( 'class' => 'form-group' ) );
			form_select( array( 'name' => 'dir' , 'class' => 'form-control' , 'onChange' => 'this.form.submit()' ) , 'Accès rapide' , $dirs_filter, ( $the_filter ) );
		div_close();
	form_close();
	if( $uploads ) :
		foreach( $uploads as $upload ) :
			$col++;
			$key++;
			$parent = substr( $upload , 0 , - strlen( basename( $upload ) ) );

			if( $start ) :
				echo '<div class="row">';
				$start = false;
			endif;
//	form_submit( array( 'class' => 'btn btn-link' , 'data-toggle' => 'modal' , 'data-target' => '#delete-directory' , 'value' => 'Supprimer le dossier') );
			echo '<div class="col-md-4">';
			if( is_image( $upload ) ) echo img( utf8_encode( $upload ) , array( 'class' => 'img-' . $key . ' img-responsive clickable' , 'data-toggle' => 'modal' , 'data-target' => '#img-' . $key ) );
			echo '</div>'; ?>
			<div class="modal fade" id="img-<?php echo $key; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-box="<?php echo $key; ?>" data-parent="<?php echo $parent; ?>">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="title-<?php echo $key; ?> modal-title" id="myModalLabel" data-name="<?php echo basename( utf8_encode( $upload ) ); ?>" data-id="<?php echo $key; ?>"><?php echo basename( utf8_encode( $upload ) ); ?></h3>
					</div>
					<div class="modal-body">
						<div class="pull-right margin-bottom-10">
							<div class="btn-group">
								<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
								Action <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#" class="rename-file" data-title="<?php echo $key; ?>">Renommer</a></li>
									<li><a href="#" class="delete-file" data-parent="<?php echo $parent; ?>" data-title="<?php echo $key; ?>">Supprimer</a></li>
								</ul>
							</div>
						</div>
						<p>
							<?php 
							$imageinfo = getimagesize(utf8_encode( $upload ) ); 
							echo 'Largeur: ' . $imageinfo[0] . ' & Hauteur: ' . $imageinfo[1]; 
							?>
						</p>
						<?php echo img( utf8_encode( $upload ) , array( 'class' => 'img-responsive center-block' ) ); ?>
						<?php 
						$disable_previous = ($key == 0 ) ? true : false;
						$disable_next = ( $key == $nbOfUploads - 1 ) ? true : false;
						?>
						<div class="text-center">
							<button <?php echo $disable_previous ? 'disabled="disabled"' : 'data-dismiss="modal"'; ?> class="btn btn-link previous-media" data-previous="<?php echo $key-1; ?>">Précédent</button>
							<button <?php echo $disable_next ? 'disabled="disabled"' : 'data-dismiss="modal"'; ?> class="btn btn-link next-media" data-next="<?php echo $key+1; ?>">Suivant</button>
						</div>
					</div>
				</div>
			</div>
			</div>
			<?php
			if( $col == 3 || $key == $nbOfUploads - 1 ) :
				$start = true;
				echo '</div>';
			endif;


		endforeach;
	else :
		echo 'Pas de résultats.';
	endif;
	div_close();

	div( array( 'class' => 'col-md-4' ) );

		div( array('class' => 'alert alert-warning' ) , '<strong>Comportement récursif.</strong> Les médias des sous-dossier du dossier courant sont affichés.' );
		$currentdir = basename( get_upload_dir() . $the_filter );
		div( array( 'class' => 'panel panel-info' ) );
		div( array( 'class' => 'panel-heading' ) , 'Navigation dans <strong>' . $currentdir . '</strong>' );
			div( array( 'class' => 'panel-body' ) );
				$dirs_in = array_filter( glob( get_upload_dir() . $the_filter . '*', GLOB_ONLYDIR ) );
				if( $dirs_in ) :
				foreach( $dirs_in as $dir )
					echo '<p><span class="glyphicon glyphicon-folder-close"></span> ' . a( get_clean_url() . '?dir=' . urlencode( $the_filter . basename( utf8_encode( $dir ) ) . '/' )  , basename( utf8_encode( $dir ) ) ) . '</p>';
				else :
					echo '<p>Ce dossier ne contient pas de sous-dossiers.</p>';
				endif;
			div_close();
		div_close();

		div( array( 'class' => 'panel panel-info' ) );
		div( array( 'class' => 'panel-heading' ) , 'Créer un nouveau dossier dans <strong>' . $currentdir . '</strong>' );
			div( array( 'class' => 'panel-body' ) );
				echo '<p>Le dossier est crée dans l\'emplacement actuel.</p>';
				form_open( array( 'class' => 'form-inline' , 'action' => get_clean_url() . '/?new_dir=1') );
				form_hidden( array( 'name' => 'parent_directory' , 'value' => $the_filter ) );
				div( array( 'class' => 'form-group' ) );
				form_input( array( 'class' => 'form-control' , 'name' => 'directoryname' , 'placeholder' => 'Nom du dossier' ) );
				div_close();
				form_submit( array( 'value' => 'Créer' , 'class' => 'btn btn-primary' ) );
				form_close();
			div_close();
		div_close();

		div( array( 'class' => 'panel panel-info' ) );
		div( array( 'class' => 'panel-heading' ) , 'Uploader des médias dans <strong>' . $currentdir . '</strong>' );
			div( array( 'class' => 'panel-body' ) );
				echo '<p>Les médias sont uploadés dans l\'emplacement actuel.</p>';
				echo '<p>Vous pouvez uploader plusieurs fichiers d\'affilés en les sélectionnant tous.</p>';
				form_open( array( 'method' => 'post' , 'enctype' => 'multipart/form-data' ) );
				form_hidden( array( 'name' => 'parent_directory' , 'value' => $the_filter ) );
				form_input( array( 'type' => 'hidden' , 'name' => 'MAX_FILE_SIZE' , 'value'  => maxsize_upload_files() ) );
				form_hidden( array( 'name' => 'upload_files' , 'value' => create_token('upload_files') ) );
				form_input( array( 'type' => 'file' , 'name' => 'the_file[]' ) , null , 'multiple' );
				echo '<p>';
				form_submit( array( 'value' => 'Upload' , 'class' => 'btn btn-link' ) );
				echo '</p>';
				form_close();
			div_close();
		div_close();


	div_close();

	div_close();
}

function adminpage_editor(){
	$base = '.';
	if( get_base_var() )
		$base .= get_base_var('/');
	else
		$base .= '/';

	$beforeleaving_message = "Le fichier a été modifié et ne sera pas enregistré.";
	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9 the_code">
				<p>
					Vous êtes sur l'éditeur de texte ARpt.
				</p>
				<p>
					Ce module vous donne accès aux fichiers sources de votre site. Cliquez sur un fichier sur la liste ci-contre.
				</p>
			</div>

			<div class="col-md-3"><?php
				echo '<div class="panel panel-primary" data-currentfolder="" data-basefolder="'.$base.'">';
				echo '<div class="panel-heading">Fichiers</div>';
				echo '<div  class="list-group list-files">';
				echo '</div>';
				echo '</div>'; ?>
			</div>

		</div>
	</div>

	<script type="text/javascript">
	jQuery(".editor-textarea").keydown(function(e) {
	    if(e.keyCode === 9) { // tab was pressed
	        // get caret position/selection
	        var start = this.selectionStart;
	        var end = this.selectionEnd;

	        var $this = $(this);
	        var value = $this.val();

	        // set textarea value to: text before caret + tab + text after caret
	        $this.val(value.substring(0, start)
	                    + "\t"
	                    + value.substring(end));

	        // put caret at right position again (add one for the tab)
	        this.selectionStart = this.selectionEnd = start + 1;

	        // prevent the focus lose
	        e.preventDefault();
	    }
	});

	jQuery(".the_code").on("change keyup paste",".editor-textarea",function(){
		if( jQuery(".valid-editor-textarea").html() == "OK" )
			jQuery(".valid-editor-textarea").html("Enregistrer");
		jQuery(".valid-editor-textarea").removeClass("disabled");
	});
	var current_folder = jQuery(".panel-primary").attr("data-currentfolder" );
	var base_folder = jQuery(".panel-primary").attr("data-basefolder" );
	if( current_folder == '' ){ 
		jQuery(".panel-primary").attr("data-currentfolder" ,base_folder );
		jQuery(".panel-primary .list-files").html( phpajax( 'editor_get_files_inside' , base_folder ) );
		jQuery(".panel-heading").html( base_folder );
	}

	jQuery(".list-files").on("click","a",function(e){

		e.preventDefault();

		var type = jQuery(this).attr("data-type");
		var path = jQuery(this).attr("data-path");
		var current_folder = jQuery(".panel-primary").attr("data-currentfolder" );

		if( type == 'folder' ){
			jQuery(".panel-primary").attr("data-currentfolder", current_folder + path + '/' );
			jQuery(".panel-primary .list-files").html( phpajax( 'editor_get_files_inside' ,  current_folder + path + '/' ) );
			jQuery(".panel-primary .list-files").prepend('<a href="#" data-type="back" data-path="" class="list-group-item"><strong>..</strong></a>');
			jQuery(".panel-heading").html(current_folder + path + '/' );
		}
		if( type == 'back' ){
			current_folder = current_folder.substring( 0 , current_folder.length-1 );
			var parent_folder = current_folder.substr( 0 , current_folder.lastIndexOf("/") ) + "/";

			jQuery(".panel-heading").html( parent_folder );

			jQuery(".panel-primary").attr("data-currentfolder", parent_folder );
			jQuery(".panel-primary .list-files").html( phpajax( 'editor_get_files_inside' ,  parent_folder ) );

			if( parent_folder != base_folder )
				jQuery(".panel-primary .list-files").prepend('<a href="#" data-type="back" data-path="" class="list-group-item"><strong>..</strong></a>');

		}

		if( type == 'file' ){

			if( jQuery(".valid-editor-textarea").length && !jQuery(".valid-editor-textarea").hasClass("disabled" ) )
				if( !confirm("<?php echo $beforeleaving_message; ?>") )
					return;

			jQuery(".the_code").html( "Chargement..." );
					
			jQuery(".the_code").html( phpajax( 'editor_display_file_code' ,  current_folder + path ) );
			jQuery(".the_code").prepend( '<h2>' + current_folder + path + '</h2>');
			jQuery(".the_code").prepend( '<button class="btn btn-success pull-right valid-editor-textarea"><span class="glyphicon glyphicon-save"> </span></button>' );
			jQuery(".valid-editor-textarea").addClass("disabled");
		}
	});

	jQuery(".the_code").on("click",".valid-editor-textarea",function(e){
		
		var r = phpajax( 'editor_register_file' , jQuery(".the_code h2").html() , jQuery(".editor-textarea").val() );
		if( r == "filechanged" ){
			jQuery(this).html('<span class="glyphicon glyphicon-saved"> </span>');
			jQuery(".valid-editor-textarea").addClass("disabled");
		}else{
			jQuery(this).html("Réessayer SVP");
		}
	});

	jQuery(window).on("beforeunload",function(){
		if( jQuery(".valid-editor-textarea").length && !jQuery(".valid-editor-textarea").hasClass("disabled" ) )
			return "<?php echo $beforeleaving_message; ?>";
	});

	</script><?php
}
?>