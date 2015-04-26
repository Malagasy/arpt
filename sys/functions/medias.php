<?php

function upload_miniature( $cid , $files , $maxsize = null ){
	if( is_null( $maxsize) ) $maxsize = maxsize_upload_files();

	if( $files['size'] > $maxsize ) return false;
	elseif( !is_image( $files['tmp_name'] ) ) return false;
	
	if( ( $ext = is_image_mime( $files['type'] ) ) === false ) return false;

 	return move_uploaded_file( $files['tmp_name'] ,  get_upload_dir() . '/avatar-' . get_contentslug( $cid ) . $ext );

}

function is_image_mime( $mime ){

	if( $mime == "image/jpeg" ) return '.jpeg';
	elseif( $mime == "image/png" ) return '.png';
	else return false;
}
function add_format( $name , $width , $height ){
	global $format_miniatures;

	$format_miniatures[$name] = array( 'width' => $width , 'height' => $height );
}

function get_format( $name ){
	global $format_miniatures;
	if( isset( $format_miniatures[$name] ) )
		return $format_miniatures[$name];
	return;
}
function has_miniature( $type = 'content' , $id = null ){
	if( get_miniature( $type , $id ) == null )
		return false;
	return true;
}

function rm_miniature( $cid ){
	$c = new_content( $cid );
	$c->qnext();
	return unlink( $c->qminiature() );
}

function get_miniature( $type = 'content' , $id = null ){

	if( $id == null )
		if( is_queriablepage() )
			$id = qid();
		else
			return;

	if( $type == 'content' ) :
		$path = get_upload_dir() . '/avatar-' . get_contentslug( $id );

		if( $miniature = glob( $path . '.*') )
			return $miniature[0];

	endif;
	return;

}

function upload_medias( $files , $parentdir){
	foreach( $files as $file ) :
		if( $file['size'] > maxsize_upload_files() ) continue;
		move_uploaded_file( $file['tmp_name'] ,  get_upload_dir() . $parentdir . date("omd-Gi") . '-' . $file['name'] );
	endforeach;
	return true;
}
function js_rename_media( $folder , $name , $old_name ){
	if( $name == $old_name ) return;

	if( !file_exists( $folder . $old_name ) ) return ;

	echo rename( $folder . $old_name , $folder . $name );
}

function js_delete_media( $filename ){
	echo unlink( $filename );
}

function init_attachment_media_tinymce( $specificities , $label , $content ){
	if( !is_tinymceable( get_pageargs(0) ) ) return;
	echo '<p data-toggle="modal" data-target="#window-medias-' . $specificities['id'] . '" class="btn btn-default">Chercher un média</p>'; ?>
	<div class="modal fade" id="window-medias-<?php echo $specificities['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Sélectionner un média</h3>
					<div class="margin-bottom-10">
						<div class="btn-group">
							<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
							Action <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo get_admin_url('multimedia'); ?>" target="_blank">Uploader une image</a></li>
								<li><a href="#" class="refresh-medias">Rafraichir</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="modal-body modal-body-medialist">
					<?php
					window_display_all_medias();
					?>
				</div>
				<div class="modal-footer">
					<a class="btn btn-primary valid" data-textareaid="<?php echo $specificities['id'] ?>" data-dismiss="modal" href="#">Sélectionner</a>
					<a class="btn btn-default" data-dismiss="modal" href="#">Annuler</a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery("div.modal").on("click",".modal-footer a.valid",function(){
			var path = jQuery("img.selected").data("path");

			if( jQuery(this).data("textareaid") == '<?php echo $specificities["id"] ?>'){
				(tinyMCE.get('<?php echo $specificities["id"]; ?>')).setContent(tinyMCE.get("<?php echo $specificities['id']; ?>").getContent() + '<img src="' + path + '">');
			}
		});
		jQuery("a.refresh-medias").on("click",function(e){
			e.preventDefault();
			jQuery(".modal-body-medialist").html( phpajax("window_display_all_medias") );
		});
	</script>
	<?php
}
add_trigger( 'before_form_textarea' , 'init_attachment_media_tinymce' );

function window_display_all_medias(){
	$uploads = get_uploaded();
	$nbOfUploads = count( $uploads );
	$start = true;
	$col = 0;
	$key = -1;
	echo '<div class="container-fluid">';
	if( $uploads ) :
		foreach( $uploads as $upload ) :
			$col++;
			$key++;

			if( $start ) :
				echo '<div class="row">';
				$start = false;
			endif;
//	form_submit( array( 'class' => 'btn btn-link' , 'data-toggle' => 'modal' , 'data-target' => '#delete-directory' , 'value' => 'Supprimer le dossier') );
			echo '<div class="col-md-3">';
			if( is_image( $upload ) ) echo img( $upload , array( 'class' => 'img-responsive clickable' , 'data-path' => $upload ) );
			echo '</div>';
			if( $col == 4 || $key == $nbOfUploads - 1 ) :
				$start = true;
				echo '</div>';
			endif;


		endforeach;
	else :
		echo 'Pas de résultats.';
	endif;
	echo '</div>'; ?>
	<script type="text/javascript">
		jQuery("img.clickable").on("click",function(){
			jQuery("img.clickable").removeClass("selected");
			jQuery(this).addClass("selected");
		});

	</script>
	<?php
}