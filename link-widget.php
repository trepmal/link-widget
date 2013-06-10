<?php
/*
 * Plugin Name: Link Widget
 * Plugin URI: trepmal.com
 * Description:
 * Version:
 * Author: Kailey Lampert
 * Author URI: kaileylampert.com
 * License: GPLv2 or later
 * TextDomain: link-widget
 * DomainPath:
 * Network:
 */

add_action( 'widgets_init', 'register_link_widget' );
function register_link_widget() {
	register_widget( 'Link_Widget' );
}
class Link_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'link-widget', 'description' => __( 'Insert a link', 'link-widget' ) );
		$control_ops = array( 'width' => 300 );
		parent::WP_Widget( 'linkwidget', __( 'Link', 'link-widget' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
		echo $before_widget;

		echo $instance['hide_title'] ? '' : $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;

		$link = '<a href="'. esc_url( $instance['link_url'] ) .'">'. esc_html( $instance['link_text'] ) .'</a>';

		if ( ! empty( $instance['wrapping_tag'] ) ) {
			global $allowedposttags;
			$allowed_tags = array_keys( $allowedposttags );
			$tag = in_array($instance['wrapping_tag'], $allowed_tags ) ? $instance['wrapping_tag'] : '';
			if ( ! empty( $tag ) )
				$link = "<{$tag}>$link</{$tag}>";
		}

		echo $link;

		echo $after_widget;

	} //end widget()

	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['hide_title'] = (bool) $new_instance['hide_title'] ? 1 : 0;
		$instance['link_text'] = strip_tags( $new_instance['link_text'] );
		$instance['link_url'] = esc_url_raw( $new_instance['link_url'] );
		$instance['wrapping_tag'] = strip_tags( $new_instance['wrapping_tag'] );
		return $instance;

	} //end update()

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'hide_title' => 1, 'link_text' => __( 'Click Here', 'link-widget' ), 'link_url' => 'http://', 'wrapping_tag' => 'p' ) );
		extract( $instance );
		?>
		<p style="width:63%;float:left;">
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p style="width:33%;float:right;padding-top:20px;height:20px;">
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>"<?php checked( $hide_title ); ?> />
			<label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide Title?', 'link-widget' );?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e( 'Link Text:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_attr( $link_text ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php _e( 'Link URL:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('link_url'); ?>" name="<?php echo $this->get_field_name('link_url'); ?>" type="text" value="<?php echo esc_attr( esc_url( $link_url ) ); ?>" />
			</label>
		</p>
		<p style="width:33%;float:left;">
			<label for="<?php echo $this->get_field_id( 'wrapping_tag' ); ?>"><?php _e( 'Wrapping Tag:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('wrapping_tag'); ?>" name="<?php echo $this->get_field_name('wrapping_tag'); ?>" type="text" value="<?php echo esc_attr( $wrapping_tag ); ?>" />
			</label>
		</p>
		<p style="width:63%;float:right;padding-top:20px;height:20px;">
		<code>div</code>, <code>p</code>, <code>section</code>, <code>span</code>, etc.
		</p>
		<?php
	} //end form()

}

// eof