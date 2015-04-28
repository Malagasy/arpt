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
