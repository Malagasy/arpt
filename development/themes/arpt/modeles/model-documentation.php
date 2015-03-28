<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
		<?php
		if( get_queried()->total > 1 ) :
			echo '<h1>Où souhaitez-vous aller ?</h1>';
			div( array( 'class' => 'list-group' ) );
			while( qnext() ) :
				echo a( content_link( qid() ) , null , array( 'class' => 'list-group-item' ) );
				echo '<h4 class="list-group-item-heading">' . qtitle() . '</h4>';
				echo '<p class="list-group-item-text">' . qsumup() . '</p>';
				echo a_close();
			endwhile;
			div_close();
			div_close(); /* row page-heder ends */
		else : ?>
		<h1> <?php echo qtitle(); ?> </h1>

		<p>
			<?php echo qcontent(); ?>
		</p>
		<hr>
		<div class="row">
			<div class="col-md-4">
				<h4>Les classes ARpt</h4>
				
				<?php
					$documentation_classes = get_contents( array( 'type' => 'documentation' , 'category' => 'classe' , 'orderby' => 'content_title' , 'ob_suffix' => 'ASC') );
				?>
				<div class="list-group">
				<?php while( $documentation_classes->next() ) : ?>
					<a href="<?php echo $documentation_classes->qlink(); ?>" class="btn btn-link">
						<?php echo $documentation_classes->qtitle(); ?>
					</a>
				<?php endwhile; ?>
				</div>
				<h4>Famille de fonctions</h4>
				<div class="list-group">
					<?php
					$doc_fonctions_famille = get_contents( array( 'type' => 'documentation' , 'category' => 'famille-fonctions' ) );
					while( $doc_fonctions_famille->next() ) :
						echo a( $doc_fonctions_famille->qlink() , $doc_fonctions_famille->qtitle() , array( 'class' => 'btn btn-link' )); 
					endwhile; ?>
				</div>
			</div>
			<div class="col-md-8">
				<?php
				$fonctions = new_content( array( 'type' => 'fonction' , 'limit' => 50 ) );
				$i = 0;
				?>
				<h4>Dernières fonctions documentées..</h4>
				<table class="table">
					<thead></thead>
					<tbody>
						<?php
						while( $fonctions->next() ) :
							$i++;
							if( $i == 1 ) :
								echo '<tr>';
							endif;

							echo '<td>' . $fonctions->qtitlelink() . '</td>';

							if( $i == 3 ) :
								echo '</tr>';
								$i = 0;
							endif;

						endwhile;

						?>
					</tbody>	
				</table>

			</div>

		</div>

		<p class="content-bottom top-buffer-40">
			<?php 
			echo '<i>' . siteslogan() . '</i>';
			?>
		</p>
		</div>
		<div class="col-md-4 top-buffer-40">
			<?php
			load('menus.php');
			?>
		</div>
	</div>

	<div class="row" id="comments">
		<?php load_part( 'commentaires' ); ?>
	</div>

	<?php endif; ?>
</div>
<?php
get_footer();
?>