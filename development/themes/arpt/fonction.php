<?php

get_header();

?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content"><?php
			$prototype = qproperty( 'prototype' );
			$return = qproperty( 'return' );
			$example = qproperty( 'example' );
			?>
			<h1> Fonction &laquo; <?php echo qtitle(); ?> </h1>
			<div class="modal fade" id="ask" tabindex="-1" role="dialog" aria-labelledby="Faire une demande" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Demander des renseignements</h4>
						</div>
						<div class="modal-body">
							<p>
								Utilisez ce champ pour obtenir des informations sur un sujet spécifique.
							</p>
							<textarea name="reason_ask" class="form-control"></textarea>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary">Valider</button>
						</div>
					</div>
				</div>
			</div>
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