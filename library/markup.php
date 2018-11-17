<?php
require_once( 'markup/single_meta.php' );

require_once( 'markup/get_posts.php' );

require_once( 'markup/related_posts.php' );

require_once( 'markup/old_functions.php' );

// なるさだの公式ログ出力
function shard_narsada_logo() {
  $path_narsada_logo = get_stylesheet_directory_uri().'/library/images/narsada.png';
  if( $path_narsada_logo ):
    echo '<span class="common-logo"><img src="'.$path_narsada_logo .'" alt="logo"></span>';
  else:
    return;
  endif;
}

// HomeのHERO
if(! function_exists('shard_hero')) {
  function shard_hero() {
    //if( is_home() || is_front_page()||is_single()|| is_singular('how_to_ingress') ):
      $hero = '<div %1$s><div %2$s><div %3$s><section %4$s><h1 %5$s>%6$s</h1></section></div></div></div>';
      $hero_particle_option = array("particles-js");
      $hero_section_option = array("hero","is-info","is-medium","is-bold");
      //$hero_section_option = array("hero","is-gradient","is-medium","is-bold");
      $hero_body_option = array("hero-body","columns","is-centered");
      $hero_container_option = array("container");
      $hero_description_option = array("common-hero--description");

      if ( get_bloginfo('description') ) :
        $hero_description = get_bloginfo ( 'description' );
      else:
        $hero_description = 'The world around you is not what it seems.';
      endif;

      $hero_particle_attribute = sprintf('id="%1$s"', implode(' ',$hero_particle_option));
      $hero_section_attribute = sprintf('class="%1$s"', implode(' ',$hero_section_option));
      $hero_body_attribute = sprintf('class="%1$s"', implode(' ',$hero_body_option));
      $hero_container_attribute = sprintf('class="%1$s"', implode(' ',$hero_container_option));
      $hero_title_attribute = sprintf('class="%1$s"', implode(' ',$hero_description_option));

      echo sprintf( $hero,
          $hero_section_attribute,
          $hero_particle_attribute,
          $hero_body_attribute,
          $hero_container_attribute,
          $hero_description_attribute,
          $hero_description
      );

    //else:
    //  return;
    //endif;
  }
}


if(! function_exists('shard_ingress_svg')) {
  function shard_ingress_svg($term_id, $size = 120) {
    $ingress_svg = get_term_meta($term_id,$taxonomy,false);
    $svg_list = [ // svgのリスト
      'XM',
      'abadon',
      'attack',
      'barrier',
      'begin',
      'capture',
      'chaos',
      'clearall',
      'discover',
      'harmony',
      'imperfect',
      'journey',
      'leberate',
      'openall',
      'outside',
      'path',
      'safety',
      'search',
      'shapers',
      'them'
    ];
    // termのラジオボタンにsvg画像が設定されているか
    $svg = $ingress_svg['svg'][0];
    // 定義されているなら、その画像を出す
    if($svg !== '' && in_array($svg,$svg_list)):
      $svg_path = get_template_directory_uri().'/library/images/svg/'.$svg.'.svg';
      echo '<img src="'.$svg_path.'" width=".$size.">';
    //未設定ならallグリフを返す
    else:
      $svg_all_path = get_template_directory_uri().'/library/images/svg/'.'all.svg';
      echo '<img src="'.$svg_all_path.'" width=".$size.">';
    endif;
  }
}


// カテゴリー毎に投稿を一覧で表示する
if ( ! function_exists( 'shard_get_archive_posts' ) ) {
  function shard_get_archive_posts() {
    global $post;
    $args = array(
      'type'           => 'post',
      'child_of'       => 0,
      'parent'         => '',
      'orderby'        => 'name',
      'order'          => 'ASC',
      'hide_empty'     => 1,
      'hierarchical'   => 1,
      'exclude'        => '',
      'include'        => '',
      'number'         => '',
      'taxonomy'       => 'category',
      'pad_counts'     => false
    );
    $get_cat = get_categories($args);
      foreach($get_cat as $val) {
        $category_list_id[$val->name]= $val->cat_ID;
      }
      foreach($category_list_id as $key => $val) {
        echo '<h3>' .$key .'</h3>';

        $cat_args = array(
          'numberposts' => 3,
          'category'    => $val
        );
        $cat_get_posts = get_posts($cat_args);
        if ( $cat_get_posts ) {
            foreach ( $cat_get_posts as $post ) {
                setup_postdata( $post );
                the_title();
            }
            wp_reset_postdata();
        }
      }
  }
}

// カテゴリー毎にカスタム投稿の一覧を表示する
if ( ! function_exists( 'shard_get_archive_custom_posts' ) ) {
  function shard_get_archive_custom_posts() {
    $taxonomy_name = 'how_to_cat'; // タクソノミーのスラッグ名を入れる
    $post_type     = 'how_to_ingress'; // カスタム投稿のスラッグ名を入れる
    $args = array(
        'orderby' => 'name',
        'hierarchical' => false
    );
    $taxonomys = get_terms( $taxonomy_name, $args);
    if(!is_wp_error($taxonomys) && count($taxonomys)) {
      foreach($taxonomys as $taxonomy):
        $url_taxonomy = get_term_link($taxonomy->slug, $taxonomy_name);
        $tax_get_array = array(
            'post_type' => $post_type, //表示したいカスタム投稿
            'posts_per_page' => 5,//表示件数
            // https://blog.nakachon.com/2014/10/27/dont-use-name-field-tax-query-in-japanese/
            // termsにはidを, fieldにはterm_idを入れるべき
            'tax_query' => array(
                array(
                      'taxonomy' => $taxonomy_name,
                      'terms'     => array($taxonomy -> term_id),
                      'field'    => 'term_id',
                      'operator' => 'IN',
                      'include_children' => true
                     )
            )
        );
        $tax_posts = get_posts( $tax_get_array );

        // ポストが存在するならば
        if($tax_posts):
          echo '<div class="column is-12-mobile is-6-tablet is-6-desktop front-section">';
          echo  '<div class="front-section__columns columns is-mobile">';
          echo  '<section class="front-section__content column">';
          echo    '<div class="front-section__header">';
          echo      '<figure class="front-section__figure">';
                      shard_ingress_svg($taxonomy->term_id); // アイコンをtermi_idを元にして生成する
          echo      '</figure>';
          echo      '<h2 class="front-section__title" id="' . esc_html($taxonomy->slug) . '">';
          echo        esc_html($taxonomy->name);
          echo      '</h2>';
          echo    '</div>';
          echo    '<ul class="front-section__list">';
            foreach($tax_posts as $tax_post):
               echo '<li class="front-section__listItem"><a href="'. get_permalink($tax_post->ID).'">'. get_the_title($tax_post->ID).'</a></li>';
            endforeach;
            wp_reset_postdata();
          echo     '</ul>';
          echo      '<div class="front-section__viewall"><a href="'. $url_taxonomy .'"><i class="fas fa-arrow-circle-right"></i>View All</a></div>';
          echo    '</section>';// #front-section__content
          echo   '</div>';
          echo '</div>';
        endif;
      endforeach;
    }
  }
}

// アーカイブ等でカテゴリーのタイトルに「カテゴリー：」などと表示しない
add_filter( 'get_the_archive_title', function ($title) {
  if ( is_category() ) :
    $title = single_cat_title( '', false );
  elseif ( is_tag() ) :
    $title = single_tag_title( '', false );
  elseif ( is_tax('how_to_cat') || is_tax('how_to_tag') ) :
    $title = single_term_title( '', false );
  endif;
    return $title;
});

// titleを丸める
if(!function_exists('narsada_title_limit')) {
  function narsada_title_limit($get_post_title) {
    if(mb_strlen($get_post_title, 'UTF-8')>55){
      $title= mb_substr($get_post_title, 0, 55, 'UTF-8');
      return $title.'……';
    }else{
      return $get_post_title;
    }
  }
}
