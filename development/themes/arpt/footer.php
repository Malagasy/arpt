<hr>
<footer class="text-center">
	<div class="container-fluid">
		<div class="row">
			<ul class="list-inline">
				<li>ARpt v1.0</li>
				<?php 
				$footer_links = get_navmenu_links( 'footer' );
				foreach( $footer_links as $page_id ) : 
				$page = get_contents( $page_id );
				$page->next();
				$current_slugpage = get_pageargs(0);
				?>
				<li><?php echo $page->qtitlelink(); ?></li>
				<?php
				endforeach;
				$page->free(); 
				?>
			</ul>
		</div>
		<div class="row">
			©2015 ARpt, un CMS simple et évolutif. Développé par Andy RAL | Propulsé par ARpt
		</div>
	</div>
</footer>