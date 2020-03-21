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
 * needs please refer to https://docs.woocommerce.com/document/moneris/ for more information.
 *
 * @author    SkyVerge
 * @copyright Copyright (c) 2012-2019, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_5_0 as Framework;

/**
 * WooCommerce Moneris Gateway Main Plugin Class.
 *
 * @since 2.0.0
 *
 * @method \WC_Gateway_Moneris_Credit_Card get_gateway( $gateway_id = null )
 */
class WC_Moneris extends Framework\SV_WC_Payment_Gateway_Plugin {


	/** version number */
	const VERSION = '2.12.0';

	/** @var WC_Moneris single instance of this plugin */
	protected static $instance;

	/** the plugin id */
	const PLUGIN_ID = 'moneris';

	/** the credit card gateway class name */
	const CREDIT_CARD_GATEWAY_CLASS_NAME = 'WC_Gateway_Moneris_Credit_Card';

	/** the credit card gateway id */
	const CREDIT_CARD_GATEWAY_ID = 'moneris';

	/** the interac online gateway class name */
	const INTERAC_GATEWAY_CLASS_NAME = 'WC_Gateway_Moneris_Interac';

	/** the interac online gateway id */
	const INTERAC_GATEWAY_ID = 'moneris_interac';

	/** the production URL endpoint for the Canadian integration */
	const PRODUCTION_URL_ENDPOINT_CA = 'https://www3.moneris.com';

	/** the test (sandbox) URL endpoint for the Canadian integration */
	const TEST_URL_ENDPOINT_CA = 'https://esqa.moneris.com';

	/** the production URL endpoint for the US integration */
	const PRODUCTION_URL_ENDPOINT_US = 'https://esplus.moneris.com';

	/** the test (sandbox) URL endpoint for the US integration */
	const TEST_URL_ENDPOINT_US = 'https://esplusqa.moneris.com';

	/** the Canadian integration identifier */
	const INTEGRATION_CA = 'ca';

	/** the US integration identifier */
	const INTEGRATION_US = 'us';


	/** @var array the Canadian test hosted tokenization profile IDs */
	protected $ca_test_ht_profile_ids;

	/** @var array the US test hosted tokenization profile IDs */
	protected $us_test_ht_profile_ids;


	/**
	 * Constructs the class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			[
				'text_domain' => 'woocommerce-gateway-moneris',
				'gateways'    => [
					self::CREDIT_CARD_GATEWAY_ID => self::CREDIT_CARD_GATEWAY_CLASS_NAME,
					self::INTERAC_GATEWAY_ID     => self::INTERAC_GATEWAY_CLASS_NAME,
				],
				'dependencies'       => [
					'php_extensions' => [ 'SimpleXML', 'xmlwriter', 'dom' ],
				],
				'currencies'         => [ 'CAD' ],
				'require_ssl'        => true,
				'supports'           => [
					self::FEATURE_CAPTURE_CHARGE,
					self::FEATURE_CUSTOMER_ID,
					self::FEATURE_MY_PAYMENT_METHODS,
				],
			]
		);

		$this->ca_test_ht_profile_ids = [
			'store1' => 'ht2AEB6OCPZ9Q2Q',
			'store2' => 'ht53LEMAF6364YO',
			'store3' => 'ht2DJSN9Y12I7BL',
			'store5' => 'ht1F6MUXJMN8NOS',
		];

		$this->us_test_ht_profile_ids = [
			'monusqa002' => 'ht5E9HQZ69IBDJ2',
			'monusqa003' => 'ht9OLFBJXAE7ZR2',
			//'monusqa004' => '', // hosted tokenization is not enabled on US Test Store 4
			'monusqa005' => 'htXPENIGFR75XHD',
			'monusqa006' => 'htZP9GTUFJOM128',
		];

		$this->includes();

		add_action( 'init', [ $this, 'include_template_functions' ], 25 );

		add_action( 'admin_enqueue_scripts',  [ $this, 'enqueue_admin_scripts' ] );

		// Display Interac issuer data to customer
		add_action( 'woocommerce_order_details_after_order_table', [ $this, 'interac_order_table_receipt_data' ] );
		add_action( 'woocommerce_email_after_order_table',         [ $this, 'interac_email_order_table_receipt_data' ], 10, 3 );

		add_action( 'woocommerce_order_status_on-hold_to_cancelled', [ $this, 'maybe_reverse_authorization' ] );

		if ( is_admin() && ! is_ajax() ) {

			add_filter( 'woocommerce_order_actions', [ $this, 'add_order_reverse_authorization_action' ] );

			add_action( 'woocommerce_order_action_' . $this->get_id() . '_reverse_authorization', [ $this, 'maybe_reverse_authorization' ] );
		}

		// Pay Page - Hosted Tokenization Checkout
		// AJAX handler to handle request logging
		add_action( 'wp_ajax_wc_payment_gateway_' . $this->get_id() . '_handle_hosted_tokenization_response',        [ $this, 'handle_hosted_tokenization_response' ] );
		add_action( 'wp_ajax_nopriv_wc_payment_gateway_' . $this->get_id() . '_handle_hosted_tokenization_response', [ $this, 'handle_hosted_tokenization_response' ] );
	}


	/**
	 * Loads any required files.
	 *
	 * @since 2.0.0
	 */
	public function includes() {

		// gateway classes
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-moneris-credit-card.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-moneris-interac.php' );

		// tokens handler class
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-moneris-payment-tokens-handler.php' );

		// payment forms
		require_once( $this->get_plugin_path() . '/includes/payment-forms/class-wc-moneris-payment-form.php' );
	}


	/**
	 * Initializes the lifecycle handler.
	 *
	 * @since 2.11.0
	 */
	protected function init_lifecycle_handler() {

		require_once( $this->get_plugin_path() . '/includes/Handlers/Lifecycle.php' );

		$this->lifecycle_handler = new \SkyVerge\WooCommerce\Moneris\Handlers\Lifecycle( $this );
	}


	/**
	 * Gets the settings page link.
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_id gateway ID
	 * @return string
	 */
	public function get_settings_link( $gateway_id = null ) {

		return sprintf( '<a href="%s">%s</a>',
			$this->get_settings_url( $gateway_id ),
			self::CREDIT_CARD_GATEWAY_ID === $gateway_id ? __( 'Configure Moneris', 'woocommerce-gateway-moneris' ) : __( 'Configure Interac', 'woocommerce-gateway-moneris' )
		);
	}


	/**
	 * Adds any required admin notices.
	 *
	 * @since  2.1.0
	 */
	public function add_admin_notices() {

		parent::add_admin_notices();

		// show a notice for any settings/configuration issues
		$this->add_settings_admin_notices();
	}


	/**
	 * Enqueues scripts in the WP Admin.
	 *
	 * @since 2.10.0
	 */
	public function enqueue_admin_scripts() {

		wp_enqueue_script( 'woocommerce_moneris_admin', $this->get_plugin_url() . '/assets/js/admin/wc-moneris-admin.min.js' );

		wp_localize_script( 'woocommerce_moneris_admin', 'wc_moneris_admin', [
			'integration_ca'                             => self::INTEGRATION_CA,
			'integration_us'                             => self::INTEGRATION_US,
			'ca_sandbox_hosted_tokenization_profile_ids' => $this->get_ca_test_ht_profile_ids(),
			'us_sandbox_hosted_tokenization_profile_ids' => $this->get_us_test_ht_profile_ids(),
		] );
	}


	/**
	 * Adds any settings admin notices.
	 *
	 * @since  2.0.0
	 */
	private function add_settings_admin_notices() {

		$settings = $this->get_gateway_settings( self::CREDIT_CARD_GATEWAY_ID );

		// technically not DRY, but avoids unnecessary instantiation of the gateway class
		if ( (
			( isset( $settings['integration'] ) && 'us' === $settings['integration'] && strlen( $settings['dynamic_descriptor'] ) > 20 && ! isset( $_POST['woocommerce_moneris_integration'] ) ) ||
			( isset( $_POST['woocommerce_moneris_integration'] ) && 'us' === $_POST['woocommerce_moneris_integration'] && strlen( $_POST['woocommerce_moneris_dynamic_descriptor'] ) > 20 )
		) ) {

			$message = sprintf(
				__( '%1$sMoneris Gateway:%2$s US integration dynamic descriptor is too long.  You are recommended to %3$sshorten%4$s it to 20 characters or less as only the first 20 characters will be used.', 'woocommerce-gateway-moneris' ),
				'<strong>', '</strong>',
				'<a href="' . $this->get_settings_url() . '#woocommerce_moneris_dynamic_descriptor">', '</a>'
			);
			$this->get_admin_notice_handler()->add_admin_notice( $message, 'us-dynamic-descriptor-notice' );
		}

		$environment = isset( $_POST['woocommerce_moneris_environment'] ) ? $_POST['woocommerce_moneris_environment'] : $settings['environment'];

		// warning if hosted tokenization is enabled but no profile id is configured in the production environment
		// TODO: restore this for both environments once the Profile ID field is restored and pre-populated in the sandbox settings {CW 2018-01-17}
		if ( 'production' === $environment ) {

			$hosted_tokenization_enabled    = isset( $settings['hosted_tokenization'] ) && 'yes' === $settings['hosted_tokenization'];
			$hosted_tokenization_profile_id = isset( $settings['hosted_tokenization_profile_id'] ) ? $settings['hosted_tokenization_profile_id'] : '';

			// catch any immediate settings changes
			if ( isset( $_POST['woocommerce_moneris_hosted_tokenization'] ) ) {
				$hosted_tokenization_profile_id = '1' === $_POST['woocommerce_moneris_hosted_tokenization'];
			}

			// catch any immediate settings changes
			if ( isset( $_POST['woocommerce_moneris_hosted_tokenization_profile_id'] ) ) {
				$hosted_tokenization_profile_id = $_POST['woocommerce_moneris_hosted_tokenization_profile_id'];
			}

			if ( $hosted_tokenization_enabled && ! $hosted_tokenization_profile_id ) {

				$message = sprintf(
					__( '%1$sMoneris Gateway:%2$s Hosted tokenization is enabled but will not be active until a %3$sProfile ID%4$s is configured.', 'woocommerce-gateway-moneris' ),
					'<strong>', '</strong>',
					'<a href="' . $this->get_settings_url() . '#woocommerce_moneris_hosted_tokenization_profile_id">', '</a>'
				);
				$this->get_admin_notice_handler()->add_admin_notice( $message, 'hosted-tokenization-profile-id-missing-notice', [ 'notice_class' => 'error' ] );
			}
		}
	}


	/**
	 * Adds a "Reverse Authorization" action to the Admin Order Edit Order
	 * Actions dropdown
	 *
	 * @since 2.0.0
	 *
	 * @param array $actions available order actions
	 * @return array
	 */
	public function add_order_reverse_authorization_action( $actions ) {

		// bail adding a new order from the admin
		if ( ! isset( $_REQUEST['post'] ) ) {
			return $actions;
		}

		$order = wc_get_order( (int) $_REQUEST['post'] );

		if ( ! $order) {
			return $actions;
		}

		$payment_method = $order->get_payment_method();

		// bail if the order wasn't paid for with this gateway
		if ( ! $this->has_gateway( $payment_method ) ) {
			return $actions;
		}

		$gateway = $this->get_gateway( $payment_method );

		// ensure that the authorization is still valid for capture
		if ( ! $gateway->get_capture_handler()->order_can_be_captured( $order ) ) {
			return $actions;
		}

		$actions[ $this->get_id() . '_reverse_authorization' ] = __( 'Reverse Authorization', 'woocommerce-gateway-moneris' );

		return $actions;
	}


	/**
	 * Reverse a prior authorization if this payment method was used for the
	 * given order, the charge hasn't already been captured/reversed
	 *
	 * @since 2.0
	 * @see Framework\Payment_Gateway\Handlers\Capture::order_can_be_captured()
	 * @param \WC_Order|int $order the order identifier or order object
	 */
	public function maybe_reverse_authorization( $order ) {

		if ( ! is_object( $order ) ) {
			$order = wc_get_order( $order );
		}

		$payment_method = $order->get_payment_method();

		// bail if the order wasn't paid for with this gateway
		if ( ! $this->has_gateway( $payment_method ) ) {
			return;
		}

		$gateway = $this->get_gateway( $payment_method );

		// ensure the authorization is still valid for capture
		if ( ! $gateway->get_capture_handler() || ! $gateway->get_capture_handler()->order_can_be_captured( $order ) ) {
			return;
		}

		// remove order status change actions, otherwise we get a whole bunch of reverse calls and errors
		remove_action( 'woocommerce_order_status_on-hold_to_cancelled', [ $this, 'maybe_reverse_authorization' ] );
		remove_action( 'woocommerce_order_action_' . $this->get_id() . '_reverse_authorization', [ $this, 'maybe_reverse_authorization' ] );

		// Starting in WC 2.1 we need to remove the meta box order save action, otherwise the wp_update_post() call
		//  in WC_Order::update_status() to update the post last modified will re-trigger the save action, which
		//  will update the order status to $_POST['order_status'] which of course will be whatever the order status
		//  was prior to the auth capture (ie 'on-hold')
		remove_action( 'woocommerce_process_shop_order_meta', 'WC_Meta_Box_Order_Data::save', 10 );

		// perform the capture
		$gateway->do_credit_card_reverse_authorization( $order );
	}


	/** Frontend methods ******************************************************/


	/**
	 * Includes the template functions.
	 *
	 * @since 2.0.0
	 */
	public function include_template_functions() {

		require_once( $this->get_plugin_path() . '/includes/wc-gateway-moneris-template.php' );
	}


	/**
	 * Displays the Interac Issuer confirmation number and name in the order
	 * receipt table, if the given order was paid for via Interac.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order order object
	 */
	public function interac_order_table_receipt_data( $order ) {

		// non-interac order
		if ( self::INTERAC_GATEWAY_ID !== $order->get_payment_method( 'edit' ) ) {
			return;
		}

		$issuer_conf = $order->get_meta( '_wc_moneris_interac_idebit_issconf' );
		$issuer_name = $order->get_meta( '_wc_moneris_interac_idebit_issname' );

		// missing the data
		if ( ! $issuer_conf || ! $issuer_name ) {
			return;
		}

		// otherwise: display the idebit data
		?>
		<header>
			<h2><?php _e( 'INTERAC Details', 'woocommerce-gateway-moneris' ); ?></h2>
		</header>
		<dl class="interac_details">
			<dt><?php _e( 'Issuer Confirmation:', 'woocommerce-gateway-moneris' ); ?></dt><dd><?php echo esc_html( $issuer_conf ); ?></dd>
			<dt><?php _e( 'Issuer Name:', 'woocommerce-gateway-moneris' ); ?></dt><dd><?php echo esc_html( $issuer_name ); ?></dd>
		</dl>
		<?php
	}


	/**
	 * Displays the Interac Issuer confirmation number and name in the email
	 * order receipt table, if the given order was paid for via Interac.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @param bool $sent_to_admin whether the email was sent to admin
	 * @param bool $plain_text whether the email is plaintext
	 */
	public function interac_email_order_table_receipt_data( $order, $sent_to_admin, $plain_text = false ) {

		// non-interac order
		if ( self::INTERAC_GATEWAY_ID !== $order->get_payment_method( 'edit' ) ) {
			return;
		}

		$issuer_conf = $order->get_meta( '_wc_moneris_interac_idebit_issconf' );
		$issuer_name = $order->get_meta( '_wc_moneris_interac_idebit_issname' );

		// missing the data
		if ( ! $issuer_conf || ! $issuer_name ) {
			return;
		}

		if ( ! $plain_text ) {
			// html email
			?>
			<h2><?php _e( 'INTERAC Details', 'woocommerce-gateway-moneris' ); ?></h2>

			<p><strong><?php _e( 'Issuer Confirmation:', 'woocommerce-gateway-moneris' ); ?></strong> <?php echo esc_html( $issuer_conf ); ?></p>
			<p><strong><?php _e( 'Issuer Name:', 'woocommerce-gateway-moneris' ); ?></strong>         <?php echo esc_html( $issuer_name ); ?></p>
			<?php
		} else {
			// plain text email
			echo __( 'INTERAC Details', 'woocommerce-gateway-moneris' ) . "\n\n";

			echo __( 'Issuer Confirmation:', 'woocommerce-gateway-moneris' ) . ' ' . esc_html( $issuer_conf ) . "\n";
			echo __( 'Issuer Name:', 'woocommerce-gateway-moneris' )         . ' ' . esc_html( $issuer_name ) . "\n";
		}
	}


	/** Hosted Tokenization methods ******************************************************/


	/**
	 * Handles the hosted tokenization response by handing off to the gateway.
	 *
	 * @since 2.0.0
	 */
	public function handle_hosted_tokenization_response() {

		$this->get_gateway()->handle_hosted_tokenization_response();
	}


	/**
	 * Gets the hosted tokenization profile IDs for the Canada test stores.
	 *
	 * @return array
	 */
	public function get_ca_test_ht_profile_ids() {

		return $this->ca_test_ht_profile_ids;
	}


	/**
	 * Gets the hosted tokenization profile IDs for the US test stores.
	 *
	 * @return array
	 */
	public function get_us_test_ht_profile_ids() {

		return $this->us_test_ht_profile_ids;
	}


	/** Helper methods ******************************************************/


	/**
	 * Builds the Apple Pay handler instance.
	 *
	 * @since 1.11.0-dev.1
	 *
	 * @return \SkyVerge\WooCommerce\Moneris\Apple_Pay
	 */
	protected function build_apple_pay_instance() {

		require_once( $this->get_plugin_path() . '/includes/Apple_Pay.php' );
		require_once( $this->get_plugin_path() . '/includes/Apple_Pay/AJAX.php' );
		require_once( $this->get_plugin_path() . '/includes/Apple_Pay/Frontend.php' );

		return new \SkyVerge\WooCommerce\Moneris\Apple_Pay( $this );
	}


	/**
	 * Gets the one true instance of the plugin.
	 *
	 * @since 2.2.0
	 *
	 * @return \WC_Moneris
	 */
	public static function instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Gets the plugin sales page URL.
	 *
	 * @since 2.11.0
	 *
	 * @return string
	 */
	public function get_sales_page_url() {

		return 'https://woocommerce.com/products/moneris-gateway/';
	}


	/**
	 * Gets the plugin documentation URL.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_documentation_url() {

		return 'https://docs.woocommerce.com/document/moneris/';
	}


	/**
	 * Gets the plugin support URL.
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */
	public function get_support_url() {

		return 'https://woocommerce.com/my-account/marketplace-ticket-form/';
	}


	/**
	 * Returns the plugin name, localized
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_plugin_name() {

		return __( 'WooCommerce Moneris Gateway', 'woocommerce-gateway-moneris' );
	}


	/**
	 * Returns __FILE__
	 *
	 * @since 2.0
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {

		return __FILE__;
	}


} // end WC_Moneris


/**
 * Returns the One True Instance of Moneris
 *
 * @since 2.2.0
 * @return \WC_Moneris
 */
function wc_moneris() {

	return \WC_Moneris::instance();
}
