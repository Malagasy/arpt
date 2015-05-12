<div class="well well-lg">
	<?php
	if( !visitor_can_comment() ) :
		echo 'Veuillez vous ' . a( get_signup_url() , 'connecter' ) . ', pour ajouter un commentaire.';	
	elseif( comments_actived() ) :
		echo '<h2>Laisser un commentaire</h2>';
		comments_form(); 
		echo '<hr/>';
	else :
		echo '<p>Les commentaires ne sont pas activés pour cette page.</p>';
	endif;
	$comments = queried_comments();
	while( $comments->next() ) : ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<p class="pull-right">
					<?php echo 'Il y a ' . timeAgoInWords( $comments->qdate_notformated() ); ?>
				</p>
				<?php echo $comments->qauthor(); ?>
			</div>
			<div class="panel-body">
				<samp><?php echo $comments->qcontent() ?></samp>
			</div>
				<p class="pull-right">
					<?php if( $comments->qauthorid() == get_currentuserid() || is_user_admin() ) :
					echo get_delete_url( 'comment' , $comments->qid() , null , 'Supprimer mon commentaire' );
					endif; ?>
				</p>
		</div><?php
	endwhile;
	?>
</div>

