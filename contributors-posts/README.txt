=== Contributors' Posts ===
Contributors: Magicianred
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=magicianred%40gmail%2ecom&lc=IT&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: contributor, post, editor, columnist, editorial journalist, journalism, advice columnist, news analyst, masthead, headline, news, newspaper, press
Requires at least: 3
Tested up to: 3.9
Stable tag: 0.8.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Contributors' Posts is a plugin to manage posts of contributors with the same account without having to create other users. For online Press.

== Description ==
This plugin is useful for those who want to add to your blog Columnists or other Characters without having to create new WordPress Users, you can associate these contributors also to pages and posts.

It can also be useful to writers who wish to create posts that relate to the characters in their stories, or as if they were the characters of their own stories to write them.

With this plugin you can add Contributors' Posts, Contributors and, through shortcode, add content to posts, or through widgets, add to the sidebar lists or details of contributors and their posts.

Plugin localized with PoEdit.
Localization made: Italian (me).

Follow the updates of the plugin subscribing to http://simone.paolucci.name/wordpress/plugins/contributorsposts/contributorsposts.rss.php

== Installation ==

1. Upload to your plugins folder, usually `wp-content/plugins/`
2. If you want, use the plugin 'Taxonomy Images' for show image for Contributors
	( http://wordpress.org/extend/plugins/taxonomy-images/ )
3. Activate the plugin on the plugin screen
4. If necessary, regenerate permalinks in order to display the archive of the Contributors
5. As you want, copy the file style.css in custom-style.css (same directory) and set your rules of style
6. As you want, create in your theme directory the single.php in single-contributorpost.php
7. As you want, create in your theme directory the archive.php in archive-contributorpost.php
8. Go to 'Contributors Posts Settings' -> 'Generale Settings' and configure HTML for your theme
9. Go to 'Contributors Posts Settings' -> 'Customize Contributor, Category and Tag Setting' and configure which post_type associate contributors,
		where show contributors posts, and if activate special plugin page for show contributor post and archive.
10. Use plugin 'Widget Css Classes' for set more class to div widget
	( http://wordpress.org/extend/plugins/widget-css-classes/screenshots/ )
11. Use plugin 'Rich Text Tags' for add html feature at taxonomy description (e.g.: to write a biography of contributor)
	( http://wordpress.org/plugins/rich-text-tags/screenshots/ )

== Frequently Asked Questions ==

= Do I really need to use this plugin? =

Probably not, Wordpress manages the posts of other users by adding other users. But if you want a plugin to handle these posts without creating other users, or if those users are 'fictitious', this plugin is for you.

= How edited the view of Contributor Post and Archive of Contributors Posts =

You must copy (from your theme directory) single.php and archive.php, rename these to single-contributorpost.php and archive-contributorpost.php.
Disabilite from 'Contributors Posts Settings' -> 'Customize Contributor, Category and Tag Setting' the 'Select the option below to attive the plugin specific page showing a type:' options.
Then customize your pages.

= How I can show only biography on Contributors' Post archive? =

You can set querystring in widget or shortcode at Contributor link 'onlybio=true', then in archive-contributorpost.php page you can manage a querystring for disability the posts list and show only the description of taxonomy.

== Screenshots ==

1. Show of Admin List of Contributors Posts
2. Show of Admin List of Contributors ( with plugin 'Taxonomy Images' active )
3. Show the Admin Widget 'Contributors List' setting
4. Show the Widget 'Contributors List' in Sidebar with 1 Posts for each Contributors
5. Show the Admin Widget 'Contributors Info' setting
6. Show the Widget 'Contributors Info' in Sidebar with 1 Posts
7. Show the Admin Widget 'Contributors Info [Random]' setting
8. Show the Page edit with the shortcode for show the lists of Contributors and Posts
9. Show the Page view with the shortcode for show the lists of Contributors and Posts
10. Show the Page edit with the shortcode for show the Contributors Info and Posts
11. Show the Page view with the shortcode for show the Contributors Info and Posts
12. Show the Page for show the contributors posts ( custom of single.php of theme twentyeleven )
13. Show the Page for show the archive of contributors posts ( custom of archive.php of theme twentyeleven )
14. Show Contributors Posts Settings page for personalizze your HTML div contributor box and others
15. Contributors can be associate to others post type.
16. New settings for contributors posts: Contributors associate with others post types, merge contributors posts in category, tag and search results,
    attive/disattive plugin special page for show contributors posts and contributor post archive.

== Changelog ==

= 0.8.1 =
* I have added the possibility of associate a querystring to Contributor (taxonomy) link in widget and shortcode.
* I have added the possibility of choise if show or not the Contributor image (both widgets and shortcodes).

= 0.8 =
* I have added the possibility of associate Contributors (taxonomy) to others post type (like posts, pages, attachements, etc.).
* I have added the possibility of merge the posts with contributors posts in category, tag and search pages (You can choice it in settings).
* I have added the possibility of attivate/disattivate the plugin pages of archive e single view of contributors posts.
* I have fixed some minor bugs.

= 0.7.2.1 =
* I have added a link to the latest news of the plugin when the RSS is not displayed.

= 0.7.2 =
* I have fixed a bug that prevented it from making more than one item from the list of contributors. Now the field "Exclude contributor(s)" does work.

= 0.7.1 =
* I have commented code with a bug that prevented it from making more than one item from the list of contributors. At the moment the field "Exclude contributor(s)" does not work.

= 0.7 =
* Add Settings Option page for customize the HTML to wrap Contributor data:
	HTML Start Separator, HTML Contributor Description Separator (before), HTML Contributor Box (Opening and closing), HTML Contributor Box Image (opening and closing),
	HTML Contributor Box Title (opening and closing), HTML Contributor Box Post Count (opening and closing), HTML Contributor Box Description (opening and closing),
	HTML Contributor Box Description Empty (opening and closing), HTML Contributor Box Posts Box (opening and closing), HTML Contributor Box Post Content (opening and closing),
	HTML Contributor Box Post Title (opening and closing), HTML Contributor Box Post Description (opening and closing), HTML Contributor Box Post Description Empty (opening and closing).
* Localizate plugin with PoEdit: Italian.

= 0.6 =
* Add Categories and Tags to Contributors Posts. It's the same of posts, so you can retrieve them together with posts by category or tag.
* Edit the shortcode [contributorsList], now it accepts the following attributes: number_contributors, contributor_with_description, show_posts_count,  number_posts, post_with_description, except_contributor_id
	Edit post_with_description - now it accepts the follow value: 'no', '0' -> no description [default]; 'short', '1' -> short description; 'long', '2' -> long description.
	Add except_contributor_id: list of id(s) or slug divide by comma (,) to exclude
* Edit the shortcode [contributorsInfo]. It accepts the following attributes:
	contributor_id, contributor_with_description, show_posts_count, number_posts, post_with_description
	Edit post_with_description - now it accepts the follow value: 'no', '0' -> no description [default]; 'short', '1' -> short description; 'long', '2' -> long description.
* Edit the shortcode [randContributorsInfo]. It accept the following attributes:
	contributor_id, contributor_with_description, show_posts_count, number_posts, post_with_description, except_contributor_id
	This is the same as contributorsInfo, but retrieves randomly the contributor and it accepts a list of contributor to exclude
* Edit the widget ContributorsList. Show a combo for view mode to post description. Add a new field Except contributors: list of id(s) or slug divide by comma (,) to exclude
* Edit the widget ContributorsInfo. Show a combo for view mode to post description.
* Add the widget ContributorsInfoRandom.
	This is the same as contributorsInfo, but retrieves randomly the contributor and it accepts a list of contributor to exclude

= 0.5 =
* Add stylesheet in plugin directory ( style.css )
* Add the option to create a custom stylesheet without edit the default stylesheet
	( the name of the new file must be custom-style.css )
* Edit the shortcode [contributorsList], now it accepts the following attributes: number_contributors, contributor_with_description, show_posts_count,  number_posts, post_with_description
* Add the shortcode [contributorsInfo]. It accept the following attributes:
	contributor_id, contributor_with_description, show_posts_count, number_posts, post_with_description
* Edit the widget ContributorsList.
* Add the widget ContributorsInfo.

== Upgrade Notice ==

= 0.8.1 =
New: Add two new functionalities (both for widgets and shortcodes): show contributor image and add querystring to contributor link.

= 0.8 =
New: Contributors associate to others post types, merge contributors posts with posts in category, tag and search result.

= 0.7.2.1 =
Add link to RSS Last News Plugin

= 0.7.2 =
Bug fix

= 0.7 =
various improvements, added Settings Option Page for personalize HTML and Localizate Plugin with PoEdit

= 0.6 =
various improvements, added a shortcode [randContributorsInfo], a widget ContributorsInfoRandom.

= 0.5 =
various improvements, added a shortcode, a widget and customization with a stylesheet

== ToDo Section ==
Nothing to do at the moment. I accept your suggestions.