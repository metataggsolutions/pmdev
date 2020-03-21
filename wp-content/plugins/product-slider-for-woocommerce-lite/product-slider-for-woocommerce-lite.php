<?php
defined('ABSPATH') or die("No script kiddies please!");

/**
    Plugin Name: Product Slider For WooCommerce Lite
    Plugin URI:  https://accesspressthemes.com/wordpress-plugins/product-slider-for-woocommerce-lite/
    Description: Product Slider for WooCommerce Lite is a free version WooCommerce extension that lets you showcase your online products in the most appealing way using slider and carousel layouts
    Version:     1.0.9
    Author:      AccessPress Themes
    Author URI:  http://accesspressthemes.com/
    Domain Path: /languages/
    Text Domain: product-slider-for-woocommerce-lite
*/

    defined( 'PSFWL_VERSION' ) or define( 'PSFWL_VERSION', '1.0.9' ); 
    defined( 'PSFWL_TD' ) or define( 'PSFWL_TD', 'product-slider-for-woocommerce-lite' ); 
    defined( 'PSFWL_IMG_DIR' ) or define( 'PSFWL_IMG_DIR', plugin_dir_url( __FILE__ ) . 'images' ); 
    defined( 'PSFWL_JS_DIR' ) or define( 'PSFWL_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );  
    defined( 'PSFWL_CSS_DIR' ) or define( 'PSFWL_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' ); 
    defined( 'PSFWL_PATH' ) or define( 'PSFWL_PATH', plugin_dir_path( __FILE__ ) );
    defined( 'PSFWL_URL' ) or define( 'PSFWL_URL', plugin_dir_url( __FILE__ ) );

    defined('PSFWL_LITE_PLUGIN_NAME') or define('PSFWL_LITE_PLUGIN_NAME', 'Product Slider For WooCommerce Lite');
    defined('PSFWL_LITE_DEMO') or define('PSFWL_LITE_DEMO', 'http://demo.accesspressthemes.com/wordpress-plugins/product-slider-for-woocommerce-lite');
    defined('PSFWL_LITE_DOC') or define('PSFWL_LITE_DOC', 'https://accesspressthemes.com/documentation/product-slider-for-woocommerce-lite/');
    defined('PSFWL_LITE_DETAIL') or define('PSFWL_LITE_DETAIL', 'https://accesspressthemes.com/wordpress-plugins/product-slider-for-woocommerce-lite/');
    defined('PSFWL_LITE_RATING') or define('PSFWL_LITE_RATING', 'https://wordpress.org/support/plugin/product-slider-for-woocommerce-lite/reviews/#new-post');

    defined('PSFWL_PRO_PLUGIN_NAME') or define('PSFWL_PRO_PLUGIN_NAME', 'Product Slider For WooCommerce');
    defined('PSFWL_PRO_LINK') or define('PSFWL_PRO_LINK', 'https://1.envato.market/c/1302794/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fproduct-slider-for-woocommerce-woo-extension-to-showcase-products%2F22645023');
    defined('PSFWL_PRO_DEMO') or define('PSFWL_PRO_DEMO', 'http://demo.accesspressthemes.com/wordpress-plugins/product-slider-for-woocommerce');
    defined('PSFWL_PRO_DETAIL') or define('PSFWL_PRO_DETAIL', 'https://accesspressthemes.com/wordpress-plugins/product-slider-for-woocommerce/');


    if (! class_exists('PSFWL_Class')){
        class PSFWL_Class{
            function __construct(){
            register_activation_hook(__FILE__, array($this, 'default_plugin_settings'));
            add_action('init', array($this, 'psfw_plugin_text_domain'));
            //Adding a Post Type for plugin, adding meta boxes and saving those values
            add_action('init', array($this, 'psfw_register_post_type'));
            add_action('add_meta_boxes', array($this, 'psfw_add_slider_metabox')); //add metabox for slider
            add_action('add_meta_boxes', array($this, 'psfw_shortcode_usage_metabox')); //add metabox for shortcode
            add_action('add_meta_boxes', array($this, 'psfw_promotional_banner')); //add metabox for shortcode
            add_action('save_post', array($this, 'psfw_meta_save'));
            add_filter( 'manage_productsliderwoo_posts_columns', array( $this, 'psfw_columns_head' ) );
            add_action( 'manage_productsliderwoo_posts_custom_column', array( $this, 'psfw_columns_content' ), 10, 2 );
            add_action('admin_menu', array($this, 'psfw_register_about_us_page')); //add submenu to the post type of plugin
            //Including CSS and JS files
            add_action('wp_enqueue_scripts', array( $this, 'psfw_register_assets'),99);
            add_action('admin_enqueue_scripts', array( $this, 'psfw_register_admin_assets')); 
            $this->includes();
            add_shortcode('psfw', array($this, 'psfw_generate_shortcode')); // generating shortcode
            add_action('widgets_init', array( $this, 'psfw_widget_register'));
            //Adding Metaboxes in the post type PRODUCT and saving those values
            add_action( 'add_meta_boxes', array( $this, 'psfw_banner_settings')); //add banner metabox to posttype Product
            add_action( 'add_meta_boxes', array( $this, 'psfw_button_settings'));  //add button metabox to posttype Product
            add_action( 'save_post', array( $this, 'psfw_advance_field_save'));
            //Backend: 
            //Post Settings: Taxonomies/Caterogies
            add_action('wp_ajax_psfw_selected_taxonomy_terms', array($this, 'psfw_selected_taxonomy_terms'));
            add_action('wp_ajax_psfw_add_tax_condition', array($this, 'psfw_add_tax_condition'));
            add_action('wp_ajax_psfw_hierarchy_terms', array( $this, 'psfw_hierarchy_terms'));
            //Post Settings: Custom Field
            add_action( 'wp_ajax_psfw_add_meta_condition', array( $this, 'psfw_add_meta_condition'));
            // For Variable Products
            add_action( 'wp_ajax_psfw_variable_attribute', array( $this, 'psfw_variable_attribute'));
            add_action( 'wp_ajax_nopriv_psfw_variable_attribute', array( $this, 'psfw_variable_attribute'));
            add_action( 'wp_ajax_woocommerce_add_to_cart_variable_rc', array( $this,'woocommerce_add_to_cart_variable_rc_callback'));
            add_action( 'wp_ajax_nopriv_woocommerce_add_to_cart_variable_rc', array( $this,'woocommerce_add_to_cart_variable_rc_callback' ) );
            add_filter( 'woocommerce_get_sections_products', array( $this, 'wc_ajax_add_to_cart_variable_add_section'));
            add_filter( 'woocommerce_get_settings_products', array( $this, 'wc_ajax_add_to_cart_variable_all_settings'), 10, 2 );
            add_filter( 'woocommerce_show_variation_price', array( $this, 'show_variation_price')); 
            add_filter( 'plugin_row_meta', array( $this, 'psfw_plugin_row_meta' ), 10, 2 );
            add_filter( 'admin_footer_text', array( $this, 'psfw_admin_footer_text' ) );
        }

        function psfw_promotional_banner(){
            add_meta_box('psfw_promotional_banner_option', __('Check Pro Version',PSFWL_TD), array($this,'promotional_banner' ), 'productsliderwoo', 'side', 'default');
        }

        function promotional_banner(){
            ?>
            <div class="psfw-upgrade-wrapper">
                <a href="<?php echo PSFWL_PRO_LINK ?>" target="_blank">
                    <img src="<?php echo PSFWL_IMG_DIR . '/upgrade-to-pro/upgrade-to-pro.png' ?>" style="width:100%;">
                </a>

                <div class="psfw-upgrade-button-wrap-backend">

                    <a href="<?php echo PSFWL_PRO_DEMO; ?>" class="smls-demo-btn" target="_blank">Demo</a>

                    <a href="<?php echo PSFWL_PRO_LINK; ?>" target="_blank" class="smls-upgrade-btn">Upgrade</a>

                    <a href="<?php echo PSFWL_PRO_DETAIL; ?>" target="_blank" class="smls-upgrade-btn">Plugin Information</a>

                </div>

                <a href="<?php echo PSFWL_PRO_LINK ?>" target="_blank">
                    <img src="<?php echo PSFWL_IMG_DIR; ?>/upgrade-to-pro/upgrade-to-pro-feature.png" alt="<?php _e( 'Product Slider For WooCommerce', PSFWL_TD ); ?>" style="width:100%;">
                </a>
            </div>

            <?php
        }

        function psfw_plugin_row_meta( $links, $file ){
            if ( strpos( $file, 'product-slider-for-woocommerce-lite.php' ) !== false ) {
                $new_links = array(
                    'demo' => '<a href="'.PSFWL_LITE_DEMO.'" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span>Live Demo</a>',
                    'doc' => '<a href="'.PSFWL_LITE_DOC.'" target="_blank"><span class="dashicons dashicons-media-document"></span>Documentation</a>',
                    'support' => '<a href="http://accesspressthemes.com/support" target="_blank"><span class="dashicons dashicons-admin-users"></span>Support</a>',
                    'pro' => '<a href="'.PSFWL_PRO_LINK.'" target="_blank"><span class="dashicons dashicons-cart"></span>Premium version</a>'
                );
                $links = array_merge( $links, $new_links );
            }
            return $links;
        }


        function psfw_admin_footer_text( $text ){
            if( isset($_GET['post_type']) && $_GET['post_type'] === 'productsliderwoo') {
                $text = 'Enjoyed ' . PSFWL_LITE_PLUGIN_NAME . '? <a href="' . PSFWL_LITE_RATING . '" target="_blank">Please leave us a ★★★★★ rating</a> We really appreciate your support! | Try premium version <a href="' . PSFWL_PRO_LINK . '" target="_blank">' . PSFWL_PRO_PLUGIN_NAME . '</a> - more features, more power!';
                return $text;
            } else {
                return $text;
            }
        }

        function show_variation_price(){
            return TRUE;
        }

        function wc_ajax_add_to_cart_variable_all_settings( $settings, $current_section ){
            /**
             * Check the current section is what we want
             * */
            if ( $current_section == 'wc_ajax_add_to_cart_variable' ) {
                $settings_slider = array();

                // Add Title to the Settings
                $settings_slider[] = array( 'name' => __( 'WC Ajax for Variable Products Settings', PSFWL_TD ), 'type' => 'title', 'desc' => __( 'The following options are used to configure WC Ajax for Variable Products', PSFWL_TD ), 'id' => 'wc_ajax_add_to_cart_variable' );

                // Add first checkbox option
                $settings_slider[] = array(
                    'name' => __( 'Add Selection option to Category Page', PSFWL_TD ),
                    'desc_tip' => __( 'This will automatically insert variable selection options on product Category Archive Page', PSFWL_TD ),
                    'id' => 'wc_ajax_add_to_cart_variable_category_page',
                    'type' => 'checkbox',
                    'css' => 'min-width:300px;',
                    'desc' => __( 'Enable Varition select option on Category Archive page', PSFWL_TD ),
                );

                $settings_slider[] = array( 'type' => 'sectionend', 'id' => 'wc_ajax_add_to_cart_variable' );

                return $settings_slider;
            } else {
                return $settings;
            }
        }

        function wc_ajax_add_to_cart_variable_add_section( $sections ){
            $sections[ 'wc_ajax_add_to_cart_variable' ] = __( 'WC Ajax for Variable Products', PSFWL_TD );
            return $sections;
        }

        function woocommerce_add_to_cart_variable_rc_callback(){
            ob_start();
            $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST[ 'product_id' ] ) );
            $quantity = empty( $_POST[ 'quantity' ] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST[ 'quantity' ] );

            $variation_id = isset( $_POST[ 'variation_id' ] ) ? sanitize_text_field( absint( $_POST[ 'variation_id' ] )) : '';
            $variation = array();
            $variation = ! empty( $_POST[ 'variation' ] ) ?  $this -> psfw_sanitize_array($_POST[ 'variation' ]) : '';

            $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation, $cart_item_data );

            if ( $passed_validation && WC() -> cart -> add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {
                do_action( 'woocommerce_ajax_added_to_cart', $product_id );
                if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
                    wc_add_to_cart_message( $product_id );
                }
                WC_AJAX::get_refreshed_fragments();
            } else {
                $this -> json_headers();

                // If there was an error adding to the cart, redirect to the product page to show any errors
                $data = array(
                    'error' => true,
                    'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
                );
                echo json_encode( $data );
            }
            die();
        }
        /*
         * Woocommerce Variable Attribute
         */

        function psfw_variable_attribute(){
            global $wpdb;
            include (PSFWL_PATH . '/inc/frontend/data/lightbox/variable-content.php');
            die();
        }

        /* Add custom column to post list */
        function psfw_columns_head( $columns ){
            $columns[ 'shortcodes' ] = __( 'Shortcodes', PSFWL_TD );
            $columns[ 'template' ] = __( 'Template Include', PSFWL_TD );
            return $columns;
        }

        public function psfw_columns_content( $column, $post_ID ){
            if ($column == 'shortcodes') {
                $id = $post_ID;
                $post = get_post($id); 
                $slug = $post->post_name;
                if($slug != ''){ ?>
                    <textarea class="egpr-shortcode-display-value" style="resize: none;" rows="2" cols="40" readonly="readonly">[psfw slug="<?php echo $slug; ?>"] </textarea>
                    <span class="et-copied-info" style="display: none;"><?php _e('Shortcode copied to your clipboard.', PSFWL_TD); ?></span>
                <?php  }else{
                  echo 'Posts not published yet.';
              }    
          }
          if ($column == 'template') {
            $id = $post_ID;
            $post = get_post($id); 
            $slug = $post->post_name;
            if($slug != ''){ ?>
                <textarea class="egpr-shortcode-display-value" style="resize: none;" rows="2" cols="45" readonly="readonly">&lt;?php echo do_shortcode("[psfw slug='<?php echo $slug; ?>']"); ?&gt;</textarea>
                <span class="et-copied-info" style="display: none;"><?php _e('Shortcode copied to your clipboard.', PSFWL_TD); ?></span>
            <?php }else{
             echo 'Posts not published yet.';
         }
     }
 }

 function default_plugin_settings() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if (! is_plugin_active('woocommerce/woocommerce.php')) {
        wp_die(__('You need to activate WooCommerce in order to activate "Product Slider For WooCommerce". Please make sure that you have WooCommerce installed and activated.', PSFWL_TD ));
    }
    if (is_plugin_active('product-slider-for-woocommerce/product-slider-for-woocommerce.php')) {
        wp_die(__('You need to deactivate Product Slider For WooCommerce Plugin in order to activate Product Slider For WooCommerce lite plugin. Please deactivate pro one.', 'product-slider-for-woocommerce-lite'));
    }
}

//save the extra field metabox values
function psfw_advance_field_save( $post_id ){

            // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'psfw_product_nonce' ] ) && wp_verify_nonce( $_POST[ 'psfw_product_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
            // Exits script depending on save status
    if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }
    if ( isset( $_POST[ 'psfw_advance_option' ] ) && !empty($_POST[ 'psfw_advance_option' ]) ) {
        $psfw_advance = array();
        $_POST = array_map('stripslashes_deep', $_POST);
        $psfw_advance = $this -> psfw_sanitize_array($_POST[ 'psfw_advance_option' ]);
        $advance_field =  $psfw_advance ;
        update_post_meta( $post_id, 'psfw_advance_option', $advance_field );
    }
    return;
}

function psfw_banner_settings(){
    $type = array( 'product', 'download', 'psfw_product_manager' );
    add_meta_box( 'psfw_banner_settings', __( 'Product Slider For WooCommerce Banner/Ribbon Settings', PSFWL_TD ), array( $this, 'psfw_banner_callback' ), $type, 'normal', 'core' );
}

function psfw_banner_callback(){
    wp_nonce_field( basename( __FILE__ ), 'psfw_product_nonce' );
    include('inc/admin/fields/psfw-banner.php');
}

function psfw_button_settings(){
    $type = array( 'product', 'download', 'psfw_product_manager' );
    add_meta_box( 'psfw_button_settings', __( 'Product Slider For WooCommerce Settings', PSFWL_TD ), array( $this, 'psfw_button_callback' ), $type, 'normal', 'core' );
}

function psfw_button_callback(){
    wp_nonce_field( basename( __FILE__ ), 'psfw_product_nonce' );
    include('inc/admin/fields/button-settings.php');
}

function includes(){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
        include(PSFWL_PATH . '/inc/admin/visual-composer.php'); 
    }
    include('inc/admin/psfw-widget.php');
}

function psfw_widget_register(){
    register_widget( 'PSFW_Widget' );
}

function psfw_add_meta_condition(){
    global $wpdb;
    include( 'inc/ajax/add-meta.php' );
    die();
}

function psfw_hierarchy_terms(){
    global $wpdb;
    include( 'inc/ajax/hierarchy-terms.php' );
    die();
}

function psfw_add_tax_condition(){
    global $wpdb;
    include( 'inc/ajax/add-taxonomy.php' );
    die();
}

function psfw_selected_taxonomy_terms(){
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'psfw-admin-ajax-nonce')) {
        global $wpdb;
        include('inc/ajax/fetch-terms.php');
        die();
    }
}

function psfw_generate_shortcode( $atts, $content = null ){
    ob_start();
    include('inc/frontend/psfw-frontend.php');
    $blog = ob_get_contents();
    ob_end_clean();
    return $blog;
}

function psfw_meta_save( $post_id ){
            // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );

    $is_valid_nonce = ( isset( $_POST[ 'psfw_product_nonce' ] ) && wp_verify_nonce( $_POST[ 'psfw_product_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
            // Exits script depending on save status
    if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }

    if ( isset( $_POST[ 'psfw_option' ] ) && !empty( $_POST[ 'psfw_option' ]) ) {
        $_POST = array_map('stripslashes_deep', $_POST);
        $psfw_array = $this -> psfw_sanitize_array( $_POST[ 'psfw_option' ]);
        $val = $psfw_array;
        update_post_meta( $post_id, 'psfw_option', $val );
    }

    return;
}

function psfw_sanitize_array( $array = array(), $sanitize_rule = array() ){
    if ( ! is_array( $array ) || count( $array ) == 0 ) {
        return array();
    }
    foreach ( $array as $k => $v ) {
        if ( ! is_array( $v ) ) {
            $default_sanitize_rule = (is_numeric( $k )) ? 'html' : 'text';
            $sanitize_type = isset($sanitize_rule[ $k ] ) ? $sanitize_rule[ $k ] : $default_sanitize_rule;
            $array[ $k ] = $this -> psfw_sanitize_value($v, $sanitize_type);
        }
        if ( is_array( $v ) ) {
            $array[ $k ] = $this -> psfw_sanitize_array($v, $sanitize_rule);
        }
    }
    return $array;
}

function psfw_sanitize_value( $value = '', $sanitize_type = 'text' ){
    switch ( $sanitize_type ) {
        case 'html':
        $allowed_html = wp_kses_allowed_html( 'post' );
        return wp_kses( $value, $allowed_html );
        break;
        default:
        return sanitize_text_field( $value );
        break;
    }
}

function psfw_register_assets(){
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'psfw-fontawesome', PSFWL_CSS_DIR . '/font-awesome.min.css', false, PSFWL_VERSION );
    wp_enqueue_style( 'psfw-font', '//fonts.googleapis.com/css?family=Bitter|Hind|Playfair+Display:400,400i,700,700i,900,900i|Open+Sans:400,500,600,700,900|Lato:300,400,700,900|Montserrat|Droid+Sans|Roboto|Lora:400,400i,700,700i|Roboto+Slab|Rubik|Merriweather:300,400,700,900|Poppins|Ropa+Sans|Playfair+Display|Rubik|Source+Sans+Pro|Roboto+Condensed|Roboto+Slab:300,400,700|Amatic+SC:400,700|Quicksand|Oswald|Quicksand:400,500,700', false );
    wp_enqueue_style( 'psfw-animation-style', PSFWL_CSS_DIR . '/animate.css', false, PSFWL_VERSION );
    wp_enqueue_style( 'psfw-linearicons', PSFWL_CSS_DIR . '/linear-icon-font.min.css', false, PSFWL_VERSION);

    if ( class_exists( 'WooCommerce' ) ) {
        global $woocommerce;
        wp_enqueue_script( 'wc-add-to-cart-variation', $woocommerce -> plugin_url() . '/assets/js/frontend/add-to-cart-variation.js', array( 'jquery' ), '1.6', true );
    }
    
    wp_enqueue_script( 'psfw-isotope-script', PSFWL_JS_DIR . '/isotope.js', array( 'jquery' ), PSFWL_VERSION );
    wp_enqueue_script( 'psfw-imageloaded-script', PSFWL_JS_DIR . '/imagesloaded.min.js', array( 'jquery' ), PSFWL_VERSION );
    wp_enqueue_script( 'psfw-linearicons', PSFWL_JS_DIR . '/linear-icon.min.js', array( 'jquery' ), PSFWL_VERSION  );
    wp_enqueue_script( 'psfw-bxslider-script', PSFWL_JS_DIR . '/jquery.bxslider.min.js', array( 'jquery' ), PSFWL_VERSION );

    wp_enqueue_style( 'psfw-bxslider-style', PSFWL_CSS_DIR . '/jquery.bxslider.css', false, PSFWL_VERSION );
    
    wp_enqueue_style( 'psfw-frontend-style', PSFWL_CSS_DIR . '/psfw-frontend.css', PSFWL_VERSION );
    wp_enqueue_style( 'psfw-responsive-style', PSFWL_CSS_DIR . '/psfw-responsive.css', false, PSFWL_VERSION );
    
            // "wp-util" used to solve the issue that stopped the price of variable products from displaying.
    if ( class_exists( 'WooCommerce' ) ) {
        $js_list = array( 'jquery', 'psfw-imageloaded-script', 'psfw-isotope-script', 'psfw-bxslider-script', 'wc-add-to-cart-variation', 'wp-util' );
    } else {
        $js_list = array( 'jquery', 'psfw-imageloaded-script', 'psfw-isotope-script', 'psfw-bxslider-script', 'wp-util' );
    }

    wp_enqueue_script( 'psfw-frontend-script', PSFWL_JS_DIR . '/psfw-frontend.js', $js_list, PSFWL_VERSION );

    $frontend_ajax_nonce = wp_create_nonce( 'psfw-frontend-ajax-nonce' );
    $frontend_ajax_object = array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => $frontend_ajax_nonce );
    wp_localize_script( 'psfw-frontend-script', 'psfw_frontend_js_params', $frontend_ajax_object );
    if ( class_exists( 'WooCommerce' ) ) {
        global $woocommerce;
                //wp_register_script( 'psfw-woo-script', '/psfwscript_url' );
        wp_enqueue_script( 'psfw-woo-script' );

        $translation_array = array( 'templateUrl' => $woocommerce -> plugin_url() );
        wp_localize_script( 'psfw-woo-script', 'psfw_object_name', $translation_array );
    }
            // To solve the issue that stopped the price of variable products from displaying:
            //wp_enqueue_script( 'script_handle', plugin_dir_url( __FILE__ ) . 'script.js', array( 'jquery', 'wp-util' ), '1.0', true );
}

function psfw_register_admin_assets( $hook ){
    global $post;
    wp_enqueue_media();
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'psfw-picker-script', PSFWL_JS_DIR . '/icon-picker.js', array( 'jquery' ), PSFWL_VERSION );
    wp_enqueue_script( 'psfw-admin-script', PSFWL_JS_DIR . '/psfw-admin-script.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable', 'jquery-ui-core', 'psfw-picker-script' ), PSFWL_VERSION );
    $admin_ajax_nonce = wp_create_nonce( 'psfw-admin-ajax-nonce' );
    $admin_ajax_object = array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => $admin_ajax_nonce );
    wp_localize_script( 'psfw-admin-script', 'psfw_backend_js_params', $admin_ajax_object );
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'psfw-icon-style', PSFWL_CSS_DIR . '/icon-picker.css', false, PSFWL_VERSION );
    wp_enqueue_style( 'psfw-font', PSFWL_CSS_DIR . '/font-awesome.min.css', false, PSFWL_VERSION );
    wp_enqueue_style( 'psfw-backend-style', PSFWL_CSS_DIR . '/psfw-backend-style.css', false, PSFWL_VERSION );
    wp_enqueue_style( 'psfw-jquery-ui-style', PSFWL_CSS_DIR . '/jquery-ui-css-1.12.1.css', false, PSFWL_VERSION );
}

function psfw_shortcode_usage_metabox(){
    add_meta_box('psfw_shortcode_usage_option', __('Product Slider Usage',PSFWL_TD), array($this,'psfw_shortcode_usage' ), 'productsliderwoo', 'side', 'default');
}

function psfw_shortcode_usage( $post ){
    wp_nonce_field( basename( __FILE__ ), 'psfw_shortcode_usage_option_nonce');
    include('inc/admin/settings/psfw-usages.php');
}

function psfw_add_slider_metabox(){
    add_meta_box( 'psfw_add_blog', __('Product Slider For WooCommerce Lite', PSFWL_TD), array($this, 'psfw_add_slider_callback'), 'productsliderwoo', 'normal', 'high');
}

function psfw_add_slider_callback( $post ){
    wp_nonce_field( basename( __FILE__ ), 'psfw_product_nonce');
    include('inc/admin/psfw-blog-meta.php');
}

function psfw_plugin_text_domain(){
    load_plugin_textdomain('product-slider-for-woocommerce-lite', false, basename( dirname( __FILE__ ) ) . '/languages/');
}

function psfw_register_post_type(){
    $labels = array(
        'name' => _x( 'Product Slider For WooCommerce Lite', 'post type general name', PSFWL_TD ),
        'singular_name' => _x( 'Product Slider For WooCommerce Lite', 'post type singular name', PSFWL_TD ),
        'menu_name' => _x( 'Product Slider For WooCommerce Lite', 'admin menu', PSFWL_TD ),
        'name_admin_bar' => _x( 'Product Slider For WooCommerce Lite', 'add new on admin bar', PSFWL_TD ),
        'add_new' => _x( 'Add New', 'Product Slider For WooCommerce Lite', PSFWL_TD ),
        'add_new_item' => __( 'Add New Product Slider For WooCommerce Lite', PSFWL_TD ),
        'new_item' => __( 'New Product Slider For WooCommerce Lite', PSFWL_TD ),
        'edit_item' => __( 'Edit Product Slider For WooCommerce Lite', PSFWL_TD ),
        'view_item' => __( 'View Product Slider For WooCommerce Lite', PSFWL_TD ),
        'all_items' => __( 'All Product Slider For WooCommerce Lite', PSFWL_TD ),
        'search_items' => __( 'Search Product Slider For WooCommerce Lite', PSFWL_TD ),
        'parent_item_colon' => __( 'Parent Product Slider For WooCommerce Lite:', PSFWL_TD ),
        'not_found' => __( 'No Product Slider For WooCommerce Lite found.', PSFWL_TD ),
        'not_found_in_trash' => __( 'No Product Slider For WooCommerce Lite found in Trash.', PSFWL_TD )
    );

    $args = array(
        'labels' => $labels,
        'description' => __('Description.', PSFWL_TD),
        'public' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'query_var' => true,
        'rewrite' => array('slug' => PSFWL_TD),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );
    register_post_type('productsliderwoo', $args);
}

function psfw_register_about_us_page ($submenu){
    add_submenu_page( 'edit.php?post_type=productsliderwoo', __('About Us', PSFWL_TD), __('About Us', PSFWL_TD), 'manage_options', 'psfw-about-us', array($this, 'psfw_about_callback'));

    global $submenu;
    $submenu['edit.php?post_type=productsliderwoo'][] = array( 'Documentation', 'manage_options', PSFWL_LITE_DOC );
    $submenu['edit.php?post_type=productsliderwoo'][] = array( 'Check Premium Version', 'manage_options', PSFWL_PRO_LINK );
}

function sanitize_array($array = array(), $sanitize_rule = array()) {
    if (!is_array($array) || count($array) == 0) {
        return array();
    }
    foreach ($array as $k => $v) {
        if (!is_array($v)) {

            $default_sanitize_rule = (is_numeric($k)) ? 'text' : 'html';
            $sanitize_type = isset($sanitize_rule[$k]) ? $sanitize_rule[$k] : $default_sanitize_rule;
            $array[$k] = $this->sanitize_value($v, $sanitize_type);
        }
        if (is_array($v)) {
            $array[$k] = $this->sanitize_array($v, $sanitize_rule);
        }
    }
    return $array;
}

function sanitize_value($value = '', $sanitize_type = 'text') {
    switch ($sanitize_type) {
        case 'html':
        $allowed_html = wp_kses_allowed_html('post');
        return wp_kses($value, $allowed_html);
        break;
        default:
        return sanitize_text_field($value);
        break;
    }
}

function psfw_plugin_settings_callback(){
    include('inc/admin/psfw-plugin-settings.php');
}

function psfw_about_callback(){
    include('inc/admin/psfw-about-page.php');
}

function print_array( $array ){
    echo '<pre>';
    print_r( $array );
    echo '</pre>';
}

function get_id_from_slug($slug){
    $posts = get_posts(array('name' => $slug,'post_type'=>'productsliderwoo'));
    return$posts[0]->ID;
}

function psfw_wishlist_button($product_item_id){
    if ( class_exists( 'YITH_WCWL' ) ) {
        global $product, $yith_wcwl;
        $default_wishlists = is_user_logged_in() ? YITH_WCWL() -> get_wishlists( array( 'is_default' => true ) ) : false;
        if ( ! empty( $default_wishlists ) ) {
            $default_wishlist = $default_wishlists[ 0 ][ 'ID' ];
        } else {
            $default_wishlist = false;
        }
        $wishlist_url = YITH_WCWL() -> get_wishlist_url();
        ?>
        <div class="yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo $product_item_id; ?>">
            <div class="yith-wcwl-add-button show" style="display:block">
                <a  href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_item_id ) ); ?>" rel="nofollow" data-product-id="<?php echo $product_item_id ; ?>" data-product-type="variable" class="add_to_wishlist">
                    <i class="psfw-heart fa fa-heart-o"></i>
                    <img src="<?php echo PSFWL_IMG_DIR; ?>/wpspin_light.gif" class="ajax-loading" alt="loading" style="display:none !important;">
                    <span class="ts-tooltip button-tooltip"><?php _e( 'Add to wishlist', PSFWL_TD ); ?></span>
                </a>
            </div>
            <div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
                <a href="<?php echo $wishlist_url ; ?>" rel="nofollow">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ts-tooltip button-tooltip"><?php _e( 'Browse Wishlist', PSFWL_TD ); ?></span> </a>
                </div>
                <div class="yith-wcwl-wishlistexistsbrowse hide" style="display:none">
                    <a href="<?php echo $wishlist_url ; ?>" rel="nofollow">
                        <i class="fa fa-check" aria-hidden="true"></i><span class="ts-tooltip button-tooltip"><?php _e( 'Browse Wishlist', PSFWL_TD ); ?></span> </a>
                    </div>
                    <div style="clear:both"></div>
                    <div class="yith-wcwl-wishlistaddresponse"></div>
                </div>
                <?php
                return;
            }
        }

        function psfw_fetch_content( $post, $excerpt_length ){
            return substr( get_the_excerpt(), 0, $excerpt_length );
        }

        //returns all the terms for category dropdown as options
        function psfw_fetch_category( $post_id, $post, $category ){
            $psfw_option = get_post_meta( $post_id, 'psfw_option', true );
            if (!isset($psfw_option['psfw_show_taxonomy_query']) && !isset($psfw_option['psfw_show_custom_query'])&& !isset($psfw_option['psfw_show_keyword_query']) && !isset($psfw_option['psfw_show_popular_query']) ){
                $category = '';
            }
            $categories = get_the_terms( $post, $category );
            $separator = ' ';
            $output = '';
            if ( ! empty( $categories ) ) {
                foreach ( $categories as $category ) {
                    $output .= '<a href="' . esc_url( get_category_link( $category -> term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', PSFWL_TD ), $category -> name ) ) . '">' . esc_html( $category -> name ) . '</a>' . $separator;
                }
                return trim( $output, $separator );
            }
        }

        function psfw_fetch_category_no_link( $post_id, $post, $category ){
            $psfw_option = get_post_meta( $post_id, 'psfw_option', true );

            if (!isset($psfw_option['psfw_show_taxonomy_query']) && !isset($psfw_option['psfw_show_custom_query'])&& !isset($psfw_option['psfw_show_keyword_query']) && !isset($psfw_option['psfw_show_popular_query']) ){
                $category = '';
            }

            $categories = get_the_terms( $post, $category );
            $separator = ' ';
            $output = '';
            if ( ! empty( $categories ) ) {
                foreach ( $categories as $category ) {
                    $output .= '<span class="psfw-scroll-category">' .esc_html( $category -> name ) . $separator . '</span>';
                }
                return trim( $output, $separator );
            }
        }

        function psfw_fetch_category_list( $taxonomy, $term_id ){
            $option_html = "";
            $taxonomies_array[] = $taxonomy;
            $terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );
            $categoryHierarchy = array();
            $this-> psfw_sort_terms_hierarchicaly( $terms, $categoryHierarchy );

            if (count( $categoryHierarchy) > 0 ) { //condition check if the taxonomy has atleast single term
                $terms_exclude = array();
                $option_html .= $this -> psfw_print_option( $categoryHierarchy, 1, '', '', $term_id );
            }
            return $option_html;
        }

        function psfw_sort_terms_hierarchicaly( Array &$cats, Array &$into, $parentId = 0 ){
            foreach ( $cats as $i => $cat ) {
                if ( $cat -> parent == $parentId ) {
                    $into[ $cat -> term_id ] = $cat;
                    unset( $cats[ $i ] );
                }
            }
            foreach ( $into as $topCat ) {
                $topCat -> children = array();
                $this -> psfw_sort_terms_hierarchicaly( $cats, $topCat -> children, $topCat -> term_id );
            }
        }

        function psfw_print_option( $terms, $hierarchical = 1, $form = '', $field_title = '', $selected_term = array() ){
            foreach ( $terms as $term ) {
                $space = $this -> psfw_check_parent( $term );
                $option_value = $term -> term_id;
                if ( is_array( $selected_term ) ) {
                    $selected = (in_array( $option_value, $selected_term )) ? 'selected="selected"' : '';
                } else {
                    $selected = ($selected_term == $option_value) ? 'selected="selected"' : '';
                }
                $form .= '<option value="' . $option_value . '" ' . $selected . '>' . $space . $term -> name . '</option>';
                if ( ! empty( $term -> children ) ) {
                    $form .= $this -> psfw_print_option( $term -> children, $hierarchical, '', $field_title, $selected_term );
                }
            }
            return $form;
        }
        
        function psfw_check_parent( $term, $space = '' ){
            if ( is_object( $term ) ) {
                if ( $term -> parent != 0 ) {
                    $space .= str_repeat( '&nbsp;', 2 );
                    $parent_term = get_term_by( 'id', $term -> parent, $term -> taxonomy );
                    $space .= $this -> psfw_check_parent( $parent_term, $space );
                }
            }
            return $space;
        }

        function psfw_generate_random_string( $length ){
            $string = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $random_string = '';
            for ( $i = 1; $i <= $length; $i ++ ) {
                $random_string .= $string[ rand( 0, 61 ) ];
            }
            return $random_string;
        }

        // retrieves the attachment ID from the file URL
        function psfw_get_image_id( $image_url ){
            global $wpdb;
            $query = "SELECT ID FROM {$wpdb -> posts} WHERE guid='$image_url'";
            $id = $wpdb -> get_var( $query );
            return $id;
        }
    }
    $psfwl_obj = new PSFWL_Class();
}