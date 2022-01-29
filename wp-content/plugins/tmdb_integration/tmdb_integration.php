<?php
/**
 * @package Tmdb-Integration
 */
/*
Plugin Name: TMDB WP Integration
Plugin URI: https://zazimthedev.com/
Description: TMDB Wordpress - Woocommerce Integration
Version: 1.0.0
Author: Kostas Zaimakis
Author URI: https://zazimthedev.com/
License: GPLv2 or later
Text Domain: tmdb_int
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit();
}

define('TMDB_INT_VERSION', '4.2.1');
define('TMDB_INT__MINIMUM_WP_VERSION', '5.0');
define('TMDB_INT__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TMDB_OPTIONS', 'tmdb_options');

require __DIR__ . '/admin/admin-includes.php';

// register_activation_hook( __FILE__, array( 'Akismet', 'plugin_activation' ) );
// register_deactivation_hook( __FILE__, array( 'Akismet', 'plugin_deactivation' ) );
