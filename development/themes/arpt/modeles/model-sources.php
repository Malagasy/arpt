<?php
get_header();
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8">
			<?php
			while( qnext() ) : ?>
				<h1><?php echo qtitle() ?></h1>
				<p class="content">
					<?php echo qcontent(); ?>
				</p><?php
			endwhile; 
			?>
			
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