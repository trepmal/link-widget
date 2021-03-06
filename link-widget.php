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

	/**
	 *
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'link-widget',
			'description' => __( 'Insert a link', 'link-widget' )
		);
		$control_ops = array();
		parent::__construct( 'linkwidget', __( 'Link', 'link-widget' ), $widget_ops, $control_ops );
	}

	/**
	 *
	 */
	function widget( $args, $instance ) {

		echo $args['before_widget'];

		echo $instance['hide_title'] ? '' : $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];

		$link = '<a href="' . esc_url( $instance['link_url'] ) . '">' . esc_html( $instance['link_text'] ) . '</a>';

		if ( ! empty( $instance['wrapping_tag'] ) ) {
			global $allowedposttags;
			$allowed_tags = array_keys( $allowedposttags );
			$tag = in_array( $instance['wrapping_tag'], $allowed_tags ) ? $instance['wrapping_tag'] : '';
			if ( ! empty( $tag ) ) {
				$link = "<{$tag}>$link</{$tag}>";
			}
		}

		echo $link;

		echo $args['after_widget'];

	} //end widget()

	/**
	 *
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title']        = wp_strip_all_tags( $new_instance['title'] );
		$instance['hide_title']   = (bool) $new_instance['hide_title'] ? 1 : 0;
		$instance['link_text']    = wp_strip_all_tags( $new_instance['link_text'] );
		$instance['link_url']     = esc_url_raw( $new_instance['link_url'] );
		$instance['wrapping_tag'] = wp_strip_all_tags( $new_instance['wrapping_tag'] );

		return $instance;

	} //end update()

	/**
	 *
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'        => '',
			'hide_title'   => 1,
			'link_text'    => __( 'Click Here', 'link-widget' ),
			'link_url'     => 'http://',
			'wrapping_tag' => 'p'
		) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</label>
			<span>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>"<?php checked( $instance['hide_title'] ); ?> />
				<label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e('Hide Title?', 'link-widget' );?></label>
			</span>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e( 'Link Text:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" type="text" value="<?php echo esc_attr( $instance['link_text'] ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php _e( 'Link URL:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'link_url' ); ?>" name="<?php echo $this->get_field_name( 'link_url' ); ?>" type="text" value="<?php echo esc_attr( esc_url( $instance['link_url'] ) ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'wrapping_tag' ); ?>"><?php _e( 'Wrapping Tag:', 'link-widget' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'wrapping_tag' ); ?>" name="<?php echo $this->get_field_name( 'wrapping_tag' ); ?>" type="text" value="<?php echo esc_attr( $instance['wrapping_tag'] ); ?>" />
			</label>
			<span class="description">
				<code>div</code>, <code>p</code>, <code>section</code>, <code>span</code>, etc.
			</span>
		</p>
		<?php
	} //end form()

}

// eof