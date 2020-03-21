<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );

if ( isset( $psfw_option[ 'psfw_show_link_image' ] ) && $psfw_option[ 'psfw_show_link_image' ] == '1' ) { ?>
    <a href="<?php echo get_permalink( $product_item_id ); ?>" target="_blank" class="psfw-image-hover-wrap">
        <!-- <div class="psfw-image-hover-wrap"> -->
            <div class="psfw-image-second-container">
                <?php include(PSFWL_PATH . 'inc/frontend/content/image.php'); ?>
                <div class="psfw-top-hover-wrap">
                    <div class="psfw-buttons-collection psfw-clearfix">
                        <?php
                        include (PSFWL_PATH . '/inc/frontend/data/button-one.php');
                        include (PSFWL_PATH . '/inc/frontend/data/button-two.php');
                        ?>
                    </div>
                </div>
            </div>
            <?php  include (PSFWL_PATH . '/inc/frontend/data/ribbon.php'); ?>
        <!-- </div> -->
    </a>
<?php } else{ ?>

    <div class="psfw-image-hover-wrap">
        <div class="psfw-image-second-container">
            <?php include(PSFWL_PATH . 'inc/frontend/content/image.php'); ?>
            <div class="psfw-top-hover-wrap">
                <div class="psfw-buttons-collection psfw-clearfix">
                    <?php
                    include (PSFWL_PATH . '/inc/frontend/data/button-one.php');
                    include (PSFWL_PATH . '/inc/frontend/data/button-two.php');
                    ?>
                </div>
            </div>
        </div>
        <?php  include (PSFWL_PATH . '/inc/frontend/data/ribbon.php'); ?>
    </div>

<?php } ?>



<div class="psfw-details-wrap">
    <?php
    if ( isset( $psfw_option[ 'psfw_show_category' ] ) && $psfw_option[ 'psfw_show_category' ] == '1' ) { ?>
        <div class="psfw-category-wrap">
            <?php echo $psfw_fetch_category; ?>
        </div>
    <?php
    }
    include (PSFWL_PATH . '/inc/frontend/data/title.php');
    if ( isset( $psfw_option[ 'psfw_show_content' ] ) && $psfw_option[ 'psfw_show_content' ] == '1' ) { ?>
        <div class="psfw-content">
            <?php echo $psfw_fetch_content; ?>
        </div>
     <?php
    }
    include (PSFWL_PATH . '/inc/frontend/data/price.php');
    ?>
</div>