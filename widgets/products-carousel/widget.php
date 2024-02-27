<?php
namespace HuynhCongHieuAddons\Widgets\Products_Carousel;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Products_Carousel extends Widget_Base {

    public function get_name() {
			return 'products-carousel-2';
    }

    public function get_title() {
			return __( 'Products Carousel 2', 'hch-addons' );
    }

    public function get_icon() {
			return 'eicon-slider-push';
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
		protected function be_cpt_taxonomies($post_type, $value='id') {
			$options = array();
			$terms = get_terms( $post_type );
			if (!empty($terms) && !is_wp_error($terms)) {
				foreach ($terms as $term) {
					if ('name' == $value) {
							$options[$term->name] = $term->name;
					} else {
							$options[$term->term_id] = $term->name;
					}
				}
			}
			return $options;
		}
	
		/**
		 * Get Custom Post Type Title
		 */
		protected function be_cpt_get_post_title($cpt_name='') {
			if ( $cpt_name ) {
				$list = get_posts( array(
						'post_type'         => $cpt_name,
						'posts_per_page'    => 100,
				) );
				$options = array();
				if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
					foreach ( $list as $post ) {
							$options[ $post->ID ] = $post->post_title;
					}
				}
				return $options;
			}
		}

		/**
		* Elementor Render Products
		*/
		protected function be_elementor_render_products($settings) {
			$output = '';
			
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

			if($settings['show_best_selling_filter']== 'true'){
				$args['meta_key'] = 'total_sales';
				$args['orderby'] = 'meta_value_num';
			} else {
				if($settings['show_on_sale_filter'] == 'true'){
					$args['meta_key'] = '_sale_price';
					$args['meta_value'] = array('');
					$args['meta_compare'] = 'NOT IN';
				} else {
					if($settings['show_featured_filter'] == 'true'){
						$args['tax_query'] = array( array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => array( 'featured' ),
								'operator' => 'IN',
						) );
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

					$output .= '<div class="slide-item"><div class="'.esc_attr( implode( ' ', wc_get_product_class( '', $product->get_id()))).'">';
					if($settings['product_type'] == 'type4'){
						$output .= bacola_product_type4();
					}elseif($settings['product_type'] == 'type2'){
						$output .= bacola_product_type2();
					} else {
						$output .= bacola_product_type1();
					}
					$output .= '</div></div>';
				
				endwhile;
			}
			wp_reset_postdata();
			
			return $output;
		}

		/**
		 * 
		 */
		protected function register_controls() {

			$this->start_controls_section( 'content_section',
				[
					'label' => esc_html__( 'Content', 'hch-addons' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
		
			$this->add_control( 'title',
				[
					'label' => esc_html__( 'Title', 'hch-addons' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'Title Text',
					'label_block' => true,
				]
			);
	
			$this->add_control( 'subtitle',
				[
					'label' => esc_html__( 'Subtitle', 'hch-addons' ),
					'type' => Controls_Manager::TEXTAREA,
					'default' => 'Subtitle Text.',
					'label_block' => true,
				]
			);

			$this->add_control( 'auto_play',
				[
					'label' => esc_html__( 'Auto Play', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'True', 'hch-addons' ),
					'label_off' => esc_html__( 'False', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);
		
			$this->add_control( 'auto_speed',
				[
					'label' => esc_html__( 'Auto Speed', 'hch-addons' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => '1600',
					'condition' => ['auto_play' => 'true']
				]
			);
		
			$this->add_control( 'dots',
				[
					'label' => esc_html__( 'Dots', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'True', 'hch-addons' ),
					'label_off' => esc_html__( 'False', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);
			
			$this->add_control( 'arrows',
				[
					'label' => esc_html__( 'Arrows', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'True', 'hch-addons' ),
					'label_off' => esc_html__( 'False', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_control( 'slide_speed',
				[
					'label' => esc_html__( 'Slide Speed', 'hch-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '1200',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'be_section_tabs__filters',
				[
					'label' => esc_html__('Filters', 'hch-addons'),
				]
			);

			$this->add_control( 'show_best_selling_filter',
				[
					'label' => esc_html__( 'Best Selling', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'hch-addons' ),
					'label_off' => esc_html__( 'Hide', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control( 'best_selling_filter_title',
				[
					'label' => esc_html__( 'Best Selling Title', 'hch-addons' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'TOP IZDELKI',
					// 'label_block' => true,
				]
			);

			$this->add_control( 'show_on_sale_filter',
				[
					'label' => esc_html__( 'On Sale', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'hch-addons' ),
					'label_off' => esc_html__( 'Hide', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control( 'on_sale_filter_title',
				[
					'label' => esc_html__( 'On Sale Title', 'hch-addons' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'V AKCIJI',
					// 'label_block' => true,
				]
			);
			
			$this->add_control( 'show_featured_filter',
				[
					'label' => esc_html__( 'Featured', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'hch-addons' ),
					'label_off' => esc_html__( 'Hide', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control( 'featured_filter_title',
				[
					'label' => esc_html__( 'Featured Title', 'hch-addons' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'NOVOSTI',
					// 'label_block' => true,
				]
			);

			$this->end_controls_section();
			
		
			$this->start_controls_section(
				'be_section_post__filters',
				[
					'label' => esc_html__('Query', 'hch-addons'),
				]
			);
		
			$this->add_control( 'product_type',
				[
					'label' => esc_html__( 'Product Type', 'hch-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'type2',
					'options' => [
						'select-type' => esc_html__( 'Select Type', 'hch-addons' ),
						'type2'	  => esc_html__( 'Type 2', 'hch-addons' ),
						'type4'	  => esc_html__( 'Type 4', 'hch-addons' ),
					],
				]
			);
		
			$this->add_control( 'column',
				[
					'label' => esc_html__( 'Desktop Column', 'hch-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 4,
					'options' => [
						'0' => esc_html__( 'Select Column', 'hch-addons' ),
						'2' 	  => esc_html__( '2 Columns', 'hch-addons' ),
						'3'		  => esc_html__( '3 Columns', 'hch-addons' ),
						'4'		  => esc_html__( '4 Columns', 'hch-addons' ),
						'5'		  => esc_html__( '5 Columns', 'hch-addons' ),
						'6'		  => esc_html__( '6 Columns', 'hch-addons' ),
					],
				]
			);
		
			$this->add_control( 'mobile_column',
				[
					'label' => esc_html__( 'Mobile Column', 'hch-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => '2',
					'options' => [
						'0' => esc_html__( 'Select Column', 'hch-addons' ),
						'1' 	  => esc_html__( '1 Column', 'hch-addons' ),
						'2'		  => esc_html__( '2 Columns', 'hch-addons' ),
					],
				]
			);

			// Posts Per Page
			$this->add_control( 'post_count',
				[
					'label' => esc_html__( 'Posts Per Page', 'hch-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => count( get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) ),
					'default' => 8
				]
			);
		
			$this->add_control( 'cat_filter',
				[
					'label' => esc_html__( 'Filter Category', 'hch-addons' ),
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $this->be_cpt_taxonomies('product_cat'),
					'description' => 'Select Category(s)',
					'default' => '',
					'label_block' => true,
				]
			);

			$this->add_control( 'tag_filter',
				[
					'label' => esc_html__( 'Filter Tag', 'hch-addons' ),
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $this->be_cpt_taxonomies('product_tag'),
					'description' => 'Select Tag(s)',
					'default' => '',
					'label_block' => true,
				]
			);

			$this->add_control( 'post_include_filter',
				[
					'label' => esc_html__( 'Include Products', 'hch-addons' ),
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $this->be_cpt_get_post_title('product'),
					'description' => 'Select Product(s) to Include',
					'label_block' => true,
				]
			);

			$this->add_control( 'order',
				[
						'label' => esc_html__( 'Select Order', 'hch-addons' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
								'ASC' => esc_html__( 'Ascending', 'hch-addons' ),
								'DESC' => esc_html__( 'Descending', 'hch-addons' )
						],
						'default' => 'DESC'
				]
			);

			$this->add_control( 'orderby',
				[
						'label' => esc_html__( 'Order By', 'hch-addons' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
								'id' => esc_html__( 'Post ID', 'hch-addons' ),
								'menu_order' => esc_html__( 'Menu Order', 'hch-addons' ),
								'rand' => esc_html__( 'Random', 'hch-addons' ),
								'date' => esc_html__( 'Date', 'hch-addons' ),
								'title' => esc_html__( 'Title', 'hch-addons' ),
						],
						'default' => 'date',
				]
			);

			$this->add_control( 'hide_out_of_stock_items',
				[
					'label' => esc_html__( 'Hide Out of Stock?', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'True', 'hch-addons' ),
					'label_off' => esc_html__( 'False', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);

			$this->end_controls_section();
		

			$this->start_controls_section('be_styling',
				[
					'label' => esc_html__( ' Style', 'hch-addons' ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);
		
			$this->add_control( 'title_heading',
				[
					'label' => esc_html__( 'TITLE', 'hch-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before'
				]
			);
			
			$this->add_control( 'title_color',
				[
					'label' => esc_html__( 'Title Color', 'hch-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column .entry-title' => 'color: {{VALUE}};']
				]
			);
		
			$this->add_control( 'title_hvrcolor',
				[
						'label' => esc_html__( 'Title Hover Color', 'hch-addons' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => ['{{WRAPPER}} .column .entry-title:hover' => 'color: {{VALUE}};']
				]
			);
		
			$this->add_control( 'title_size',
				[
					'label' => esc_html__( 'Size', 'hch-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'default' => '',
					'selectors' => [ '{{WRAPPER}} .column .entry-title' => 'font-size: {{SIZE}}px;' ],
				]
			);
		
			$this->add_responsive_control( 'title_left',
				[
					'label' => esc_html__( 'Left', 'hch-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vh' ],
					'range' => [
							'px' => [
									'min' => 0,
									'max' => 1000
							],
							'vh' => [
									'min' => 0,
									'max' => 100
							]
					],
					'selectors' => [
							'{{WRAPPER}} .column .entry-title' => 'padding-left: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_responsive_control( 'title_top',
				[
					'label' => esc_html__( 'Top', 'hch-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vh' ],
					'range' => [
							'px' => [
									'min' => 0,
									'max' => 1000
							],
							'vh' => [
									'min' => 0,
									'max' => 100
							]
					],
					'selectors' => [
							'{{WRAPPER}} .column .entry-title' => 'padding-top: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_control( 'title_opacity_important_style',
				[
					'label' => esc_html__( 'Opacity', 'hch-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 1,
					'step' => 0.1,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column .entry-title' => 'opacity: {{VALUE}} ;'],
				]
			);
		
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typo',
					'label' => esc_html__( 'Typography', 'hch-addons' ),
					'scheme' => Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .column .entry-title'
				]
			);
		
			$this->add_control( 'subtitle_heading',
				[
					'label' => esc_html__( 'SUBTITLE', 'hch-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before'
				]
			);
		
			$this->add_control( 'subtitle_color',
				[
					'label' => esc_html__( 'Subtitle Color', 'hch-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column .entry-description' => 'color: {{VALUE}};']
				]
			);
		
			$this->add_control( 'subtitle_hvrcolor',
				[
					'label' => esc_html__( 'Subtitle Hover Color', 'hch-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column .entry-description:hover' => 'color: {{VALUE}};']
				]
			);
		
			$this->add_control( 'subtitle_size',
				[
					'label' => esc_html__( 'Subtitle Size', 'hch-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'default' => '',
					'selectors' => [ '{{WRAPPER}} .column .entry-description' => 'font-size: {{SIZE}}px;' ],
				]
			);
		
			$this->add_responsive_control( 'subtitle_left',
				[
					'label' => esc_html__( 'Left', 'hch-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vh' ],
					'range' => [
							'px' => [
									'min' => 0,
									'max' => 1000
							],
							'vh' => [
									'min' => 0,
									'max' => 100
							]
					],
					'selectors' => [
							'{{WRAPPER}} .column .entry-description' => 'padding-left: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_responsive_control( 'subtitle_top',
				[
					'label' => esc_html__( 'Top', 'hch-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vh' ],
					'range' => [
							'px' => [
									'min' => 0,
									'max' => 1000
							],
							'vh' => [
									'min' => 0,
									'max' => 100
							]
					],
					'selectors' => [
							'{{WRAPPER}} .column .entry-description' => 'padding-top: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_control( 'subtitle_opacity_important_style',
				[
					'label' => esc_html__( 'Opacity', 'hch-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 1,
					'step' => 0.1,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column .entry-description' => 'opacity: {{VALUE}} ;'],
				]
			);
		
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'subtitle_typo',
					'label' => esc_html__( 'Typography', 'hch-addons' ),
					'scheme' => Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .column .entry-description'
				]
			);
		
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$products_html = $this->be_elementor_render_products($settings);
		$more_products_url = "/shop-2/";
		
		$output = '<div class="site-module module-carousel">';

		if($settings['title'] || $settings['subtitle']){
			$output .= '<div class="module-header">';
			$output .= '<div class="column">';
			$output .= '<h4 class="entry-title">'.esc_html($settings['title']).'</h4>';
			$output .= '<div class="entry-description">'.esc_html($settings['subtitle']).'</div>';
			$output .= '</div>';
			$output .= '</div>';
		}

		if($settings['show_on_sale_filter'] || $settings['show_featured_filter'] || $settings['show_best_selling_filter']){
				$is_active_tab = false;
				
				$output .= '<div class="be-filter be-products-filter" data-settings=\''.json_encode($settings).'\'">';
				if ( $settings['show_best_selling_filter'] ) {
					$active_cl = '';
					if ( ! $is_active_tab ) {
						$more_products_url = "shop-2/?orderby=popularity";
						$active_cl = 'active';
						$is_active_tab = true;
					}
					$output .= '<div class="filter-tab '.$active_cl.'" data-filter-by="best_selling">'.esc_html($settings['best_selling_filter_title']).'</div>';
				}
				if ( $settings['show_on_sale_filter'] ) {
					$active_cl = '';
					if ( ! $is_active_tab ) {
						$more_products_url = "/shop-2/?on_sale=onsale";
						$active_cl = 'active';
						$is_active_tab = true;
					}
					$output .= '<div class="filter-tab '.$active_cl.'" data-filter-by="on_sale">'.esc_html($settings['on_sale_filter_title']).'</div>';
				}
				if ( $settings['show_featured_filter'] ) {
					$active_cl = '';
					if ( ! $is_active_tab ) {
						$more_products_url = "/shop-2/?orderby=rating";
						$active_cl = 'active';
						$is_active_tab = true;
					}
					$output .= '<div class="filter-tab '.$active_cl.'" data-filter-by="featured">'.esc_html($settings['featured_filter_title']).'</div>';
				}
				$output .= '</div>';
		}

		$output .= '<div class="be-products-wrapper">';
		$output .= '<svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
		$output .= '<div class="be-products-ls">';
		if ( $products_html ) {
			$output .= '<div class="module-body">';
			$output .= '<div class="slider-wrapper">';
			$output .= '<div class="products be-slider" style="grid-template-columns: repeat('.$settings['column'].', 1fr);" data-mobile="'.esc_attr($settings['mobile_column']).'" data-slidespeed="'.esc_attr($settings['slide_speed']).'" data-arrows="'.esc_attr($settings['arrows']).'" data-autoplay="'.esc_attr($settings['auto_play']).'" data-autospeed="'.esc_attr($settings['auto_speed']).'" data-dots="'.esc_attr($settings['dots']).'">';
			$output .= $products_html;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="more-products"><a href="'.$more_products_url.'">'. __( "Prikaži več izdelkov", "hch-addons" ) .'</a></div>';
		} else {
			$output .= '<div class="no-product"><h3>'. __( "Ujemajočih izdelkov ni mogoče najti.", "hch-addons" ) .'</h3></div>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		echo $output;
	}

}