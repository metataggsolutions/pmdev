<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-image-hover-wrap">
    <?php include(PSFWL_PATH . 'inc/frontend/content/image.php'); ?>
</div>
<div class="psfw-circle-content psfw-hidden" <?php echo $inline_css; ?>>
    <div class="psfw-circle-content-inner">
        <div class="psfw-top-wrap psfw-hidden">
            <div class="psfw-content-container">
                <?php
                if ( isset( $psfw_option[ 'psfw_show_category' ] ) && $psfw_option[ 'psfw_show_category' ] == '1' ) { ?>
                    <div class="psfw-category-wrap">
                        <?php echo $psfw_fetch_category; ?>
                    </div>
                    <?php
                }
                include (PSFWL_PATH . '/inc/frontend/data/title.php');
                if ( isset( $psfw_option[ 'psfw_show_content' ] ) && $psfw_option[ 'psfw_show_content' ] == '1' ) {
                    ?>
                    <div class="psfw-content">
                        <?php echo $psfw_fetch_content; ?>
                    </div>
                    <?php
                }
                include (PSFWL_PATH . '/inc/frontend/data/price.php'); ?>
                <div class="psfw-buttons-collection psfw-clearfix">
                    <?php
                        include (PSFWL_PATH . '/inc/frontend/data/button-one.php');
                        include (PSFWL_PATH . '/inc/frontend/data/button-two.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>