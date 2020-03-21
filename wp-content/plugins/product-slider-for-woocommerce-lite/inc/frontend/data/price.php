<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );

if ( isset( $psfw_option[ 'psfw_show_price' ] ) && $psfw_option[ 'psfw_show_price' ] == '1' ) {
    include (PSFWL_PATH . '/inc/frontend/data/inner/price-detail.php');
}