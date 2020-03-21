<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-image-hover-wrap">
    <div class="psfw-image-second-container">
        <?php
        include(PSFWL_PATH . 'inc/frontend/content/image.php');
        ?>
        <div class="psfw-top-container">
            <?php
            if ( (isset( $psfw_option[ 'product_type' ] ) && $psfw_option[ 'product_type' ] == 'product') && class_exists( 'WooCommerce' ) ) {
                if ( isset( $psfw_option[ 'psfw_show_wishlist' ] ) && $psfw_option[ 'psfw_show_wishlist' ] == '1' ) { ?>
                    <div class="psfw-wishlist-wrap psfw-icon-hover-wrap psfw-button"> <?php
                        if ( (isset( $psfw_option[ 'product_type' ] ) && $psfw_option[ 'product_type' ] == 'product') && class_exists( 'WooCommerce' ) ) {
                            $this -> psfw_wishlist_button($product_item_id);
                        } ?>
                    </div> <?php
                }
            } 
        ?>
        </div>
    </div>
    <?php  include (PSFWL_PATH . '/inc/frontend/data/ribbon.php'); ?>
</div>
<div class="psfw-content-inner-wrap">
    <div class="psfw-top-wrap">
        <?php
        if(isset( $psfw_option[ 'psfw_show_category' ] ) && $psfw_option[ 'psfw_show_category' ] == '1' ) { ?>
            <div class="psfw-category-wrap">
                <?php echo $psfw_fetch_category; ?>
            </div> <?php
        }
        include (PSFWL_PATH . '/inc/frontend/data/title.php');
        if ( isset( $psfw_option[ 'psfw_show_content' ] ) && $psfw_option[ 'psfw_show_content' ] == '1' ) { ?>
            <div class="psfw-content">
                <?php
                echo $psfw_fetch_content;
                ?>
            </div> <?php
        } ?>
        <div class="psfw-price-review-wrap"><?php 
            include (PSFWL_PATH . '/inc/frontend/data/price.php');
            ?>
        </div>
        <div class="psfw-bottom-hover-wrap">
            <div class="psfw-buttons-collection psfw-clearfix">
                <?php
                include (PSFWL_PATH . '/inc/frontend/data/button-one.php');
                include (PSFWL_PATH . '/inc/frontend/data/button-two.php');
                ?>
            </div>
        </div>
    </div>
</div>