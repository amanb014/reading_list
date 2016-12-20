<?php

//File for any shortcodes to be used by the system.

//This shortcode is for the different lists that are supported by the readinglist.
//Currently, there is a "currently-reading", "future-reading", and "past"
//Also supports pagination.
function readinglist_list_output($atts, $content = null) {
	
	//if one of the parameters is not defined from the shortcode, these are the defaults to be used.
	$atts = shortcode_atts( array(
		'status' => 'future',
		'pagination' => true,
		'count' => 5,
	), $atts );

	//Pagination is dependent on this.
	$paged = get_query_var('paged') ? get_query_var('paged') : 1;

	//The query for the post type, to get all the posts.
	$query_results = new WP_Query(
		array(
			'post_type' => 'book',
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'ASC',
			'no_found_rows' => false,
			'posts_per_page' => $atts['count'],
			'paged' => $paged,
			'tax_query' 	=> array(
									array(
										'taxonomy' 		=> 'status',
										'field' 		=> 'slug',
										'terms' 		=>  $atts['status'],
									)
								),
		)
	);

	$html_out = '<div id="book_list">';
		
	//If we have posts.. then continue.
	if($query_results->have_posts()) {

		//While there are posts, loop this whole thing. This represents every single book there is in the list to be displayed.
		while($query_results->have_posts()) {
			//Gets the next post in line and iterates one up
			$query_results->the_post();

			$start_string = get_post_meta(get_the_ID(), 'start_date', true);
			$end_string = get_post_meta(get_the_ID(), 'end_date', true);

			$start = new DateTime($start_string);
			$end = new DateTime($end_string);

			$display_start = $start_string != "" ? $start->format('d F, o') : 'Not Started';
			$display_end = $end_string != "" ? $end->format('d F, o') : 'Not Finished';
			

			//Gets the post meta containing start and end dates and makes a new DateTime out of it (to format it later)
			
			$amazon = get_post_meta(get_the_ID(),'amazonlink', true);

			//Global post variable to refer.
			global $post;

			//Starts the HTML out. The whole book displaying process is done by appending the text in html
			$html_out .= '<div class="book_block"><a target="_blank" href= "' . $amazon . '"><img class="book_image" src="';
			if(has_post_thumbnail()) { 
				$html_out .= get_the_post_thumbnail_url();
			} else {
				$html_out .= plugins_url('/reading-list/default.png');
			}
			$html_out .= '"></a>'; //Closing <img>
			$html_out .= '<div class="actual_content">';

			$html_out .= '<p><strong>Book Title: </strong>' . the_title('','',false) . '</p>';
			$html_out .= '<p><strong>Author: </strong>' . get_post_meta(get_the_ID(), 'author', true) . '</p>';
			$html_out .= '<p><strong># of Pages: </strong>' . get_post_meta(get_the_ID(), 'pagecount', true) . '</p><br>';
			$html_out .= '<p><strong>Start Date: </strong>' . $display_start . '</p>';
			$html_out .= '<p><strong>End Date: </strong>' . $display_end . '</p>';
			$html_out .= '<p><strong>Pages/Day: </strong>' . get_post_meta(get_the_ID(),'averagepages', true) . '</p>';
			$html_out .= '<p><strong><a target="_blank" href="' . $amazon . '">' . 'View on Amazon' . '</a></strong></p>';

			$html_out .= '</div>'; //Closing actual_content
			$html_out .= '</div>'; //Closing book_block
		}
	} 

	//if there are no posts.. then display some text. 
	else {
		$html_out .= '<p class="error">There are no books in this status.</p>';
	}
				
	$html_out .= '</div>'; //Closing book_list

	wp_reset_postdata();

	//Pagination if there are more than one page to be displayed. Pagination is always on, and the default number of items per page is 5. The user can set this number by defining "count" in the shortcode.
	if($query_results->max_num_pages > 1 && is_page()) {
		$html_out .= '<nav class="prev-next-posts">';
		$html_out .= '<div class="nav-previous">';
		$html_out .= get_next_posts_link('<span class="meta-nav">&larr;</span> Older', $query_results->max_num_pages);
		$html_out .= '</div>';
		$html_out .= '<div class="nav-next">';
		$html_out .= get_previous_posts_link('<span class="meta-nav">&rarr;</span> Newer');
		$html_out .= '</div>';
		$html_out .= '</nav>';
	}

	//Returns the HTML to be displayed for all the books (including pagination)
	return $html_out;

}

//Registers the shortcode [readinglist]
add_shortcode('readinglist', 'readinglist_list_output');
?>