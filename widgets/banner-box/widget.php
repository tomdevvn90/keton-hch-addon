<?php
namespace Keton_4WEB_Addons\Widgets\Banner_Box_Keton;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Banner_Box_Keton extends Widget_Base {

        public function get_name() {
                return 'be-banner-box-keton';
        }

        public function get_title() {
                return __( 'Banner Box', '4web-addons' );
        }

        public function get_icon() {
                return 'eicon-banner';
        }

        public function get_categories() {
                return [ '4web-addons' ];
        }

         public function get_script_depends() {
			return [ '4web-addons' ];
		}

		protected function register_controls() {

			$this->start_controls_section(
				'be_section_content_banner_box',
				[
					'label' => esc_html__('Content', '4web-addons'),
				]
			);

            $this->add_control(
                'image_banner', [
                    'label' => __( 'Image', 'bearsthemes-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_control( 'title_banner_box',
                [
                    'label' => esc_html__( 'Title', '4web-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => __( 'Type title here', '4web-addons' ),
                    'label_block' => true,
                ]
            );

            $this->add_control( 'sub_title_banner_box',
                [
                    'label' => esc_html__( 'Sub title', '4web-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => __( 'Type sub title here', '4web-addons' ),
                    'label_block' => true,
                ]
            );

            $this->add_control( 'link_banner_box',
                [
                    'label' => esc_html__( 'Link', '4web-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => __( 'Type link here', '4web-addons' ),
                    'label_block' => true,
                ]
            );

			$this->end_controls_section();

            $this->start_controls_section('be_styling',
                [
                    'label' => esc_html__( 'Style', '4web-addons' ),
                    'tab' => Controls_Manager::TAB_STYLE
                ]
            );

            $this->add_control(
                'color_circle_background',
                [
                    'label' => __( 'Background Color Circle', '4web-addons' ),
                    'type' => Controls_Manager::COLOR
                ]
            );

            $this->add_control(
                'box_circle_radius',
                [
                    'label' => __( 'Circle radius (px)', '4web-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 800,
                        ]
                    ]
                ]
            );

            $this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $link_banner = $settings['link_banner_box'];
        $image = $settings['image_banner']['url'];
        $title = $settings['title_banner_box'];
        $sub_title = $settings['sub_title_banner_box'];
        $circle_radius = $settings['box_circle_radius']['size'];
        $circle_radius_db = 2*$circle_radius;
        $color_bg = $settings['color_circle_background'];

        if(!empty($link_banner)) {
            ?>
            <style>
                .wrapper-banner-box-custom .overlay-bg {
                    background:<?php echo $color_bg?>;
                    width:<?php echo $circle_radius_db?>px;
                    height:<?php echo $circle_radius_db?>px;
                    top:-<?php echo $circle_radius?>px;
                    left:-<?php echo $circle_radius?>px;
                }
                .wrapper-banner-box-custom .content-detail {
                    max-width:<?php echo $circle_radius?>px;
                    max-height:<?php echo $circle_radius?>px;
                }
            </style>
            <div class="wrapper-banner-box-custom">
                <a class="content" href="<?php echo $link_banner?>">
                    <img class="thumb" src="<?php echo $image?>"/>
                    <div class="overlay-bg"></div>
                    <div class="content-detail">
                        <?php 
                            if($title) {
                                ?>
                                    <h3 class="title"><?php echo $title?></h3>
                                <?php
                            }
                            if($sub_title) {
                                ?>
                                    <span class="sub-title"><?php echo $sub_title?></span>
                                <?php
                            }
                        ?>
                    </div>
                </a>
            </div>
            <?php
        }else{
            ?>
            <style>
                .wrapper-banner-box-custom .overlay-bg {
                    background:<?php echo $color_bg?>;
                    width:<?php echo $circle_radius_db?>px;
                    height:<?php echo $circle_radius_db?>px;
                    top:-<?php echo $circle_radius?>px;
                    left:-<?php echo $circle_radius?>px;
                }
                .wrapper-banner-box-custom .content-detail {
                    max-width:<?php echo $circle_radius?>px;
                    max-height:<?php echo $circle_radius?>px;
                }
                @media(min-width:768px) and (max-width:992px) {
              
                    .wrapper-banner-box-custom .content-detail {
                        background:<?php echo $color_bg?>;
                    }
                    
                }
            </style>
            <div class="wrapper-banner-box-custom">
                <div class="content">
                    <img class="thumb" src="<?php echo $image?>"/>
                    <div class="overlay-bg"></div>
                    <div class="content-detail">
                        <?php 
                            if($title) {
                                ?>
                                    <h3 class="title"><?php echo $title?></h3>
                                <?php
                            }
                            if($sub_title) {
                                ?>
                                    <span class="sub-title"><?php echo $sub_title?></span>
                                <?php
                            }
                        ?>

                    </div>
                </div>
            </div>
            <?php
        }
        
	}
}