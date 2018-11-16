<?php
// Register User Contact Methods
function custom_user_contact_methods( $user_contact_method ) {

  $user_contact_method['gplus_url'] = __( 'Google Plus Profile URL', 'text_domain' );
  $user_contact_method['gplus'] = __( 'Google Plus user name', 'text_domain' );
  $user_contact_method['twitter'] = __( 'twitter username', 'text_domain' );
  $user_contact_method['agent'] = __( 'Ingress Agent name', 'text_domain' );

  return $user_contact_method;

}
add_filter( 'user_contactmethods', 'custom_user_contact_methods' );
