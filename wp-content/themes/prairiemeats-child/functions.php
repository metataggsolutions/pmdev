<?php
/*
 Recommended way to include parent theme styles.
  (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
*/ 
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );   

	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.12.4');
		wp_enqueue_script('jquery');
	}



	wp_register_script('custom-carousel-js', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), false, false);
    wp_enqueue_script('custom-carousel-js');

        

    

    /* Call js and css files for product single page select box  */

    if(is_singular('product')) {

        if (! wp_script_is('select2', 'enqueued')) {

            wp_enqueue_script('select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', ['jquery']);

        }

        if (! wp_style_is('select2', 'enqueued')) {

            wp_enqueue_style('select2', WC()->plugin_url() . '/assets/css/select2.css', []);

        }

        wp_enqueue_script('woo-select2', get_stylesheet_directory_uri() . '/js/woo-variations-select2' . '.js', ['jquery', 'select2']);

    }

    

}



//

// Your code goes below

//



/* Added Instagram & Youtube Options in DIVI theme Option settings */

if ( ! function_exists( 'et_get_safe_localization' ) ) {

    function et_get_safe_localization( $string ) {

    	return wp_kses( $string, et_get_allowed_localization_html_elements() );

    }

}



if ( ! function_exists( 'et_get_allowed_localization_html_elements' ) ) {

    

    function et_get_allowed_localization_html_elements() {

    	$whitelisted_attributes = array(

    		'id'    => array(),

    		'class' => array(),

    		'style' => array(),

    	);

    

    	$whitelisted_attributes = apply_filters( 'et_allowed_localization_html_attributes', $whitelisted_attributes );

    

    	$elements = array(

    		'a'      => array(

    			'href'   => array(),

    			'title'  => array(),

    			'target' => array(),

    		),

    		'b'      => array(),

    		'em'     => array(),

    		'p'      => array(),

    		'span'   => array(),

    		'div'    => array(),

    		'strong' => array(),

    	);

    

    	$elements = apply_filters( 'et_allowed_localization_html_elements', $elements );

    

    	foreach ( $elements as $tag => $attributes ) {

    		$elements[ $tag ] = array_merge( $attributes, $whitelisted_attributes );

    	}

    

    	return $elements;

    }

    

}



if ( ! function_exists( 'et_load_core_options' ) ) {

    

    function et_load_core_options() {

        $options = require_once( get_stylesheet_directory() . esc_attr( "/panel_options.php" ) );

    }

    add_action( 'init', 'et_load_core_options', 999 );

    

}

/* End Added Instagram & Youtube Options in DIVI theme Option settings */



add_action( 'widgets_init', 'blog_widgets_init' );

function blog_widgets_init() {

   register_sidebar( array(

   'name' => __( 'Blog Section', 'theme-slug' ),

   'id' => 'blog-section',

   'description' => __( 'Add widgets here to appear in your blog details.', 'theme-slug' ),

   'before_widget' => '',

    'after_widget'  => '',

    'before_title'  => '<h4>',

    'after_title'   => '</h4>',

   ) );

}



add_action( 'widgets_init', 'footer_menu_widgets_init' );

function footer_menu_widgets_init() {

   register_sidebar( array(

   'name' => __( 'Footer Top Section', 'theme-slug' ),

   'id' => 'footer-top',

   'description' => __( 'Add widgets here to appear in your footer.', 'theme-slug' ),

   'before_widget' => '',

    'after_widget'  => '',

    'before_title'  => '<h3>',

    'after_title'   => '</h3>',

   ) );

}



add_action( 'widgets_init', 'footer_middle_widgets_init' );

function footer_middle_widgets_init() {

   register_sidebar( array(

   'name' => __( 'Footer Middle Section', 'theme-slug' ),

   'id' => 'footer-middle',

   'description' => __( 'Add widgets here to appear in your footer.', 'theme-slug' ),

   'before_widget' => '',

    'after_widget'  => '',

    'before_title'  => '<h4>',

    'after_title'   => '</h4>',

   ) );

}





/* Feature Post Select Option */

function sm_custom_meta() {

    add_meta_box( 'sm_meta', __( 'Featured Posts', 'sm-textdomain' ), 'sm_meta_callback', 'post' );

}

function sm_meta_callback( $post ) {

    $featured = get_post_meta( $post->ID );

    ?>

 

	<p>

    <div class="sm-row-content">

        <label for="is-feature">

            <input type="checkbox" name="is-feature" id="is-feature" value="yes" <?php if ( isset ( $featured['is-feature'] ) ) checked( $featured['is-feature'][0], 'yes' ); ?> />

            <?php _e( 'Featured this post', 'sm-textdomain' )?>

        </label>

        

    </div>

</p>

 

    <?php

}

add_action( 'add_meta_boxes', 'sm_custom_meta' );  



/**

 * Saves the custom meta input

 */

function sm_meta_save( $post_id ) {

 

    // Checks save status

    $is_autosave = wp_is_post_autosave( $post_id );

    $is_revision = wp_is_post_revision( $post_id );

    $is_valid_nonce = ( isset( $_POST[ 'sm_nonce' ] ) && wp_verify_nonce( $_POST[ 'sm_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

 

    // Exits script depending on save status

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {

        return;

    }

 

 // Checks for input and saves

if( isset( $_POST[ 'is-feature' ] ) ) {

    update_post_meta( $post_id, 'is-feature', 'yes' );

} else {

    update_post_meta( $post_id, 'is-feature', '' );

}

 

}

add_action( 'save_post', 'sm_meta_save' );





function cs_add_order_again_to_my_orders_actions( $actions, $order ) {

	if ( $order->has_status( 'completed' ) ) {

		$actions['order-again'] = array(

			'url'  => wp_nonce_url( add_query_arg( 'order_again', $order->get_id() ) , 'woocommerce-order_again' ),

			'name' => __( 'Re-orders', 'woocommerce' )

		);

	}

	return $actions;

}

add_filter( 'woocommerce_my_account_my_orders_actions', 'cs_add_order_again_to_my_orders_actions', 50, 2 );



add_action( 'get_header', 'bbloomer_remove_storefront_sidebar' );

function bbloomer_remove_storefront_sidebar() {

  if ( is_product()) {

     remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );

  }

}



/* Theme Options Settings */

if( function_exists('acf_add_options_page') ) {



	acf_add_options_page(array(

		'page_title' 	=> 'Theme Options',

		'menu_title'	=> 'Theme Options',

		'menu_slug' 	=> 'theme-options',

        'parent_slug'	=> '',

		'capability'	=> 'edit_posts',

        'position'      => false,

        'icon_url'      => false,

		'redirect'		=> true

	));

	

	acf_add_options_sub_page(array(

		'page_title' 	=> 'Theme Inner Page Settings',

		'menu_title'	=> 'Inner Page Settings',

		'menu_slug'   	=> 'theme-options-innerpage',

        'capability'	=> 'edit_posts',

		'parent_slug'	=> 'theme-options',

        'position'      => false,

        'icon_url'      => false

	));

	

}



/**

 * Remove product data tabs

 */

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );



function woo_remove_product_tabs( $tabs ) {



    //unset( $tabs['description'] );      	// Remove the description tab

    unset( $tabs['reviews'] ); 			// Remove the reviews tab

    unset( $tabs['additional_information'] );  	// Remove the additional information tab



    return $tabs;

}



/* Remove sorting from all pages */

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );



/* Change position of single page price */

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );







/* Remove SKU from product single page */

//add_filter( 'wc_product_sku_enabled', '__return_false' );



/* Remove Category from product single page */

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );



/* Add the label of Quantity in single page */

add_action( 'woocommerce_before_add_to_cart_quantity', 'bbloomer_echo_qty_front_add_cart' );

function bbloomer_echo_qty_front_add_cart() {

 echo '<div class="qty">Quantity: </div>'; 

}



/* Breadcrumb add custom icon */ 

add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );

function wcc_change_breadcrumb_delimiter( $defaults ) {

    // Change the breadcrumb delimeter from '/' to '>'

    $defaults['delimiter'] = '<i></i>';

    return $defaults;

}



/**

 * Change number of related products output

 */ 

function woo_related_products_limit() {

  global $product;

	

	$args['posts_per_page'] = 4;

	return $args;

}

add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );

  function jk_related_products_args( $args ) {

	$args['posts_per_page'] = 4; // 4 related products

	$args['columns'] = 3; // arranged in 2 columns

	return $args;

}



/* If cart is empty redirect to product page */

function wc_empty_cart_redirect_url() {

    return the_permalink(33216);

}

add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );





/* Add Continue Shopping Button on product single page */

add_action( 'woocommerce_after_add_to_cart_button', 'wc_continue_shopping_button' );

function wc_continue_shopping_button() {

 $shop_page_url = get_the_permalink(33216);

 if ( WC()->cart->get_cart_contents_count() != 0 ) {

     echo '<div class="continue_shipping">';

     echo ' <a href="'.$shop_page_url.'">Continue Shopping</a>';

     echo '</div>';  

 }

}





/* Product Listing page add View Details button */

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

add_action( 'woocommerce_after_shop_loop_item', 'custom_woocommerce_template_loop_add_to_cart', 10 );

 

function custom_woocommerce_template_loop_add_to_cart() {

	global $product;

	$detail_page = $product->get_permalink( $product->get_id() );

	echo '<a class="btn_details" href="'.$detail_page.'">View Details</a>';

}



/* Cart menu get cart item count */

add_filter( 'woocommerce_add_to_cart_fragments', 'iconic_cart_count_fragments', 10, 1 );

function iconic_cart_count_fragments( $fragments ) {

    $fragments['div.header-cart-count'] = '<div class="header-cart-count">' . WC()->cart->get_cart_contents_count() . '</div>';

    return $fragments;

    

}



/* Add Register Fields */

function wooc_extra_register_fields() {?>

   <p class="form-row form-row-first">

   <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>

   <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />

   </p>

   <p class="form-row form-row-last">

   <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>

   <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />

   </p>

   <div class="clear"></div>

   <?php

}

add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );





/* Register fields Validating. */

function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

	  if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {

			 $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );

	  }

	  if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {

			 $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );

	  }

		 return $validation_errors;

}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );



/* Below code save extra fields. */

function wooc_save_extra_register_fields( $customer_id ) {

	if ( isset( $_POST['billing_first_name'] ) ) {

		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

	}

	if ( isset( $_POST['billing_last_name'] ) ) {

		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

	}

}



/* Remove Sale tag from product */

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );



/* Add Wow tag on product listing page */

add_action( 'woocommerce_after_shop_loop_item', 'show_tags', 20 );

function show_tags() {

	global $product;

	$current_tags = get_the_terms( get_the_ID(), 'product_tag' );

	if ( $current_tags && ! is_wp_error( $current_tags ) ) { 

		foreach ( $current_tags as $tag ) {

			$tag_title[] = $tag->name; // tag name

			$tag_link = get_term_link( $tag ); // tag archive link

			if($tag->term_taxonomy_id == 104){

				echo "<div class='wow_tag'>" . $tag->name . "</div>";

			}

		}

		$commaList = implode(', ', $tag_title);

		echo "<div class='prd_tag'>";

		echo $commaList;

		echo "</div>";

	}

}





add_filter( 'gettext', 'change_woocommerce_return_to_shop_text', 20, 3 );

function change_woocommerce_return_to_shop_text( $translated_text, $text, $domain ) {

    switch ( $translated_text ) {

        case 'Go to the shop' :

		$translated_text = __( 'Shop Now', 'woocommerce' );

		

		case 'Go shop' :

		$translated_text = __( 'Shop Now', 'woocommerce' );

   break;

  }

 return $translated_text; 



}



/*function prefix_conditional_body_class( $classes ) {

    if( is_page_template('tpl-product-search.php') )

        $classes[] = 'woocommerce woocommerce-page archive';



    return $classes;

}

add_filter( 'body_class', 'prefix_conditional_body_class' );*/





add_filter( 'woocommerce_package_rates', 'bbloomer_woocommerce_tiered_shipping', 10, 2 );

   

function bbloomer_woocommerce_tiered_shipping( $rates, $package ) {

   

   $threshold = 100;

   if ( WC()->cart->subtotal <= $threshold ) {

         unset( $rates['flat_rate:8'] );

   } else {

      unset( $rates['flat_rate:7'] );

   }

   

   return $rates;

   

}









// Add Variation Custom fields

//Display Fields in admin on product edit screen

add_action( 'woocommerce_product_after_variable_attributes', 'woo_variable_fields', 10, 3 );

//Save variation fields values

add_action( 'woocommerce_save_product_variation', 'save_variation_fields', 10, 2 );

// Create new fields for variations

function woo_variable_fields( $loop, $variation_data, $variation ) {

  echo '<div class="variation-custom-fields">';

	

	  // Class Select

	  woocommerce_wp_select( 

	  array( 

		'id'          => 'class_select['. $loop .']', 

		'label'       => __( 'Class ', 'woocommerce' ), 

		'desc_tip'    => true,

		// 'wrapper_class' => 'form-row',

		'description' => __( 'Choose a Class.', 'woocommerce' ),

		'value'       => get_post_meta($variation->ID, 'class_select', true),

		'options' => array(

			'Fresh'   => __( 'Fresh', 'woocommerce' ),

			'Frozen'   => __( 'Frozen', 'woocommerce' ),

			'Dry' => __( 'Dry', 'woocommerce' )

		  )

		)

	  );

	  

	  // Unit Select

	  woocommerce_wp_select( 

	  array( 

		'id'          => 'unit_select['. $loop .']', 

		'label'       => __( 'Unit ', 'woocommerce' ), 

		'desc_tip'    => true,

		// 'wrapper_class' => 'form-row',

		'description' => __( 'Choose a Unit.', 'woocommerce' ),

		'value'       => get_post_meta($variation->ID, 'unit_select', true),

		'options' => array(

			'BG' => __( 'BG', 'woocommerce' ),

			'BX' => __( 'BX', 'woocommerce' ),

			'CS' => __( 'CS', 'woocommerce' ),

			'EA' => __( 'EA', 'woocommerce' ),

			'KG' => __( 'KG', 'woocommerce' ),

			'PC' => __( 'PC', 'woocommerce' ),

			'PK' => __( 'PK', 'woocommerce' ),

			'RP' => __( 'RP', 'woocommerce' ),

			'RS' => __( 'RS', 'woocommerce' ),

			'ST' => __( 'ST', 'woocommerce' ),

			'TR' => __( 'TR', 'woocommerce' )

		  )

		)

	  );

	  

	  // Price Unit Select

	  woocommerce_wp_select( 

	  array( 

		'id'          => 'price_unit_select['. $loop .']', 

		'label'       => __( 'Price Unit ', 'woocommerce' ), 

		'desc_tip'    => true,

		// 'wrapper_class' => 'form-row',

		'description' => __( 'Choose a Price Unit.', 'woocommerce' ),

		'value'       => get_post_meta($variation->ID, 'price_unit_select', true),

		'options' => array(

			'KG' => __( 'KG', 'woocommerce' ),

			'EACH' => __( 'EACH', 'woocommerce' ),

			'CASE' => __( 'CASE', 'woocommerce' ),

			'BAG' => __( 'BAG', 'woocommerce' ),

			/*'EA' => __( 'EA', 'woocommerce' ),

			'KG' => __( 'KG', 'woocommerce' ),

			'PC' => __( 'PC', 'woocommerce' ),

			'PK' => __( 'PK', 'woocommerce' ),

			'ST' => __( 'ST', 'woocommerce' ),

			'TR' => __( 'TR', 'woocommerce' )*/

		  )

		)

	  );

   

  echo "</div>"; 

 

    

  echo '<div class="woocommerce_options_panel" style="padding: 0;">';    

  woocommerce_wp_checkbox( 

		array( 

			'id'            => 'is_add_to_cart_variation['. $loop .']', 

			'label'         => __('Hide/Show Cart Button', 'woocommerce' ), 

			'description'   => __( 'Check if you want to hide cart button.', 'woocommerce' ),

			'value'         => get_post_meta( $variation->ID, 'is_add_to_cart_variation', true ), 

			)

		);

   echo '</div>';    

}

/** Save new fields for variations */

function save_variation_fields( $variation_id, $i) {

	

	// Class Select

	$select = $_POST['class_select'][$i];

	update_post_meta( $variation_id, 'class_select', esc_attr( $select ) );

	

	// Unit Select

	$select = $_POST['unit_select'][$i];

	update_post_meta( $variation_id, 'unit_select', esc_attr( $select ) );

	

	// Price Unit Select

	$select = $_POST['price_unit_select'][$i];

	update_post_meta( $variation_id, 'price_unit_select', esc_attr( $select ) );

    

    // Checkbox

	$checkbox = isset($_POST['is_add_to_cart_variation'][$i]) ? 'yes' : 'no';

    update_post_meta( $variation_id, 'is_add_to_cart_variation', $checkbox );

}



// Custom Product Variation

add_filter( 'woocommerce_available_variation', 'custom_load_variation_settings_products_fields' );

function custom_load_variation_settings_products_fields( $variations ) {

	//echo "<pre>";print_r($variations);

	global $post;

	

	if(get_post_meta( $variations[ 'variation_id' ], '_sku', true )){

		$variations['variation_custom_product_code'] = get_post_meta( $variations[ 'variation_id' ], '_sku', true );

	} else{	

	$variations['variation_custom_product_code'] = get_post_meta( $post->ID, '_sku', true );  

	}

	$variations['variation_custom_class_select'] = get_post_meta( $variations[ 'variation_id' ], 'class_select', true );  

 

	$variations['variation_custom_unit_select'] = get_post_meta( $variations[ 'variation_id' ], 'unit_select', true );  

  

	$variations['variation_custom_price_unit_select'] = get_post_meta( $variations[ 'variation_id' ], 'price_unit_select', true );

    

    $variations['variation_custom_is_add_to_cart_variation'] = get_post_meta( $variations[ 'variation_id' ], 'is_add_to_cart_variation', true );

  

  return $variations;

    

}



/**/



add_filter( 'woocommerce_catalog_settings', 'add_woocommerce_dimension_units' );



function add_woocommerce_dimension_units( $settings ) {

  foreach ( $settings as &$setting ) {



    if ( $setting['id'] == 'woocommerce_dimension_unit' ) {

      $options = array();



      foreach ( $setting['options'] as $key => $value ) {

        if ( $key == 'in' ) {

          // safely add foot and mile to the dimensions units, in the correct order

          $options[ $key ] = $value;



          if ( ! isset( $setting['options']['ft'] ) ) $options['ft'] = __( 'ft' );  // foot

          if ( ! isset( $options['yd'] ) )            $options['yd'] = __( 'yd' );  // yard (correct order)

          if ( ! isset( $setting['options']['mi'] ) ) $options['mi'] = __( 'mi' );  // mile



        } else {

          // maintain all other existing dimensions

          if ( ! isset( $options[ $key ] ) ) $options[ $key ] = $value;

        }

      }

      $setting['options'] = $options;

    }

  }



  return $settings;

}





/**

 * This adds the new unit to the WooCommerce admin

 */

function add_woocommerce_dimension_unit_league( $settings ) {



	foreach ( $settings as &$setting ) {



		if ( 'woocommerce_dimension_unit' == $setting['id'] ) {

			$setting['options']['league'] = __( 'league' );  // new unit

		}

	}



	return $settings;

}

add_filter( 'woocommerce_products_general_settings', 'add_woocommerce_dimension_unit_league' );



// Shop and archives pages: we replace the button add to cart by a link to the product

add_filter( 'woocommerce_loop_add_to_cart_link', 'custom_text_replace_button', 10, 2 );

function custom_text_replace_button( $button, $product  ) {

    $button_text = __("View product", "woocommerce");

    return '<a class="button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';

}



/* replacing add to cart button and quantities by a custom text */ 

add_action( 'woocommerce_single_product_summary', 'replacing_template_single_add_to_cart', 1, 0 );

function replacing_template_single_add_to_cart() {

	global $post;

	$is_add_to_cart = get_post_meta( $post->ID, 'is_add_to_cart', true ); 

    $is_add_to_cart_variation = get_post_meta( $post->ID, 'is_add_to_cart_variation', true ); 

	

	if($is_add_to_cart == "yes"){

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		

		add_action( 'woocommerce_single_product_summary', function(){



			$text = __("This item is available in store only.", "woocommerce");



			echo '<div class="visit_store_text" >'.$text.'</div>';

		}, 30 );

	}

}



/* The code for displaying WooCommerce Product Custom Fields Hide/Show Cart Button start */

add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields' ); 



/* Following code Saves  WooCommerce Product Custom Fields */

add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save' );



function woocommerce_product_custom_fields () {

	global $woocommerce, $post;

	echo '<div class=" product_custom_field ">';



	woocommerce_wp_checkbox( 

		array( 

			'id'            => 'is_add_to_cart', 

			'label'         => __('Hide/Show Cart Button', 'woocommerce' ), 

			'description'   => __( 'Check if you want to hide cart button.', 'woocommerce' ),

			'value'         => get_post_meta( $post->ID, 'is_add_to_cart', true ), 

			)

		);

	echo '</div>';

}



function woocommerce_product_custom_fields_save($post_id)

{

	$woocommerce_custom_product_text_field = $_POST['is_add_to_cart'];

	update_post_meta($post_id, 'is_add_to_cart', esc_attr($woocommerce_custom_product_text_field));

}



/* The code for displaying WooCommerce Product Custom Fields Hide/Show Cart Button end */





add_filter( 'woocommerce_no_shipping_available_html', 'my_custom_no_shipping_message' );

add_filter( 'woocommerce_cart_no_shipping_available_html', 'my_custom_no_shipping_message' );

function my_custom_no_shipping_message( $message ) {

	return __( 'Delivery is only available within Saskatoon but is coming soon to Regina. Please choose delivery to one of our Saskatoon locations or contact your closest Prairie Meats to place an order' );

}



/*

 * Add custom Fields to simple products

 */

function simple_woo_add_custom_fields() {



	global $woocommerce, $post;



	echo '<div class="options_group">';



 	// Simple product class select

	  woocommerce_wp_select( 

	  array( 

		'id'          => 'class_select', 

		'label'       => __( 'Class ', 'woocommerce' ), 

		'desc_tip'    => true,

		// 'wrapper_class' => 'form-row',

		'description' => __( 'Choose a Class.', 'woocommerce' ),

		'value'       => get_post_meta($post->ID, 'class_select', true),

		'options' => array(

			'Fresh'   => __( 'Fresh', 'woocommerce' ),

			'Frozen'   => __( 'Frozen', 'woocommerce' ),

			'Dry' => __( 'Dry', 'woocommerce' )

		  )

		)

	  );

    

    //  Simple product unit select

	  woocommerce_wp_select( 

	  array( 

		'id'          => 'unit_select', 

		'label'       => __( 'Unit ', 'woocommerce' ), 

		'desc_tip'    => true,

		// 'wrapper_class' => 'form-row',

		'description' => __( 'Choose a Unit.', 'woocommerce' ),

		'value'       => get_post_meta($post->ID, 'unit_select', true),

		'options' => array(

			'BG' => __( 'BG', 'woocommerce' ),

			'BX' => __( 'BX', 'woocommerce' ),

			'CS' => __( 'CS', 'woocommerce' ),

			'EA' => __( 'EA', 'woocommerce' ),

			'KG' => __( 'KG', 'woocommerce' ),

			'PC' => __( 'PC', 'woocommerce' ),

			'PK' => __( 'PK', 'woocommerce' ),

			'RP' => __( 'RP', 'woocommerce' ),

			'RS' => __( 'RS', 'woocommerce' ),

			'ST' => __( 'ST', 'woocommerce' ),

			'TR' => __( 'TR', 'woocommerce' )

		  )

		)

	  );

	  

	  //  Simple product price pnit select

	  woocommerce_wp_select( 

	  array( 

		'id'          => 'price_unit_select', 

		'label'       => __( 'Price Unit ', 'woocommerce' ), 

		'desc_tip'    => true,

		// 'wrapper_class' => 'form-row',

		'description' => __( 'Choose a Price Unit.', 'woocommerce' ),

		'value'       => get_post_meta($post->ID, 'price_unit_select', true),

		'options' => array(

			'KG' => __( 'KG', 'woocommerce' ),

			'EACH' => __( 'EACH', 'woocommerce' ),

			'CASE' => __( 'CASE', 'woocommerce' ),

			'BAG' => __( 'BAG', 'woocommerce' ),

			/*'EA' => __( 'EA', 'woocommerce' ),

			'KG' => __( 'KG', 'woocommerce' ),

			'PC' => __( 'PC', 'woocommerce' ),

			'PK' => __( 'PK', 'woocommerce' ),

			'ST' => __( 'ST', 'woocommerce' ),

			'TR' => __( 'TR', 'woocommerce' )*/

		  )

		)

	  );

   



 	echo '</div>';

}

add_action( 'woocommerce_product_options_pricing', 'simple_woo_add_custom_fields' ); 



/*

 * Save custom fields to simple products

 */



function simple_woo_add_custom_fields_save( $post_id ){

    

    // Class Select

	$select_class = $_POST['class_select'];

    update_post_meta( $post_id, 'class_select', esc_attr( $select_class ) );

	

	// Unit Select

	$select_unit = $_POST['unit_select'];

	update_post_meta( $post_id, 'unit_select', esc_attr( $select_unit ) );

	

	// Price Unit Select

	$select_punit = $_POST['price_unit_select'];

	update_post_meta( $post_id, 'price_unit_select', esc_attr( $select_punit ) ); 

    

}

add_action( 'woocommerce_process_product_meta', 'simple_woo_add_custom_fields_save' );



/* Display fields on fornt end */



function single_display_woo_custom_fields() {

	global $post;

    $product_id = $post->ID;

    $product = wc_get_product( $product_id );

    if( $product->is_type( 'simple' ) ) {

	$class_select_field = get_post_meta( $post->ID, 'class_select', true );

    $unit_select_field = get_post_meta( $post->ID, 'unit_select', true );

	$punit_select_field = get_post_meta( $post->ID, 'price_unit_select', true );

	$pcode = get_post_meta( $post->ID, '_sku', true );

if ( !empty( $class_select_field ) || !empty( $unit_select_field ) || !empty( $punit_select_field )) {    

  echo '<div class="woocommerce-variation single_variation">';  

	if ( !empty( $pcode ) ) {

		echo '<div class="woocommerce-variation-custom-wight"><span>Product Code:</span> ' . $pcode . '</div>';

	}	

	if ( !empty( $class_select_field ) ) {

			echo '<div class="woocommerce-variation-custom-class-select"><span>Class:</span> ' . $class_select_field . '</div>';

	}

    /*if ( !empty( $unit_select_field ) ) {

		echo '<div class="woocommerce-variation-custom-unit-select"><span>Unit:</span> ' . $unit_select_field . '</div>';

	}*/

    if ( !empty( $punit_select_field ) ) {

		echo '<div class="woocommerce-variation-custom-wight"><span>Price Unit:</span> ' . $punit_select_field . '</div>';

	}

	

    echo '</div>';

}

}

}

add_action( 'woocommerce_before_add_to_cart_button', 'single_display_woo_custom_fields', 15 );



/* Show Empty Categories and Subcategories */

add_filter( 'woocommerce_product_subcategories_hide_empty', '__return_false' );

/* End of Show Empty Categories and Subcategories */



/*Admin Check hierarchical Tag */

function my_woocommerce_taxonomy_args_product_tag( $array ) {

    $array['hierarchical'] = true;

    return $array;

};

add_filter( 'woocommerce_taxonomy_args_product_tag', 'my_woocommerce_taxonomy_args_product_tag', 10, 1 );



/* Include SKU In Search */



function custom_pre_get_posts_query( $q  ) {

    if( ! is_admin() && $q ->is_main_query() && $q->query_vars['post_type'] == 'product') {

			if(isset($_REQUEST['ps']) && $_REQUEST['ps'] !== "")

			{

				$post_type = array('product', 'product_variation');

				$posts1 = get_posts(array(

					'fields' => 'ids',

					'post_type' => array('product', 'product_variation'),

					's' => $_REQUEST['ps']                    

				)); 

				

				$posts2 = get_posts(array(

						'fields' => 'ids',

						'post_type' => array('product', 'product_variation'),

						'meta_query' => array(                        

						array(

							'key' => '_sku',

							'value' => $_REQUEST['ps'],

							'compare' => 'LIKE'

						)

						)

				)); 

				$unique = array_unique( array_merge( $posts1, $posts2 ) );   

				if(!empty($unique)){    

				$posts = get_posts(array(

					'post_type' => array('product', 'product_variation'),

					'post__in' => $unique,

					'post_status' => 'publish',

					'posts_per_page' => -1

				));    

				foreach ($posts as $post) {

					$wp_posts[] = $post->ID;                  

				}  

					$q->set('post__in', $wp_posts );    

				}

				else

				{

					$q->set( 's', $_REQUEST['ps'] );

				}



				$q->set('post_type', $post_type );

			

			} 



			if(isset($_REQUEST['onsale']) && $_REQUEST['onsale'] == "true")

			{

			$post_type = array('product', 'product_variation');	



			$posts2 = get_posts(array(

				'fields' => 'ids',

					'post_type' => 'product',

					'posts_per_page' => -1,

					'tax_query' => array(

						array(

							'taxonomy' => 'product_type',

							'field'    => 'slug',

							'terms'    => 'simple'

							)

						),

					'meta_query' => array(                        

					array(

						'key' => 'is_add_to_cart',

						'value' => 'yes',

						'compare' => 'NOT LIKE'

					)

					)

					

			));  

			$posts3 = get_posts(array(

				'post_type' => 'product_variation',

				'posts_per_page' => -1,

				'meta_query' => array(                        

				array(

					'key' => 'is_add_to_cart_variation',

					'value' => 'yes',

					'compare' => 'NOT LIKE'

				)

				)

				

			));   

			foreach ($posts3 as $post3) {

				$wp_posts3[] = $post3->post_parent;                  

			}  

			$varposts = array_unique($wp_posts3);

			$uniqueavailable = array_merge( $posts2, $varposts ); 

			$q->set('post__in', $uniqueavailable );

			$q->set('post_type', $post_type );

			}







			if(isset($_REQUEST['sortcustomname']) && $_REQUEST['sortcustomname'] == "sortingasc")

			{

			$post_type = array('product', 'product_variation');	

			$q->set( 'orderby', 'meta_value' );

			$q->set( 'meta_key', 'sorting_name' );

			$q->set( 'order', 'ASC' );

			$q->set('post_type', $post_type );

			}



			if(isset($_REQUEST['sortcustomname']) && $_REQUEST['sortcustomname'] == "sortingdesc")

			{

			$post_type = array('product', 'product_variation');	

			$q->set( 'orderby', 'meta_value' );

			$q->set( 'meta_key', 'sorting_name' );

			$q->set( 'order', 'DESC' );

			$q->set('post_type', $post_type );

			}



			if(isset($_REQUEST['sortcustomname']) && $_REQUEST['sortcustomname'] == "sortingdefaultasc")

			{

			$post_type = array('product', 'product_variation');	

			$q->set( 'orderby', 'title' );

			$q->set( 'order', 'ASC' );

			$q->set('post_type', $post_type );

			}



			if(isset($_REQUEST['sortcustomname']) && $_REQUEST['sortcustomname'] == "sortingdefaultdesc")

			{

			$post_type = array('product', 'product_variation');	

			$q->set( 'orderby', 'title' );

			$q->set( 'order', 'DESC' );

			$q->set('post_type', $post_type );

			}





	}

}

add_action( 'woocommerce_product_query' , 'custom_pre_get_posts_query' );



/* End of Include SKU In Search */



add_action('template_redirect', 'remove_shop_breadcrumbs' );

function remove_shop_breadcrumbs(){

	if (is_shop() && (isset($_REQUEST['product_tag']) || isset($_REQUEST['product_cat']) || isset($_REQUEST['ps']) ))

	{

		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

		add_action( 'woocommerce_before_main_content', 'custom_woocommerce_breadcrumb', 5, 0);

		function custom_woocommerce_breadcrumb()

		{

		?>

		<nav class="woocommerce-breadcrumb"><a href="<?php echo get_site_url(); ?>">Home</a><i></i><a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>">Shop</a>

		<?php 

		if(isset($_REQUEST['ps']) || $_REQUEST['product_tag'])

		{

		echo '<i></i> ';

		}

		if(isset($_REQUEST['product_tag']) && $_REQUEST['product_tag'] !== "")

		{

				$current_taxonomy  = $_REQUEST['product_tag'];

				$termname = array();

				foreach($current_taxonomy as $termslug){

					$term = get_term_by('slug', $termslug, 'product_tag'); 

					$termname[] = $term->name;

				}

				$current_term = implode(', ', $termname);

				echo ' "Product Tagged: ' . $current_term . '" ';

		}

		if(isset($_REQUEST['product_cat']) && $_REQUEST['product_cat'] !== "")

		{

				$current_taxonomy1  = $_REQUEST['product_cat'];

				$term1 = get_term_by('slug', $current_taxonomy1, 'product_cat'); 

				echo ' "Product Categorized: ' . $term1->name . '" '; 

		}

		if(isset($_REQUEST['ps']) && $_REQUEST['ps'] !== "")

		{

		echo ' "Search Result For: ' . $_REQUEST['ps'] . '" ';

		}		

		?>

		</nav>

		<?php 

		}

	}	



	if ((is_product_tag() || is_product_category()) && !is_shop())

	{

	if(!isset($_REQUEST['product_tag']) || !isset($_REQUEST['product_cat']))

	{	

		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

		add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 5, 0);

	}

	} 	



	if(is_singular()){

		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

		add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 35, 0); 

	}

}





add_action( 'woocommerce_shop_loop_item_title', 'aq_display_brand_before_title' );

function aq_display_brand_before_title(){

  global $product;

  $product_id = $product->get_id();

  $attributecount = count($product->get_attributes());

  if ( get_post_type( $product_id ) == 'product_variation' && $attributecount > 1 ) 

  {

	echo '<h2 class="woocommerce-loop-product__title">' . $product->attribute_summary . '</h2>';

  }

}





add_action( 'woocommerce_before_checkout_form', 'print_donation_notice', 10 );

function print_donation_notice() {

    wc_print_notice( sprintf(

        __("Delivery is available within Saskatoon or Regina. Please fill out the Billing details section to see your store pickup or delivery options.", "woocommerce")

    ), 'success' );

}



/*Product single page remove product variation*/

add_filter('woocommerce_get_price_html', 'lw_hide_variation_price', 10, 2);

function lw_hide_variation_price( $v_price, $v_product ) {

$v_product_types = array( 'variable', 'simple');

if(is_shop() || is_product_category())

{

if ( in_array ( $v_product->get_type(), $v_product_types )) {

return '';

}

}



if(is_product())

{

if ( in_array ( $v_product->get_type(), array( 'variable') )) {

return '';

}

}



// return regular price

return $v_price;

}





/* replacing add to cart button and quantities by a custom text for variation product */ 

add_action( 'woocommerce_before_add_to_cart_quantity', 'bbloomer_display_dropdown_variation_add_cart' );

function bbloomer_display_dropdown_variation_add_cart() {

    

   global $product;

    

   if ( $product->is_type('variable') ) {

      ?>

      <script>

			$( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {

			var variation_type = variation.variation_custom_is_add_to_cart_variation;

			if(variation_type == 'yes')

            {

                $(".single_variation_wrap .woocommerce-variation-add-to-cart").hide();

                $(".visit_store_text").hide();

                $('<div class="visit_store_text">This item is available in store only</div>').insertAfter('.single_variation_wrap');                

            }

            else

            {

                $(".single_variation_wrap .woocommerce-variation-add-to-cart").show();

                $(".visit_store_text").hide();

            } 

          });

      </script>

      <?php     

   }

    

}

/* end replacing add to cart button and quantities by a custom text for variation product */ 



/* start code remove price on search page, product-category page without 'All product categroy'*/

add_filter( 'woocommerce_variable_sale_price_html', 'businessbloomer_remove_prices', 10, 2 );

add_filter( 'woocommerce_variable_price_html', 'businessbloomer_remove_prices', 10, 2 );

add_filter( 'woocommerce_get_price_html', 'businessbloomer_remove_prices', 10, 2 );

 

function businessbloomer_remove_prices( $price, $product ) {

    

if ( ! is_admin() && (isset($_GET['ps']) || is_product_category())) {

    if( is_product_category('all-products') ) {        

	   $price = $price;

        } else {

        $price = '';

        }   



}

    return $price;

}

/* start code remove price on search page, product-category page without 'All product categroy'*/



add_action( 'woocommerce_after_add_to_cart_quantity', 'misha_before_add_to_cart_btn' );

 

function misha_before_add_to_cart_btn(){

    global $product;

    //$product->id;

    //echo '<pre>';print_r($product);

    if ( $product->is_type('simple') ) {

        

        $data = get_post_meta( $product->get_id(), 'price_unit_select', true );

        echo '<div class="price_unit_txt">'.$data.'</div>';

    }

    

   if ( $product->is_type('variable') ) {

      ?>

      <script>

      $( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {   

	    var variation_select = variation.variation_custom_price_unit_select;

        if(variation_select !='')

         {

           $(".price_unit_txt").html(variation_select);

         }

      });      

      </script>

      <?php  

       echo '<div class="price_unit_txt"></div>';

   }

}



/* new added */

add_filter( 'woocommerce_update_order_review_fragments', 'my_custom_shipping_table_update');

function my_custom_shipping_table_update( $fragments ) {

    ob_start();

    ?>

    <table class="my-custom-shipping-table">

        <tbody>

        <?php wc_cart_totals_shipping_html(); ?>

        </tbody>

    </table>

    <?php

    $woocommerce_shipping_methods = ob_get_clean();

    $fragments['.my-custom-shipping-table'] = $woocommerce_shipping_methods;

    return $fragments;

}



/* Remove password strength check. */

function iconic_remove_password_strength() {

   wp_dequeue_script( 'wc-password-strength-meter' );

}

add_action( 'wp_print_scripts', 'iconic_remove_password_strength', 10 );

/* End of Remove password strength check. */



/* Sorting by sorting name field */

add_filter('woocommerce_get_catalog_ordering_args', 'am_woocommerce_catalog_orderby');

function am_woocommerce_catalog_orderby( $args ) {

    $args['meta_key'] = 'sorting_name';

    $args['orderby'] = 'meta_value';

    $args['order'] = 'ASC'; 

    return $args;

}

/* End of Sorting by sorting name field */



/*

add_action('save_post','save_post_callback');

function save_post_callback($post_id){

    global $post; 

    if ($post->post_type != 'MY_CUSTOM_POST_TYPE_NAME'){

        return;

    }

    //if you get here then it's your post type so do your thing....

}

*/



function get_video_info_from_vimeo ($post_id) {

	$post_type = get_post_type($post_id);

	if ($post_type != 'product') {

	return;

	}

	remove_action('acf/save_post', 'get_video_info_from_vimeo');

	$sortingname = get_field('field_5e53a37dfa0f6', $post_id);

	if(empty($sortingname)) {

	$posttitle = get_the_title($post_id);

	update_field( 'field_5e53a37dfa0f6', $posttitle, $post_id );

	}

	add_action('acf/save_post', 'get_video_info_from_vimeo');

	}

	add_action( 'acf/save_post', 'get_video_info_from_vimeo' );


/* Disable Cart */

//add_filter( 'woocommerce_is_purchasable', '__return_false');

/*

add_action( 'woocommerce_single_product_summary', 'hide_add_to_cart_button_variable_product', 1, 0 );

function hide_add_to_cart_button_variable_product() {

    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );

}

*/

/* End of Disable Cart */

