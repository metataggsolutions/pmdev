<?php
/**
 * WooCommerce Moneris
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Moneris to newer
 * versions in the future. If you wish to customize WooCommerce Moneris for your
 * needs please refer to http://docs.woocommerce.com/document/moneris/ for more information.
 *
 * @package   WC-Gateway-Moneris/Gateway
 * @author    SkyVerge
 * @copyright Copyright (c) 2012-2018, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\Moneris\Apple_Pay;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_5_0 as Framework;

/**
 * The Moneris Apple Pay frontend handler.
 *
 * @method \SkyVerge\WooCommerce\Moneris\Apple_Pay get_handler()
 * @method \WC_Gateway_Moneris_Credit_Card get_gateway()
 */
class Frontend extends Framework\SV_WC_Payment_Gateway_Apple_Pay_Frontend {


	/**
	 * Enqueues the scripts and styles.
	 *
	 * @since 1.11.0-dev.1
	 */
	public function enqueue_scripts() {

		parent::enqueue_scripts();

		// limit the Moneris Apple Pay SDK to relevant pages, as JS errors will be thrown if it's not utilized
		if ( is_product() || is_cart() || is_checkout() ) {

			$sdk_url = $this->get_gateway()->is_test_environment() ? 'https://esqa.moneris.com/applepayjs/applepay-api.js' : 'https://www3.moneris.com/applepayjs/applepay-api.js';

			wp_register_script( 'moneris-apple-pay-js', $sdk_url, array(), $this->get_plugin()->get_version(), false );
		}

		wp_enqueue_script( 'wc-moneris-apple-pay', $this->get_plugin()->get_plugin_url() . '/assets/js/frontend/wc-moneris-apple-pay.min.js', array( 'jquery', 'moneris-apple-pay-js' ), $this->get_plugin()->get_version(), true );
	}


	/**
	 * Renders the Apple Pay button and Moneris iframe markup.
	 *
	 * @since 1.11.0-dev.1
	 */
	public function render_button() {

		parent::render_button();

		?>
		<div
			id="moneris-apple-pay"
			style="display: none;"
			store-id="<?php echo esc_attr( $this->get_gateway()->get_store_id() ); ?>"
			merchant-identifier="<?php echo esc_attr( $this->get_handler()->get_merchant_id() ); ?>"
			display-name="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></div>
		<?php
	}


	/**
	 * Initializes Apple Pay on the single product page.
	 *
	 * @since 1.11.0-dev.1
	 */
	public function init_product() {

		$args = $this->get_handler_args();

		try {

			$product = wc_get_product( get_the_ID() );

			if ( ! $product ) {
				throw new Framework\SV_WC_Payment_Gateway_Exception( 'Product does not exist.' );
			}

			$payment_request = $this->get_handler()->get_product_payment_request( $product );

			$args['payment_request'] = $payment_request;

		} catch ( Framework\SV_WC_Payment_Gateway_Exception $e ) {

			$this->get_handler()->log( 'Could not initialize Apple Pay. ' . $e->getMessage() );
		}

		// replace the page load nonces now that a session has been created
		$args['create_order_nonce']    = wp_create_nonce( 'wc_moneris_apple_pay_create_order' );
		$args['process_receipt_nonce'] = wp_create_nonce( 'wc_moneris_apple_pay_process_receipt' );

		/**
		 * Filters the Apple Pay product handler args.
		 *
		 * @since 1.11.0-dev.1
		 *
		 * @param array $args
		 */
		$args = apply_filters( 'wc_moneris_apple_pay_product_handler_args', $args );

		wc_enqueue_js( sprintf( 'window.wc_moneris_apple_pay_handler = new WC_Moneris_Apple_Pay_Product_Handler(%s);', json_encode( $args ) ) );

		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'render_button' ) );
	}


	/** Cart functionality ****************************************************/


	/**
	 * Initializes Apple Pay on the cart page.
	 *
	 * @since 1.11.0-dev.1
	 */
	public function init_cart() {

		$args = $this->get_handler_args();

		try {

			$payment_request = $this->get_handler()->get_cart_payment_request( WC()->cart );

			$args['payment_request'] = $payment_request;

		} catch ( Framework\SV_WC_Payment_Gateway_Exception $e ) {

			$args['payment_request'] = false;
		}

		/**
		 * Filters the Apple Pay cart handler args.
		 *
		 * @since 1.11.0-dev.1
		 *
		 * @param array $args
		 */
		$args = apply_filters( 'wc_moneris_apple_pay_cart_handler_args', $args );

		wc_enqueue_js( sprintf( 'window.wc_moneris_apple_pay_handler = new WC_Moneris_Apple_Pay_Cart_Handler(%s);', json_encode( $args ) ) );

		add_action( 'woocommerce_proceed_to_checkout', array( $this, 'render_button' ) );
	}


	/** Checkout functionality ************************************************/


	/**
	 * Initializes Apple Pay on the checkout page.
	 *
	 * @since 1.11.0-dev.1
	 */
	public function init_checkout() {

		/**
		 * Filters the Apple Pay checkout handler args.
		 *
		 * @since 1.11.0-dev.1
		 *
		 * @param array $args
		 */
		$args = apply_filters( 'wc_moneris_apple_pay_checkout_handler_args', $this->get_handler_args() );

		wc_enqueue_js( sprintf( 'window.wc_moneris_apple_pay_handler = new WC_Moneris_Apple_Pay_Checkout_Handler(%s);', json_encode( $args ) ) );

		if ( $this->get_plugin()->is_plugin_active( 'woocommerce-checkout-add-ons.php' ) ) {
			add_action( 'woocommerce_review_order_before_payment', array( $this, 'render_button' ) );
		} else {
			add_action( 'woocommerce_before_checkout_form', array( $this, 'render_checkout_button' ), 15 );
		}
	}


	/**
	 * Gets the Moneris handler args.
	 *
	 * @since 1.11.0-dev.1
	 *
	 * @return array
	 */
	protected function get_handler_args() {

		return array(
			'create_order_nonce'    => wp_create_nonce( 'wc_moneris_apple_pay_create_order' ),
			'process_receipt_nonce' => wp_create_nonce( 'wc_moneris_apple_pay_process_receipt' ),
			'debug_log'             => ! $this->get_gateway()->debug_off(),
		);
	}


}
