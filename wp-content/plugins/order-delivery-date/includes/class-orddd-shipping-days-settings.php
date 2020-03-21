<?php
/**
 * Order Delivery Shipping Days Settings in admin.
 *
 * @author Tyche Softwares
 * @package Order-Delivery-Date-Pro-for-WooCommerce/Admin/Settings/Custom-Delivery
 * @since 2.4
 * @category Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Orddd Shipping Days Settings class
 *
 * @class Orddd_Shipping_Days_Settings
 */
class Orddd_Shipping_Days_Settings {

	/**
	 * Callback for adding Shipping days tab settings
	 */
	public static function orddd_shipping_days_settings_section_callback() {
		?>
		<b><?php esc_html_e( 'Shipping Days', 'order-delivery-date' ); ?></b><?php esc_html_e( 'refers to the working days of your own company.', 'order-delivery-date' ); ?><b><?php esc_html_e( 'Delivery Days', 'order-delivery-date' ); ?></b><?php esc_html_e( 'refers to the working days of your shipping company to whom you submit your orders for deliveries.', 'order-delivery-date' ); ?> <br><?php esc_html_e( 'Leave this unchanged if you handle delivery & shipping by yourself.', 'order-delivery-date' ); ?>
		<a href="https://www.tychesoftwares.com/docs/docs/order-delivery-date-pro-for-woocommerce/setup-delivery-dates/?utm_source=userwebsite&utm_medium=link&utm_campaign=OrderDeliveryDateProSetting" target="_blank" class="dashicons dashicons-external" style="line-height:unset;"></a>
		<?php
	}

	/**
	 * Callback for adding Enable time slot setting
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 */
	public static function orddd_enable_shipping_days_callback( $args ) {
		$orddd_enable_shipping_days = '';
		if ( 'on' === get_option( 'orddd_enable_shipping_days' ) ) {
			$orddd_enable_shipping_days = 'checked';
		}

		?>
		<input type="checkbox" name="orddd_enable_shipping_days" id="orddd_enable_shipping_days" class="day-checkbox" <?php echo esc_attr( $orddd_enable_shipping_days ); ?>/>
		<label for="orddd_enable_shipping_days"><?php echo wp_kses_post( $args[0] ); ?></label>
		<?php
	}

	/**
	 * Callback for adding Shipping Weekdays setting
	 *
	 * @param string $input Weekday slug.
	 * @return string
	 */
	public static function orddd_shipping_day_0_save( $input ) {
		$input = self::return_orddd_shipping_day_input( 'orddd_shipping_day_0' );
		return $input;
	}

	/**
	 * Callback for adding Shipping Weekdays setting
	 *
	 * @param string $input Weekday slug.
	 * @return string
	 */
	public static function orddd_shipping_day_1_save( $input ) {
		$input = self::return_orddd_shipping_day_input( 'orddd_shipping_day_1' );
		return $input;
	}

	/**
	 * Callback for adding Shipping Weekdays setting
	 *
	 * @param string $input Weekday slug.
	 * @return string
	 */
	public static function orddd_shipping_day_2_save( $input ) {
		$input = self::return_orddd_shipping_day_input( 'orddd_shipping_day_2' );
		return $input;
	}

	/**
	 * Callback for adding Shipping Weekdays setting
	 *
	 * @param string $input Weekday slug.
	 * @return string
	 */
	public static function orddd_shipping_day_3_save( $input ) {
		$input = self::return_orddd_shipping_day_input( 'orddd_shipping_day_3' );
		return $input;
	}

	/**
	 * Callback for adding Shipping Weekdays setting
	 *
	 * @param string $input Weekday slug.
	 * @return string
	 */
	public static function orddd_shipping_day_4_save( $input ) {
		$input = self::return_orddd_shipping_day_input( 'orddd_shipping_day_4' );
		return $input;
	}

	/**
	 * Callback for adding Shipping Weekdays setting
	 *
	 * @param string $input Weekday slug.
	 * @return string
	 */
	public static function orddd_shipping_day_5_save( $input ) {
		$input = self::return_orddd_shipping_day_input( 'orddd_shipping_day_5' );
		return $input;
	}

	/**
	 * Callback for adding Shipping Weekdays setting
	 *
	 * @param string $input Weekday slug.
	 * @return string
	 */
	public static function orddd_shipping_day_6_save( $input ) {
		$input = self::return_orddd_shipping_day_input( 'orddd_shipping_day_6' );
		return $input;
	}

	/**
	 * Check if the selected weekday is valid
	 *
	 * @param string $weekday Weekday slug.
	 * @return string $input
	 */
	public static function return_orddd_shipping_day_input( $weekday ) {
		global $orddd_shipping_days;
		$input = '';
		if ( isset( $_POST['orddd_shipping_days'] ) ) {
			$weekdays = $_POST['orddd_shipping_days'];
			if ( in_array( $weekday, $weekdays, true ) ) {
				$input = 'checked';
			}
		}
		return $input;
	}

	/**
	 * Callback for selecting weekdays if 'Weekdays' option is selected
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 */
	public static function orddd_shipping_days_callback( $args ) {
		global $orddd_shipping_days;
		echo '<select class="orddd_shipping_days" id="orddd_shipping_days" name="orddd_shipping_days[]" placeholder="Select Weekdays" multiple="multiple">';
		foreach ( $orddd_shipping_days as $n => $day_name ) {
			if ( 'checked' === get_option( $n ) ) {
				print( '<option name="' . esc_attr( $n ) . '" value="' . esc_attr( $n ) . '" selected>' . esc_attr( $day_name ) . '</option>' );
			} else {
				print( '<option name="' . esc_attr( $n ) . '" value="' . esc_attr( $n ) . '">' . esc_attr( $day_name ) . '</option>' );
			}
		}
		echo '</select>';
		echo '<script>
            jQuery( ".orddd_shipping_days" ).select2();
        </script>';

		?>
		<label for="orddd_shipping_days"><?php echo wp_kses_post( $args[0] ); ?></label>
		<?php
	}
}
