<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-customization-outer-wrap">
    <div class="psfw-post-option-wrap">
        <label><?php _e( 'Auto', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <select name="psfw_option[psfw_slide_auto]" class="psfw-slide-auto">
                <option value="true"  <?php if ( isset( $psfw_option[ 'psfw_slide_auto' ] ) && $psfw_option[ 'psfw_slide_auto' ] == 'true' ) echo 'selected=="selected"'; ?>><?php _e( 'True', PSFWL_TD ) ?></option>
                <option value="false"  <?php if ( isset( $psfw_option[ 'psfw_slide_auto' ] ) && $psfw_option[ 'psfw_slide_auto' ] == 'false' ) echo 'selected=="selected"'; ?>><?php _e( 'False', PSFWL_TD ) ?></option>
            </select>
        </div>
    </div>
    <div class ="psfw-post-option-wrap">
        <label><?php _e( 'Auto Play Timeout', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <input type="number" name="psfw_option[psfw_slide_speed]" class="psfw-slide-speed" value="<?php
            if ( isset( $psfw_option[ 'psfw_slide_speed' ] ) ) {
                echo esc_attr( $psfw_option[ 'psfw_slide_speed' ] );
            } else {
                echo '1000';
            }
            ?>">
        </div>
    </div>
    <div class="psfw-post-option-wrap">
		<label><?php _e( 'Number of Post', PSFWL_TD ); ?></label>
		<div class="psfw-post-field-wrap">
    		<input type="number" class="psfw-post-number" min="1" name="psfw_option[psfw_post_number]"  value="<?php
		        if ( isset( $psfw_option[ 'psfw_post_number' ] ) ) {
		            echo $psfw_option[ 'psfw_post_number' ];
		        } else {
		            echo '5';
		        }
		        ?>"/>
    		<p class="description"><?php _e( 'Enter the excerpt length for post content', PSFWL_TD ) ?></p>
		</div>
	</div>
</div>