<?php
get_header();
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
			<?php
			while( qnext() ) : ?>
				<h1><?php echo qtitle() ?></h1>
				<p>
					<?php echo qcontent(); ?>
				</p><?php
			endwhile; ?>

			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<h4>Concepts et notions</h4>
					<?php
					$childs = get_contentchilds( array( 'category' => 'concept' ) );
					if( $childs->qhas() ){
						echo '<div class="list-group">';
						while( $childs->next() ){
							echo $childs->qtitlelink( array( 'class' => 'list-group-item' ) );
						}
						echo '</div>';
					}
					$childs->free();
					?>
					</div>

					<div class="col-md-6">
						<h4>Exemple pratique</h4>
					<?php
					$childs = get_contentchilds( array( 'category' => 'pratique' ) );
					if( $childs->qhas() ){
						echo '<div clas="list-group">';
						while( $childs->next() ){
							echo $childs->qtitlelink( array( 'class' => 'list-group-item' ) );
						}
						echo '</div>';
					}
					?>
					</div>
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
</div>
<?php
get_footer();
?>