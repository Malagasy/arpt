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
			<form class="form-horizontal" method="post" id="form_contact">
				<fieldset>
					<legend>Formulaire de contact</legend>
					<div class="form-group">
						<label for="contact_email" class="col-lg-4 control-label">Votre adresse e-mail</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="contact_email" id="contact_email" placeholder="toto@gmail.com">
						</div>
					</div>
					<div class="form-group">
						<label for="contact_sujet" class="col-lg-4 control-label">Sujet</label>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="contact_sujet" id="contact_sujet" placeholder="Signaler..">
						</div>
					</div>
					<div class="form-group">
						<label for="contact_message" class="col-lg-4 control-label">Votre message</label>
						<div class="col-lg-8">
							<textarea class="form-control" name="contact_message" id="contact_message" rows="7"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-8 col-lg-offset-4">

							<button type="cancel" class="btn btn-default btn-cancel">Annuler</button>
							<button type="submit" class="btn btn-primary btn-submit">Envoyer</button>
						</div>
					</div>
				</fieldset>
			</form>
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