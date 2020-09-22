<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://herzog-webstudios.de/
 * @since      1.0.0
 *
 * @package    Cec_Termine
 * @subpackage Cec_Termine/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cec_Termine
 * @subpackage Cec_Termine/includes
 * @author     Florian Herzog <fh@herzog-webstudios.de>
 */
class Cec_Termine_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cec-termine',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
