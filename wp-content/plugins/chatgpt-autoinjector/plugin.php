<?php
/*
Plugin Name: ChatGPT autoinjector
Plugin URI: https://gurabije-nft.azurewebsites.net/
Description: Adds GPT shortcode to every post and page
Version: 1.0
Author: Gurabije
Author URI: https://gurabije-nft.azurewebsites.net/
*/
function shortcode_adder_add_chatbot( $content ) {
  global $post;
  if( ! $post instanceof WP_Post ) return $content;

  switch( $post->post_type ) {
    case 'post':
      return $content . '[chatbot_chatgpt]'

    case 'page':
      return $content . '[chatbot_chatgpt]';

    default:
      return $content;
  }
}

add_filter( 'the_content', 'shortcode_adder_add_chatbot' );
?>