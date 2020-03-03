<?php
function mgr_cp_load_admin_style() {
        wp_enqueue_style( 'contributorsposts_admin_css', plugins_url( '../styles/admin-style.css', __FILE__ ), false, '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'mgr_cp_load_admin_style' );

function mgr_cp_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['contributorpost'] = array(
		0 => '',
		1 => sprintf( __('Contributor Post updated. <a href="%s">View Contributor Post</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Contributor Post updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Contributor Post restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Contributor Post published. <a href="%s">View Contributor Post</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Contributor Post saved.'),
		8 => sprintf( __('Contributor Post submitted. <a target="_blank" href="%s">Preview Contributor Post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Contributor Post scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Contributor Post</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Contributor Post draft updated. <a target="_blank" href="%s">Preview Contributor Post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'mgr_cp_updated_messages' );



function mgr_cp_contextual_help( $contextual_help, $screen_id, $screen ) {
	if ( 'contributorpost' == $screen->id ) {

		$contextual_help = '<h2>'. __('Contributors Posts', 'mgr_cp') .'</h2>
		<p>'. __( 'Contributors Posts show the details of the posts of the contributors that we show on the blog. You can see a list of them on this page in reverse chronological order - the latest one we added is first. ', 'mgr_cp') .'</p>
		<p>'. __( 'You can view/edit the details of each Contributor Post by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items. ', 'mgr_cp') .'</p>';

	} elseif ( 'edit-contributorpost' == $screen->id ) {

		$contextual_help = '<h2>'. __('Editing Contributors Posts', 'mgr_cp') .'</h2>
		<p>'. __('This page allows you to view/modify Contributor Post details. Please make sure to fill out the available boxes with the appropriate details (contributor by, etc) and <strong>not</strong> add these details to the Contributor Post description.', 'mgr_cp') .'</p>';

	}
	return $contextual_help;
}
add_action( 'contextual_help', 'mgr_cp_contextual_help', 10, 3 );




function mgr_cp_set_default_options_array() {
	if( false === get_option( 'mgr_cp_options' ) ) {
		$new_options = array();
		$new_options['after_contributorBoxSeparator'] = '<hr/>';
		$new_options['before_contributorBoxDescriptionSeparator'] = '<br/>';

		$new_options['before_contributorBox'] = '<div class="contributor_box">';
		$new_options['after_contributorBox'] = '</div>';
		$new_options['before_contributorBoxImage'] = '<div class="contributor_box_image">';
		$new_options['after_contributorBoxImage'] = '</div>';
		$new_options['before_contributorBoxTitle'] = '<h2 class="heading contributor_box_title">';
		$new_options['after_contributorBoxTitle'] = '</h2>';
		$new_options['before_contributorBoxPostCount'] = '<span class="contributor_box_postcount">(<strong>';
		$new_options['after_contributorBoxPostCount'] = '</strong>)</span>';
		$new_options['before_contributorBoxDescription'] = '<div class="contributor_box_desc">';
		$new_options['after_contributorBoxDescription'] = '</div>';
		$new_options['before_contributorBoxDescriptionVoid'] = '<div class="contributor_box_desc_voids">';
		$new_options['after_contributorBoxDescriptionVoid'] = '</div>';

		$new_options['before_contributorBoxPosts'] = '<div class="contributor_posts">';
		$new_options['after_contributorBoxPosts'] = '</div>';
		$new_options['before_contributorBoxPostContent'] = '<div class="contributor_post_content">';
		$new_options['after_contributorBoxPostContent'] = '</div>';
		$new_options['before_contributorBoxPostTitle'] = '<h3 class="contributor_post_title"><strong>';
		$new_options['after_contributorBoxPostTitle'] = '</strong></h3>';
		$new_options['before_contributorBoxPostDescription'] = '<p class="contributor_post_text">';
		$new_options['after_contributorBoxPostDescription'] = '</p>';
		$new_options['before_contributorBoxPostDescriptionVoid'] = '<p class="contributor_post_text_void">';
		$new_options['after_contributorBoxPostDescriptionVoid'] = '</p>';

		add_option( 'mgr_cp_options', $new_options );
	}
}

add_action( 'admin_menu', 'mgr_cp_settings_menu' );

function mgr_cp_settings_menu() {
	add_options_page(
		__( 'Contributors Posts Configuration', 'mgr_cp' ),
		__('Contributors Posts Settings', 'mgr_cp'),
		'manage_options',
		'mgr_cp_contributorsposts', 'mgr_cp_config_page' );
}

function mgr_cp_admin_tabs( $current = 'general' ) {
    $tabs = array( 'general' => __('General Settings', 'mgr_cp'),
									'contributor' => __('Contributor, Category & Tag Settings', 'mgr_cp'),
									'support' => __('Support and Donate', 'mgr_cp') );
    echo '<div id="icon-themes" class="icon32"><br></div>';

		?><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/">
			<img align="right" src="<?php print(plugins_url('../images/wiball_onkeyboard.png', __FILE__)) ?>" width="226" height="186" alt="<?php _e('Wiball on keybord (C) Elisa Ragni 2010 by-nc-nd-3.0', 'mgr_cp');?>" title="<?php _e('Wiball on keybord (C) Elisa Ragni 2010 by-nc-nd-3.0', 'mgr_cp');?>" /></a>
    <h1><?php _e( 'Settings for Contributors\' Posts', 'mgr_cp' ); ?></h1><?php
		echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=mgr_cp_contributorsposts&tab=$tab'>$name</a>";

    }
    echo '</h2>';
}

function mgr_cp_config_page( ) {
	$options = get_option( 'mgr_cp_options' );

	if ( isset ( $_GET['tab'] ) ) mgr_cp_admin_tabs($_GET['tab']); else mgr_cp_admin_tabs('general');

	?>
	<div id="mgr_cp-general" class="wrap">

			<?php
			if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
			else $tab = 'general'; ?>
		<table>
			<colgroup>
				<col style="width:80%;">
				<col style="width:20%;">
			</colgroup>
			<tr>
				<td>

						<?php
						switch ( $tab ){
							case 'general' : /**** GENERAL SETTINGS ****/
								 ?>
					<form method="post" action="admin-post.php?tab=<?php echo urlencode($tab); ?>">
						<?php wp_nonce_field( 'mgr_cp' ); ?>
						<input type="hidden" name="action" value="save_mgr_cp_options" />

						<table class="form-table">
							<caption><h3><?php _e( 'Customize HTML tags of the Contributor Box', 'mgr_cp' ); ?></h3></caption>
							<colgroup>
								<col style="width:30%;">
								<col style="width:70%;">
							</colgroup>
							<tr>
								<td align="right"><?php _e( 'HTML Start Separator', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxSeparator"><?php echo ( html_entity_decode($options['after_contributorBoxSeparator'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Description Separator (before)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxDescriptionSeparator"><?php echo ( html_entity_decode($options['before_contributorBoxDescriptionSeparator'] )); ?></textarea></td>
							</tr>

							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box (Opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBox"><?php echo ( html_entity_decode($options['before_contributorBox'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBox"><?php echo ( html_entity_decode($options['after_contributorBox'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Image (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxImage"><?php echo ( html_entity_decode($options['before_contributorBoxImage'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Image (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxImage"><?php echo ( html_entity_decode($options['after_contributorBoxImage'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Title (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxTitle"><?php echo ( html_entity_decode($options['before_contributorBoxTitle'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Title (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxTitle"><?php echo ( html_entity_decode($options['after_contributorBoxTitle'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Count (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxPostCount"><?php echo ( html_entity_decode($options['before_contributorBoxPostCount'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Count (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxPostCount"><?php echo ( html_entity_decode($options['after_contributorBoxPostCount'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Description (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxDescription"><?php echo ( html_entity_decode($options['before_contributorBoxDescription'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Description (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxDescription"><?php echo ( html_entity_decode($options['after_contributorBoxDescription'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Description Empty (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxDescriptionVoid"><?php echo ( html_entity_decode($options['before_contributorBoxDescriptionVoid'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Description Empty (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxDescriptionVoid"><?php echo ( html_entity_decode($options['after_contributorBoxDescriptionVoid'] )); ?></textarea></td>
							</tr>

							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Posts Box (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxPosts"><?php echo ( html_entity_decode($options['before_contributorBoxPosts'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Posts Box (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxPosts"><?php echo ( html_entity_decode($options['after_contributorBoxPosts'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Content (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxPostContent"><?php echo ( html_entity_decode($options['before_contributorBoxPostContent'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Content (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxPostContent"><?php echo ( html_entity_decode($options['after_contributorBoxPostContent'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Title (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxPostTitle"><?php echo ( html_entity_decode($options['before_contributorBoxPostTitle'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Title (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxPostTitle"><?php echo ( html_entity_decode($options['after_contributorBoxPostTitle'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Description (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxPostDescription"><?php echo ( html_entity_decode($options['before_contributorBoxPostDescription'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Description (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxPostDescription"><?php echo ( html_entity_decode($options['after_contributorBoxPostDescription'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Description Empty (opening)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="before_contributorBoxPostDescriptionVoid"><?php echo ( html_entity_decode($options['before_contributorBoxPostDescriptionVoid'] )); ?></textarea></td>
							</tr>
							<tr>
								<td align="right"><?php _e( 'HTML Contributor Box Post Description Empty (closing)', 'mgr_cp' ); ?>:</td>
								<td align="left"><textarea style="width:90%" name="after_contributorBoxPostDescriptionVoid"><?php echo ( html_entity_decode($options['after_contributorBoxPostDescriptionVoid'] )); ?></textarea></td>
							</tr>

							<tr>
								<td colspan="2" align="center"><input type="submit" value="<?php _e( 'Submit', 'mgr_cp' ); ?>" class="button-primary" /></td>
							</tr>
						</table>

					</form>
						<?php
							break;


							case 'contributor' : /**** Contributors SETTINGS ****/
								 ?>
					<form method="post" action="admin-post.php?tab=<?php echo urlencode($tab); ?>">
						<?php wp_nonce_field( 'mgr_cp' ); ?>
						<input type="hidden" name="action" value="save_mgr_cp_options" />
						<table class="form-table">
							<caption><h3><?php _e( 'Customize Contributor taxonomy, Category and Tag Options', 'mgr_cp' ); ?></h3></caption>
							<colgroup>
								<col style="width:30%;">
								<col style="width:70%;">
							</colgroup>
							<tr>
								<td align="left" colspan="2"><?php _e( 'Select Post types to associate a Contributor taxonomy', 'mgr_cp' ); ?>:</td>
							</tr>
							<tr>
								<td align="center" colspan="2">
									<div class="post-type-selection-box">
									<?php
										if(isset($options['post_types_associate_contributor'])) {
											$post_types_associate = explode(",", $options['post_types_associate_contributor']);
										} else $post_types_associate = array('contributorpost');
										$post_types = get_post_types( '', 'names' );
										foreach ( $post_types as $post_type ) {
											$checked = ( in_array($post_type, $post_types_associate ) ) ? " checked=\"checked\" " : '';
											echo '<div class="post-type-select-box"><input type="checkbox" name="chkPostAssociateToContributor[' . $post_type . ']" value="' . $post_type . '" '. $checked .'>' . $post_type . '</div>';
										}
									?>
								</td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php _e( 'Select Taxonomy types where you want Contributors\' Posts to be showed', 'mgr_cp' ); ?>:</td>
							</tr>
							<tr>
								<td align="center" colspan="2">
									<div class="post-type-selection-box">
									<?php
										if(isset($options['contributorpost_show_archives'])) {
											$contributorpost_show_archives = explode(",", $options['contributorpost_show_archives']);
										} else $contributorpost_show_archives = array();
										$taxonomy_types = array( 'tag', 'category', 'search' );
										foreach ( $taxonomy_types as $taxonomy_type ) {
											$checked = ( in_array($taxonomy_type, $contributorpost_show_archives ) ) ? " checked=\"checked\" " : '';
											echo '<div class="post-type-select-box"><input type="checkbox" name="chkContributorpostShowArchives[' . $taxonomy_type . ']" value="' . $taxonomy_type . '" '. $checked .'>' . $taxonomy_type . '</div>';
										}
									?>
								</td>
							</tr>
							<tr>
								<td align="left" colspan="2"><?php _e( 'Select the option below to attive the plugin specific page showing a type', 'mgr_cp' ); ?>:</td>
							</tr>
							<tr>
								<td align="center" colspan="2">
									<div class="post-type-selection-box">
									<?php
										if(isset($options['contributorpost_show_pages'])) {
											$pages_show_type = explode(",", $options['contributorpost_show_pages']);
										} else $pages_show_type = array();
										$types = array( 'archive', 'single' );
										foreach ( $types as $type ) {
											$checked = ( in_array($type, $pages_show_type ) ) ? " checked=\"checked\" " : '';
											echo '<div class="post-type-select-box"><input type="checkbox" name="chkPagesShowType[' . $type . ']" value="' . $type . '" '. $checked .'>' . $type . '</div>';
										}
									?>
								</td>
							</tr>

							<tr>
								<td colspan="2" align="center"><input type="submit" value="<?php _e( 'Submit', 'mgr_cp' ); ?>" class="button-primary" /></td>
							</tr>
						</table>

					</form>
						<?php
							break;


							case "support": /**** SUPPORT AND DONATE ****/ ?>
						<table class="form-table">
							<caption><h3><?php _e( 'Support and Donate', 'mgr_cp' ); ?></h3></caption>
							<tr>
								<td>
									<p><?php _e('<strong>Contributors\' Posts</strong> is a plugin developed by Simone "Magicianred" Paolucci.', 'mgr_cp') ?></p>
									<p><?php _e('Visit his website to learn about other projects he have realized.', 'mgr_cp'); ?></p>
									<p><a href="http://simone.paolucci.name/projects.php">http://simone.paolucci.name/projects.php</a></p>
									<p><?php _e('You can know more about this plugin, to send suggestions and to give your vote through the website Wordpress.org, where the plugin is distributed.', 'mgr_cp'); ?></p>
									<p><a href="http://wordpress.org/extend/plugins/contributors-posts/">http://wordpress.org/extend/plugins/contributors-posts/</a></p>
								</td>
							</tr>
							<tr>
								<td>
									<p><?php _e('You can also contribute to the development of the project by making a donation to the developer.', 'mgr_cp'); ?></p>
									<table class="donation_methods" style="border:dashed thin black;width:90%;text-align:center;">
										<colgroup><col width="23%"><col width="54%"><col width="23%"></colgroup>
										<tr>
											<td>
												<!-- Begin Skrill Donations -->
												<div class="skrill">
													<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
														<input type="hidden" name="pay_to_email" value="magicianred@gmail.com" />
														<input type="hidden" name="recipient_description" value="Simone Paolucci" />
														<input type="hidden" name="status_url" value="mailto:magicianred@gmail.com" />
														<input type="hidden" name="return_url" value="http://magicianred.altervista.org/thankyou" />
														<input type="hidden" name="language" value="IT" />
														<input type="text" name="amount" value="5.00" style="width:70px;" /> EUR<br />
														<input type="hidden" name="currency" value="EUR" />
														<input type="hidden" name="detail1_description" value="<?php _e('Donation', 'mgr_cp'); ?>" />
														<input type="hidden" name="detail1_text" value="<?php _e('Donation to the development of Wordpress plugins Contributors Posts', 'mgr_cp'); ?>" />
														<input style="background: url('<?php print(plugins_url('../images/skrill_chkout.gif', __FILE__)) ?>') no-repeat top left;width: 110px;height:52px;" type="submit" value="" />
													</form>
												</div>
												<!-- End Skrill Donations -->
											</td>
											<td>
												<!-- Begin PayPal Donations -->
												<div class="paypal">
													<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
															<div class="paypal-donations">
																<input type="hidden" name="cmd" value="_donations" />
																<input type="hidden" name="business" value="magicianred@gmail.com" />
																<input type="hidden" name="return" value="http://magicianred.altervista.org/thankyou" />
																<input type="hidden" name="item_name" value="<?php _e('Donation to the development of Wordpress plugins Contributors Posts', 'mgr_cp'); ?>" />
																<input type="hidden" name="item_number" value="Simone Paolucci" />
																<input type="text" name="amount" value="5" style="width:80%" /> EUR<br/>
																<input type="hidden" name="rm" value="0" />
																<input type="hidden" name="currency_code" value="EUR" />
																<input type="image" src="https://www.paypal.com/it_IT/IT/i/btn/btn_donateCC_LG.gif" style="width:221px;height:47px;" name="submit" alt="PayPal - The safer, easier way to pay online." />
																<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
															</div>
													</form>
												</div>
												<!-- End PayPal Donations -->
											</td>
											<td>
												<div id="flattr">
													<a href="http://flattr.com/thing/1308113/Wordpress-plugin-Contributors-Posts" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

							<?php } ?>
				</td>

				<td style="vertical-align:top;border-left:dashed thin black;padding-left:10px;">
					<h3><?php _e('Last News about Plugin','mgr_cp');?></h3>
					<?php include_once(ABSPATH.WPINC.'/rss.php');
						wp_rss('http://simone.paolucci.name/wordpress/plugins/contributorsposts/contributorsposts.rss.php', 7); ?>
				</td>
			</tr>
		</table>
			<br><br>
			<table style="border:dashed thin black;width:90%;text-align:center;">
				<caption><h3><?php _e('Credits and Attributions','mgr_cp');?></h3></caption>
				<tr>
					<td>
						'<span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/StillImage" property="dct:title" rel="dct:type">Wiball on keyboard</span>'
						<?php print(_e('by', 'mgr_cp')); ?> <a xmlns:cc="http://creativecommons.org/ns#" href="http://simone.paolucci.name/themes/web/images/logo.png" property="cc:attributionName" rel="cc:attributionURL">
							Elisa Ragni</a>
						<?php print(_e('is licensed under a', 'mgr_cp')); ?> <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">
							<?php print(_e('Creative Commons Attribution - Non Commercial - Share Alike 3.0 Unported License', 'mgr_cp')); ?></a>.<br/>
							<?php print(_e('Permissions beyond the scope of this license may be available at', 'mgr_cp')); ?>
						<a xmlns:cc="http://creativecommons.org/ns#" href="http://www.facebook.com/pages/Elisa-Ragni/159242230776374" rel="cc:morePermissions">
							Elisa Ragni - <?php print(_e('Facebook Page', 'mgr_cp')); ?></a>.
					</td>
				</tr>
			</table>
	</div>
<?php }

add_action( 'admin_init', 'mgr_cp_admin_init' );

function mgr_cp_admin_init() {
	add_action( 'admin_post_save_mgr_cp_options', 'mgr_cp_save_options' );
}

function mgr_cp_save_options() {
	if( !current_user_can( 'manage_options' ) )
		wp_die( __('Not allowed', 'mgr_cp') );

	check_admin_referer( 'mgr_cp' );

	$options = get_option( 'mgr_cp_options' );
	if ( isset ( $_GET['tab'] ) )
		 $tab = $_GET['tab'];
	else
		 $tab = 'general';

	switch ( $tab ){
		 case 'general' :
				$options['after_contributorBoxSeparator'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxSeparator'])));
				$options['before_contributorBoxDescriptionSeparator'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxDescriptionSeparator'])));

				$options['before_contributorBox'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBox'])));
				$options['after_contributorBox'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBox'])));
				$options['before_contributorBoxImage'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxImage'])));
				$options['after_contributorBoxImage'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxImage'])));
				$options['before_contributorBoxTitle'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxTitle'])));
				$options['after_contributorBoxTitle'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxTitle'])));
				$options['before_contributorBoxPostCount'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxPostCount'])));
				$options['after_contributorBoxPostCount'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxPostCount'])));
				$options['before_contributorBoxDescription'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxDescription'])));
				$options['after_contributorBoxDescription'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxDescription'])));
				$options['before_contributorBoxDescriptionVoid'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxDescriptionVoid'])));
				$options['after_contributorBoxDescriptionVoid'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxDescriptionVoid'])));

				$options['before_contributorBoxPosts'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxPosts'])));
				$options['after_contributorBoxPosts'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxPosts'])));
				$options['before_contributorBoxPostContent'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxPostContent'])));
				$options['after_contributorBoxPostContent'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxPostContent'])));
				$options['before_contributorBoxPostTitle'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxPostTitle'])));
				$options['after_contributorBoxPostTitle'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxPostTitle'])));
				$options['before_contributorBoxPostDescription'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxPostDescription'])));
				$options['after_contributorBoxPostDescription'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxPostDescription'])));
				$options['before_contributorBoxPostDescriptionVoid'] = stripslashes(wp_filter_post_kses(addslashes($_POST['before_contributorBoxPostDescriptionVoid'])));
				$options['after_contributorBoxPostDescriptionVoid'] = stripslashes(wp_filter_post_kses(addslashes($_POST['after_contributorBoxPostDescriptionVoid'])));
		break;

		case 'contributor' :
			/* SAVE post type associate */
				$post_types_associate = array('contributorpost');
				if(isset($_POST['chkPostAssociateToContributor'])) {
					$post_types_associate = $_POST['chkPostAssociateToContributor'];
				} else {
					$post_types_associate = array('contributorpost');
				}
				$post_types_availables = get_post_types( '', 'names' );
				// verify the post types is exists at moment
				$post_types_associate = array_intersect($post_types_associate, $post_types_availables);

				$post_types_associate = implode(",",$post_types_associate);
				$options['post_types_associate_contributor'] = $post_types_associate;

			/* SAVE type to show contributors posts */
				$taxonomy_types = array('tag','category');
				if(isset($_POST['chkContributorpostShowArchives'])) {
					$contributorpostShowArchives = $_POST['chkContributorpostShowArchives'];
				} else {
					$contributorpostShowArchives = array();
				}
				// verify the post types is exists at moment
				$post_types_associate = array_intersect($contributorpostShowArchives, $post_types_associate);

				$contributorpostShowArchives = implode(",",$contributorpostShowArchives);
				$options['contributorpost_show_archives'] = $contributorpostShowArchives;

			/* SAVE specific page for contributor post */
				$pages_types = array('archive','single');
				if(isset($_POST['chkPagesShowType'])) {
					$pages_type_show = $_POST['chkPagesShowType'];
				} else {
					$pages_type_show = array();
				}
				// verify the post types is exists at moment
				$pages_type_show = array_intersect($pages_type_show, $pages_types);

				$pages_type_show = implode(",",$pages_type_show);
				$options['contributorpost_show_pages'] = $pages_type_show;


		break;

		case 'donate':

		break;
	}
	update_option( 'mgr_cp_options', $options );

	wp_redirect( add_query_arg( array('page' => 'mgr_cp_contributorsposts', 'tab' => $tab),  admin_url( 'options-general.php' ) ) );

	exit;
}

