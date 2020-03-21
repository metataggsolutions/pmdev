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

use SkyVerge\WooCommerce\PluginFramework\v5_5_0 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * Moneris Payment Gateway Credit Card Class
 *
 * Implements the Moneris eSELECTplus direct credit card API
 *
 * @since 2.0
 */
class WC_Gateway_Moneris_Credit_Card extends Framework\SV_WC_Payment_Gateway_Direct {


	/** @var string integration country, one of INTEGRATION_CA or INTEGRATION_US */
	protected $integration_country;

	/** @var string configured dynamic descriptor */
	protected $dynamic_descriptor;

	/** @var string the configured production store id */
	protected $store_id;

	/** @var string the configured production api token */
	protected $api_token;

	/** @var string the configured test store id for the US integration */
	protected $us_test_store_id;

	/** @var string the configured test store id for the Canadian integration */
	protected $ca_test_store_id;

	/** @var string whether avs is enabled 'yes' or 'no' */
	protected $enable_avs;

	/** @var string how to handle AVS neither street address nor zip code match: 'accept', 'reject', 'hold' */
	protected $avs_neither_match;

	/** @var string how to handle AVS zip code match: 'accept', 'reject', 'hold' */
	protected $avs_zip_match;

	/** @var string how to handle AVS street address match: 'accept', 'reject', 'hold' */
	protected $avs_street_match;

	/** @var string how to handle AVS neither street address nor zip code verified: 'accept', 'reject', 'hold' */
	protected $avs_not_verified;

	/** @var string whether CSC is required for *all* (including tokenized) transactions, 'yes' or 'no' */
	protected $require_csc;

	/** @var string how to handle CVD does not match: 'accept', 'reject', 'hold' */
	protected $csc_not_match;

	/** @var string how to handle CVD not verified: 'accept', 'reject', 'hold' */
	protected $csc_not_verified;

	/** @var string whether hosted tokenization is enabled, 'yes' or 'no' */
	protected $hosted_tokenization;

	/** @var string the production environment hosted tokenization profile id */
	protected $hosted_tokenization_profile_id;

	/** @var string the test environment hosted tokenization profile id */
	protected $test_hosted_tokenization_profile_id;

	/** @var WC_Moneris_API instance */
	protected $api;

	/** @var string hopefully temporary work-around for the inflexible Framework\SV_WC_Payment_Gateway::do_transaction() handling of order notes for held authorize-only transaction */
	protected $held_authorization_status_message;


	/**
	 * Initializes the gateway.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			\WC_Moneris::CREDIT_CARD_GATEWAY_ID,
			wc_moneris(),
			array(
				'method_title'       => __( 'Moneris', 'woocommerce-gateway-moneris' ),
				'method_description' => __( 'Allow customers to securely check out using Moneris', 'woocommerce-gateway-moneris' ),
				'supports'           => array(
					self::FEATURE_PRODUCTS,
					self::FEATURE_CARD_TYPES,
					self::FEATURE_TOKENIZATION,
					self::FEATURE_TOKEN_EDITOR,
					self::FEATURE_CREDIT_CARD_CHARGE,
					self::FEATURE_CREDIT_CARD_AUTHORIZATION,
					self::FEATURE_CREDIT_CARD_CAPTURE,
					self::FEATURE_CREDIT_CARD_CHARGE_VIRTUAL,
					self::FEATURE_DETAILED_CUSTOMER_DECLINE_MESSAGES,
					self::FEATURE_PAYMENT_FORM,
					self::FEATURE_ADD_PAYMENT_METHOD,
					self::FEATURE_REFUNDS,
					self::FEATURE_VOIDS,
					self::FEATURE_APPLE_PAY,
				 ),
				'payment_type'       => self::PAYMENT_TYPE_CREDIT_CARD,
				'environments'       => array(
					self::ENVIRONMENT_PRODUCTION => __( 'Production', 'woocommerce-gateway-moneris' ),
					self::ENVIRONMENT_TEST       => __( 'Sandbox', 'woocommerce-gateway-moneris' )
				),
				'currencies'         => array(), // no currency requirements
			)
		);

		// render additional rows on the WC Status page for various gateway settings
		add_action( 'wc_payment_gateway_' . $this->get_id() . '_system_status_end', array( $this, 'render_status_rows' ) );
	}


	/**
	 * Initializes the capture handler.
	 *
	 * @since 2.11.0
	 */
	public function init_capture_handler() {

		require_once( $this->get_plugin()->get_plugin_path() . '/includes/Handlers/Capture.php' );

		$this->capture_handler = new \SkyVerge\WooCommerce\Moneris\Handlers\Capture( $this );
	}


	/**
	 * Gets the currencies supported by Apple Pay.
	 *
	 * @since 1.11.0-dev.1
	 *
	 * @return array
	 */
	public function get_apple_pay_currencies() {

		return array( 'USD', 'CAD' );
	}


	/**
	 * Renders additional rows on the WC Status page for various gateway settings.
	 *
	 * @internal
	 *
	 * @since 2.10.2
	 */
	public function render_status_rows() {

		?>

		<tr>
			<td data-export-label="Hosted Tokenization Enabled"><?php esc_html_e( 'Hosted Tokenization Enabled', 'woocommerce-gateway-moneris' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( __( 'Displays whether Hosted Tokenization is enabled.', 'woocommerce-gateway-moneris' ) ); ?></td>
			<td>
				<?php if ( $this->hosted_tokenization_enabled() ) : ?>
					<mark class="yes">&#10004;</mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td data-export-label="Integration Country"><?php esc_html_e( 'Integration Country', 'woocommerce-gateway-moneris' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( __( 'Displays the configured integration country.', 'woocommerce-gateway-moneris' ) ); ?></td>
			<td>
				<?php echo esc_html( $this->get_integration_country() ); ?>
			</td>
		</tr>

		<tr>
			<td data-export-label="AVS Enabled"><?php esc_html_e( 'AVS Enabled', 'woocommerce-gateway-moneris' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( __( 'Displays whether AVS is enabled.', 'woocommerce-gateway-moneris' ) ); ?></td>
			<td>
				<?php if ( $this->avs_enabled() ) : ?>
					<mark class="yes">&#10004;</mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
			</td>
		</tr>

		<?php if ( $this->avs_enabled() ) : ?>

			<tr>
				<td data-export-label="AVS Actions"><?php esc_html_e( 'AVS Actions', 'woocommerce-gateway-moneris' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( __( 'Displays the transaction action for each AVS result.', 'woocommerce-gateway-moneris' ) ); ?></td>
				<td>
					<?php /* translators: Placeholders: %s - transaction result action like "hold" or "accept" */
					echo sprintf( esc_html__( 'Neither Match: %s', 'woocommerce-gateway-moneris' ), $this->avs_neither_match ) . ', '; ?>
					<?php /* translators: Placeholders: %s - transaction result action like "hold" or "accept" */
					echo sprintf( esc_html__( 'Zip Match: %s', 'woocommerce-gateway-moneris' ), $this->avs_zip_match ) . ', '; ?>
					<?php /* translators: Placeholders: %s - transaction result action like "hold" or "accept" */
					echo sprintf( esc_html__( 'Street Match: %s', 'woocommerce-gateway-moneris' ), $this->avs_street_match ) . ', '; ?>
					<?php /* translators: Placeholders: %s - transaction result action like "hold" or "accept" */
					echo sprintf( esc_html__( 'Not Verified: %s', 'woocommerce-gateway-moneris' ), $this->avs_not_verified ); ?>
				</td>
			</tr>

		<?php endif; ?>

		<tr>
			<td data-export-label="CSC Enabled"><?php esc_html_e( 'CSC Enabled', 'woocommerce-gateway-moneris' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( __( 'Displays whether CSC validation is enabled.', 'woocommerce-gateway-moneris' ) ); ?></td>
			<td>
				<?php if ( $this->csc_enabled() ) : ?>
					<mark class="yes">&#10004;</mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td data-export-label="CSC Required"><?php esc_html_e( 'CSC Required', 'woocommerce-gateway-moneris' ); ?>:</td>
			<td class="help"><?php echo wc_help_tip( __( 'Displays whether CSC validation is required for all transactions.', 'woocommerce-gateway-moneris' ) ); ?></td>
			<td>
				<?php if ( $this->csc_required() ) : ?>
					<mark class="yes">&#10004;</mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
			</td>
		</tr>

		<?php if ( $this->csc_enabled() ) : ?>

			<tr>
				<td data-export-label="CSC Actions"><?php esc_html_e( 'CSC Actions', 'woocommerce-gateway-moneris' ); ?>:</td>
				<td class="help"><?php echo wc_help_tip( __( 'Displays the transaction action for each CSC result.', 'woocommerce-gateway-moneris' ) ); ?></td>
				<td>
					<?php /* translators: Placeholders: %s - transaction result action like "hold" or "accept" */
					echo sprintf( esc_html__( 'No Match - %s', 'woocommerce-gateway-moneris' ), $this->csc_not_match ) . ', '; ?>
					<?php /* translators: Placeholders: %s - transaction result action like "hold" or "accept" */
					echo sprintf( esc_html__( 'Not Verified - %s', 'woocommerce-gateway-moneris' ), $this->csc_not_verified ); ?>
				</td>
			</tr>

		<?php endif; ?>

		<?php
	}


	/**
	 * Gets an array of form fields specific for this method
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	protected function get_method_form_fields() {

		$form_fields = array(

			'integration_country' => array(
				'title'    => __( 'Integration Country', 'woocommerce-gateway-moneris' ),
				'type'     => 'select',
				'desc_tip' => __( 'Is your Moneris account based in the US or Canada?', 'woocommerce-gateway-moneris' ),
				'default'  => 'CA' == WC()->countries->get_base_country() ? \WC_Moneris::INTEGRATION_CA : WC_Moneris::INTEGRATION_US,
				'options'  => array(
					\WC_Moneris::INTEGRATION_CA => __( 'Canada', 'woocommerce-gateway-moneris' ),
					\WC_Moneris::INTEGRATION_US => __( 'United States', 'woocommerce-gateway-moneris' ),
				),
			),

			'store_id' => array(
				'title'    => __( 'Store ID', 'woocommerce-gateway-moneris' ),
				'type'     => 'text',
				'class'    => 'environment-field production-field',
				'desc_tip' => __( 'Your Moneris store ID', 'woocommerce-gateway-moneris' ),
			),

			'api_token' => array(
				'title'    => __( 'API Token', 'woocommerce-gateway-moneris' ),
				'type'     => 'password',
				'class'    => 'environment-field production-field',
				'desc_tip' => __( 'Your Moneris API token.  Find this by logging into your Moneris account and going to Admin &gt; store settings &gt; API Token', 'woocommerce-gateway-moneris' ),
			),

			'us_test_store_id' => array(
				'title'       => __( 'Store ID', 'woocommerce-gateway-moneris' ),
				'type'        => 'select',
				'options'     => array(
					'monusqa002' => 'monusqa002 (Pinless Debit)',
					'monusqa003' => 'monusqa003',
					'monusqa004' => 'monusqa004',
					'monusqa005' => 'monusqa005',
					'monusqa006' => 'monusqa006',
					//'monusqa024' => 'monusqa024 (ACH only)',
					//'monusqa025' => 'monusqa025 (ACH and Credit Card)',
				),
				'default'     => 'monusqa003',
				'class'       => 'environment-field test-field integration-field us-field',
				'description' => sprintf( __( "Moneris uses a set of shared test accounts, to view your transactions log in to the %smerchant center%s with the selected store id and password 'abc1234'", 'woocommerce-gateway-moneris' ),
											'<a href="https://esplusqa.moneris.com/usmpg/index.php">', '</a>' ),
			),

			'ca_test_store_id' => array(
				'title'       => __( 'Store ID', 'woocommerce-gateway-moneris' ),
				'type'        => 'select',
				'options'     => array(
					'store1'  => 'store1',
					'store2'  => 'store2',
					'store3'  => 'store3',
					'store5'  => 'store5 (test AVS &amp; CVD)',
					// 'moneris' => 'moneris (test VBV)',
				),
				'default'     => 'store1',
				'class'       => 'environment-field test-field integration-field ca-field',
				'description' => sprintf( __( "Moneris uses a set of shared test accounts, to view your transactions log in to the %smerchant center%s with the selected store id and password 'password'", 'woocommerce-gateway-moneris' ),
											'<a href="https://esqa.moneris.com/mpg/">', '</a>' ),
			),

			'dynamic_descriptor' => array(
				'title'       => __( 'Dynamic Descriptor', 'woocommerce-gateway-moneris' ),
				'type'        => 'text',
				'desc_tip'    => __( 'What your buyers will see on their credit card statement ', 'woocommerce-gateway-moneris' ),
				'description' => __( 'Twenty characters maximum allowed if the integration country is United States' ),
			),

			'enable_avs' => array(
				'title'       => __( 'Address Verification Service (AVS)', 'woocommerce-gateway-moneris' ),
				'label'       => __( 'Perform an AVS check on customers billing addresses', 'woocommerce-gateway-moneris' ),
				'desc_tip'    => __( 'This must first be enabled in your Moneris merchant profile and works only with Visa / MasterCard / Discover / JCB / American Express card types.  All other card types will not be declined due to AVS.', 'woocommerce-gateway-moneris' ),
				'type'        => 'checkbox',
				'default'     => 'no',
			),

			'avs_neither_match' => array(
				'description' => __( 'If neither street address nor zip code match', 'woocommerce-gateway-moneris' ),
				'class'       => 'avs-field',
				'type'        => 'select',
				'options'     => array(
					'accept' => __( 'Accept Transaction', 'woocommerce-gateway-moneris' ),
					'reject' => __( 'Reject Transaction', 'woocommerce-gateway-moneris' ),
					'hold'   => __( 'Hold Transaction',   'woocommerce-gateway-moneris' ),
				),
				'default'     => 'accept',
				'desc_tip'    => __( "Use 'Accept' to automatically accept the transaction, 'Reject' to automatically decline the transaction, and 'Hold' to perform an authorization and hold the order for review.", 'woocommerce-gateway-moneris' ),
			),

			'avs_zip_match' => array(
				'description' => __( 'If zip code matches but street address does not match or could not be verified', 'woocommerce-gateway-moneris' ),
				'class'       => 'avs-field',
				'type'        => 'select',
				'options'     => array(
					'accept' => __( 'Accept Transaction', 'woocommerce-gateway-moneris' ),
					'reject' => __( 'Reject Transaction', 'woocommerce-gateway-moneris' ),
					'hold'   => __( 'Hold Transaction',   'woocommerce-gateway-moneris' ),
				),
				'default'     => 'accept',
				'desc_tip'    => __( "Use 'Accept' to automatically accept the transaction, 'Reject' to automatically decline the transaction, and 'Hold' to perform an authorization and hold the order for review.", 'woocommerce-gateway-moneris' ),
			),

			'avs_street_match' => array(
				'description' => __( 'If street address matches but zip code does not match or could not be verified', 'woocommerce-gateway-moneris' ),
				'class'       => 'avs-field',
				'type'        => 'select',
				'options'     => array(
					'accept' => __( 'Accept Transaction', 'woocommerce-gateway-moneris' ),
					'reject' => __( 'Reject Transaction', 'woocommerce-gateway-moneris' ),
					'hold'   => __( 'Hold Transaction',   'woocommerce-gateway-moneris' ),
				),
				'default'     => 'accept',
				'desc_tip'    => __( "Use 'Accept' to automatically accept the transaction, 'Reject' to automatically decline the transaction, and 'Hold' to perform an authorization and hold the order for review.", 'woocommerce-gateway-moneris' ),
			),

			'avs_not_verified' => array(
				'description' => __( 'If street address and zip code could not be verified', 'woocommerce-gateway-moneris' ),
				'class'       => 'avs-field',
				'type'        => 'select',
				'options'     => array(
					'accept' => __( 'Accept Transaction', 'woocommerce-gateway-moneris' ),
					'reject' => __( 'Reject Transaction', 'woocommerce-gateway-moneris' ),
					'hold'   => __( 'Hold Transaction',   'woocommerce-gateway-moneris' ),
				),
				'default'     => 'accept',
				'desc_tip'    => __( "Use 'Accept' to automatically accept the transaction, 'Reject' to automatically decline the transaction, and 'Hold' to perform an authorization and hold the order for review.", 'woocommerce-gateway-moneris' ),
			),

		);

		// collect the CSC fields
		$csc_form_fields = array();

		foreach ( $this->form_fields as $name => $field ) {
			if ( 'enable_csc' == $name || ( isset( $field['class'] ) && false !== strpos( $field['class'], 'csc-field' ) ) ) {
				$csc_form_fields[ $name ] = $field;
				unset( $this->form_fields[ $name ] );
			}
		}

		// and append them following the AVS fields
		$fields = $form_fields + $csc_form_fields;

		return $fields;
	}


	/**
	 * Adds the CSC result handling form fields
	 *
	 * @since 2.0.0
	 *
	 * @param array $form_fields gateway form fields
	 * @return array
	 */
	protected function add_csc_form_fields( $form_fields ) {

		$form_fields = parent::add_csc_form_fields( $form_fields );

		$form_fields['enable_csc']['label'] = __( 'Collect a Card Security Code (CVD) on checkout and validate', 'woocommerce-gateway-moneris' );
		$form_fields['enable_csc']['desc_tip'] = __( 'This must first be enabled in your Moneris merchant profile and works only with Visa / MasterCard / Discover / JCB / American Express card types.  All other card types will not be declined due to CSC.', 'woocommerce-gateway-moneris' );

		$form_fields['csc_not_match'] = array(
			'description' => __( 'If CSC does not match', 'woocommerce-gateway-moneris' ),
			'class'       => 'csc-field',
			'type'        => 'select',
			'options'     => array(
				'accept' => __( 'Accept Transaction', 'woocommerce-gateway-moneris' ),
				'reject' => __( 'Reject Transaction', 'woocommerce-gateway-moneris' ),
				'hold'   => __( 'Hold Transaction',   'woocommerce-gateway-moneris' ),
			),
			'default'     => 'accept',
			'desc_tip'    => __( "Use 'Accept' to automatically accept the transaction, 'Reject' to automatically decline the transaction, and 'Hold' to perform an authorization and hold the order for review.", 'woocommerce-gateway-moneris' ),
		);

		$form_fields['csc_not_verified'] = array(
			'description' => __( 'If CSC could not be verified', 'woocommerce-gateway-moneris' ),
			'class'       => 'csc-field',
			'type'        => 'select',
			'options'     => array(
				'accept' => __( 'Accept Transaction', 'woocommerce-gateway-moneris' ),
				'reject' => __( 'Reject Transaction', 'woocommerce-gateway-moneris' ),
				'hold'   => __( 'Hold Transaction',   'woocommerce-gateway-moneris' ),
			),
			'default'     => 'accept',
			'desc_tip'    => __( "Use 'Accept' to automatically accept the transaction, 'Reject' to automatically decline the transaction, and 'Hold' to perform an authorization and hold the order for review.", 'woocommerce-gateway-moneris' ),
		);

		$form_fields['require_csc'] = array(
			'title'    => __( 'Require Card Verification', 'woocommerce-gateway-moneris' ),
			'class'    => 'csc-field',
			'label'    => __( 'Require the Card Security Code for all transactions', 'woocommerce-gateway-moneris' ),
			'desc_tip' => __( 'Enabling this field will require the CSC even for tokenized transactions, and will disable support for WooCommerce Subscriptions and WooCommerce Pre-Orders.', 'woocommerce-gateway-moneris' ),
			'type'     => 'checkbox',
			'default'  => 'no',
		);

		return $form_fields;
	}


	/**
	 * Adds the Hosted Tokenization options.
	 *
	 * @since 2.0.0
	 *
	 * @param array $form_fields gateway form fields
	 * @return array $form_fields gateway form fields
	 */
	protected function add_tokenization_form_fields( $form_fields ) {

		$form_fields = parent::add_tokenization_form_fields( $form_fields );

		$form_fields['tokenization']['label'] = _x( 'Allow customers to securely save their payment details for future checkout.  You must contact your Moneris account rep to enable the "Vault" option on your account before enabling this setting.', 'Supports tokenization', 'woocommerce-gateway-moneris' );

		$form_fields['hosted_tokenization'] = array(
			'title'   => __( 'Hosted Tokenization', 'woocommerce-gateway-moneris' ),
			'label'   => __( 'Use a hosted form field to collect credit card information on checkout and reduce PCI-compliance assessment scope.', 'woocommerce-gateway-moneris' ),
			'type'    => 'checkbox',
			'default' => 'no',
		);

		$form_fields['hosted_tokenization_profile_id'] = array(
			'title'       => __( 'Hosted Tokenization Profile ID', 'woocommerce-gateway-moneris' ),
			'type'        => 'text',
			'class'       => 'environment-field production-field hosted-tokenization-field',
			'description' => sprintf( __( 'Generate this by logging into your %sMerchant Resource Center%s &gt; Admin &gt; Hosted Tokenization - use %s%s%s as the source domain.', 'woocommerce-gateway-moneris' ),
								'<a href="#" target="_blank">', '</a>',
								'<span class="nowrap">',
								get_home_url( null, '', get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ? 'https' : null ),
								'</span>'
			)
		);

		$form_fields['test_hosted_tokenization_profile_id'] = array(
			'title'       => __( 'Hosted Tokenization Profile ID', 'woocommerce-gateway-moneris' ),
			'type'        => 'text',
			'class'       => 'environment-field test-field hosted-tokenization-field',
			'description' => __( 'Profile IDs for sandbox accounts are provided automatically, but you may also generate your own and override them here. <br/>In order to restore the default value, just select another test Store ID.', 'woocommerce-gateway-moneris' )
		);

		return $form_fields;
	}


	/**
	 * Returns an array of javascript script params to localize for the
	 * checkout/pay page javascript.  Mostly used for i18n purposes
	 *
	 * @since 2.0.0
	 *
	 * @return array associative array of param name to value
	 */
	protected function get_gateway_js_localized_script_params() {

		$params = array( 'require_csc' => $this->csc_required() );

		if ( $this->hosted_tokenization_available() ) {
			$params['hosted_tokenization_url']        = $this->get_hosted_tokenization_url();
			$params['general_error']                  = __( 'An error occurred with your payment, please try again or try another payment method', 'woocommerce-gateway-moneris' );
			$params['card_number_missing_or_invalid'] = __( 'Card number is missing or invalid', 'woocommerce-gateway-moneris' );
			$params['ajaxurl']                        = admin_url( 'admin-ajax.php', 'relative' );

			// get the current order and add the cancel/return URLs
			$order_id = isset( $GLOBALS['wp']->query_vars['order-pay'] ) ? absint( $GLOBALS['wp']->query_vars['order-pay'] ) : 0;

			if ( $order_id ) {

				$order = wc_get_order( $order_id );

				$params['order_id'] = $order->get_id();
			}
		}

		// add the "require_csc" param, which is needed to properly handle the checkout page tokenization logic
		return array_merge( parent::get_payment_form_js_localized_script_params(), $params );
	}


	/**
	 * Display settings page with some additional javascript for hiding
	 * conditional fields.  The "Require CSC" field will be shown only when
	 * the "Enable CSC" and "Tokenization" are enabled
	 *
	 * @since 2.0.0
	 */
	public function admin_options() {

		parent::admin_options();

		// add inline javascript to show the correct test store id field based on the current integration country
		ob_start();
		?>
			$( '#woocommerce_<?php echo $this->get_id(); ?>_integration_country, #woocommerce_<?php echo $this->get_id(); ?>_environment, #woocommerce_<?php echo $this->get_id(); ?>_hosted_tokenization' ).change( function() {

				var environment = $( '#woocommerce_<?php echo $this->get_id(); ?>_environment' ).val();
				var integration = $( '#woocommerce_<?php echo $this->get_id(); ?>_integration_country' ).val();
				var hostedTokenization = $( '#woocommerce_<?php echo $this->get_id(); ?>_hosted_tokenization' ).is( ':checked' );

				// I thought to store this as data attributes on the anchor tag, but THINK AGAIN, WooCommerce strips out any data attributes
				var usTestMerchantCenterUrl       = '<?php echo $this->get_merchant_center_url( self::ENVIRONMENT_TEST,       WC_Moneris::INTEGRATION_US ); ?>';
				var usProductionMerchantCenterUrl = '<?php echo $this->get_merchant_center_url( self::ENVIRONMENT_PRODUCTION, WC_Moneris::INTEGRATION_US ); ?>';
				var caTestMerchantCenterUrl       = '<?php echo $this->get_merchant_center_url( self::ENVIRONMENT_TEST,       WC_Moneris::INTEGRATION_CA ); ?>';
				var caProductionMerchantCenterUrl = '<?php echo $this->get_merchant_center_url( self::ENVIRONMENT_PRODUCTION, WC_Moneris::INTEGRATION_CA ); ?>';

				// hide all integration-dependant fields
				$( '.integration-field' ).closest( 'tr' ).hide();

				// show the currently configured integration fields
				var $integrationFields = $( '.' + integration + '-field.' + environment + '-field' );

				$integrationFields.not( '.hidden' ).closest( 'tr' ).show();

				// hide all hosted tokenization-dependant fields
				$( '.hosted-tokenization-field' ).closest( 'tr' ).hide();

				// show the hosted tokenization-dependant fields for the current environment, if hosted tokenization is enabled
				if ( hostedTokenization ) {
					var $hostedTokenizationFields = $( '.hosted-tokenization-field.' + environment + '-field' );
					$hostedTokenizationFields.not( '.hidden' ).closest( 'tr' ).show();

					var merchantCenterUrl = null;
					if ( '<?php echo self::ENVIRONMENT_PRODUCTION; ?>' == environment ) {
						if ( '<?php echo \WC_Moneris::INTEGRATION_US; ?>' == integration ) {
							merchantCenterUrl = usProductionMerchantCenterUrl;
						} else {
							merchantCenterUrl = caProductionMerchantCenterUrl;
						}
					} else {
						if ( '<?php echo \WC_Moneris::INTEGRATION_US; ?>' == integration ) {
							merchantCenterUrl = usTestMerchantCenterUrl;
						} else {
							merchantCenterUrl = caTestMerchantCenterUrl;
						}
					}
					$hostedTokenizationFields.closest('tr').find('a').attr( 'href', merchantCenterUrl );
				}

				// the dynamic descriptor description is only relevant to the US integration
				var dynamicDescriptorDescription = $( '#woocommerce_<?php echo $this->get_id(); ?>_dynamic_descriptor' ).closest( 'td' ).find( '.description' );
				if ( '<?php echo \WC_Moneris::INTEGRATION_US; ?>' == integration ) {
					dynamicDescriptorDescription.show();
				} else {
					dynamicDescriptorDescription.hide();
				}

			} ).change();

			$( '#woocommerce_<?php echo $this->get_id(); ?>_enable_avs' ).change( function() {
				var enableAvs = $( this ).is( ':checked' );

				if ( enableAvs ) {
					$( '.avs-field' ).closest( 'tr' ).show();
				} else {
					$( '.avs-field' ).closest( 'tr' ).hide();
				}
			} ).change();

			$( '#woocommerce_<?php echo $this->get_id(); ?>_enable_csc' ).change( function() {
				var enableCsc = $( this ).is( ':checked' );

				if ( enableCsc ) {
					$( '.csc-field' ).closest( 'tr' ).show();
				} else {
					$( '.csc-field' ).closest( 'tr' ).hide();
				}
			} ).change();

			// add inline javascript to show the "require csc" field when the "enable csc" and "tokenization" fields are both checked
			$( '#woocommerce_<?php echo $this->get_id(); ?>_enable_csc, #woocommerce_<?php echo $this->get_id(); ?>_tokenization' ).change( function() {

				if ( $( '#woocommerce_<?php echo $this->get_id(); ?>_enable_csc' ).is( ':checked' ) && $( '#woocommerce_<?php echo $this->get_id(); ?>_tokenization' ).is( ':checked' ) ) {
					$( '#woocommerce_<?php echo $this->get_id(); ?>_require_csc' ).closest( 'tr' ).show();
				} else {
					$( '#woocommerce_<?php echo $this->get_id(); ?>_require_csc' ).closest( 'tr' ).hide();
				}

			} ).change();
		<?php

		wc_enqueue_js( ob_get_clean() );

	}


	/**
	 * Gets the merchant account transaction URL for the given order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return string|null
	 */
	public function get_transaction_url( $order ) {

		$receipt_id  = $this->get_order_meta( $order, 'receipt_id' );
		$trans_id    = $this->get_order_meta( $order, 'auth_trans_id' );
		$environment = $this->get_order_meta( $order, 'environment' );
		$integration = $this->get_order_meta( $order, 'integration' );

		// fall back to the regular transaction ID if the auth-id isn't present
		if ( ! $trans_id ) {
			$trans_id = $this->get_order_meta( $order, 'trans_id' );
		}

		if ( ! $receipt_id || ! $trans_id || ! $environment || ! $integration ) {
			return null;
		}

		$host = $this->get_moneris_host( $environment, $integration );

		if ( $this->is_us_integration() ) {
			$host .= '/usmpg/reports/order_history/index.php';
		} else {
			$host .= '/mpg/reports/order_history/index.php';
		}

		$this->view_transaction_url = add_query_arg( array( 'order_no' => $receipt_id, 'orig_txn_no' => $trans_id ), $host );

		return parent::get_transaction_url( $order );
	}


	/**
	 * Determines if the gateway is properly configured to perform transactions.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_configured() {

		$is_configured = parent::is_configured();

		// missing configuration
		if ( ! $this->get_store_id() || ! $this->get_api_token() ) {
			$is_configured = false;
		}

		return $is_configured;
	}


	/**
	 * Add any Moneris specific payment and transaction information as
	 * class members of WC_Order instance.  Added members can include:
	 *
	 * $order->dynamic_descriptor - Merchant defined description sent on a per-transaction basis that will appear on the credit card statement appended to the merchant�s business name.
	 * $order->perform_avs - true if the avs data should be included with the transaction request
	 *
	 * @since 2.0.0
	 *
	 * @param int|\WC_Order $order order or order ID being processed
	 * @return \WC_Order
	 */
	public function get_order( $order ) {

		// add common order members
		$order = parent::get_order( $order );

		// add the configured dynamic descriptor
		$order->dynamic_descriptor = $this->get_dynamic_descriptor( true );

		// whether to include the avs fields
		$order->perform_avs = $this->avs_enabled();

		if ( empty( $order->payment->card_type ) ) {

			// determine the card type from the account number
			if ( ! empty( $order->payment->account_number ) ) {
				$account_number = $order->payment->account_number;
			} else {
				$account_number = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-card-bin' );
			}

			$order->payment->card_type = Framework\SV_WC_Payment_Gateway_Helper::card_type_from_account_number( $account_number );
		}

		if ( $this->is_test_environment() ) {

			// Add a prefix to the transaction ID to avoid "duplicate order ID" errors
			// during testing
			$order->unique_transaction_ref = uniqid( '', true ) . $order->unique_transaction_ref;

			// Test amount entered in enhanced payment form
			// @since 2.8.0
			if ( ( $test_amount = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-test-amount' ) ) ) {
				$order->payment_total = Framework\SV_WC_Helper::number_format( $test_amount );
			}
		}

		$order->payment->card_type = Framework\SV_WC_Payment_Gateway_Helper::normalize_card_type( $order->payment->card_type );

		return $order;
	}


	/**
	 * Validates the payment fields when processing the checkout.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function validate_fields() {

		$is_valid = true;

		$expiration_month = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-exp-month' );
		$expiration_year  = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-exp-year' );
		$expiry           = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-expiry' );
		$csc              = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-csc' );

		if ( $this->hosted_tokenization_available() && Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-temp-payment-token' ) ) {
			// dealing with a hosted tokenization temporary token, which means it does not exist in our local datastore
			// and there's no account number to validate

			if ( ! $expiration_month & ! $expiration_year && $expiry ) {
				list( $expiration_month, $expiration_year ) = array_map( 'trim', explode( '/', $expiry ) );
			}

			$is_valid = $this->validate_credit_card_expiration_date( $expiration_month, $expiration_year ) && $is_valid;

			// validate card security code
			if ( $this->csc_enabled() ) {
				$is_valid = $this->validate_csc( $csc ) && $is_valid;
			}

			return $is_valid;
		}

		// normal operation
		$is_valid = parent::validate_fields();

		// tokenized transaction with CSC required, validate the csc
		if ( Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-payment-token' ) && $this->csc_required() ) {

			$csc      = Framework\SV_WC_Helper::get_posted_value( 'wc-' . $this->get_id_dasherized() . '-csc' );
			$is_valid = $this->validate_csc( $csc ) && $is_valid;

		}

		return $is_valid;
	}


	/**
	 * Performs a credit card transaction for the given order and returns the
	 * result.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @param \WC_Moneris_API_Response|null $response API response object
	 * @return Framework\SV_WC_Payment_Gateway_API_Response API response object
	 * @throws Exception network timeouts, etc
	 */
	protected function do_credit_card_transaction( $order, $response = null ) {

		// look for a response attached to the order object
		$response = null === $response && ! empty( $order->payment->response ) ? $order->payment->response : $response;

		// normal operation if no avs checks in force or card does not support it, or there is already a payment receipt (from client-side processing)
		if ( $response || ! $this->has_efraud_validations() || ( $order->payment->card_type && ! $this->card_supports_efraud_validations( $order->payment->card_type ) ) ) {
			return parent::do_credit_card_transaction( $order, $response );
		}

		// we have at least one efraud condition, so we'll start by doing an authorization for the full amount
		$response = $this->get_api()->credit_card_authorization( $order );

		// authorization failure, we're done
		if ( ! $response->transaction_approved() ) {
			return $response;
		}

		// store the original authorization transaction ID in case it's needed later
		$order->payment->auth_trans_id = $response->get_transaction_id();

		// this is for Hosted Tokenization transactions with efraud settings, and tokenization vault disabled
		// we don't know the card type until we perform an authorization request, so we do so, get the card
		// type and proceed from there
		if ( ! $order->payment->card_type && ( ! $response->get_card_type() || ! $this->card_supports_efraud_validations( $response->get_card_type() ) ) ) {

			if ( $this->perform_credit_card_charge( $order ) ) {

				$order = $this->get_order_for_capture( $order );

				// complete the charge if needed
				$order->capture->trans_id   = $response->get_transaction_id();
				$order->capture->receipt_id = $response->get_receipt_id();

				$response = $this->get_api()->credit_card_capture( $order );
			}

			return parent::do_credit_card_transaction( $order, $response );
		}

		// set the card type/account number for hosted tokenized (non-tokenization) transactions
		if ( ! $order->payment->card_type ) {
			$order->payment->card_type      = $response->get_card_type();
			$order->payment->account_number = $response->get_masked_pan();
		}

		// get the combined efraud action
		$efraud_action = $this->get_efraud_action( $response->get_avs_result(), $response->get_csc_result(), $order );

		if ( 'accept' == $efraud_action ) {

			if ( $this->perform_credit_card_charge( $order ) ) {

				$order = $this->get_order_for_capture( $order );

				// complete the charge if needed
				$order->capture->trans_id   = $response->get_transaction_id();
				$order->capture->receipt_id = $response->get_receipt_id();

				$response = $this->get_api()->credit_card_capture( $order );
			} // otherwise just return the authorization response

		} elseif ( 'reject' == $efraud_action ) {

			$order = $this->get_order_for_capture( $order );

			// reverse the charge
			$order->capture->trans_id   = $response->get_transaction_id();
			$order->capture->receipt_id = $response->get_receipt_id();

			$this->get_api()->credit_card_authorization_reverse( $order );

			// mark the original response as failed since we've reversed the authorization
			$response->failed();

			$messages = array();
			if ( $this->has_avs_validations() && 'reject' == $this->get_avs_action( $response->get_avs_result() ) ) {
				$messages[] = sprintf( __( 'AVS %s (result: %s)', 'woocommerce-gateway-moneris' ), $this->get_avs_error_message( $response->get_avs_result() ), $response->get_avs_result_code() );
			}

			if ( $this->has_csc_validations() && 'reject' == $this->get_csc_action( $response->get_csc_result() ) ) {
				$messages[] = sprintf( __( 'CSC %s (result: %s)', 'woocommerce-gateway-moneris' ), $this->get_csc_error_message( $response->get_csc_result() ), $response->get_csc_result_code() );
			}

			$response->set_status_message( implode( ', ', $messages ) );

			// we really don't care whether the reversal succeeded, though it should have
			return $response;

		} else { // hold

			// mark the response as held
			$response->held();

			$messages = array();
			if ( $this->has_avs_validations() && 'hold' == $this->get_avs_action( $response->get_avs_result() ) ) {
				$messages[] = sprintf( __( 'AVS %s (result: %s)', 'woocommerce-gateway-moneris' ), $this->get_avs_error_message( $response->get_avs_result() ), $response->get_avs_result_code() );
			}

			if ( $this->has_csc_validations() && 'hold' == $this->get_csc_action( $response->get_csc_result() ) ) {
				$messages[] = sprintf( __( 'CSC %s (result: %s)', 'woocommerce-gateway-moneris' ), $this->get_csc_error_message( $response->get_csc_result() ), $response->get_csc_result_code() );
			}

			$message = __( "Authorization", 'woocommerce-gateway-moneris' ) . ' ' . implode( ', ', $messages );

			if ( $this->perform_credit_card_authorization( $order ) ) {
				// workaround
				$this->held_authorization_status_message = $message;
			} else {
				// this message will be added to the 'hold' order notes
				$response->set_status_message( $message );
			}

			return $response;
		}

		// success! update order record
		return parent::do_credit_card_transaction( $order, $response );
	}


	/**
	 * Marks the given order as 'on-hold', set an order note and display a message
	 * to the customer.
	 *
	 * TODO: this should hopefully be a temporary override, until we figure out a
	 * better way to handle the messaging for held authorize-only orders in the
	 * Framework\SV_WC_Payment_Gateway::do_transaction() method
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param string $message a message to display within the order note
	 * @param Framework\SV_WC_Payment_Gateway_API_Response optional $response the transaction response object
	 */
	public function mark_order_as_held( $order, $message, $response = null ) {

		// reset the capture eligibility as this may be a new authorization
		// for an order that previously had its authorization reversed
		$this->update_order_meta( $order, 'auth_can_be_captured', 'yes' );

		if ( ! is_null( $this->held_authorization_status_message ) ) {
			$message = $this->held_authorization_status_message;
		}

		parent::mark_order_as_held( $order, $message, $response );
	}


	/**
	 * Performs a credit card authorization reversal for the given order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @return null|Framework\SV_WC_Payment_Gateway_API_Response the response of the reversal attempt
	 */
	public function do_credit_card_reverse_authorization( $order ) {

		try {

			$response = $this->get_api()->credit_card_authorization_reverse( $this->get_order_for_capture( $order ) );

			if ( $response->transaction_approved() ) {

				$message = sprintf(
					__( '%s%s Authorization Reversed', 'woocommerce-gateway-moneris' ),
					$this->get_method_title(),
					$this->is_test_environment() ? ' ' . __( 'Test', 'woocommerce-gateway-moneris' ) : ''
				);

				// adds the transaction id (if any) to the order note
				if ( $response->get_transaction_id() ) {
					$message .= ' ' . sprintf( __( '(Transaction ID %s)', 'woocommerce-gateway-moneris' ), $response->get_transaction_id() );
				}

				// cancel the order.  since this results in an update to the post object we need to unhook the save_post action, otherwise we can get boomeranged and change the status back to on-hold
				$this->unhook_woocommerce_process_shop_order_meta();

				$this->mark_order_as_cancelled( $order, $message, $response );

				// once an authorization has been reversed, it cannot be captured again
				$this->update_order_meta( $order, 'auth_can_be_captured', 'no' );

			} else {

				$message = sprintf(
					__( '%s%s Authorization Reversal Failed: %s - %s', 'woocommerce-gateway-moneris' ),
					$this->get_method_title(),
					$this->is_test_environment() ? ' ' . __( 'Test', 'woocommerce-gateway-moneris' ) : '',
					$response->get_status_code(),
					$response->get_status_message()
				);

				if ( $response->is_authorization_invalid() ) {
					// already reversed or captured, cancel the order.  since this results in an update to the post object we need to unhook the save_post action, otherwise we can get boomeranged and change the status back to on-hold
					$this->unhook_woocommerce_process_shop_order_meta();

					$this->mark_order_as_cancelled( $order, $message, $response );

					// mark the capture as invalid
					$this->update_order_meta( $order, 'auth_can_be_captured', 'no' );

				} else {

					$order->add_order_note( $message );
				}

			}

			return $response;

		} catch ( Exception $e ) {

			$message = sprintf(
				__( '%s%s Authorization Reversal Failed: %s', 'woocommerce-gateway-moneris' ),
				$this->get_method_title(),
				$this->is_test_environment() ? ' ' . __( 'Test', 'woocommerce-gateway-moneris' ) : '',
				$e->getMessage()
			);

			$order->add_order_note( $message );

			return null;
		}
	}


	/**
	 * Gets the order object with additional properties needed for capture.
	 *
	 * @since 2.7.0
	 * @param \WC_Order|int $order the order object or ID
	 * @param float|null $amount capture amount
	 * @return \WC_Order
	 */
	public function get_order_for_capture( $order, $amount = null ) {

		$order = parent::get_order_for_capture( $order );

		$order->capture->receipt_id = $this->get_order_meta( $order, 'receipt_id' );

		return $order;
	}

	/**
	 * Gets the order object with additional properties needed for refunds.
	 *
	 * @since 2.8.0
	 * @param WC_Order|int $order order being processed
	 * @param float $amount refund amount
	 * @param string $reason optional refund reason text
	 * @return WC_Order object with refund information attached
	 */
	protected function get_order_for_refund( $order, $amount, $reason ) {

		$order = parent::get_order_for_refund( $order, $amount, $reason );

		$order->refund->receipt_id = $this->get_order_meta( $order, 'receipt_id' );

		// Check whether the charge has already been captured by this gateway
		$charge_captured = $this->get_order_meta( $order, 'charge_captured' );

		if ( 'yes' == $charge_captured ) {
			// For orders authorised, then captured, the transaction ID should be the
			// one from the "capture" operation
			$capture_trans_id = $this->get_order_meta( $order, 'capture_trans_id' );

			if( ! empty( $capture_trans_id ) ) {
				$order->refund->trans_id = $capture_trans_id;
			}
		}

		return $order;
	}


	/**
	 * Called after an unsuccessful transaction attempt.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param Framework\SV_WC_Payment_Gateway_API_Response $response the transaction response
	 * @return bool
	 */
	protected function do_transaction_failed_result( WC_Order $order, Framework\SV_WC_Payment_Gateway_API_Response $response ) {

		// missing token, meaning local token is invalid, so delete it from the local datastore
		if ( ( 'res_preauth_cc' == $response->get_request()->get_type() || 'res_purchase_cc' == $response->get_request()->get_type() )
			&& 983 == $response->get_status_code() ) {
			$this->get_payment_tokens_handler()->remove_token( $order->get_user_id(), $order->payment->token );
		}

		return parent::do_transaction_failed_result( $order, $response );
	}


	/**
	 * Unhooks the core WooCommerce process shop order meta, so we can update
	 * the order status without causing the core WooCommerce code to fire and
	 * undo our change
	 *
	 * @since 2.0
	 */
	private function unhook_woocommerce_process_shop_order_meta() {

		// complete the order.  since this results in an update to the post object we need to unhook the save_post action, otherwise we can get boomeranged and change the status back to on-hold
		remove_action( 'woocommerce_process_shop_order_meta', 'WC_Meta_Box_Order_Data::save', 40 );
	}


	/**
	 * Adds any gateway-specific transaction data to the order.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param \WC_Moneris_API_Response $response API response object
	 */
	public function add_payment_gateway_transaction_data( $order, $response ) {

		// record the integration country (us/ca)
		$this->update_order_meta( $order, 'integration', $this->get_integration_country() );

		// record the receipt ID (order number)
		$this->update_order_meta( $order, 'receipt_id', $response->get_receipt_id() );

		// record the transaction reference number
		if ( $response->get_reference_num() ) {
			$this->update_order_meta( $order, 'reference_num', $response->get_reference_num() );
		}

		// record the avs result code
		if ( $response->get_avs_result_code() ) {
			$this->update_order_meta( $order, 'avs', $response->get_avs_result_code() );
		}

		// record the csc validation code
		if ( $response->get_csc_result_code() ) {
			$this->update_order_meta( $order, 'csc', $response->get_csc_result_code() );
		}

		// if we're configured to perform a credit card charge, but a preauth
		// was performed, this likely indicates an AVS failure.  Mark the
		// charge as not captured so it can be managed through the admin
		if ( $this->perform_credit_card_charge( $order ) && 'preauth' == $response->get_request()->get_type() ) {
			$this->update_order_meta( $order, 'charge_captured', 'no' );
		}

		// store the original transaction ID to be used to generate the transaction URL
		if ( ! empty( $order->payment->auth_trans_id ) ) {

			$this->update_order_meta( $order, 'auth_trans_id', $order->payment->auth_trans_id );

			// use the core method here since \SV_WC_Payment_Gateway::update_order_meta() prefixes the gateway ID
			update_post_meta( $order->get_id(), '_transaction_id', $order->payment->auth_trans_id );
		}
	}


	/**
	 * Returns true if tokenization takes place after an authorization/charge
	 * transaction.
	 *
	 * Moneris has both a post-transaction tokenization request, as well as a
	 * dedicated tokenization request.
	 *
	 * @since 2.0
	 * @return boolean true if there is a tokenization request that is issued
	 *         after an authorization/charge transaction
	 */
	public function tokenize_after_sale() {
		return true;
	}


	/**
	 * Return the Payment Tokens Handler class instance.
	 *
	 * @since 2.5.0
	 * @return \WC_Gateway_Moneris_Payment_Tokens_Handler
	 */
	protected function build_payment_tokens_handler() {

		return new \WC_Gateway_Moneris_Payment_Tokens_Handler( $this );
	}


	/** AVS/CSC methods ******************************************************/


	/**
	 * Returns true if the AVS checks should be performed when processing a payment
	 *
	 * @since 2.0
	 * @return boolean true if AVS is enabled
	 */
	public function avs_enabled() {
		return 'yes' == $this->enable_avs;
	}


	/**
	 * Returns true if either AVS or CSC checks should be performed when
	 * processing a payment
	 *
	 * @since 2.0
	 * @return boolean true if AVS or CSC is enabled
	 */
	public function has_efraud_validations() {
		return $this->has_avs_validations() || $this->has_csc_validations();
	}


	/**
	 * Returns true if the given card type supports eFraud (AVS/CSC) validations
	 *
	 * @since 2.0
	 * @param string $card_type the card type
	 * @return boolean true if the card type supports AVS and CSC validations
	 */
	private function card_supports_efraud_validations( $card_type ) {

		$valid_types = array(
			Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_VISA,
			Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_MASTERCARD,
			Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_AMEX,
			Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_DISCOVER,
			Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_JCB,
		);

		return in_array( $card_type, $valid_types, true );
	}


	/**
	 * Returns true if avs checks are enabled, and at least one check is not
	 * simply 'accept'
	 *
	 * @since 2.0
	 * @return true if avs checks are enabled
	 */
	public function has_avs_validations() {
		return $this->avs_enabled() && ( 'accept' != $this->avs_neither_match || 'accept' != $this->avs_zip_match || 'accept' != $this->avs_street_match || 'accept' != $this->avs_not_verified );
	}


	/**
	 * Returns the single action to take based on the AVS and CSC responses.
	 *
	 * @since 2.0.0
	 *
	 * @param string $avs_code the standardized avs code, one of 'Z', 'A', 'N', 'Y', 'U' or null
	 * @param string $csc_code the standardized csc code, one of 'M', 'N', 'U', or null
	 * @param \WC_Order $order order object
	 *
	 * @return string one of 'accept', 'reject', 'hold'
	 */
	private function get_efraud_action( $avs_code, $csc_code, $order ) {

		$actions = array();

		if ( $avs_code ) {
			$actions[] = $this->get_avs_action( $avs_code );
		}

		// Only get the CSC action if there is a response code and:
		//     a. the csc is required
		//     b. this isn't a saved method transaction
		//     c. this is a hosted tokenization transaction
		// This avoids rejections for situations where a CSC is not a factor,
		// like for a saved method or subscription renewals
		if ( $csc_code && ( $this->csc_required() || empty( $order->payment->token ) || Framework\SV_WC_Helper::get_posted_value( 'wc-moneris-temp-payment-token' ) ) ) {
			$actions[] = $this->get_csc_action( $csc_code );
		}

		// rejection conquers all
		if ( in_array( 'reject', $actions ) ) {
			return 'reject';
		}

		// hold beats accept
		if ( in_array( 'hold', $actions ) ) {
			return 'hold';
		}

		// if all good
		return 'accept';
	}


	/**
	 * Returns the action to take based on the settings configuration and
	 * standardized $avs_code.
	 *
	 * @since 2.0
	 * @param string $avs_code the standardized avs code, one of 'Z', 'A', 'N', 'Y', 'U' or null
	 * @return string one of 'accept', 'reject', 'hold'
	 */
	private function get_avs_action( $avs_code ) {

		// unknown card type or unknown result, mark as approved
		if ( is_null( $avs_code ) ) {
			return 'accept';
		}

		switch ( $avs_code ) {
			// zip match, locale no match
			case 'Z': return $this->avs_zip_match;

			// zip no match, locale match
			case 'A': return $this->avs_street_match;

			// zip no match, locale no match
			case 'N': return $this->avs_neither_match;

			// zip match, locale match
			case 'Y': return 'accept';

			// zip and locale could not be verified
			case 'U': return $this->avs_not_verified;
		}
	}


	/**
	 * Returns an error message based on the $avs_code
	 *
	 * @since 2.0
	 * @param string $avs_code the unified AVS error code, one of 'Z', 'A', 'N', 'U'
	 * @return string message based on the code
	 */
	private function get_avs_error_message( $avs_code ) {

		switch ( $avs_code ) {
			// zip match, locale no match
			case 'Z': return __( 'postal code match, street address no match', 'woocommerce-gateway-moneris' );

			// zip no match, locale match
			case 'A': return __( 'postal code no match, street address match', 'woocommerce-gateway-moneris' );

			// zip no match, locale no match
			case 'N': return __( 'postal code no match, street address no match', 'woocommerce-gateway-moneris' );

			// zip and locale could not be verified
			case 'U': return __( 'could not be verified', 'woocommerce-gateway-moneris' );;
		}
	}


	/**
	 * Returns the action to take based on the settings configuration and
	 * standardized $csc_code.
	 *
	 * @since 2.0
	 * @param string $csc_code the standardized avs code, one of 'M', 'N', 'U', or null
	 * @return string one of 'accept', 'reject', 'hold'
	 */
	private function get_csc_action( $csc_code ) {

		// unsupported card
		if ( is_null( $csc_code ) ) {
			return 'accept';
		}

		switch ( $csc_code ) {
			// match
			case 'M': return 'accept';

			// no match
			case 'N': return $this->csc_not_match;

			// could not be verified, or unknown result code
			case 'U': return $this->csc_not_verified;
		}
	}


	/**
	 * Returns an error message based on the $csc_code
	 *
	 * @since 2.0
	 * @param string $csc_code the unified CSC error code, one of 'N', 'U'
	 * @return string message based on the code
	 */
	private function get_csc_error_message( $csc_code ) {

		switch ( $csc_code ) {
			// no match
			case 'N': return __( 'no match', 'woocommerce-gateway-moneris' );

			// zip and locale could not be verified
			case 'U': return __( 'could not be verified', 'woocommerce-gateway-moneris' );;
		}
	}


	/**
	 * Returns true if CSC checks are enabled, and at least one check is not
	 * simple 'accept'
	 *
	 * @since 2.0
	 * @return true if csc checks are enabled
	 */
	private function has_csc_validations() {
		return $this->csc_enabled() && ( 'accept' != $this->csc_not_match || 'accept' != $this->csc_not_verified );
	}


	/**
	 * Returns true if the CSC is required for all transactions, including
	 * tokenized
	 *
	 * @since 2.0
	 * @return boolean true if the CSC is required for all transactions, even tokenized
	 */
	public function csc_required() {
		return $this->csc_enabled() && 'yes' == $this->require_csc;
	}


	/** Subscriptions ******************************************************/


	/**
	 * Tweaks the labels shown when editing the payment method for a Subscription.
	 *
	 * @hooked from Framework\SV_WC_Payment_Gateway_Integration_Subscriptions
	 *
	 * @since 2.3.2
	 *
	 * @param array $meta payment meta
	 * @param \WC_Subscription $subscription subscription being edited, unused
	 * @return array
	 */
	public function subscriptions_admin_add_payment_meta( $meta, $subscription ) {

		if ( isset( $meta[ $this->get_id() ] ) ) {

			$meta[ $this->get_id() ]['post_meta'][ $this->get_order_meta_prefix() . 'payment_token' ]['label'] = __( 'Data Key', 'woocommerce-gateway-moneris' );
		}

		return $meta;
	}


	/**
	 * Returns meta keys to be excluded when copying over meta data when:
	 *
	 * + a renewal order is created from a subscription
	 * + the user changes their payment method for a subscription
	 * + processing the upgrade from Subscriptions 1.5.x to 2.0.x
	 *
	 * @since 2.3.2
	 * @param array $meta_keys
	 * @return array
	 */
	public function subscriptions_get_excluded_order_meta_keys( $meta_keys ) {

		$meta_keys[] = $this->get_order_meta_prefix() . 'integration';
		$meta_keys[] = $this->get_order_meta_prefix() . 'receipt_id';
		$meta_keys[] = $this->get_order_meta_prefix() . 'reference_num';
		$meta_keys[] = $this->get_order_meta_prefix() . 'avs';
		$meta_keys[] = $this->get_order_meta_prefix() . 'csc';

		return $meta_keys;
	}


	/**
	 * Determines if this gateway with its current configuration supports subscriptions.
	 *
	 * Requiring CSC for all transactions removes support for subscriptions.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function supports_subscriptions() {

		return parent::supports_subscriptions() && ! $this->csc_required();
	}


	/**
	 * Determines if this gateway with its current configuration supports pre-orders.
	 *
	 * Requiring CSC for all transactions removes support for pre-orders.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function supports_pre_orders() {

		return parent::supports_pre_orders() && ! $this->csc_required();
	}


	/** Pay Page Hosted Tokenization methods **********************************/


	/**
	 * Returns the url to the hosted tokenization checkout script
	 *
	 * @since 2.0
	 * @param string $url the checkout javascript url, unused
	 * @return string the checkout javascript url
	 */
	public function hosted_tokenization_javascript_url( $url ) {
		// return the url to the hosted tokenization javascript
		return $this->get_plugin()->get_plugin_url() . '/assets/js/frontend/wc-' . $this->get_plugin()->get_id_dasherized() . '-hosted-tokenization.min.js';
	}


	/**
	 * Handle the hosted tokenization response
	 *
	 * This is called from an AJAX context because the request is made in
	 * client-side javascript
	 *
	 * @since 2.0
	 */
	public function handle_hosted_tokenization_response() {

		$order_id      = isset( $_GET['orderId'] )      ? $_GET['orderId']      : '';  // order id if on pay page only
		$response_code = isset( $_GET['responseCode'] ) ? $_GET['responseCode'] : '';
		$error_message = isset( $_GET['errorMessage'] ) ? $_GET['errorMessage'] : '';
		$token         = isset( $_GET['token'] )        ? $_GET['token'] : '';
		$request_time  = isset( $_GET['requestTime'] )  ? $_GET['requestTime']  : '';  // request time, in seconds

		$response_body = array();

		if ( $token ) {
			$response_body[] = sprintf( __( 'token: %s', 'woocommerce-gateway-moneris' ), $token );
		}

		if ( $error_message ) {
			$response_body[] = sprintf( __( 'hosted tokenization response error message: %s', 'woocommerce-gateway-moneris' ), $error_message );
		}

		$this->get_plugin()->log_api_request(
			array(
				'time'   => $request_time,
				'method' => 'POST',
				'uri'    => $this->get_hosted_tokenization_iframe_url(),
				'body'   => null,
			),
			array(
				'code' => $response_code,
				'body' => implode( ', ', $response_body ),
			)
		);

		if ( $response_code >= 50 ) {

			if ( ! $order_id ) {

				if ( WC()->session->order_awaiting_payment > 0 ) {

					$putative_order_id = absint( WC()->session->order_awaiting_payment );

					$putative_order = wc_get_order( $putative_order_id );

					// check if order is available and unpaid
					if ( $putative_order instanceof WC_Order && ! $putative_order->is_paid() ) {
						$order_id = $putative_order_id;
					}
				}
			}

			// if we have an order id (on the pay page) add an order note
			if ( $order_id ) {
				$order = wc_get_order( $order_id );

				$order_note = sprintf( '%s: %s', $response_code, implode( ', ', $response_body ) );

				// any chance of some kind of a delay occurring, and this marking an order as failed when it has already succeeded?
				$this->mark_order_as_failed_quiet( $order, $order_note );
			}
		}
	}


	/**
	 * Mark the given order as failed and set the order note
	 *
	 * @since 2.0
	 * @param WC_Order $order the order
	 * @param string $error_message a message to display inside the "Payment Failed" order note
	 */
	protected function mark_order_as_failed_quiet( $order, $error_message ) {

		$order_note = sprintf( _x( '%s Payment Failed (%s)', 'Order Note: (Payment method) Payment failed (error)', 'woocommerce-gateway-moneris' ), $this->get_method_title(), $error_message );

		// Mark order as failed if not already set, otherwise, make sure we add the order note so we can detect when someone fails to check out multiple times
		if ( ! $order->has_status( 'failed' ) ) {
			$order->update_status( 'failed', $order_note );
		} else {
			$order->add_order_note( $order_note );
		}

		$this->add_debug_message( $error_message, 'error' );

		// shhhh quiet like
		// wc_add_notice( __( 'An error occurred, please try again or try an alternate form of payment.', 'woocommerce-gateway-moneris' ), 'error' );
	}


	/** Frontend methods ******************************************************/


	/**
	 * Enqueues the required gateway.js library and custom checkout javascript.
	 *
	 * Also localizes payment method validation errors.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function enqueue_scripts() {

		// call to parent and determine whether we need to load
		if ( ! parent::enqueue_scripts() ) {
			return false;
		}

		// enqueue the frontend styles
		wp_enqueue_style( 'wc-moneris', $this->get_plugin()->get_plugin_url() . '/assets/css/frontend/wc-moneris.min.css', false, WC_Moneris::VERSION );

		return true;
	}


	/** Getter methods ******************************************************/


	/**
	 * Gets the API instance.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Moneris_API API instance
	 */
	public function get_api() {

		if ( isset( $this->api ) ) {
			return $this->api;
		}

		require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-moneris-api.php' );
		require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-moneris-api-request.php' );
		require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-moneris-api-response.php' );
		require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-moneris-api-create-payment-token-response.php' );
		require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-moneris-api-delete-payment-token-response.php' );
		require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-moneris-api-receipt-response.php' );

		require_once( $this->get_plugin()->get_plugin_path() . '/includes/api/class-wc-moneris-api-response-message-helper.php' );

		return $this->api = new WC_Moneris_API( $this->get_id(), $this->get_api_endpoint(), $this->get_store_id(), $this->get_api_token(), $this->get_integration_country() );
	}


	/**
	 * Returns the configured integration country identifier
	 *
	 * @since 2.0
	 * @return string one of 'us' or 'ca'
	 */
	public function get_integration_country() {
		return $this->integration_country;
	}


	/**
	 * Set the integration country identifier
	 *
	 * @since 2.3.2
	 * @param string $country, either `us` or `ca`
	 */
	public function set_integration_country( $country ) {

		$this->integration_country = $country;
	}


	/**
	 * Returns true if the configured integration is US
	 *
	 * @since 2.0
	 * @param string $integration optional integration id, one of 'us' or 'ca'.
	 *        Defaults to currently configured integration
	 * @return boolean true if the configured integration is US
	 */
	public function is_us_integration( $integration = null ) {

		if ( is_null( $integration ) ) {
			$integration = $this->get_integration_country();
		}

		return \WC_Moneris::INTEGRATION_US == $integration;
	}


	/**
	 * Returns true if the configured integration is Canadian
	 *
	 * @since 2.0
	 * @param string $integration optional integration id, one of 'us' or 'ca'.
	 *        Defaults to currently configured integration
	 * @return boolean true if the configured integration is Canadian
	 */
	public function is_ca_integration( $integration = null ) {

		if ( is_null( $integration ) ) {
			$integration = $this->get_integration_country();
		}

		return \WC_Moneris::INTEGRATION_CA == $integration;
	}


	/**
	 * Returns true if the hosted tokenization option is enabled
	 *
	 * @since 2.0
	 * @return boolean true if the hosted tokenization option is enabled
	 */
	public function hosted_tokenization_enabled() {
		return 'yes' == $this->hosted_tokenization;
	}

	/**
	 * Returns the hosted tokenization profile ID to be used with the sandbox. The
	 * available profile IDs are taken from Moneris test accounts, and are
	 * "authentication bypassed" ones.
	 *
	 * @since 2.8.0
	 * @link https://github.com/skyverge/wc-plugins/issues/1853
	 * @return string
	 */
	protected function get_test_hosted_tokenization_profile_id() {

		if ( '' !== $this->test_hosted_tokenization_profile_id ) {
			return $this->test_hosted_tokenization_profile_id;
		}

		$profile_ids = array(
			\WC_Moneris::INTEGRATION_CA => wc_moneris()->get_ca_test_ht_profile_ids(),
			\WC_Moneris::INTEGRATION_US => wc_moneris()->get_us_test_ht_profile_ids(),
		);

		if ( isset( $profile_ids[ $this->get_integration_country() ][ $this->get_store_id() ] ) ) {
			return $profile_ids[ $this->get_integration_country() ][ $this->get_store_id() ];
		}

		return '';
	}

	/**
	 * Returns the currently configured store id, based on the current
	 * environment and integration country
	 *
	 * @since 2.0
	 * @return string the current store id
	 */
	public function get_hosted_tokenization_profile_id() {

		if ( $this->is_production_environment() ) {
			$profile_id = $this->hosted_tokenization_profile_id;
		} else {
			$profile_id = $this->get_test_hosted_tokenization_profile_id();
		}

		// get the current order ID
		$order_id = isset( $GLOBALS['wp']->query_vars['order-pay'] ) ? absint( $GLOBALS['wp']->query_vars['order-pay'] ) : 0;

		/**
		 * Filter the hosted tokenization profile ID.
		 *
		 * @since 2.5.0
		 * @param string $profile_id the profile ID
		 * @param int $order_id the order ID
		 * @param \WC_Gateway_Moneris_Credit_Card the gateway instance
		 */
		return apply_filters( 'wc_moneris_hosted_tokenization_profile_id', $profile_id, $order_id, $this );
	}


	/**
	 * Returns the hosted tokenization url, which can be used to create the
	 * iframe url
	 *
	 * @since 2.0
	 * @return string hosted tokenization url
	 */
	public function get_hosted_tokenization_url() {
		return $this->get_moneris_host() . '/HPPtoken/index.php';
	}


	/**
	 * Returns the hosted tokenization iframe url, given the currently configured
	 * environment, integration, and profile ID
	 *
	 * @since 2.0.0
	 *
	 * @return string hosted tokenization iframe url
	 */
	public function get_hosted_tokenization_iframe_url() {

		$url = $this->get_hosted_tokenization_url();

		if ( $this->get_hosted_tokenization_profile_id() ) {
			$url = add_query_arg( array( 'id' => $this->get_hosted_tokenization_profile_id() ), $url );
		}

		/**
		 * Filters the hosted tokenization input field styles.
		 *
		 * @since 2.0.0
		 *
		 * @param string css styles
		 * @param \WC_Gateway_Moneris_Credit_Card
		 */
		$css_textbox = apply_filters( 'wc_moneris_hosted_tokenization_css_textbox', 'width:calc( 100% - 1px);border-radius:3px;font-size:1.5em;color:rgb(102,102,102);padding:8px;border:1px solid rgb(187,187,187);line-height:1.5;background-color:rgb(255,255,255);margin:0;', $this );

		if ( $css_textbox && '' !== $css_textbox ) {
			$url = add_query_arg( array( 'css_textbox' => (string) $css_textbox ), $url );
		}

		/**
		 * Filters the hosted tokenization iframe body styles.
		 *
		 * @since 2.10.0
		 *
		 * @param string css styles
		 * @param \WC_Gateway_Moneris_Credit_Card
		 */
		$css_body = apply_filters( 'wc_moneris_hosted_tokenization_css_body', '', $this );

		if ( $css_body && '' !== $css_body ) {
			$url = add_query_arg( array( 'css_body' => (string) $css_body ), $url );
		}

		return $url;
	}


	/**
	 * Returns true if hosted tokenization is available (enabled and profile
	 * id set)
	 *
	 * @since 2.0
	 * @return boolean true if hosted tokenization is available, false otherwise
	 */
	public function hosted_tokenization_available() {
		return $this->hosted_tokenization_enabled() && $this->get_hosted_tokenization_profile_id();
	}


	/**
	 * Returns the Moneris host given $environment and $integration
	 *
	 * @since 2.0
	 * @param string $environment optional environment id, one of 'test' or 'production'.
	 *        Defaults to currently configured environment
	 * @param string $integration optional integration id, one of 'us' or 'ca'.
	 *        Defaults to currently configured integration
	 * @return string moneris host based on the environment and integration
	 */
	public function get_moneris_host( $environment = null, $integration = null ) {

		// get parameter defaults
		if ( is_null( $environment ) ) {
			$environment = $this->get_environment();
		}
		if ( is_null( $integration ) ) {
			$integration = $this->get_integration_country();
		}

		if ( $this->is_production_environment( $environment ) ) {
			return \WC_Moneris::INTEGRATION_US == $integration ? \WC_Moneris::PRODUCTION_URL_ENDPOINT_US : WC_Moneris::PRODUCTION_URL_ENDPOINT_CA;
		} else {
			return \WC_Moneris::INTEGRATION_US == $integration ? \WC_Moneris::TEST_URL_ENDPOINT_US : WC_Moneris::TEST_URL_ENDPOINT_CA;
		}
	}


	/**
	 * Returns the API endpoint based on the environment and integration
	 * country
	 *
	 * @since 2.0
	 * @return string current API endpoint URL
	 */
	public function get_api_endpoint() {

		$endpoint = $this->get_moneris_host();

		if ( $this->is_us_integration() ) {
			return $endpoint . '/gateway_us/servlet/MpgRequest';
		} else {
			return $endpoint . '/gateway2/servlet/MpgRequest';
		}
	}


	/**
	 * Gets the merchant center login URL
	 *
	 * @since 2.0
	 * @param string $environment optional environment id, one of 'test' or 'production'.
	 *        Defaults to currently configured environment
	 * @param string $integration optional integration id, one of 'us' or 'ca'.
	 *        Defaults to currently configured integration
	 * @return string merchant center login URL based on the environment and integration
	 */
	public function get_merchant_center_url( $environment = null, $integration = null ) {

		// get parameter defaults
		if ( is_null( $environment ) ) {
			$environment = $this->get_environment();
		}
		if ( is_null( $integration ) ) {
			$integration = $this->get_integration_country();
		}

		$endpoint = $this->get_moneris_host( $environment, $integration );

		if ( $this->is_us_integration( $integration ) ) {
			return $endpoint . '/usmpg';
		} else {
			return $endpoint . '/mpg';
		}
	}


	/**
	 * Returns true if the configured dynamic descriptor is valid.  For the US
	 * integration, the dynamic descriptor must be 20 characters or less
	 *
	 * @since 2.0
	 * @return boolean true if the configured dynamic descriptor is valid
	 */
	public function dynamic_descriptor_is_valid() {
		if ( $this->is_us_integration() && strlen( $this->get_dynamic_descriptor() ) > 20 ) {
			return false;
		}

		return true;
	}


	/**
	 * Returns the configured dynamic descriptor
	 *
	 * @since 2.0
	 * @param boolean $safe optional parameter, to indicate whether to return a
	 *        safe (US integration) version.  Defaults to false
	 * @return string the dynamic descriptor
	 */
	public function get_dynamic_descriptor( $safe = false ) {
		if ( $safe && $this->is_us_integration() ) {
			return substr( $this->dynamic_descriptor, 0, 20 );
		} else {
			return $this->dynamic_descriptor;
		}
	}


	/**
	 * Returns the currently configured store id, based on the current
	 * environment and integration country
	 *
	 * @since 2.0
	 * @return string the current store id
	 */
	public function get_store_id() {
		if ( $this->is_production_environment() ) {
			return $this->store_id;
		} else {
			return $this->is_us_integration() ? $this->us_test_store_id : $this->ca_test_store_id;
		}
	}


	/**
	 * Returns the currently configured API token, based on the current
	 * environment and integration country
	 *
	 * @since 2.0
	 * @return string the current store id
	 */
	public function get_api_token() {
		if ( $this->is_production_environment() ) {
			return $this->api_token;
		} else {
			if ( $this->is_us_integration() ) {
				return 'qatoken';
			} else {
				if ( 'moneris' == $this->get_store_id() ) {
					return 'hurgle';
				} else {
					return 'yesguy';
				}
			}
		}
	}


	/**
	 * Gets the custom payment form instance.
	 *
	 * @since 2.8.0
	 *
	 * @return \WC_Moneris_Payment_Form
	 */
	public function get_payment_form_instance() {
		return new \WC_Moneris_Payment_Form( $this );
	}


	/**
	 * Process a void.
	 *
	 * @since 2.8.0
	 * @param WC_Order $order order object (with refund class member already added)
	 * @return bool|WP_Error true on success, or a WP_Error object on failure/error
	 */
	protected function process_void( WC_Order $order ) {

		// Remove the action that triggers the reverse authorization. This will
		// prevent the operation from being performed twice
		remove_action( 'woocommerce_order_status_on-hold_to_cancelled', array( $this, 'maybe_reverse_authorization' ) );
		remove_action( 'woocommerce_order_action_' . $this->get_plugin()->get_id() . '_reverse_authorization', array( $this, 'maybe_reverse_authorization' ) );

		/* In Moneris, voids for pre-auth transactions must be processed as credit
		 * card authorisation reversals. Such operation requires the same meta data
		 * that is used for capture, hence the call to get_order_for_capture().
		 *
		 * @link https://developer.moneris.com/Documentation/NA/E-Commerce%20Solutions/API/Purchase%20Correction?lang=php
		 */
		$order = $this->get_order_for_capture( $order );

		return parent::process_void( $order );
	}


	/**
	 * Return the gateway-specifics JS script handle. This is used for:
	 *
	 * + enqueuing the script
	 * + the localized JS script param object name
	 *
	 * Defaults to 'wc-<plugin ID dasherized>'.
	 *
	 * @since 2.8.0
	 * @return string
	 */
	protected function get_gateway_js_handle() {

		if ( $this->hosted_tokenization_enabled() ) {
			return 'wc-moneris-hosted-tokenization';
		}

		return parent::get_gateway_js_handle();
	}


}
