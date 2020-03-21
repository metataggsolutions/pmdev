<?php

/*
 * Plugin Name: Anything Slider - Divi
 * Plugin URI:  http://demo.cakewp.com/anythingslider
 * Description: With Anything Slider you can literally slide anything as the name suggest. Use any Divi built-in modules in slides or many ( unless there's a conflict with a module ) 3rd party modules you may have. No limit at all!
 * Author:      munirkamal
 * Version:     1.5
 * Author URI:  http://www.cakewp.com
 */

/* ===================================== */
//              CHANGELOG                //
/* ===================================== */

/* 
-- v1.5
- Improved: Divi Animations work on each slide now.
- FIX: Minor Bug Fixes.

-- v1.4
- NEW: Two completely new modules added which will allow the user to create any CPT slider with the ability to puzzle slide layout in any way a user wants using the provided CPT Elements module. This update opens up a bunch of new possibilities you can use Anything Slider plugin.
- FIX: Some bug fixes to AS module.
- Improved: Some under the hood improvements to AS module.

-- V1.3
- NEW: Disable Next/Previous Labels Option.
- NEW: Equal Height Option.
- NEW: Minimum Height Option.
- NEW: Slide Type option to choose between Layout or Custom/Content. Now making basic sliders, even complex are more easier than before.
- NEW: Slide Image with bunch of dependant options to style it.
- NEW: Slide Content with bunch of depenadnt options to style it.
- NEW: Two Buttons can be added now bellow content for content slides.
- FIX: Image Top alignment for builtin NavMenu control.
- FIX: Other bugs fixes & code improvement.

-- V1.2
- NEW: Built-in Nav Menu Control which also syncs on auto.
- NEW: Custom IDs/URL made possible.
- NEW: Option to disable ID/URL.
- NEW: Option to enable Slide on Mouse Hover for Nav Menu control and AS Menu module. 
- NEW: Option to Center Slide in Carousel mode
- NEW: Option to enable RTL Support, this will make the slider work in RTL websites.
- NEW: Slideby Option added to specify how many slides to skip on each rotates in carousel mode.
- Improved: Some controls type changed from text to range for better user experience.
- Improved: Better organization of controls.
- Improved: Next/Previous control disabled state.
- Improved: Script loading
- Fixed: AutoHeight Issue where images got cut off from the bottom. 
- Fixed: Few Bugs and code cleanup.
- Fixed: Auto update functionality, now from the next time user will be able to update from WP dashboard directly.

-- V1.1
- Fixed: Divi Local Cache Storage issue
- Fixed: Slider OFF animation, this will now make slider slide in left/right direction.
- Fixed: Next/prev control hover issue when position was set to 'Both Sides'
- Fixed: AS Menu image's max-width when put inside slider module
*/


/* ===================================== */


define( 'CWP_AS_VERSION', '1.5' );
define( 'CWP_AS_STORE_URL', 'https://elegantmarketplace.com' );
define( 'CWP_AS_ITEM_NAME', 'Anything Slider' );
define( 'CWP_AS_ITEM_ID', '269841' ); 
define( 'CWP_AS_AUTHOR_NAME', 'Munir Kamal' );

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include( dirname( __FILE__ ) . '/includes/EDD_SL_Plugin_Updater.php' );
}

// retrieve our license key from the DB
$cwp_as_license_key = trim( get_option( 'cwp_as_license_key' ) );

// setup the updater
$cwp_as_edd_updater = new EDD_SL_Plugin_Updater( CWP_AS_STORE_URL, __FILE__, array(
        'version'   => CWP_AS_VERSION,
        'license'   => $cwp_as_license_key,
        'item_name' => CWP_AS_ITEM_NAME,
        'item_id'   => CWP_AS_ITEM_ID,
        'author'    => CWP_AS_AUTHOR_NAME
    )
);

//EMP Licensing Integration
require_once('includes/emp-licensing.php');

add_action('plugins_loaded', 'cwp_divi_anything_slider_init');

if (!function_exists('cwp_divi_anything_slider_init')){
function cwp_divi_anything_slider_init() {
    add_action('init', 'cwp_divi_anything_slider_setup', 9999);

     add_image_size('AS_150_square', 150, 150, true);
     add_image_size('AS_250_square', 250, 250, true);
     add_image_size('AS_350_square', 350, 350, true);
     add_image_size('AS_250_tall', false, 250, true);
     add_image_size('AS_450_tall', false, 450, true);
     add_image_size('AS_650_tall', false, 650, true);
}
}

if(!function_exists('cwp_divi_anything_slider_setup')) {
function cwp_divi_anything_slider_setup() {

    if ( class_exists('ET_Builder_Module')) {

        //Inclue Modules
        $modules_path = trailingslashit(dirname(__FILE__)) . 'modules/';
        require_once($modules_path . 'cwp-as-menu.php');
        require_once($modules_path . 'cwp-as-slider.php');
        require_once($modules_path . 'cwp-as-cpt-slider.php');
        require_once($modules_path . 'cwp-as-cpt-elements.php');

        //Needed Scripts and Styles
        wp_register_script( 
        'cwp_anythingslider_owl_js', 
        plugins_url( '/js/owl.carousel.min.js', __FILE__ ), 
        array ('jquery'),
        false,
        false 
        );
        wp_register_style( 
            'cwp_anythingslider_owl_css', 
            plugins_url( '/css/owl.carousel.min.css', __FILE__ ), 
            false,
            false,
            false 
        );
        wp_register_style( 
            'cwp_anythingslider_animate_css', 
            plugins_url( '/css/animate.css', __FILE__ ), 
            false,
            false,
            false 
        );
    }
}
}

function cwp_anythingslider_admin_scripts() {
       wp_enqueue_script( 
        'cwp_anythingslider_clearLocalStorage_js', 
        plugins_url( '/js/as_clear_local_storage.js', __FILE__ ), 
        false,
        false,
        false 
    );
}
add_action('et_builder_ready', 'cwp_anythingslider_admin_scripts');
add_action('admin_enqueue_scripts', 'cwp_anythingslider_admin_scripts');

?>