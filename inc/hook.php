<?php 
// Include template archive recipes
add_filter( 'template_include', 'be_force_template_recipe_cat' );

// Hook rewrite step filter price
add_filter ('woocommerce_price_filter_widget_step','be_force_step_filter_price_widget',999);

// Hook rewrite min price filter
add_filter ( 'woocommerce_price_filter_widget_min_amount','be_force_min_amout_price_widget',999);

// Hook ajax pagination
add_action( 'wp_ajax_LoadPostPagination', 'LoadPostPagination_init' );
add_action( 'wp_ajax_nopriv_LoadPostPagination', 'LoadPostPagination_init' );

// Hook save data filter popular 
add_action( 'wp_ajax_nopriv_SaveDataPopularFilter', 'be_save_data_popular_filter_callback' );
add_action( 'wp_ajax_SaveDataPopularFilter', 'be_save_data_popular_filter_callback' );

//Hook add slug attribute 
add_filter('woocommerce_layered_nav_count','be_add_attribute_slug_callback',99,3);

//Hook override orderby filter
add_filter('woocommerce_catalog_orderby','be_woocommerce_catalog_orderby_callback',99);
add_filter('woocommerce_get_catalog_ordering_args','be_custom_query_orderby_callback',99,3);

//Hook filter result filter
add_action( 'woocommerce_before_shop_loop', 'be_show_result_filter_popular', 99 );

// Hook ajax filter product categories
add_action( 'wp_ajax_filter_cat_product_shop', 'filter_cat_product_shop_init' );
add_action( 'wp_ajax_nopriv_filter_cat_product_shop', 'filter_cat_product_shop_init' );

//Hook add ingredient single product
//add_filter( 'woocommerce_short_description', 'ingredient_single_product_callback',1,99);

//Hook filter tax query woocommerce
add_filter( 'woocommerce_product_query_tax_query', 'hch_woocommerce_product_query_tax_query', 10, 2 );

//Remove template grid view product
remove_action( 'woocommerce_before_shop_loop', 'bacola_catalog_ordering_start', 30 );

//Add action template grid view product
add_action( 'woocommerce_before_shop_loop', 'hch_catalog_ordering_start', 31 );

//Add element scroll responsive ajax filter product
add_action( 'woocommerce_before_main_content','add_element_scroll_jax_filter');

// Add custom breadcrumb bar
add_action( 'woocommerce_before_main_content','keton_add_custom_breadcrumb');

//Add action template grid view product
add_action( 'woocommerce_after_shop_loop', 'keton_add_signup_form_for_shop' );

// Update rating most single product 
//add_action('init','update_rating_most_product');

// Update count rating single product
//add_action( 'comment_post', 'update_rating_most_single_product', 10, 3 );

// Hook for After Main Content of Product
// add_action( 'woocommerce_after_main_content', 'be_add_related_recipes_blog_video', 99);
add_action('get_footer','be_add_related_recipes_blog_video',10);
//add_action('get_footer','be_get_reviews_single_product',11);

//Hook custom single product
remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
add_action('web4_single_header_top','web4_single_product_header');
add_action('4web_single_product_summary','add_categories_before_description',10,1);
add_action( 'woocommerce_after_add_to_cart_button', 'add_custom_addtocart_and_checkout' );
add_action('custom_guarante_product_variable','add_guarante_single_product');
add_filter( 'woocommerce_format_sale_price', 'cw_change_product_price_display', 11, 3 );

//Add css admin 
add_action('admin_head', 'custom_admin_dashboard'); 




?>