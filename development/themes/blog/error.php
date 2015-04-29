<?php get_header(); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    Cette page est introuvable
                    <small>:(</small>
                </h1>

				<p>
					La page que vous avez demandé est introuvable. Elle a peut être été déplacée ou supprimée. Vous pouvez essayer de la retrouver en utilisant le moteu rde recherche.
				</p>

				<?php search_form(); ?>

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
