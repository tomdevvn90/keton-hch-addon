<?php 
// Include template archive recipes

add_filter( 'template_include', 'keton_force_template_recipe_cat' );

add_filter ('woocommerce_price_filter_widget_step','custom_filter_price_widget',999);

?>