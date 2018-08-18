<?php
// https://qiita.com/oreo3@github/items/117e41689d3396f70f6d

//remove_filter( 'the_content', 'wpautop' );

/**
 * 任意期の記事wpautopを無効
 * 投稿冒頭のコメントで autop を無効化
 * @link https://elearn.jp/wpman/column/c20130813_01.html
 */

function noautop( $content ) {
    if ( strpos( $content, '<!--noautop-->' ) !== false ) {
        remove_filter( 'the_content', 'wpautop' );
        $content = preg_replace( "/\s*\<!--noautop-->\s*(\r\n|\n|\r)?/u", "", $content );
    }
    return $content;
}
add_filter( 'the_content', 'noautop', 1 );

/**
 * ビジュアルエディタに切り替えで、空の span タグや i タグが消されるのを防止
 */
if ( ! function_exists('tinymce_init') ) {
    function tinymce_init( $init ) {
        $init['verify_html'] = false; // 空タグや属性なしのタグを消させない
        $initArray['valid_children'] = '+body[style], +div[div|span|a], +span[span]'; // 指定の子要素を消させない
        return $init;
    }
    add_filter( 'tiny_mce_before_init', 'tinymce_init', 100 );
}
