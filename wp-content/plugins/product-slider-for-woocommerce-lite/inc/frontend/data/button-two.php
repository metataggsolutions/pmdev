<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );

if ( isset( $psfw_option[ 'psfw_show_button_two' ] ) && $psfw_option[ 'psfw_show_button_two' ] == '1' ) {
    include (PSFWL_PATH . '/inc/frontend/data/inner/button-two-detail.php');
}

