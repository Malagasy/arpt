<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
			<?php
			$prototype = qproperty( 'prototype' );
			$return = qproperty( 'return' );
			$example = qproperty( 'example' );
			$c = strlen( $prototype ) + strlen( $return ) + strlen( $example );

			if( $c < 800 ){ ?>
				<div class="panel panel-default">
					Cette page ne semble pas assez complète. Si vous souhaite des informations supplémentaires n'hésitez pas à faire une demande.
				</div>
			<?php
			}

			?>
		<h1> Fonction - <?php echo qtitle(); ?> </h1>
		<p>
			<?php echo qcontent(); ?>
		</p>
		<p>
		<?php if( $prototype ) : ?>
			<h3>Prototype</h3>
			<?php echo $prototype; ?>
		<?php endif; ?>
		</p>
		<p>
		<?php if( $return ) : ?>
			<h3>Valeurs de retour</h3>
			<?php echo $return; ?>
		<?php endif; ?>
		</p>

		<p>
		<?php if( $example ) : ?>
			<h3>Cas d'utilisation</h3>
			<?php echo $example; ?>
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