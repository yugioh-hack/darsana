<?php
// パンくずリストを出力する
function breadcrumb($divOption = array("class" => "breadcrumbs")) {
  global $post;
  $str ='';
  $ulOption = array("class" => "breadcrumb__list");
  $liOption = array("class" => "breadcrumb__item", "itemprop" => "itemListElement");
  $currentOption = 'class="breadcrumb__current"';
  if(!is_home() && !is_admin()) {
    $schemaBread = 'https://schema.org/BreadcrumbList';
    $schemaList  = 'https://schema.org/ListItem';
    $position    = 1;

    $tagAttribute = '';
    foreach($divOption as $attrName => $attrValue) {
      $tagAttribute .= sprintf(' %1$s="%2$s"', $attrName, $attrValue);
    }
    $ulAttribute = '';
    foreach($ulOption as $attrName => $attrValue) {
      $ulAttribute .= sprintf(' %1$s="%2$s"', $attrName, $attrValue);
    }
    $liAttribute = '';
    foreach($liOption as $attrName => $attrValue) {
      $liAttribute .= sprintf(' %1$s="%2$s" ', $attrName, $attrValue);
    }
    $navTitle = '<h2 class="screen-reader-text">BreadCrumbs</h2>';

    $str .= sprintf(
      '<nav %1$s>%2$s<ul %3$s itemscope itemtype="%4$s"><li %5$s itemscope itemtype="%6$s" ><a itemprop="url" href="%7$s"><span itemprop="url">Home</span></a><meta itemprop="position" content="%8$d" /></li>',
      $tagAttribute,
      $navTitle,
      $ulAttribute,
      esc_url( $schemaBread ),
      $liAttribute,
      esc_url( $schemaList ),
      esc_url( home_url() ),
      $position
    );

    if(is_single()) {
      $categories = get_the_category($post->ID); // カテゴリーIDを取得
      $cat        = $categories[0]; //最初の要素のカテゴリーだけを抽出
      // $strSingle = '';

      // 親カテゴリーがある場合
      if($cat -> parent !=0 ) {
        $ancestors = array_reverse(get_ancestors( $cat->cat_ID, 'category' )); // 親カテを昇順で取得
        foreach( $ancestors as $ancestor ) {
          ++$position;//ポジションを設定する
          $str .= sprintf(
            '<li %1$s itemscope itemtype="%2$s"><a itemprop="url" href="%3$s"><span itemprop="name">%4$s</span></a><meta itemprop="position" content="%5$d" /></li>',
            $liAttribute,
            esc_html( $schemaList ),
            esc_url( get_category_link( $ancestor ) ),
            esc_html( get_cat_name( $ancestor )),
            $position
          );
        }
      }

      $str .= sprintf(
        '<li %1$s itemscope itemtype="%2$s"><a itemprop="url" href="%3$s"><span itemprop="name">%4$s</span></a><meta itemprop="position" content="%5$d" /></li>',
        $liAttribute,
        esc_html( $schemaList ),
        esc_url( get_category_link( $cat->term_id ) ),
        esc_html( get_cat_name( $cat->cat_ID )),
        ++$position
      );

      $str .= sprintf(
        '<li %1$s itemscope itemtype="%2$s"><span %3$s itemprop="name">%4$s</span><meta itemprop="position" content="%5$d" /></li>',
        $liAttribute,
        esc_html( $schemaList ),
        $currentOption,
        esc_html( mb_substr( $post->post_title,0,30 ) ),//30文字制限
        ++$position
      );

    }
    elseif(is_category()) {
      $cat = get_queried_object();
      // $strCat = '';
      if( $cat -> parent !=0 ) {
        $ancestors = array_reverse(get_ancestors( $cat->cat_ID, 'category' ));
        foreach($ancestors as $ancestor) {
          ++$position;
          $str .= sprintf(
            '<li %1$s itemscope itemtype="%2$s"><a itemprop="url" href="%3$s"><span itemprop="name">%4$s</span></a><meta itemprop="position" content="%5$d" /></li>',
            $liAttribute,
            esc_html( $schemaList ),
            esc_url( get_category_link( $ancestor ) ),
            esc_html( get_cat_name( $ancestor ) ),
            $position
          );
        }
        // $str .= $strCat;
      }
      $str .= sprintf(
        '<li %1$s itemscope itemtype=%2$s><span %3$s itemprop="name">%4$s</span><meta itemprop="position" content="%5$d" /></li>',
        $liAttribute,
        $currentOption,
        esc_html( $schemaList ),
        esc_html( $cat -> name ),
        ++$position
      );
    }
    elseif(is_page()) {
      if( $post -> post_parent !=0 ) {
        $ancestors = array_reverse( get_ancestors( $post->ID ) );
        foreach( $ancestors as $ancestor ) {
          ++$position;
          $str .= sprintf(
            '<li %1$s itemscope itemtype="%2$s"><a href="%3$s" itemprop="url"><span itemprop="name">%4$s</span></a><meta itemprop="position" content="%5$s" /></li>',
            $liAttribute,
            esc_html( $schemaList ),
            esc_url( getpermalink($ancestor) ),
            esc_html( get_the_title($ancestor) ),
            $position
            );
        }
      }
      $str .= sprintf(
        '<li %1$s itemscope itemtype="%2$s"><span %3$s itemprop="name">%4$s</span><meta itemprop="position" content="%5$d" /></li>',
        $liAttribute,
        esc_html( $schemaList ),
        $currentOption,
        esc_html( $post->post_title ),
        ++$position
        );
    }
    elseif(is_tag()) {
      $str .= sprintf(
          '<li %1$s itemscope itemtype="%2$s"><span %3$s itemprop="name">%4$s</span></li>',
          $liAttribute,
          esc_url( $schemaList ),
          $currentOption,
          esc_html(single_tag_title('',false))//取得するだけなのでfalseをつける
        );
    }
    elseif(is_date()) {
      if(get_query_var( 'day' ) !=0) {
        // 年を出力
        $str .= sprintf(
          '<li %1$s itemscope itemtype="%2$s"><a itemprop="url" href="%3$s"><span itemprop="name">%4$s</span></a></li>',
          $liAttribute,
          esc_url( $schemaList ),
          esc_url( get_year_link( get_query_var( 'year' ) ) ),
          esc_html( get_query_var( 'year' ).'年' )
        );
        $str .= sprintf(
          '<li %1$s itemscope itemtype="%2$s"><a itemprop="url" href="%3$s"><span itemprop="name">%4$s</span></a></li>',
          $liAttribute,
          esc_url( $schemaList ),
          esc_url( get_year_link( get_query_var( 'monthnum' ) ) ),
          esc_html( get_query_var( 'monthnum' ).'月' )
        );
        $str .= sprintf(
          '<li %1$s itemscope itemtype="%2$s"><span %3$s itemprop="name">%4$s</span></li>',
          $liAttribute,
          esc_url( $schemaList ),
          $currentOption,
          esc_html( get_query_var( 'day' ).'日' )
        );
      }
    }

    $str .= '</ul>';
    $str .= '</nav>';
  }
  echo $str;
}
