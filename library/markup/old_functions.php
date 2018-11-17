<?php
/****************************************************
 * 互換性のためにとっておく。非推奨なので基本使わない
****************************************************/

// 一般投稿とカスタム投稿をリスト表示
if(! function_exists('shard_frontPage_posts_list')) {
  function shard_frontPage_posts_list() {
    $args = array(
      'posts_per_page'   => 4, //表示件数
      'offset'           => 0,
      'orderby'          => 'modified', // 更新順
      'order'            => 'DESC',
      'post_type'        => array( 'post','how_to_ingress','anime','info'),
      'post_status'      => 'publish',
      'suppress_filters' => true
    );
    $posts_array = get_posts( $args );
    $posts_list_item = '<ul>';
    foreach ( $posts_array as $post ) : setup_postdata( $post ) ;
      $posts_list_item .= '<li class="front-aside--postList__item"><a href="'. get_the_permalink($post->ID).'">'. get_the_title($post->ID). '</a><time class="front-aside--postList__time">'.get_the_modified_date('',$post->ID).'</time></li>';
    endforeach; wp_reset_postdata();
    $posts_list_item .= '</ul>';

    echo $posts_list_item;
  }
}
//
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

// カスタムポストの一覧をフロントページに表示
if(! function_exists('shard_get_custom_posts')) {
  function shard_get_custom_posts($post_type ='info',$taxonomy_name='info_cat',$info_title='おしらせ') {
    //$post_type     = 'info'; // カスタム投稿のスラッグ名を入れる
    //$taxonomy_name = 'info_cat'; // タクソノミーのスラッグ名を入れる
    $args = array(
        'orderby' => 'name',
        'hierarchical' => false
    );

    $taxonomys = get_terms( $taxonomy_name, $args);
    if(!is_wp_error($taxonomys) && count($taxonomys)):
      $post_list = '';
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
          foreach($tax_posts as $tax_post):// リストを作る
            $post_list .= '<li class="front-infomation__listItem"><span class="front-infomation__listIcon">' . strtoupper($taxonomy->slug) . '</span><a href="'. get_permalink($tax_post->ID).'">'. get_the_title($tax_post->ID).'</a></li>';
          endforeach;
        endif;
        wp_reset_postdata();
      endforeach;

      // リストがあるなら
      if(! $post_list !== ''):
        $svg_all_path = get_template_directory_uri().'/library/images/svg/'.'all_black.svg';
        echo    '<div class="front-infomation">';
        echo      '<div class="front-infomation__header">';
        echo        '<figure class="front-infomation__figure">';
        echo          '<img class="front-infomation__img" src="'.$svg_all_path.'">';
        echo        '</figure>';
        echo        '<h1 class="front-infomation__title">'.$info_title.'</h1>';
        echo      '</div>';
        echo      '<ul class="front-infomation__list">';
        echo      $post_list;
        echo      '</ul>';
        echo    '</div>';// #front-infomation__content
      endif;
    endif;
  }
}
