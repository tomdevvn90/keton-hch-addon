<?php
namespace Keton_4WEB_Addons\Widgets\Gallery_Image_Responisve;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Gallery_Image_Responisve extends Widget_Base {

        public function get_name() {
                return 'be-gallegry-image-responsive';
        }

        public function get_title() {
                return __( 'Gallery', '4web-addons' );
        }

        public function get_icon() {
                return 'eicon-gallery-grid';
        }

        public function get_categories() {
                return [ '4web-addons' ];
        }

         public function get_script_depends() {
			return [ '4web-addons' ];
		}


		protected function register_controls() {

			$this->start_controls_section(
				'be_section_list_image_gallery',
				[
					'label' => esc_html__('Gallery', '4web-addons'),
				]
			);

            $repeater = new Repeater();

            $this->add_control( 'columns',
                [
                    'label' => esc_html__( 'Columns', '4web-addons' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                    'label_block' => true,
                ]
            );

            $repeater->add_control( 'link_item_gallery',
                [
                    'label' => esc_html__( 'Link', '4web-addons' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => __( 'Type link here', '4web-addons' ),
                    'label_block' => true,
                ]
            );

            $repeater->add_control(
                'list_image', [
                    'label' => __( 'Image', 'bearsthemes-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );


            $this->add_control(
                'list_image_gallery',
                [
                    'label' => __( 'Gallery', '4web-addons' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        [
                            'list_image' => Utils::get_placeholder_image_src(),
                            'link_item_gallery'=>__( '', '4web-addons' ),
                        ],
                    ]
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
					'label' => esc_html__( 'BACKGROUND', '4web-addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before'
				]
			);

            $this->add_control(
                'slider_background',
                [
                    'label' => __( 'Background Color', '4web-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'box_border_radius',
                [
                    'label' => __( 'Border Radius', '4web-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'border-radius: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->add_control(
                'image_width',
                [
                    'label' => __( 'Width Image', '4web-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1920,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wrapper-gallery-reponsive .item img' => 'width: {{SIZE}}{{UNIT}}',
                    ],
                ]
            );

            $this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $list_gallery = $settings['list_image_gallery'];
        $column = $settings['columns'];
        ?>
        <div class="wrapper-gallery-reponsive">
            <div class="btn-wrap wrap-prev">
                <div class="prev-btn">
                    <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="18.5" cy="18.5" r="18" transform="rotate(-180 18.5 18.5)" fill="white" stroke="#F2F2F2"/>
                    <path d="M22 8L11.3934 18.6066L22 29.2132" stroke="#252525" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <div class="list-image colum-grid-<?php echo $column ?>">
                <?php 
                    if(!empty($list_gallery)) {
                        foreach ($list_gallery as $item) {
                            if(!empty($item['link_item_gallery'])) {
                                ?>
                                <a class="link-item item" href="<?php echo $item['link_item_gallery']?>">
                                    <img src="<?php echo $item['list_image']['url']?>"/>
                                </a>
                                <?php
                            }else{
                                ?>
                                <div class="item">
                                    <img src="<?php echo $item['list_image']['url']?>"/>
                                </div>
                                <?php
                            }
                        }
                    }
                ?>
            </div>
            <div class="btn-wrap wrap-next">
                <div class="next-btn">
                    <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="18.5" cy="18.5" r="18" fill="white" stroke="#F2F2F2"/>
                    <path d="M18.6055 29.2109L29.2121 18.6043L18.6055 7.99773" stroke="#252525" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>
        <?php
	}
}