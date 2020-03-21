<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-price">
    <?php
    if((isset($psfw_option['product_type']) && $psfw_option['product_type'] == 'product') && class_exists('WooCommerce')){
        if(isset($psfw_option['product_price']) && $psfw_option['product_price'] == 'sale_price') {
            woocommerce_template_loop_price();
        } else {
            $product = new WC_Product( $product_item_id );
            echo wc_price( $product -> get_price() );
        }
    } 
    ?>
</div>