<?php get_header(); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header"> <?php 
                    if( is_authorpage() ) : ?>
                  	  Recherche sur l'auteur
                  	  <small>"<?php echo ucwords( get_pageargs(0) ); ?>"</small> <?php 
             	    else : ?>
						Recherche par date
						<small><?php echo implode( '/' , get_params() ); ?></small><?php
             		endif; ?>
                </h1>


                <?php

                if( qhas() ) {
	                while( qnext() ){
	                    ?>
	                    <h2><?php echo a( qlink() , qtitle() ); ?></h2>

	                    <p class="lead">
	                        par <?php echo qauthorlink(); ?>
	                    </p>

	                    <p><span class="glyphicon glyphicon-time"></span> Publié le <?php echo qdate(); ?></p>

	                    <hr>

	                    <?php
	                    if( has_miniature() ){
	                        echo '<img class="img-responsive" src="' . qminiature() .'" alt="Miniature">';
	                        echo '<hr>';
	                    }
	                    ?>

	                    <?php echo qcontent(); ?>

	                    <hr>
	                    <?php
	                }
	                qfree();
	            }else{
	            	echo '<p>Pas de résultat.</p>';
	            }
                ?>

                <hr>

                <!-- Pager -->
                <?php qpagination(); ?>

            </div>
            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">
                <?php load_part('menu-right'); ?>
           </div>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <?php get_footer(); ?>
        </footer>

    </div>
    <!-- /.container -->

</body>

</html>
