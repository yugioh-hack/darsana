<?php
/*****************************************
 *  投稿を出力する独自の関数置き場
 *****************************************/

// 一般投稿とカスタム投稿を時系列でリスト表示
if(! function_exists('narsada_get_posts_all')) {
  function narsada_get_posts_all() {
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

// サイドバー用 一般投稿とカスタム投稿を時系列でリスト表示
if(! function_exists('narsada_get_posts_all__sidebar')) {
  function narsada_get_posts_all__sidebar() {
    $args = array(
      'posts_per_page'   => 6, //表示件数
      'offset'           => 0,
      'orderby'          => 'modified', // 更新順
      'order'            => 'DESC',
      'post_type'        => array( 'post','how_to_ingress','anime','info'),
      'post_status'      => 'publish',
      'suppress_filters' => true
    );
    $posts_array = get_posts( $args );
    $posts_list_item = '<ul class="sidebar-list">';
    foreach ( $posts_array as $post ) : setup_postdata( $post ) ;
      $posts_list_item .= '<li class="sidebar-listItem"><a href="'. get_the_permalink($post->ID).'">'. get_the_title($post->ID). '</a></li>';
    endforeach; wp_reset_postdata();
    $posts_list_item .= '</ul>';

    $str  = '<div class="widget">';
    $str .= '<h4 class="widgettitle">更新履歴</h4>';
    $str .=  $posts_list_item;
    $str .= '</div>';
  }
}
// フロントにて一般的な投稿を時系列で表示する
if(! function_exists('narsada_get_posts')) {
  function narsada_get_posts($default_post_type ='post',
                             $default_taxonomy = 'category',
                             $blog_title='スタッフブログ')
  {

    $svg_all_path = get_template_directory_uri().'/library/images/svg/'.'all_black.svg';
    echo '<div class="front-infomation">';
    echo   '<div class="front-infomation__header">';
    echo     '<figure class="front-infomation__figure">';
    echo       '<img class="front-infomation__img" src="'.$svg_all_path.'">';
    echo     '</figure>';
    echo     '<h1 class="front-infomation__title">'.$blog_title.'</h1>';
    echo   '</div>';
    echo   '<ul class="front-infomation__list">';

      $args = array(
              'numberposts' => 5,            //表示（取得）する記事の数
              'post_type' => $default_post_type      //投稿タイプの指定
          );
    $getPosts = get_posts( $args );

    if( $getPosts ) :
      foreach( $getPosts as $post ) : setup_postdata( $post );
        $term = wp_get_post_terms($post->ID,$default_taxonomy); //投稿IDからtermを配列として取得
        echo '<li class="front-infomation__listItem"><span class="front-infomation__listIcon">' . strtoupper($term[0]->name) . '</span><a href="'. get_permalink($post ->ID).'">'.get_the_title($post->ID).'</a></li>';
      endforeach;
    else:
      echo '<li><p>記事はまだありません。</p></li>';
    endif;

    wp_reset_postdata(); //クエリのリセット

    echo  '</ul>';
    echo '</div>';
  }
}
// フロントにて、カスタムポストを時系列で表示する
if(! function_exists('narsada_get_custom_posts')) {
  function narsada_get_custom_posts($default_post_type ='info',
                                    $default_taxonomy = 'info_cat',
                                    $info_title='News') {

    $svg_all_path = get_template_directory_uri().'/library/images/svg/'.'all_black.svg';
    echo '<div class="front-infomation">';
    echo   '<div class="front-infomation__header">';
    echo     '<figure class="front-infomation__figure">';
    echo       '<img class="front-infomation__img" src="'.$svg_all_path.'">';
    echo     '</figure>';
    echo     '<h1 class="front-infomation__title">'.$info_title.'</h1>';
    echo   '</div>';
    echo   '<ul class="front-infomation__list">';

      $args = array(
              'numberposts' => 5,            //表示（取得）する記事の数
              'post_type' => $default_post_type      //投稿タイプの指定
          );
    $customPosts = get_posts( $args );

    if( $customPosts ) :
      foreach( $customPosts as $post ) : setup_postdata( $post );
        $term = wp_get_post_terms($post->ID,$default_taxonomy); //投稿IDからtermを配列として取得
        echo '<li class="front-infomation__listItem"><span class="front-infomation__listIcon">' . strtoupper($term[0]->slug) . '</span><a href="'. get_permalink($post ->ID).'">'.get_the_title($post->ID).'</a></li>';
      endforeach;
    else:
      echo '<li><p>記事はまだありません。</p></li>';
    endif;

    wp_reset_postdata(); //クエリのリセット

    echo  '</ul>';
    echo '</div>';
  }
}
