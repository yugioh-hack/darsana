<?php

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
