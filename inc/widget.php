<?php 


add_action( 'widgets_init', 'create_popular_filter_widget' );
function create_popular_filter_widget() {
	register_widget('Popular_filter_Widget');
}


class Popular_filter_Widget extends WP_Widget {
	function __construct() {
        parent::__construct (
            'popular_filter_widget',
            'Popular Filter Widget',
            array(
                'description' => 'Create popular filter widget'
            )
        );

	}


	function form( $instance ) {
		$default = array(
			'title' => 'Title Widget'
		);
		$instance = wp_parse_args( (array) $instance, $default);
		$title = esc_attr( $instance['title'] );
		?>
		<div class="wrapper-title-widget">
			<label>Title:</label>
			<input class="widefat" type="text" name="<?php echo $this->get_field_name('title')?>" value="<?php echo $title?>" />
		</div>
		<?php
	}


	function update( $new_instance, $old_instance ) {

		parent::update( $new_instance, $old_instance );
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;

	}

	function widget( $args, $instance ) {
		$categories = get_field('cat_filter_popular','options');
		$price = get_field('price_filter_popular','options');
		$product_status = get_field('product_status_filter_popular','options');
		$brands = get_field('brands_filter_popular','options');
		$ingredient = get_field('ingredient_filter_popular','options');
		$item_show = [];
		$max_item = [
			'categories'=>0,
			'price_range'=>0,
			'product_status'=>0,
			'brands_attribute'=>0,
			'ingredient'=>0
		];
		if(!empty($ingredient)) {
			$item_ingre_max = get_highest($ingredient,'count_ingre_filter');
			$max_item ['ingredient'] = $item_ingre_max['count_ingre_filter'];
			$item_ingre_max['filter_type'] = 'ingredient';
			// $item_cat_max += ['filter_type' => 'categories'];
		}

		if(!empty($categories)) {
			$item_cat_max = get_highest($categories,'count_popular_filter');
			$max_item ['categories'] = $item_cat_max['count_popular_filter'];
			$item_cat_max['filter_type'] = 'categories';
			// $item_cat_max += ['filter_type' => 'categories'];
		}
		if(!empty($price)) {
			$item_price_max = get_highest($price,'count_min_max_filter');
			$max_item ['price_range'] = $item_price_max['count_min_max_filter'];
			$item_price_max['filter_type'] = 'range_price';
		}
		if(!empty($product_status)) {
			$item_product_status_max = get_highest($product_status,'count_product_status_filter');
			$max_item ['product_status'] = $item_product_status_max['count_product_status_filter'];
			$item_product_status_max['filter_type'] = 'product_status';
		}
		if(!empty($brands)) {
			$item_brands_max = get_highest($brands,'count_brand_filter');
			$max_item ['brands_attribute'] = $item_brands_max['count_brand_filter'];
			$item_brands_max['filter_type'] = 'attribute-product';
		}
		uasort($max_item, 'cmp');
		$key_item_show = array_slice($max_item, 0, 3);
		foreach ($key_item_show as $key => $value) {
			if($key=='ingredient') {
				if(!empty($item_ingre_max)) {
					array_push($item_show,$item_ingre_max);
				}
			}
			if($key=='categories') {
				if(!empty($item_cat_max)) {
					array_push($item_show,$item_cat_max);
				}
			}
			if($key=='price_range') {
				if(!empty($item_price_max)) {
					array_push($item_show,$item_price_max);
				}
			}
			if($key=='product_status') {
				if(!empty($item_product_status_max)) {
					array_push($item_show,$item_product_status_max);
				}
			}
			if($key=='brands_attribute') {
				if(!empty($item_brands_max)) {
					array_push($item_show,$item_brands_max);
				}
			}
		}
		if(!empty($item_show)) {
		?>
		<div class="widget widget-be-popular-filter active-dropdown-filter">
			<h4 class="widget-title">
				<?php 
					echo $instance['title'];
				?>
			</h4>
			<div class="widget-body-popular-filter">
				<?php 
					foreach ($item_show as $key => $item) {
						if($item['filter_type']=='categories') {
							?>
							<div data-count="<?php echo $item['count_popular_filter']?>" class="<?php echo $item['filter_type']?> count">
								<input type="radio" id="categories_pp" name="filter_pp_wg" value="<?php echo $item['slug_cat_filter']?>">	
  								<label for="categories_pp">
									<?php echo $item['name_cat_filter']?>
									<span>(<?php echo $item['count_popular_filter']?>)</span>
								</label>
							</div>
							<?php
						}

						if($item['filter_type']=='ingredient') {
							?>
							<div data-count="<?php echo $item['count_ingre_filter']?>" class="<?php echo $item['filter_type']?> count">
								<input type="radio" id="ingredien_pp" name="filter_pp_wg" value="<?php echo $item['slug_ingre_filter']?>">	
  								<label for="ingredien_pp">
									<?php echo $item['name_ingre_filter']?>
									<span>(<?php echo $item['count_ingre_filter']?>)</span>
								</label>
							</div>
							<?php
						}
						if($item['filter_type']=='attribute-product') {
							?>
							<div data-count="<?php echo $item['count_brand_filter']?>" class="<?php echo $item['filter_type']?> count">
								<input data-tax="<?php echo $item['slug_tax_attribute']?>" type="radio" id="attribute_pp" name="filter_pp_wg" value="<?php echo $item['slug_brand_filter']?>">	
  								<label for="attribute_pp">
									<?php echo $item['name_brand_filter']?>
									<span>(<?php echo $item['count_brand_filter']?>)</span>
								</label>
							</div>
							<?php
						}

						if($item['filter_type']=='range_price') {
							$min_max_price = explode('-',$item['price_filter_min_max']);
							if(intval($min_max_price[0])<= intval($min_max_price[1]) ) {
								?>
								<div data-count="<?php echo $item['count_min_max_filter']?>" class="<?php echo $item['filter_type']?> count">
									<input type="radio" id="price_range_pp" name="filter_pp_wg" value="<?php echo $item['price_filter_min_max']?>">	
	  								<label for="price_range_pp">
										<?php echo $item['price_filter_min_max']?>
										<span>€ (<?php echo $item['count_min_max_filter']?>)</span>
									</label>
								</div>
								<?php
							}
						}
						if($item['filter_type']=='product_status') {
							?>
							<div data-count="<?php echo $item['count_product_status_filter']?>" class="<?php echo $item['filter_type']?> count" >
								<input type="radio" id="product_status_pp" name="filter_pp_wg" value="<?php echo $item['slug_product_status_filter']?>">	
  								<label for="product_status_pp">
									<?php 
										if($item['slug_product_status_filter']=='instock') {
											echo 'In Stock';
										}
										if($item['slug_product_status_filter']=='onsale') {
											echo 'On Sale';
										}		
									?>
									<span>(<?php echo $item['count_product_status_filter']?>)</span>
								</label>
							</div>
							<?php
						}
					}
				?>
			</div>
		</div>
		<?php
		}
	}
}


class hch_widget_hch_product_ingredient extends WP_Widget { 
	
	// Widget Settings
	function __construct() {
		$widget_ops = array('description' => esc_html__('For Main Shop Page.','4web-addons') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'klb_product_ingredient' );
		 parent::__construct( 'klb_product_ingredient', esc_html__('Bacola Product Ingredient','4web-addons'), $widget_ops, $control_ops );
	}

	// Widget Output
	function widget($args, $instance) {


		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$exclude = $instance['exclude'];

		echo $before_widget;

		if($title) {
			echo $before_title . $title . $after_title;
		}


		if($exclude == 'all'){
			$terms = get_terms( array(
				'taxonomy' => 'product-ingredient',
				'hide_empty' => false,
				'parent'    => 0,
			) );
		} else {
			$str = $exclude;
			$arr = explode(',', $str);
			$terms = get_terms( array(
				'taxonomy' => 'product-ingredient',
				'hide_empty' => false,
				'parent'    => 0,
				'exclude'   => $arr,
			) );
		}
		
		wp_enqueue_script( 'klb-widget-product-categories');
		wp_enqueue_style( 'klb-widget-product-categories');
		
		echo '<div class="widget-body site-checkbox-lists">';
		echo '<div class="site-scroll">';
		echo '<ul>';

		foreach ( $terms as $term ) {
			$term_children = get_term_children( $term->term_id, 'product-ingredient' );
			$checkbox = '';
			if(isset($_GET['filter_ingredient'])){
				if(in_array($term->term_id, explode(',',$_GET['filter_ingredient']))){
					$checkbox = 'checked';
				}
			}
			echo '<li>';
			echo '<a href="'.esc_url(bacola_get_ingredient_url($term->term_id)).'" class="product_cat">';
			echo '<input name="product_cat[]" value="'.esc_attr($term->term_id).'" id="'.esc_attr($term->name).'" type="checkbox" '.esc_attr($checkbox).'>';
			echo '<label ><span></span>'.esc_html($term->name).'</label>';
			echo '</a>';
				if($term_children){
					echo '<ul class="children">';
					
					foreach($term_children as $child){
						$childterm = get_term_by( 'id', $child, 'product-ingredient' );
						$ancestor = get_ancestors( $childterm->term_id, 'product-ingredient' );
						
						$term_third_children = get_term_children( $childterm->term_id, 'product-ingredient' );

						$childcheckbox = '';
						if(isset($_GET['filter_ingredient'])){
							if(in_array($childterm->term_id, explode(',',$_GET['filter_ingredient']))){ 
								$childcheckbox .= 'checked';
							}
						} 
						
						if($childterm->parent && (sizeof($term_third_children)>0)){
							echo '<li>';
							echo '<a href="'.esc_url( bacola_get_ingredient_url($childterm->term_id)).'">';
							echo '<input name="product_cat[]" value="'.esc_attr($childterm->term_id).'" id="'.esc_attr($childterm->name).'" type="checkbox" '.esc_attr($childcheckbox).'>';
							echo '<label><span></span>'.esc_html($childterm->name).'</label>';
							echo '</a>';
							if($term_third_children){
								
								echo '<ul class="children">';
								foreach($term_third_children as $third_child){
									$thirdchildterm = get_term_by( 'id', $third_child, 'product-ingredient' );
									$thirdchildthumbnail_id = get_term_meta( $thirdchildterm->term_id, 'thumbnail_id', true );
									$thirdchildimage = wp_get_attachment_url( $thirdchildthumbnail_id );
									
									$thirdchildcheckbox = '';
									if(isset($_GET['filter_ingredient'])){
										if(in_array($thirdchildterm->term_id, explode(',',$_GET['filter_ingredient']))){ 
											$thirdchildcheckbox .= 'checked';
										}
									} 
									
									echo '<li>';
									echo '<a href="'.esc_url( bacola_get_ingredient_url($thirdchildterm->term_id)).'">';
									echo '<input name="product_cat[]" value="'.esc_attr($thirdchildterm->term_id).'" id="'.esc_attr($thirdchildterm->name).'" type="checkbox" '.esc_attr($thirdchildcheckbox).'>';
									echo '<label><span></span>'.esc_html($thirdchildterm->name).'</label>';
									echo '</a>';
									echo '</li>';
								}
								echo '</ul>';
							}
							
							echo '</li>';
						} elseif (sizeof($ancestor) == 1) {
							echo '<li>';
							echo '<a href="'.esc_url( bacola_get_ingredient_url($childterm->term_id)).'">';
							echo '<input name="product_cat[]" value="'.esc_attr($childterm->term_id).'" id="'.esc_attr($childterm->name).'" type="checkbox" '.esc_attr($childcheckbox).'>';
							echo '<label><span></span>'.esc_html($childterm->name).'</label>';
							echo '</a>';
							echo '</li>';
						}
				
					}
					echo '</ul>';
				} 
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
		echo '</div>';


		echo $after_widget;
	}
	
	// Update
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['exclude'] = strip_tags($new_instance['exclude']);
		
		return $instance;
	}
	
	// Backend Form
	function form($instance) {
		
		$defaults = array('title' => 'Product Ingredient', 'exclude' => 'All');
		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','4web-addons'); ?></label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php esc_html_e('Exclude id:','4web-addons'); ?></label>
			<input class="widefat"  id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $instance['exclude']; ?>" />
		</p>

	<?php
	}
}

// Add Widget
function hch_widget_hch_product_ingredient_init() {
	register_widget('hch_widget_hch_product_ingredient');
}
add_action('widgets_init', 'hch_widget_hch_product_ingredient_init');



?>