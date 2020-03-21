<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-layout-outer-wrap">
    <div class ="psfw-post-option-wrap">
        <label><?php _e( 'Layout', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <select name="psfw_option[psfw_select_layout]" class="psfw-select-layout">
                <option value="carousel"  <?php if ( isset( $psfw_option[ 'psfw_select_layout' ] ) && $psfw_option[ 'psfw_select_layout' ] == 'carousel' ) echo 'selected=="selected"'; ?>><?php _e( 'Carousel', PSFWL_TD ) ?></option>
                <option value="slider"  <?php if ( isset( $psfw_option[ 'psfw_select_layout' ] ) && $psfw_option[ 'psfw_select_layout' ] == 'slider' ) echo 'selected=="selected"'; ?>><?php _e( 'Slider', PSFWL_TD ) ?></option>
            </select>
        </div>
    </div>
    <div class="psfw-slider-setting-wrap">
        <div class ="psfw-post-option-wrap">
            <label><?php _e( 'Slider Template', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <select name="psfw_option[psfw_slider_template]" class="psfw-slider-template">
                    <?php
                    $psfw_slider_names = array( 'Circular content template', 'Duet layer template', 'Focused template', 'Snazzy template');                    
                    $s = 1;
                    foreach ($psfw_slider_names as $psfw_slider_name){
                        ?>
                        <option value="template-<?php echo $s; ?>" <?php if ( ! empty( $psfw_option[ 'psfw_slider_template' ] ) ) selected( $psfw_option[ 'psfw_slider_template' ], 'template-' . $s ); ?>><?php echo $psfw_slider_name; ?>
                        </option>
                        <?php
                        $s ++;
                    }
                    ?>    
                </select>
            </div>
        </div>
        <div class="psfw-slider-demo psfw-preview-image">
            <?php
            for ( $cnt = 1; $cnt <= 4; $cnt ++ ) {
                if ( isset( $psfw_option[ 'psfw_slider_template' ] ) ) {
                    $option_value = $psfw_option[ 'psfw_slider_template' ];
                    $exploed_array = explode( '-', $option_value );
                    $cnt_num = $exploed_array[ 1 ];
                    if ( $cnt != $cnt_num ) {
                        $style = "style='display:none;'";
                    } else {
                        $style = '';
                    }
                } ?>
                <div class="psfw-slider-common" id="psfw-slider-demo-<?php echo $cnt; ?>" <?php if ( isset( $style ) ) echo $style; ?>>
                    <h4><?php _e( 'Template', PSFWL_TD ); ?> <?php echo $cnt; ?> <?php _e( 'Preview', PSFWL_TD ); ?></h4>
                    <img src="<?php echo PSFWL_IMG_DIR . '/demo/slider-templates/template-' . $cnt . '.png' ?>"/>
                </div>
            <?php } ?>
        </div>
        <div class ="psfw-post-option-wrap">
            <label><?php _e( 'Container Width', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <input type="number" name="psfw_option[psfw_slider_container_width]" class="psfw_slider_container_width" value="<?php
                    if ( isset( $psfw_option[ 'psfw_slider_container_width' ] ) ) {
                        echo $psfw_option['psfw_slider_container_width'];
                    } else {
                        echo '';
                    } ?>">
                    <p class="description"><?php _e( 'Enter the width of the container in px', PSFWL_TD ) ?></p>
            </div>
        </div>
        <?php 
            $abc = (isset($psfw_option[ 'psfw_slider_template' ]) && ($psfw_option[ 'psfw_slider_template'] == "template-3" ) ) ? "style='display:none;'" : "style='display:block;'"; 
        ?>
        <div class ="psfw-post-option-wrap psfw-content-align-wrapper" <?php echo $abc ?> >
            <label><?php _e( 'Content Wrap Align', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <label class="left">
                    <input type="radio" name="psfw_option[content_wrap_align]" class="content_wrap_align" value="left" <?php
                    echo (isset($psfw_option[ 'content_wrap_align' ] ) && $psfw_option[ 'content_wrap_align' ] == "left") ? "checked='checked'" : "";?>> <?php _e('Left', PSFWL_TD) ?>
                </label>
                <label class="right">
                    <input type="radio" name="psfw_option[content_wrap_align]" class="content_wrap_align" value="right" <?php
                    echo (isset($psfw_option[ 'content_wrap_align' ] ) && $psfw_option[ 'content_wrap_align' ] == "right") ? "checked='checked'" : "";?>> <?php _e('Right', PSFWL_TD) ?>
                </label>
                    <p class="description"><?php _e( 'Select the alignment for the content wrap of the slider', PSFWL_TD ) ?></p>
            </div>
        </div>
    </div>
    <div class="psfw-carousel-setting-wrap" style="display:none;">
        <div class ="psfw-post-option-wrap">
            <label><?php _e( 'Carousel Template', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <select name="psfw_option[psfw_carousel_template]" class="psfw-carousel-template">
                    <?php
                    $psfw_carousel_names = array( 'Persimmons Template', 'Jazzy Template', 'Nature Leafy Template', 'Bold Border Template');
                    $c = 1;
                    foreach ( $psfw_carousel_names as $psfw_carousel_name ) {
                        ?>
                        <option value="template-<?php echo $c; ?>" <?php if ( ! empty( $psfw_option[ 'psfw_carousel_template' ] ) ) selected( $psfw_option[ 'psfw_carousel_template' ], 'template-' . $c ); ?>><?php echo $psfw_carousel_name; ?></option>
                        <?php
                        $c ++;
                    }
                    ?>    
                </select>
            </div>
        </div>
        <div class="psfw-carousel-demo psfw-preview-image">
            <?php
            for ( $cnt = 1; $cnt <= 4; $cnt ++ ) {
                if ( isset( $psfw_option[ 'psfw_carousel_template' ] ) ) {
                    $option_value = $psfw_option[ 'psfw_carousel_template' ];
                    $exploed_array = explode( '-', $option_value );
                    $cnt_num = $exploed_array[ 1 ];
                    if ( $cnt != $cnt_num ) {
                        $style = "style='display:none;'";
                    } else {
                        $style = '';
                    }
                }
                ?>
                <div class="psfw-carousel-common" id="psfw-carousel-demo-<?php echo $cnt; ?>" <?php if ( isset( $style ) ) echo $style; ?>>
                    <h4><?php _e( 'Template', PSFWL_TD ); ?> <?php echo $cnt; ?> <?php _e( 'Preview', PSFWL_TD ); ?></h4>
                        <img src="<?php echo PSFWL_IMG_DIR . '/demo/carousel-templates/template-' . $cnt . '.png' ?>"/>
                </div>
            <?php } ?>
        </div>
        <div class ="psfw-post-option-wrap">
            <label> <?php _e( 'Slide Column', PSFWL_TD ); ?> </label>
            <div class="psfw-post-field-wrap">
                <input type="number" name="psfw_option[psfw_slide_column]" min="1" max="4" class="psfw-slide-column" value="<?php
                if ( isset( $psfw_option[ 'psfw_slide_column' ] ) ) {
                    echo esc_attr( $psfw_option[ 'psfw_slide_column' ] );
                } else {
                    echo '3';
                }
                ?>">
            </div>
        </div>
        <div class ="psfw-post-option-wrap">
            <label><?php _e( 'Slide Width', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <input type="text" name="psfw_option[psfw_slide_width]" class="psfw-slide-width" value="<?php
                if(isset($psfw_option['psfw_slide_width'])){
                    echo esc_attr( $psfw_option[ 'psfw_slide_width' ] );
                } else {
                    echo '350';
                }
                ?>">
            </div>
        </div>
    </div>
</div>