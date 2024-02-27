<?php
namespace HuynhCongHieuAddons\Widgets\Recipes_Carousel;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Recipes_Carousel extends Widget_Base {

    public function get_name() {
			return 'recipes-carousel';
    }

    public function get_title() {
			return __( 'Recipes Carousel', 'hch-addons' );
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
		* Elementor Render Recipes
		*/
		protected function be_elementor_render_recipes($settings) {
			$output = '';
			
			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} else {
				$paged = 1;
			}
		
			$args = array(
				'post_type'      => 'recipe',
				'post_status'    => 'publish',
				'paged' 			   => $paged,
				'posts_per_page' => $settings['post_count'],
				'post__in'       => $settings['post_include_filter'],
				'order'          => $settings['order'],
				'orderby'        => $settings['orderby']
			);
		
			$args['klb_special_query'] = true;

			if($settings['cat_filter']){
				$args['tax_query'][] = array(
					'taxonomy' 	=> 'recipe-cat',
					'field' 	=> 'term_id',
					'terms' 	=> $settings['cat_filter']
				);
			}
			
			$loop = new \WP_Query( $args );
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) : $loop->the_post();
					global $post;
          $terms = get_the_terms( $post->ID, 'recipe-cat' );

					$output .= '<div class="slide-item"><a href="'.get_permalink($post->ID).'" class="slider-inner">';
          $output .= get_the_post_thumbnail( $post->ID, 'medium' );
          if ( !empty( $terms ) ){
            $output .= '<div class="cats">' . join(', ', wp_list_pluck($terms, 'name')) . '</div>';
          }
          $output .= '<h3>'.$post->post_title.'</h3>';
          $output .= '<div class="crt-date">' . get_the_date( 'F j, Y' ) . '</div>';
					$output .= '</a></div>';
				
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

      $this->add_control( 'btn_text',
        [
            'label' => esc_html__( 'Button Text', 'hch-addons' ),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => 'Vsi recepti',
        ]
      );
  
      $this->add_control( 'btn_link',
        [
            'label' => esc_html__( 'Button Link', 'hch-addons' ),
            'type' => Controls_Manager::URL,
            'label_block' => true,
        ]
      );

      $this->add_control( 'show_categories_filter',
				[
					'label' => esc_html__( 'Categories Filter', 'hch-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'hch-addons' ),
					'label_off' => esc_html__( 'Hide', 'hch-addons' ),
					'return_value' => 'true',
					'default' => 'true',
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
				'be_section_post__filters',
				[
					'label' => esc_html__('Query', 'hch-addons'),
				]
			);
		
			$this->add_control( 'column',
				[
					'label' => esc_html__( 'Desktop Column', 'hch-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 3,
					'options' => [
						'0' => esc_html__( 'Select Column', 'hch-addons' ),
						'2' 	  => esc_html__( '2 Columns', 'hch-addons' ),
						'3'		  => esc_html__( '3 Columns', 'hch-addons' ),
						'4'		  => esc_html__( '4 Columns', 'hch-addons' ),
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
					'max' => count( get_posts( array('post_type' => 'recipe', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) ),
					'default' => 8
				]
			);
		
			$this->add_control( 'cat_filter',
				[
					'label' => esc_html__( 'Filter Category', 'hch-addons' ),
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $this->be_cpt_taxonomies('recipe-cat'),
					'description' => 'Select Category(s)',
					'default' => '',
					'label_block' => true,
				]
			);

			$this->add_control( 'post_include_filter',
				[
					'label' => esc_html__( 'Include Recipes', 'hch-addons' ),
					'type' => Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $this->be_cpt_get_post_title('recipe'),
					'description' => 'Select Recipe(s) to Include',
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
		
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();
     	
			$this->start_controls_section('btn_styling',
				[
					'label' => esc_html__( ' Button Style', 'hch-addons' ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);
		
			$this->add_responsive_control( 'btn_padding',
				[
					'label' => esc_html__( 'Padding', 'hch-addons' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => ['{{WRAPPER}}  .column a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],              
				]
			);
  	    
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'btn_typo',
					'label' => esc_html__( 'Typography', 'hch-addons' ),
					'scheme' => Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .column a '
				]
			);
		
			$this->add_responsive_control( 'btn_right',
				[
					'label' => esc_html__( 'Right', 'hch-addons' ),
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
							'{{WRAPPER}}  .column a' => 'margin-right: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_responsive_control( 'btn_top',
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
							'{{WRAPPER}}  .column a' => 'margin-top: {{SIZE}}{{UNIT}}',
					]
				]
			);
		
			$this->add_control( 'btn_opacity_important_style',
				[
					'label' => esc_html__( 'Opacity', 'hch-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 1,
					'step' => 0.1,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column a' => 'opacity: {{VALUE}} ;'],
				]
			);

			$this->start_controls_tabs('btn_tabs');
        $this->start_controls_tab( 'btn_normal_tab',
				[ 'label' => esc_html__( 'Normal', 'hch-addons' ) ]
			);
		
			$this->add_control( 'btn_color',
				[
					'label' => esc_html__( 'Color', 'hch-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#FFFFFF',
					'selectors' => ['{{WRAPPER}} .column a ' => 'color: {{VALUE}};']
				]
			);
       
	    $this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'btn_border',
					'label' => esc_html__( 'Border', 'hch-addons' ),
					'selector' => '{{WRAPPER}} .column a ',
				]
			);
        
			$this->add_responsive_control( 'btn_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'hch-addons' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => ['{{WRAPPER}} .column a ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'],
				]
			);
       
			$this->add_control( 'btn_bgclr',
				[
					'label' => esc_html__( 'Background Color', 'hch-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#252525',
					'selectors' => [
						'{{WRAPPER}} .column a' => 'background-color: {{VALUE}};'
					]
				]
			);
       
			$this->end_controls_tab();
        $this->start_controls_tab('btn_hover_tab',
					[ 'label' => esc_html__( 'Hover', 'hch-addons' ) ]
        );
       
	    $this->add_control( 'btn_hvrcolor',
				[
					'label' => esc_html__( 'Color', 'hch-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column a:hover ' => 'color: {{VALUE}};']
				]
			);
       
	    $this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'btn_hvrborder',
					'label' => esc_html__( 'Border', 'hch-addons' ),
					'selector' => '{{WRAPPER}} .column a:hover',
				]
			);
		
			$this->add_control( 'btn_hvrbgclr',
				[
					'label' => esc_html__( 'Background Color', 'hch-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .column a:hover' => 'background-color: {{VALUE}};'
					]
				]
			);
		
			$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$recipes_html = $this->be_elementor_render_recipes($settings);

    $target = $settings['btn_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['btn_link']['nofollow'] ? ' rel="nofollow"' : '';
		
		$output = '<div class="site-module module-carousel be-normal-carousel">';

		if($settings['title'] || $settings['subtitle']){
			$output .= '<div class="module-header">';
			$output .= '<div class="column">';
      $output .= '<div class="head-icon"><svg width="30" height="23" viewBox="0 0 30 23" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_7_644)">
      <path d="M14.9991 6.13648C21.5924 6.13648 26.9557 11.6002 26.9557 18.3168H28.8109C28.8109 10.8744 23.1062 4.76631 15.9193 4.2825V1.89178H18.5518V0H11.4465V1.88989H14.0623V4.2825C6.88273 4.77387 1.18921 10.8782 1.18921 18.3168H3.04438C3.04438 11.6002 8.40769 6.13648 15.001 6.13648H14.9991Z" fill="white"/>
      <path d="M30 20.1689H0V22.0588H30V20.1689Z" fill="white"/></g><defs><clipPath id="clip0_7_644"><rect width="30" height="22.0588" fill="white"/>
      </clipPath></defs></svg></div>';
			$output .= '<h4 class="entry-title">'.esc_html($settings['title']).'</h4>';
			$output .= '</div>';
      $output .= '<div class="column">';
      if($settings['btn_text']){
        $output .= '<a class="button button-info-default xsmall rounded" href="'.esc_url($settings['btn_link']['url']).'" '.esc_attr($target.$nofollow).'>'.esc_html($settings['btn_text']).' <i class="klbth-icon-right-arrow"></i></a>';
      }
      $output .= '</div>';
			$output .= '</div>';
		}

    if ( $settings['show_categories_filter'] ) {
      $all_recipe_cats = $this->be_cpt_taxonomies('recipe-cat');

      $output .= '<div class="be-filter be-recipes-filter" data-settings=\''.json_encode($settings).'\'" >';
      $output .= '<div class="filter-tab active" data-filter-by="all">'.__( 'VSI RECEPTI', 'hch-addons' ).'</div>';

      foreach ($all_recipe_cats as $key => $value) {
        $output .= '<div class="filter-tab" data-filter-by="'.$key.'">'.$value.'</div>';
      }
      $output .= '</div>';
    }

		$output .= '<div class="be-recipes-wrapper">';
		$output .= '<svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
		$output .= '<div class="be-recipes-ls">';
		if ( $recipes_html ) {
			$output .= '<div class="module-body">';
			$output .= '<div class="slider-wrapper">';
			$output .= '<div class="recipes be-normal-slider" data-slideshow="'.esc_attr($settings['column']).'" data-mobile="'.esc_attr($settings['mobile_column']).'" data-slidespeed="'.esc_attr($settings['slide_speed']).'" data-arrows="'.esc_attr($settings['arrows']).'" data-autoplay="'.esc_attr($settings['auto_play']).'" data-autospeed="'.esc_attr($settings['auto_speed']).'" data-dots="'.esc_attr($settings['dots']).'">';
			$output .= $recipes_html;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		} else {
			$output .= '<div class="no-recipe"><h3>Ujemajočih izdelkov ni mogoče najti.</h3></div>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		echo $output;
	}

}