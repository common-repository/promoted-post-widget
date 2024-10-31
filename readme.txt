=== Promoted Post Widget ===
Contributors: atlanticbt, zaus, tnblueswirl
Donate link: http://atlanticbt.com/
Tags: widget, featured, promoted, expiration
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: trunk

Simple widget to display (promote) a specific post/page.  Set an expiration for this "promotion" to expire, with fallback to another post.

== Description ==

Simple widget to display (promote) a specific post/page.  Provides a list of from all public post types to choose an entry to promote until a specified date.  When this "promotion" expires, you can either display a static message or use another post/page instead.


== Installation ==

1. Upload the plugin folder to your plugins directory `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the Widget "Promoted Content" to a sidebar

== Frequently Asked Questions ==

= What are the options? =
* __Title:__ The displayed widget title - leaving it blank will use the post title (if you override the default, see Hooks)
* __Feature Expires:__ until what date (YYYY-MM-DD) to show the promoted post; after which fall back to the default
* __Feature Post:__ choose from the dropdown of public post types to promote
* __Default:__ display this entry instead after the expiration
* __Trim summary to:__ if no excerpt is provided, shorten the post content to this number of words

Note that you can select the most recent post from the dropdown options.

= How can I change the defaults? =

If you don't specify certain options, the widget will use builtin defaults.  Override these with the following hook:
    add_filter('abt_promo_post_defaults', 'YOUR_HOOK');
where `YOUR_HOOK` is a function that takes an array and returns the following settings:

    function promo_widget_defaults($settings){
    	return array(
    			#'title'		=> 'Latest From MY SITE',	// disable to use post title
    			'display'	=> 'Check out our new video! Credit goes to ... <p><a class="archive" href="/category/news">News Archive</a></p>',
    			'url'		=> '#',
    			'image'		=> '<img src="' . get_stylesheet_directory_uri() . '/images/g_video.jpg" alt="Latest from MY SITE">',
    	);
    }

= What else can I use? =

Two static methods are available for "embedding" the widget in code - basically just the internal processing used by the widget function.

    abtcore_feature_widget::display_promo($args, $before_title, $after_title)

which uses the following

    abtcore_feature_widget::get_promo($args)

to get either the promoted post or the default.  `$args` will contain an array of the widget options (`title`, `expire`, `promo`, `default`, `trim`).

Function `get_promo` will return an array of (`title`, `display`, `url`, and `image`) to `display_promo` where it gets combined with the defaults from the hook to output the widget HTML.

== Screenshots ==

1. NA

== Changelog ==

= 0.3 =
* fixed bug with missing limit_text() function

= 0.2 =
* split off to plugin
* bug fixes
* extra options

= 0.1 =
* CORE plugin


== Upgrade Notice ==

= 0.2 =
First added to repository.


== About AtlanticBT ==

From [About AtlanticBT][].

= Our Story =

> Atlantic Business Technologies, Inc. has been in existence since the relative infancy of the Internet.  Since March of 1998, Atlantic BT has become one of the largest and fastest growing web development companies in Raleigh, NC.  While our original business goal was to develop new software and systems for the medical and pharmaceutical industries, we quickly expanded into a business that provides fully customized, functional websites and Internet solutions to small, medium and larger national businesses.

> Our President, Jon Jordan, founded Atlantic BT on the philosophy that Internet solutions should be customized individually for each client’s specialized needs.  Today we have expanded his vision to provide unique custom solutions to a growing account base of more than 600 clients.  We offer end-to-end solutions for all clients including professional business website design, e-commerce and programming solutions, business grade web hosting, web strategy and all facets of internet marketing.

= Who We Are =

> The Atlantic BT Team is made up of friendly and knowledgeable professionals in every department who, with their own unique talents, share a wealth of industry experience.  Because of this, Atlantic BT always has a specialist on hand to address each client’s individual needs.  Due to the fact that the industry is constantly changing, all of our specialists continuously study the latest trends in all aspects of internet technology.   Thanks to our ongoing research in the web designing, programming, hosting and internet marketing fields, we are able to offer our clients the most recent and relevant ideas, suggestions and services.

[About AtlanticBT]: http://www.atlanticbt.com/company "The Company Atlantic BT"