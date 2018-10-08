<!doctype html>

<html <?php language_attributes(); ?> class="no-js">

  <head>
    <meta charset="utf-8">

    <?php // force Internet Explorer to use the latest rendering engine available ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php // mobile meta (hooray!) ?>
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
    <!--[if IE]>
      <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <![endif]-->
    <?php // or, set /favicon.ico for IE10 win ?>
    <meta name="msapplication-TileColor" content="#f01d4f">
    <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
            <meta name="theme-color" content="#121212">

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php // wordpress head functions ?>
    <?php wp_head(); ?>
    <?php // end of wordpress head ?>

    <?php // drop Google Analytics Here ?>
    <?php // end analytics ?>

  </head>

  <body <?php body_class('common-wrapper'); ?> itemscope itemtype="https://schema.org/WebPage">
      <header role="banner" itemscope itemtype="https://schema.org/WPHeader">

        <nav class="navbar is-spaced is-primary section" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
          <div class="navbar-brand">
            <?php // to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> ?>
            <?php if(is_home()): ?>
            <h1 class="title navbar-item" itemscope itemtype="https://schema.org/Organization"><?php bloginfo('name'); ?></h1>
            <?php else: ?>
            <a class="title navbar-item" href="<?php echo home_url(); ?>" rel="nofollow"><h1 itemscope itemtype="https://schema.org/Organization"><?php bloginfo('name'); ?></h1></a>
            <?php endif; ?>
          </div>
          <?php // if you'd like to use the site description you can un-comment it below ?>
          <?php // bloginfo('description'); ?>


          <div class="navbar-menu">
            <?php wp_nav_menu(array(
                       'container' => false,                           // remove nav container
                       'container_class' => 'navbar-menu',                 // class of container (should you choose to use it)
                       'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
                       'menu_class' => 'navbar-start',               // adding custom nav class
                       'theme_location' => 'main-nav',                 // where it's located in the theme
                       'before' => '<li>',                                 // before the menu
                       'after' => '</li>',                                // after the menu
                       'link_before' => '',                          // before each link
                       'link_after' => '',                           // after each link
                       'depth' => 0,                                 // limit the depth of the nav
                       'fallback_cb' => ''                             // fallback function (if there is one)
            )); ?>
          </div>

        </nav>

      </header>
        <?php breadcrumb(); ?>
