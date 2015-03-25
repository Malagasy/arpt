<div class="well well-lg">
	<?php
	if( !visitor_can_comment() ) :
		echo 'Veuillez vous ' . a( get_signup_url() , 'connecter' ) . ', pour ajouter un commentaire.';	
	elseif( comments_actived() ) :

		$userid = is_user_online() ? get_currentuserid() : 0;

		form_open( array( 'method' => 'post' ) );

		echo '<h2>Laisser un commentaire <button type="submit" class="btn btn-primary">Valider</button></h2>';
		
		form_input( array('type' => 'hidden' , 'name' => 'content_type' , 'value' => 'commentaire' ));
		
		form_input( array('type' => 'hidden' , 'name' => 'redirect' , 'value' => get_clean_url('?posted=1')  ));

		form_input( array('type'=> 'hidden' , 'name' => 'userid' , 'value' => $userid ) );

		form_input( array('type'=> 'hidden' , 'name' => 'title' , 'value' => get_currentcontentname() . ' - ' . get_username( $userid ) ) );
			
		form_input( array( 'type' => 'hidden' , 'name' => 'parentid' , 'value' => get_currentcontentid() ) );
		
		form_textarea( array( 'name' => 'message' , 'rows' => 7, 'class' => 'form-control fusion-bottom' ) , null , last_value('message') );
		
		form_close();
	else :
		echo '<p>Les commentaires ne sont pas activés pour cette page.</p>';
	endif;
	$comments = queried_comments();
	if( $comments->qhas() ) :
		echo '<hr>';
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
						<?php if( $comments->qauthorid() == get_currentuserid() ) :
						echo get_delete_url( 'comment' , $comments->qid() , null , 'Supprimer mon commentaire' );
						endif; ?>
					</p>
			</div><?php
		endwhile;
	endif;
	?>
</div>

