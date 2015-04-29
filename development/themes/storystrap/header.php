<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo sitedescription(); ?>">

    <title><?php echo sitetitle(); ?></title>

	<?php head(); ?>

</head>

<body>
    <header class="navbar navbar-bright navbar-fixed-top" role="banner">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="<?php echo get_site_url(); ?>" class="navbar-brand"><?php echo sitename(); ?></a>
        </div>
        <nav class="collapse navbar-collapse" role="navigation">
           <?php 
            $header = get_navmenu_links('header');

            if( $header ){ ?>
                <ul class="nav navbar-nav">
                    <?php
                foreach( $header as $id ){
                    $page = get_contents( array( 'id' => $id ) );
                    $page->next(); ?>
                    <li><a href="<?php echo $page->qlink() ?>"><?php echo $page->qtitle() ?></a></li>
                    <?php
                    $page->free();
                } ?>
                </ul>
                <?php
            }
            ?>
        </nav>
      </div>
    </header>

    <div id="masthead">  
      <div class="container">
        <div class="row">
          <div class="col-md-7">
            <h1><?php echo sitename(); ?>
              <p class="lead"><?php echo description(); ?></p>
            </h1>
          </div>
          <div class="col-md-5">
            <div class="well well-lg"> 
              <div class="row">
                <div class="col-sm-12">
                    Ce bloc est libre, vous pouvez y mettre ce que vous voulez dedans. Par exemple vos coordonnées personnelles si vous souhaitez être contactés.
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div><!-- /cont -->
      
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="top-spacer"> </div>
          </div>
        </div> 
      </div><!-- /cont -->
      
    </div>
