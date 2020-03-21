<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
global $post;
$post_id = $post -> ID;
$psfw_advance_option = get_post_meta( $post_id, 'psfw_advance_option', true );
?>
<div class="psfw-post-option-wrap">
    <label for="button-one-link" class="psfw-button-one-link"><?php _e( 'Call To Action Button One Link', PSFWL_TD ); ?></label>
    <div class="psfw-post-field-wrap">
        <input type="text" class="psfw-button1-custom-link" name="psfw_advance_option[button_one_custom_link]" value="<?php
        if ( isset( $psfw_advance_option[ 'button_one_custom_link' ] ) ) {
            echo esc_attr( $psfw_advance_option[ 'button_one_custom_link' ] );
        }
        ?>">
    </div>
    <div class="psfw-tooltip-icon">
        <span class="dashicons dashicons-info"></span>
        <span class="psfw-tooltip-info"><?php _e( 'This is used to show the individual custom link for call to action button one', PSFWL_TD ); ?></span>
    </div>
</div>
<div class="psfw-post-option-wrap">
    <label for="custom-button-link" class="psfw-custom2-link"><?php _e( 'Call To Action Button Two Link', PSFWL_TD ); ?></label>
    <div class="psfw-post-field-wrap">
        <input type="text" class="psfw-custom-button2-link" name="psfw_advance_option[button_two_custom_link]" value="<?php
        if ( isset( $psfw_advance_option[ 'button_two_custom_link' ] ) ) {
            echo esc_attr( $psfw_advance_option[ 'button_two_custom_link' ] );
        }
        ?>">
    </div>
    <div class="psfw-tooltip-icon">
        <span class="dashicons dashicons-info"></span>
        <span class="psfw-tooltip-info"><?php _e( 'This is used to show the individual custom link for call to action button two', PSFWL_TD ); ?></span>
    </div>
</div>
<div class="psfw-post-option-wrap">
    <label for="swap-image" class="psfw-swap-setting"><?php _e( 'Upload Swap on Hover Image', PSFWL_TD ); ?></label>
    <div class="psfw-post-field-wrap">
        <input type="text" class="psfw-swap-image-url" name="psfw_advance_option[swap_image_url]"  value="<?php
        if ( isset( $psfw_advance_option[ 'swap_image_url' ] ) ) {
            echo esc_attr( $psfw_advance_option[ 'swap_image_url' ] );
        }
        ?>" />
        <input type="button" class="psfw-swap-image-url-button button-secondary" name="psfw_swap_image"  value="<?php _e( 'Upload Image', PSFWL_TD ); ?>" size="25"/>
        <div class="psfw-image-preview">
            <img  class="psfw-swap-image" src="<?php
            if ( isset( $psfw_advance_option[ 'swap_image_url' ] ) ) {
                echo esc_attr( $psfw_advance_option[ 'swap_image_url' ] );
            }
            ?>" alt="" width="200">
        </div>
    </div>
    <div class="psfw-tooltip-icon">
        <span class="dashicons dashicons-info"></span>
        <span class="psfw-tooltip-info"><?php _e( 'This is used to show the swap image for Swap On Hover animation type.', PSFWL_TD ); ?></span>
    </div>
</div>