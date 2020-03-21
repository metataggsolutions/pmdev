<?php
/*
Plugin Name: Woo Image SEO
Description: Boost your SEO by automatically adding  alt tags and title attributes to all product images. Requires WooCommerce.
Version: 1.0.1
Plugin URI: https://wordpress.org/plugins/woo-image-seo/
Author: Danail Emandiev
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Function to ensure that there are plugin settings in the database
function woo_image_seo_check_for_existing_settings() {
    
    // Check for existing settings
    if ( !get_option( 'woo_image_seo' ) ) {
        // do this if there are no settings in the database
        // set default settings var
        $default_settings = '{"alt":{"enable":1,"force":0,"text":{"1":"[none]","2":"[name]","3":"[none]"}},"title":{"enable":1,"force":1,"text":{"1":"[none]","2":"[name]","3":"[none]"}}}';
        update_option( 'woo_image_seo', $default_settings );
    }
    
}


// Add settings page to dashboard
function woo_image_seo_add_page() {
	add_submenu_page( 'woocommerce', 'Woo Image SEO', 'Woo Image SEO', 'manage_options', 'woo_image_seo', 'woo_image_seo_page_callback' );
}

function woo_image_seo_page_callback() {
	include('settings.php');
}


// Add a link in the Installed Plugins page to the Plugin's settings page
function woo_image_seo_add_settings_link( $links ) {
    array_push( $links, '<a href="admin.php?page=woo_image_seo">Settings</a>' );
  	return $links;
}


// Main function - change image attributes
function woo_image_seo_change_image_attributes($attr, $attachment) {
    
    // check if the current post is a product
	if ( get_post_type() === 'product' ) {
	    
	    // check for existing settings or save default to database
	    woo_image_seo_check_for_existing_settings();
		
		// decode JSON settings string
		$settings = json_decode( get_option( 'woo_image_seo' ), true );
		
		// Check which attributes should be handled
		foreach ( $settings as $settings_key => $settings_value ) {

				// Check if attribute should be changed, two conditions
				// 1. if the attribute handle is enabled
				// 2. if there is no attribute set or the attribute has no length or 'force attribute' is enabled
				if ( $settings_value['enable'] && (!isset($attr[$settings_key]) || strlen($attr[$settings_key]) === 0 || $settings_value['force']) ) {
					
					$attr[$settings_key] = ''; // declare var so we can append later
					
					// Check how the attribute is built
					foreach ( $settings_value['text'] as $text_key => $text_value ) {
						
						if ( $text_value ) {
							switch ($text_value) {
						
						case '[name]': // Get product title
							$text_value = get_the_title();
							break;
							
						case '[category]': // Get product categories
							$product_categories = get_the_terms( get_the_ID(), 'product_cat' );
							// check if product has a category, it should be an array
							if ( is_array($product_categories) ) {
								// if first category is not "Uncategorized", use it
								if ( $product_categories[0]->name !== 'Uncategorized' ) {
									$text_value = $product_categories[0]->name;
								}
								else if ( isset($product_categories[1]) ) { // try to get another category
									$text_value = $product_categories[1]->name;
								}
							}
							break;
							
						case '[tag]': // Get product tags
							$product_tags = get_the_terms( get_the_ID(), 'product_tag' );
							// check if product has a tag
							if ( is_array($product_tags) ) {
								$text_value = $product_tags[0]->name;
							}
							break;
							
						default: // if value is not one of the above
							$text_value = null;
							break;
							
							}
							
							if ($text_value) { // if value is not null/0
								$attr[$settings_key] .= $text_value . ' ';
							}
						}
					}
					
					// Trim whitespace
					$attr[$settings_key] = trim($attr[$settings_key]);
				
				}
			
		}
		
		
	}
	
	// Return the final attribute to front-end
	return $attr;
	
}

// hooks, filters, actions
register_activation_hook( __FILE__, 'woo_image_seo_check_for_existing_settings' );
add_action('admin_menu', 'woo_image_seo_add_page');
add_filter( "plugin_action_links_".plugin_basename( __FILE__ ), 'woo_image_seo_add_settings_link' );
add_filter('wp_get_attachment_image_attributes', 'woo_image_seo_change_image_attributes', 20, 2);