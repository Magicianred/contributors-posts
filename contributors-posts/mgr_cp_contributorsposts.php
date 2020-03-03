<?php
/*
Plugin Name: Contributors's Posts
Plugin URI: http://wordpress.org/extend/plugins/contributors-posts/
Description: A plugin to manage posts of contributors with the same account without having to create other users.
Version: 0.8.1
Author: Simone "Magicianred" Paolucci
Author URI: http://simone.paolucci.name
License: GPL

Copyright 2013 Magicianred (email: magicianred@gmail.com)
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
require_once('includes/setup_include.php');
require_once('includes/utility_include.php');
require_once('includes/widget_include.php');
require_once('includes/shortcode_include.php');
require_once('includes/admin_include.php');

/* Charge languages files */
function mgr_cp_lang_init() {
  load_plugin_textdomain( 'mgr_cp', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
}
add_action('plugins_loaded', 'mgr_cp_lang_init');

/* Add template page for Contributors' Posts */
function mgr_cp_include_template_function( $template_path ) {
	$options = get_option( 'mgr_cp_options' );
	$showPages = array();

	if(isset($options['contributorpost_show_pages'])) {
		$varPages = $options['contributorpost_show_pages'];
		$showPages = explode(',',$varPages);
	}


    if ( get_post_type() == 'contributorpost' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if( in_array('single',$showPages) ) {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-contributorpost.php';
            } elseif ( $theme_file = locate_template( array ( 'single-contributorpost.php' ) ) ) {
                $template_path = $theme_file;
            }
        } elseif ( is_archive()  ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if(in_array('archive',$showPages)) {
							$template_path = plugin_dir_path( __FILE__ ) . '/archive-contributorpost.php';
						} elseif ( $theme_file = locate_template( array ( 'archive-contributorpost.php' ) ) ) {
                $template_path = $theme_file;
            }
				}
    }
    return $template_path;
}
add_filter( 'template_include', 'mgr_cp_include_template_function', 1 );


/* Show the List of Contributors and list of posts */
function mgr_cp_contributors_list_with_posts($number_contributors = 5, $contributor_with_description = 1, $show_posts_count = 1, $show_contributor_image = 1, $number_posts = 1, $post_with_description = 0, $except_contributor_id = 0, $add_querystring_link_contributor = '') {

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	$is_taxonomy_images = 0;

	if(is_plugin_active('taxonomy-images/taxonomy-images.php')) {
			$is_taxonomy_images = 1;
			global $taxonomy_images_plugin;
	}

	$options = get_option( 'mgr_cp_options' );

		// Customize tags
		$after_contributorBoxSeparator = ( !empty( $options['after_contributorBoxSeparator'] ) ? $options['after_contributorBoxSeparator'] : '<hr/>' );
		$before_contributorBoxDescriptionSeparator = ( !empty( $options['before_contributorBoxDescriptionSeparator'] ) ? $options['before_contributorBoxDescriptionSeparator'] : '<br/>' );

		$before_contributorBox = ( !empty( $options['before_contributorBox'] ) ? $options['before_contributorBox'] : '<div class="contributor_box">' );
		$after_contributorBox = ( !empty( $options['after_contributorBox'] ) ? $options['after_contributorBox'] : '</div>' );
		$before_contributorBoxImage = ( !empty( $options['before_contributorBoxImage'] ) ? $options['before_contributorBoxImage'] : '<div class="contributor_box_image">' );
		$after_contributorBoxImage = ( !empty( $options['after_contributorBoxImage'] ) ? $options['after_contributorBoxImage'] : '</div>' );
		$before_contributorBoxTitle = ( !empty( $options['before_contributorBoxTitle'] ) ? $options['before_contributorBoxTitle'] : '<span class="contributor_box_title">' );
		$after_contributorBoxTitle = ( !empty( $options['after_contributorBoxTitle'] ) ? $options['after_contributorBoxTitle'] : '</span>' );
		$before_contributorBoxPostCount = ( !empty( $options['before_contributorBoxPostCount'] ) ? $options['before_contributorBoxPostCount'] : '<span class="contributor_box_postcount">(<strong>' );
		$after_contributorBoxPostCount = ( !empty( $options['after_contributorBoxPostCount'] ) ? $options['after_contributorBoxPostCount'] : '</strong>)</span>' );
		$before_contributorBoxDescription = ( !empty( $options['before_contributorBoxDescription'] ) ? $options['before_contributorBoxDescription'] : '<div class="contributor_box_desc">' );
		$after_contributorBoxDescription = ( !empty( $options['after_contributorBoxDescription'] ) ? $options['after_contributorBoxDescription'] : '</div>' );
		$before_contributorBoxDescriptionVoid = ( !empty( $options['before_contributorBoxDescriptionVoid'] ) ? $options['before_contributorBoxDescriptionVoid'] : '<div class="contributor_box_desc_void">' );
		$after_contributorBoxDescriptionVoid = ( !empty( $options['after_contributorBoxDescriptionVoid'] ) ? $options['after_contributorBoxDescriptionVoid'] : '</div>' );

		$before_contributorBoxPosts = ( !empty( $options['before_contributorBoxPosts'] ) ? $options['before_contributorBoxPosts'] : '<div class="contributor_posts">' );
		$after_contributorBoxPosts = ( !empty( $options['after_contributorBoxPosts'] ) ? $options['after_contributorBoxPosts'] : '</div>' );
		$before_contributorBoxPostContent = ( !empty( $options['before_contributorBoxPostContent'] ) ? $options['before_contributorBoxPostContent'] : '<div class="contributor_post_content">' );
		$after_contributorBoxPostContent = ( !empty( $options['after_contributorBoxPostContent'] ) ? $options['after_contributorBoxPostContent'] : '</div>' );
		$before_contributorBoxPostTitle = ( !empty( $options['before_contributorBoxPostTitle'] ) ? $options['before_contributorBoxPostTitle'] : '<h3 class="contributor_post_title"><strong>' );
		$after_contributorBoxPostTitle = ( !empty( $options['after_contributorBoxPostTitle'] ) ? $options['after_contributorBoxPostTitle'] : '</strong></h3><br/>' );
		$before_contributorBoxPostDescription = ( !empty( $options['before_contributorBoxPostDescription'] ) ? $options['before_contributorBoxPostDescription'] : '<p class="contributor_post_text">' );
		$after_contributorBoxPostDescription = ( !empty( $options['after_contributorBoxPostDescription'] ) ? $options['after_contributorBoxPostDescription'] : '</p>' );
		$before_contributorBoxPostDescriptionVoid = ( !empty( $options['before_contributorBoxPostDescriptionVoid'] ) ? $options['before_contributorBoxPostDescriptionVoid'] : '<p class="contributor_post_text_void">' );
		$after_contributorBoxPostDescriptionVoid = ( !empty( $options['after_contributorBoxPostDescriptionVoid'] ) ? $options['after_contributorBoxPostDescriptionVoid'] : '</p>' );


	if( !isset($number_posts) || !is_numeric($number_posts) ) $number_posts = 1;
	if( !isset($number_contributors) || !is_numeric($number_contributors) ) $number_contributors = 5;
	if( !isset($contributor_with_description) || !is_numeric($contributor_with_description) ) $contributor_with_description = 1;
	if( !isset($show_posts_count) || !is_numeric($show_posts_count) ) $show_posts_count = 1;
	if( !isset($show_contributor_image) || !is_numeric($show_contributor_image) ) $show_contributor_image = 1;
	if(!is_numeric($post_with_description)) {
		if($post_with_description == "long") {
			$post_with_description = 2;
		} elseif($post_with_description == "short") {
			$post_with_description = 1;
		} elseif ($post_with_description == "no") {
			$post_with_description = 0;
		}
	} else {
		if( !isset($post_with_description) || !is_numeric($post_with_description) ) $post_with_description = 0;
	}

	$post_type = 'contributorpost';
	$taxonomy = 'contributor';
	$content_list = '';

	/*/ Get all the taxonomies for this post type
	$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type ) );

	foreach( $taxonomies as $taxonomy ) : */

		// split the contributor id or slug
		if ( strpos( $except_contributor_id, ',' ) === FALSE ) {
			$except_terms[] = mgr_cp_get_termId_by_id_or_slug($except_contributor_id, $taxonomy);
		} else {
			$arrTerms = explode(",", $except_contributor_id);
			if( is_array($arrTerms) ) {
				foreach( $arrTerms as $id )
					$except_terms[] = mgr_cp_get_termId_by_id_or_slug($id, $taxonomy);
			} else // if split is false, set term id to 0
				$except_terms = null;
		}

		if($except_terms === null) {
			$getTermArgs = array(
				'orderby' => 'count',
				'number' => $number_contributors,
				'order'   => 'DESC'
			);
		} else {
			$getTermArgs = array(
				'orderby' => 'count',
				'exclude' => $except_terms,
				'number' => $number_contributors,
				'order'   => 'DESC'
			);
		}
		$terms = get_terms( $taxonomy, $getTermArgs );

		$content_list .= $after_contributorBoxSeparator. " ";
		$num = 1;
		foreach( $terms as $term ) :
				//if($num > $number_contributors) break;
				$posts = new WP_Query( "taxonomy=$taxonomy&term=$term->slug" );
				$link = get_bloginfo( 'url' ) . '/'. $taxonomy .'/' . $term->slug . '/';

				$content_list .= $before_contributorBox. ' ';
				if($is_taxonomy_images && $show_contributor_image == 1) {
					$img = $taxonomy_images_plugin->get_image_html( 'detail', $term->term_taxonomy_id );
					if( !empty( $img ) ) {
						$content_list .= $before_contributorBoxImage. ' ';
						$content_list .= '<a href="' . $link . '">' . $img . '</a>';
						$content_list .= $after_contributorBoxImage. ' ';
					}
				}

				// if exists $add_querystring_link_contributor add it to link
				if(trim($add_querystring_link_contributor) != '')
					( strpos($link,"?") === false ) ? $link .= '?'.$add_querystring_link_contributor : $link .= '&'.$add_querystring_link_contributor;

				$content_list .= $before_contributorBoxTitle .'<a href="'. $link .'">'. $term->name .'</a>'. $after_contributorBoxTitle;
				if($show_posts_count) {
					$content_list .= ' '.$before_contributorBoxPostCount. $posts->post_count . $after_contributorBoxPostCount.'';
				}
				$content_list .= $before_contributorBoxDescriptionSeparator. ' ';
				if($contributor_with_description) {
					$content_list .= ''. $before_contributorBoxDescription . esc_html( $term->description ) .$after_contributorBoxDescription.' ';
				} else {
					$content_list .= ''. $before_contributorBoxDescription .$after_contributorBoxDescription.' ';
				}

				$content_list .= $before_contributorBoxPosts.' ';


			$numPosts = 1;
			while ( $posts->have_posts() && $numPosts <= $number_posts ) {
				$posts->the_post();
				$content_list .= $before_contributorBoxPostContent.' ';
				$content_list .= $before_contributorBoxPostTitle .'<a href="'. get_permalink() .'">'. get_the_title() .'</a>'.$after_contributorBoxPostTitle;

				switch($post_with_description) {
					case "2":
						$content_list .= ''.$before_contributorBoxPostDescription. mgr_cp_get_the_content_with_formatting() .$after_contributorBoxPostDescription.' ';
						break;
					case "1":
						$content_list .= ''.$before_contributorBoxPostDescription. get_the_excerpt() .$after_contributorBoxPostDescription.' ';
						break;
					default:
						$content_list .= ''.$before_contributorBoxPostDescriptionVoid.' '.$after_contributorBoxPostDescriptionVoid.' ';
				}
				$content_list .= $after_contributorBoxPostContent.' '; // contributor_post_content
				$numPosts++;
			}
			$content_list .= $after_contributorBoxPosts	.' '; // contributor_posts

			$content_list .= $after_contributorBox .' '; // contributor_box

			$num++;
	/*	} */
		endforeach;		// End for terms
	/* endforeach; */ // End for taxonomies
	wp_reset_postdata();

	return $content_list;
}

/* Show the Contributors Info and list of posts */
function mgr_cp_contributors_info_with_posts($contributor_id = 0, $contributor_with_description = 0, $show_posts_count = 0, $show_contributor_image = 1, $number_posts = 0, $post_with_description = 0, $add_querystring_link_contributor = '') {

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	$is_taxonomy_images = 0;

	if(is_plugin_active('taxonomy-images/taxonomy-images.php')) {
			$is_taxonomy_images = 1;
			global $taxonomy_images_plugin;
	}

	$options = get_option( 'mgr_cp_options' );

		// Customize tags
		$after_contributorBoxSeparator = ( !empty( $options['after_contributorBoxSeparator'] ) ? $options['after_contributorBoxSeparator'] : '<hr/>' );
		$before_contributorBoxDescriptionSeparator = ( !empty( $options['before_contributorBoxDescriptionSeparator'] ) ? $options['before_contributorBoxDescriptionSeparator'] : '<br/>' );

		$before_contributorBox = ( !empty( $options['before_contributorBox'] ) ? $options['before_contributorBox'] : '<div class="contributor_box">' );
		$after_contributorBox = ( !empty( $options['after_contributorBox'] ) ? $options['after_contributorBox'] : '</div>' );
		$before_contributorBoxImage = ( !empty( $options['before_contributorBoxImage'] ) ? $options['before_contributorBoxImage'] : '<div class="contributor_box_image">' );
		$after_contributorBoxImage = ( !empty( $options['after_contributorBoxImage'] ) ? $options['after_contributorBoxImage'] : '</div>' );
		$before_contributorBoxTitle = ( !empty( $options['before_contributorBoxTitle'] ) ? $options['before_contributorBoxTitle'] : '<span class="contributor_box_title">' );
		$after_contributorBoxTitle = ( !empty( $options['after_contributorBoxTitle'] ) ? $options['after_contributorBoxTitle'] : '</span>' );
		$before_contributorBoxPostCount = ( !empty( $options['before_contributorBoxPostCount'] ) ? $options['before_contributorBoxPostCount'] : '<span class="contributor_box_postcount">(<strong>' );
		$after_contributorBoxPostCount = ( !empty( $options['after_contributorBoxPostCount'] ) ? $options['after_contributorBoxPostCount'] : '</strong>)</span>' );
		$before_contributorBoxDescription = ( !empty( $options['before_contributorBoxDescription'] ) ? $options['before_contributorBoxDescription'] : '<div class="contributor_box_desc">' );
		$after_contributorBoxDescription = ( !empty( $options['after_contributorBoxDescription'] ) ? $options['after_contributorBoxDescription'] : '</div>' );
		$before_contributorBoxDescriptionVoid = ( !empty( $options['before_contributorBoxDescriptionVoid'] ) ? $options['before_contributorBoxDescriptionVoid'] : '<div class="contributor_box_desc_void">' );
		$after_contributorBoxDescriptionVoid = ( !empty( $options['after_contributorBoxDescriptionVoid'] ) ? $options['after_contributorBoxDescriptionVoid'] : '</div>' );

		$before_contributorBoxPosts = ( !empty( $options['before_contributorBoxPosts'] ) ? $options['before_contributorBoxPosts'] : '<div class="contributor_posts">' );
		$after_contributorBoxPosts = ( !empty( $options['after_contributorBoxPosts'] ) ? $options['after_contributorBoxPosts'] : '</div>' );
		$before_contributorBoxPostContent = ( !empty( $options['before_contributorBoxPostContent'] ) ? $options['before_contributorBoxPostContent'] : '<div class="contributor_post_content">' );
		$after_contributorBoxPostContent = ( !empty( $options['after_contributorBoxPostContent'] ) ? $options['after_contributorBoxPostContent'] : '</div>' );
		$before_contributorBoxPostTitle = ( !empty( $options['before_contributorBoxPostTitle'] ) ? $options['before_contributorBoxPostTitle'] : '<h3 class="contributor_post_title"><strong>' );
		$after_contributorBoxPostTitle = ( !empty( $options['after_contributorBoxPostTitle'] ) ? $options['after_contributorBoxPostTitle'] : '</strong></h3><br/>' );
		$before_contributorBoxPostDescription = ( !empty( $options['before_contributorBoxPostDescription'] ) ? $options['before_contributorBoxPostDescription'] : '<p class="contributor_post_text">' );
		$after_contributorBoxPostDescription = ( !empty( $options['after_contributorBoxPostDescription'] ) ? $options['after_contributorBoxPostDescription'] : '</p>' );
		$before_contributorBoxPostDescriptionVoid = ( !empty( $options['before_contributorBoxPostDescriptionVoid'] ) ? $options['before_contributorBoxPostDescriptionVoid'] : '<p class="contributor_post_text_void">' );
		$after_contributorBoxPostDescriptionVoid = ( !empty( $options['after_contributorBoxPostDescriptionVoid'] ) ? $options['after_contributorBoxPostDescriptionVoid'] : '</p>' );


	//if( !isset($contributorId) || !is_numeric($contributorId) ) $contributorId = 0;
	if( !isset($contributor_with_description) || !is_numeric($contributor_with_description) ) $contributor_with_description = 0;
	if( !isset($show_posts_count) || !is_numeric($show_posts_count) ) $show_posts_count = 0;
	if( !isset($show_contributor_image) || !is_numeric($show_contributor_image) ) $show_contributor_image = 1;
	if( !isset($number_posts) || !is_numeric($number_posts) ) $number_posts = 0;

	if(!is_numeric($post_with_description)) {
		if($post_with_description == "long") {
			$post_with_description = 2;
		} elseif($post_with_description == "short") {
			$post_with_description = 1;
		} elseif ($post_with_description == "no") {
			$post_with_description = 0;
		}
	} else {
		if( !isset($post_with_description) || !is_numeric($post_with_description) ) $post_with_description = 0;
	}

	$term = null;
	$terms = array();
	$post_type = 'contributorpost';
	$taxonomy = 'contributor';
	$content_list = '';

	/*/ Get all the taxonomies for this post type
	$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type ) );

	foreach( $taxonomies as $taxonomy ) : */

		/*/ split the contributor id or slug
		if ( strpos( $contributor_id, ',' ) === FALSE ) {
			$terms[] = mgr_cp_get_term_by_id_or_slug($contributor_id, $taxonomy);
		} else {
			$arrTerms = explode(",", $contributor_id);
			if( is_array($arrTerms) ) {
				foreach( $arrTerms as $id )
					$terms[] = mgr_cp_get_term_by_id_or_slug($id, $taxonomy);
			} else // if split is false, set term id to 0
				$terms[0] = 0;
		} */

		// split the contributor id or slug
		if ( strpos( $contributor_id, ',' ) === FALSE ) {
			$include_terms[] = mgr_cp_get_termId_by_id_or_slug($contributor_id, $taxonomy);
		} else {
			$arrTerms = explode(",", $contributor_id);
			if( is_array($arrTerms) ) {
				foreach( $arrTerms as $id )
					$include_terms[] = mgr_cp_get_termId_by_id_or_slug($id, $taxonomy);
			} else // if split is false, set term id to 0
				$include_terms = null;
		}

		if($include_terms !== null) {
			$getTermArgs = array(
				'orderby' => 'count',
				'include' => $include_terms,
				//'number' => $number_contributors,
				'order'   => 'DESC'
			);
		} else {
			$getTermArgs = null;
		}
		$terms = get_terms( $taxonomy, $getTermArgs );

		$content_list .= $after_contributorBoxSeparator. " ";

		foreach( $terms as $term ) :
			$posts = new WP_Query( "taxonomy=$taxonomy&term=$term->slug" );
			$link = get_bloginfo( 'url' ) . '/'. $taxonomy .'/' . $term->slug . '/';

			$content_list .= $before_contributorBox. ' ';
			if($is_taxonomy_images && $show_contributor_image == 1) {
				$img = $taxonomy_images_plugin->get_image_html( 'detail', $term->term_taxonomy_id );
				if( !empty( $img ) ) {
					$content_list .= ''.$before_contributorBoxImage;
					$content_list .= '<a href="' . $link . '">' . $img . '</a>';
					$content_list .= $after_contributorBoxImage .' '; // contributor_box_image
				}
			}

			// if exists $add_querystring_link_contributor add it to link
			if(trim($add_querystring_link_contributor) != '')
				( strpos($link,"?") === false ) ? $link .= '?'.$add_querystring_link_contributor : $link .= '&'.$add_querystring_link_contributor;
			$content_list .= $before_contributorBoxTitle .'<a href="'. $link .'">'. $term->name .'</a>' .$after_contributorBoxTitle .' ';
			if($show_posts_count) {
				$content_list .= ' '.$before_contributorBoxPostCount. $posts->post_count .$after_contributorBoxPostCount.' ';
			}
			$content_list .= $before_contributorBoxDescriptionSeparator .' ';
			if($contributor_with_description) {
				$content_list .= ''.$before_contributorBoxDescription	. $term->description .$after_contributorBoxDescription.' ';
			} else {
				$content_list .= ' '.$before_contributorBoxDescription .' '. $after_contributorBoxDescriptionVoid .' ';
			}

			$content_list .= $before_contributorBoxPosts .' ';

			$numPosts = 1;
			while ( $posts->have_posts() && $numPosts <= $number_posts ) {
				$posts->the_post();
				$content_list .= $before_contributorBoxPostContent. ' ';
				$content_list .= $before_contributorBoxPostTitle. '<a href="'. get_permalink() .'">'. get_the_title() .'</a>'.$after_contributorBoxPostTitle.' ';

				switch($post_with_description) {
					case "2":
						$content_list .= $before_contributorBoxPostDescription.''. mgr_cp_get_the_content_with_formatting() .$after_contributorBoxPostDescription	.' ';
						break;
					case "1":
						$content_list .= $before_contributorBoxPostDescription.''.  get_the_excerpt() .$after_contributorBoxPostDescription	.' ';
						break;
					default:
						$content_list .= $before_contributorBoxPostDescriptionVoid .' '. $after_contributorBoxPostDescriptionVoid .' ';
				}
				$content_list .= $after_contributorBoxPostContent .' '; // contributor_post_content
				$numPosts++;
			}
			$content_list .= $after_contributorBoxPosts. ' '; // contributor_posts

			$content_list .= $after_contributorBox	.' '; // contributor_box

		endforeach;		// End for terms
	/* endforeach; */ // End for taxonomies
	wp_reset_postdata();

	return $content_list;
}

function mgr_cp_rand_contributors_info_with_posts($contributor_with_description = 0, $show_posts_count = 0, $show_contributor_image = 1, $number_posts = 0, $post_with_description = 0, $except_contributor_id = 0, $add_querystring_link_contributor = '' ) {

		$taxonomy = "contributor";
		$contributor_id = 0;
		$is_except = true;

		/*/ split the contributor id or slug
		if ( strpos( $except_contributor_id, ',' ) === FALSE ) {
			$contributors[] = $except_contributor_id;
		} else {
			$arrContributors = explode(",", $except_contributor_id);
			if( is_array($arrContributors) ) {
				foreach( $arrContributors as $id )
					$contributors[] = $id;
			} else // if split is false, set term id to 0
				$contributors[0] = 0;
		}

		// check to avoid an infinite loop
		$terms = get_terms( $taxonomy );
		if( count($terms) > count($contributors) ) {

			while($is_except) {
				$contributor = mgr_cp_get_rand_term_id($taxonomy);
				if( !in_array($contributor->term_id, $contributors) && !in_array($contributor->slug, $contributors) ) {
					$is_except = false;
				}
			}
			$contributor_id = $contributor->term_id;
		} */

		// split the contributor id or slug
		if ( strpos( $except_contributor_id, ',' ) === FALSE ) {
			$except_terms[] = mgr_cp_get_termId_by_id_or_slug($except_contributor_id, $taxonomy);
		} else {
			$arrTerms = explode(",", $except_contributor_id);
			if( is_array($arrTerms) ) {
				foreach( $arrTerms as $id )
					$except_terms[] = mgr_cp_get_termId_by_id_or_slug($id, $taxonomy);
			} else // if split is false, set term id to 0
				$except_terms = null;
		}

		if($except_terms === null) {
			$getTermArgs = array(
				'orderby' => 'count',
				'order'   => 'DESC'
			);
		} else {
			$getTermArgs = array(
				'orderby' => 'count',
				'exclude' => $except_terms,
				'order'   => 'DESC'
			);
		}
		//$terms = get_terms( $taxonomy, $getTermArgs );
		$contributor = mgr_cp_get_rand_term_id($taxonomy, $getTermArgs);
		$contributor_id = $contributor->term_id;

//$debug =  "[debug] contributor_id: ". $contributor_id ." // contributor_with_description: ". $contributor_with_description ." // ";
//$debug .= "show_posts_count: ". $show_posts_count ." // number_posts: ". $number_posts ." // post_with_description: ". $post_with_description ." <hr>";
		return mgr_cp_contributors_info_with_posts($contributor_id, $contributor_with_description, $show_posts_count, $number_posts, $post_with_description);
}