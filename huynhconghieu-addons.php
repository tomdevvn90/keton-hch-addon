<?php
/**
 * Plugin Name: HCH Addons
 * Description: Create new elements for Elementor.
 * Plugin URI:  https://keton.4web.si
 * Version:     1.0.0
 * Author:      Huynh Cong Hieu
 * Author URI:  https://keton.4web.si
 * Text Domain: hch-addons
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main HuynhCongHieu Addons Class
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.0.0
 */

define('B_HELPERS_DIR', plugin_dir_path(__FILE__)); 

final class HuynhCongHieu_Addons {

	/**
	 * Plugin Version
	 *
	 * @since 2.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 2.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 2.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'hch-addons' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		// Enqueue scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'hch_enqueue_scripts' ) );

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}
		
		/* Hook */

		require_once( plugin_dir_path(__FILE__) . '/inc/hook.php' );

		/* Add new custom post type */
		require_once( plugin_dir_path(__FILE__) . '/inc/new-custom-posttype.php' );

		/* Helper functions */
		require_once( plugin_dir_path(__FILE__) . '/inc/helper-functions.php' );

		/* Ajax functions */
		require_once( plugin_dir_path(__FILE__) . '/inc/ajax-functions.php' );

		/* widget block */
		require_once( plugin_dir_path(__FILE__) . '/inc/widget.php' );

		/* Shortcode */
		require_once( plugin_dir_path(__FILE__) . '/inc/shortcode.php' );

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( plugin_dir_path(__FILE__) . '/plugin.php' );

	}

	/**
	 * Enqueue scripts
	 */
	public function hch_enqueue_scripts() {

		wp_enqueue_style( 'hch-addons-elements', plugins_url( '/assets/css/elements.css', __FILE__ ) );

		wp_enqueue_style( 'hch-addons-recipes-cat', plugins_url( '/assets/css/recipes-categories.css', __FILE__ ) );

		wp_enqueue_style( 'hch-addons-ajax-pagination', plugins_url( '/assets/css/ajax_pagination.css', __FILE__ ) );

		wp_enqueue_style( 'hch-addons-shop-page', plugins_url( '/assets/css/shop.css', __FILE__ ) );

		wp_enqueue_script( 'hch-addons-video-recipes', plugins_url('/assets/js/video-recipes.js',__FILE__) , ['jquery'], time(), true  );

		wp_enqueue_script( 'hch-addons-shop-page', plugins_url('/assets/js/shop.js',__FILE__) , ['jquery'], time(), true  );

		wp_enqueue_script( 'hch-addons-ajax-pagination', plugins_url('/assets/js/ajax_pagination.js',__FILE__) , ['jquery'], time(), true  );

		$php_array = array(
			'admin_ajax' => admin_url( 'admin-ajax.php' )
		);

		wp_localize_script( 'hch-addons-ajax-pagination', 'hch_array_ajaxp', $php_array );
		wp_localize_script( 'hch-addons-shop-page', 'hch_array_ajaxp', $php_array );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'hch-addons' ),
			'<strong>' . esc_html__( 'HuynhCongHieu Addons', 'hch-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'hch-addons' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'hch-addons' ),
			'<strong>' . esc_html__( 'HuynhCongHieu Addons', 'hch-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'hch-addons' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'hch-addons' ),
			'<strong>' . esc_html__( 'HuynhCongHieu Addons', 'hch-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'hch-addons' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate HuynhCongHieu_Addons.
new HuynhCongHieu_Addons();
