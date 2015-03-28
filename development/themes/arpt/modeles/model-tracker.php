<?php
if( !get_pageargs() || get_pagetype() == 'development' )
	redirect( get_url('sources') );

$path = './' . implode( '/' , get_pageargs() );

if( is_dir( $path ) || !file_exists( $path ) || $path == './settings.php' )
	redirect( get_url('sources') );

get_header();
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8"><?php

			$slug = do_slug( implode( '/' , get_pageargs() ) );
			set_queried( array( 'slug' => $slug ) );

			while( qnext() ) : ?>
				<h1><small><?php echo 'Fichiers : ' . qtitle() ?></small></h1>
				<p class="content">
					<?php echo qcontent(); ?>
				</p><?php
			endwhile; ?>
			<div class="pre_view_code_block">
				<span><?php
					$finfo = finfo_open( FILEINFO_NONE  );
					logr( finfo_file( $finfo , $path ) );
					?>
	
				</span>
				<pre class="view_code_block" style="font-size:11px"><code><?php echo trim( htmlspecialchars( file_get_contents( $path ) ) );	 ?></code></pre>
			</div>



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