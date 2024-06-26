<?php
namespace Keton_4WEB_Addons;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public $widgets = array();
	public $woocommerce_status = false;

	public function woocommerce_status() {

		if ( class_exists( 'WooCommerce' ) ) {
			$this->woocommerce_status = true;
		}

		return $this->woocommerce_status;
	}

	public function widgets_list() {

		$this->widgets = array();

		// WooCommerce.
		if ( $this->woocommerce_status() ) {

 			$this->widgets = array_merge(
 				$this->widgets, array(
					'products-carousel',
					'recipes-carousel',
					'video-recipes-carousel',
					'posts-carousel',
					'recipes-categories-1',
					'buy-smart',
					'list-reviews',
					'gallery-image',
					'banner-box',
					'categories-product'
 				)
 			);

		}

		return $this->widgets;
	}

	/**
	 * widget_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_styles() {

	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {

		wp_register_script( '4web-addons', plugins_url( '/assets/js/frontend.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_localize_script( '4web-addons', 'hch_objs', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) );
		wp_enqueue_script('4web-addons');

	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function include_widgets_files() {

		foreach( $this->widgets_list() as $widget ) {
			require_once( __DIR__ . '/widgets/'. $widget .'/widget.php' );
		}

	}

	/**
	 * Register Category
	 *
	 * Register new Elementor category.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_category( $elements_manager ) {
		$elements_manager->add_category(
			'4web-addons',
			[
				'title' => esc_html__( '4WEB Addons', '4web-addons' )
			]
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Recipe Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Recipes_Carousel\Be_Recipes_Carousel() );

		// Video Recipe Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Video_Recipes_Carousel\Be_Video_Recipes_Carousel() );

		// WooCommerce Widgets.
		if ( $this->woocommerce_status() ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Products_Carousel\Be_Products_Carousel() );
		}

		// Posts widgets

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Posts_Carousel\Be_Posts_Carousel() );

		// Recipes Categories
		
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Recipes_Categories_1\Be_Recipes_Categories_1() );
	

		// Smart Shopping
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Buy_Smart_Home\Be_Buy_Smart_Home() );

		// Banner Box
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Banner_Box_Keton\Be_Banner_Box_Keton() );

		// Be Categories Product
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Categories_Keton_Addon\Be_Categories_Keton_Addon() );
		

		// List Reviews
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\List_reviews_Home\Be_List_reviews_Home() );

		// Gallery Image
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Gallery_Image_Responisve\Be_Gallery_Image_Responisve() );



	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Register widget styles
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'widget_styles' ] );

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register category
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

	}
}

// Instantiate Plugin Class
Plugin::instance();
