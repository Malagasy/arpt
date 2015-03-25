<?php get_header(); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            	<?php 
            	qnext();
            	?>

                <h1 class="page-header">
                	Vous êtes entrain de lire
                    <small><?php echo qtitle(); ?></small>
                </h1>

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
                <p>
                    <a href="<?php echo get_edit_content_url(); ?>">Editer ce contenu</a>
                </p>
                <hr>
                
                <?php
                qfree();

                load_part('commentaires');
                ?>

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
