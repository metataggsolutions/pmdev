<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>

<div  class="psfw-shortcode-usage-wrapper">
    <ul class="psfw-usage-tab-wrap">
        <li data-usage="tab-1" class="psfw-usage-trigger psfw-usage-active">
            <?php _e( 'Shortcode', PSFWL_TD ); ?>
        </li>
        <li data-usage="tab-2" class="psfw-usage-trigger">
            <?php _e( 'Template Include', PSFWL_TD ); ?>
        </li>
    </ul>
    <div class="psfw-usage-post" data-usage-ref="tab-1">
        <p class="description">
            <?php _e( 'Copy and paste the shortcode directly into any WordPress post or page.', PSFWL_TD ) ?>
        </p>
        <div class="psfw-shortcode-page-wrap">
            <input type='text' value='[psfw slug="<?php echo $post -> post_name; ?>"]' readonly="readonly">
        </div>
    </div>
    <div class="psfw-usage-post" data-usage-ref="tab-2" style="display: none;">
        <p class="description">
            <?php
            _e( 'Copy and paste this code into a template file to include the Product Slider For WooCommerce Lite within your theme.', PSFWL_TD );
            ?>
        </p>
        <div class = "psfw-shortcode-theme-wrap">
            &lt;?php echo do_shortcode("[psfw slug='<?php echo $post -> post_name; ?>']");
            ?&gt;
        </div>
    </div>
</div>