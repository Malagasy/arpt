<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
		<?php
		if( $count_result = get_queried()->total ) : ?>
			<?php 
			$search = last_value('search');
			if( $count_result > 1 )
				echo '<h1>' . $count_result . ' résultats pour votre recherche</h1>';
			else
				echo '<h1>' . $count_result . ' résultat pour votre recherche</h1>';
			div( array( 'class' => 'list-group' ) );
			while( qnext() ) :
				$link = content_link( qid() );
				echo a( $link , null , array( 'class' => 'list-group-item' ) );
				echo '<h4 class="list-group-item-heading"><u>' . bold_search( qtitle() , $search ) . '</u></h4>';
				echo '<p class="list-group-item-text">' . bold_search( qsumup() , $search ) . '</p>';
				echo '<small class="text-info">' . bold_search( $link , $search ) . '</small>';
				echo a_close();
			endwhile;
			div_close();
		else :
			echo '<h2>Votre recherche n\'a rien donné <small>):</small></h2>'; ?>
			<p>
				Vous avez maintenant les possibilités suivantes :
				<ul>
					<li>Revenir à la <a href="<?php echo get_home_url(); ?>">page d'accueil</a></li>
					<li>Réssayer la recherche à l'aide du formulaire ci-dessous.</li>
				</ul>
				<?php search_form(); ?>
			</p>
		<?php endif; ?>
		</div>
		<div class="col-md-4 top-buffer-40">
			<?php
			load('menus.php');
			?>
		</div>
	</div>

</div>

<?php
get_footer();
?>