<!DOCTYPE html>
<html lang="FR">
	<head>

	<?php head(); ?>
	
	<title><?php echo sitetitle(); ?></title>

	</head>

	<body>
		<div class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a href="<?php echo get_site_url(); ?>" class="navbar-brand"><?php echo sitename(); ?></a>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
					<div class="navbar-collapse collapse" id="navbar-main">
						<?php 
						$header_links = get_navmenu_links( 'header' );
						if( $header_links ) :
						?>
						<ul class="nav navbar-nav">
						<?php foreach( $header_links as $page_id ) : ?>
						<?php $page = get_contents( $page_id ); ?>
						<?php $page->next(); ?>
						<?php $current_slugpage = get_pageargs(0); ?>
						<li>
							<?php echo $page->qtitlelink(); ?>
						</li>
						<?php endforeach; ?>
						<?php $page->free(); ?>
						</ul>
						<?php endif; ?>

					<form class="nav navbar-nav navbar-right navbar-form" method="POST" action="search/">
						<input type="hidden" name="doArequest">
						<div class="form-group">
							<input type="text" name="search" value="<?php echo last_value('search'); ?>" class="form-control" placeholder="Recherchez..">
						</div>
						<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</form>
				</div>
			</div>
		</div>