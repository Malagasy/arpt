<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
		<h1> Fonction - <?php echo qtitle(); ?> </h1>
		<p>
			<?php echo qcontent(); ?>
		</p>
		<p>
		<?php if( $party = qproperty( 'prototype' ) ) : ?>
			<h2>Prototype</h2>
			<?php echo $party; ?>
		<?php endif; ?>
		</p>
		<p>
		<?php if( $party = qproperty( 'return' ) ) : ?>
			<h2>Valeurs de retour</h2>
			<?php echo $party; ?>
		<?php endif; ?>
		</p>

		<p>
		<?php if( $party = qproperty( 'example' ) ) : ?>
			<h2>Cas d'utilisation</h2>
			<?php echo $party; ?>
		<?php endif; ?>
		</p>
		
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

	<div class="row">
		<?php load_part( 'commentaires' ); ?>
	</div>
</div>

<?php
get_footer();
?>