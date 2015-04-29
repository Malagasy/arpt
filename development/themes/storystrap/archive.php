<?php get_header(); ?>

<div class="container">

    <div class="row">

        <div class="col-md-12"> 
          
          <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 storystrap-body">
                        <h1 class="page-header">Page des archives</h1>
                        <div class="well well-lg">
                        <?php while( qnext() ) : ?>

                            <h3> <?php echo qtitlelink(); ?> <br> <small> <span class="glyphicon glyphicon-time"></span> <?php echo qdate(); ?> • <?php echo qauthorlink(); ?> </small> </h3>
                        
                        <?php endwhile; ?>
                        </div>

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
