<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<?php
		if( $count_result = get_queried()->total ) : ?>
			<?php 
			if( $count_result > 1 )
				echo '<h1>' . $count_result . ' posts associés au mot clé ' . ucwords( undo_slug( get_pageargs(0) ) ) . '</h1>';
			else
				echo '<h1>' . $count_result . ' post associé au mot clé ' . ucwords( undo_slug( get_pageargs(0) ) ) . '</h1>';
			div( array( 'class' => 'list-group' ) );
			while( qnext() ) :
				$link = content_link( qid() ) . '/';
				echo a( $link , null , array( 'class' => 'list-group-item' ) );
				echo '<h4 class="list-group-item-heading"><u>' . qtitle()  . '</u></h4>';
				echo '<p class="list-group-item-text">' . qsumup(). '</p>';
				echo '<small class="text-info">' . $link . '</small>';
				echo a_close();
			endwhile;
			div_close();
		else :
			echo '<h1>Aucun post n\'est associé à ce mot clé</h1>';
		endif; ?>
	</div>
</div>
<?php
get_footer();
?>