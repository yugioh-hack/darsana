<?php
/* Welcome to Bones :)
This is the core Bones file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/bones/

  - head cleanup (remove rsd, uri links, junk css, ect)
  - enqueueing scripts & styles
  - theme support functions
  - custom menu output & fallbacks
  - related post function
  - page-navi function
  - removing <p> from around images
  - customizing the post excerpt

*/

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function bones_head_cleanup() {
  // category feeds
  // remove_action( 'wp_head', 'feed_links_extra', 3 );
  // post and comment feeds
  // remove_action( 'wp_head', 'feed_links', 2 );
  // EditURI link
  remove_action( 'wp_head', 'rsd_link' );
  // windows live writer
  remove_action( 'wp_head', 'wlwmanifest_link' );
  // previous link
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  // start link
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  // links for adjacent posts
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  // WP version
  remove_action( 'wp_head', 'wp_generator' );
  // remove WP version from css
  //add_filter( 'style_loader_src', 'bones_remove_wp_ver_css_js', 9999 );
  // remove Wp version from scripts
  //add_filter( 'script_loader_src', 'bones_remove_wp_ver_css_js', 9999 );

} /* end bones head cleanup */


  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
  }

  return $title;

// remove WP version from RSS
function bones_rss_version() { return ''; }

// remove WP version from scripts
function bones_remove_wp_ver_css_js( $src ) {
  if ( strpos( $src, 'ver=' ) )
    $src = remove_query_arg( 'ver', $src );
  return $src;
}

// remove injected CSS for recent comments widget
function bones_remove_wp_widget_recent_comments_style() {
  if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
    remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
  }
}

// remove injected CSS from recent comments widget
function bones_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
  }
}

// remove injected CSS from gallery
function bones_gallery_style($css) {
  return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function bones_scripts_and_styles() {

  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  if (!is_admin()) {

    // modernizr (without media query polyfill)
    wp_register_script( 'bones-modernizr', get_stylesheet_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );

    $fa5 = '//use.fontawesome.com/releases/v5.3.1/css/all.css';
    wp_register_style( 'fa5css', $fa5 , array(), '', 'all' );
    // register main stylesheet
    //wp_register_style( 'bones-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );
    $shard5 = '/library/shard5/css/shard5.css';
    wp_register_style( 'shard-stylesheet', get_stylesheet_directory_uri() . $shard5, array(), date("YmdHi", filemtime( get_stylesheet_directory(). $shard5 ) ), 'all' );

    // ie-only style sheet
    wp_register_style( 'bones-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

    // comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
      wp_enqueue_script( 'comment-reply' );
    }

    //adding scripts file in the footer
    wp_register_script( 'bones-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array( 'jquery' ), '', true );

    wp_register_script( 'googlePlus', '//apis.google.com/js/plusone.js',array(),'', true );

    //https://apis.google.com/js/platform.js
    // jQueryをcdnから読み込む
    if(!is_admin()) {
      wp_deregister_script('jquery'); // 既存のjQueryを排除
      if( $is_IE ) {
        wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), '',true);
      }
      else {
        wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), '',true);
      }
    }
    // enqueue styles and scripts
    wp_enqueue_script( 'bones-modernizr' );
    wp_enqueue_script( 'googlePlus' );
    //wp_enqueue_style( 'bones-stylesheet' );
    wp_enqueue_style( 'shard-stylesheet' );
    wp_enqueue_style( 'bones-ie-only' );
    wp_enqueue_style( 'fa5css' );
    // 現状、integrityやconditionalはadd_dateで入れられないので今は削除
    //wp_style_add_data( 'fa5css', 'integrity','sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU');
    //wp_style_add_data( 'fa5css', 'crossorigin', 'anonymous' );

    $wp_styles->add_data( 'bones-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet

    /*
    I recommend using a plugin to call jQuery
    using the google cdn. That way it stays cached
    and your site will load faster.
    */
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bones-js' );

  }
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function bones_theme_support() {
  // タイトルタグを出力する WP4.1+
  add_theme_support( 'title-tag' );

  // wp thumbnails (sizes handled in functions.php)
  add_theme_support( 'post-thumbnails' );

  // default thumb size
  set_post_thumbnail_size(125, 125, true);

  // wp custom background (thx to @bransonwerner for update)
  add_theme_support( 'custom-background',
      array(
      'default-image' => '',    // background image default
      'default-color' => '',    // background color default (dont add the #)
      'wp-head-callback' => '_custom_background_cb',
      'admin-head-callback' => '',
      'admin-preview-callback' => ''
      )
  );

  // rss thingy
  add_theme_support('automatic-feed-links');

  // to add header image support go here: http://themble.com/support/adding-header-background-image-support/

  // adding post format support
  add_theme_support( 'post-formats',
    array(
      'aside',             // title less blurb
      'gallery',           // gallery of images
      'link',              // quick link to other site
      'image',             // an image
      'quote',             // a quick quote
      'status',            // a Facebook like status update
      'video',             // video
      'audio',             // audio
      'chat'               // chat transcript
    )
  );

  // wp menus
  add_theme_support( 'menus' );

  // registering wp3+ menus
  register_nav_menus(
    array(
      'main-nav' => __( 'The Main Menu', 'bonestheme' ),   // main nav in header
      'footer-links' => __( 'Footer Links', 'bonestheme' ) // secondary nav in footer
    )
  );

  // Enable support for HTML5 markup.
  add_theme_support( 'html5', array(
    'comment-list',
    'search-form',
    'comment-form'
  ) );

  add_theme_support( 'custom-header',array(
    'default-image' => '',
    'random-default' => false,
    'width' => 0,
    'height' => 0,
    'flex-height' => false,
    'flex-width' => false,
    'default-text-color' => '',
    'header-text' => true,
    'uploads' => true,
    'wp-head-callback' => '',
    'admin-head-callback' => '',
    'admin-preview-callback' => '',
    'video' => false,
    'video-active-callback' => 'is_front_page',
  ));

} /* end bones theme support */


/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using bones_related_posts(); )
function bones_related_posts() {
  echo '<ul id="bones-related-posts">';
  global $post;
  $tags = wp_get_post_tags( $post->ID );
  if($tags) {
    foreach( $tags as $tag ) {
      $tag_arr .= $tag->slug . ',';
    }
    $args = array(
      'tag' => $tag_arr,
      'numberposts' => 5, /* you can change this to show more */
      'post__not_in' => array($post->ID)
    );
    $related_posts = get_posts( $args );
    if($related_posts) {
      foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
        <li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
      <?php endforeach; }
    else { ?>
      <?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'bonestheme' ) . '</li>'; ?>
    <?php }
  }
  wp_reset_postdata();
  echo '</ul>';
} /* end bones related posts function */

/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function bones_page_navi() {
  global $wp_query;
  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;
  echo '<nav class="pagination">';
  echo '<h2 class="screen-reader-text">Pagination</h2>';
  echo paginate_links( array(
    'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    'format'       => '',
    'current'      => max( 1, get_query_var('paged') ),
    'total'        => $wp_query->max_num_pages,
    'prev_text'    => '&larr;',
    'next_text'    => '&rarr;',
    'type'         => 'list',
    'end_size'     => 3,
    'mid_size'     => 3
  ) );
  echo '</nav>';
}

function custom_page_navi() {

  global $wp_query;
  $bignum = PHP_INT_MAX;
  $current = max(1,absint(get_query_var('paged')) );
  $pagination = paginate_links( array(
      'base'   => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
      'format' => '?paged=%#%',
      'current' => $current,
      'total'   => $wp_query->max_num_pages,
      'type'    => 'array',
      'prev_text' => '&laquo;',
      'next_text' => '&raquo;',
    )
  );

  echo '<div class="columns">';
  echo '<div class="column">';
  echo '<nav class="pagination is-centered">';
  echo '<h2 class="screen-reader-text">Pagination</h2>';

  if(!empty( $pagination )):
    echo '<ul class="pagination-list">';
    foreach($pagination as $key => $page_link) {
      echo '<li class="pagination-link';
      if(strpos($page_link,'current') !== false)
        echo ' active';
      echo '">'.$page_link.'</li>';
    }
    echo '</ul>';
  endif;

  echo '</nav>';
  echo '</div>';
  echo '</div>';
}

/* end page navi */

/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function bones_filter_ptags_on_images($content){
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function bones_excerpt_more($more) {
  global $post;
  // edit here if you like
  return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'bonestheme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'bonestheme' ) .'</a>';
}



?>
