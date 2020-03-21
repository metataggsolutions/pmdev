<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); 

$image = isset( $psfw_option[ 'psfw_image_size' ] ) ? esc_attr( $psfw_option[ 'psfw_image_size' ] ) : 'full';
$image_type = isset( $psfw_option[ 'psfw_image_type' ] ) ? esc_attr( $psfw_option[ 'psfw_image_type' ] ) : 'normal';
$thumbnail_url = get_the_post_thumbnail_url( $product_item_id, $image );
?>
<div class="psfw-image">
    <?php
    if ( $image_type == 'normal' || $image_type == 'scroll' ) {
        if ( has_post_thumbnail( $product_item_id ) ) {
            if ( isset( $psfw_option[ 'psfw_show_link_image' ] ) && $psfw_option[ 'psfw_show_link_image' ] == '1' ) {
                ?>
                <a href="<?php echo get_permalink( $product_item_id ); ?>" target="_blank">
                    <img src="<?php echo $thumbnail_url; ?>">
                </a>
                <?php
            } else {
                ?>
                <img src="<?php echo $thumbnail_url; ?>">
                <?php
            }
        }
    } else {
        if ( $psfw_option[ 'psfw_select_layout' ] == 'carousel' ) {
            if ( has_post_thumbnail( $product_item_id ) ) {
                if ( isset( $psfw_advance_option[ 'swap_image_url' ] ) && $psfw_advance_option[ 'swap_image_url' ] != '' ) {
                    $bottom_image_url = $psfw_advance_option[ 'swap_image_url' ];
                    $bottom_image_id = $this -> psfw_get_image_id( $bottom_image_url );
                    $bottom_image_link = wp_get_attachment_url( $bottom_image_id, $image );
                    ?>
                    <img class="psfw-bottom-image" src="<?php echo esc_url( $bottom_image_link ); ?>" />
                    <img class="psfw-top-image" src="<?php echo esc_url( $thumbnail_url ); ?>" />
                    <?php
                } else {
                    if ( isset( $psfw_option[ 'psfw_show_link_image' ] ) && $psfw_option[ 'psfw_show_link_image' ] == '1' ) {
                        ?>
                        <a href="<?php echo get_permalink( $product_item_id ); ?>" target="_blank">
                            <img src="<?php echo $thumbnail_url; ?>">
                        </a>
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo $thumbnail_url; ?>">
                        <?php
                    }
                }
            }
        } else {
            if ( has_post_thumbnail( $product_item_id ) ) {
                if ( isset( $psfw_option[ 'psfw_show_link_image' ] ) && $psfw_option[ 'psfw_show_link_image' ] == '1' ) {
                    ?>
                    <a href="<?php echo get_permalink( $product_item_id ); ?>" target="_blank">
                        <img src="<?php echo $thumbnail_url; ?>">
                    </a>
                    <?php
                } else {
                    ?>
                    <img src="<?php echo $thumbnail_url; ?>">
                    <?php
                }
            }
        }
    }
    ?>
</div>