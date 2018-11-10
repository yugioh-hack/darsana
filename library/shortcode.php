<?php
/*
 * short code
 */
add_shortcode('ingress_item', 'sc_item_2');
function sc_item_2() {
    $item_name = '';
    $item_name = get_post_meta(get_the_ID(), 'item_name', true);

    if( $item_name  !== '' ): // nameがあるときだけ出力

      $item_image_1     = '';   $item_image_1     = get_post_meta(get_the_ID(), 'item_image_1', true);
      $item_image_2     = '';   $item_image_2     = get_post_meta(get_the_ID(), 'item_image_2', true);
      $item_description = '';   $item_description = get_post_meta(get_the_ID(), 'item_description', true);
      $item_class       = '';   $item_class       = get_post_meta(get_the_ID(), 'item_class', true);
      $item_rare        = '';   $item_rare        = get_post_meta(get_the_ID(), 'item_rare', true);
      $item_level       = '';   $item_level       = get_post_meta(get_the_ID(), 'item_level', true);
      $item_price       = '';   $item_price       = get_post_meta(get_the_ID(), 'item_price', true);


      if($item_image_1 !== ''):
        $card_image = '';
        $image_attributes_1 = [];
        $image_size = 'full';
        $image_attributes_1 = wp_get_attachment_image_src($item_image_1,$image_size);

        if( !empty($image_attributes_1) ):
          $image_1 = '<img src="'.$image_attributes_1[0].'" width="'.$image_attributes_1[1].'">';
        endif;

      endif;

      //if($item_image_2 !== ''):
      //  $image_attributes_2 = [];
      //  $image_attributes_2 = wp_get_attachment_image_src($item_image_2);
      //  if( !empty($image_attributes_2) ):
      //    $image_2 = '<img src="'.$image_attributes_2[0].'" width="'.$image_attributes_2[1].'">';
      //  endif;
      //endif;

      $temp_item_content .= '<p class="narsada_card-alt_description_dt">%1$s</p><p class="narsada_card-alt_description_dd">%2$s</p>';

      if($item_description !== ''):
        $item_content .= sprintf($temp_item_content,'効果',$item_description);
      endif;

      if($item_class !== ''):
        $item_content .= sprintf($temp_item_content,'分類',$item_class);
      endif;

      if($item_rare !== ''):
        $item_content .= sprintf($temp_item_content,'レア度',$item_rare);
      endif;

      if($item_level !== ''):
        $item_content .= sprintf($temp_item_content,'レベル',$item_level);
      endif;

      if($item_price !== ''):
        $item_content .= sprintf($temp_item_content,'価格',$item_price);
      endif;

      $card  = '<article class="narsada_card-alt">';
      $card .=   '<div><figure class="narsada_card-alt_image image">' . $image_1 . '</figure></div>';
      $card .=   '<dl class="narsada_card-alt-content">';
      $card .=     '<dt class="narsada_card-alt-title"><h2>' . $item_name . '</h2></dt>';
      $card .=     '<dd class="narsada_card-alt_description">'.$item_content.'</dd>';
      $card .=   '</dl>';
      $card .= '</article>';

      echo $card;

    else:
      return;
    endif;
}

function sc_item() {
    $item_name = '';
    $item_name = get_post_meta(get_the_ID(), 'item_name', true);

    if( $item_name  !== '' ): // nameがあるときだけ出力

      $item_image_1     = '';   $item_image_1     = get_post_meta(get_the_ID(), 'item_image_1', true);
      $item_image_2     = '';   $item_image_2     = get_post_meta(get_the_ID(), 'item_image_2', true);
      $item_description = '';   $item_description = get_post_meta(get_the_ID(), 'item_description', true);
      $item_class       = '';   $item_class       = get_post_meta(get_the_ID(), 'item_class', true);
      $item_rare        = '';   $item_rare        = get_post_meta(get_the_ID(), 'item_rare', true);
      $item_level       = '';   $item_level       = get_post_meta(get_the_ID(), 'item_level', true);
      $item_price       = '';   $item_price       = get_post_meta(get_the_ID(), 'item_price', true);


      if($item_image_1 !== ''):
        $card_image = '';
        $image_attributes_1 = [];
        $image_size = 'medium';
        $image_attributes_1 = wp_get_attachment_image_src($item_image_1,$image_size);

        if( !empty($image_attributes_1) ):
          $image_1 = '<img src="'.$image_attributes_1[0].'" width="'.$image_attributes_1[1].'">';
        endif;

      endif;

      //if($item_image_2 !== ''):
      //  $image_attributes_2 = [];
      //  $image_attributes_2 = wp_get_attachment_image_src($item_image_2);
      //  if( !empty($image_attributes_2) ):
      //    $image_2 = '<img src="'.$image_attributes_2[0].'" width="'.$image_attributes_2[1].'">';
      //  endif;
      //endif;

      if($item_description !== ''):
        $item_content .= '<dt>効果</dt><dd>'.$item_description.'</dd>';
      endif;

      if($item_class !== ''):
        $item_content .= '<dt>分類</dt><dd>'.$item_class.'</dd>';
      endif;

      if($item_rare !== ''):
        $item_content .= '<dt>レア度</dt><dd>'.$item_rare.'</dd>';
      endif;

      if($item_level !== ''):
        $item_content .= '<dt>レベル</dt><dd>'.$item_level.'</dd>';
      endif;

      if($item_price !== ''):
        $item_content .= '<dt>価格</dt><dd>'.$item_price.'</dd>';
      endif;

      $card  = '<article class="narsada_card">';
      $card .=   '<div class="narsada_card-image">';
      $card .=     '<figure class="image is-4by3">' . $image_1 . '</figure>';
      $card .=   '</div>';
      $card .=   '<div class="narsada_card-content">';
      $card .=     '<h2 class="narsada_card-title">' . $item_name . '</h2>';
      $card .=     '<dl class="narsada_card-description">'.$item_content.'</dl>';
      $card .=   '</div>';
      $card .= '</article>';

      echo $card;

    else:
      return;
    endif;
}
