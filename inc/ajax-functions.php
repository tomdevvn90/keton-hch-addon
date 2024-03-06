<?php
/* Ajax Functions */


/* Filter Products on carousel */

add_action( 'wp_ajax_nopriv_be_filter_products_on_carousel', 'be_filter_products_on_carousel_callback' );
add_action( 'wp_ajax_be_filter_products_on_carousel', 'be_filter_products_on_carousel_callback' );
function be_filter_products_on_carousel_callback() {
	global $wpdb;
	
	$settings = $_POST['settings'];
	$filter_by = $_POST['filter_by'];

	$more_products_url = "/shop-2/";
	$products_html = '';	
			
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	} else {
		$paged = 1;
	}

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'paged' 			   => $paged,
		'posts_per_page' => $settings['post_count'],
		'post__in'       => $settings['post_include_filter'],
		'order'          => $settings['order'],
		'orderby'        => $settings['orderby']
	);

	$args['klb_special_query'] = true;

	if($settings['hide_out_of_stock_items'] == 'true'){
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'outofstock',
				'operator' => 'NOT IN',
			),
		);
	}

	if($settings['cat_filter']){
		$args['tax_query'][] = array(
			'taxonomy' 	=> 'product_cat',
			'field' 	=> 'term_id',
			'terms' 	=> $settings['cat_filter']
		);
	}

	if($settings['tag_filter']){
		$args['tax_query'][] = array(
			'taxonomy' 	=> 'product_tag',
			'field' 	=> 'term_id',
			'terms' 	=> $settings['tag_filter']
		);
	}

	if( $filter_by == 'best_selling' ){
		$args['meta_key'] = 'total_sales';
		$args['orderby'] = 'meta_value_num';

		$more_products_url = "shop-2/?orderby=popularity";
	} else {
		if( $filter_by == 'on_sale' ){
			$args['meta_key'] = '_sale_price';
			$args['meta_value'] = array('');
			$args['meta_compare'] = 'NOT IN';

			$more_products_url = "/shop-2/?on_sale=onsale";
		} else {
			if( $filter_by == 'featured' ){
				$args['tax_query'] = array( array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'featured' ),
						'operator' => 'IN',
				) );

				$more_products_url = "/shop-2/?orderby=rating";
			} else {
				// Do nothing
			}
		}
	}
	
	$loop = new \WP_Query( $args );
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();
			global $product;
			global $post;
			global $woocommerce;

			$products_html .= '<div class="slide-item"><div class="'.esc_attr( implode( ' ', wc_get_product_class( '', $product->get_id()))).'">';
			if($settings['product_type'] == 'type4'){
				$products_html .= bacola_product_type4();
			}elseif($settings['product_type'] == 'type2'){
				$products_html .= bacola_product_type2();
			} else {
				$products_html .= bacola_product_type1();
			}
			$products_html .= '</div></div>';
		
		endwhile;
	}
	wp_reset_postdata();

	if ( $products_html ) {
		$output .= '<div class="module-body">';
		$output .= '<div class="slider-wrapper">';
		$output .= '<div class="products be-slider" data-slideshow="'.esc_attr($settings['column']).'" data-mobile="'.esc_attr($settings['mobile_column']).'" data-slidespeed="'.esc_attr($settings['slide_speed']).'" data-arrows="'.esc_attr($settings['arrows']).'" data-autoplay="'.esc_attr($settings['auto_play']).'" data-autospeed="'.esc_attr($settings['auto_speed']).'" data-dots="'.esc_attr($settings['dots']).'">';
		$output .= $products_html;
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="more-products"><a class="be-btn-link" href="'.$more_products_url.'">'. __( "Prikaži več izdelkov", "hch-addons" ) .'</a></div>';
		echo $output;
		?>
		<script>
				var BeProductsSliderHandler = function () {
					var container = jQuery(".be-slider");

					container.each(function () {
						var self = jQuery(this);

						var sliderItems = jQuery(".slider-item");
						sliderItems.imagesLoaded(function () {
							self.closest(".slider-wrapper").addClass("slider-loaded");
						});

						var autoplay = jQuery(this).data("autoplay");
						var autospeed = jQuery(this).data("autospeed");
						var arrows = jQuery(this).data("arrows");
						var dots = jQuery(this).data("dots");
						var slidescroll = jQuery(this).data("slidescroll");
						var slidespeed = jQuery(this).data("slidespeed");
						var asnav = jQuery(this).data("asnav");
						var focusselect = jQuery(this).data("focusselect");
						var vertical = jQuery(this).data("vertical");
						var mobileslide = 1;

						if (jQuery(this).hasClass("products")) {
							var mobileslide = jQuery(this).data("mobile");
						}

						self.not(".slick-initialized").slick({
							autoplay: autoplay,
							autoplaySpeed: autospeed,
							arrows: arrows,
							dots: dots,
							slidesToShow: 4,
							slidesToScroll: slidescroll,
							speed: slidespeed,
							asNavFor: asnav,
							focusOnSelect: focusselect,
							centerPadding: false,
							cssEase: "cubic-bezier(.48,0,.12,1)",
							vertical: vertical,
							responsive: [
								{
									breakpoint: 9999,
									settings: "unslick",
								},
								{
									breakpoint: 991,
									settings: {
										slidesToShow: mobileslide < 3 ? mobileslide : 2,
									},
								},
							],
						});
					});

					// Rebuild the carousel after unslick
					jQuery(window).resize(function () {
						jQuery(".be-slider").not(".slick-initialized").slick("resize");
					});
					jQuery(window).on("orientationchange", function () {
						jQuery(".be-slider").not(".slick-initialized").slick("resize");
					});
				};

				var beProductHover = function() {
					var product = jQuery( '.be-slider.products .product' );

					product.each( function(e) {
						var fadeBlock = jQuery(this).find( '.product-fade-block' );
						var contentBlock = jQuery(this).find( '.product-content-fade' );
						var outerHeight = 0;

						if ( fadeBlock.length ) {
							fadeBlock.each( function(e) {
								var self = jQuery(this);
								outerHeight += self.outerHeight();
				
								contentBlock.css( 'marginBottom', -outerHeight );
							});
						}
					});
				}

				BeProductsSliderHandler();
				beProductHover();

		</script>
		<?php
	} else {
		$output .= '<div class="no-item-found"><h3>'. __( "Ujemajočih izdelkov ni mogoče najti.", "hch-addons" ) .'</h3></div>';
		echo $output;
	}

	wp_die();

}

/* Filter Recipes on carousel */
add_action( 'wp_ajax_nopriv_be_filter_recipes_on_carousel', 'be_filter_recipes_on_carousel_callback' );
add_action( 'wp_ajax_be_filter_recipes_on_carousel', 'be_filter_recipes_on_carousel_callback' );
function be_filter_recipes_on_carousel_callback() {
	global $wpdb;
	
	$settings = $_POST['settings'];
	$filter_by = $_POST['filter_by'];

	$recipes_html = '';	
			
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	} else {
		$paged = 1;
	}

	$args = array(
		'post_type'      => 'recipe',
		'post_status'    => 'publish',
		'paged' 			   => $paged,
		'posts_per_page' => $settings['post_count'],
		// 'post__in'       => $settings['post_include_filter'],
		'order'          => $settings['order'],
		'orderby'        => $settings['orderby']
	);

	$args['klb_special_query'] = true;

	// if($settings['cat_filter']){
	// 	$args['tax_query'][] = array(
	// 		'taxonomy' 	=> 'recipe-cat',
	// 		'field' 	=> 'term_id',
	// 		'terms' 	=> $settings['cat_filter']
	// 	);
	// }

	if($filter_by){
		$args['tax_query'][] = array(
			'taxonomy' 	=> 'recipe-cat',
			'field' 	=> 'term_id',
			'terms' 	=> $filter_by
		);
	} else {
		$args['orderby'] = 'rand';
	}
	
	$loop = new \WP_Query( $args );
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();
			global $post;
			$terms = get_the_terms( $post->ID, 'recipe-cat' );

			$recipes_html .= '<div class="slide-item"><a href="'.get_permalink($post->ID).'" class="slider-inner">';
			$recipes_html .= get_the_post_thumbnail( $post->ID, 'medium' );
			if ( !empty( $terms ) ){
				$recipes_html .= '<div class="cats">' . join(', ', wp_list_pluck($terms, 'name')) . '</div>';
			}
			$recipes_html .= '<h3>'.$post->post_title.'</h3>';
			$recipes_html .= '<div class="crt-date">' . get_the_date( 'F j, Y' ) . '</div>';
			$recipes_html .= '</a></div>';
		
		endwhile;
	}
	wp_reset_postdata();

	if ( $recipes_html ) {
		$output .= '<div class="module-body">';
		$output .= '<div class="slider-wrapper">';
		$output .= '<div class="recipes be-normal-slider" data-slideshow="'.esc_attr($settings['column']).'" data-mobile="'.esc_attr($settings['mobile_column']).'" data-slidespeed="'.esc_attr($settings['slide_speed']).'" data-arrows="'.esc_attr($settings['arrows']).'" data-autoplay="'.esc_attr($settings['auto_play']).'" data-autospeed="'.esc_attr($settings['auto_speed']).'" data-dots="'.esc_attr($settings['dots']).'">';
		$output .= $recipes_html;
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		echo $output;
		?>
		<script>
				var BeNormalSliderHandler = function () {
					var container = jQuery(".be-normal-slider");

					container.each(function () {
						var self = jQuery(this);

						var sliderItems = jQuery(".slider-item");
						sliderItems.imagesLoaded(function () {
							self.closest(".slider-wrapper").addClass("slider-loaded");
						});

						var autoplay = jQuery(this).data("autoplay");
						var autospeed = jQuery(this).data("autospeed");
						var arrows = jQuery(this).data("arrows");
						var dots = jQuery(this).data("dots");
						var slideshow = jQuery(this).data("slideshow");
						var slidescroll = jQuery(this).data("slidescroll");
						var slidespeed = jQuery(this).data("slidespeed");
						var asnav = jQuery(this).data("asnav");
						var focusselect = jQuery(this).data("focusselect");
						var vertical = jQuery(this).data("vertical");
						var mobileslide = jQuery(this).data("mobile");

						self.not(".slick-initialized").slick({
							autoplay: autoplay,
							autoplaySpeed: autospeed,
							arrows: arrows,
							dots: dots,
							slidesToShow: slideshow,
							slidesToScroll: slidescroll,
							speed: slidespeed,
							asNavFor: asnav,
							focusOnSelect: focusselect,
							centerPadding: false,
							cssEase: "cubic-bezier(.48,0,.12,1)",
							vertical: vertical,
							responsive: [
								{
									breakpoint: 1200,
									settings: {
										slidesToShow: slideshow < 5 ? slideshow : 4,
									},
								},
								{
									breakpoint: 1024,
									settings: {
										slidesToShow: slideshow < 4 ? slideshow : 3,
									},
								},
								{
									breakpoint: 991,
									settings: {
										slidesToShow: slideshow < 3 ? slideshow : 2,
									},
								},
								{
									breakpoint: 768,
									settings: {
										slidesToShow: slideshow < 2 ? slideshow : mobileslide,
									},
								},
							],
						});
					});
				};
				BeNormalSliderHandler();

		</script>
		<?php
	} else {
		$output .= '<div class="no-item-found"><h3>'. __( "Ni ustreznih receptov.", "hch-addons" ) .'</h3></div>';
		echo $output;
	}

	wp_die();

}

/* Ajax pagination */
function LoadPostPagination_init() {
    $posts_per_page = intval($_POST['posts_per_page']);
    $paged = intval($_POST['data_page']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $post_not_in = intval($_POST['post_not_in']);
    $allpost = be_query_ajax_pagination( $post_type, $posts_per_page , $paged, $post_not_in );
    echo $allpost;
    exit;
}

/* Save data filter popular  */
function be_save_data_popular_filter_callback() {
	$id_cat = $_POST['filter_cat'];
	$min_max_price = $_POST['min_max_price'];
	$status_product = $_POST['status_product'];
	$brand_status = $_POST['brand_slug'];
	$brand_name = $_POST['brand_name'];
	if(!empty($id_cat)) {
		$data_filter_cat_init = get_field('cat_filter_popular','options');
		if(!empty($data_filter_cat_init)) {
			foreach ($data_filter_cat_init as $key => $value) {
				$count = $value['count_popular_filter'];
				if(intval($value['slug_cat_filter']) == intval($id_cat) ) {
					$data_filter_cat_init[$key]['count_popular_filter'] = intval($count) + 1;
					break;
				}else{
					array_push($data_filter_cat_init,['slug_cat_filter'=>$id_cat,'count_popular_filter'=>1]);
					break;
				}
			}
			update_field('cat_filter_popular',$data_filter_cat_init,'options');
		}else{
			$data_save = [];
			array_push($data_save,['slug_cat_filter'=>$id_cat,'count_popular_filter'=>1]);
			update_field('cat_filter_popular',$data_save,'options');
		}
	}

	if(!empty($min_max_price)) {
		$data_filter_min_max_init = get_field('price_filter_popular','options');
		if(!empty($data_filter_min_max_init)) {
			foreach ($data_filter_min_max_init as $key => $value) {
				$count = $value['count_min_max_filter'];
				if($value['price_filter_min_max'] == $min_max_price ) {
					$data_filter_min_max_init[$key]['count_min_max_filter'] = intval($count) + 1;
					break;
				}else{
					array_push($data_filter_min_max_init,['price_filter_min_max'=>$min_max_price,'count_min_max_filter'=>1]);
					break;
				}
			}
			update_field('price_filter_popular',$data_filter_min_max_init,'options');
		}else{
			$data_save = [];
			array_push($data_save,['price_filter_min_max'=>$min_max_price,'count_min_max_filter'=>1]);
			update_field('price_filter_popular',$data_save,'options');
		}
	}

	if(!empty($status_product)) {
		$data_filter_status_product = get_field('product_status_filter_popular','options');
		if(!empty($data_filter_status_product)) {
			foreach ($data_filter_status_product as $key => $value) {
				$count = $value['count_product_status_filter'];
				if($value['slug_product_status_filter'] == $status_product ) {
					$data_filter_status_product[$key]['count_product_status_filter'] = intval($count) + 1;
					break;
				}else{
					array_push($data_filter_status_product,['slug_product_status_filter'=>$status_product,'count_product_status_filter'=>1]);
					break;
				}
			}
			update_field('product_status_filter_popular',$data_filter_status_product,'options');
		}else{
			$data_save = [];
			array_push($data_save,['slug_product_status_filter'=>$status_product,'count_product_status_filter'=>1]);
			update_field('product_status_filter_popular',$data_save,'options');
		}
	}

	if(!empty($brand_status) || !empty($brand_name)) {
		$data_filter_brand_status = get_field('brands_filter_popular','options');
		if(!empty($data_filter_brand_status)) {
			foreach ($data_filter_brand_status as $key => $value) {
				$count = $value['count_brand_filter'];
				if($value['slug_brand_filter'] == $brand_status ) {
					$data_filter_brand_status[$key]['count_brand_filter'] = intval($count) + 1;
					break;
				}else{
					array_push($data_filter_brand_status,['name_brand_filter'=>$brand_name,'slug_brand_filter'=>$brand_status,'count_brand_filter'=>1]);
					break;
				}
			}
			update_field('brands_filter_popular',$data_filter_brand_status,'options');
		}else{
			$data_save = [];
			array_push($data_save,['name_brand_filter'=>$brand_name,'slug_brand_filter'=>$brand_status,'count_brand_filter'=>1]);
			update_field('brands_filter_popular',$data_save,'options');
		}
	}
}
