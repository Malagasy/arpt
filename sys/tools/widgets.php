<?php


function widget_navsearchform(){ 

	if( action_edit_widget() ) :
		form_open( array( 'class' => 'form-inline' ) );
		form_hidden( array( 'name' => 'edit-widget' , 'value' =>__function__ ) );

		widget_title_form();

		form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Update' ) );
		form_close();
		return;
	endif;
	
	echo widget_title();

	search_form(); ?>
<?php
}
/*TRES MAUVAISE FACON DE FAIRE IL FAUT UTILISER UN WIDGET*/
function widget_last_12_months_contents(){
	$widget_option_1 = get_option( 'widget_last_12_months_contents_monthstodisplay' );
	if( action_edit_widget() ) :
		
		form_open( array( 'class' => 'form-inline' ) );
		form_hidden( array( 'name' => 'edit-widget' , 'value' => __function__ ) );

		widget_title_form();

		for( $i = 1 ; $i < 12 ; $i++ )
			$count[$i] = $i;

		div( array( 'class' => 'form-group' ) );
		form_select( array( 'class' => 'form-control' , 'name' => option('widget_last_12_months_contents_monthstodisplay') ) , 'Nombre de mois à afficher' , $count , $widget_option_1 );
		div_close();

		form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Update' ) );

		span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'Ce widget regroupe vos articles dans une liste mensuelle.' );
		form_close();

		return;
	endif;
	echo widget_title();
	$delimiter = call_layers('widget_last_comments_delimiter_layer',array( 'start' => '<div class="list-group">' , 'end' => '</div>' , 'start_inner' => '' , 'end_inner' => '' , 'class_inner' => 'list-group-item') ); 
	echo  $delimiter['start'];
	for( $i = 0 ; $i <= $widget_option_1 ; $i++ ) :
		$time = strtotime( "-$i month" );
		$year = date( 'Y' , $time ); //logr($year . '-' . $month . '-' .$i);
		$month = date( 'm' , $time); //logr($year . '-' . $month . '-' .$i);
		echo $delimiter['start_inner'] . a( get_site_url() . '/' . $year . '/' . $month , date( 'F Y' , $time ) , array( 'class' => $delimiter['class_inner'] ) ) . $delimiter['end_inner'];
	endfor;
	echo $delimiter['end'];

}

function widget_last_articles(){ 
	$widget_option_1 = get_option( 'widget_last_articles_articlestodisplay' );

	if( action_edit_widget() ) :
		
		form_open( array( 'class' => 'form-inline' ) );
	
		form_hidden( array( 'name' => 'edit-widget' , 'value' => __function__ ) );

		widget_title_form();

		for( $i = 1 ; $i < 12 ; $i++ )
			$count[$i] = $i;
		div( array( 'class' => 'form-group' ) );
		form_select( array( 'class' => 'form-control' , 'name' => option('widget_last_articles_articlestodisplay') ) , 'Nombre d\'articles à afficher' , $count , $widget_option_1 );
		div_close();


		form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Update' ) );
		
		span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'Ce widget affiche les <i>x</i> derniers articles publiés sur votre site.' );

		form_close();

		return;
	endif;
	echo widget_title();

	$delimiter = call_layers('widget_last_articles_delimiter_layer',array( 'start' => '<div class="list-group">' , 'end' => '</div>' , 'start_inner' => '' , 'end_inner' => '' , 'class_inner' => 'list-group-item' ) ); 
	echo $delimiter['start'];
	$contents = get_contents( array( 'type' => 'article' , 'limit' => $widget_option_1 , 'ob_suffix' => ' DESC ') );
	while( $contents->next() )
		echo $delimiter['start_inner'] . '<a href="' . $contents->qlink() . '" class="'.$delimiter['class_inner'].'">'. $contents->qtitle() . '</a>' . $delimiter['end_inner'];
	$contents->free();
	echo $delimiter['end'];
}

function widget_userprofile(){ 
	if( action_edit_widget() ) return false;
?>
	<p>
		<?php 
		if( !is_user_online() ) :
			echo '<h4>Connectez-vous</h4>';
			signin_form();
			echo '<p class="help-block"><a href="' . get_signup_url('new' ) . '">S\'inscrire ?</a></p>';
		else :
			echo '<h4>Que voulez-vous faire ?</h4>';
			echo '<div class="list-group">';
			echo a( get_home_url() , 'Page d\'accueil' , array( 'class' => 'list-group-item' ) ) ;
			if( is_user_admin() ) echo a( get_admin_url() , 'Administrer le site' , array( 'class' => 'list-group-item' ) ) ;
			echo a( get_logout_url() , 'Se déconnecter' , array( 'class' => 'list-group-item' )  );
			echo '</div>';
		endif;
		?>
	</p>
<?php
}

function widget_last_comments(){
	$widget_option_1 = get_option( 'widget_last_comments_commentstodisplay' );

	if( action_edit_widget() ) :
		
		form_open(  array( 'class' => 'form-inline' ) );
		form_hidden( array( 'name' => 'edit-widget' , 'value' => __function__ ) );


		widget_title_form();

		for( $i = 1 ; $i < 12 ; $i++ )
			$count[$i] = $i;

		div( array( 'class' => 'form-group' ) );
		form_select( array(  'class' => 'form-control' , 'name' => option(__function__ . '_commentstodisplay') ) , 'Nombre de commentaires à afficher' , $count , $widget_option_1 );
		div_close();

		form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Update' ) );
		
		span( array( 'id' => 'helpBlock' , 'class' => 'help-block' ) , 'Ce widget affiche les <i>x</i> derniers commentaires publiés sur votre site.' );
		form_close();

		return;
	endif;
	$comments = get_comments( array( 'orderby' => ' id ' , 'ob_suffix' => ' DESC ' , 'limit' => $widget_option_1	) );

	echo widget_title();

	$delimiter = call_layers('widget_last_comments_delimiter_layer',array( 'start' => '<div class="list-group">' , 'end' => '</div>' , 'start_inner' => '' , 'end_inner' => '' ,'class_inner' => 'list-group-item' ) ); 

	echo $delimiter['start'];
	while( $comments->next() ) : //
		$parent = new_content( array( 'id' => $comments->qpid() ) );
		$parent->next();
		echo $delimiter['start_inner'] . a( $parent->qlink() . '#comments' , $comments->qauthor() . ' "' . $comments->qsumup() . '"', array( 'class' => $delimiter['class_inner'] )  ) . $delimiter['end_inner'];
	endwhile;
	echo $delimiter['end'];

	$comments->free();

}


function widget_bloc_html(){
	$html_code = get_option( __function__.'_htmlcode' );

	if( action_edit_widget() ) :
		
		form_open(  array( 'class' => 'form-inline' ) );
		form_hidden( array( 'name' => 'edit-widget' , 'value' => __function__ ) );
		form_unsafe_submit();

		widget_title_form();

		div( array( 'class' => 'form-group' ) );
		form_textarea( array( 'name' => option(__function__.'_htmlcode' ) , 'class' => 'form-control' ) , 'Code HTML' , $html_code);
		div_close();

		form_submit( array( 'class' => 'btn btn-primary' , 'value' => 'Update' ) );
		form_close();

		return;
	endif;

	echo widget_title();

	echo $html_code;

}

