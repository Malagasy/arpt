<div class="menu-right">
	<div class="small">
		<?php echo breadcrumb(); ?>
	</div>		
	<?php if( is_user_admin() && !is_errorpage() ) : ?>
	<div class="well well-lg">
		<?php
		echo 'Dernière modification le ' . qlastedit() . ' ';
		echo a( get_edit_content_url( qid() ) , '<span class="glyphicon glyphicon-edit"><span>' ); 
		?>  
	</div>
	<?php endif; ?>
	<div class="well well-lg">
		<?php
		echo '<a class="btn btn-lg btn-info btn-block" href="https://github.com/Malagasy/arpt/archive/master.zip"><span class="glyphicon glyphicon-download-alt"></span> ARpt v1.0.0 en ZIP</a>'; 
		?>  
	</div>
	<?php output_widgets( '<div class="well well-lg">' , '</div>' , array('1') ); ?>
</div>