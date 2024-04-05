<?php
namespace Keton_4WEB_Addons\Widgets\Buy_Smart_Home;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Be_Buy_Smart_Home extends Widget_Base {

    public function get_name() {
			return 'be-buy-smart';
    }

    public function get_title() {
			return __( 'Smart shopping', '4web-addons' );
    }

    public function get_icon() {
			return 'eicon-woo-cart';
    }

    public function get_categories() {
			return [ '4web-addons' ];
    }

    public function get_script_depends() {
			return [ '4web-addons' ];
		}
    protected function get_product_ids() {
        $supported_ids = [];

        $wp_query = new \WP_Query( array(
            'post_type' => 'product',
            'post_status' => 'publish'
        ) );

        if ( $wp_query->have_posts() ) {
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $supported_ids[get_the_ID()] = get_the_title();
            }
        }

        return $supported_ids;
    }
	
    protected function register_controls() {



    }

	protected function render() {
		$settings = $this->get_settings_for_display();
        $list_product = get_field('list_product_compare','options');

        ?>
            <div class="wrapper-shopping-smart-custom">
                <?php 
                    if(!empty($list_product)) {
                        ?>
                        <div class="flex-box-top">
                            <div class="top-our-product">
                                <h3 class="title">
                                    <?php 
                                        echo __('Naš izdelek','4web-addons');
                                    ?>
                                </h3>
                                <div class="filter-dropdown">
                                    <select>
                                        <?php 
                                            foreach ($list_product as $key => $pr) {
                                                ?>
                                                    <option value="product-<?php echo $key?>">
                                                        <?php 
                                                            echo $pr['label'];
                                                        ?>
                                                    </option>
                                                <?php
                                            }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="top-st-product">
                                <h3 class="title">
                                    <?php 
                                        echo __('Standardni izdelek','4web-addons');
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="content-product">
                            <div class="content-detail-product">
                                <svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>
                                <?php 
                                    foreach ($list_product as $key => $out_ct) {
                                        if($key==0) {
                                            $class="show-active";
                                        }else{
                                            $class="";
                                        }
                                        ?>
                                            <div class="item <?php echo $class?>"  data-filter ="product-<?php echo $key?>">
                                                <div class="item-our">
                                                    <div class="ct-pr">
                                                        <div class="ingredient">
                                                            <?php 
                                                                foreach ($out_ct['our_product']['ingred'] as $key => $value) {
                                                                    ?>
                                                                    <div class="in-item">
                                                                        <div class="data">
                                                                            <?php 
                                                                                echo $value['data']
                                                                            ?>
                                                                        </div>
                                                                        <div class="name">
                                                                            <?php 
                                                                                echo $value['name_in']
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="product-our">
                                                            <a class="product-image" href="<?php echo get_permalink($out_ct['our_product']['product'])?>">
                                                                <img src="<?php echo get_the_post_thumbnail_url($out_ct['our_product']['product'],'full')?>"/>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item-standard">
                                                    <div class="ct-pr">
                                                        <div class="ingredient">
                                                            <?php 
                                                                foreach ($out_ct['standard_product']['ingredients'] as $key => $value) {
                                                                    ?>
                                                                    <div class="in-item">
                                                                        <div class="data">
                                                                            <?php 
                                                                                echo $value['data_in']
                                                                            ?>
                                                                        </div>
                                                                        <div class="name">
                                                                            <?php 
                                                                                echo $value['name_in']
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="product-our">
                                                            <a class="product-image" href="<?php echo get_permalink($out_ct['standard_product']['product'])?>">
                                                                <img src="<?php echo get_the_post_thumbnail_url($out_ct['standard_product']['product'],'full')?>"/>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        <?php
		
	}
}