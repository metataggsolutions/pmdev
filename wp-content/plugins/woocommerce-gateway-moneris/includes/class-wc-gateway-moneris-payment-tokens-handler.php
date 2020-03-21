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
 * @copyright Copyright (c) 2012-2019, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_5_0 as Framework;

/**
 * Handle the payment tokenization related functionality.
 *
 * @since 2.5.0
 */
class WC_Gateway_Moneris_Payment_Tokens_Handler extends Framework\SV_WC_Payment_Gateway_Payment_Tokens_Handler {

	/**
	 * Tokenizes the current payment method and adds the standard transaction
	 * data to the order post record.
	 *
	 * @since 2.5.0
	 * @param WC_Order $order the order object
	 * @param WC_Moneris_API_Response $response optional create payment token response, or null if the tokenize payment method request should be made
	 * @return WC_Order the order object
	 * @param string $environment_id optional environment id, defaults to plugin current environment
	 * @throws Exception on network error or request error
	 * @throws Framework\SV_WC_Payment_Gateway_Feature_Unsupported_Exception if payment method tokenization is not supported
	 */
	public function create_token( WC_Order $order, $response = null, $environment_id = null ) {

		// tokenize a previous transaction, so long as it wasn't placed with a
		// temporary token, which must be converted to a permanent token with
		// the add
		if ( $response ) {

			if ( ! Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-temp-payment-token' ) ) {
				// normal credit card account number direct tokenization
				$order->payment->receipt_id = $response->get_receipt_id();
				$order->payment->trans_id   = $response->get_transaction_id();
			} else {
				// temporary token to permanent token, use the masked pan returned
				// by the tokenized request
				$order->payment->card_type = Framework\SV_WC_Payment_Gateway_Helper::card_type_from_account_number( $response->get_masked_pan() );
			}
		}

		// When changing a subscriptions payment method, blank out the original trans id/receipt id so that a new card number will be run and tokenized
		if ( isset( $_POST['woocommerce_change_payment'] ) && $_POST['woocommerce_change_payment'] ) {
			$order->payment->trans_id   = null;
			$order->payment->receipt_id = null;
		}

		return parent::create_token( $order, $response, $environment_id );
	}


	/**
	 * Gets the payment token object identified by $token from the user identified by $user_id.
	 *
	 * @since 2.5.0
	 *
	 * @param int $user_id WordPress user ID, or 0 for guest
	 * @param string $token payment token
	 * @param string|null $environment_id optional environment ID
	 * @return Framework\SV_WC_Payment_Gateway_Payment_Token|null
	 */
	public function get_token( $user_id, $token, $environment_id = null ) {

		if ( $this->get_gateway()->hosted_tokenization_available() && Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-temp-payment-token' ) ) {

			$exp_month = Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-exp-month' );
			$exp_year  = Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-exp-year' );
			$expiry    = Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-expiry' );

			if ( ! $exp_month & ! $exp_year && $expiry ) {
				list( $exp_month, $exp_year ) = array_map( 'trim', explode( '/', $expiry ) );
			}

			// working with a hosted tokenization temp token
			return new Framework\SV_WC_Payment_Gateway_Payment_Token(
				Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-payment-token' ),
				array(
					'type'      => 'credit_card',
					'exp_month' => $exp_month,
					'exp_year'  => $exp_year,
				)
			);
		}

		// normal behavior
		return parent::get_token( $user_id, $token, $environment_id );
	}


	/**
	 * Returns true if the current payment method should be tokenized: whether
	 * requested by customer or otherwise forced.  This parameter is passed from
	 * the checkout page/payment form.
	 *
	 * @since 2.5.0
	 * @return boolean true if the current payment method should be tokenized
	 * @throws Framework\SV_WC_Payment_Gateway_Feature_Unsupported_Exception if payment method tokenization is not supported
	 */
	public function should_tokenize() {

		// make the temp payment token permanent
		return Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-tokenize-payment-method' )
			&& ( ! Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-payment-token' )
			  || Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-temp-payment-token' ) );
	}
}
