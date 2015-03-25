<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content"><?php
			qnext(); ?>
			<h1> <?php echo qtitle(); ?> </h1>
			<p>
				<?php echo qcontent(); ?>
			</p>
		
			<p class="content-bottom top-buffer-40">
				<?php 
				echo '<i>' . description() . '</i>';
				?>
			</p>
		</div>
		<?php qfree(); ?>

		<div class="col-md-4 top-buffer-40">
		<?php load('menus.php'); ?>
		</div>
	</div>

	<div class="row" id="comments">
		<?php load_part( 'commentaires' ); ?>
	</div>

</div>
<?php
get_footer();
?>