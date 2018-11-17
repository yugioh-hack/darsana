<?php
// Q&A記事を共通タグをもとに出力
function narsada_qa_related_posts() {
  $taxonomy_slug = 'how_to_tag';
  $post_type_slug = 'qa_ingress';
  $current_terms = array();
  $current_terms = get_the_terms($post->ID, $taxonomy_slug); //投稿につけられたタームを取得
  //この記事がタグを持っているかどうか判別
  if ( !empty($current_terms) && !is_wp_error($current_terms) ) :
    $current_term_list = array();
    foreach ( $current_terms as $current_term ) :
      $current_term_list[] = $current_term->slug;
    endforeach;

    // タグが設定されているならば
    if( !empty($current_term_list) ):
      $term_args = array(
          'post_type'      => $post_type_slug,
          'taxonomy'       => $taxonomy_slug,
          'tax_query'      => array(
              array(
                  'taxonomy' => $taxonomy_slug,
                  'field'    => 'slug',
                  'terms'    => $current_term_list,
                    ),
                                   ),
          'posts_per_page' => -1,//すべて表示
      );
    else:
      return;
    endif;

    $qa_posts = new WP_Query( $term_args );
     //関連する記事があるかどうか判別
    if( $qa_posts -> have_posts() ) :
      //関連するQ&Aがあるならば表示
      echo '<section>';
      echo '<h4 class="singlePost-qa--title">FAQ</h4>';
      echo '<dl class="singlePost-qa--dl">';
      while($qa_posts -> have_posts()):
        $qa_posts->the_post();
          echo '<dt class="singlePost-qa--dt">' .get_the_title(). '</dt>';
          // apply_filtersで改行を含むデータを取得する
          //echo '<dd class="singlePost-qa--dd">' .apply_filters('the_content',get_the_content()). '</dd>';
          echo '<dd class="singlePost-qa--dd">' .get_the_content(). '</dd>';
      endwhile;
      wp_reset_postdata();
      echo '</dl>';
      echo '</section>';
    endif;
  else:
    return;
  endif;
}
