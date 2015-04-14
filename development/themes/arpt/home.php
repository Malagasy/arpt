<?php

get_header();

?>
<div class="jumbotron">
	<div class="container">
		<h1>ARpt, un CMS simple et évolutif</h1>
		<p>
			ARpt est un CMS simple et OpenSource. Il permet aux utilisateurs de réaliser leur site de manitère intuitive et personnalisable tout en offrant aux développeurs la capacité d'ajouter de nouvelles fonctionnalités.	
		</p>
		<p><a class="btn btn-primary btn-lg more-info" href="#" role="button">Plus d'infos <span class="glyphicon glyphicon-triangle-right"></span></a></p>
		<div class="more-info-next container-fluid" style="display:none">
			<div class="row">
				<div class="col-md-9">
					<p><span class="glyphicon glyphicon-triangle-right"></span>ARpt permet de gérer et d'organiser votre site à l'aide d'une interface dédiée<br>
					<span class="glyphicon glyphicon-triangle-right"></span>ARpt fournit un ensemble d'outils de gestion pour votre site<br>
					<span class="glyphicon glyphicon-triangle-right"></span>ARpt s'installe en 30 secondes</p>
				</ul>
				</div>
				<div class="col-md-3">
					<div class="more-info-buttons" style="display:none">
						<a class="btn btn-info btn-lg" href="a-propos/" role="button">Qui suis-je ?</a>
						<a class="btn btn-primary btn-lg" href="https://github.com/Malagasy/arpt/archive/master.zip" role="button">Télécharger</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="well well-lg">
		<div class="row">
			<div class="col-md-9">
				<h2>
					Un petit CMS français
				</h2>
				<p class="lead">
					ARpt est un système de gestion de contenus simple. Il permet à un utilisateur de créer son site simplement et rapidement.
				</p>
				<p>
					Le CMS se veut le plus souple possible afin d'offrir aux développeurs une grande liberté d'action.
				</p>
				<p>
					Afin de comprendre le fonctionnement du CMS jetez un oeil <a href="/prise-en-main">au guide de prise en main</a> ou à la documentation en ligne pour des informations plus précises. Le blog relate l'avancement du CMS, les correctifs en cours et ceux qui ont été corrigés.
				</p>
				<p>
					<b>Pour ceux qui n'ont jamais développé sur un CMS.</b> ARpt est un bon choix, découvrez une nouvelle façon de développer, relativement proche de CMS très puissants comme Wordpress.

				</p>
			</div>
			<div class="col-md-3">

				<img src="<?php echo get_upload_dir() . '/arpt-logo.png'; ?>" class="img-responsive"/>
				<a href="https://github.com/Malagasy/arpt/archive/master.zip" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-download-alt"></span> Télécharger ARpt v1.0</a>-
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h2>Installation</h2>
				<ol>
					<li>Téléchargez et décompressez ARpt en cliquant sur le lien ci-contre.</li>
					<li>Placez ARpt à la racine de votre serveur Web. En utilisant un environnement de développement (par exemple, WAMP) il vous suffira de le placer sur la racine du serveur local ( <i>/www/ sur WAMP</i> ).</li>
					<li>Renommez le fichier <b>settings-example.php</b> en <b>settings.php</b> et ouvrez-le avec votre éditeur préféré. Renseignez vos informations de connexion serveur :
						<ul>
							<li><b>define('MYSQLI_LOCALHOST' , 'YourHost');</b> : remplacer <i>YourHost</i> par le nom du serveur Web ( sur un serveur Web local il s'agit de 'localhost' )</li>
							<li>Les 3 lignes qui suivent requièrent le <b>nom de l'utilisateur</b>, le <b>mot de passe de connexion</b> et <b>le nom de la base de données</b>.</li>
						</ul>
						<i>Les identifiants doivent être entourés de quote</i>.
					</li>
					<li>Créez une base de donnée vierge portant le même nom que le nom <b>YourDatabase</b> choisi plus haut.</li>
					<li>Rechercher l'adresse de votre site depuis votre navigateur... ARpt est prêt à l'emploi.</li>
				</ol>
				<p>
					Bonne utilisation ! :-)
				</p>
			</div>

			</div>
		</div>
	</div>
</div>
<?php
get_footer();
?>