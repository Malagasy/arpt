<?php get_header(); ?>

<div class="container">

    <div class="row">

        <div class="col-md-12"> 
          
          <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 storystrap-body">
                        <h1 class="page-header">Cette page est introuvable <small>):</small></h1>

                        
                        <p>
                            La page que vous avez demandé est introuvable. Elle a peut être été déplacée ou supprimée. Vous pouvez essayer de la retrouver en utilisant le moteu rde recherche.
                        </p>

                        <?php search_form(); ?>

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
