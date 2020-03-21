<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
$query = new WP_Query( $args );

$rowCount = $query -> found_posts;

if ( $query -> have_posts() ) {
        $psfw_row = 1;
        while ( $query -> have_posts() ) {
            $query -> the_post();

            $product_item_id = get_the_ID();
            $psfw_advance_option = get_post_meta( $product_item_id, 'psfw_advance_option', true );

            if (isset( $psfw_option['psfw_select_layout']) && $psfw_option['psfw_select_layout'] == 'carousel'){
                include(PSFWL_PATH . 'inc/frontend/content/psfw-carousel.php');
            }
            else {
                include(PSFWL_PATH . 'inc/frontend/content/psfw-slider.php');
            }
        }
} else {
    _e( 'No post found', PSFWL_TD );
}
wp_reset_postdata();