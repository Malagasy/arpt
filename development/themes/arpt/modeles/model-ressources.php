<?php
get_header();
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
			<?php
			while( qnext() ) : ?>
				<h1><?php echo qtitle() ?></h1>
				<p class="content">
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
						echo '<ul>';
						while( $childs->next() ){
							echo '<li>' . $childs->qtitlelink() . '</li>';
						}
						echo '</ul>';
					}
					?>
					</div>

					<div class="col-md-6">
						<h4>Exemple pratique</h4>
					<?php
					$childs = get_contentchilds( array( 'category' => 'technique' ) );
					if( $childs->qhas() ){
						echo '<ul>';
						while( $childs->next() ){
							echo '<li>' . $childs->qtitlelink() . '</li>';
						}
						echo '</ul>';
					}
					?>
					</div>
				</div>
			</div>
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