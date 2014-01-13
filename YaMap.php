<?php
/**
	Show ads positions on an Yandex map 
*/
 
add_action( 'widgets_init', 'register_lisette_jdp_yamap_widget' );

function register_lisette_jdp_yamap_widget() {
	register_widget('Lisette_JDP_YaMap' );
}

class Lisette_JDP_YaMap extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'lisette_jdp_yamap',
			__('Lisette_JDP YaMap', 'lisette_jdp'),
			array( 'description' => __( 'Show ads on an Yandex Map', 'lisette_jdp' ), )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if ( is_home() || is_single() ) {
			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $args['before_widget'];

			if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];
			
			$yamap = dirname( __FILE__ ) . '/views/yamap.php'; 
			if( file_exists( $yamap ) )
				include_once( $yamap );
			else
				echo __('Map not found!','lisette_jdp');
			
			echo $args['after_widget'];
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" 
				id="<?php echo $this->get_field_id( 'title' ); ?>" 
				name="<?php echo $this->get_field_name( 'title' ); ?>" 
				type="text" 
				value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}
