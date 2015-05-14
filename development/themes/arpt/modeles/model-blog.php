<?php

get_header();
$articles = get_contents( array( 'type' => 'article' ) );
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
			<?php
			while( $articles->qnext() ) : ?>
				<h2><?php echo $articles->qtitle() ?><span class="date"><?php echo 'Le ' . $articles->qdate(); ?></span></h2>
				<p class="contentdata">
					<?php echo 'Publié le ' . $articles->qdate() . ' par ' . $articles->qauthor() . '. Dans la catégorie ' . a( $articles->qcategory() . '/' , $articles->qcategory() ); ?>
				</p>
				<?php
				if( $miniature = $articles->qminiature() ) : 
					echo img( $miniature , array( 'class' => 'img-responsive' ) );
				endif;
				?>
				<p>
					<?php echo $articles->qcontent(); ?>
				</p><?php
			endwhile;
			$articles->free();
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