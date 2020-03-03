<?php


/* Add Stylesheet for Plugin */
function mgr_cp_queue_stylesheet() {
	if ( is_readable( plugin_dir_path( __FILE__ ) . '../styles/custom-style.css' ) ) {
			wp_enqueue_style( 'mgr_cp_stylesheet', plugins_url( 'custom-style.css', __FILE__ ) );  //plugin_dir_url( __FILE__ ) . 'custom-style.css' );
	} else {
			wp_enqueue_style( 'mgr_cp_stylesheet', plugins_url( '../styles/style.css', __FILE__ ) );
	}
}
add_action( 'wp_enqueue_scripts', 'mgr_cp_queue_stylesheet');




function mgr_cp_get_the_content_with_formatting ($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}


register_activation_hook( __FILE__ , 'mgr_cp_set_default_options_array' );

function mgr_cp_get_rand_term_id($taxonomy, $args = null) {
		if($args === null)
			$terms = get_terms( $taxonomy );
		else
			$terms = get_terms( $taxonomy, $args );
		if(count($terms)>1) {
			$random = rand(0,count($terms)-1); //get a random number from 0 to number of elements in $terms array
			$contributor = $terms[$random];
		} else {
			$contributor = $terms[0];
		}
		return $contributor;
}

/**
 * Get Term Id from a Term Id or Slug
 */
function mgr_cp_get_termId_by_id_or_slug($contributor_id = 0, $taxonomy) {
	$termId = 0;
		if( $contributor_id != 0 && is_numeric($contributor_id) ) {
			// Contributor Id
			//$term = get_term( $contributor_id, $taxonomy );
			$termId = $contributor_id;
		} elseif ( !is_numeric($contributor_id) ) {
			// Contributor Slug
			$term = get_term_by('slug', $contributor_id, $taxonomy );
			$termId = $term->term_id;
			// If term is null yet, I get default Term
			if ( is_null($term) ) {
				$termId = 0;
			}
		}
		return $termId;
}

/**
 * Get Term from a Term Id or Slug
 */
function mgr_cp_get_term_by_id_or_slug($contributor_id = 0, $taxonomy) {
		if( $contributor_id != 0 && is_numeric($contributor_id) ) {
			// Contributor Id
			//$term = get_term( $contributor_id, $taxonomy );
			$term = get_term_by('id', $contributor_id, $taxonomy );
		} elseif ( !is_numeric($contributor_id) ) {
			// Contributor Slug
			$term = get_term_by('slug', $contributor_id, $taxonomy );
		}
		// If term is null yet, I get default Term
		if ( is_null($term) ) {
			$terms = get_terms( $taxonomy );
			$term = $terms[0];
		}
		return $term;
}

/**
 * Get Args for filter Term (It does not used yet)
 */
function mgr_cp_get_contributor_queryarg($contributor_id, $taxonomy, $bExcept) {
	$operator = ($bExcept) ? 'NOT IN' : 'IN';
	$id_or_slug = ( $contributor_id != 0 && is_numeric($contributor_id) ) ? 'id' : 'slug';
	$contributor = array(
			'taxonomy'  => $taxonomy,
			'field'     => $id_or_slug,
			'terms'     => $contributor_id,
			'operator'  => $operator,
	);
	return $contributor;
}
function mgr_cp_get_contributor_queryargs($contributor_id, $taxonomy, $bExcept) {
	if ( strpos( $contributor_id, ',' ) === FALSE ) {
		$args[] =mgr_cp_get_contributor_queryarg($contributor_id, $taxonomy, $bExcept);
		return $args;
	} else {
		$arrTerms = explode(",", $contributor_id);
		if( is_array($arrTerms) ) {
			$args['relation'] = 'AND';
			foreach( $arrTerms as $id )
				$args[] = mgr_cp_get_contributor_queryarg($id, $taxonomy, $bExcept);
		} else // if split is false, set term id to 0
			$args[0] = 0;
		return $args;
	}
}

