<?php

get_header();
set_queried( array( 'type' => 'article' ) );
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
			<?php
			while( qnext() ) : ?>
				<h2><?php echo qtitle() ?><span class="date"><?php echo 'Le ' . qdate(); ?></span></h2>
				<p class="contentdata">
					<?php echo 'Publié le ' . qdate() . ' par ' . qauthor() . '. Dans la catégorie ' . qcategory(); ?>
				</p>
				<p class="content">
					<?php echo qsumup(); ?>
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
</div>

<?php
get_footer();
?>