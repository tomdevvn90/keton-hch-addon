<?php
namespace Keton_4WEB_Addons\Widgets\List_reviews_Home;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_List_reviews_Home extends Widget_Base {

        public function get_name() {
                return 'be-list-review-home';
        }

        public function get_title() {
                return __( 'List Rating Reviews', '4web-addons' );
        }

        public function get_icon() {
                return 'eicon-review';
        }

        public function get_categories() {
                return [ '4web-addons' ];
        }

         public function get_script_depends() {
			return [ '4web-addons' ];
		}


		protected function register_controls() {

			$this->start_controls_section(
				'be_section_list_reviews',
				[
					'label' => esc_html__('Reviews', '4web-addons'),
				]
			);

            $repeater = new Repeater();

            $this->add_control( 'count_all_reviews',
                [
                    'label' => esc_html__( 'All Count Reviews ', '4web-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 20,
                    'label_block' => true,
                ]
            );

            $this->add_control( 'average_rating',
                [
                    'label' => esc_html__( 'The average rating for reviews ', '4web-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
				    'max' => 5,
                    'default' => 5,
                    'label_block' => true,
                ]
            );


            $repeater->add_control(
                'rating_reviews', [
                    'label' => __( 'Rating Reviews', '4web-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
				    'max' => 5,
                    'default' => 5,
                    'label_block' => true,
                ]
            );


            $repeater->add_control(
                'content_reviews', [
                    'label' => __( 'Content Reviews', '4web-addons' ),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
				    'rows' => 10,
                    'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac ipsum et odio varius faucibus. Nulla vulputate fermentum fringilla.', '4web-addons' ),
				    'placeholder' => __( 'Type description reviews here', '4web-addons' ),
                ]
            );

            $repeater->add_control(
                'author_reviews', [
                    'label' => __( 'Author', '4web-addons' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'Ime Priimek', '4web-addons' ),
				    'placeholder' => __( 'Type author reviews here', '4web-addons' ),
                ]
            );

            $repeater->add_control(
                'date_reviews', [
                    'label' => __( 'Date', '4web-addons' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( '7/11/2023', '4web-addons' ),
				    'placeholder' => __( 'Type date reviews here', '4web-addons' ),
                ]
            );

            $this->add_control(
                'list_reviews',
                [
                    'label' => __( 'List Reviews', '4web-addons' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                           
                            'rating_reviews' => 5,
                            'content_reviews' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac ipsum et odio varius faucibus. Nulla vulputate fermentum fringilla.', '4web-addons', '4web-addons' ),
                            'author_reviews'=>__( 'Ime Priimek', '4web-addons' ),
                            'date_reviews'=>__( '7/11/2023', '4web-addons' )
                        ],
                        [
                           
                            'rating_reviews' => 5,
                            'content_reviews' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ac ipsum et odio varius faucibus. Nulla vulputate fermentum fringilla.', '4web-addons', '4web-addons' ),
                            'author_reviews'=>__( 'Ime Priimek', '4web-addons' ),
                            'date_reviews'=>__( '7/11/2023', '4web-addons' )
                        ]
                    ]
                ]
            );

			$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $allcount_reviews = $settings['count_all_reviews'];
        $all_average = $settings['average_rating'];
        $list_review = $settings['list_reviews'];
        $icon_star = '<svg width="26" height="23" viewBox="0 0 26 23" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="0.793945" y="0.328125" width="24.9042" height="22.3987" fill="#49999A"/>
        <path d="M11.9215 1.98777C12.2976 1.28062 13.311 1.28062 13.6872 1.98777L15.8987 6.14512C16.0337 6.39883 16.2712 6.58221 16.5509 6.64851L20.9787 7.69835C21.697 7.86865 21.9895 8.73174 21.5228 9.30363L18.4133 13.1139C18.2475 13.3171 18.1682 13.5772 18.1923 13.8383L18.6536 18.8265C18.7252 19.6011 17.9239 20.1573 17.2233 19.8192L13.2389 17.8968C12.9643 17.7643 12.6443 17.7643 12.3698 17.8968L8.38533 19.8192C7.68473 20.1573 6.8834 19.6011 6.95503 18.8265L7.4163 13.8383C7.44045 13.5772 7.36108 13.3171 7.19531 13.1139L4.08585 9.30363C3.61915 8.73174 3.91167 7.86865 4.62991 7.69835L9.05777 6.64851C9.33738 6.58221 9.57496 6.39883 9.70992 6.14512L11.9215 1.98777Z" fill="white"/>
        </svg>';
        ?>
        <div class="wrapper-list-reviews-home">
            <div class="average-reviews-wrapper">
                <div class="icon-rating">
                    <?php 
                        for ($x = 0; $x <$all_average; $x++) {
                            echo $icon_star;
                        }
                    ?>
                </div>
                <div class="count-all-rating">
                    <?php 
                        echo __($allcount_reviews.' ocen','4web-addons');
                    ?>
                </div>
            </div>
            <div class="slider-wp-new-custom">
                <div class="btn-wrap prev-wrap">
                    <div class="prev-btn">
                        <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18.5" cy="18.5" r="18" transform="rotate(-180 18.5 18.5)" fill="white" stroke="#F2F2F2"/>
                        <path d="M22 8L11.3934 18.6066L22 29.2132" stroke="#252525" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="list-reviews">
                    <?php 
                        if(!empty($list_review)) {
                            foreach ($list_review as $rv) {
                                ?>
                                <div class="item-review">
                                    <div class="icon-rating">
                                        <?php 
                                            for ($x = 0; $x < $rv['rating_reviews']; $x++) {
                                                echo $icon_star;
                                            }
                                        ?>
                                    </div>
                                    <div class="content-reviews">
                                        <?php 
                                            echo $rv['content_reviews']
                                        ?>
                                    </div>
                                    <div class="author">
                                        <?php 
                                            echo $rv['author_reviews']
                                        ?>
                                    </div>
                                    <div class="date">
                                        <?php 
                                            echo $rv['date_reviews']
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    ?>
                </div>
                <div class="btn-wrap next-wrap">
                    <div class="next-btn">
                        <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18.5" cy="18.5" r="18" fill="white" stroke="#F2F2F2"/>
                        <path d="M18.6055 29.2109L29.2121 18.6043L18.6055 7.99773" stroke="#252525" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
           
        <?php
		
	}
}