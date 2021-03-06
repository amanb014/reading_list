<?php

//Registers the taxonomy "status" This is used for the statuses such as "currently reading", "future reading", and read. 
function readinglist_register_taxonomy() {

	$s 	= 'Status';
	$p 	= 'Statuses';

	$labels = array (
		'name' => $p,
		'singular_name' => $s,
		'menu_name' => $p,
		'all_items' => 'All '.$p,
		'edit_item' => 'Edit '.$s,
		'view_item' => 'View '.$s,
		'update_item' => 'Update '.$s,
		'add_new_item' => 'Add New '.$s,
		'new_item_name' => 'New '.$s.' Name',
		'parent_name' => 'Parent '.$s,
		'search_items' => 'Search '.$p,
		'separate_items_with_commas' => 'Separate '.$p.'with commas',
		'add_or_remove_items' => 'Add or Remove '.$p
	);

	$args = array(
		'labels' => $labels,
		'pubic' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'description' => 'The status of this current book.',
		'show_admin_column' => true,
		'hierarchical' => true,
		'rewrite' => array('slug' => 'status'),
		'sort' => true
	);
	register_taxonomy('status', 'book', $args);
}
add_action('init', 'readinglist_register_taxonomy');

//No need for parent taxonomy in this case. There are no child/parents. 
//This basically removes the parent boxes from both pages (to create new post), and new taxonomy
add_action('admin_head','remove_status_parent');
function remove_status_parent() {
  global $pagenow, $typenow;
  if (in_array($pagenow,array('post-new.php','post.php'))) { // Only for the post add & edit pages
    $css='<style> #newstatus_parent { display:none; } </style>';
    echo $css;
  }

  if ($pagenow == 'edit-tags.php' && $typenow == 'book') {
  	 $css='<style> .term-parent-wrap { display:none; } </style>';
  	 echo $css;
  }
}

//Change checkboxes to radio buttons
//This is done so that you cannot select more than one status for the book.
//ob_start creates a stream of HTMl that is being displayed to be stored in the variable instead of outputting it.
add_action('add_meta_boxes','readinglist_radio_buttons','book',2);
function readinglist_radio_buttons($post_type, $post) {
  ob_start();
}

//This function takes the ob_start() and ends it. Replaces all "checkbox" with "radio" buttons in the section that was streamed to it. 
add_action('dbx_post_sidebar','mysite_dbx_post_sidebar');
function mysite_dbx_post_sidebar() {
  $html = ob_get_clean();
  $html = str_replace('"checkbox"','"radio"',$html);
  echo $html;
}
?>