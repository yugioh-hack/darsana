<?php
// HomeのHERO
if(! function_exists('shard_hero')) {
  function shard_hero() {
    if( is_home() || is_front_page() ):
      $hero = '<section %1$s><div %2$s><div %3$s><div %4$s><h1 %5$s>%6$s</h1></div></div></div></section>';
      $hero_particle_option = array("particles-js");
      $hero_section_option = array("hero","is-info","is-medium","is-bold");
      $hero_body_option = array("hero-body columns is-centered");
      $hero_container_option = array("container");
      $hero_title_option = array("common-hero--title");

      if ( get_bloginfo('description') ) :
        $hero_info = get_bloginfo ( 'description' );
      else:
        $hero_info = 'The world around you is not what it seems.';
      endif;

      $hero_particle_attribute = sprintf('id="%1$s"', implode(' ',$hero_particle_option));
      $hero_section_attribute = sprintf('class="%1$s"', implode(' ',$hero_section_option));
      $hero_body_attribute = sprintf('class="%1$s"', implode(' ',$hero_body_option));
      $hero_container_attribute = sprintf('class="%1$s"', implode(' ',$hero_container_option));
      $hero_title_attribute = sprintf('class="%1$s"', implode(' ',$hero_title_option));

      echo sprintf( $hero,
          $hero_section_attribute,
          $hero_particle_attribute,
          $hero_body_attribute,
          $hero_container_attribute,
          $hero_title_attribute,
          $hero_info
      );

    else:
      return;
    endif;
  }
}


// 一般投稿とカスタム投稿をリスト表示
if(! function_exists('shard_frontPage_posts_list')) {
  function shard_frontPage_posts_list() {
    $args = array(
      'posts_per_page'   => 4, //表示件数
      'offset'           => 0,
      'orderby'          => 'modified', // 更新順
      'order'            => 'DESC',
      'post_type'        => array( 'post','how_to_ingress'),
      'post_status'      => 'publish',
      'suppress_filters' => true
    );
    $posts_array = get_posts( $args );
    $posts_list_item = '<ul>';
    foreach ( $posts_array as $post ) : setup_postdata( $post ) ;
      $posts_list_item .= '<li><a href="'. get_the_permalink($post->ID).'">'. get_the_title($post->ID). '</a><time class="front-aside--postList__time">'.get_the_modified_date('',$post->ID).'</time></li>';
    endforeach; wp_reset_postdata();
    $posts_list_item .= '</ul>';

    echo $posts_list_item;
  }
}
// シングルポストの下にカテゴリーとタグを出力する関数
// ブログとそれ以外を区別する
if(! function_exists('shard_singlePost_footer_term')) {
  function shard_singlePost_footer_term() {
    if(is_single()):
        $singlePostTermList  = '<div class="singlePost--footer__info">';
        $singlePostTermList .= '<p class="singlePost--footer__tagName">%1$s</p>';
        $singlePostTermList .= '<p class="singlePost--footer__tagLists">%2$s</p>';
        $singlePostTermList .= '</div>';

        $catListName = "Categories";
        $tagListName = "Tags";
        $catLists    = '';
        $tagLists    = '';

      if(get_post_type() == 'post'): //一般的な投稿ならば
        $catLists .= get_the_category_list(' ,');
        $tagLists .= get_the_tag_list('<span class="singlePost--footer__tagItem">','</span><span class="singlePost--footer__tagItem">','</span>');
      else: // カスタム投稿
        $taxonomies = get_post_taxonomies(); //投稿に紐付いたタクソノミーの一覧
        $taxonomy_cat = '';
        $taxonomy_tag = '';

        foreach($taxonomies as $val):
          if ( strpos($val, '_cat') !== false ):  // '_cat'がタクソノミーの中に含まれているかを確認
            $taxonomy_cat = $val; // '_cat'を含むタクソノミーを抽出
          endif;
          if ( strpos($val, '_tag') !== false ):  // '_tag'がタクソノミーの中に含まれているかを確認
            $taxonomy_tag = $val; // '_cat'を含むタクソノミーを抽出
          endif;
        endforeach;

        if($taxonomy_cat !== false):
          $catLists .= get_the_term_list($post->ID, $taxonomy_cat ,'',', ','' );
        endif;

        if($taxonomy_tag !== false):
          $tagLists .= get_the_term_list($post->ID, $taxonomy_tag ,'<span class="singlePost--footer__tagItem">','</span><span class="singlePost--footer__tagItem">','','</span>');
        endif;

      endif; // get_post_type()

      printf( $singlePostTermList,$catListName,$catLists);
      printf( $singlePostTermList,$tagListName,$tagLists);
    endif; // is_single
  }
}

// term_id によってfontawesomeの表示を変更する
if(! function_exists('shard_fontawesome_random')) {
  function shard_fontawesome_random($term_id) {
    switch($term_id % 7) {
      case '0':
        return '<i class="fas fa-coffee"></i>';
        break;
      case '1':
        return '<i class="fas fa-chalkboard-teacher"></i>';
        break;
      case '2':
        return '<i class="fas fa-cocktail"></i>';
        break;
      case '3':
        return '<i class="fas fa-helicopter"></i>';
        break;
      case '4':
        return '<i class="fas fa-walking"></i>';
        break;
      case '5':
        return '<i class="fas fa-charging-station"></i>';
        break;
      case '6':
        return '<i class="fas fa-car"></i>';
        break;
      default :
        return '<i class="fas fa-coffee"></i>';
    }
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
      foreach($taxonomys as $taxonomy) {
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
          echo '<section class="column is-12-mobile is-6-tablet is-4-desktop front-section">';
          echo  '<div class="front-section__columns columns is-mobile">';
          echo  '<div class="column is-2">';
          echo    '<span class="front-icon">'.shard_fontawesome_random($taxonomy->term_id).'</span>'; // アイコンをtermi_idを元にしてランダムに生成する
          echo  '</div>';
          echo  '<div class="front-section__content column">';
          echo    '<h2 class="front-heading" id="' . esc_html($taxonomy->slug) . '">';
          echo      '<a href="'. $url_taxonomy .'">'. esc_html($taxonomy->name) .'</a>';
          echo    '</h2>';
          echo    '<ul class="front-list">';
            foreach($tax_posts as $tax_post):
               echo '<li class="front-listItem"><a href="'. get_permalink($tax_post->ID).'">'. get_the_title($tax_post->ID).'</a></li>';
            endforeach;
            wp_reset_postdata();
          echo     '</ul>';
          echo    '</div>';// #front-section__content
          echo   '</div>';
          echo '</section>';
        endif;

      }
    }
  }
}

// アーカイブ等でカテゴリーのタイトルに「カテゴリー：」などと表示しない
add_filter( 'get_the_archive_title', function ($title) {
  if ( is_category() ) :
    $title = single_cat_title( '', false );
  elseif ( is_tag() ) :
    $title = single_tag_title( '', false );
  elseif ( is_tax('how_to_cat') ) :
    $title = single_term_title( '', false );
  endif;
    return $title;
});
