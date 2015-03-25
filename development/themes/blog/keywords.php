<?php get_header(); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    Recherche sur mot clé
                    <small>"<?php echo get_pageargs(0); ?>"</small>
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
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

</body>

</html>
