<?php get_header(); ?>

    <div class="container">

        <div class="row">

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

                <?php qpagination(); ?>

            </div>
            <div class="col-md-4">
                <?php load_part('menu-right'); ?>
           </div>

        </div>

        <hr>

        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
        </footer>

    </div>

</body>

</html>
