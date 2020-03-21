<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" );

if(isset($atts['slug'])){
    $post_slug = $atts['slug'];
    $post_id = $this->get_id_from_slug($post_slug);
}else{
    $post_id = $atts['id']; // for visual composer compatible
}

$psfw_option = get_post_meta( $post_id, 'psfw_option', true );

$class_layout = 'psfw-layout-' . $psfw_option[ 'psfw_select_layout' ] . '-section';
$random_num = rand( 111111111, 999999999 );
if ( isset( $psfw_option[ 'psfw_select_layout' ] ) && $psfw_option[ 'psfw_select_layout' ] == 'carousel' ) {
    if ( isset( $psfw_option[ 'psfw_carousel_template' ] ) && $psfw_option[ 'psfw_carousel_template' ] == 'template-3' ) {
        $psfw_layout_class = 'psfw-car-' . $psfw_option[ 'psfw_carousel_template' ] . ' psfw-car-outer-wrap psfw-upper-arrow';
    } else {
        $psfw_layout_class = 'psfw-car-' . $psfw_option[ 'psfw_carousel_template' ] . ' psfw-car-outer-wrap';
    }
} else if ( isset( $psfw_option[ 'psfw_select_layout' ] ) && $psfw_option[ 'psfw_select_layout' ] == 'slider' ) {
    $psfw_layout_class = 'psfw-slider-wrapper' . ' psfw-slider-' . $psfw_option[ 'psfw_slider_template' ];
}

if ( isset( $psfw_option[ 'psfw_image_type' ] ) && $psfw_option[ 'psfw_image_type' ] == 'scroll' ) {
    $image_hover = ' psfw-scroll-wrapper';
} else if ( isset( $psfw_option[ 'psfw_image_type' ] ) && $psfw_option[ 'psfw_image_type' ] == 'swap' ) {
    $image_hover = ' psfw-swap-container';
}else{
    $image_hover='';
}

$content_wrap_align = (isset( $psfw_option[ 'content_wrap_align' ] ) && $psfw_option[ 'content_wrap_align' ]!='') ? ($psfw_option['content_wrap_align']): 'default';

if ( isset( $psfw_option[ 'psfw_slider_template' ] ) && !($psfw_option[ 'psfw_slider_template' ] == 'template-3' )) {
    if($content_wrap_align != 'default'){
        $content_align_class ="psfw_content_align_".$content_wrap_align;
    }else{
        $content_align_class ="";
    }
}
else{
    $content_align_class ="";
}

?>
<div class="<?php
if ( (isset( $psfw_option[ 'product_type' ] ) && $psfw_option[ 'product_type' ] == 'product') && class_exists( 'WooCommerce' ) ) {
    echo 'woocommerce';
}
?>
 psfw-product-outer-wrapper-<?php echo $random_num; ?> <?php echo $content_align_class ;?>  psfw-main-product-wrapper <?php echo esc_attr( $psfw_layout_class ); ?> <?php
    if ( (isset( $psfw_option[ 'psfw_show_button_one' ] ) && $psfw_option[ 'psfw_show_button_one' ] == '1') && $psfw_option[ 'psfw_show_button_two' ] != '1' ) {
        echo 'psfw-only-one-button';
    }
    if ( (isset( $psfw_option[ 'psfw_show_button_two' ] ) && $psfw_option[ 'psfw_show_button_two' ] == '1') && $psfw_option[ 'psfw_show_button_one' ] != '1' ) {
        echo 'psfw-only-one-button';
    }
    echo $image_hover;
    ?>" data-id="psfw_<?php
    echo rand( 1111111, 9999999 );
    ?>" <?php if ( isset( $psfw_option[ 'psfw_show_lightbox' ] ) && $psfw_option[ 'psfw_show_lightbox' ] == '1' ) { ?>
        data-layout="<?php echo $psfw_option[ 'psfw_select_layout' ]; ?>"
    <?php } ?>>
    <div class="<?php echo esc_attr( $class_layout ); ?> psfw-clearfix" 
        <?php if ( $psfw_option[ 'psfw_select_layout' ] == 'carousel' ) { ?>

            data-column = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_column' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_column' ] );
            } ?>"
            data-auto = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_auto' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_auto' ] );
            }
            ?>"
            data-speed = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_speed' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_speed' ] );
            }
            ?>"
            data-pager = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_pager' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_pager' ] );
            }
            ?>"
            data-template = "<?php
            if ( isset( $psfw_option[ 'psfw_carousel_template' ] ) ) {
                 echo esc_attr( $psfw_option[ 'psfw_carousel_template' ] );
            }
            ?>"
            data-width = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_width' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_width' ] );
            }
            ?>"
            <?php
        }
        if ($psfw_option[ 'psfw_select_layout' ] == 'slider' ) {
            ?>
            data-template = "<?php
            if ( isset( $psfw_option[ 'psfw_slider_template' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slider_template' ] );
            }
            ?>"
            data-controls = "<?php
            if ( isset( $psfw_option[ 'psfw_nav_controls' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_nav_controls' ] );
            }
            ?>"
            data-auto = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_auto' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_auto' ] );
            }
            ?>"
            data-orientation = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_type' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_type' ] );
            }
            ?>"
            data-autohover = "<?php
            if ( isset( $psfw_option[ 'psfw_pause_on_hover' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_pause_on_hover' ] );
            }
            ?>"
            data-speed = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_speed' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_speed' ] );
            }
            ?>"
            data-pager = "<?php
            if ( isset( $psfw_option[ 'psfw_slide_pager' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_pager' ] );
            }
            ?>"
            <?php
        }
        ?>>
            <?php
            if ( isset( $psfw_option[ 'psfw_post_excerpt' ] ) ) {
                $excerpt = $psfw_option[ 'psfw_post_excerpt' ];
            }
            if ( isset( $psfw_option[ 'psfw_post_number' ] ) ) {
                $post_number = $psfw_option[ 'psfw_post_number' ];
            } else {
                $post_number = 20;
            }
            if ( isset( $psfw_option[ 'psfw_select_order' ] ) ) {
                $order = $psfw_option[ 'psfw_select_order' ];
            } else {
                $order = 'DESC';
            }
            if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) ) {
                $order_by = $psfw_option[ 'psfw_select_orderby' ];
            } else {
                $order_by = 'date';
            }
            if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) ) {
                $status = $psfw_option[ 'psfw_select_post_status' ];
            } else {
                $status = 'publish';
            }
            if ( isset( $psfw_option[ 'psfw_date_format_post' ] ) ) {
                $date_format = $psfw_option[ 'psfw_date_format_post' ];
            }
            if ( isset( $psfw_option[ 'product_type' ] ) ) {
                $post_type = $psfw_option[ 'product_type' ];
            }
             /*
              * Condition for taxonomy
              */
            if ( isset( $psfw_option[ 'psfw_show_taxonomy_query' ] ) && $psfw_option[ 'psfw_show_taxonomy_query' ] == '1' ) {
                $tax = $psfw_option[ 'select_post_taxonomy' ];
                /*
                 * single taxonomy tax query
                 */
                if ( $psfw_option[ 'taxonomy_queries_type' ] == 'simple-taxonomy' ) {
                    if ( $psfw_option[ 'simple_taxonomy_terms' ] == '' ) {
                        $terms = get_terms( $tax, array( 'hide_empty' => false ) );
                        $term_ids = wp_list_pluck( $terms, 'term_id' );
                        $id = implode( ", ", array_keys( $term_ids ) );
                        $tax_query = array( array(
                                'taxonomy' => $tax,
                                'field' => 'term_id',
                                'terms' => array( $id )
                             ), );
                    } else {
                        $simple_term = $psfw_option[ 'simple_taxonomy_terms' ];
                        $tax_query = array( array(
                                'taxonomy' => $tax,
                                'field' => 'term_id',
                                'terms' => $simple_term
                        ), );
                    }
                }
            }
            $args = array(
                'post_type' => $post_type,
                'order' => $order,
                'orderby' => $order_by,
                'posts_per_page' => $post_number,
                'post_status' => $status
            );
            if ( !empty( $tax_query ) ) {
                $args[ 'tax_query' ] = $tax_query;
            }
            if ( !empty( $keyword ) ) {
                $args[ 's' ] = $keyword;
            }
            if ( !empty( $meta_query ) ) {
                $args[ 'meta_query' ] = $meta_query;
            }
            if ( !empty( $view_meta ) ) {
                $args[ 'meta_key' ] = $view_meta;
            }
            include(PSFWL_PATH . 'inc/frontend/psfw-template.php');
            ?>
    </div> 
</div>