<?php

function readinglist_list_output($atts, $content = null) {
	$atts = shortcode_atts( array(
		'status' => 'future',
		'pagination' => true,
		'count' => 5,
	), $atts );

	switch($atts['status']) {
		case "current":
			//$query_results = readinglist_get_current($atts);
			$tax = "currently-reading";
		break;
		case "future":
			//$query_results = readinglist_get_future($atts);
			$tax = "future-reading";
		break;
		case "past":
			// $query_results = readinglist_get_past($atts);
			$tax = "read";
		break;
	}


	$paged = get_query_var('paged') ? get_query_var('paged') : 1;

	$query_results = new WP_Query(
		array(
			'post_type' => 'book',
			'tax_query' 	=> array(
									array(
										'taxonomy' 		=> 'status',
										'field' 		=> 'slug',
										'terms' 		=>  $tax,
									)
								),
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'post_status' => 'publish',
			'posts_per_page' => $atts['count'],
		)
	);

	$html_out = "<div id=\"book_list\">";
		
	if($query_results->have_posts()) {

		while($query_results->have_posts()) {
			$query_results->the_post();
			$start_orig = get_post_meta(get_the_ID(), 'start_date', true);
			$end_orig = get_post_meta(get_the_ID(), 'end_date', true);

			$start = new DateTime($start_orig);
			$end = new DateTime($end_orig);

			global $post;

			$html_out .= "<div class=\"book_block\"><img class=\"book_image\" src=\"";
			if(has_post_thumbnail()) { 
				$html_out .= get_the_post_thumbnail_url();
			} else {
				$html_out .= plugins_url('/reading-list/default.png');
			}
			$html_out .= '">'; //Closing <img>
			$html_out .= "<div class=\"actual_content\">";

			$html_out .= "<p><strong>Book Title: </strong>" . the_title("","",false) . "</p>";
			$html_out .= "<p><strong>Author: </strong>" . get_post_meta(get_the_ID(), 'author', true) . "</p>";
			$html_out .= "<p><strong># of Pages: </strong>" . get_post_meta(get_the_ID(), 'pagecount', true) . "</p><br>";
			$html_out .= "<p><strong>Start Date: </strong>" . $start->format('d F, o') . "</p>";
			$html_out .= "<p><strong>End Date: </strong>" . $end->format('d F, o') . "</p>";
			$html_out .= "<p><strong>Pages/Day: </strong>" . get_post_meta(get_the_ID(),'averagepages', true) . "</p>";

			$html_out .= "</div>"; //Closing actual_content
			$html_out .= "</div>"; //Closing book_block
		}
	} else {
		$html_out .= "<p class=\"error\">There are no books in this status.</p>";
	}
				
	$html_out .= "</div>"; //Closing book_list

	return $html_out;

}

add_shortcode('readinglist', 'readinglist_list_output');

?>