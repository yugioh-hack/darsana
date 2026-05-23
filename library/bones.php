<?php
/* Welcome to Bones :)
This is the core Bones file where most of the
main functions & features reside.
*/

/*********************
WP_HEAD GOODNESS
*********************/

function bones_head_cleanup() {
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
} /* end bones head cleanup */

// remove WP version from RSS
function bones_rss_version() { return ''; }

// remove WP version from scripts
function bones_remove_wp_ver_css_js( $src ) {
  if ( strpos( $src, 'ver=' ) ) {
    $src = remove_query_arg( 'ver', $src );
  }
  return $src;
}

// remove injected CSS for recent comments widget
function bones_remove_wp_widget_recent_comments_style() {
  if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
    remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
  }
}

// remove injected CSS from recent comments widget (Modern WP method)
function bones_remove_recent_comments_style() {
  add_filter( 'show_recent_comments_widget_style', '__return_false' );
}

// remove injected CSS from gallery
function bones_gallery_style($css) {
  return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}

/*********************
SCRIPTS & ENQUEUEING
*********************/

function bones_scripts_and_styles() {
  global $wp_styles;
  global $is_IE; // PHP 8対策: グローバル変数を明示

  if (!is_admin()) {

    // modernizr
    wp_register_script( 'bones-modernizr', get_stylesheet_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );

    $fa5 = 'https://use.fontawesome.com/releases/v5.3.1/css/all.css';
    wp_register_style( 'fa5css', $fa5 , array(), '', 'all' );
    
    // shard5 stylesheet - PHP 8対策: ファイルの存在チェックを追加
    $shard5_rel_path = '/library/css/shard5.css'; // ※ディレクトリツリーに合わせて修正しました
    $shard5_abs_path = get_stylesheet_directory() . $shard5_rel_path;
    $shard5_ver = file_exists($shard5_abs_path) ? date("YmdHi", filemtime($shard5_abs_path)) : '1.0';
    wp_register_style( 'shard-stylesheet', get_stylesheet_directory_uri() . $shard5_rel_path, array(), $shard5_ver, 'all' );

    // ie-only style sheet
    wp_register_style( 'bones-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

    if ( is_singular() && comments_open() && (get_option('thread_comments') == 1)) {
      wp_enqueue_script( 'comment-reply' );
    }

    wp_register_script( 'bones-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array( 'jquery' ), '', true );
    wp_register_script( 'googlePlus', 'https://apis.google.com/js/plusone.js', array(), '', true );

    if(!is_admin()) {
      wp_deregister_script('jquery');
      if( $is_IE ) {
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), '', true);
      } else {
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', array(), '', true);
      }
    }

    wp_enqueue_script( 'bones-modernizr' );
    wp_enqueue_script( 'googlePlus' );
    wp_enqueue_style( 'shard-stylesheet' );
    wp_enqueue_style( 'bones-ie-only' );
    wp_enqueue_style( 'fa5css' );

    $wp_styles->add_data( 'bones-ie-only', 'conditional', 'lt IE 9' );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bones-js' );
  }
}

/*********************
THEME SUPPORT
*********************/

function bones_theme_support() {
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size(125, 125, true);

  add_theme_support( 'custom-background',
      array(
      'default-image' => '',
      'default-color' => '',
      'wp-head-callback' => '_custom_background_cb',
      'admin-head-callback' => '',
      'admin-preview-callback' => ''
      )
  );

  add_theme_support('automatic-feed-links');

  add_theme_support( 'post-formats',
    array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' )
  );

  add_theme_support( 'menus' );

  register_nav_menus(
    array(
      'main-nav' => __( 'The Main Menu', 'bonestheme' ),
      'footer-links' => __( 'Footer Links', 'bonestheme' )
    )
  );

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
}

/*********************
RELATED POSTS FUNCTION
*********************/

function bones_related_posts() {
  echo '<ul id="bones-related-posts">';
  global $post;
  $tags = wp_get_post_tags( $post->ID );
  if($tags) {
    $tag_arr = ''; // PHP 8対策: 変数を初期化
    foreach( $tags as $tag ) {
      $tag_arr .= $tag->slug . ',';
    }
    $args = array(
      'tag' => $tag_arr,
      'numberposts' => 5,
      'post__not_in' => array($post->ID)
    );
    $related_posts = get_posts( $args );
    if($related_posts) {
      foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
        <li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
      <?php endforeach; 
    } else { ?>
      <?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'bonestheme' ) . '</li>'; ?>
    <?php }
  }
  wp_reset_postdata();
  echo '</ul>';
}

/*********************
PAGE NAVI
*********************/

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

/*********************
RANDOM CLEANUP ITEMS
*********************/

function bones_filter_ptags_on_images($content){
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

function bones_excerpt_more($more) {
  global $post;
  return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'bonestheme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'bonestheme' ) .'</a>';
}
?>