<?php 

/*

Plugin Name: Super Elem Addons
Version: 1.0.0
Plugin URI: http:localhost:8090/portfolio/wp-content/plugins/
Description: Nikos Christomanos personal Addons for elementor
Author: Nikos Christomanos
Author URI: https://yourwebsite.gr
Text Domain: super-elem-addon-plugin
Domain path: /languages/

*/

if(!defined('ABSPATH'))
{
  die();
}

/* DEFINE GLOBAL CONSTANTS */
define('SA_PLUGIN_DOMAIN','super-elem-addon');
define('SA_PLUGIN_PATH',plugin_dir_path(__FILE__));
define('SA_PLUGIN_URL',plugin_dir_url(__FILE__));
define('MINIMUM_ELEMENTOR_VERSION','2.0.0');

/* INCLUDE REQUIRED PLUGINS ELEMENTOR */
require SA_PLUGIN_PATH.'elementor/SAwidgets.php';

add_action('after_setup_theme','se_theme_setup');

function se_theme_setup(){
  add_theme_support('woocommerce');
}
?>
