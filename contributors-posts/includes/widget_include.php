<?php
/* Create Widget for show list of Authors */
class mgr_cp_ContributorsListWidget extends WP_Widget {

	function mgr_cp_ContributorsListWidget() {
			parent::__construct( false, 'Contributors List' );
	}

	function widget( $args, $instance ) {

			extract($args);
			$title 		= apply_filters( 'widget_title', $instance['title'] );
			$number_posts 	= isset( $instance['number_posts'] ) ? absint( $instance['number_posts'] ) : 1;
			$number_contributors 	= isset( $instance['number_contributors'] ) ? absint( $instance['number_contributors'] ) : 5;
			$contributor_with_description 	= isset( $instance['contributor_with_description'] ) ? absint( $instance['contributor_with_description'] ) : 1;
			$show_posts_count = isset( $instance['show_posts_count'] ) ? absint( $instance['show_posts_count'] ) : 0;
			$show_contributor_image = isset( $instance['show_contributor_image'] ) ? absint( $instance['show_contributor_image'] ) : 1;
			$add_querystring_link_contributor = isset( $instance['add_querystring_link_contributor'] ) ? ( $instance['add_querystring_link_contributor'] ) : '';
			$post_with_description 	= isset( $instance['post_with_description'] ) ? absint( $instance['post_with_description'] ) : 0;
			$except_contributor_id 	= isset( $instance['except_contributor_id'] ) ? ( $instance['except_contributor_id'] ) : 0;

			$before_widget = ( !empty( $before_widget ) ? $before_widget : '<div class="ContributorsListWidget">' );
			$after_widget = ( !empty( $after_widget ) ? $after_widget : '</div>' );
			$before_title = ( !empty( $before_title ) ? $before_title : '<h2 class="ContributorsListTitle">' );
			$after_title = ( !empty( $after_title ) ? $after_title : '</h2>' );

			print $before_widget;
			if(!empty($instance['title']) && ($instance['title'] != ''))
				print $before_title.$instance['title'].$after_title;

			print mgr_cp_contributors_list_with_posts( $number_contributors,$contributor_with_description, $show_posts_count, $show_contributor_image, $number_posts, $post_with_description, $except_contributor_id, $add_querystring_link_contributor );

			print $after_widget;
	}

	function update($new_instance, $old_instance) { // in update if the value not exists set 0
			$instance 				= $old_instance;
			$instance['title'] 		= isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number_posts'] 	= isset( $new_instance['number_posts'] ) ? strip_tags( $new_instance['number_posts'] ) : 1;
			$instance['number_contributors'] 	= isset( $new_instance['number_contributors'] ) ? strip_tags( $new_instance['number_contributors'] ) : 5;
			$instance['contributor_with_description'] 	= isset( $new_instance['contributor_with_description'] ) ? strip_tags( $new_instance['contributor_with_description'] ) : 0;
			$instance['show_posts_count'] 	= isset( $new_instance['show_posts_count'] ) ? strip_tags( $new_instance['show_posts_count'] ) : 0;
			$instance['show_contributor_image'] 	= isset( $new_instance['show_contributor_image'] ) ? strip_tags( $new_instance['show_contributor_image'] ) : 0;
			$instance['add_querystring_link_contributor'] 	= isset( $new_instance['add_querystring_link_contributor'] ) ? strip_tags( $new_instance['add_querystring_link_contributor'] ) : '';
			$instance['post_with_description'] 	= isset( $new_instance['post_with_description'] ) ? strip_tags( $new_instance['post_with_description'] ) : 0;
			$instance['except_contributor_id'] 	= isset( $new_instance['except_contributor_id'] ) ? strip_tags( $new_instance['except_contributor_id'] ) : 0;
			return $instance;
	}

	function form( $instance ) {
		//var_dump($instance);
			$title 			= isset( $instance['title'] )  ? esc_attr( $instance['title'] ) : '';
			$number_posts			= isset( $instance['number_posts'] ) ? absint( $instance['number_posts'] )  : 1;
			$number_contributors			= isset( $instance['number_contributors'] ) ? absint( $instance['number_contributors'] )  : 1;
			$contributor_with_description			= isset( $instance['contributor_with_description'] ) ? absint( $instance['contributor_with_description'] )  : 1;
			$show_posts_count			= isset( $instance['show_posts_count'] ) ? absint( $instance['show_posts_count'] )  : 0;
			$show_contributor_image			= isset( $instance['show_contributor_image'] ) ? absint( $instance['show_contributor_image'] )  : 1;
			$add_querystring_link_contributor			= isset( $instance['add_querystring_link_contributor'] ) ? ( $instance['add_querystring_link_contributor'] )  : '';
			$post_with_description			= isset( $instance['post_with_description'] ) ? absint( $instance['post_with_description'] )  : 0;
			$except_contributor_id			= isset( $instance['except_contributor_id'] ) ? ( $instance['except_contributor_id'] )  : 0;
//echo "[debug] contributor_with_description: ". $contributor_with_description ." // number_posts: ". $number_posts ."<br>";
//echo " show_posts_count: ". $show_posts_count ." // post_with_description: ". $post_with_description ."<hr>";
//echo " show_contributor_image: ". $show_contributor_image ." // add_querystring_link_contributor: ". $add_querystring_link_contributor ."<hr>";
			?>
			<p><label for="<?php echo $this->get_field_id('title');?>">
			<?php _e('Title', 'mgr_cp') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title; ?>" />
			</label></p>
			<p>
				<input id="<?php echo $this->get_field_id('number_contributors'); ?>" class="small-text" name="<?php echo $this->get_field_name('number_contributors'); ?>" type="number_contributors" min="1" step="1" value="<?php echo esc_attr( $number_contributors ); ?>" />
				<label for="<?php echo $this->get_field_id('number_contributors'); ?>"><?php _e('Maximum Contributors to show', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('show_posts_count'); ?>" name="<?php echo $this->get_field_name('show_posts_count'); ?>" type="checkbox" value="1" <?php checked( '1', $show_posts_count ); ?>/>
        <label for="<?php echo $this->get_field_id('show_posts_count'); ?>"><?php _e('Show posts count', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('show_contributor_image'); ?>" name="<?php echo $this->get_field_name('show_contributor_image'); ?>" type="checkbox" value="1" <?php checked( '1', $show_contributor_image ); ?>/>
        <label for="<?php echo $this->get_field_id('show_contributor_image'); ?>"><?php _e('Show Contributor Image', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('contributor_with_description'); ?>" name="<?php echo $this->get_field_name('contributor_with_description'); ?>" type="checkbox" value="1" <?php checked( '1', $contributor_with_description ); ?>/>
        <label for="<?php echo $this->get_field_id('contributor_with_description'); ?>"><?php _e('Add description of contributor', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('number_posts'); ?>" class="small-text" name="<?php echo $this->get_field_name('number_posts'); ?>" type="number_posts" min="1" step="1" value="<?php echo esc_attr( $number_posts ); ?>" />
				<label for="<?php echo $this->get_field_id('number_posts'); ?>"><?php _e('Max number of post for each contributor (can be 0)', 'mgr_cp'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'post_with_description' ); ?> "><?php _e('Choose how to show post description', 'mgr_cp'); ?></label>
				<select id="<?php echo $this->get_field_id( 'post_with_description' ); ?>" name="<?php echo $this->get_field_name( 'post_with_description' ); ?>">
						 <option value="0" <?php selected($post_with_description, '0'); ?>><?php _e('No description', 'mgr_cp'); ?></option>
						 <option value="1" <?php selected($post_with_description, '1'); ?>><?php _e('Short description', 'mgr_cp'); ?></option>
						 <option value="2" <?php selected($post_with_description, '2'); ?>><?php _e('Long description', 'mgr_cp'); ?></option>
				</select>
			</p>
			<p><label for="<?php echo $this->get_field_id('except_contributor_id');?>">
			<?php _e('<strong>Except these</strong> Contributor(s) Id or Slug (divide by comma)', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('except_contributor_id');?>" name="<?php echo $this->get_field_name('except_contributor_id');?>" type="text" value="<?php echo $except_contributor_id; ?>" />
			</label></p>
			<p><label for="<?php echo $this->get_field_id('add_querystring_link_contributor');?>">
			<?php _e('Set Querystring at Contributor Link (without question mark [?]. e.g.: <em>onlybio=true&short=false</em>)', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('add_querystring_link_contributor');?>" name="<?php echo $this->get_field_name('add_querystring_link_contributor');?>" type="text" value="<?php echo $add_querystring_link_contributor; ?>" />
			</label></p>
			<?php
	}
}

function mgr_cp_register_contributorsListWidgets() {
		register_widget( 'mgr_cp_ContributorsListWidget' );
}

add_action( 'widgets_init', 'mgr_cp_register_contributorsListWidgets' );



/* Create Widget for show Author(s) Info */
class mgr_cp_ContributorsInfoWidget extends WP_Widget {

	function mgr_cp_ContributorsInfoWidget() {
			parent::__construct( false, 'Contributor(s) Info' );
	}

	function widget( $args, $instance ) {

			extract($args);
			$title 		= apply_filters( 'widget_title', $instance['title'] );
			$contributor_id = isset( $instance['contributor_id'] ) ? ( $instance['contributor_id'] ) : 0;
			$contributor_with_description 	= isset( $instance['contributor_with_description'] ) ? absint( $instance['contributor_with_description'] ) : 1;
			$number_posts 	= isset( $instance['number_posts'] ) ? absint( $instance['number_posts'] ) : 0;
			$show_posts_count = isset( $instance['show_posts_count'] ) ? absint( $instance['show_posts_count'] ) : 0;
			$show_contributor_image = isset( $instance['show_contributor_image'] ) ? absint( $instance['show_contributor_image'] ) : 1;
			$add_querystring_link_contributor = isset( $instance['add_querystring_link_contributor'] ) ? ( $instance['add_querystring_link_contributor'] ) : '';
			$post_with_description 	= isset( $instance['post_with_description'] ) ? absint( $instance['post_with_description'] ) : 0;
//echo "[debug] contributor_id: ". $contributor_id ." // contributor_with_description: ". $contributor_with_description ." // number_posts: ". $number_posts ."<br>";
//echo " show_posts_count: ". $show_posts_count ." // post_with_description: ". $post_with_description ."<hr>";

			$before_widget = ( !empty( $before_widget ) ? $before_widget : '<div class="ContributorsInfoWidget">' );
			$after_widget = ( !empty( $after_widget ) ? $after_widget : '</div>' );
			$before_title = ( !empty( $before_title ) ? $before_title : '<h2 class="ContributorsInfoTitle">' );
			$after_title = ( !empty( $after_title ) ? $after_title : '</h2>' );

			print $before_widget;
			if(!empty($instance['title']) && ($instance['title'] != ''))
				print $before_title.$instance['title'].$after_title;
			print mgr_cp_contributors_info_with_posts( $contributor_id, $contributor_with_description, $show_posts_count, $show_contributor_image, $number_posts, $post_with_description, $add_querystring_link_contributor );

			print $after_widget;
	}

	function update($new_instance, $old_instance) { // in update if the value not exists set 0
			$instance 				= $old_instance;
			$instance['title'] 		= isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['contributor_id'] 	= isset( $new_instance['contributor_id'] ) ? strip_tags( $new_instance['contributor_id'] ) : 0;
			$instance['contributor_with_description'] 	= isset( $new_instance['contributor_with_description'] ) ? strip_tags( $new_instance['contributor_with_description'] ) : 0;
			$instance['number_posts'] 	= isset( $new_instance['number_posts'] ) ? strip_tags( $new_instance['number_posts'] ) : 0;
			$instance['show_contributor_image'] 	= isset( $new_instance['show_contributor_image'] ) ? strip_tags( $new_instance['show_contributor_image'] ) : 0;
			$instance['add_querystring_link_contributor'] 	= isset( $new_instance['add_querystring_link_contributor'] ) ? strip_tags( $new_instance['add_querystring_link_contributor'] ) : '';
			$instance['show_posts_count'] 	= isset( $new_instance['show_posts_count'] ) ? strip_tags( $new_instance['show_posts_count'] ) : 0;
			$instance['post_with_description'] 	= isset( $new_instance['post_with_description'] ) ? strip_tags( $new_instance['post_with_description'] ) : 0;
			return $instance;
	}

	function form( $instance ) {
			$title 			= isset( $instance['title'] )  ? esc_attr( $instance['title'] ) : '';
			$contributor_id			= isset( $instance['contributor_id'] ) ? esc_attr( $instance['contributor_id'] )  : 0;
			$contributor_with_description			= isset( $instance['contributor_with_description'] ) ? absint( $instance['contributor_with_description'] )  : 1;
			$show_posts_count			= isset( $instance['show_posts_count'] ) ? absint( $instance['show_posts_count'] )  : 0;
			$number_posts			= isset( $instance['number_posts'] ) ? absint( $instance['number_posts'] )  : 0;
			$show_contributor_image			= isset( $instance['show_contributor_image'] ) ? absint( $instance['show_contributor_image'] )  : 1;
			$add_querystring_link_contributor			= isset( $instance['add_querystring_link_contributor'] ) ? ( $instance['add_querystring_link_contributor'] )  : '';
			$post_with_description			= isset( $instance['post_with_description'] ) ? absint( $instance['post_with_description'] )  : 0;
//echo "[debug] contributor_id: ". $contributor_id ." // contributor_with_description: ". $contributor_with_description ." // number_posts: ". $number_posts ."<br>";
//echo " show_posts_count: ". $show_posts_count ." // post_with_description: ". $post_with_description ."<hr>";
			?>
			<p><label for="<?php echo $this->get_field_id('title');?>">
			<?php _e('Title', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title; ?>" />
			</label></p>
			<p><label for="<?php echo $this->get_field_id('contributor_id');?>">
			<?php _e('Contributor(s) Id or Slug (divide by comma)', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('contributor_id');?>" name="<?php echo $this->get_field_name('contributor_id');?>" type="text" value="<?php echo $contributor_id; ?>" />
			</label></p>
			<p>
				<input id="<?php echo $this->get_field_id('contributor_with_description'); ?>" name="<?php echo $this->get_field_name('contributor_with_description'); ?>" type="checkbox" value="1" <?php checked( '1', $contributor_with_description ); ?>/>
        <label for="<?php echo $this->get_field_id('contributor_with_description'); ?>"><?php _e('Add description of contributor', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('show_posts_count'); ?>" name="<?php echo $this->get_field_name('show_posts_count'); ?>" type="checkbox" value="1" <?php checked( '1', $show_posts_count ); ?>/>
        <label for="<?php echo $this->get_field_id('show_posts_count'); ?>"><?php _e('Show posts count', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('show_contributor_image'); ?>" name="<?php echo $this->get_field_name('show_contributor_image'); ?>" type="checkbox" value="1" <?php checked( '1', $show_contributor_image ); ?>/>
        <label for="<?php echo $this->get_field_id('show_contributor_image'); ?>"><?php _e('Show Contributor Image', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('number_posts'); ?>" class="small-text" name="<?php echo $this->get_field_name('number_posts'); ?>" type="number_posts" min="1" step="1" value="<?php echo esc_attr( $number_posts ); ?>" />
				<label for="<?php echo $this->get_field_id('number_posts'); ?>"><?php _e('Max number of post for each contributor (can be 0)', 'mgr_cp'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'post_with_description' ); ?> "><?php _e('Choose how to show post description'); ?></label>
				<select id="<?php echo $this->get_field_id( 'post_with_description' ); ?>" name="<?php echo $this->get_field_name( 'post_with_description' ); ?>">
						 <option value="0" <?php selected($post_with_description, '0'); ?>><?php _e('No description', 'mgr_cp'); ?></option>
						 <option value="1" <?php selected($post_with_description, '1'); ?>><?php _e('Short description', 'mgr_cp'); ?></option>
						 <option value="2" <?php selected($post_with_description, '2'); ?>><?php _e('Long description', 'mgr_cp'); ?></option>
				</select>
			</p>
			<p><label for="<?php echo $this->get_field_id('add_querystring_link_contributor');?>">
			<?php _e('Set Querystring at Contributor Link (without question mark [?]. e.g.: <em>onlybio=true&short=false</em>)', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('add_querystring_link_contributor');?>" name="<?php echo $this->get_field_name('add_querystring_link_contributor');?>" type="text" value="<?php echo $add_querystring_link_contributor; ?>" />
			</label></p>
			<?php
	}
}

function mgr_cp_register_contributorsInfoWidgets() {
		register_widget( 'mgr_cp_ContributorsInfoWidget' );
}

add_action( 'widgets_init', 'mgr_cp_register_contributorsInfoWidgets' );

/* Create Widget for show Author(s) Info Random */
class mgr_cp_RandContributorsInfoWidget extends WP_Widget {

	function mgr_cp_RandContributorsInfoWidget() {
			parent::__construct( false, 'Contributor(s) Info [Random]' );
	}

	function widget( $args, $instance ) {

			extract($args);
			$title 		= apply_filters( 'widget_title', $instance['title'] );
			$contributor_with_description 	= isset( $instance['contributor_with_description'] ) ? absint( $instance['contributor_with_description'] ) : 1;
			$number_posts 	= isset( $instance['number_posts'] ) ? absint( $instance['number_posts'] ) : 0;
			$show_contributor_image 	= isset( $instance['show_contributor_image'] ) ? absint( $instance['show_contributor_image'] ) : 1;
			$add_querystring_link_contributor 	= isset( $instance['add_querystring_link_contributor'] ) ? ( $instance['add_querystring_link_contributor'] ) : '';
			$show_posts_count = isset( $instance['show_posts_count'] ) ? absint( $instance['show_posts_count'] ) : 0;
			$post_with_description 	= isset( $instance['post_with_description'] ) ? absint( $instance['post_with_description'] ) : 0;
			$except_contributor_id = isset( $instance['except_contributor_id'] ) ? ( $instance['except_contributor_id'] ) : 0;
//echo "[debug] contributor_id: ". $contributor_id ." // contributor_with_description: ". $contributor_with_description ." // number_posts: ". $number_posts ."<br>";
//echo " show_posts_count: ". $show_posts_count ." // post_with_description: ". $post_with_description ."<hr>";

			$before_widget = ( !empty( $before_widget ) ? $before_widget : '<div class="ContributorsInfoWidget">' );
			$after_widget = ( !empty( $after_widget ) ? $after_widget : '</div>' );
			$before_title = ( !empty( $before_title ) ? $before_title : '<h2 class="ContributorsInfoTitle">' );
			$after_title = ( !empty( $after_title ) ? $after_title : '</h2>' );

			print $before_widget;
			if(!empty($instance['title']) && ($instance['title'] != ''))
				print $before_title.$instance['title'].$after_title;
			print mgr_cp_rand_contributors_info_with_posts( $contributor_with_description, $show_posts_count, $show_contributor_image, $number_posts, $post_with_description, $except_contributor_id, $add_querystring_link_contributor );

			print $after_widget;
	}

	function update($new_instance, $old_instance) { // in update if the value not exists set 0
			$instance 				= $old_instance;
			$instance['title'] 		= isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['contributor_with_description'] 	= isset( $new_instance['contributor_with_description'] ) ? strip_tags( $new_instance['contributor_with_description'] ) : 0;
			$instance['number_posts'] 	= isset( $new_instance['number_posts'] ) ? strip_tags( $new_instance['number_posts'] ) : 0;
			$instance['show_contributor_image'] 	= isset( $new_instance['show_contributor_image'] ) ? strip_tags( $new_instance['show_contributor_image'] ) : 0;
			$instance['add_querystring_link_contributor'] 	= isset( $new_instance['add_querystring_link_contributor'] ) ? strip_tags( $new_instance['add_querystring_link_contributor'] ) : '';
			$instance['show_posts_count'] 	= isset( $new_instance['show_posts_count'] ) ? strip_tags( $new_instance['show_posts_count'] ) : 0;
			$instance['post_with_description'] 	= isset( $new_instance['post_with_description'] ) ? strip_tags( $new_instance['post_with_description'] ) : 0;
			$instance['except_contributor_id'] 	= isset( $new_instance['except_contributor_id'] ) ? strip_tags( $new_instance['except_contributor_id'] ) : 0;
			return $instance;
	}

	function form( $instance ) {
			$title 			= isset( $instance['title'] )  ? esc_attr( $instance['title'] ) : '';
			$contributor_with_description			= isset( $instance['contributor_with_description'] ) ? absint( $instance['contributor_with_description'] )  : 1;
			$show_posts_count			= isset( $instance['show_posts_count'] ) ? absint( $instance['show_posts_count'] )  : 0;
			$show_contributor_image			= isset( $instance['show_contributor_image'] ) ? absint( $instance['show_contributor_image'] )  : 1;
			$add_querystring_link_contributor			= isset( $instance['add_querystring_link_contributor'] ) ? ( $instance['add_querystring_link_contributor'] )  : '';
			$number_posts			= isset( $instance['number_posts'] ) ? absint( $instance['number_posts'] )  : 0;
			$post_with_description			= isset( $instance['post_with_description'] ) ? absint( $instance['post_with_description'] )  : 0;
			$except_contributor_id			= isset( $instance['except_contributor_id'] ) ? esc_attr( $instance['except_contributor_id'] )  : 0;
//echo "[debug] contributor_id: ". $contributor_id ." // contributor_with_description: ". $contributor_with_description ." // number_posts: ". $number_posts ."<br>";
//echo " show_posts_count: ". $show_posts_count ." // post_with_description: ". $post_with_description ."<hr>";
			?>
			<p><label for="<?php echo $this->get_field_id('title');?>">
			<?php _e('Title', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title; ?>" />
			</label></p>
			<p>
				<input id="<?php echo $this->get_field_id('contributor_with_description'); ?>" name="<?php echo $this->get_field_name('contributor_with_description'); ?>" type="checkbox" value="1" <?php checked( '1', $contributor_with_description ); ?>/>
        <label for="<?php echo $this->get_field_id('contributor_with_description'); ?>"><?php _e('Add description of contributor', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('show_posts_count'); ?>" name="<?php echo $this->get_field_name('show_posts_count'); ?>" type="checkbox" value="1" <?php checked( '1', $show_posts_count ); ?>/>
        <label for="<?php echo $this->get_field_id('show_posts_count'); ?>"><?php _e('Show posts count', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('show_contributor_image'); ?>" name="<?php echo $this->get_field_name('show_contributor_image'); ?>" type="checkbox" value="1" <?php checked( '1', $show_contributor_image ); ?>/>
        <label for="<?php echo $this->get_field_id('show_contributor_image'); ?>"><?php _e('Show Contributor Image', 'mgr_cp'); ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id('number_posts'); ?>" class="small-text" name="<?php echo $this->get_field_name('number_posts'); ?>" type="number_posts" min="1" step="1" value="<?php echo esc_attr( $number_posts ); ?>" />
				<label for="<?php echo $this->get_field_id('number_posts'); ?>"><?php _e('Max number of post for each contributor (can be 0)', 'mgr_cp'); ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'post_with_description' ); ?> "><?php _e('Choose how to show post description'); ?></label>
				<select id="<?php echo $this->get_field_id( 'post_with_description' ); ?>" name="<?php echo $this->get_field_name( 'post_with_description' ); ?>">
						 <option value="0" <?php selected($post_with_description, '0'); ?>><?php _e('No description', 'mgr_cp'); ?></option>
						 <option value="1" <?php selected($post_with_description, '1'); ?>><?php _e('Short description', 'mgr_cp'); ?></option>
						 <option value="2" <?php selected($post_with_description, '2'); ?>><?php _e('Long description', 'mgr_cp'); ?></option>
				</select>
			</p>
			<p><label for="<?php echo $this->get_field_id('except_contributor_id');?>">
			<?php _e('<strong>Except these</strong> Contributor(s) Id or Slug (divide by comma)', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('except_contributor_id');?>" name="<?php echo $this->get_field_name('except_contributor_id');?>" type="text" value="<?php echo $except_contributor_id; ?>" />
			</label></p>
			<p><label for="<?php echo $this->get_field_id('add_querystring_link_contributor');?>">
			<?php _e('Set Querystring at Contributor Link (without question mark [?]. e.g.: <em>onlybio=true&short=false</em>)', 'mgr_cp'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('add_querystring_link_contributor');?>" name="<?php echo $this->get_field_name('add_querystring_link_contributor');?>" type="text" value="<?php echo $add_querystring_link_contributor; ?>" />
			</label></p>
			<?php
	}
}

function mgr_cp_register_randContributorsInfoWidgets() {
		register_widget( 'mgr_cp_RandContributorsInfoWidget' );
}

add_action( 'widgets_init', 'mgr_cp_register_randContributorsInfoWidgets' );
