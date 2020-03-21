<?php
/************************************
* the code below is just a standard
* options page. Substitute with
* your own.
*************************************/

function cwp_as_license_menu() {
	add_plugins_page( 'Anything Slider', 'Anything Slider', 'manage_options', 'as-license', 'cwp_as_license_page' );
}
add_action('admin_menu', 'cwp_as_license_menu');

function cwp_as_license_page() {
	$license 	= get_option( 'cwp_as_license_key' );
	$status 	= get_option( 'cwp_as_license_status' );
	?>
	<div class="wrap anything_slider">


		<h2><?php _e('Anything Slider'); ?><span style="background: #d6d4d4;padding: 5px;margin-left: 10px;border-radius: 5px;"><?php _e('v'.CWP_AS_VERSION); ?></span></h2>
		<form method="post" action="options.php">

			<?php settings_fields('cwp_as_license'); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key'); ?>
						</th>
						<td>
							<input id="cwp_as_license_key" name="cwp_as_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<label class="description" for="cwp_as_license_key"><?php _e('Enter your license key'); ?></label>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Activate License'); ?>
							</th>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<span style="color:green;"><?php _e('active'); ?></span>
									<?php wp_nonce_field( 'cwp_as_nonce', 'cwp_as_nonce' ); ?>
									<input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
								<?php } else {
									wp_nonce_field( 'cwp_as_nonce', 'cwp_as_nonce' ); ?>
									<input type="submit" class="button-secondary" name="edd_license_activate" value="<?php _e('Activate License'); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
		<hr>
        <div class="col">

            <h3 class="anythingslider_demos"><?php _e( 'Free Layouts & Starter Kits', 'Divi' ); ?></h3>
            <p><?php _e('We have a growing library for you to get started with creating awesome interactive content using Anything Slider quickly,', 'Divi'); ?></p>
           <?php _e('<a class="button-primary" href="http://demo.cakewp.com/anythingslider/starter-kit/" target="_blank">Starter Kit</a> <a class="button-primary" href="http://demo.cakewp.com/anythingslider/layout-packs/" target="_blank">Layout Packs</a>', 'Divi'); ?>
        </div>

        <div class="col">

            <h3 class="anythingslider_docs"><?php _e( 'Documentation', 'Divi' ); ?></h3>
            <p><?php _e('Anything Slider has detailed documentation to help you out from installing to using the plugin. If you need help this is the first plase to visit', 'Divi'); ?></p>
           <?php _e('<a class="button-primary" href="http://demo.cakewp.com/anythingslider/documentation" target="_blank">Read Documentation</a>', 'Divi'); ?>
        </div>

        <div class="col">

            <h3 class="anythingslider_support"><?php _e( 'Support', 'Divi' ); ?></h3>
            <p><?php _e('If you get stuck somewhere and unable to find help from the documentation, please open a support ticket here and we will get back to you ASAP.', 'Divi'); ?></p>
           <?php _e('<a class="button-primary" href="http://cakewp.com/support" target="_blank">Open Support Ticket</a>', 'Divi'); ?>
        </div>
	<?php

}

function cwp_as_register_option() {
	// creates our settings in the options table
	register_setting('cwp_as_license', 'cwp_as_license_key', 'cwp_as_edd_sanitize_license' );
}
add_action('admin_init', 'cwp_as_register_option');

function cwp_as_edd_sanitize_license( $new ) {
	$old = get_option( 'cwp_as_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'cwp_as_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
* this illustrates how to activate
* a license key
*************************************/

function cwp_as_activate_license() {
	// listen for our activate button to be clicked
	if( isset( $_POST['edd_license_activate'] ) ) {
		// run a quick security check
	 	if( ! check_admin_referer( 'cwp_as_nonce', 'cwp_as_nonce' ) )
			return; // get out if we didn't click the Activate button
		// retrieve the license from the database
		$license = trim( get_option( 'cwp_as_license_key' ) );
		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => CWP_AS_ITEM_ID, // The ID of the item in EDD
			'url'        => home_url()
		);
		// Call the custom API.
		$response = wp_remote_post( CWP_AS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'revoked' :
						$message = __( 'Your license key has been disabled.' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.' );
						break;
					case 'invalid' :
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.' );
						break;
					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), CWP_AS_ITEM_NAME );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.' );
						break;
					default :
						$message = __( 'An error occurred, please try again.' );
						break;
				}
			}
		}
		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'plugins.php?page=' . 'as-license' );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
			wp_redirect( $redirect );
			exit();
		}
		// $license_data->license will be either "valid" or "invalid"
		update_option( 'cwp_as_license_status', $license_data->license );
		wp_redirect( admin_url( 'plugins.php?page=' . 'as-license' ) );
		exit();
	}
}
add_action('admin_init', 'cwp_as_activate_license');

//Admin notices
function cwp_as_admin_notices() {
	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
		switch( $_GET['sl_activation'] ) {
			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;
			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;
		}
	}
}
add_action( 'admin_notices', 'cwp_as_admin_notices' );

/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function cwp_as_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['edd_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'cwp_as_nonce', 'cwp_as_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'cwp_as_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => CWP_AS_ITEM_ID, // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( CWP_AS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'cwp_as_license_status' );

	}
}
add_action('admin_init', 'cwp_as_deactivate_license');


/************************************
* this illustrates how to check if
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function cwp_as_check_license() {

	global $wp_version;

	$license = trim( get_option( 'cwp_as_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => CWP_AS_ITEM_ID,
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( CWP_AS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}
