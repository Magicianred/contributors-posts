<?php

/* CUSTOM TYPE */
function mgr_cp_contributorpost() {
	$labels = array(
		'name'               => _x( 'Contributors Posts', 'post type general name' ),
		'singular_name'      => _x( 'Contributor Post', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'contributorpost' ),
		'add_new_item'       => __( 'Add New Contributor Post', 'mgr_cp' ),
		'edit_item'          => __( 'Edit Contributor Post', 'mgr_cp' ),
		'new_item'           => __( 'New Contributor Post', 'mgr_cp' ),
		'all_items'          => __( 'All Contributors Posts', 'mgr_cp' ),
		'view_item'          => __( 'View Contributor Post', 'mgr_cp' ),
		'search_items'       => __( 'Search Contributors Posts', 'mgr_cp' ),
		'not_found'          => __( 'No contributors posts found', 'mgr_cp' ),
		'not_found_in_trash' => __( 'No contributors posts found in the Trash', 'mgr_cp' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Contributors Posts'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __('Holds our contributors and contributors posts specific data', 'mgr_cp'),
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'custom-fields', 'thumbnail', 'excerpt', 'comments' ),
		'has_archive'   => true,
    	'taxonomies' => array('category', 'post_tag')
	);
	register_post_type( 'contributorpost', $args );
}
add_action( 'init', 'mgr_cp_contributorpost', 1 );


/* TAXONOMY */
function mgr_cp_taxonomies_contributorpost() {
	$labels = array(
		'name'              => _x( 'Contributors', 'taxonomy general name', 'mgr_cp' ),
		'singular_name'     => _x( 'Contributor', 'taxonomy singular name', 'mgr_cp' ),
		'search_items'      => __( 'Search Contributor', 'mgr_cp' ),
		'all_items'         => __( 'All Contributors', 'mgr_cp' ),
		'parent_item'       => __( 'Parent Contributor', 'mgr_cp' ),
		'parent_item_colon' => __( 'Parent Contributor:', 'mgr_cp' ),
		'edit_item'         => __( 'Edit Contributor', 'mgr_cp' ),
		'update_item'       => __( 'Update Contributor', 'mgr_cp' ),
		'add_new_item'      => __( 'Add New Contributor', 'mgr_cp' ),
		'new_item_name'     => __( 'New Contributor', 'mgr_cp' ),
		'menu_name'         => __( 'Contributors', 'mgr_cp' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
	);

	$options = array();
	$contributor_associate_post_type = array('contributorpost');
	global $wpdb;
	$result = $wpdb->get_var("SELECT option_value FROM ". $wpdb->options ." WHERE option_name = 'mgr_cp_options' " );
	if($result != '') $options = unserialize($result);
	if(isset($options['post_types_associate_contributor'])) {
		$contributor_associate_post_type = explode(",", $options['post_types_associate_contributor']);
	} else $contributor_associate_post_type = array('contributorpost');

	$post_types_availables = get_post_types( '', 'names' );
	// verify the post types is exists at moment
	$contributor_associate_post_type = array_intersect($contributor_associate_post_type, $post_types_availables);

	register_taxonomy( 'contributor', $contributor_associate_post_type, $args );
}
add_action( 'init', 'mgr_cp_taxonomies_contributorpost', 2 );




// add view Contributors Posts to tags and category archives
function mgr_cp_add_custom_types_to_tax( $query ) {
            if( !empty( $query->query_vars['suppress_filters'] ) ) // TODO check if necessary
                return $query;
	global $wp, $wpdb;
	$taxonomy_types = array('tag','category');
	$custom_post_type = array('contributorpost');
	$current_post_types = array();
	$merge_post_types = array();
	$bChange = false;

	$result = $wpdb->get_var("SELECT option_value FROM ". $wpdb->options ." WHERE option_name = 'mgr_cp_options' " );

	if($result != '') $options = unserialize($result);
	if(isset($options['contributorpost_show_archives'])) {
		$post_types_show_archives = explode(",", $options['contributorpost_show_archives']);
	} else $post_types_show_archives = array( );

	// add custom post to tag archive
	if(in_array("tag",$post_types_show_archives)) {
		if( !is_admin() && is_tag() && empty( $query->query_vars['suppress_filters'] ) && $query->is_main_query()  ) {
			$current_post_types = $query->get( 'post_type' );
			if ( !$current_post_types || $current_post_types == 'post' )
					$current_post_types = array( 'post', 'contributorpost' );
			elseif ( is_array( $current_post_types ) )
					array_push( $current_post_types, 'contributorpost' );
			$bChange=true;

			/*if(is_array($current_post_types))
				$merge_post_types = array_merge($custom_post_type,$current_post_types);
			else
				$merge_post_types = $custom_post_type;*/
		}
	}

	// add custom post to category archive
	if(in_array("category",$post_types_show_archives)) {
		if( !is_admin() && is_category() && empty( $query->query_vars['suppress_filters'] ) && $query->is_main_query()  ) {

			$current_post_types = $query->get( 'post_type' );
			if ( !$current_post_types || $current_post_types == 'post' )
					$current_post_types = array( 'post', 'contributorpost' );
			elseif ( is_array( $current_post_types ) )
					array_push( $current_post_types, 'contributorpost' );
			$bChange=true;

			/*if(is_array($current_post_types))
				$merge_post_types = array_merge($custom_post_type,$current_post_types);
			else
				$merge_post_types = $custom_post_type;*/
		}
	}

	// add custom post to search
	if(in_array("search",$post_types_show_archives)) {
		if ( !is_admin() && is_search() && $query->is_main_query() ) {

			$current_post_types = $query->get( 'post_type' );
			if ( !$current_post_types || $current_post_types == 'post' )
					$current_post_types = array( 'post', 'contributorpost' );
			elseif ( is_array( $current_post_types ) )
					array_push( $current_post_types, 'contributorpost' );
			$bChange=true;
		}
	}

	if($bChange==true)
		$query->set( 'post_type', $current_post_types );
	return $query;
}
add_filter( 'pre_get_posts', 'mgr_cp_add_custom_types_to_tax' );

