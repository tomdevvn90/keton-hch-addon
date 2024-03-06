<?php 
// Include template archive recipes

add_filter( 'template_include', 'be_force_template_recipe_cat' );

// hook rewrite step filter price

add_filter ('woocommerce_price_filter_widget_step','be_force_step_filter_price_widget',999);

//hook rewrite min price filter

add_filter ( 'woocommerce_price_filter_widget_min_amount','be_force_min_amout_price_widget',999);

?>