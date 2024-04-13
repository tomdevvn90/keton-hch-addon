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

if ( ! function_exists( 'keton_4web_breadcrubms' ) ) {
    function keton_4web_breadcrubms() {
        global $wp_query, $post, $paged;

        $space      = '';
        $on_front   = get_option( 'show_on_front' );
        $blog_page  = get_option( 'page_for_posts' );
        $separator  = '';
        $link       = apply_filters( 'keton_breadcrumb_link', '<li><a  href="%1$s" title="%2$s" rel="bookmark">%2$s</a> </li>  ' );
        $current    = apply_filters( 'keton_breadcrumb_current', '<li><span>%s</span></li>' );

        if ( ( $on_front == 'page' && is_front_page() ) || ( $on_front == 'posts' && is_home() ) ) {
            return;
        }

        $out = '';

        if ( $on_front == "page" && is_home() ) {
            $blog_title = isset( $blog_page ) ? get_the_title( $blog_page ) : esc_html__( 'Our Blog', '4web-addons' );
            $out .= sprintf( $link, home_url(), esc_html__( 'DOMOV', '4web-addons' ) ) . $separator . sprintf( $current, $blog_title );
        } else {
            $out .= sprintf( $link, home_url(), esc_html__( 'DOMOV', '4web-addons' ) );
        }

        if ( is_singular() ) {

            if ( is_singular( 'post' ) && $blog_page > 0 ) {
                $out .= $separator . sprintf( $link, get_permalink( $blog_page ), esc_attr( get_the_title( $blog_page ) ) );
            }

            if ( $post->post_parent > 0 ) {
                if ( isset( $post->ancestors ) ) {
                    if ( is_array( $post->ancestors ) )
                        $ancestors = array_values( $post->ancestors );
                    else
                        $ancestors = array( $post->ancestors );
                } else {
                    $ancestors = array( $post->post_parent );
                }
                foreach ( array_reverse( $ancestors ) as $key => $value ) {
                    $out .= $separator . sprintf( $link, get_permalink( $value ), esc_attr( get_the_title( $value ) ) );
                }
            }

            $post_type = get_post_type();
            if ( get_post_type_archive_link( $post_type ) ) {
                $post_type_obj = get_post_type_object( get_post_type($post) );
                $out .= $separator . sprintf( $link, get_post_type_archive_link( $post_type ), esc_attr( $post_type_obj->labels->menu_name ) );
            }

            $out .= $separator . sprintf( $current, get_the_title() );

        } else {
            if ( is_post_type_archive() ) {
                $post_type = get_post_type();
                $post_type_obj = get_post_type_object( get_post_type($post) );
                // echo '<pre>';
                // print_r($post_type_obj);
                // echo '</pre>';
                $out .= $separator . sprintf( $current, $post_type_obj->labels->menu_name );
            } else if ( is_tax() ) {
                if ( is_tax( 'download_tag' ) || is_tax( 'download_category' ) ) {
                    $post_type = get_post_type();
                    $post_type_obj = get_post_type_object( get_post_type($post) );
                    $out .= $separator . sprintf( $link, get_post_type_archive_link( $post_type ), esc_attr( $post_type_obj->labels->menu_name ) );
                }
                $out .= $separator . sprintf( $current, $wp_query->queried_object->name );
            } else if ( is_category() ) {
                $out .= $separator . esc_html__( 'Category : ', '4web-addons' ) . sprintf( $current, $wp_query->queried_object->name );
            } else if ( is_tag() ) {
                $out .= $separator . esc_html__( 'Tag : ', '4web-addons' ) . sprintf( $current, $wp_query->queried_object->name );
            } else if ( is_date() ) {
                $out .= $separator;
                if ( is_day() ) {
                    global $wp_locale;
                    $out .= sprintf( $link, get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ), $wp_locale->get_month( get_query_var( 'monthnum' ) ).' '.get_query_var( 'year' ) );
                    $out .= $separator . sprintf( $current, get_the_date() );
                } else if ( is_month() ) {
                    $out .= sprintf( $current, single_month_title( ' ', false ) );
                } else if ( is_year() ) {
                    $out .= sprintf( $current, get_query_var( 'year' ) );
                }
            } else if ( is_404() ) {
                $out .= $separator . sprintf( $current, esc_html__( 'Error 404', '4web-addons' ) );
            } else if ( is_search() ) {
                $out .= $separator . sprintf( $current, esc_html__( 'Search', '4web-addons' ) );
            }
        }
        $out .= '';
        return  '<ul class="keton-breadcrumb-menu">'.apply_filters( 'keton_breadcrumbs_out', $out ).'</ul>';
    }
}
add_shortcode( "keton_breadcrumb", 'keton_4web_breadcrubms' );

?>