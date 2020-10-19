<?php
/**
 * Plugin Name: Video Thumbnail Generator
 * Plugin URI: https://github.com/wolfthemes/wolf-video-thumbnail-generator
 * Description: Generate an image from the first video in the post. Supports YouTube and Vimeo.
 * Version: 1.0.9
 * Author: WolfThemes
 * Author URI: http://wolfthemes.com
 * Requires at least: 5.0
 * Tested up to: 5.5
 *
 * Text Domain: wolf-video-thumbnail-generator
 * Domain Path: /languages/
 *
 * @package WolfVideoThumbnailGenerator
 * @category Core
 * @author WolfThemes
 *
 * Verified customers who have purchased a premium theme at https://wlfthm.es/tf/
 * will have access to support for this plugin in the forums
 * https://wlfthm.es/help/
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wolf_Video_Thumbnail_Generator' ) ) {
	/**
	 * Main Wolf_Video_Thumbnail_Generator Class
	 *
	 * Contains the main functions for Wolf_Video_Thumbnail_Generator
	 *
	 * @class Wolf_Video_Thumbnail_Generator
	 * @version 1.0.9
	 * @since 1.0.0
	 */
	class Wolf_Video_Thumbnail_Generator {

		/**
		 * @var string
		 */
		public $version = '1.0.9';

		/**
		 * @var Video Thumbnail Generator The single instance of the class
		 */
		protected static $_instance = null;

		/**
		 * @var string
		 */
		private $update_url = 'http://plugins.wolfthemes.com/update';

		/**
		 * @var the support forum URL
		 */
		private $support_url = 'https://wlfthm.es/help';

		/**
		 * Main Video Thumbnail Generator Instance
		 *
		 * Ensures only one instance of Video Thumbnail Generator is loaded or can be loaded.
		 *
		 * @static
		 * @see WVTG()
		 * @return Video Thumbnail Generator - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Video Thumbnail Generator Constructor.
		 */
		public function __construct() {

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'wss_loaded' );
		}

		/**
		 * Hook into actions and filters
		 */
		private function init_hooks() {

			add_action( 'admin_init', array( $this, 'plugin_update' ) );
		}

		/**
		 * Define WR Constants
		 */
		private function define_constants() {

			$constants = array(
				'WVTG_DEV'         => false,
				'WVTG_DIR'         => $this->plugin_path(),
				'WVTG_URI'         => $this->plugin_url(),
				'WVTG_CSS'         => $this->plugin_url() . '/assets/css',
				'WVTG_JS'          => $this->plugin_url() . '/assets/js',
				'WVTG_SLUG'        => plugin_basename( dirname( __FILE__ ) ),
				'WVTG_PATH'        => plugin_basename( __FILE__ ),
				'WVTG_VERSION'     => $this->version,
				'WVTG_SUPPORT_URL' => $this->support_url,
				'WVTG_DOC_URI'     => 'http://docs.wolfthemes.com/documentation/plugins/' . plugin_basename( dirname( __FILE__ ) ),
				'WVTG_WOLF_DOMAIN' => 'wolfthemes.com',
			);

			foreach ( $constants as $name => $value ) {
				$this->define( $name, $value );
			}
		}

		/**
		 * Define constant if not already set
		 *
		 * @param  string      $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 * string $type ajax, frontend or admin
		 *
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {

			/**
			 * Functions used in frontend and admin
			 */
			// include_once( 'inc/wvtg-core-functions.php' );

			if ( $this->is_request( 'admin' ) ) {
				include_once 'inc/admin/class-wvtg-admin.php';
				include_once 'inc/admin/class-wvtg-video-thumbnail.php';
			}

			if ( $this->is_request( 'ajax' ) ) {
				// include_once( 'inc/ajax/wvtg-ajax-functions.php' );
			}

			if ( $this->is_request( 'frontend' ) ) {
				// include_once( 'inc/frontend/wvtg-functions.php' );
				// include_once( 'inc/frontend/class-wvtg-shortcodes.php' );
			}
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Plugin update
		 */
		public function plugin_update() {

			if ( ! class_exists( 'WP_GitHub_Updater' ) ) {
				include_once 'inc/admin/updater.php';
			}

			$repo = 'wolfthemes/wolf-video-thumbnail-generator';

			$config = array(
				'slug'               => plugin_basename( __FILE__ ),
				'proper_folder_name' => 'wolf-video-thumbnail-generator',
				'api_url'            => 'https://api.github.com/repos/' . $repo . '',
				'raw_url'            => 'https://raw.github.com/' . $repo . '/master/',
				'github_url'         => 'https://github.com/' . $repo . '',
				'zip_url'            => 'https://github.com/' . $repo . '/archive/master.zip',
				'sslverify'          => true,
				'requires'           => '5.0',
				'tested'             => '5.5',
				'readme'             => 'README.md',
				'access_token'       => '',
			);

			new WP_GitHub_Updater( $config );
		}
	} // end class
} // end class check

/**
 * Returns the main instance of WVTG to prevent the need to use globals.
 *
 * @return Wolf_Video_Thumbnail_Generator
 */
function WVTG() {
	return Wolf_Video_Thumbnail_Generator::instance();
}

WVTG(); // Go.
