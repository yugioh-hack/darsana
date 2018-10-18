<?php

// sns_share_button
function sns_share_button() {
  $post_url = get_the_permalink();

  $twitter_text = 'ツイート';
  $fb_count = fetch_facebook_count($post_url);
  $feedly_count = get_feedly_count($post_url);
  $google_plus_one_count = get_google_plus_one_count($post_url);
  $hatena_count = get_hatena_hatebu_count($post_url);

  $twitter_share = '<a class="button sns-button sns-button--twitter" href="http://twitter.com/share?text=' . get_the_title() .  '&url=' . $post_url . '" target="_blank">'. $twitter_text .'</a>';
  $fb_share      = '<a class="button sns-button sns-button--facebook" href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '&t='. get_the_title() . '"  target="_blank">' . $fb_count . '</a>';
  $gplus_share   = '<a class="button sns-button sns-button--googleplus" href="https://plus.google.com/share?url=' . $post_url . '" target="_blank">' . $google_plus_one_count . '</a>';
  $hatebu_share  = '<a class="button sns-button sns-button--hatena" href="http://b.hatena.ne.jp/entry/'. $post_url . '" target="_blank">' . $hatena_count . '</a>';

  $button  = '';
  $button .= $twitter_share;
  $button .= $fb_share;
  $button .= $gplus_share;
  $button .= $hatebu_share;

  $str = '<div class="sns-container">'.$button.'</div>';
  echo $str;
}

/*********************************
/* Facebookシェア数を取得する
********************************/
function fetch_facebook_count($url) {
  //URLをURLエンコード
  $encoded_url = rawurlencode( $url );
  //Facebookにリクエストを送る
  $response = wp_remote_get( 'https://graph.facebook.com/?id='.$encoded_url );
  $res = '0';

  //取得に成功した場合
  if (!is_wp_error( $response ) && $response["response"]["code"] === 200) {
    $body = $response['body'];
    $json = json_decode( $body ); //ジェイソンオブジェクトに変換する
    $res = ($json->{"share"}->{"share_count"} ? $json->{"share"}->{"share_count"} : 0);
  }
  return $res;
}

/********************************
 *  Feedlyの購読者数をカウントする
 *  http://nelog.jp/get-feedly-count
 ********************************/
function get_feedly_count(){
  $feed_url = rawurlencode(get_bloginfo( 'rss2_url' ) );
  //$feed_url = rawurlencode(get_bloginfo( 'http://yugioh-hack.com/ingress-pokemongo-osm-ingadv2017' ) );
  $res = '0';
  $subscribers = wp_remote_get( "http://cloud.feedly.com/v3/feeds/feed%2F$feed_url" );
  if (!is_wp_error( $subscribers ) && $subscribers["response"]["code"] === 200) {
    $subscribers = json_decode( $subscribers['body'] );
    if ( $subscribers ) {
      $subscribers = $subscribers->subscribers;
      set_transient( 'feedly_subscribers', $subscribers, 60 * 60 * 12 );
      $res = ($subscribers ? $subscribers : '0');
    }
  }
  return $res;
}

// Google+のカウント数を取得する エラー対策ずみ
// http://nelog.jp/fetch-google-plus-count
function get_google_plus_one_count($url) {
  $query = 'https://apis.google.com/_/+1/fastbutton?url=' . urlencode( $url );
  //URL（クエリ）先の情報を取得
  $result = wp_remote_get($query);
  // 正規表現でカウント数のところだけを抽出
  preg_match( '/\[2,([0-9.]+),\[/', $result["body"], $count );

  return isset($count[1]) ? intval($count[1]) : 0;
}

//はてブ数の取得
function get_hatena_hatebu_count($url){
  //はてブ数を取得
  $url = 'http://api.b.st-hatena.com/entry.count?url=' . urlencode( $url );
  $subscribers = wp_remote_get($url);

  $result = 0;

  if (!is_wp_error( $subscribers ) && $subscribers["response"]["code"] === 200) { // レスポンスコード200はリクエストが成功していることを意味する
    // $json = json_decode( $subscribers['body'] );// Jsonコードに変換
    // $result = ($json->{"body"} ? $json->{"body"} : 0);
    $result = $subscribers["body"];
  }
  //カウントを出力
  return $result;
}
