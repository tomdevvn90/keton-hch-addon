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
//add_filter('woocommerce_layered_nav_count','be_add_attribute_slug_callback',2,99);

//Hook override orderby filter
// add_fillter('woocommerce_catalog_orderby','be_woocommerce_catalog_orderby_callback',99);

?>