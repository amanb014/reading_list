<?php
  /*
  Plugin Name: Reading List
  Plugin URI: http://dontjudgecovers.com/
  Description: A plugin to create several lists of books (currently reading, read, and to read). Retreiving book data from Amazon and displaying it on a page. 
  Version: 0.5
  Author: Aman Bhimani
  Author URI: http://amanbhimani.com/
  */

  define('WP_DEBUG', true);
  if( !defined('ABSPATH')) {
    exit;
  }

  //Register the custom post type "Books".
  require_once (plugin_dir_path(__FILE__).'reading-list-cpt.php');
  require_once (plugin_dir_path(__FILE__).'reading-list-taxonomy.php');
  require_once (plugin_dir_path(__FILE__).'reading-list-fields.php');
  require_once (plugin_dir_path(__FILE__).'reading-list-shortcodes.php');

  function readinglist_enqueue_scripts() {
    //These varibales allow us to target the post type and the post edit screen.
    global $pagenow, $typenow;
    if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'book' ) {
      //Plugin Main CSS File.
      wp_enqueue_style( 'readinglist-css', plugins_url( 'scripts/css/reading-list.css', __FILE__ ) );
      wp_enqueue_script( 'readinglist-js', plugins_url('scripts/js/reading-list.js', __FILE__), array('jquery', 'jquery-ui-datepicker'), '20161214', true);
    }
  }

  function readinglist_enqueue_public_style() {
      wp_enqueue_style( 'readinglist_public_css', plugins_url( 'scripts/css/reading_list_public.css', __FILE__ ) );
  }
  //This hook ensures our scripts and styles are only loaded in the admin.
  add_action( 'admin_enqueue_scripts', 'readinglist_enqueue_scripts' );
  add_action('wp_head', 'readinglist_enqueue_public_style');
?>