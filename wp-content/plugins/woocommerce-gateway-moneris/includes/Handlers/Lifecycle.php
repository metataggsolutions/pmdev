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

namespace SkyVerge\WooCommerce\Moneris\Handlers;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_5_0 as Framework;

/**
 * The lifecycle handler.
 *
 * @since 2.11.0
 *
 * @method \WC_Moneris get_plugin()
 */
class Lifecycle extends Framework\Plugin\Lifecycle {


	/**
	 * Constructs the class.
	 *
	 * @since 2.11.0
	 *
	 * @param \WC_Moneris $plugin
	 */
	public function __construct( \WC_Moneris $plugin ) {

		parent::__construct( $plugin );

		$this->upgrade_versions = [
			'2.3.3',
		];
	}


	/**
	 * Installs the plugin.
	 *
	 * @since 2.11.0
	 */
	protected function install() {

		// v1 releases didn't track the version number, so we can't tell what we're upgrading from
		if ( $this->get_plugin()->get_gateway_settings( \WC_Moneris::CREDIT_CARD_GATEWAY_ID ) ) {
			$this->upgrade( null );
		}
	}


	/**
	 * Performs any upgrade routines.
	 *
	 * @since 2.11.0
	 *
	 * @param string $installed_version currently installed version
	 */
	protected function upgrade( $installed_version ) {

		if ( null === $installed_version ) {
			// upgrading from v1
			$settings = $this->get_plugin()->get_gateway_settings( \WC_Moneris::CREDIT_CARD_GATEWAY_ID );

			// rename 'purchasecountry' to 'integration'
			$settings['integration'] = $settings['purchasecountry'];
			unset( $settings['purchasecountry'] );

			// framework standard
			$settings['enable_csc'] = $settings['enable_cvd'];
			unset( $settings['enable_cvd'] );

			$settings['dynamic_descriptor'] = $settings['dynamicdescriptor'];
			unset( $settings['dynamicdescriptor'] );

			$settings['environment'] = 'yes' === $settings['sandbox'] ? 'test' : 'production';
			unset( $settings['sandbox'] );

			if ( 'test' === $settings['environment'] ) {

				$settings['test_store_id'] = $settings['storeid'];
				unset( $settings['storeid'] );

				$settings['test_api_token'] = $settings['apitoken'];
				unset( $settings['apitoken'] );

			} else {

				$settings['store_id'] = $settings['storeid'];
				unset( $settings['storeid'] );

				$settings['api_token'] = $settings['apitoken'];
				unset( $settings['apitoken'] );

			}

			// v1 supported only charge transactions
			$settings['transaction_type'] = 'charge';

			// update to new settings
			update_option( 'woocommerce_' . \WC_Moneris::CREDIT_CARD_GATEWAY_ID . '_settings', $settings );
		}

		parent::upgrade( $installed_version );
	}


	/**
	 * Upgrades to v2.3.3.
	 *
	 * @since 2.11.0
	 */
	protected function upgrade_to_2_3_3() {

		$settings = $this->get_plugin()->get_gateway_settings( \WC_Moneris::CREDIT_CARD_GATEWAY_ID );

		$settings['integration_country'] = $settings['integration'];

		unset( $settings['integration'] );

		// update to new settings
		update_option( 'woocommerce_' . \WC_Moneris::CREDIT_CARD_GATEWAY_ID . '_settings', $settings );
	}


}
