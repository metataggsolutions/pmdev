<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-inner-wrap" data-post_id="<?php echo $product_item_id; ?>" data-shortcode_id="<?php echo $post_id; ?>">
    <div class="psfw-inner-wrap-contain">
        <?php
        $psfw_fetch_category = $this -> psfw_fetch_category($post_id, $product_item_id, $psfw_option['select_post_taxonomy']);
        $psfw_fetch_content = $this -> psfw_fetch_content($product_item_id, $psfw_option['psfw_post_excerpt']);
        if(isset( $psfw_option[ 'psfw_carousel_template' ] ) && $psfw_option[ 'psfw_carousel_template' ] != ' ') {
            include(PSFWL_PATH . 'inc/frontend/template/corousel/'.$psfw_option[ 'psfw_carousel_template' ].'.php');
        } else {
            include(PSFWL_PATH . 'inc/frontend/template/corousel/template-1.php');
        }
        ?>
    </div>
</div>
