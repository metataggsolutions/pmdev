<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-image-hover-wrap">
    <div class="psfw-image-second-container">
        <?php include(PSFWL_PATH . 'inc/frontend/content/image.php'); ?>
    </div>
    <?php include (PSFWL_PATH . '/inc/frontend/data/ribbon.php'); ?>
</div>
<div class="psfw-content-inner-wrap">
    <div class="psfw-top-wrap">
        <?php
        if ( isset( $psfw_option[ 'psfw_show_category' ] ) && $psfw_option[ 'psfw_show_category' ] == '1' ) { ?>
            <div class="psfw-category-wrap">
                <?php echo $psfw_fetch_category; ?>
            </div> <?php
        }
        include (PSFWL_PATH . '/inc/frontend/data/title.php');
        if ( isset( $psfw_option[ 'psfw_show_content' ] ) && $psfw_option[ 'psfw_show_content' ] == '1' ) { ?>
            <div class="psfw-content"> <?php
                echo $psfw_fetch_content; ?>
            </div> <?php
        }
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