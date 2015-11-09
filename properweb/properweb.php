<?php
/*
Plugin Name: ProperWeb
Description: This plugin will allow you to extand the reseller hosting API from ResellersPanel with customised shortcodes
Version: 1.0
Author: ProperWeb
*/

define('CODE', 'error_code');
define('MSG', 'error_msg');

define('BRONZE', 58395);
define('SILVER', 58393);
define('GOLD', 58394);
define('CURRENCY', "C$%.2f");

$pw_path = dirname(__FILE__);
require_once dirname($pw_path).'/resellerspanel/resellerspanel.php';

//Configurations

$rp_crypt = new rp_crypt();

try {
	$rp_api = new rp_paymentapi('https://api.duoservers.com/', isset($rp_settings['user']) && $rp_settings['user']?$rp_crypt->decrypt($rp_settings['user']):'', isset($rp_settings['pass']) && $rp_settings['pass']?$rp_crypt->decrypt($rp_settings['pass']):'');
} 
catch (Exception $e) {
	global $rp_api_error;
	$rp_api_error = $e->getMessage();
}

//include relevant stylesheet
function pwrp_scripts() {
	wp_register_style('pwrp_style', plugin_dir_url( __FILE__ ) . 'style.css');
	wp_enqueue_style('pwrp_style');
}
add_action( 'wp_enqueue_scripts', 'pwrp_scripts' );

//create shortcode to make a separator of the new article within the page
add_shortcode('pw_plans', 'pwrp_print_plans');

//usage [pw_plans plan="58395"]
//usage [pw_plans best="58393"]
function pwrp_print_plans( $atts ) {
	global $pw_path;
	$plans = _pwrp_plans();
	
	$best = 0;
	if ( !empty($atts['best']) ) {
		$best = (int)trim($atts['best']);
	}

	if (empty($atts['plan'])) {
		ob_start();
		require $pw_path.'/templates/pw_plans.php';
		return do_shortcode(ob_get_clean());
	}
}

/* DO NOT MESS WITH BELOW */

//get only hosting plans with all services and priced in CAD
function _pwrp_plans() {
	global $rp_api;

	try {
		$all_products = $rp_api->command('products', 'get_plans', array('prices' => array ('price'),'currencies' => array('CAD'),'offered' => true));
		$products = array_slice($all_products['plans'], 0, $all_products['count'] - 1);
	}
	catch (Exception $e) {
		global $rp_api_error;
		$rp_api_error = $e->getMessage();
	}
	//map by product_id
	$plans = array();
	if (!empty($products)) {
		foreach ($products as $key => $value) {
			$plans[$value['rp_product_id']] = $value;
		}
	}
	return $plans;
}

function rp_admin_api_error(){
		global $rp_api_error;
		if ($rp_api_error)
			echo '<div class="updated fade"><p>Plugin has experienced error <br /> <strong>'.$rp_api_error.'</strong></p></div>';
	}
	
add_action('admin_notices', 'rp_admin_api_error');
?>