<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
		<?php
		if( get_queried()->total > 1) :
			echo '<h1>Où souhaitez-vous aller ?</h1>';
			div( array( 'class' => 'list-group' ) );
			while( qnext() ) :
				echo a( content_link( qid() ) , null , array( 'class' => 'list-group-item' ) );
				echo '<h4 class="list-group-item-heading">' . qtitle() . '</h4>';
				echo '<p class="list-group-item-text">' . qsumup() . '</p>';
				echo a_close();
			endwhile;
			div_close();
		else :
			qnext(); ?>
			<h1> <?php echo qtitle(); ?> </h1>
			<p>
				<?php echo qcontent(); ?>
			</p><?php
		endif;
		?>
		
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