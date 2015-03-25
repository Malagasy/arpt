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
			<form class="form-horizontal">
				<fieldset>
					<legend>Formulaire de contact</legend>
					<div class="form-group">
						<label for="inputEmail" class="col-lg-2 control-label">Email</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" name="email" id="inputEmail" placeholder="toto@dudule.com">
						</div>
					</div>
					<div class="form-group">
						<label for="inputSubject" class="col-lg-2 control-label">Objet</label>
						<div class="col-lg-10">
							<input type="text" class="form-control" name="subject" id="inputSubject" placeholder="Signaler un bug...">
						</div>
					</div>
					<div class="form-group">
						<label for="corps" class="col-lg-2 control-label">Message</label>
						<div class="col-lg-10">
							<textarea class="form-control" rows="7" id="corps"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-10 col-lg-offset-2">
							<button type="submit" class="btn btn-primary">Envoyer</button>
						</div>
					</div>
				</fieldset>
			</form>
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