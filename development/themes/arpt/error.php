<?php

get_header();
echo get_pagetype();
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
		<h1>La page est introuvable</h1>
		<p>
			Désolé, cette page n'existe pas ou a peut être été modifié.
		</p>
		<p>
			Vous avez maintenant les possibilités suivantes :
			<ul>
				<li>Revenir à la <a href="<?php echo get_home_url(); ?>">page d'accueil</a></li>
				<li><a href="<?php echo content_link('contact'); ?>">Me contacter pour signaler un lien inactif</a></li>
				<li>Faire une recherche à l'aide du moteur de recherche ci-dessous</li>
			</ul>
			<?php search_form(); ?>
		</p>
		
		<p class="content-bottom top-buffer-40">
			<?php 
			echo '<i>' . description() . '</i>';
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