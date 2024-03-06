<?php 
function be_ajax_pagination_callback( $atts ){
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 5,
            'paged' => 1,
            'post_type' => 'post',
            'post_not_in'=>'',
        ), $atts,'be_ajax_pagination'
    );
    $posts_per_page = intval($atts['posts_per_page']);
    $paged = intval($atts['paged']);
    $post_type = sanitize_text_field($atts['post_type']);
    $post_not_in = $atts['post_not_in'];
    $allpost  = '<div id="result_ajaxp">';
    $allpost .= be_query_ajax_pagination( $post_type, $posts_per_page , $paged, $post_not_in );
    $allpost .= '</div>';
    return $allpost;
}
add_shortcode('be_ajax_pagination', 'be_ajax_pagination_callback');

?>