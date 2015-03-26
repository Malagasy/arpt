<?php
if( !get_pageargs() )
	redirect( get_url('sources') );

get_header();
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8"><?php

			$slug = do_slug( implode( '/' , get_pageargs() ) );
			set_queried( array( 'slug' => $slug ) );

			while( qnext() ) : ?>
				<h1><?php echo qtitle() ?></h1>
				<p class="content">
					<?php echo qcontent(); ?>
				</p><?php
			endwhile; ?>
			<div class="view_code_block">
					<textarea class="view_code_block form-control"><?php echo trim( htmlspecialchars( file_get_contents( './' . qtitle() ) ) );	 ?>
					</textarea>
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