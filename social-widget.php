<?php
class Jake_Social_Widget extends WP_Widget {
	private $format  = "img";
	private $formats = array(
		"link" => '<li><a href="%s" target="_blank" title="Visit %s on %s" class="social-icon social-icon-%s">%s</a>',
		"img"  => '<img src="%s" alt="%s" class="social-icon" />',
		"text" => 'Visit %s on %s',
	);


	function flush_widget_cache() {
		wp_cache_delete('Jake_Social_Widget', 'widget');
	}
	
	function Jake_Social_Widget() {
		$widget_ops = array('classname' => 'Jake_Social_Widget', 'description' => __('This widget displays links to Facebook, Twitter, Linkedin and a mailing list sign up form.', 'roots'));
		$this->WP_Widget('Jake_Social_Widget', __('Social Media Widget', 'roots'), $widget_ops);
		$this->alt_option_name = 'Jake_Social_Widget';

		add_action('save_post', array(&$this, 'flush_widget_cache'));
		add_action('deleted_post', array(&$this, 'flush_widget_cache'));
		add_action('switch_theme', array(&$this, 'flush_widget_cache'));
	}

	function get_social_item($social_url, $name, $file) {
		$site = get_bloginfo("name");
		if ($this->format == "img") {
			$content = sprintf($this->formats["img"], $file, $name);
		} else {
			$content = sprintf($this->formats["text"], $site, $name);
		}
		if ($social_url) {
			return sprintf(
				$this->formats["link"], 
				$social_url,
				$name,
				$site,
				strtolower($name),
				$content
			);
		} else {
			return '';
		}
	}

	function widget($args, $instance) {
		$this->facebook  = isset( $instance[ 'facebook' ] )  ? esc_attr( $instance[ 'facebook' ] )   : false;
		$this->twitter   = isset( $instance[ 'twitter' ] )   ? esc_attr( $instance[ 'twitter' ] )  : false;
		$this->linkedin  = isset( $instance[ 'linkedin' ] )  ? esc_attr( $instance[ 'linkedin' ] ) : false;
		$this->youtube   = isset( $instance[ 'youtube' ] )  ? esc_attr( $instance[ 'youtube' ] ) : false;
		$this->pinterest = isset( $instance[ 'pinterest' ] )  ? esc_attr( $instance[ 'pinterest' ] ) : false;
		$this->google    = isset( $instance[ 'google' ] )  ? esc_attr( $instance[ 'google' ] ) : false;
		
		if (isset($instance[ 'hide_imgs' ])) {
			$this->format = $instance['hide_imgs'] == "on"  ? "text" : "img";
		}

?>


			<ul class="social-media-links horizontal-list ">
			<?php
				echo $this->get_social_item($this->facebook, "Facebook", "/assets/img/icon-facebook.png");
				echo $this->get_social_item($this->twitter, "Twitter", "/assets/img/icon-twitter.png");
				echo $this->get_social_item($this->linkedin, "LinkedIn", "/assets/img/icon-linkedin.png");
				echo $this->get_social_item($this->youtube, "YouTube", "/assets/img/icon-youtube.png");
				echo $this->get_social_item($this->pinterest, "Facebook", "/assets/img/icon-pinterest.png");
				echo $this->get_social_item($this->google, "Google", "/assets/img/icon-google.png");
			?>

			</ul> 
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance[ 'facebook' ]  = strip_tags( $new_instance[ 'facebook' ] );
		$instance[ 'twitter' ]   = strip_tags( $new_instance[ 'twitter' ] );
		$instance[ 'linkedin' ]  = strip_tags( $new_instance[ 'linkedin' ] );
		$instance[ 'youtube' ]   = strip_tags( $new_instance[ 'youtube' ] );
		$instance[ 'pinterest' ] = strip_tags( $new_instance[ 'pinterest' ] );
		$instance[ 'google' ]    = strip_tags( $new_instance[ 'google' ] );
		
		$instance[ 'hide_imgs' ]      = $new_instance[ 'hide_imgs' ];

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions[ 'Jake_Social_Widget' ] ) ) {
			delete_option( 'Jake_Social_Widget' );
		}

		return $instance;
	}

	function form($instance) {
		$facebook  = isset( $instance[ 'facebook'  ]) ? esc_attr( $instance[ 'facebook' ] ) : '';
		$twitter   = isset( $instance[ 'twitter' ] )  ? esc_attr( $instance[ 'twitter' ] )  : '';
		$linkedin  = isset( $instance[ 'linkedin' ] ) ? esc_attr( $instance[ 'linkedin' ] ) : '';
		$youtube   = isset( $instance[ 'youtube' ] ) ? esc_attr( $instance[ 'youtube' ] ) : '';
		$pinterest = isset( $instance[ 'pinterest' ] ) ? esc_attr( $instance[ 'pinterest' ] ) : '';
		$google    = isset( $instance[ 'google' ] ) ? esc_attr( $instance[ 'google' ] ) : '';
		
		$imgs      = isset( $instance[ 'hide_imgs' ] ) ? $instance[ 'hide_imgs' ] : false;

	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php _e('Facebook:', 'roots'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php _e('Twitter:', 'roots'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>"><?php _e('LinkedIn:', 'roots'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" type="text" value="<?php echo esc_attr($linkedin); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php _e('YouTube:', 'roots'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="text" value="<?php echo esc_attr($youtube); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php _e('Pinterest:', 'roots'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="text" value="<?php echo esc_attr($pinterest); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('google')); ?>"><?php _e('Google +:', 'roots'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('google')); ?>" name="<?php echo esc_attr($this->get_field_name('google')); ?>" type="text" value="<?php echo esc_attr($google); ?>" />
		</p> 

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('hide_imgs')); ?>">
				<input id="<?php echo esc_attr($this->get_field_id('hide_imgs')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_imgs')); ?>" type="checkbox" <?php echo $imgs ? 'checked="checked"' : ''; ?> />
				<?php _e("Don't Use Images", 'roots'); ?>
			</label>
		</p> 		  
	<?php
	}
}