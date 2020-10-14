<?php
/**
 * Video Thumbnail Generator Admin.
 *
 * @class WVTG_Admin
 * @author WolfThemes
 * @category Admin
 * @package WolfVideoThumbnailGenerator/Admin
 * @version 1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WVTG_Admin class.
 */
class WVTG_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {

		// Includes files.
		$this->includes();

		// Admin init hooks.
		$this->admin_init_hooks();
	}

	/**
	 * Perform actions on updating the theme id needed
	 */
	public function update() {

		if ( ! defined( 'IFRAME_REQUEST' ) && ! defined( 'DOING_AJAX' ) && ( get_option( 'wvtg_version' ) !== WVTG_VERSION ) ) {

			// Update hook.
			do_action( 'wvtg_do_update' );

			// Update version.
			delete_option( 'wvtg_version' );
			add_option( 'wvtg_version', WVTG_VERSION );

			// After update hook.
			do_action( 'wvtg_updated' );
		}
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {


	}

	/**
	 * Admin init
	 */
	public function admin_init_hooks() {

		// Update version and perform stuf if needed.
		add_action( 'admin_init', array( $this, 'update' ), 0 );
	}
}

return new WVTG_Admin();
