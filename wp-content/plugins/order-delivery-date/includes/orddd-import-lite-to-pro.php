<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This class will import the data of lite version to the pro version.
 * In v9.6, only the delivery dates are being imported.
 * TODO: The delivery settings should also be prompted for import
 * @since 9.6
 *
 */
if ( !class_exists( 'orddd_import_lite_to_pro' ) ) {

    class orddd_import_lite_to_pro {

        public static function orddd_show_import_data() {
            ob_start();
            orddd_import_lite_to_pro::orddd_get_import_data();
        }

        public static function orddd_get_import_data() {

            global $orddd_version;
            ?>
            <div class="wrap about-wrap">
                <h2><?php printf( __( 'Welcome to Order Delivery Date Pro for WooCommerce v%s', 'order-delivery-date' ), $orddd_version ); ?></h2>
            <?php
                orddd_import_lite_to_pro::orddd_display_information_of_lite_active();
                ?>
                 <div id = "orddd_import_yes_no" class = "orddd_import_yes_no" > 
                <?php
                    orddd_import_lite_to_pro::orddd_display_yes_button();
                    orddd_import_lite_to_pro::orddd_display_no_button();
                ?>
                </div>
            </div>
            <?php
        }

        public static function orddd_display_information_of_lite_active () {
            ?>
            <div>
                
                <p><?php _e( 'We have noticed that you are using the Lite version of the Order Delivery Date plugin on your store. Thus, before activating the Pro version, you should choose if you want to import the Lite version data in Pro. We fully understand the importance of data captured in the Lite version of the plugin & hence would like to give you an option to choose what you want to do with that data.', 'order-delivery-date' ); ?></p>
                
                <p><?php _e( 'You can import all your data by clicking on the <strong>Yes </strong> button below. In the next step, you will be asked to select what data you want to import before beginning the actual process of import.', 'order-delivery-date' ); ?></p>
                
                <p><?php _e( 'If you don\'t wish to import the data from the Lite version of the plugin, then please click on the <strong>No </strong>button below. Selecting <strong>No </strong>would deactivate the Lite version & keep the Pro version active.', 'order-delivery-date' ); ?></p>
                
                <div id = "orddd_import_checkboxes" class = "orddd_import_checkboxes" style="display: none">

                <hr><p><?php _e( 'You can choose what data you want to import from Lite plugin using the options below:', 'order-delivery-date' ); ?></p>

                <table>
                    <tr>
                        <td>
                            <?php _e( 'Delivery Dates:', 'order-delivery-date' ); ?>         
                        </td>
                        <td>
                          &nbsp<input type="checkbox" name="orddd_delivery_dates_import" value = "orddd_delivery_dates_import" id = "orddd_delivery_dates_import" checked>  Delivery dates of orders placed with the Lite version will be retained.
                        </td>
                    </tr>

                    <!-- 
                    <tr>
                        <td>
                            <?php _e( 'Settings:', 'order-delivery-date' ); ?>         
                        </td>
                        <td>
                            &nbsp<input type="checkbox" name="wcap_settings_import" id = "wcap_settings_import" value ="wcap_settings_import" checked>  
                        </td>
                    </tr>
                -->
                </table>
                <h5> 
                <?php _e( '<strong>Note: Once the import is complete, the Lite version will be deactivated & Pro version will remain active.</strong>', 'order-delivery-date' ); ?>
                 </h5>
                <?php
                    orddd_import_lite_to_pro::orddd_display_import_button();
                    orddd_import_lite_to_pro::orddd_display_no_button();
                ?>
                </div>
            </div>
            <?php
        }

        public static function orddd_display_yes_button () {
            ?>
            <input type="submit" name="submit" id="orddd-import-yes" class="button button-primary orddd-import-yes" value="Yes"  />
            <?php
        }

        public static function orddd_display_no_button() {
            ?>
            <input type="submit" name="submit" id="orddd-import-no" class="button button-primary orddd-import-no" value="No"  />
            <?php
        }

        public static function orddd_display_import_button() {
            ?>
            <input type="submit" name="submit" id="orddd-import-now" class="button button-primary orddd-import-now" value="Import data"  />
            <?php
        }


        public static function orddd_migrate_admin_init() {

            $orddd_is_import_page_displayed = get_option( 'orddd_import_page_displayed' );
            $orddd_is_lite_data_imported    = get_option( 'orddd_lite_data_imported' );

            if ( is_plugin_active( 'order-delivery-date-for-woocommerce/order_delivery_date.php' ) && 
               ( $orddd_is_import_page_displayed != 'yes' || '' != $orddd_is_import_page_displayed ) && false === $orddd_is_import_page_displayed  ) {
                
                update_option( 'orddd_import_page_displayed', 'yes' );    
                wp_safe_redirect( admin_url( 'admin.php?page=orddd-update' ) );
                exit;
            }

            if ( !isset( $_GET ['page'] ) || ( isset( $_GET ['page'] ) && 
                'orddd-update' != $_GET ['page'] ) ) {

                if ( $orddd_is_import_page_displayed == 'yes' && 
                    in_array( 'order-delivery-date-for-woocommerce/order_delivery_date.php', (array) get_option( 'active_plugins', array() ) ) ) {

                    $wcap_lite_plugin_path =   ( dirname( dirname ( 'order_delivery_date.php' ) ) ) . '/order-delivery-date-for-woocommerce/order_delivery_date.php';
                    deactivate_plugins( $wcap_lite_plugin_path );
                }
            }
        }

        public static function orddd_admin_menus() {

            if ( empty( $_GET[ 'page' ] ) ) {
                return;
            }

            $orddd_update_page_name   = __( 'About Order Delivery Date Pro for WooCommerce', 'order-delivery-date' );
            $orddd_welcome_page_title = __( 'Welcome to Order Delivery Date Pro for WooCommerce', 'order-delivery-date' );
            
            add_dashboard_page( $orddd_welcome_page_title, '', 'manage_options', 'orddd-update', array( 'orddd_import_lite_to_pro' , 'orddd_show_import_data') );
        }    


        /**
         * @since: 9.6
         */
        public static function orddd_do_not_import_lite_data() {

            $wcap_lite_plugin_path =   ( dirname( dirname ( 'order_delivery_date.php' ) ) ) . '/order-delivery-date-for-woocommerce/order_delivery_date.php';
            deactivate_plugins( $wcap_lite_plugin_path );

            /**
             * Add option which button is clicked for the record.
             */
            add_option ( 'orddd_lite_data_imported', 'no' ); 
            wp_die();
        }

        /**
         * This function will import the delivery dates of orders placed using the lite version.
         * @since: 9.6
         */
        public static function orddd_import_lite_data() {

            global $wpdb;
            if ( 'true' === $_POST[ 'orddd_import_delivery_dates' ] ) {

                $results = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_orddd_lite_timestamp' " );
                foreach ( $results as $key => $value ) {

                    $order_id                = $value->post_id;
                    $delivery_date_timestamp = $value->meta_value;
                    update_post_meta( $order_id, '_orddd_timestamp', $delivery_date_timestamp );
                }
            }

            $wcap_lite_plugin_path =   ( dirname( dirname ( 'order_delivery_date.php' ) ) ) . '/order-delivery-date-for-woocommerce/order_delivery_date.php';
            deactivate_plugins( $wcap_lite_plugin_path );

            /**
             * Add option which button is clicked for the record.
             */
            add_option ( 'orddd_lite_data_imported', 'yes' );

            echo "All delivery dates have been imported.";
            
            wp_die();
        }


        /**
         * This function will display the notice when delivery dates of Lite plugin are imported to Pro.
         * @since: 9.6
         */
        public static function orddd_migrate_lite_to_pro_notice() {
            if ( isset( $_GET[ 'orddd_lite_import' ] ) && 'yes' === $_GET[ 'orddd_lite_import' ] ) {
                if ( get_post_type() != 'page' && get_post_type() != 'post' ) {
                    $class = 'notice notice-success is-dismissible';
                    $message = __( 'The delivery dates from Lite version have been successfully imported to Pro version.', 'order-delivery-date' );
                    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
                }
            }
        }
    }
}
