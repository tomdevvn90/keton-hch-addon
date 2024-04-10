<?php
/**
* Register new custom post type
*/

if ( ! function_exists( 'hch_custom_post_type_recipes' ) ) {

	// Register Recipes Post Type
	function hch_custom_post_type_recipes() {
		$labels  = array(
			'name'                  => _x( 'Recipes', 'Post Type General Name', '4web-addons' ),
			'singular_name'         => _x( 'Recipes', 'Post Type Singular Name', '4web-addons' ),
			'menu_name'             => __( 'Recipes', '4web-addons' ),
			'name_admin_bar'        => __( 'Recipes', '4web-addons' ),
			'archives'              => __( 'Item Archives', '4web-addons' ),
			'attributes'            => __( 'Item Attributes', '4web-addons' ),
			'parent_item_colon'     => __( 'Parent Item:', '4web-addons' ),
			'all_items'             => __( 'All Items', '4web-addons' ),
			'add_new_item'          => __( 'Add New Item', '4web-addons' ),
			'add_new'               => __( 'Add New', '4web-addons' ),
			'new_item'              => __( 'New Item', '4web-addons' ),
			'edit_item'             => __( 'Edit Item', '4web-addons' ),
			'update_item'           => __( 'Update Item', '4web-addons' ),
			'view_item'             => __( 'View Item', '4web-addons' ),
			'view_items'            => __( 'View Items', '4web-addons' ),
			'search_items'          => __( 'Search Item', '4web-addons' ),
			'not_found'             => __( 'Not found', '4web-addons' ),
			'not_found_in_trash'    => __( 'Not found in Trash', '4web-addons' ),
			'featured_image'        => __( 'Featured Image', '4web-addons' ),
			'set_featured_image'    => __( 'Set featured image', '4web-addons' ),
			'remove_featured_image' => __( 'Remove featured image', '4web-addons' ),
			'use_featured_image'    => __( 'Use as featured image', '4web-addons' ),
			'insert_into_item'      => __( 'Insert into item', '4web-addons' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', '4web-addons' ),
			'items_list'            => __( 'Items list', '4web-addons' ),
			'items_list_navigation' => __( 'Items list navigation', '4web-addons' ),
			'filter_items_list'     => __( 'Filter items list', '4web-addons' ),
		);
		$rewrite = array(
			'slug'       => 'recipe',
		);
		$args    = array(
			'label'               => __( 'Recipes', '4web-addons' ),
			'description'         => __( 'Post Type Description', '4web-addons' ),
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
			'name'                  => _x( 'Recipes Video', 'Post Type General Name', '4web-addons' ),
			'singular_name'         => _x( 'Recipes Video', 'Post Type Singular Name', '4web-addons' ),
			'menu_name'             => __( 'Recipes Video', '4web-addons' ),
			'name_admin_bar'        => __( 'Recipes Video', '4web-addons' ),
			'archives'              => __( 'Item Archives', '4web-addons' ),
			'attributes'            => __( 'Item Attributes', '4web-addons' ),
			'parent_item_colon'     => __( 'Parent Item:', '4web-addons' ),
			'all_items'             => __( 'All Items', '4web-addons' ),
			'add_new_item'          => __( 'Add New Item', '4web-addons' ),
			'add_new'               => __( 'Add New', '4web-addons' ),
			'new_item'              => __( 'New Item', '4web-addons' ),
			'edit_item'             => __( 'Edit Item', '4web-addons' ),
			'update_item'           => __( 'Update Item', '4web-addons' ),
			'view_item'             => __( 'View Item', '4web-addons' ),
			'view_items'            => __( 'View Items', '4web-addons' ),
			'search_items'          => __( 'Search Item', '4web-addons' ),
			'not_found'             => __( 'Not found', '4web-addons' ),
			'not_found_in_trash'    => __( 'Not found in Trash', '4web-addons' ),
			'featured_image'        => __( 'Featured Image', '4web-addons' ),
			'set_featured_image'    => __( 'Set featured image', '4web-addons' ),
			'remove_featured_image' => __( 'Remove featured image', '4web-addons' ),
			'use_featured_image'    => __( 'Use as featured image', '4web-addons' ),
			'insert_into_item'      => __( 'Insert into item', '4web-addons' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', '4web-addons' ),
			'items_list'            => __( 'Items list', '4web-addons' ),
			'items_list_navigation' => __( 'Items list navigation', '4web-addons' ),
			'filter_items_list'     => __( 'Filter items list', '4web-addons' ),
		);
		$rewrite = array(
			'slug'       => 'recipe-video',
		);
		$args    = array(
			'label'               => __( 'Recipes Video', '4web-addons' ),
			'description'         => __( 'Post Type Description', '4web-addons' ),
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
			'name'                       => _x( 'Categories', 'Taxonomy General Name', '4web-addons' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', '4web-addons' ),
			'menu_name'                  => __( 'Categories', '4web-addons' ),
			'all_items'                  => __( 'All Items', '4web-addons' ),
			'parent_item'                => __( 'Parent Item', '4web-addons' ),
			'parent_item_colon'          => __( 'Parent Item:', '4web-addons' ),
			'new_item_name'              => __( 'New Item Name', '4web-addons' ),
			'add_new_item'               => __( 'Add New Item', '4web-addons' ),
			'edit_item'                  => __( 'Edit Item', '4web-addons' ),
			'update_item'                => __( 'Update Item', '4web-addons' ),
			'view_item'                  => __( 'View Item', '4web-addons' ),
			'separate_items_with_commas' => __( 'Separate items with commas', '4web-addons' ),
			'add_or_remove_items'        => __( 'Add or remove items', '4web-addons' ),
			'choose_from_most_used'      => __( 'Choose from the most used', '4web-addons' ),
			'popular_items'              => __( 'Popular Items', '4web-addons' ),
			'search_items'               => __( 'Search Items', '4web-addons' ),
			'not_found'                  => __( 'Not Found', '4web-addons' ),
			'no_terms'                   => __( 'No items', '4web-addons' ),
			'items_list'                 => __( 'Items list', '4web-addons' ),
			'items_list_navigation'      => __( 'Items list navigation', '4web-addons' ),
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
			'rewrite'           => false, //$rewrite,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'recipe-cat', array( 'recipe' ), $args );

	}
	add_action( 'init', 'hch_custom_taxonomy_recipes', 0 );
}

if ( ! function_exists( 'hch_custom_taxonomy_product' ) ) {

	// Register Product Ingredient
	function hch_custom_taxonomy_product() {
		$labels  = array(
			'name'                       => _x( 'Ingredient', 'Taxonomy General Name', '4web-addons' ),
			'singular_name'              => _x( 'Ingredient', 'Taxonomy Singular Name', '4web-addons' ),
			'menu_name'                  => __( 'Ingredient', '4web-addons' ),
			'all_items'                  => __( 'All Items', '4web-addons' ),
			'parent_item'                => __( 'Parent Item', '4web-addons' ),
			'parent_item_colon'          => __( 'Parent Item:', '4web-addons' ),
			'new_item_name'              => __( 'New Item Name', '4web-addons' ),
			'add_new_item'               => __( 'Add New Item', '4web-addons' ),
			'edit_item'                  => __( 'Edit Item', '4web-addons' ),
			'update_item'                => __( 'Update Item', '4web-addons' ),
			'view_item'                  => __( 'View Item', '4web-addons' ),
			'separate_items_with_commas' => __( 'Separate items with commas', '4web-addons' ),
			'add_or_remove_items'        => __( 'Add or remove items', '4web-addons' ),
			'choose_from_most_used'      => __( 'Choose from the most used', '4web-addons' ),
			'popular_items'              => __( 'Popular Items', '4web-addons' ),
			'search_items'               => __( 'Search Items', '4web-addons' ),
			'not_found'                  => __( 'Not Found', '4web-addons' ),
			'no_terms'                   => __( 'No items', '4web-addons' ),
			'items_list'                 => __( 'Items list', '4web-addons' ),
			'items_list_navigation'      => __( 'Items list navigation', '4web-addons' ),
		);
		$rewrite = array(
			'slug'         => 'product-ingredient',
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
		register_taxonomy( 'product-ingredient', array( 'product' ), $args );

		// Register Product Brand
		
		$labels_brand  = array(
			'name'                       => _x( 'Brand', 'Taxonomy General Name', '4web-addons' ),
			'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', '4web-addons' ),
			'menu_name'                  => __( 'Brand', '4web-addons' ),
			'all_items'                  => __( 'All Items', '4web-addons' ),
			'parent_item'                => __( 'Parent Item', '4web-addons' ),
			'parent_item_colon'          => __( 'Parent Item:', '4web-addons' ),
			'new_item_name'              => __( 'New Item Name', '4web-addons' ),
			'add_new_item'               => __( 'Add New Item', '4web-addons' ),
			'edit_item'                  => __( 'Edit Item', '4web-addons' ),
			'update_item'                => __( 'Update Item', '4web-addons' ),
			'view_item'                  => __( 'View Item', '4web-addons' ),
			'separate_items_with_commas' => __( 'Separate items with commas', '4web-addons' ),
			'add_or_remove_items'        => __( 'Add or remove items', '4web-addons' ),
			'choose_from_most_used'      => __( 'Choose from the most used', '4web-addons' ),
			'popular_items'              => __( 'Popular Items', '4web-addons' ),
			'search_items'               => __( 'Search Items', '4web-addons' ),
			'not_found'                  => __( 'Not Found', '4web-addons' ),
			'no_terms'                   => __( 'No items', '4web-addons' ),
			'items_list'                 => __( 'Items list', '4web-addons' ),
			'items_list_navigation'      => __( 'Items list navigation', '4web-addons' ),
		);
		$rewrite_brand = array(
			'slug'         => 'product-brand',
			'hierarchical' => true,
		);
		$args_brand    = array(
			'labels'            => $labels_brand,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'rewrite'           => $rewrite_brand,
			'show_in_rest'      => true,
		);
		//register_taxonomy( 'product-brand', array( 'product' ), $args_brand );

	}
	add_action( 'init', 'hch_custom_taxonomy_product', 0 );
}

// Rewrite URL Category
add_filter('term_link', 'hch_post_category_link', 999 , 3);
function hch_post_category_link( $link, $term_id , $taxonomy ){
	 if($taxonomy == 'recipe-cat'){
		 $term = get_term( $term_id, 'recipe-cat' );
		 return home_url( 'recipe/?cat='.$term->slug );
	 }
	 return $link;
}

add_action('pre_get_posts' , 'hch_custom_query_recipe_categories', 999);
function hch_custom_query_recipe_categories( $query ){
	if ( ! is_admin() && $query->is_main_query()
	    && isset($_GET['cat']) && $_GET['cat'] != ''
	){
		$cat_name = $_GET['cat'];
		$query->query_vars['taxonomy'] = 'recipe-cat';
		$query->query_vars['term'] = $cat_name;
		$query->set('tax_query',
				 array(
						 'taxonomy' => 'recipe-cat',
						 'field' => 'slug',
						 'terms' => array(
								 $cat_name,
						 ),
						 'operator' => 'IN'
				 )
		);
	}
}

add_action( 'init', 'hch_rewrites_init', 99999 );
function hch_rewrites_init(){
		$taxonomy_slug = 'recipe-cat';
		$terms = get_categories(array('hide_empty' => false , 'taxonomy' => $taxonomy_slug));
    foreach ( $terms as $term ) {
			add_rewrite_rule(
		      '^recipe/?cat='.$term->slug.'$',
		      'index.php?taxonomy=recipe-cat&term='.$term->slug,
		      'top' );
		}
    flush_rewrite_rules();
}