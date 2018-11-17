<?php
// 出力
if(! function_exists('narsada_single_meta') {
  function narsada_single_meta() {

  }
}

// byline
if(! function_exists('narsada_single_byline') ) {
  function narsada_single_byline() {
     $post_time = '<time class="singlePost_byline__updated" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>';
    $str = '<p class="singlePost_byline">'.$post_time.</p>;

     return $str;
  }
}

// user meta
if(! function_exists('narsada_user_meta')) {
  function narsada_user_meta() {
    $user_img = '';
    if(get_avatar(get_the_author_id())):
      $user_img = '<figure class="singlePost_usermeta__img">'. get_avatar(get_the_author_id(), 50).'</figure>';
    endif;

    $user_content  = '<div class="singlePost_usermeta__content">';
    if(get_the_author_meta('nickname')):
      $user_content .= '<p class="singlePost_usermeta__nickname">'.get_the_author_meta('nickname').'</p>';
    endif;
    if(get_the_author_meta('gplus') && get_the_author_meta('gplus_url') ):
      $user_content  .= '<a class="singlePost_usermeta__link" href="'.get_the_author_meta('gplus_url').'"><p class="singlePost_usermeta__gplus">'.get_the_author_meta('gplus').'</p></a>';
    endif;
    $user_content .= '</div>';

    $str =  sprintf('<div class="singlePost_usermeta">%1$s %2$s</div>',
      $user_img,
      $user_content
    );
    return $str;
  }
}

