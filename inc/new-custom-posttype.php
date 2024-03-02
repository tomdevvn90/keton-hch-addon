<?php
/**
* Register new custom post type
*/

if ( ! function_exists( 'hch_custom_post_type_recipes' ) ) {

	// Register Recipes Post Type
	function hch_custom_post_type_recipes() {
		$labels  = array(
			'name'                  => _x( 'Recipes', 'Post Type General Name', 'hch-addons' ),
			'singular_name'         => _x( 'Recipes', 'Post Type Singular Name', 'hch-addons' ),
			'menu_name'             => __( 'Recipes', 'hch-addons' ),
			'name_admin_bar'        => __( 'Recipes', 'hch-addons' ),
			'archives'              => __( 'Item Archives', 'hch-addons' ),
			'attributes'            => __( 'Item Attributes', 'hch-addons' ),
			'parent_item_colon'     => __( 'Parent Item:', 'hch-addons' ),
			'all_items'             => __( 'All Items', 'hch-addons' ),
			'add_new_item'          => __( 'Add New Item', 'hch-addons' ),
			'add_new'               => __( 'Add New', 'hch-addons' ),
			'new_item'              => __( 'New Item', 'hch-addons' ),
			'edit_item'             => __( 'Edit Item', 'hch-addons' ),
			'update_item'           => __( 'Update Item', 'hch-addons' ),
			'view_item'             => __( 'View Item', 'hch-addons' ),
			'view_items'            => __( 'View Items', 'hch-addons' ),
			'search_items'          => __( 'Search Item', 'hch-addons' ),
			'not_found'             => __( 'Not found', 'hch-addons' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'hch-addons' ),
			'featured_image'        => __( 'Featured Image', 'hch-addons' ),
			'set_featured_image'    => __( 'Set featured image', 'hch-addons' ),
			'remove_featured_image' => __( 'Remove featured image', 'hch-addons' ),
			'use_featured_image'    => __( 'Use as featured image', 'hch-addons' ),
			'insert_into_item'      => __( 'Insert into item', 'hch-addons' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'hch-addons' ),
			'items_list'            => __( 'Items list', 'hch-addons' ),
			'items_list_navigation' => __( 'Items list navigation', 'hch-addons' ),
			'filter_items_list'     => __( 'Filter items list', 'hch-addons' ),
		);
		$rewrite = array(
			'slug'       => 'recipe',
		);
		$args    = array(
			'label'               => __( 'Recipes', 'hch-addons' ),
			'description'         => __( 'Post Type Description', 'hch-addons' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'taxonomies'          => array( 'recipe-cat' ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'query_var'           => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-clipboard',
			'has_archive'         => true,
			'exclude_from_search' => false,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		);
		register_post_type( 'recipe', $args );
	}
	add_action( 'init', 'hch_custom_post_type_recipes', 0 );
}



if ( ! function_exists( 'hch_custom_post_type_video_recipes' ) ) {

	// Register Recipes Video Post Type
	function hch_custom_post_type_video_recipes() {
		$labels  = array(
			'name'                  => _x( 'Recipes Video', 'Post Type General Name', 'hch-addons' ),
			'singular_name'         => _x( 'Recipes Video', 'Post Type Singular Name', 'hch-addons' ),
			'menu_name'             => __( 'Recipes Video', 'hch-addons' ),
			'name_admin_bar'        => __( 'Recipes Video', 'hch-addons' ),
			'archives'              => __( 'Item Archives', 'hch-addons' ),
			'attributes'            => __( 'Item Attributes', 'hch-addons' ),
			'parent_item_colon'     => __( 'Parent Item:', 'hch-addons' ),
			'all_items'             => __( 'All Items', 'hch-addons' ),
			'add_new_item'          => __( 'Add New Item', 'hch-addons' ),
			'add_new'               => __( 'Add New', 'hch-addons' ),
			'new_item'              => __( 'New Item', 'hch-addons' ),
			'edit_item'             => __( 'Edit Item', 'hch-addons' ),
			'update_item'           => __( 'Update Item', 'hch-addons' ),
			'view_item'             => __( 'View Item', 'hch-addons' ),
			'view_items'            => __( 'View Items', 'hch-addons' ),
			'search_items'          => __( 'Search Item', 'hch-addons' ),
			'not_found'             => __( 'Not found', 'hch-addons' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'hch-addons' ),
			'featured_image'        => __( 'Featured Image', 'hch-addons' ),
			'set_featured_image'    => __( 'Set featured image', 'hch-addons' ),
			'remove_featured_image' => __( 'Remove featured image', 'hch-addons' ),
			'use_featured_image'    => __( 'Use as featured image', 'hch-addons' ),
			'insert_into_item'      => __( 'Insert into item', 'hch-addons' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'hch-addons' ),
			'items_list'            => __( 'Items list', 'hch-addons' ),
			'items_list_navigation' => __( 'Items list navigation', 'hch-addons' ),
			'filter_items_list'     => __( 'Filter items list', 'hch-addons' ),
		);
		$rewrite = array(
			'slug'       => 'recipe-video',
		);
		$args    = array(
			'label'               => __( 'Recipes Video', 'hch-addons' ),
			'description'         => __( 'Post Type Description', 'hch-addons' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'query_var'           => true,
			'show_in_menu'        => true,
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-format-video',
			'has_archive'         => true,
			'exclude_from_search' => false,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		);
		register_post_type( 'recipe-video', $args );
	}
	add_action( 'init', 'hch_custom_post_type_video_recipes', 0 );
}

if ( ! function_exists( 'hch_custom_taxonomy_recipes' ) ) {

	// Register Recipes Categories
	function hch_custom_taxonomy_recipes() {
		$labels  = array(
			'name'                       => _x( 'Categories', 'Taxonomy General Name', 'hch-addons' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'hch-addons' ),
			'menu_name'                  => __( 'Categories', 'hch-addons' ),
			'all_items'                  => __( 'All Items', 'hch-addons' ),
			'parent_item'                => __( 'Parent Item', 'hch-addons' ),
			'parent_item_colon'          => __( 'Parent Item:', 'hch-addons' ),
			'new_item_name'              => __( 'New Item Name', 'hch-addons' ),
			'add_new_item'               => __( 'Add New Item', 'hch-addons' ),
			'edit_item'                  => __( 'Edit Item', 'hch-addons' ),
			'update_item'                => __( 'Update Item', 'hch-addons' ),
			'view_item'                  => __( 'View Item', 'hch-addons' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'hch-addons' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'hch-addons' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'hch-addons' ),
			'popular_items'              => __( 'Popular Items', 'hch-addons' ),
			'search_items'               => __( 'Search Items', 'hch-addons' ),
			'not_found'                  => __( 'Not Found', 'hch-addons' ),
			'no_terms'                   => __( 'No items', 'hch-addons' ),
			'items_list'                 => __( 'Items list', 'hch-addons' ),
			'items_list_navigation'      => __( 'Items list navigation', 'hch-addons' ),
		);
		$rewrite = array(
			'slug'         => 'recipe-cat',
			'hierarchical' => true,
		);
		$args    = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'rewrite'           => $rewrite,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'recipe-cat', array( 'recipe' ), $args );

	}
	add_action( 'init', 'hch_custom_taxonomy_recipes', 0 );
}

// Add the custom columns to the book post type:
// add_filter( 'manage_recipe_posts_columns', 'hch_set_custom_edit_recipe_columns' );
function hch_set_custom_edit_recipe_columns($columns) {
    // Remove Date and Template
    unset($columns['date']);
		$columns['image'] = __( 'Image', 'cosmos' );
    $columns['location'] = __( 'Location', 'cosmos' );
    $columns['date'] = 'Date';
    return $columns;
}
// Add the data to the custom columns for the book post type:
// add_action( 'manage_recipe_posts_custom_column' , 'hch_custom_recipe_column', 10, 2 );
function hch_custom_recipe_column( $column, $post_id ) {
    switch ( $column ) {
        case 'location' :
          $location = get_field('location',$post_id);
          echo $location;
          break;
        case 'image' :
					$image = get_field('result_picture',$post_id);
          echo '<img style="max-width: 100px;height: auto;" src="'.$image.'" />';
					break;
    }
}