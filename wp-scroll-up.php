<?php
/*
Plugin Name: WP Back To Top Button
Plugin URI: http://wpxpress.net/wp-back-to-top-button/
Description: The best WordPress back to top plugin With scroll progress indicator.
Author: wpXpress
Author URI: http://wpxpress.net/
Version: 1.0.0
License: GPLv2+
Text Domain: wpst
Domain Path: /languages
*/

// Don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'WP_Scroll_Top' ) ) {
	class WP_Scroll_Top {

		/**
		 * Version
		 *
		 * @since 1.0.0
		 * @var  string
		 */
		public $version = '1.0.0';


		/**
		 * The single instance of the class.
		 */
		protected static $instance = null;


		/**
		 * Constructor for the class
		 *
		 * Sets up all the appropriate hooks and actions
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		public function __construct() {

			// Define constants
			$this->define_constants();

			// Include required files
			$this->includes();

			// Initialize the action hooks
			$this->init_hooks();

		}


		/**
		 * Initializes the class
		 *
		 * Checks for an existing instance
		 * and if it does't find one, creates it.
		 *
		 * @return object Class instance
		 * @since 1.0.0
		 *
		 */
		public static function instance() {

			if ( null === self::$instance ) {

				self::$instance = new self();

			}

			return self::$instance;

		}


		/**
		 * Define constants
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function define_constants() {

			define( 'WPST_VERSION', $this->version );
			define( 'WPST_FILE', __FILE__ );
			define( 'WPST_DIR_PATH', plugin_dir_path( WPST_FILE ) );
			define( 'WPST_DIR_URI', plugin_dir_url( WPST_FILE ) );
			define( 'WPST_INCLUDES', WPST_DIR_PATH . 'inc' );
			define( 'WPST_ADMIN', WPST_DIR_PATH . 'admin' );
			define( 'WPST_ASSETS', WPST_DIR_URI . 'assets' );
		}


		/**
		 * Include required files
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function includes() {

			require WPST_INCLUDES . '/functions.php';

			if ( is_admin() ) {
				require_once WPST_ADMIN . '/class-settings-api.php';
				require_once WPST_ADMIN . '/class-settings.php';
			}


		}


		/**
		 * Init Hooks
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function init_hooks() {

			add_action( 'init', array( $this, 'localization_setup' ) );
			add_action( 'wp_head', array( $this, 'internal_styles' ) );
			add_action( 'wp_head', array( $this, 'add_markup' ) );
			add_action( 'wp_footer', array( $this, 'internal_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_settings_links' ) );

		}


		/**
		 * Initialize plugin for localization
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		public function localization_setup() {

			load_plugin_textdomain( 'wpst', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		}


		/**
		 * Plugin action links
		 *
		 * @param array $links
		 *
		 * @return array
		 */
		function plugin_settings_links( $links ) {

			$links[] = '<a href="' . admin_url( 'admin.php?page=' ) . 'wp-back-to-top">' . __( 'Settings', 'wpst' ) . '</a>';

			return $links;

		}


		function enqueue_scripts() {

			wp_register_style( 'wpst-fonts', WPST_ASSETS . '/css/fonts.css', array(), '1.0.0' );
			wp_register_style( 'wpst-style', WPST_ASSETS . '/css/wp-scroll-top.css', array(), '1.0.0' );
			wp_register_script( 'wpst-script', WPST_ASSETS . '/js/wp-scroll-top.js', array( 'jquery' ), '1.0.0', true );
			
			wp_enqueue_style( 'wpst-fonts' );
			wp_enqueue_style( 'wpst-style' );
			wp_enqueue_script( 'wpst-script' );
		}


		public function internal_styles() {
			?>
			<style type="text/css">
				background: red;
			</style>
			<?php
		}


		public function internal_scripts() {
			?>
			<script type="text/javascript">
				alert('Hi');
			</script>
			<?php
		}


		public function add_markup() {
			?>
            <div class="progress-wrap">
                <svg class="progress-circle" width="100%" height="100%" viewBox="-1 -1 102 102">
                    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
                </svg>
            </div>
			<?php
		}

	}
}


/**
 * Initialize the plugin
 *
 * @return object
 */
function wpx_scroll_top() {
	return WP_Scroll_Top::instance();
}

// Kick Off
wpx_scroll_top();
