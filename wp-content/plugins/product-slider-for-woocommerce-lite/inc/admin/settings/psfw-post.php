<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-post-selection-wrap">
    <input type="hidden" name="psfw_option[product_type]" class="psfw-product-type" value="product">            
    <div class="psfw-query-wrapper">
        <ul class="psfw-query-tab">
            <li data-menu="taxonomy-settings" class="psfw-query-tigger psfw-query-active">
                <?php _e('Taxonomies/Categories', PSFWL_TD) ?>
            </li>
        </ul>
    </div>
    <div class ="psfw-query-setting-wrap psfw-active-field" data-menu-ref="taxonomy-settings">
        <?php include(PSFWL_PATH . 'inc/admin/filter/psfw-taxonomy.php'); ?>
    </div>
    <div class="psfw-separation-wrap">
        <div class ="psfw-post-option-wrap">
            <label><?php _e( 'OrderBy', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <select name="psfw_option[psfw_select_orderby]" class="psfw-select-orderby">
                    <option value="none"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'none' ) echo 'selected=="selected"'; ?>><?php _e( 'None', PSFWL_TD ) ?></option>
                    <option value="ID"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'ID' ) echo 'selected=="selected"'; ?>><?php _e( 'ID', PSFWL_TD ) ?></option>
                    <option value="author"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'author' ) echo 'selected=="selected"'; ?>><?php _e( 'Author', PSFWL_TD ) ?></option>
                    <option value="title"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'title' ) echo 'selected=="selected"'; ?>><?php _e( 'Title', PSFWL_TD ) ?></option>
                    <option value="name"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'name' ) echo 'selected=="selected"'; ?>><?php _e( 'Post Name', PSFWL_TD ) ?></option>
                    <option value="type"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'type' ) echo 'selected=="selected"'; ?>><?php _e( 'Post Type', PSFWL_TD ) ?></option>
                    <option value="date"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'date' ) echo 'selected=="selected"'; ?>><?php _e( 'Date', PSFWL_TD ) ?></option>
                    <option value="modified"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'modified' ) echo 'selected=="selected"'; ?>><?php _e( 'Last Modified Date', PSFWL_TD ) ?></option>
                    <option value="parent"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'parent' ) echo 'selected=="selected"'; ?>><?php _e( 'Parent ID', PSFWL_TD ) ?></option>
                    <option value="rand"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'rand' ) echo 'selected=="selected"'; ?>><?php _e( 'Random', PSFWL_TD ) ?></option>
                    <option value="comment_count"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'comment_count' ) echo 'selected=="selected"'; ?>><?php _e( 'Comment Count', PSFWL_TD ) ?></option>
                    <option value="menu_order"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'menu_order' ) echo 'selected=="selected"'; ?>><?php _e( 'Menu Order', PSFWL_TD ) ?></option>
                    <option value="meta_value"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'meta_value' ) echo 'selected=="selected"'; ?>><?php _e( 'Alphabetical Meta Value', PSFWL_TD ) ?></option>
                    <option value="meta_value_num"  <?php if ( isset( $psfw_option[ 'psfw_select_orderby' ] ) && $psfw_option[ 'psfw_select_orderby' ] == 'meta_value_num' ) echo 'selected=="selected"'; ?>><?php _e( 'Numeric Meta Value', PSFWL_TD ) ?></option>
                </select>
            </div>
        </div>
        <div class="psfw-orderby-meta-warp" style="display: none;">
            <div class ="psfw-post-option-wrap">
                <label><?php _e( 'Meta Key', PSFWL_TD ); ?></label>
                <div class="psfw-post-field-wrap">
                    <input type="text" class="psfw-orderby-key" name="psfw_option[psfw_orderby_key]" value="<?php
                    if ( isset( $psfw_option[ 'psfw_orderby_key' ] ) ) {
                        echo esc_attr( $psfw_option[ 'psfw_orderby_key' ] );
                    }
                    ?>" >
                </div>
            </div>
        </div>
        <div class ="psfw-post-option-wrap">
            <label><?php _e( 'Order', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <select name="psfw_option[psfw_select_order]" class="psfw-select-order">
                    <option value="ASC"  <?php if ( isset( $psfw_option[ 'psfw_select_order' ] ) && $psfw_option[ 'psfw_select_order' ] == 'ASC' ) echo 'selected=="selected"'; ?>><?php _e( 'Ascending', PSFWL_TD ) ?></option>
                    <option value="DESC"  <?php if ( isset( $psfw_option[ 'psfw_select_order' ] ) && $psfw_option[ 'psfw_select_order' ] == 'DESC' ) echo 'selected=="selected"'; ?>><?php _e( 'Descending', PSFWL_TD ) ?></option>
                </select>
            </div>
        </div>
        <div class ="psfw-post-option-wrap">
            <label><?php _e( 'Post Status', PSFWL_TD ); ?></label>
            <div class="psfw-post-field-wrap">
                <select name="psfw_option[psfw_select_post_status]" class="psfw-select-post-status">
                    <option value="publish"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'publish' ) echo 'selected=="selected"'; ?>><?php _e( 'Publish', PSFWL_TD ) ?></option>
                    <option value="pending"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'pending' ) echo 'selected=="selected"'; ?>><?php _e( 'Pending', PSFWL_TD ) ?></option>
                    <option value="draft"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'draft' ) echo 'selected=="selected"'; ?>><?php _e( 'Draft', PSFWL_TD ) ?></option>
                    <option value="auto-draft"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'auto-draft' ) echo 'selected=="selected"'; ?>><?php _e( 'Auto Draft',PSFWL_TD ) ?></option>
                    <option value="future"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'future' ) echo 'selected=="selected"'; ?>><?php _e( 'Future', PSFWL_TD ) ?></option>
                    <option value="private"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'private' ) echo 'selected=="selected"'; ?>><?php _e( 'Private', PSFWL_TD ) ?></option>
                    <option value="inherit"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'inherit' ) echo 'selected=="selected"'; ?>><?php _e( 'Inherit', PSFWL_TD ) ?></option>
                    <option value="trash"  <?php if ( isset( $psfw_option[ 'psfw_select_post_status' ] ) && $psfw_option[ 'psfw_select_post_status' ] == 'trash' ) echo 'selected=="selected"'; ?>><?php _e( 'Trash', PSFWL_TD ) ?></option>
                </select>
            </div>
        </div>
    </div>
</div>