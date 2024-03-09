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


function be_ajax_pagination_product_callback( $atts ){
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 5,
            'paged' => 1,
            'post_type' => 'post',
            'categories'=>'',
            'range_price'=>'',
            'tax'=>'',
            'slug_attr'=>'',
            'status_product'=>'',
            'ingredient'=>''
        ), $atts,'be_product_result_shop'
    );
    $posts_per_page = intval($atts['posts_per_page']);
    $paged = intval($atts['paged']);
    $post_type = sanitize_text_field($atts['post_type']);
    $categories = $atts['categories'];
    $range_price = $atts['range_price'];
    $tax = $atts['tax'];
    $status_product = $atts['status_product'];
    $slug_attr = $atts['slug_attr'];
    $ingredient = $atts['ingredient'];
    $allpost  = '<div id="result_ajaxp_filter_shop">';
    $allpost .= be_query_ajax_product_shop( $post_type, $posts_per_page , $paged, $categories, $range_price, $tax, $slug_attr, $status_product, $ingredient);
    $allpost .= '</div>';
    return $allpost;
}
add_shortcode('be_product_result_shop', 'be_ajax_pagination_product_callback');

?>