<?php
   /*
   Plugin Name: Post Types Carousel & Slider
   Plugin URI: http://aeonian.in
   Description: By using this plugin you can display posts OR custom posts as a slider OR carousel. Next and Previous post data come by Ajax.
   Version: 1.0
   Author: Aeonian
   Author URI: 
   Text Domain: post-types-carousel-slider
   License: GPLv2
   */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class postcsCls {

	public function __construct() {
		/*Admin functionality*/
		require_once(dirname(__FILE__) . '/includes/admin.php');

		/*Css & Js*/
		require_once(dirname(__FILE__) . '/includes/css_js.php');

		/*Ajax functionality*/
		require_once(dirname(__FILE__) . '/includes/ajax.php');
	}
	
}

$postcsCls = New postcsCls;