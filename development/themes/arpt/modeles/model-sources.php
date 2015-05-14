<?php
get_header();
?>

<div class="container">
	<div class="row page-header">
		<div class="col-md-8 content">
			<?php
			while( qnext() ) : ?>
				<h1><?php echo qtitle() ?></h1>
				<p>
					<?php echo qcontent(); ?>
				</p><?php
			endwhile; 
			?>

			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3"><?php

						$page_system_files = scandir( './' );
						?>
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>Fichiers Page Système</th>
								</tr>
							</thead>
							<tbody><?php
								foreach( $page_system_files as $file ) : 
									if( file_extension( $file ) != 'php' || $file == 'settings.php' ) continue;?>
								<tr>
									<td><?php echo a( get_site_url( 'tracks/' . $file ) , $file ); ?></td>
								</tr><?php
								endforeach; ?>

							</tbody>
						</table>
					</div>

					<div class="col-md-3"><?php
						$main_files = scandir( './sys/' );
						?>
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>Main Files / Classes Files</th>
								</tr>
							</thead>
							<tbody><?php
								foreach( $main_files as $file ) : 
									if( file_extension( $file ) != 'php' ) continue;?>
								<tr>
									<td><?php echo a( get_site_url( 'tracks/sys/' . $file ) , $file ); ?></td>
								</tr><?php
								endforeach; ?>

							</tbody>
						</table>
					</div>
					
					<div class="col-md-3"><?php
						$function_files = scandir( './sys/functions/' );
						?>
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>Functions Files / Classes Files Layer</th>
								</tr>
							</thead>
							<tbody><?php
								foreach( $function_files as $file ) : 
									if( file_extension( $file ) != 'php' ) continue;?>
								<tr>
									<td><?php echo a( get_site_url( 'tracks/sys/functions/' . $file ) , $file ); ?></td>
								</tr><?php
								endforeach; ?>

							</tbody>
						</table>
					</div>

					<div class="col-md-3"><?php
						$tool_files = scandir( './sys/tools/' );
						?>
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>Tools Files / Functions Files Layer</th>
								</tr>
							</thead>
							<tbody><?php
								foreach( $tool_files as $file ) : 
									if( file_extension( $file ) != 'php' ) continue;?>
								<tr>
									<td><?php echo a( get_site_url( 'tracks/sys/tools/' . $file ) , $file ); ?></td>
								</tr><?php
								endforeach; ?>

							</tbody>
						</table>
					</div>

				</div>
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