<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); 

if ( isset( $psfw_option[ 'psfw_show_button_one' ] ) && $psfw_option[ 'psfw_show_button_one' ] == '1' ) {
    include (PSFWL_PATH . '/inc/frontend/data/inner/button-one-detail.php');
}