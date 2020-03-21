<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); 
if ( isset( $psfw_option[ 'psfw_show_title' ] ) && $psfw_option[ 'psfw_show_title' ] == '1' ) {
    include (PSFWL_PATH . '/inc/frontend/data/inner/title-detail.php');
}