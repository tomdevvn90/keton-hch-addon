<?php
namespace Keton_4WEB_Addons\Widgets\Posts_Carousel;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Posts_Carousel extends Widget_Base {

    public function get_name() {
			return 'be-posts-carousel';
    }

    public function get_title() {
			return __( 'Posts Carousel', '4web-addons' );
    }

    public function get_icon() {
			return 'eicon-slider-push';
    }

    public function get_categories() {
			return [ '4web-addons' ];
    }

    public function get_script_depends() {
			return [ '4web-addons' ];
		}

		/**
		* Elementor Render Video Recipes
		*/
		protected function be_elementor_render_video_recipes($settings) {
			$output = '';
			
			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} else {
				$paged = 1;
			}
		
			$args = array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'paged' 			   => $paged,
				'posts_per_page' => $settings['post_count'],
				// 'post__in'       => $settings['post_include_filter'],
				'order'          => $settings['order'],
				'orderby'        => $settings['orderby']
			);
		
			$args['klb_special_query'] = true;
			
			$loop = new \WP_Query( $args );
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) : $loop->the_post();
					global $post;

					$output .= '<div class="slide-item"><a href="'.get_permalink($post->ID).'" class="slider-inner">';
                    $output .= '<div class="thumbnail">' . get_the_post_thumbnail( $post->ID, 'medium' ) . '</div>';
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
					'label' => esc_html__( 'Content', '4web-addons' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);
		
			$this->add_control( 'title',
				[
					'label' => esc_html__( 'Title', '4web-addons' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'Blog',
					'label_block' => true,
				]
			);

			$this->add_control( 'auto_play',
				[
					'label' => esc_html__( 'Auto Play', '4web-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'True', '4web-addons' ),
					'label_off' => esc_html__( 'False', '4web-addons' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);
		
			$this->add_control( 'auto_speed',
				[
					'label' => esc_html__( 'Auto Speed', '4web-addons' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => '1600',
					'condition' => ['auto_play' => 'true']
				]
			);
		
			$this->add_control( 'dots',
				[
					'label' => esc_html__( 'Dots', '4web-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'True', '4web-addons' ),
					'label_off' => esc_html__( 'False', '4web-addons' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);
			
			$this->add_control( 'arrows',
				[
					'label' => esc_html__( 'Arrows', '4web-addons' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'True', '4web-addons' ),
					'label_off' => esc_html__( 'False', '4web-addons' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_control( 'slide_speed',
				[
					'label' => esc_html__( 'Slide Speed', '4web-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '1200',
				]
			);

			$this->end_controls_section();			
		
			$this->start_controls_section(
				'be_section_post__filters',
				[
					'label' => esc_html__('Query', '4web-addons'),
				]
			);
		
			$this->add_control( 'column',
				[
					'label' => esc_html__( 'Desktop Column', '4web-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => 3,
					'options' => [
						'0' => esc_html__( 'Select Column', '4web-addons' ),
						'2' 	  => esc_html__( '2 Columns', '4web-addons' ),
						'3'		  => esc_html__( '3 Columns', '4web-addons' ),
						'4'		  => esc_html__( '4 Columns', '4web-addons' ),
					],
				]
			);
		
			$this->add_control( 'mobile_column',
				[
					'label' => esc_html__( 'Mobile Column', '4web-addons' ),
					'type' => Controls_Manager::SELECT,
					'default' => '2',
					'options' => [
						'0' => esc_html__( 'Select Column', '4web-addons' ),
						'1' 	  => esc_html__( '1 Column', '4web-addons' ),
						'2'		  => esc_html__( '2 Columns', '4web-addons' ),
					],
				]
			);

			// Posts Per Page
			$this->add_control( 'post_count',
				[
					'label' => esc_html__( 'Posts Per Page', '4web-addons' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => count( get_posts( array('post_type' => 'recipe-video', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) ),
					'default' => 8
				]
			);

			$this->add_control( 'order',
				[
						'label' => esc_html__( 'Select Order', '4web-addons' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
								'ASC' => esc_html__( 'Ascending', '4web-addons' ),
								'DESC' => esc_html__( 'Descending', '4web-addons' )
						],
						'default' => 'DESC'
				]
			);

			$this->add_control( 'orderby',
				[
						'label' => esc_html__( 'Order By', '4web-addons' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
								'id' => esc_html__( 'Post ID', '4web-addons' ),
								'menu_order' => esc_html__( 'Menu Order', '4web-addons' ),
								'rand' => esc_html__( 'Random', '4web-addons' ),
								'date' => esc_html__( 'Date', '4web-addons' ),
								'title' => esc_html__( 'Title', '4web-addons' ),
						],
						'default' => 'date',
				]
			);

			$this->end_controls_section();
		

			$this->start_controls_section('be_styling',
				[
					'label' => esc_html__( ' Style', '4web-addons' ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);
		
			$this->add_control( 'title_heading',
				[
					'label' => esc_html__( 'TITLE', '4web-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before'
				]
			);
			
			$this->add_control( 'title_color',
				[
					'label' => esc_html__( 'Title Color', '4web-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => ['{{WRAPPER}} .column .entry-title' => 'color: {{VALUE}};']
				]
			);
		
			$this->add_control( 'title_hvrcolor',
				[
						'label' => esc_html__( 'Title Hover Color', '4web-addons' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => ['{{WRAPPER}} .column .entry-title:hover' => 'color: {{VALUE}};']
				]
			);
		
			$this->add_responsive_control( 'title_left',
				[
					'label' => esc_html__( 'Left', '4web-addons' ),
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
					'label' => esc_html__( 'Top', '4web-addons' ),
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
					'label' => esc_html__( 'Opacity', '4web-addons' ),
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
					'label' => esc_html__( 'Typography', '4web-addons' ),
					'scheme' => Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .column .entry-title'
				]
			);
		
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$video_recipes_html = $this->be_elementor_render_video_recipes($settings);
		
		$output = '<div class="site-module module-carousel be-normal-carousel">';

		if($settings['title'] || $settings['subtitle']){
			$output .= '<div class="module-header">';
			$output .= '<div class="column">';
		$output .= '<div class="head-icon">
						<svg width="53" height="53" viewBox="0 0 53 53" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="53" height="53" rx="5" fill="#EA2B0F"/>
						<g clip-path="url(#clip0_570_21)">
						<path d="M33.5815 16.6071H22.751V26.66H33.5815V16.6071ZM31.7345 24.8131H24.5979V18.454H31.7345V24.8131Z" fill="white"/>
						<path d="M37.0429 29.1978H22.7549V31.0447H37.0429V29.1978Z" fill="white"/>
						<path d="M37.0429 33.2296H22.7549V35.0766H37.0429V33.2296Z" fill="white"/>
						<path d="M39.2019 13H20.6696C19.1274 13 17.8715 14.2559 17.8715 15.7981V36.0278C17.8715 37.1378 16.9683 38.0391 15.8601 38.0391C14.752 38.0391 13.8488 37.1359 13.8488 36.0278V20.7849C13.8488 19.5382 14.8628 18.5261 16.1076 18.5261V16.6791C13.8414 16.6791 12 18.5205 12 20.7849V36.0278C12 38.1555 13.7306 39.886 15.8583 39.886H39.2C40.7422 39.886 41.9982 38.6301 41.9982 37.0879V15.7981C41.9982 14.2559 40.7422 13 39.2 13H39.2019ZM40.153 37.0898C40.153 37.6143 39.7264 38.0409 39.2019 38.0409H19.1514C19.5115 37.4555 19.7184 36.7647 19.7184 36.0296V15.7981C19.7184 15.2736 20.145 14.8469 20.6696 14.8469H39.2019C39.7264 14.8469 40.153 15.2736 40.153 15.7981V37.0898Z" fill="white"/>
						</g>
						<defs>
						<clipPath id="clip0_570_21">
						<rect width="30" height="26.886" fill="white" transform="translate(12 13)"/>
						</clipPath>
						</defs>
						</svg>
					</div>';
			$output .= '<h4 class="entry-title">'.esc_html($settings['title']).'</h4>';
			$output .= '</div>';
      		$output .= '<div class="column">';
			$output .= '<a class="be-btn-link" href="/blog" >'. __( "Vse oddaje", "4web-addons" ) .'</a>';
      $output .= '</div>';
			$output .= '</div>';
		}

		$output .= '<div class="be-wrapper be-video-recipes-wrapper">';
		$output .= '<svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
		$output .= '<div class="be-video-recipes-ls be-posts-ls">';
		if ( $video_recipes_html ) {
			$output .= '<div class="module-body">';
			$output .= '<div class="slider-wrapper">';
			$output .= '<div class="video-recipes be-normal-slider" data-slideshow="'.esc_attr($settings['column']).'" data-mobile="'.esc_attr($settings['mobile_column']).'" data-slidespeed="'.esc_attr($settings['slide_speed']).'" data-arrows="'.esc_attr($settings['arrows']).'" data-autoplay="'.esc_attr($settings['auto_play']).'" data-autospeed="'.esc_attr($settings['auto_speed']).'" data-dots="'.esc_attr($settings['dots']).'">';
			$output .= $video_recipes_html;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		} else {
			$output .= '<div class="no-item-found"><h3>'. __( "Ni ustreznih artiklov.", "4web-addons" ) .'</h3></div>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		echo $output;
	}

}