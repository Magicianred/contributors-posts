<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

    <?php
    $mypost = array( 'post_type' => 'contributorsposts', );
    $loop = new WP_Query( $mypost );
    ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
					</nav><!-- #nav-single -->

					<div class="ContributorInfo">
					<?php
						$terms = wp_get_post_terms( get_the_ID(), 'contributor', array("fields" => "ids"));
						$term = implode(', ', $terms);
						print do_shortcode('[contributorsInfo contributor_id="'. $term .'" contributor_with_description="1" show_posts_count="0"  number_posts="0" post_with_description="0"]');
					?>
					</div>

					<?php get_template_part( 'content', get_post_format() ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>