<?php
/**
 * @package ABT
 */
/*
Plugin Name: Promoted Post Widget
Plugin URI: http://www.atlanticbt.com/blog/wordpress-promote-a-post-in-a-widget/
Description: Feature a single post/page until a certain date, then fall back to a default
Version: 0.3
Author: atlanticbt, zaus, tnblueswirl
Author URI: http://atlanticbt.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


/**
 * Initialize feature widget!
 */

class abtcore_feature_widget extends WP_Widget {
	private $fields;
		
	function abtcore_feature_widget() {
		$this->fields = array(
			'title' => __('Feature','feature')
			, 'promo' => '#recent'
			, 'default' => ''
			, 'expire' => date('Y-m-d')
			, 'trim' => 20
		);
		
		$widget_ops = array('description' => __('Feature a single post until a certain date, then fallback to a default', 'feature') );
		//Create widget
		$this->WP_Widget('abtcore_feature', __('Promoted Content', 'feature'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		//echo $before_title, $title, $after_title;
		self::display_promo( $instance, $before_title, $after_title );
		echo $after_widget;

	} //end of widget

	//Update widget options
	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		//get old variables
		foreach($this->fields as $field => $value){
			$instance[$field] = esc_attr($new_instance[$field]);
		}
		// parse the time
		$instance['expire'] = strtotime($new_instance['expire']);
		
		return $instance;
	} //end of update

	//Widget options form
	function form($instance) {
		$tag = 'p';
		
		$instance = wp_parse_args(
				(array) $instance,
				$this->fields
		);

		extract($instance);
		#echo '<pre>', print_r($instance, true), '</pre>';
		
		?>

		<<?php echo $tag ?> class="field">
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'feature');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			<em class="summary description"><?php _e('Optional - leave blank to use post title (or hook default)', 'feature') ?></em>
		</<?php echo $tag ?>>
		<<?php echo $tag ?> class="field">
			<label for="<?php echo $this->get_field_id('expire'); ?>"><?php _e('Feature Expires:', 'feature');?></label>
			<input class="widefat date datepicker" id="<?php echo $this->get_field_id('expire'); ?>" name="<?php echo $this->get_field_name('expire'); ?>" type="text" value="<?php echo esc_attr( date('Y-m-d', $expire)); ?>" />
			<em class="summary description">(YYYY-MM-DD)</em>
		</<?php echo $tag ?>>

		<?php

		// Pull all posts
		$post_types = get_post_types(array('public'=>true));
		$post_list = array();
		foreach( $post_types as $post_type ){
			$args = array(
					'post_type'	=> $post_type,
			);
			#echo $post_type, '<pre>', print_r(get_posts($args), true), '</pre>';
			$post_list[$post_type] = get_posts($args);
		}

		?>
		<<?php echo $tag ?> class="field">
			<label for="<?php echo $this->get_field_id('promo'); ?>"><?php _e('Feature Post:', 'feature');?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('promo'); ?>" name="<?php echo $this->get_field_name('promo'); ?>" >
				<option value="">-- <?php _e('Select a Post', 'feature'); ?> --</option>
				<option value="#recent"<?php selected($promo, '#recent', true); ?>>*** <?php _e('Most Recent', 'feature'); ?> ***</option>
				<?php foreach($post_list as $type => $posts): ?>
				<optgroup label="<?php echo $type; ?>">
					<?php	 foreach($posts as $post): ?>
					<option value="<?php echo $post->ID; ?>" <?php selected($promo, $post->ID, true) ?>><?php echo ($post->post_title) ? $post->post_title : $post->post_name; ?></option>
					<?php	 endforeach; ?>
				</optgroup>
				<?php endforeach;?>
			</select>
			<em class="summary description"><?php _e('Selected post to show', 'feature'); ?></em>
		</<?php echo $tag ?>>
		
		<<?php echo $tag ?> class="field">
			<label for="<?php echo $this->get_field_id('default'); ?>"><?php _e('Default:', 'feature');?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('default'); ?>" name="<?php echo $this->get_field_name('default'); ?>" >
				<option value="">-- Select a Post --</option>
			</select>
			<em class="description"><?php _e('Fallback if Featured choice has expired', 'feature'); ?></em>
		</<?php echo $tag ?>>
		
		<!-- lazy reuse -->
		<script type="text/javascript">
			(function($){
				$('#<?php echo $this->get_field_id('default'); ?>')
					// copy the options
					.html( $('#<?php echo $this->get_field_id('promo'); ?>').html() )
					// now set selected
					.val('<?php echo $default; ?>')
					;
			})(jQuery);
		</script>
		
		<<?php echo $tag ?> class="field">
			<label for="<?php echo $this->get_field_id('trim'); ?>"><?php _e('Trim summary to:', 'feature');?></label>
			<input class="number" id="<?php echo $this->get_field_id('trim'); ?>" name="<?php echo $this->get_field_name('trim'); ?>" type="text" value="<?php echo esc_attr($trim); ?>" />
			<em class="summary description"><?php _e('How long content is trimmed to, if no excerpt provided') ?></em>
		</<?php echo $tag ?>>
		
		<<?php echo $tag ?> class="developer-hint">
			<strong>Developer Hint:</strong><br />
			Use hook <code>abt_promo_post_defaults</code> to override defaults.
		</<?php echo $tag ?>>
		<?php
	} //end of form
	
	
	static function register(){
		return register_widget("abtcore_feature_widget");
	}
	
	
	/**
	* Supporting promos widget functions
	*/
	
	static function display_promo($args, $before_title, $after_title) {
		$promo_parts_default = apply_filters('abt_promo_post_defaults', array(
				'title'		=> __('Featured Content', 'feature'),
				'display'	=> __('Check it out!', 'feature'),
				'url'		=> '/featured',
				'image'		=> '<img src="' . get_stylesheet_directory_uri() . '/abt-promo-post-placeholder.jpg" alt="Latest Content">',
		));
		
		// get the featured content (either promo or default)
		$promo_parts = self::get_promo($args);
		
		$promo_parts = array_merge($promo_parts_default, $promo_parts);
		
		if(is_array($promo_parts)){
			extract($promo_parts);
	
			echo $before_title . $title . $after_title;
			echo '<div class="textwidget">';
			echo $display;
			echo '</div>';
	
			echo $before_title . '' . $after_title;
			echo '<div class="textwidget">';
			echo '<a class="video-image" href="'.$url.'">'.$image.'</a>'; //$display;
			echo '</div>';
		}
		else {
			echo $promo_parts;
		}
	}
	
	/**
	 * Fetch promoted content according to widget args
	 */
	static function get_promo($args) {
		if($args['expire'] > time()){
			$postID = $args['promo'];
		}
		else {
			$postID = $args['default'];
		}

		if( '#recent' == $postID ) {
			$post = wp_get_recent_posts( array('numberposts'=>1, 'post_status'=>'publish') );
			if( count($post) > 0 )	$post = (object)$post[0];
		}
		else {
			// note, if nothing specified for $postID, it'll just get whatever is first in the
			// current global POST object (i.e. the current page)
			$post = get_post( $postID, OBJECT );
		}
		
		if( empty($post) ) return array();
		
		if( empty($args['title']) ){
			$parts['title'] = $post->post_title;
		} else {
			$parts['title'] = $args['title'];
		}
	
		if( !isset($post->post_excerpt) || empty($post->post_excerpt) ) {
			$summary = limit_text( strip_tags( $post->post_content ), $args['trim'] );
		} else {
			$summary = $post->post_excerpt;
		}
		
		if( $summary ){
			$parts['display'] = apply_filters('the_content',$summary) . '<p><a class="archive" href="' . $url . '" title="'. __('Read More') .'" >' . __('Read More') . '</a></p>';
		}
		
		if($url = get_permalink($post->ID)) $parts['url'] = $url;
		
		if($image = get_the_post_thumbnail( $post->ID, array(292,178) ) ) {
			// get Feature Image
			$parts['image'] = $image;
		}
		return $parts;
	}//--	fn	::get_promo
	
}///---	class	abtcore_feature_widget

//Register Widget
add_action( 'widgets_init', array('abtcore_feature_widget', 'register') );

if ( ! function_exists( 'abtcore_promo_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post�date/time and author for Promos
 *
 * @since ABT Core v0.9.3
 */
function abtcore_promo_posted_on() {
	printf( __( '%2$s', 'abtcore' ),
		'meta-prep',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark" class="timestamp"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			'<span class="month">' . get_the_date('F') . '</span> <span class="day">' . get_the_date('d') . '</span>, <span class="year">' . get_the_date('Y')
		)
	);
}
endif;

if ( ! function_exists( 'limit_text' ) ) :
/**
 * Adds function for limiting/truncating text during loop
*
* @since ABT Core v0.9.5
*/
function limit_text($text, $limit, $after = '...') {
    $words = str_word_count($text, 2);
    if($limit >= count($words)) return $text;

    $pos = array_keys($words);
    $text = substr($text, 0, $pos[$limit]) . $after;

    return $text;
}
endif;
