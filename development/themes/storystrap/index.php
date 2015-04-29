<?php get_header(); ?>

<div class="container">

    <div class="row">

        <div class="col-md-12"> 
          
          <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 storystrap-body">
                    <?php
                    while( qnext() ){
                        ?>
                        <h2><?php echo a( qlink() , qtitle() ); ?><br><small> par <?php echo qauthorlink(); ?> </small> </h2>

                        <p><span class="glyphicon glyphicon-time"></span> Publié le <?php echo qdate(); ?></p>

                        
                        <?php echo qcontent(); ?>

                        <small>
                            <?php
                            $count = qcommentscount();
                            $string = $count > 1 ? 'commentaires' : 'commentaire';
                            echo a( qlink() , $count . ' ' . $string );
                            ?>
                        </small>
                        <hr>
                        <?php
                    } ?>

                    <?php qfree(); ?>

                    <?php qpagination(); ?>

                    </div>
                    <div class="col-md-4">
                        <?php load_part( 'menu-right' ); ?>
                    </div>
                </div>

            </div>
         </div>

        </div><!-- col-md-12 -->
    </div>
</div>

    <hr>

    <footer>
        <?php get_footer(); ?>
    </footer>

</body>

</html>
