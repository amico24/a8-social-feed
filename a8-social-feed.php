<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sample.com
 * @since             1.0.0
 * @package           A8_Social_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       A8 Social Feed
 * Plugin URI:        https://sample.com
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Cyrus Kael Abiera
 * Author URI:        https://sample.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       a8-social-feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'A8_SOCIAL_FEED_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-a8-social-feed-activator.php
 */
function activate_a8_social_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-a8-social-feed-activator.php';
	A8_Social_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-a8-social-feed-deactivator.php
 */
function deactivate_a8_social_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-a8-social-feed-deactivator.php';
	A8_Social_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_a8_social_feed' );
register_deactivation_hook( __FILE__, 'deactivate_a8_social_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-a8-social-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_a8_social_feed() {

	$plugin = new A8_Social_Feed();
	$plugin->run();

}
run_a8_social_feed();
