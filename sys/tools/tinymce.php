<?php
function init_tinymce(){ 
	if( !is_tinymceable( get_pageargs(0) ) ) return;
	?>
	<script type="text/javascript">
		tinymce.init({
	    selector: "textarea",
	    plugins : 'link image lists media code hr table',
	    toolbar: 
	    		"undo redo | styleselect formatselect fontselect | bold italic underline | alignleft aligncenter alignright | bullist numlist  | hr blockquote outdent indent | table link image media | preview autolink | code ",
	    tools: "interttable",
        visual_table_class: "table table-hover table-bordered table-tinymce",
	    menubar: false,
	 	statusbar : false,
	 	height: 450,
	 	entity_encoding : "raw",
	 	<?php
	 	if( get_base_var() ) : ?>
	 		document_base_url: <?php echo get_base_var('/');
	 	else : ?>
	 		document_base_url: "/"<?php
	 	endif; ?>
	 });
	</script>
	<?php
}
add_trigger('admin_footer_script_trigger','init_tinymce');

function make_tinymceable( $page_arg ){
	global $arpt;
	$conf = $arpt->get_entity( 'tinymce_conf' );

	if( !isset($conf['allows'] ) )
		$conf['allows'] = new Chain;

	$conf['allows']->add( $page_arg );

	return $arpt->set_entity( 'tinymce_conf' , $conf );

}

function is_tinymceable( $page_arg ){
	global $arpt;
	$conf = $arpt->get_entity( 'tinymce_conf' );

	return $conf['allows']->check( $page_arg );
}