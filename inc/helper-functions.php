<?php 
 function be_get_taxonomies($taxonomy) {
    $options = array();
    $args = array(
           'taxonomy'     => $taxonomy,
           'orderby'      => 'name',
           'hide_empty'   => false
    );
    $all_categories = get_categories( $args );
    if(!empty($all_categories) && !is_wp_error($all_categories)) {
        foreach ($all_categories as $cat) {
            $options[$cat->term_id] = $cat->name;
        }
    }
    return $options;
}



function keton_force_template_recipe_cat( $template ) {
    if( is_tax('recipe-cat') || is_archive('recipe')) {
        $template = B_HELPERS_DIR .'/template/archive-recipe.php';
    }

    // if(is_archive('recipe')) {
    //     $template = B_HELPERS_DIR .'/template/archive-recipe.php';
    // }
    return $template;
}



?>