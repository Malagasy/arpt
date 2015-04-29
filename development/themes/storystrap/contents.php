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
                        <h1 class="page-header"><?php echo a( qlink() , qtitle() ); ?></h1>

                        <p class="lead"><span class="glyphicon glyphicon-time"></span> Publié le <?php echo qdate(); ?> par <?php echo qauthorlink(); ?></p>

                        
                        <?php echo qcontent(); ?>

                        <?php
                    } ?>

                    <?php load_part('commentaires'); ?>

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
