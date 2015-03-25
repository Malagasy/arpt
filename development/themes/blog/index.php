<?php get_header(); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    <?php echo sitename(); ?>
                    <small><?php echo description(); ?></small>
                </h1>


                <?php
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
                    <p class="text-right">
                        <small>
                            <?php
                            $count = qcommentscount();
                            $string = $count > 1 ? 'commentaires' : 'commentaire';
                            echo a( qlink() , $count . ' ' . $string );
                            ?>
                        </small>
                    </p>
                    <hr>
                    <?php
                }
                qfree();
                ?>

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
