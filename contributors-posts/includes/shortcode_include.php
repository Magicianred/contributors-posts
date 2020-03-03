<?php

// SHORTCODES
add_shortcode('contributorsList', 'mgr_cp_contributorsListWithPosts');

function mgr_cp_contributorsListWithPosts($atts) {
		extract(shortcode_atts(array(
			"number_contributors" => '5',
			"contributor_with_description" => '0',
			"show_posts_count" => '0',
			"show_contributor_image" => '1',
			"number_posts" => '1',
			"post_with_description" => '0',
			"except_contributor_id" => '0',
			"add_querystring_link_contributor" => ''
			), $atts));
//$debug =  "[debug] number_contributors: ". $number_contributors ." // contributor_with_description: ". $contributor_with_description ." // ";
//$debug .= "show_posts_count: ". $show_posts_count ." // number_posts: ". $number_posts ." // post_with_description: ". $post_with_description ." <hr>";
		return mgr_cp_contributors_list_with_posts( $number_contributors,$contributor_with_description, $show_posts_count, $show_contributor_image, $number_posts, $post_with_description, $except_contributor_id, $add_querystring_link_contributor );
}

// SHORTCODES
add_shortcode('contributorsInfo', 'mgr_cp_contributorsInfoWithPosts');

function mgr_cp_contributorsInfoWithPosts($atts) {
		extract(shortcode_atts(array(
			"contributor_id" => '0',
			"contributor_with_description" => '1',
			"show_posts_count" => '1',
			"show_contributor_image" => '1',
			"number_posts" => '0',
			"post_with_description" => '0',
			"add_querystring_link_contributor" => ''
			), $atts));
//$debug =  "[debug] contributor_id: ". $contributor_id ." // contributor_with_description: ". $contributor_with_description ." // ";
//$debug .= "show_posts_count: ". $show_posts_count ." // number_posts: ". $number_posts ." // post_with_description: ". $post_with_description ." <hr>";
		return mgr_cp_contributors_info_with_posts($contributor_id, $contributor_with_description, $show_posts_count, $show_contributor_image, $number_posts, $post_with_description, $add_querystring_link_contributor);
}

// SHORTCODES
add_shortcode('randContributorsInfo', 'mgr_cp_randContributorsInfoWithPosts');

function mgr_cp_randContributorsInfoWithPosts($atts) {
		extract(shortcode_atts(array(
			"contributor_with_description" => '1',
			"show_posts_count" => '1',
			"show_contributor_image" => '1',
			"number_posts" => '0',
			"post_with_description" => '0',
			"except_contributor_id" => '0',
			"add_querystring_link_contributor" => ''
			), $atts));

			return mgr_cp_rand_contributors_info_with_posts( $contributor_with_description, $show_posts_count, $show_contributor_image, $number_posts, $post_with_description, $except_contributor_id, $add_querystring_link_contributor );
}

