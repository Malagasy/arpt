<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
			<h1> <?php echo qtitle(); ?><span class="date"><?php echo 'Le ' . qdate(); ?></span></h1>
			<?php
			if( $miniature = qminiature() ) : 
				echo img( $miniature , array( 'class' => 'thumbnail pull-right' , 'style' => 'width:300px' ) );
			endif;
			?>
			<p>
				<?php echo qcontent(); ?>
			</p>
		
		<p class="content-bottom top-buffer-40">
			<?php 
			echo '<i>' . siteslogan() . '</i>';
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