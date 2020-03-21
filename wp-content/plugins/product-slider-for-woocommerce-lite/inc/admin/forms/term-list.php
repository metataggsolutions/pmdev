<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
$name_prefix = "psfw_option[psfw_blog][$blog_key]";
$value_prefix = $psfw_option[ 'psfw_blog' ][ $blog_key ];
?>
<div class="psfw-each-taxonomy-wrap">
    <div class="psfw-delete-query">
        <span class="dashicons dashicons-trash"></span>
    </div>
    <div class="psfw-post-option-wrap">
        <label><?php _e( 'Taxonomy/Category', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <select name="<?php echo esc_attr( $name_prefix . '[multiple_post_taxonomy]' ); ?>" class="psfw-multiple-taxonomy">
                <option value="select" <?php if ( isset( $value_prefix[ 'multiple_post_taxonomy' ] ) && $value_prefix[ 'multiple_post_taxonomy' ] == 'select' ) echo 'selected=="selected"'; ?>><?php echo _e( 'Choose Taxonomy', PSFWL_TD ); ?></option>
                <?php
                $product_post_type = (isset( $psfw_option[ 'product_type' ] )) ? esc_attr( $psfw_option[ 'product_type' ] ) : 'psfw_product_manager';
                $taxonomies = get_object_taxonomies( $product_post_type, 'objects' );
                foreach ( $taxonomies as $tax ) {
                    $value = $tax -> name;
                    $label = $tax -> label;
                    ?>
                    <option value="<?php echo $value; ?>" <?php if ( isset( $value_prefix[ 'multiple_post_taxonomy' ] ) && $value_prefix[ 'multiple_post_taxonomy' ] == $value ) echo 'selected=="selected"'; ?>><?php echo $label; ?></option>
                    <?php
                }
                ?>
            </select>
            <p class="description"><?php _e( 'Please select the product type first', PSFWL_TD ); ?></p>
        </div>
    </div>
    <div class="psfw-post-option-wrap">
        <label><?php _e( 'Terms', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap psfw-multiple-select">
            <select name="<?php echo esc_attr( $name_prefix . '[multiple_taxonomy_terms][]' ); ?>" multiple="multiple" class="psfw-hierarchy-taxonomy-term">
                <option value=""><?php echo 'Choose Terms'; ?></option>
                <?php
                $select_tax = $value_prefix[ 'multiple_post_taxonomy' ];
                if ( isset( $value_prefix[ 'multiple_post_taxonomy' ] ) ) {
                    echo $this -> psfw_fetch_category_list( $select_tax, $value_prefix[ 'multiple_taxonomy_terms' ] );
                }
                ?>
            </select>
        </div>
    </div>
    <div class="psfw-post-option-wrap">
        <label for="psfw-enable-operator" class="psfw-enable-operator">
            <?php _e( 'Operator', PSFWL_TD ); ?>
        </label>
        <div class="psfw-post-field-wrap">
            <label class="psfw-switch">
                <input type="checkbox" class="psfw-show-operator psfw-checkbox" value="<?php
                if ( isset( $value_prefix[ 'psfw_enable_operator' ] ) ) {
                    echo esc_attr( $value_prefix[ 'psfw_enable_operator' ] );
                } else {
                    echo '0';
                }
                ?>" name="<?php echo esc_attr( $name_prefix . '[psfw_enable_operator]' ); ?>" <?php if ( isset( $value_prefix[ 'psfw_enable_operator' ] ) && $value_prefix[ 'psfw_enable_operator' ] == '1' ) { ?>checked="checked"<?php } ?>/>
                <div class="psfw-slider round"></div>
            </label>
            <p class="description"> <?php _e( 'Enable operator to test and filter the post', PSFWL_TD ) ?></p>
            <div class="psfw-operator-inner-wrap" <?php if ( isset( $value_prefix[ 'psfw_enable_operator' ] ) && $value_prefix[ 'psfw_enable_operator' ] == '1' ) { ?> style="display: block;" <?php } else { ?> style="display: none;" <?php } ?>>
                <select name="<?php echo esc_attr( $name_prefix . '[psfw_terms_operator]' ); ?>" class="psfw-terms-operator">
                    <option value="IN" <?php if ( isset( $value_prefix[ 'psfw_terms_operator' ] ) && $value_prefix[ 'psfw_terms_operator' ] == 'IN' ) echo 'selected=="selected"'; ?>><?php _e( 'IN', PSFWL_TD ) ?></option>
                    <option value="NOT IN" <?php if ( isset( $value_prefix[ 'psfw_terms_operator' ] ) && $value_prefix[ 'psfw_terms_operator' ] == 'NOT IN' ) echo 'selected=="selected"'; ?>><?php _e( 'NOT IN', PSFWL_TD ) ?></option>
                    <option value="AND" <?php if ( isset( $value_prefix[ 'psfw_terms_operator' ] ) && $value_prefix[ 'psfw_terms_operator' ] == 'AND' ) echo 'selected=="selected"'; ?>><?php _e( 'AND', PSFWL_TD ) ?></option>
                    <option value="EXISTS" <?php if ( isset( $value_prefix[ 'psfw_terms_operator' ] ) && $value_prefix[ 'psfw_terms_operator' ] == 'EXISTS' ) echo 'selected=="selected"'; ?>><?php _e( 'EXISTS', PSFWL_TD ) ?></option>
                    <option value="NOT EXISTS" <?php if ( isset( $value_prefix[ 'psfw_terms_operator' ] ) && $value_prefix[ 'psfw_terms_operator' ] == 'NOT EXISTS' ) echo 'selected=="selected"'; ?>><?php _e( 'NOT EXISTS', PSFWL_TD ) ?></option>
                </select>
            </div>
        </div>
    </div>
</div>