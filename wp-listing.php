<?php
/**
 * A plugin that works with a shortcode to display data from a JSON file.
 *
 * @link              https://creolestudios.com
 * @since             1.0.0
 * @package           Wp_Listing
 *
 * @wordpress-plugin
 * Plugin Name:       WP listing
 * Plugin URI:        https://wp-listing
 * Description:       A plugin that works with a shortcode to display data from a JSON file.
 * Version:           1.0.0
 * Author:            Creole Studios
 * Author URI:        https://creolestudios.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-listing
 */

defined( 'ABSPATH' ) || exit;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_LISTING_VERSION', '1.0.0' );
define( 'WP_LISTING_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_LISTING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The core plugin class that is used to define internationalization,
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-listing.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_listing() {

	$plugin = new Wp_Listing();

}
run_wp_listing();
