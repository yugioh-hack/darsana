<?php

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
    $taxonomy_name = 'howto_cat'; // タクソノミーのスラッグ名を入れる
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
          echo '<section><h3 id="' . esc_html($taxonomy->slug) . '"><a href="'. $url_taxonomy .'">' . esc_html($taxonomy->name) .'</a>
                </h3> <ul>';
            foreach($tax_posts as $tax_post) {
              echo '<li><a href="'. get_permalink($tax_post->ID).'">'. get_the_title($tax_post->ID).'</a></li>';
              }
              wp_reset_postdata();
            echo '</ul></section>';
        endif;

      }
    }
  }
}
