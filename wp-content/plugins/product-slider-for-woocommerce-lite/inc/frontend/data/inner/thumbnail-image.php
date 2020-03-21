<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); 
$id = rand( 1111111, 9999999 );
?>
<div class="psfw-lightbox-image-wrapper" data-id="<?php echo $id; ?>">
    <?php
    if ( has_post_thumbnail( $product_item_id ) ) {
        ?>
        <div class="psfw-lightbox-slider-wrap">
            <?php
            echo get_the_post_thumbnail( $product_item_id, 'large' );
            ?>
        </div>
        <?php
    }
    if ( (isset( $psfw_option[ 'product_type' ] ) && $psfw_option[ 'product_type' ] == 'product') && class_exists( 'WooCommerce' ) ) {
        $product = new WC_product( $product_item_id );
        //$attachment_ids = $product -> get_gallery_attachment_ids(); deprecated since version 3.0!
        $attachment_ids = $product -> get_gallery_image_ids();
        foreach ( $attachment_ids as $attachment_id ) {
            $image_link = wp_get_attachment_url( $attachment_id, 'large' );
            ?>
            <div class="psfw-lightbox-slider-wrap">
                <img src="<?php echo $image_link; ?>" alt="">
            </div>
            <?php
        }
    }
    ?>
</div>
<?php
if ( (isset( $psfw_option[ 'product_type' ] ) && $psfw_option[ 'product_type' ] == 'product') && class_exists( 'WooCommerce' ) ) {
    $product = new WC_product( $product_item_id );
    //$attachment_ids = $product -> get_gallery_attachment_ids();
    $attachment_ids = $product -> get_gallery_image_ids();

    $l = 1;
    if ( isset( $attachment_id ) ) {
        ?>
        <div class="psfw-lightbox-thumb-wrap">
            <div class="psfw-thumbnail-slider" data-id="<?php echo $id; ?>" id="psfw-pager-<?php echo $id; ?>">
                <?php
                if ( has_post_thumbnail( $product_item_id ) ) {
                    ?>
                    <div class="psfw-each-thumb">
                        <a data-slide-index="0" href="">
                            <?php
                            echo get_the_post_thumbnail( $product_item_id, 'thumbnail' );
                            ?>
                        </a>
                    </div>
                    <?php
                }
                foreach ( $attachment_ids as $attachment_id ) {
                    $image_link = wp_get_attachment_url( $attachment_id, 'thumbnail' );
                    ?>
                    <div class="psfw-each-thumb">
                        <a data-slide-index="<?php echo $l; ?>" href="">
                            <img src="<?php echo $image_link; ?>" alt="">
                        </a>
                    </div>
                    <?php
                    $l ++;
                }
                ?>
            </div>
        </div>
        <?php
    }
}