<?php
// 出力
if(! function_exists('narsada_single_meta') ) {
  function narsada_single_meta() {
    echo '<div class="singlePost-header__meta">';
    echo narsada_user_meta();
    echo narsada_single_byline();
    echo '</div>';
  }
}

// byline
if(! function_exists('narsada_single_byline') ) {
  function narsada_single_byline() {
     $post_time = '<time class="singlePost-byline__updated" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>';
    $str = '<p class="singlePost-byline">'.$post_time.'</p>';

     return $str;
  }
}

// user meta
if(! function_exists('narsada_user_meta')) {
  function narsada_user_meta() {
    $user_img = '';
    if(get_avatar(get_the_author_id())):
      $user_img = '<figure class="singlePost-usermeta__img">'. get_avatar(get_the_author_id(), 50).'</figure>';
    endif;

    $user_content  = '<div class="singlePost-usermeta__content">';
    if(get_the_author_meta('nickname')):
      $user_content .= '<p class="singlePost-usermeta__nickname">'.get_the_author_meta('nickname').'</p>';
    endif;
    if(get_the_author_meta('gplus') && get_the_author_meta('gplus_url') ):
      $user_content  .= '<a class="singlePost-usermeta__link" href="'.get_the_author_meta('gplus_url').'"><p class="singlePost_usermeta__gplus">'.get_the_author_meta('gplus').'</p></a>';
    endif;
    $user_content .= '</div>';

    $str =  sprintf('<div class="singlePost-usermeta">%1$s %2$s</div>',
      $user_img,
      $user_content
    );
    return $str;
  }
}

// シングルポストの下にカテゴリーとタグを出力する関数
// ブログとそれ以外を区別する
if(! function_exists('shard_singlePost_footer_term')) {
  function shard_singlePost_footer_term() {
    if(is_single()):
        $singlePostTermList  = '<aside class="singlePost-footer__info">';
        $singlePostTermList .= '<h4 class="singlePost-footer__termName">%1$s</h4>';
        $singlePostTermList .= '<ul class="singlePost-footer__termLists">%2$s</ul>';
        $singlePostTermList .= '</aside>';

        $catListName = "Categories";
        $tagListName = "Tags";
        $catLists    = '';
        $tagLists    = '';

      if(get_post_type() == 'post'): //一般的な投稿ならば
        $catLists .= get_the_category_list(' ,');
        $tagLists .= get_the_tag_list('<li class="singlePost-footer__termItem--cat">','</li><li class="singlePost-footer__termItem--cat">','</li>');
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
          $catLists .= get_the_term_list($post->ID, $taxonomy_cat ,'<li class="singlePost-footer__termItem--cat">','</li><li class="singlePost-footer__termItem--cat">','</li>');
          printf( $singlePostTermList,$catListName,$catLists);
        endif;

        if($taxonomy_tag !== false):
          $tagLists .= get_the_term_list($post->ID, $taxonomy_tag ,'<li class="singlePost-footer__termItem--tag">','</li><li class="singlePost-footer__termItem--tag">','</li>');
          printf( $singlePostTermList,$tagListName,$tagLists);
        endif;

      endif; // get_post_type()

    endif; // is_single
  }
}
