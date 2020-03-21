<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class ="psfw-post-option-wrap">
    <label for="psfw-show-taxonomy-query" class="psfw-taxonomy-relation">
        <?php _e( 'Filter Taxonomies/Categories Post', PSFWL_TD ); ?>
    </label>
    <div class="psfw-post-field-wrap">
        <label class="psfw-switch">
            <input type="checkbox" class="psfw-show-taxonomy-relation psfw-checkbox" value="<?php
            if (isset( $psfw_option['psfw_show_taxonomy_query'])) {
                echo esc_attr( $psfw_option['psfw_show_taxonomy_query']);
            } else {
                echo '0';
            }
            ?>" name="psfw_option[psfw_show_taxonomy_query]" <?php if (isset( $psfw_option['psfw_show_taxonomy_query']) && $psfw_option['psfw_show_taxonomy_query'] == '1') { ?>checked="checked"<?php } ?>/>
            <div class="psfw-slider round"></div>
        </label>
        <p class="description"> <?php _e('Enable to show posts associated with certain taxonomy/category.', PSFWL_TD ) ?></p>
    </div>
</div>
<div class="psfw-taxonomy-main-wrap"
<?php if ( isset( $psfw_option[ 'psfw_show_taxonomy_query' ] ) && $psfw_option[ 'psfw_show_taxonomy_query' ] == '1' ) { ?>
         style="display: block;" <?php } else { ?>
         style="display: none;" <?php } ?>>
    <div class="psfw-post-option-wrap" style="display:none;">
        <label> <?php _e( 'Taxonomy/Category Queries Type', PSFWL_TD ); ?> </label>
        <div class="psfw-tooltip-icon">
            <span class="dashicons dashicons-info"></span>
            <span class="psfw-tooltip-info"><?php _e( 'Choose Simple Taxonomy/Category Query to display post from a single taxonomy or category with single term.For example display posts tagged with bob, under people custom taxonomy.Choose Multiple Taxonomy/Category Handling to display posts from several custom taxonomies or categories.', PSFWL_TD ); ?></span>
        </div>
        <div class="psfw-post-field-wrap">
            <div class="psfw-info-wrap">
                <input type="hidden" name="psfw_option[taxonomy_queries_type]" class="psfw-taxonomy-queries-type" value="simple-taxonomy" >
            </div>
        </div>
    </div>
    <div class="psfw-post-option-wrap">
        <label><?php _e( 'Taxonomy/Category', PSFWL_TD ); ?></label>
        <div class="psfw-post-field-wrap">
            <select name="psfw_option[select_post_taxonomy]" class="psfw-select-taxonomy">
                <option value="select" <?php if ( isset( $psfw_option[ 'select_post_taxonomy' ] ) && $psfw_option[ 'select_post_taxonomy' ] == 'select' ) echo 'selected=="selected"'; ?>><?php echo _e( 'Choose Taxonomy', PSFWL_TD ); ?></option>
                <?php
                $product_post_type = 'product';
                $taxonomies = get_object_taxonomies( $product_post_type, 'objects' );

                foreach ( $taxonomies as $tax ) {
                    $value = $tax -> name;
                    $label = $tax -> label;
                    ?>
                    <option value="<?php echo $value; ?>" <?php if ( isset( $psfw_option[ 'select_post_taxonomy' ] ) && $psfw_option[ 'select_post_taxonomy' ] == $value ) echo 'selected=="selected"'; ?>><?php echo $label; ?></option>
                    <?php
                }
                ?>
            </select>
            <p class="description"><?php _e( 'Please select the product type first', PSFWL_TD ); ?></p>
            <div class="psfw-loader-preview" style="display:none;">
                <img src="<?php echo PSFWL_IMG_DIR . '/ajax-loader-add.gif' ?>">
            </div>
        </div>
    </div>
    <div class="psfw-simple-terms-wrap" >
        <div class="psfw-post-option-wrap">
            <label><?php _e( 'Term', PSFWL_TD ); ?></label>
            <div class="psfw-tooltip-icon">
                <span class="dashicons dashicons-info"></span>
                <span class="psfw-tooltip-info"><?php _e( 'Terms refers to the items in a taxonomy. For example, a website has categories books, politics, and blogging in it. While category itself is a taxonomy the items inside it are called terms.', PSFWL_TD ); ?></span>
            </div>
            <div class="psfw-post-field-wrap">
                <div class="psfw-info-wrap">
                    <select name="psfw_option[simple_taxonomy_terms]" class="psfw-simple-taxonomy-term">
                        <option value=""><?php echo _e( 'Choose Term', PSFWL_TD ); ?></option>
                        <?php
                        if ( ! empty( $psfw_option[ 'simple_taxonomy_terms' ] ) ) {
                            echo $this -> psfw_fetch_category_list( $psfw_option[ 'select_post_taxonomy' ], $psfw_option[ 'simple_taxonomy_terms' ] );
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e( 'Please select taxonomy first.', PSFWL_TD ); ?></p>
                    <div class="psfw-taxonomy-preview" style="display:none;">
                        <img src="<?php echo PSFWL_IMG_DIR . '/ajax-loader-add.gif' ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>