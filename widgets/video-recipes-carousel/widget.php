<?php
namespace Keton_4WEB_Addons\Widgets\Video_Recipes_Carousel;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Video_Recipes_Carousel extends Widget_Base {

    public function get_name() {
			return 'video-recipes-carousel';
    }

    public function get_title() {
			return __( 'Video Recipes Carousel', '4web-addons' );
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
				'post_type'      => 'recipe-video',
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
					$output .= '<div class="meta-item">' . getPostViews($post->ID) . '</div>';
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
					'default' => 'Kuhinja izzivov',
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
      $output .= '<div class="head-icon"><svg width="29" height="30" viewBox="0 0 29 30" fill="none" xmlns="http://www.w3.org/2000/svg">
									<g clip-path="url(#clip0_7_641)"><path d="M23.07 28.0621H6.09257V23.2407H4.18091V30H24.9817V23.2407H23.07V28.0621Z" fill="white"/>
									<path d="M26.4842 2.88943C25.6106 2.28482 23.3472 1.21512 20.2159 3.36614C18.6273 1.11241 16.5799 -0.0968137 14.4828 0.00589288C12.3876 -0.0987515 10.3402 1.11241 8.7497 3.3642C5.6184 1.21318 3.35499 2.28288 2.48136 2.88749C0.468379 4.28275 -0.506569 7.22636 0.261919 9.58861C0.911885 11.5846 3.72585 19.5221 3.84629 19.8593L4.07377 20.5007H24.8918L25.1193 19.8593C25.2378 19.5221 28.0537 11.5846 28.7036 9.58861C29.4721 7.22636 28.4972 4.28275 26.4842 2.88943ZM26.8876 8.98206C26.3523 10.6273 24.2476 16.5862 23.5479 18.5628H20.4414L22.4315 9.36382H20.4739L18.4839 18.5628H15.4386V8.25148H13.5269V18.5628H10.4817L8.49163 9.36382H6.53409L8.52413 18.5628H5.41767C4.71801 16.5862 2.61326 10.6273 2.078 8.98206C1.57905 7.44921 2.2577 5.3912 3.55954 4.4901C4.83271 3.60837 6.54556 3.97075 8.38266 5.50941L9.24482 6.23224L9.8145 5.25749C10.4243 4.21492 12.0932 1.80423 14.4254 1.94569L14.4828 1.94957L14.5401 1.94569C16.8685 1.80616 18.5412 4.21492 19.1511 5.25749L19.7207 6.23224L20.5829 5.50941C22.42 3.97075 24.1329 3.60837 25.406 4.4901C26.7079 5.3912 27.3865 7.44921 26.8876 8.98206Z" fill="white"/>
									</g><defs><clipPath id="clip0_7_641"><rect width="28.9655" height="30" fill="white"/></clipPath></defs></svg></div>';
			$output .= '<h4 class="entry-title">'.esc_html($settings['title']).'</h4>';
			$output .= '</div>';
      $output .= '<div class="column">';
			$output .= '<a class="be-btn-link" href="/recipe-video" >'. __( "Vse oddaje", "4web-addons" ) .'</a>';
      $output .= '</div>';
			$output .= '</div>';
		}

		$output .= '<div class="be-wrapper be-video-recipes-wrapper">';
		$output .= '<svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
		$output .= '<div class="be-video-recipes-ls">';
		if ( $video_recipes_html ) {
			$output .= '<div class="module-body">';
			$output .= '<div class="slider-wrapper">';
			$output .= '<div class="video-recipes be-normal-slider" data-slideshow="'.esc_attr($settings['column']).'" data-mobile="'.esc_attr($settings['mobile_column']).'" data-slidespeed="'.esc_attr($settings['slide_speed']).'" data-arrows="'.esc_attr($settings['arrows']).'" data-autoplay="'.esc_attr($settings['auto_play']).'" data-autospeed="'.esc_attr($settings['auto_speed']).'" data-dots="'.esc_attr($settings['dots']).'">';
			$output .= $video_recipes_html;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
		} else {
			$output .= '<div class="no-item-found"><h3>'. __( "Ni ustreznih receptov.", "4web-addons" ) .'</h3></div>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		echo $output;
	}

}