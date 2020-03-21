<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
$custom_prefix = "psfw_option[psfw_custom][$custom_key]";
$value_prefix = $psfw_option[ 'psfw_custom' ][ $custom_key ];
?>
<div class="psfw-each-meta-container-wrap">
    <div class = "psfw-post-option-wrap">
        <label><?php _e( 'Meta Keys', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <label><input type="radio" value="pre-available" name="<?php echo esc_attr( $custom_prefix . '[multiple_meta_key_type]' ); ?>"  <?php
                if ( isset( $value_prefix[ 'multiple_meta_key_type' ] ) ) {
                    checked( $value_prefix[ 'multiple_meta_key_type' ], 'pre-available' );
                }
                ?> class="psfw-multiple-meta-keys"/><?php _e( "Pre Available Meta Keys", PSFWL_TD ); ?></label>
            <label><input type="radio" value="other" name="<?php echo esc_attr( $custom_prefix . '[multiple_meta_key_type]' ); ?>" <?php
                if ( isset( $value_prefix[ 'multiple_meta_key_type' ] ) ) {
                    checked( $value_prefix[ 'multiple_meta_key_type' ], 'other' );
                }
                ?> class="psfw-multiple-meta-keys"/><?php _e( 'Other Meta Keys', PSFWL_TD ); ?></label>
            <div class ="psfw-pre-multiple-key-wrap" <?php if ( isset( $value_prefix[ 'multiple_meta_key_type' ] ) && $value_prefix[ 'multiple_meta_key_type' ] == 'other' ) { ?> style="display: none;" <?php } ?>>
                <?php
                $post_meta_table = $wpdb -> postmeta;
                $meta_keys = $wpdb -> get_results( "SELECT DISTINCT(meta_key) FROM $post_meta_table" );
                ?>
                <select class="psfw-pre-multiple-metakey" name="<?php echo esc_attr( $custom_prefix . '[psfw_pre_multiple_meta_key]' ); ?>">
                    <option value=""><?php _e( 'None' ); ?></option>
                    <?php
                    foreach ( $meta_keys as $meta_key ) {
                        ?>
                        <option value="<?php echo $meta_key -> meta_key; ?>"
                        <?php
                        if ( isset( $value_prefix[ 'psfw_pre_multiple_meta_key' ] ) && $value_prefix[ 'psfw_pre_multiple_meta_key' ] == $meta_key -> meta_key )
                            echo 'selected == "selected"';
                        ?>><?php echo $meta_key -> meta_key; ?></option>
                                <?php
                            }
                            ?>
                </select>
            </div>
            <div class="psfw-multiple-other-key-wrap" <?php if ( isset( $value_prefix[ 'multiple_meta_key_type' ] ) && $value_prefix[ 'multiple_meta_key_type' ] == 'pre-available' ) { ?> style="display: none;" <?php } ?>>
                <input type="text" class="psfw-multiple-other-key"  name="<?php echo esc_attr( $custom_prefix . '[psfw_multiple_other_key]' ); ?>"  value="<?php
                if ( isset( $value_prefix[ 'psfw_multiple_other_key' ] ) ) {
                    echo esc_attr( $value_prefix[ 'psfw_multiple_other_key' ] );
                }
                ?>"/>
            </div>
        </div>
    </div>
    <div class ="psfw-post-option-wrap">
        <label><?php _e( 'Meta Value', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <input type="text" class="psfw-multiple-meta-value"  name="<?php echo esc_attr( $custom_prefix . '[psfw_multiple_meta_value]' ); ?>"  value="
            <?php
            if ( isset( $value_prefix[ 'psfw_multiple_meta_value' ] ) ) {
                echo esc_attr( $value_prefix[ 'psfw_multiple_meta_value' ] );
            }
            ?>"/>
        </div>
    </div>
    <div class ="psfw-post-option-wrap">
        <label><?php _e( 'Compare', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <select name="<?php echo esc_attr( $custom_prefix . '[psfw_compare_operator]' ); ?>" class="psfw-compare-post">
                <option value="=" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == '=' ) echo 'selected=="selected"'; ?>><?php _e( 'Equal To', PSFWL_TD ) ?></option>
                <option value="!=" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == '!=' ) echo 'selected=="selected"'; ?>><?php _e( 'Not Equal To', PSFWL_TD ) ?></option>
                <option value=">" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == '>' ) echo 'selected=="selected"'; ?>><?php _e( 'Greater Than', PSFWL_TD ) ?></option>
                <option value=">=" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == '>=' ) echo 'selected=="selected"'; ?>><?php _e( 'Greater Than Equal To', PSFWL_TD ) ?></option>
                <option value="<" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == '<' ) echo 'selected=="selected"'; ?>><?php _e( 'Smaller Than', PSFWL_TD ) ?></option>
                <option value="<=" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == '<=' ) echo 'selected=="selected"'; ?>><?php _e( 'Smaller Than Equal To', PSFWL_TD ) ?></option>
                <option value="LIKE" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'LIKE' ) echo 'selected=="selected"'; ?>><?php _e( 'Like', PSFWL_TD ) ?></option>
                <option value="NOT LIKE" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'NOT LIKE' ) echo 'selected=="selected"'; ?>><?php _e( 'Not Like', PSFWL_TD ) ?></option>
                <option value="IN" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'IN' ) echo 'selected=="selected"'; ?>><?php _e( 'In', PSFWL_TD ) ?></option>
                <option value="NOT IN" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'NOT IN' ) echo 'selected=="selected"'; ?>><?php _e( 'Not In', PSFWL_TD ) ?></option>
                <option value="BETWEEN" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'BETWEEN' ) echo 'selected=="selected"'; ?>><?php _e( 'Between', PSFWL_TD ) ?></option>
                <option value="NOT BETWEEN" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'NOT BETWEEN' ) echo 'selected=="selected"'; ?>><?php _e( 'Not Between', PSFWL_TD ) ?></option>
                <option value="EXISTS" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'EXISTS' ) echo 'selected=="selected"'; ?>><?php _e( 'Exists', PSFWL_TD ) ?></option>
                <option value="NOT EXISTS" <?php if ( isset( $value_prefix[ 'psfw_compare_operator' ] ) && $value_prefix[ 'psfw_compare_operator' ] == 'NOT EXISTS' ) echo 'selected=="selected"'; ?>><?php _e( 'Not Exists', PSFWL_TD ) ?></option>
            </select>
        </div>
    </div>
    <div class="psfw-custom-type-main-wrap">
        <div class ="psfw-post-option-wrap">
            <label for="psfw-show-type-check" class="psfw-show-type">
                <?php _e( 'Type', PSFWL_TD ); ?>
            </label>
            <div class="psfw-post-field-wrap">
                <label class="psfw-switch">
                    <input type="checkbox" class="psfw-show-type-filter psfw-checkbox" value="<?php
                    if ( isset( $value_prefix[ 'psfw_show_type_filter' ] ) ) {
                        echo esc_attr( $value_prefix[ 'psfw_show_type_filter' ] );
                    } else {
                        echo '0';
                    }
                    ?>" name="<?php echo esc_attr( $custom_prefix . '[psfw_show_type_filter]' ); ?>" <?php if ( isset( $value_prefix[ 'psfw_show_type_filter' ] ) && $value_prefix[ 'psfw_show_type_filter' ] == '1' ) { ?>checked="checked"<?php } ?>/>
                    <div class="psfw-slider round"></div>
                </label>
                <p class="description"> <?php _e( 'Enable to filter logo by custom field type', PSFWL_TD ) ?></p>
                <div class="psfw-type-filter-wrap" <?php if ( isset( $value_prefix[ 'psfw_show_type_filter' ] ) && $value_prefix[ 'psfw_show_type_filter' ] == '1' ) { ?> style="display: block;" <?php } else { ?> style="display: none;" <?php } ?>>
                    <select name="<?php echo esc_attr( $custom_prefix . '[psfw_field_compare_type]' ); ?>" class="psfw-field-compare-type">
                        <option value="NUMERIC" <?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'NUMERIC' ) echo 'selected=="selected"'; ?>><?php _e( 'Numeric', PSFWL_TD ) ?></option>
                        <option value="BINARY" <?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'BINARY' ) echo 'selected=="selected"'; ?>><?php _e( 'Binary', PSFWL_TD ) ?></option>
                        <option value="CHAR"  <?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'CHAR' ) echo 'selected=="selected"'; ?>><?php _e( 'Char', PSFWL_TD ) ?></option>
                        <option value="DATETIME" <?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'DATETIME' ) echo 'selected=="selected"'; ?>><?php _e( 'Date Time', PSFWL_TD ) ?></option>
                        <option value="DECIMAL" <?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'DECIMAL' ) echo 'selected=="selected"'; ?> ><?php _e( 'Decimal', PSFWL_TD ) ?></option>
                        <option value="SIGNED" <?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'SIGNED' ) echo 'selected=="selected"'; ?>><?php _e( 'Signed', PSFWL_TD ) ?></option>
                        <option value="TIME" <?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'TIME' ) echo 'selected=="selected"'; ?>><?php _e( 'Time', PSFWL_TD ) ?></option>
                        <option value="UNSIGNED"<?php if ( isset( $value_prefix[ 'psfw_field_compare_type' ] ) && $value_prefix[ 'psfw_field_compare_type' ] == 'UNSIGNED' ) echo 'selected=="selected"'; ?> ><?php _e( 'Unsigned', PSFWL_TD ) ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>