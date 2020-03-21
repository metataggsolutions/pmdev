<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
$post_type = sanitize_text_field( $_POST[ 'post_type' ] );
$key = $this -> psfw_generate_random_string( 15 );
$blog_key = 'blog_' . $key;
$blog_prefix = "psfw_option[psfw_blog][$blog_key]";
?>
<div class="psfw-each-taxonomy-wrap">
    <div class="psfw-delete-query">
        <span class="dashicons dashicons-trash"></span>
    </div>
    <div class ="psfw-post-option-wrap">
        <label><?php _e( 'Taxonomy/Category', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <select name="<?php echo esc_attr( $blog_prefix . '[multiple_post_taxonomy]' ); ?>" class="psfw-multiple-taxonomy">
                <option value="select" ><?php echo _e( 'Choose Taxonomy', PSFWL_TD ); ?></option>
                <?php
                $taxonomies = get_object_taxonomies( $post_type, 'objects' );
                foreach ( $taxonomies as $tax ) {
                    $value = $tax -> name;
                    $label = $tax -> label;
                    ?>
                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php
                }
                ?>
            </select>
            <p class="description"><?php _e( 'Please select the product type first', PSFWL_TD ); ?></p>
        </div>
    </div>
    <div class ="psfw-post-option-wrap">
        <label><?php _e( 'Terms', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap psfw-multiple-select">
            <select name="<?php echo esc_attr( $blog_prefix . '[multiple_taxonomy_terms][]' ); ?>" multiple="multiple" class="psfw-hierarchy-taxonomy-term">
                <option value="" ><?php echo _e( 'Select Taxonomy First', PSFWL_TD ); ?></option>
            </select>
        </div>
    </div>
    <div class ="psfw-post-option-wrap">
        <label for="psfw-enable-operator" class="psfw-enable-operator">
            <?php _e( 'Operator', PSFWL_TD ); ?>
        </label>
        <div class="psfw-post-field-wrap">
            <label class="psfw-switch">
                <input type="checkbox" class="psfw-show-operator psfw-checkbox" value="0" name="<?php echo esc_attr( $blog_prefix . '[psfw_enable_operator]' ); ?>"/>
                <div class="psfw-slider round"></div>
            </label>
            <p class="description"> <?php _e( 'Enable operator to test and filter the post', PSFWL_TD ) ?></p>
            <div class="psfw-operator-inner-wrap" style="display: none;">
                <select name="<?php echo esc_attr( $blog_prefix . '[psfw_terms_operator]' ); ?>" class="psfw-terms-operator">
                    <option value="IN"><?php _e( 'IN', PSFWL_TD ) ?></option>
                    <option value="NOT IN"><?php _e( 'NOT IN', PSFWL_TD ) ?></option>
                    <option value="AND"><?php _e( 'AND', PSFWL_TD ) ?></option>
                    <option value="EXISTS"><?php _e( 'EXISTS', PSFWL_TD ) ?></option>
                    <option value="NOT EXISTS"><?php _e( 'NOT EXISTS', PSFWL_TD ) ?></option>
                </select>
            </div>
        </div>
    </div>
</div>