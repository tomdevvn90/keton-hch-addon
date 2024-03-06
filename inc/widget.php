<?php 


add_action( 'widgets_init', 'create_popular_filter_widget' );
function create_popular_filter_widget() {
	register_widget('Popular_filter_Widget');
}


class Popular_filter_Widget extends WP_Widget {
	function __construct() {
        parent::__construct (
            'popular_filter_widget',
            'Popular Filter Widget',
            array(
                'description' => 'Create popular filter widget'
            )
        );

	}


	function form( $instance ) {


	}


	function update( $new_instance, $old_instance ) {


	}


	function widget( $args, $instance ) {


	}


}

?>