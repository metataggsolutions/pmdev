<?php 
/*
Plugin Name: Pickup Date Addon
Plugin URI: https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/
Description: This plugin allows customers to choose the pickup date and time during checkout for an order. This plugin is an addon for <a href="https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/" target="_blank">Order Delivery Date Pro for WooCommerce</a> plugin.
Author: Tyche Softwares
Version: 1.0
Author URI: http://www.tychesoftwares.com/about
Contributor: Tyche Softwares, http://www.tychesoftwares.com/
*/

global $orpd_version;
$orpd_version = '1.0';

if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater if it doesn't already exist
    include( dirname( __FILE__ ) . '/plugin-updates/EDD_SL_Plugin_Updater.php' );
}

include_once( 'license.php' );

// retrieve our license key from the DB
$license_key = trim( get_option( 'orpd_sample_license_key' ) );
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
// IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
define( 'ORPD_SL_STORE_URL', 'http://www.tychesoftwares.com/' ); 

// the name of your product. This is the title of your product in EDD and should match the download title in EDD exactly
// IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system
define( 'ORPD_SL_ITEM_NAME', 'Pickup Date Addon for Order Delivery Date for WooCommerce Plugin' ); 
// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater( ORPD_SL_STORE_URL, __FILE__, array(
    'version'   => '1.0',       // current version number
    'license'   => $license_key,    // license key (used get_option above to retrieve from DB)
    'item_name' => ORPD_SL_ITEM_NAME,  // name of this plugin
    'author'    => 'Ashok Rane'  // author of this plugin
)
);

class order_pickup_date {
    /**
     * order_pickup_date Constructor.
     */
    public function __construct() {

        //Check for Order Delivery Date Pro for WooCommerce
        add_action( 'admin_init', array( &$this, 'orpd_check_if_plugin_active' ) );

        //License
        add_action( 'orddd_add_submenu', array( &$this, 'orpd_addon_for_orddd_menu' ) );
        add_action( 'admin_init', array( 'orpd_license', 'orpd_register_option' ) );
        add_action( 'admin_init', array( 'orpd_license', 'orpd_deactivate_license' ) );
        add_action( 'admin_init', array( 'orpd_license', 'orpd_activate_license' ) );

        //Pickup date settings on Date Settings link page
        add_action( 'orddd_after_enable_delivery_date_setting', array( &$this, 'orddd_new_pickup_date_settings' ), 10 );
        //Hidden function
        add_action( 'orddd_before_checkout_delivery_date', array( &$this, 'orddd_enable_for_pickup_date' ) );
        
        //Pickup Date & Pickup Time Slot (if enabled) fields on checkout page
        if ( get_option( 'orddd_enable_delivery_date' ) == 'on' && ( get_option( 'orddd_enable_time_slot' ) == 'on' || get_option( 'orddd_enable_shipping_based_delivery' ) == 'on' ) ) {
            add_action( 'orddd_after_checkout_time_slot', array( &$this, 'orddd_pickup_date_field' ) );
        } else {
            add_action( 'orddd_after_checkout_delivery_date', array( &$this, 'orddd_pickup_date_field' ) );
        }

        if( 'on' == get_option( 'orddd_enable_pickup_date' ) && 'on' == get_option( 'orddd_enable_delivery_date' ) ) {
            //Loads the js code on the checkout page
            add_action( 'orddd_include_front_scripts', array( &$this, 'orddd_front_scripts_js' ) );

            //Validates the Pickup Date & Pickup Time Slot while placing the order
            add_action( 'woocommerce_checkout_process', array( &$this, 'orddd_validate_pickup_date' ) );
            add_action( 'woocommerce_checkout_process', array( &$this, 'orddd_validate_pick_up_time_slot' ) );

            //Add Pickup Date & Pickup Time Slot field records in database when order is placed
            add_filter( 'woocommerce_checkout_update_order_meta', array( &$this, 'orddd_update_pickup_date_pickup_time' ), 11 );

            //Pickup Date & Pickup Time Slot in list of orders on WooCommerce Orders page in Admin
            add_action( 'orddd_add_value_to_woocommerce_custom_column', array( &$this, 'orddd_add_value_to_woocommerce_custom_column_function' ), 10, 2 );

            //Pickup Date & Pickup Time Slot on Order received page
            add_filter( 'woocommerce_order_details_after_order_table', array( &$this, 'orddd_add_pickup_date_time_to_order_page_woo' ), 10, 1 );

            //Pickup Date & Pickup Time Slot on WooCommerce Edit Order page in Admin
            if ( get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'billing_section' ) {
                add_action( 'woocommerce_admin_order_data_after_billing_address', array( &$this, 'orddd_display_pickup_date_time_admin_order_meta') , 11, 1 );
            } else if ( get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'shipping_section'|| get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'before_order_notes' || get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'after_order_notes' ) {
                add_action( 'woocommerce_admin_order_data_after_shipping_address', array( &$this, 'orddd_display_pickup_date_time_admin_order_meta') , 11, 1 );
            }
            
            //Pickup Date & Pickup Time Slot field added in the Customer notification email
            if ( get_option( 'orddd_show_delivery_date_in_customer_email' ) == 'on' ) {
                if ( defined( 'WOOCOMMERCE_VERSION' ) && version_compare( WOOCOMMERCE_VERSION, "2.3", '>=' ) < 0 ) {
                    add_filter( 'woocommerce_email_order_meta_fields', array( &$this, 'orddd_add_pickup_date_time_to_order_woo_new' ), 12, 3 );
                } else {
                    add_filter( 'woocommerce_email_order_meta_keys', array( &$this, 'orddd_add_pickup_date_time_to_order_woo_deprecated' ), 12, 1 );
                }
            }    

            //Adds Pickup Date & Pickup Time Slot field to the qtip of the Delivery Calendar.
            add_filter( 'orddd_add_custom_field_value_to_qtip', array( &$this, 'orddd_add_custom_field_value_to_qtip_function' ) );

            //To send the Pickup Date as a end date in the Delivery Calendar
            add_filter( 'orddd_to_add_end_date', array( &$this, 'orddd_to_add_end_date_function' ), 10 );  

            //To send the Pickup Date & Time as a end date in the Google Calendar     
            add_filter( 'orddd_to_add_end_date_to_gcal', array( &$this, 'orddd_to_add_end_date_to_gcal_function' ), 10, 3 );
            add_filter( 'orddd_to_add_end_time_to_gcal', array( &$this, 'orddd_to_add_end_time_to_gcal_function' ), 10, 3 );
        }
    }

    public function orpd_check_if_plugin_active() {
        if ( !is_plugin_active( 'order-delivery-date/order_delivery_date.php' ) ) {
            if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
                deactivate_plugins( plugin_basename( __FILE__ ) );
                add_action( 'admin_notices', array( &$this, 'orpd_error_notice' ) );
                if ( isset( $_GET[ 'activate' ] ) ) {
                    unset( $_GET[ 'activate' ] );
                }
            }
        }
    }

    public function orpd_error_notice() {
        $class = 'notice notice-error';
        if( !is_plugin_active( 'order-delivery-date/order_delivery_date.php' ) ) {
            $message = __( '<b>Order Pickup Date and Time for WooCommerce Addon</b> requires <b>Order Delivery Date Pro for WooCommerce</b> plugin installed and activate.', 'order-delivery-date' );
        }
        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
    }

    public function orddd_front_scripts_js() {
        global $orpd_version, $wp;
        if ( get_option( 'orddd_enable_delivery_date' ) == 'on' ) {
            $delivery_calendar_for_shipping_methods = orddd_common::orddd_is_delivery_calendar_enabled_for_custom_delivery();
            if( 'delivery_calendar' == get_option( 'orddd_delivery_checkout_options' ) || 'on' == get_option( 'orddd_enable_shipping_based_delivery' )  ) {
                wp_enqueue_script( 'order-pickup-date-orddd', plugins_url('/js/order-pickup-date.js', __FILE__ ), '', $orpd_version, false );
            }
        }
    }

    public function orpd_addon_for_orddd_menu() {
        $page = add_submenu_page( 'order_delivery_date', __( 'Activate Order Pickup Date and Time License', 'order-delivery-date' ), __( 'Activate Order Pickup Date and Time License', 'order-delivery-date' ), 'manage_woocommerce', 'orpd_license_page', array( 'orpd_license', 'orpd_sample_license_page' ) );
    }
    
    public function orddd_new_pickup_date_settings() {
        add_settings_field(
            'orddd_enable_pickup_date',
            'Enable Pickup Date and Pickup Time:',
            array( &$this, 'orddd_enable_pickup_date_callback' ),
            'orddd_date_settings_page',
            'orddd_date_settings_section',
            array( 'Enable Pickup Date and Pickup Time capture on the checkout page along with Delivery fields.' )
        );
        
        register_setting(
            'orddd_date_settings',
            'orddd_enable_pickup_date'
        );
    }
    
    public static function orddd_enable_pickup_date_callback( $args ) {
        $enable_pickup_date = "";
        if ( 'on' == get_option( 'orddd_enable_pickup_date' ) ) {
            $enable_pickup_date = "checked";
        }
        
        echo '<input type="checkbox" name="orddd_enable_pickup_date" id="orddd_enable_pickup_date" value="on" ' . $enable_pickup_date . ' />';
            
        $html = '<label for="orddd_enable_pickup_date"> ' . $args[0] . '</label>';
        echo $html;
    }
    
    public static function orddd_enable_for_pickup_date() {
        $display_datepicker = $display = '';
        if ( 'on' == get_option( 'orddd_enable_pickup_date' ) && !is_account_page() ) {
            $field_name = 'e_pickupdate';
            $display = $min_date = '';
            $disabled_days = array();
            echo '<input type="hidden" name="h_pickupdate" id="h_pickupdate" value="">';
            echo '<input type="hidden" name="orddd_pickup_field_name" id="orddd_pickup_field_name" value="' . $field_name . '">';           
            
            $options_str = orddd_common::get_datepicker_options();
            
            $current_time = current_time( 'timestamp' );
            if ( get_option( 'orddd_enable_same_day_delivery' ) == 'on' && get_option( 'orddd_enable_delivery_date' ) == 'on' ){
                $current_date = date( 'd', $current_time );
                $current_month = date( 'm', $current_time );
                $current_year = date( 'Y', $current_time );
                $cut_off_hour = get_option( 'orddd_disable_same_day_delivery_after_hours' );
                $cut_off_minute = get_option( 'orddd_disable_same_day_delivery_after_minutes' );
                $cut_off_timestamp = gmmktime( $cut_off_hour, $cut_off_minute, 0, $current_month, $current_date, $current_year );
                if ( $cut_off_timestamp > $current_time ) {
                } else {
                    $disabled_days[] = date( ORDDD_HOLIDAY_DATE_FORMAT, $current_time );
                }
            }
            
            if ( get_option( 'orddd_enable_next_day_delivery' ) == 'on' && get_option( 'orddd_enable_delivery_date' ) == 'on' ) {
                $current_date = date( 'd', $current_time );
                $current_month = date( 'm', $current_time );
                $current_year = date( 'Y', $current_time );
                $cut_off_hour = get_option( 'orddd_disable_next_day_delivery_after_hours' );
                $cut_off_minute = get_option( 'orddd_disable_next_day_delivery_after_minutes' );
                $cut_off_timestamp = gmmktime( $cut_off_hour, $cut_off_minute, 0, $current_month, $current_date, $current_year );
                if ( $cut_off_timestamp > $current_time ) {
                } else {
                    $disabled_days[] = date( ORDDD_HOLIDAY_DATE_FORMAT, $current_time +86400 );
                }
            }    
                    
            $orddd_pickup_field_note_text = __( 'Please choose your pickup date.', 'order-delivery-date' );
            echo '<input type="hidden" name="orddd_pickup_field_note_text" id="orddd_pickup_field_note_text" value="' . $orddd_pickup_field_note_text . '">';
            
            $hidden_vars_str = orddd_common::orddd_get_shipping_based_settings();
            
           
        }
        return $display;   
    }
    
    function orddd_pickup_date_field( $checkout ) {
        if ( get_option( 'orddd_enable_pickup_date' ) == 'on' && !is_account_page() ) {
            $validate_wpefield = $validate_pickup_time_wpefield = false;
            if ( get_option( 'orddd_date_field_mandatory' ) == 'checked' ) {
                $validate_wpefield = true;
            }
            
            $result = array ( __( "Select a time slot", "order-delivery-date" ) );
            if (  get_option( 'orddd_time_slot_mandatory' ) == 'checked' ) {
                $validate_pickup_time_wpefield = true;
            }
            
            if ( is_object( $checkout ) ) {
                woocommerce_form_field( 'e_pickupdate', array(
                    'type'              => 'text',
                    'label'             => __( 'Pickup Date', 'order-delivery-date' ),
                    'required'          => $validate_wpefield,
                    'placeholder'       => __( get_option( 'orddd_delivery_date_field_placeholder' ), 'order-delivery-date' ),
                    'custom_attributes' => array( 'style'=>'cursor:text !important;')
                ),
                $checkout->get_value( 'e_pickupdate' ) );
                
                if ( get_option( 'orddd_enable_delivery_date' ) == 'on' && ( get_option( 'orddd_enable_time_slot' ) == 'on' || get_option( 'orddd_enable_shipping_based_delivery' ) == 'on' ) ) {
                   woocommerce_form_field( 'pickup_time_slot', array(
                                                'type'              => 'select',
                                                'label'             => __( 'Pickup Time Slot', 'order-delivery-date' ),
                                                'required'          => $validate_pickup_time_wpefield,
                                                'options'           => $result,
                                                'custom_attributes' => array('disabled'=>'disabled', 'style'=>'cursor:not-allowed !important;' )
                    ),
                    $checkout->get_value( 'pickup_time_slot' ) );
                }
            } else {
                woocommerce_form_field( 'e_pickupdate', array(
                    'type'              => 'text',
                    'label'             => __( 'Pickup Date', 'order-delivery-date' ),
                    'required'          => $validate_wpefield,
                    'placeholder'       => __( get_option( 'orddd_delivery_date_field_placeholder' ), 'order-delivery-date' ),
                    'custom_attributes' => array( 'style'=>'cursor:text !important;')
                ) );
                
                if ( get_option( 'orddd_enable_delivery_date' ) == 'on' && ( get_option( 'orddd_enable_time_slot' ) == 'on' || get_option( 'orddd_enable_shipping_based_delivery' ) == 'on' ) ) {
                    woocommerce_form_field( 'pickup_time_slot', array(
                        'type'              => 'select',
                        'label'             => __( 'Pickup Time Slot', 'order-delivery-date' ),
                        'required'          => $validate_pickup_time_wpefield,
                        'options'           => $result,
                        'custom_attributes' => array('disabled'=>'disabled', 'style'=>'cursor:not-allowed !important;' )
                    ) );
                }
            }

            // code to remove the choosen class added from checkout field editor plugin.
            echo '<script type="text/javascript" language="javascript">
            jQuery( document ).ready( function() {
                load_pickup_date();
                jQuery( "#pickup_time_slot" ).removeClass();
            } );';
            echo '</script>';
            $custom_override_fields = order_pickup_date::custom_override_checkout_pickup_date_time_fields();
            echo $custom_override_fields; 
            
        }
    }

    /**
     * Validate delivery date field
     **/
    
    public static function orddd_validate_pickup_date() {
        $date_mandatory = "No";
        if( isset( $_POST[ 'date_mandatory_for_shipping_method' ] ) ) {
            if( $_POST[ 'date_mandatory_for_shipping_method' ] == "checked" ) {
               $date_mandatory = "Yes";
            } else if( $_POST[ 'date_mandatory_for_shipping_method' ] == "" ) {
                $date_mandatory = "No";
            }  
        } else if( get_option( 'orddd_date_field_mandatory' ) == 'checked' && get_option( 'orddd_enable_delivery_date' ) == 'on' ) {
            $date_mandatory = "Yes";
        }
        
        if ( $date_mandatory == "Yes" ) {
            global $woocommerce;
            $delivery_enabled = orddd_common::orddd_is_delivery_enabled();
            $is_delivery_enabled = 'yes';
            if ( $delivery_enabled == 'no' ) {
                $is_delivery_enabled = 'no';
            }
             
            if( isset( $_POST[ 'e_pickupdate' ] ) ) {
                $delivery_date = $_POST[ 'e_pickupdate' ];
            } else {
                $delivery_date = '';
            }
        
            if( $is_delivery_enabled == 'yes' ) {
                 //Check if set, if its not set add an error.
                if ( $delivery_date == '' ) {
                    $message = '<strong>Pickup Date</strong>' . ' ' . __( 'is a required field.', 'order-delivery-date' );
                    wc_add_notice( $message, $notice_type = 'error' );
                }
            }
        }
    }

    /**
     * Validate Time slot field
     */
    
    public static function orddd_validate_pick_up_time_slot() {
        $timeslot_mandatory = "No";
        if( isset( $_POST[ 'time_slot_enable_for_shipping_method' ] ) && $_POST[ 'time_slot_enable_for_shipping_method' ] == 'on' ) {
            if( isset( $_POST[ 'time_slot_mandatory_for_shipping_method' ] ) && $_POST[ 'time_slot_mandatory_for_shipping_method' ] == "checked" ) {
                $timeslot_mandatory = "Yes";
            } else if( isset( $_POST[ 'time_slot_mandatory_for_shipping_method' ] ) && $_POST[ 'time_slot_mandatory_for_shipping_method' ] == "" ) {
                $timeslot_mandatory = "No";
            }
        } else if( !isset( $_POST[ 'time_slot_enable_for_shipping_method' ] ) ) {
            if( get_option( 'orddd_enable_time_slot' ) == 'on' && get_option( 'orddd_time_slot_mandatory' ) == 'checked' && get_option( 'orddd_enable_delivery_date' ) == 'on' ) {
               $timeslot_mandatory = "Yes";
            }
        }
        
        if( $timeslot_mandatory == "Yes" )  {
            global $woocommerce;
            $delivery_enabled = orddd_common::orddd_is_delivery_enabled();
            $is_delivery_enabled = 'yes';
            if ( $delivery_enabled == 'no' ) {
                $is_delivery_enabled = 'no';
            }
             
            
            if( isset( $_POST[ 'pickup_time_slot' ] ) ) {
                $ts = $_POST[ 'pickup_time_slot' ];
            } else {
                $ts = '';
            }
            
            if( $is_delivery_enabled == 'yes' ) {
                if ( $ts == '' || $ts == 'choose' || $ts == 'NA' || $ts == 'select' ) {
                    $message = '<strong>Pickup Time Slot</strong>' . ' ' . __( 'is a required field.', 'order-delivery-date' );
                    wc_add_notice( $message, $notice_type = 'error' );
                }
            }
        }
    }
    
    public static function custom_override_checkout_pickup_date_time_fields() {
        global $wpdb;
        if ( get_option( 'orddd_enable_shipping_based_delivery' ) == 'on' && get_option( 'orddd_enable_delivery_date' ) == 'on' && get_option( 'orddd_enable_pickup_date' ) == 'on' ) {
            $shipping_based_settings_query = "SELECT option_value, option_name FROM `" . $wpdb->prefix . "options` WHERE option_name LIKE 'orddd_shipping_based_settings_%' AND option_name != 'orddd_shipping_based_settings_option_key' ORDER BY option_id DESC";
            $results = $wpdb->get_results( $shipping_based_settings_query );
            $shipping_settings =  array();
            $shipping_method_based_settings = 'no';
            foreach ( $results as $key => $value ) {
                $shipping_settings = get_option( $value->option_name );
                if( isset( $shipping_settings[ 'delivery_settings_based_on' ][ 0 ] ) && $shipping_settings[ 'delivery_settings_based_on' ][ 0 ] == 'shipping_methods' ) {
                    $shipping_method_based_settings = 'yes';
                    break;
                }
            }
    
            echo '<script type="text/javascript">';
            if( $shipping_method_based_settings == 'yes' ) {
                echo '
                jQuery(document).on( "change", "input[name=\"shipping_method[0]\"]", function() {
                    load_pickup_date();
                });
                jQuery(document).on( "change", "select[name=\"shipping_method[0]\"]", function() {
                    load_pickup_date();
                });';
            }
    
            $load_delivery_date = order_pickup_date::orddd_load_pickup_date();
            echo $load_delivery_date;
            
            echo '</script>';
        }
    }
    
    public static function orddd_update_pickup_date_pickup_time( $order_id ) {
        global $wpdb;
        if ( isset( $_POST[ 'e_pickupdate' ] ) && $_POST[ 'e_pickupdate' ] != '' ) {
            if( isset( $_POST[ 'h_pickupdate' ] ) ){
                $delivery_date = $_POST[ 'h_pickupdate' ];
            } else {
                $delivery_date = '';
            }
            $date_format = 'dd-mm-y';
            
            update_post_meta( $order_id, 'Pickup Date', esc_attr( $_POST[ 'e_pickupdate' ] ) );
                
            $shipping_based = "No";
            $shipping_based_settings_query = "SELECT option_value, option_name FROM `".$wpdb->prefix."options` WHERE option_name LIKE 'orddd_shipping_based_settings_%' AND option_name != 'orddd_shipping_based_settings_option_key' ORDER BY option_id DESC";
            $results = $wpdb->get_results( $shipping_based_settings_query );
            $shipping_settings = array();
            $shipping_based_lockout = "No";
            if( get_option( 'orddd_enable_shipping_based_delivery' ) == 'on' && count( $results ) > 0) {
                foreach ( $results as $key => $value ) {
                    $shipping_based_lockout = "No";
                    $shipping_settings = get_option( $value->option_name );
                    if( isset( $shipping_settings[ 'delivery_settings_based_on' ][ 0 ] ) && $shipping_settings[ 'delivery_settings_based_on' ][ 0 ] == 'shipping_methods' ) {
                        if( isset( $shipping_settings[ 'shipping_methods' ] ) ) {
                            $shipping_methods = $shipping_settings[ 'shipping_methods' ];
                        } else {
                            $shipping_methods = array();
                        }
        
                        if( isset( $_POST[ 'shipping_method' ][ 0 ] ) && $_POST[ 'shipping_method'][ 0 ] != '' ) {
                            $shipping_method = $_POST[ 'shipping_method' ][ 0 ];
                        } else {
                            $shipping_method = '';
                        }
        
                    } else if( isset( $shipping_settings[ 'delivery_settings_based_on' ][ 0 ] ) && $shipping_settings[ 'delivery_settings_based_on' ][ 0 ] == 'product_categories' ) {
                        if( isset( $shipping_settings[ 'product_categories' ] ) ) {
                            $shipping_methods = $shipping_settings[ 'product_categories' ];
                        } else {
                            $shipping_methods = array();
                        }
        
                        if( isset( $_POST[ 'orddd_category_settings_to_load' ] ) && $_POST[ 'orddd_category_settings_to_load'] != '' ) {
                            $shipping_method = $_POST[ 'orddd_category_settings_to_load' ];
                        } else {
                            $shipping_method = '';
                        }
                    }
                    if( in_array( $shipping_method, $shipping_methods ) ) {
                        if( isset( $_POST[ 'time_setting_enable_for_shipping_method' ] ) && $_POST[ 'time_setting_enable_for_shipping_method' ] == 'on' ) {
                            $time_setting[ 'enable' ] = $_POST[ 'time_setting_enable_for_shipping_method' ];
                            $time_setting[ 'time_selected' ] = $_POST[ 'orddd_time_settings_selected' ];
                        } else {
                            $time_setting = '';
                        }
                        $shipping_based = "Yes";
                    }
                }
            }
             
            if ( $shipping_based == "No" ) {
                if( get_option( 'orddd_enable_delivery_time' ) == 'on' ) {
                    $time_setting[ 'enable' ] = get_option( 'orddd_enable_delivery_time' );
                    $time_setting[ 'time_selected' ] = $_POST[ 'orddd_time_settings_selected' ];
                } else {
                    $time_setting = '';
                }
            }
        
            $timestamp = orddd_common::orddd_get_timestamp( $delivery_date, $date_format, $time_setting );
            update_post_meta( $order_id, '_orddd_pickup_timestamp', $timestamp );
        
            orddd_process::orddd_update_lockout_days( $delivery_date );
                            
        } else {
            global $woocommerce;
            $delivery_enabled = orddd_common::orddd_is_delivery_enabled();
            $is_delivery_enabled = 'yes';
            if ( $delivery_enabled == 'no' ) {
                $is_delivery_enabled = 'no';
            }
        
            if( $is_delivery_enabled == 'yes' ) {
                update_post_meta( $order_id, 'Pickup Date', '' );
            }
        }
        
        if ( isset( $_POST[ 'pickup_time_slot' ] ) && $_POST[ 'pickup_time_slot' ] != '' ) {
            $time_slot = $_POST[ 'pickup_time_slot' ];
                
            if ( $time_slot != '' && $time_slot != 'choose' && $time_slot != 'NA' && $time_slot != 'select' ) {
                update_post_meta( $order_id, 'Pickup Time Slot', esc_attr( $time_slot ) );
                    
                $timeslt = $_POST[ 'pickup_time_slot' ];
                if( isset( $_POST[ 'h_pickupdate' ] ) ) {
                    $h_deliverydate = $_POST[ 'h_pickupdate' ];
                } else {
                    $h_deliverydate = '';
                }
                orddd_process::orddd_update_time_slot( $timeslt, $h_deliverydate );
            }
        }
    }
    
    public static function orddd_add_pickup_date_time_to_order_page_woo( $order ) {
        global $orddd_date_formats;
        if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
            $order_id = $order->get_id();
        } else {
            $order_id = $order->id;
        }

        $delivery_date_formatted = order_pickup_date::orddd_get_order_pickup_date( $order_id );
        
        if( $delivery_date_formatted != '' ) {
            echo '<p><strong>' . __( 'Pickup Date', 'order-delivery-date' ) . ':</strong> ' . $delivery_date_formatted . '</p>';
        }
        
        $order_page_time_slot = order_pickup_date::orddd_get_order_pickup_timeslot( $order_id );
        if( $order_page_time_slot != "" && $order_page_time_slot != '' ) {
            echo '<p><strong>' . __( 'Pickup Time Slot', 'order-delivery-date' ) . ': </strong>' . $order_page_time_slot;
        }                   
    }
    
    public static function orddd_display_pickup_date_time_admin_order_meta( $order ) {
        global $orddd_date_formats;
        $field_date_label = 'Pickup Date';
        
        if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
            $order_id = $order->get_id();
        } else {
            $order_id = $order->id;
        }

        $delivery_date_formatted = order_pickup_date::orddd_get_order_pickup_date( $order_id  );
        if( $delivery_date_formatted != '' ) {
            echo '<p><strong>' . __( $field_date_label, 'order-delivery-date' ) . ': </strong>' . $delivery_date_formatted;
        }
        
        $time_slot = order_pickup_date::orddd_get_order_pickup_timeslot( $order_id );
        if ( $time_slot != '' && $time_slot != '' ) {
            echo '<p>' . 'Pickup Time Slot' . ': ' . $time_slot . '</p>';
        }
    }
    
    public static function orddd_add_pickup_date_time_to_order_woo_new( $fields, $sent_to_admin, $order ) {
        if ( get_option( 'orddd_enable_delivery_date' ) == 'on' ) {
            $fields[ 'Pickup Date' ] = array(
                'label' => __( 'Pickup Date', 'order-delivery-date' ),
                'value' => get_post_meta( $order->id, 'Pickup Date', true ),
            );
        }
        
        if( get_option( 'orddd_enable_time_slot' ) == 'on' || ( isset( $_POST[ 'time_slot_enable_for_shipping_method' ] ) && $_POST[ 'time_slot_enable_for_shipping_method' ] == 'on' ) ) {
            $fields[ 'Pickup Time Slot' ] = array(
                'label' => __( 'Pickup Time Slot', 'order-delivery-date' ),
                'value' => get_post_meta( $order->id, 'Pickup Time Slot', true ),
            );
        }
        
        return $fields;
    }
    
    public static function orddd_add_pickup_date_time_to_order_woo_deprecated( $keys ) {
        if ( get_option( 'orddd_enable_delivery_date' ) == 'on' ) {
            $keys[] = 'Pickup Date';
        }
            
        if( get_option( 'orddd_enable_time_slot' ) == 'on' || ( isset( $_POST[ 'time_slot_enable_for_shipping_method' ] ) && $_POST[ 'time_slot_enable_for_shipping_method' ] == 'on' ) ) {
            $keys[] = 'Pickup Time Slot';
        }
        
        return $keys;
    }
    
    public static function orddd_get_order_pickup_date( $order_id ) {
        global $orddd_date_formats;
        $order_time_slot = '';
        $data = get_post_meta( $order_id );
        $field_date_label = 'Pickup Date';
        $delivery_date_formatted = $delivery_date_timestamp = '';
        if ( isset( $data[ '_orddd_pickup_timestamp' ] ) || isset( $data[ $field_date_label ] ) || isset( $delivery_date_for_default_label ) ) {
            if ( isset( $data[ '_orddd_pickup_timestamp' ] ) ) {
                $delivery_date_timestamp = $data[ '_orddd_pickup_timestamp' ][ 0 ];
            }
            $delivery_date_formatted = '';
            if ( $delivery_date_timestamp != '' ) {
                $delivery_date_formatted = date( $orddd_date_formats[ get_option( 'orddd_delivery_date_format') ], $delivery_date_timestamp );
                $time_settings = date( "H:i", $delivery_date_timestamp );
                if ( $time_settings != '00:01' && $time_settings != '00:00' ) {
                    $time_format = get_option( 'orddd_delivery_time_format' );
                    if ( $time_format == '1' ) {
                        $time_format_to_show = 'h:i A';
                    } else {
                        $time_format_to_show = 'H:i';
                    }
                    $delivery_date_formatted = date( $orddd_date_formats[ get_option( 'orddd_delivery_date_format' ) ].' '.$time_format_to_show, $delivery_date_timestamp );
                }
            }
            $delivery_date_formatted = orddd_common::delivery_date_language( $delivery_date_formatted, $delivery_date_timestamp );
        }
        return $delivery_date_formatted;
    }
    
    public static function orddd_get_order_pickup_timeslot( $order_id ) {
        $order_time_slot = '';
        $data = get_post_meta( $order_id );
        $field_label = 'Pickup Time Slot';
        if( $field_label != '' ) {
            if( array_key_exists( $field_label, $data ) ) {
                $order_time_slot = $data[ $field_label ][ 0 ];
            }
        }
        return $order_time_slot;
    }
    
    public static function orddd_load_pickup_date() {
        global $post, $orddd_weekdays;
        $var = $disabled_days_str = $load_delivery_var = '';
        $field_name = 'e_pickupdate';
        $alldays = $disabled_days = array();
        foreach ( $orddd_weekdays as $n => $day_name ) {
            $alldays[$n] = get_option( $n );
        }
        $alldayskeys = array_keys( $alldays );
        $checked = "No";
        foreach( $alldayskeys as $key ) {
            if ( $alldays[ $key ] == 'checked' ) {
                $checked = "Yes";
            }
        }
        if ( $checked == 'Yes' ) {
            foreach ( $alldayskeys as $key ) {
                $var .= '<input type=\"hidden\" id=\"' . $key . '\" value=\"' . $alldays[ $key ] . '\">';
            }
        } else if ( $checked == 'No' &&  get_option( 'orddd_enable_specific_delivery_dates' ) != 'on' ) {
            foreach ( $alldayskeys as $key ) {
                $var .= '<input type=\"hidden\" id=\"' . $key . '\" value=\"checked\">' ;
            }
        }
    
        if( get_option( 'start_of_week' ) != '' ) {
            $first_day_of_week = get_option( 'start_of_week' );
        }
         
        $options = orddd_common::get_datepicker_options();
         
        $current_time = current_time( 'timestamp' );
        if ( get_option( 'orddd_enable_same_day_delivery' ) == 'on' && get_option( 'orddd_enable_delivery_date' ) == 'on' ){
            $current_date = date( 'd', $current_time );
            $current_month = date( 'm', $current_time );
            $current_year = date( 'Y', $current_time );
            $cut_off_hour = get_option( 'orddd_disable_same_day_delivery_after_hours' );
            $cut_off_minute = get_option( 'orddd_disable_same_day_delivery_after_minutes' );
            $cut_off_timestamp = mktime( $cut_off_hour, $cut_off_minute, 0, $current_month, $current_date, $current_year );
            if ( $cut_off_timestamp > $current_time ) {
            } else {
                $disabled_days[] = date( ORDDD_HOLIDAY_DATE_FORMAT, $current_time );
            }
        }
         
        if ( get_option( 'orddd_enable_next_day_delivery' ) == 'on' && get_option( 'orddd_enable_delivery_date' ) == 'on' ) {
            $current_date = date( 'd', $current_time );
            $current_month = date( 'm', $current_time );
            $current_year = date( 'Y', $current_time );
            $cut_off_hour = get_option( 'orddd_disable_next_day_delivery_after_hours' );
            $cut_off_minute = get_option( 'orddd_disable_next_day_delivery_after_minutes' );
            $cut_off_timestamp = mktime( $cut_off_hour, $cut_off_minute, 0, $current_month, $current_date, $current_year );
            if ( $cut_off_timestamp > $current_time ) {
            } else {
                $disabled_days[] = date( ORDDD_HOLIDAY_DATE_FORMAT, $current_time +86400 );
            }
        }
         
        if ( count( $disabled_days ) > 0 ) {
            $disabled_days_str = addslashes(  '"' . implode( '","', $disabled_days ) . '"' );
        }
        return $load_delivery_var;
    }

    /**
    * This function used to add value on the custom column created on WooCommerce->Orders page
    * 
    * @param string $column, $order_id 
    */

    public static function orddd_add_value_to_woocommerce_custom_column_function( $column, $order_id ) {
        if ( $column == 'order_delivery_date' ) {
            $delivery_date_formatted = order_pickup_date::orddd_get_order_pickup_date( $order_id  );
            echo $delivery_date_formatted;
            
            $time_slot = order_pickup_date::orddd_get_order_pickup_timeslot( $order_id );
            echo '<p>' . $time_slot . '</p>';
        }
    }

    public static function orddd_add_custom_field_value_to_qtip_function( $order_id ) {
        $content = '';
        $delivery_date_formatted = order_pickup_date::orddd_get_order_pickup_date( $order_id  );
        if ( '' != $delivery_date_formatted ) {
            $content = "<tr> <td> <strong>Pickup Date:</strong></td><td> " . $delivery_date_formatted . "</td></tr>";       
        }
                
        $time_slot = order_pickup_date::orddd_get_order_pickup_timeslot( $order_id );
        if ( '' != $time_slot ) {
            $content .= "<tr> <td> <strong>Pickup Time Slot:</strong></td><td> " . $time_slot . "</td></tr>";
        }

        return $content;  
    }

    public static function orddd_to_add_end_date_function( $order_id ) {
        $pickup_date = '';
        $delivery_date_formatted = order_pickup_date::orddd_get_order_pickup_date( $order_id  );
        if ( '' != $delivery_date_formatted ) {
            $post_from_timestamp = strtotime( $delivery_date_formatted );
            $pickup_date = date ( 'Y-m-d H:i:s', $post_from_timestamp );
        }

        return $pickup_date;
    }

    public static function orddd_to_add_end_date_to_gcal_function( $event_details, $event_id, $test ) {
        $pickup_date = '';
        if( isset( $event_details[ 'e_pickupdate' ] ) && '' != $event_details[ 'e_pickupdate' ] ) {
            $pickup_date = date( 'Y-m-d', strtotime( $event_details[ 'e_pickupdate' ] ) );
        }
        return $pickup_date;
    }

    public static function orddd_to_add_end_time_to_gcal_function( $event_details, $event_id, $test ) {
        $pickup_end_time = '';
        if( isset( $event_details[ 'pickup_time_slot' ] ) && $event_details[ 'pickup_time_slot' ] != '' && $event_details[ 'pickup_time_slot' ] != 'NA'  && $event_details[ 'pickup_time_slot' ] != 'choose' && $event_details[ 'pickup_time_slot' ] != 'select' ) {
            $timeslot = explode( " - ", $event_details[ 'pickup_time_slot' ] );
            $from_time = date( "H:i", strtotime( $timeslot[ 0 ] ) );
            if( isset( $timeslot[ 1 ] ) && $timeslot[ 1 ] != '' ) {
                $to_time = date( "H:i", strtotime( $timeslot[ 1 ] ) );
                $pickup_end_time = $to_time;
            } else {
                $pickup_end_time = $from_time;
            }
        } else if( get_option( 'orddd_enable_delivery_time' ) == "on" )  {
            $time_settings_arr = explode( " ", $event_details[ 'e_pickupdate' ] );
            array_pop( $time_settings_arr );
            $time_settings = end( $time_settings_arr );
            $from_time = date( "H:i", strtotime( $time_settings ) );
            $pickup_end_time = $from_time;
        } else {
            $pickup_end_time = "";
        }
        return $pickup_end_time;
    }
}
$order_pickup_date = new order_pickup_date();
?>