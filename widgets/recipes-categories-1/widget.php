<?php
namespace HuynhCongHieuAddons\Widgets\Recipes_Categories_1;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Recipes_Categories_1 extends Widget_Base {

    public function get_name() {
			return 'recipes-categories-1';
    }

    public function get_title() {
			return __( 'Recipes Categories 1', 'hch-addons' );
    }

    public function get_icon() {
			return 'eicon-archive';
    }

    public function get_categories() {
			return [ 'hch-addons' ];
    }

    public function get_script_depends() {
			return [ 'hch-addons' ];
		}

		/**
    * Get Custom Post Type Taxonomies
    * @return array
    */
		protected function be_get_taxonomies($taxonomy) {
			$options = array();
			$orderby      = 'name';  
			$show_count   = 0;      
			$pad_counts   = 0;      
			$hierarchical = 1;      
			$title        = '';  
			$empty        = 0;
		  
			$args = array(
				   'taxonomy'     => $taxonomy,
				   'orderby'      => $orderby,
				   'show_count'   => $show_count,
				   'pad_counts'   => $pad_counts,
				   'hierarchical' => $hierarchical,
				   'title_li'     => $title,
				   'hide_empty'   => $empty
			);
		   	$all_categories = get_categories( $args );
			if(!empty($all_categories) && !is_wp_error($all_categories)) {
				foreach ($all_categories as $cat) {
					$options[$cat->term_id] = $cat->name;
				}
			}
			return $options;
		}

	
		protected function register_controls() {

		
			$this->start_controls_section(
				'be_section_post__filters',
				[
					'label' => esc_html__('Content', 'hch-addons'),
				]
			);
		
			$this->add_control( 'cat_feature_recipes',
				[
					'label' => esc_html__( 'Select the categories of featured recipes.', 'hch-addons' ),
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $this->be_get_taxonomies('recipes_taxonomy'),
					'description' => 'Select Category(s)',
					'default' => '',
					'label_block' => true,
				]
			);


			$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$cat_feature_setting = $settings['cat_feature_recipes'];
		if(empty($cat_feature_setting)) {
			$cat_feature = array();
			$cat_not_feature = array();
			$args = array(
				   'taxonomy'     => 'recipes_taxonomy',
				   'orderby'      => 'name',
				   'hide_empty'   => 0
			);
		   	$categories_feature = get_categories( $args );
			if(!empty($categories_feature) && !is_wp_error($categories_feature)) {
				foreach ($categories_feature as $key=>$cat) {
					if($key<=3) {
						array_push($cat_feature, $cat->term_id);
					}else{
						array_push($cat_not_feature, $cat->term_id);
					}
				}
			}
			?>
				<div class="wrapper-recipes-categories">
					<div class="recipes-cat-feature">
						<?php 
							if(!empty($cat_feature)) {
								foreach ($cat_feature as $cat_f) {
									$thumbnail = get_field('image_background','recipes_taxonomy'. '_'.$cat_f);
									?>
										<a href="<?php echo get_term_link(intval($cat_f))?>" class="item-recipes-cat" style="background-image:url(<?php echo $thumbnail?>)">
											<div class="overlay">

											</div>
											<span class="title-cat">
												<?php 
													$term_name = get_term( $cat_f )->name;
													echo $term_name;
												?>
											</span>
										</a>
									<?php
								}
							}
						?>
					</div>
					<div class="recipes-cat-not-feature">
						<?php 
							if(!empty($cat_not_feature)) {
								foreach ($cat_not_feature as $cat_f) {
									$thumbnail = get_field('image_background','recipes_taxonomy'. '_'.$cat_f);
									?>
										<a href="<?php echo get_term_link(intval($cat_f))?>" class="item-recipes-cat" style="background-image:url(<?php echo $thumbnail?>)">
											<div class="overlay">

											</div>
											<span class="title-cat">
												<?php 
													$term_name = get_term( $cat_f )->name;
													echo $term_name;
												?>
											</span>
										</a>
									<?php
								}
							}
						?>
					</div>
				</div>
			<?php
		}else{
			$args = array(
				'taxonomy'     => 'recipes_taxonomy',
				'orderby'      => 'name',
				'hide_empty'   => 0,
				'exclude' => $cat_feature_setting 
		 	);
			$recipes_cat_not_feature = get_categories( $args );
			?>
			<div class="wrapper-recipes-categories">
				<div class="recipes-cat-feature">
					<?php 
						if(!empty($cat_feature_setting)) {
							foreach ($cat_feature_setting as $id) {
								$id_cat = intval($id);
								$thumbnail = get_field('image_background','recipes_taxonomy'. '_'.$id_cat);
								?>
									<a href="<?php echo get_term_link($id_cat)?>" class="item-recipes-cat" style="background-image:url(<?php echo $thumbnail?>)">
										<div class="overlay">
										</div>
										<span class="title-cat">
											<?php 
												$term_name = get_term( $id_cat )->name;
												echo $term_name;
											?>
										</span>
									</a>
								<?php
							}
						}
					?>
				</div>	
				<div class="recipes-cat-not-feature">
					<?php 
						if(!empty($recipes_cat_not_feature)) {
							foreach ($recipes_cat_not_feature as $cat) {
								$thumbnail = get_field('image_background','recipes_taxonomy'. '_'.$cat->term_id);
								?>
									<a href="<?php echo get_term_link(intval($cat->term_id))?>" class="item-recipes-cat" style="background-image:url(<?php echo $thumbnail?>)">
										<div class="overlay">
										</div>
										<span class="title-cat">
											<?php 
												echo $cat->name;
											?>
										</span>
									</a>
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