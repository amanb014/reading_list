<?php
  /*
  Plugin Name: Reading List
  Plugin URI: http://dontjudgecovers.com/
  Description: A plugin to create several lists of books (currently reading, read, and to read). Retreiving book data from Amazon and displaying it on a page. 
  Version: 0.5
  Author: Aman Bhimani
  Author URI: http://amanbhimani.com/
  */

  //This is the main file that loads all other files and also makes sure that the css/js files are loaded. The text above is for Wordpress to recognize the plugin. 

  //If this file is accessed directly, without loading the admin pages, then the script will quit. This prevents anyone coming into the code. 
  define('WP_DEBUG', true);
  if( !defined('ABSPATH')) {
    exit;
  }

  //Require the file containing custom post type "book"
  require_once (plugin_dir_path(__FILE__).'reading-list-cpt.php');

  //Require the file containing the taxonomy for "status" of books
  require_once (plugin_dir_path(__FILE__).'reading-list-taxonomy.php');

  //Require the file containing the custom fields for the custom post type creation and editing
  require_once (plugin_dir_path(__FILE__).'reading-list-fields.php');

  //Require the file containing the shortcodes outputting the html for lists
  require_once (plugin_dir_path(__FILE__).'reading-list-shortcodes.php');

  //This function only enqueues scripts that are for the admin page. The javascript and css for "creating" and "editing" books are here.
  function readinglist_enqueue_scripts() {
    //These varibales allow us to target the post type and the post edit screen.
    global $pagenow, $typenow;
    if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'book' ) {
      //Plugin Main CSS File.
      wp_enqueue_style( 'readinglist-css', plugins_url( 'scripts/css/reading-list.css', __FILE__ ) );
      wp_enqueue_script( 'readinglist-js', plugins_url('scripts/js/reading-list.js', __FILE__), array('jquery', 'jquery-ui-datepicker'), '20161214', true);
    }
  }

  //This function enqueues the public styles (for public pages). This includes the single pages with shortcodes. This may need to be moved to the shortcodes file later on. 
  function readinglist_enqueue_public_style() {
      wp_enqueue_style( 'readinglist_public_css', plugins_url( 'scripts/css/reading_list_public.css', __FILE__ ) );
  }

  //Runs the admin scripts inclusion with the hook so that it only shows up on admin pages not public pages.
  add_action( 'admin_enqueue_scripts', 'readinglist_enqueue_scripts' );

  //Runs the inclusion function so that the public styles show up on all pages (using hook wp_head makes sure that it is in the wordpress header at all times)
  add_action('wp_head', 'readinglist_enqueue_public_style');
?>