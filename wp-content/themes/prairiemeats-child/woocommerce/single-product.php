<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
	
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?> 

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
		
		<!--<p><a href="#unit_convert">convert</a></p>
		<div id="unit_convert" class="et_pb_section popup unit_convert">
			<script type='text/javascript'>
				var gbrt=["atomic mass unit (amu)","carat (metric)","cental","centigram","dekagram","dram (dr)","grain (gr)","gram (g)","hundredweight (UK)","kilogram (kg)","microgram (µg)","milligram (mg)","newton (Earth)","ounce (oz)","pennyweight (dwt)","pound (lb)","quarter","stone","ton (UK, long)","ton (US, short)","tonne (t)","troy ounce"];
				var factors=[6.0221366516752E+26,5000,0.022046226218488,100000,100,564.38339119329,15432.358352941,1000,0.019684130552221,1,1000000000,1000000,9.80665,35.27396194958,643.01493137256,2.2046226218488,0.078736522208885,0.15747304441777,0.00098420652761106,0.0011023113109244,0.001,32.150746568628];
				</script>
				<div id="ProductList">
				    <form action="." id="cform" method="post" name="cform" role="form">
					    <table class="table table-condensed">
							<tr>
								<td style="width:48%"><span>From:</span></td>
								<td style="width:4%">&nbsp;</td>
								<td style="width:48%"><span>To:</span></td>
							</tr>
							<tr>
								<td><input class="form-control" autocomplete="off" type="text" id="T1" name="T1" value="1" onChange="convert();" onKeyUp="convert(); return true;"  /></td>
								<td class="text-center" style="vertical-align: middle;">=</td>
								<td><input class="form-control" autocomplete="off" type="text" id="T2" name="T2" value="" readonly  /></td>
							</tr>
							<tr>
								<td><small><SPAN id="N1" ></SPAN></small></td>
								<td>&nbsp;</td>
								<td><small><SPAN id="N2" ></SPAN></small></td>
							</tr>
							<tr>
								<td><select class="form-control" id="field_D1" onChange="convert_scroll();" name="D1" size="5">
								<option selected="selected" value="9">Kilogram</option>
								<option value="7">Gram</option>
								<option value="11">Milligram</option>
								<option value="15">Pound</option>
								<option value="13">Ounce</option>
								</select></td>
								<td>&nbsp;</td>
								<td><select class="form-control" id="field_D2" onChange="convert_scroll();" name="D2" size="5">
								<option selected="selected" value="9">Kilogram</option>
								<option value="7">Gram</option>
								<option value="11">Milligram</option>
								<option value="15">Pound</option>
								<option value="13">Ounce</option>							
								</select></td>
							</tr>
						</table>
						<div class='clear'></div>
					</form>
                </div>
			</div>-->
<!--<script async type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/convertjs.js"></script>
<script>
function convert_scroll(){convert();$('html, body').animate({scrollTop: ($("#topbanner").offset().top-50)}, 800);}
var module='weight-and-mass-unit-conversion';function converterclick(id1, id2){var myform = document.forms['cform'];myform.D1.selectedIndex = id1;myform.D2.selectedIndex = id2;
convert_scroll();}

function convert_scroll(){
	$( "#backbutton" ).click(function() {document.location='/';});
	convert();
	$('#converter_1_7').click(function() {converterclick(1, 7);return false;});
	$('#converter_7_1').click(function() {converterclick(7, 1);return false;});
	$('#converter_7_6').click(function() {converterclick(7, 6);return false;});
	$('#converter_6_7').click(function() {converterclick(6, 7);return false;});
	$('#converter_6_12').click(function() {converterclick(6, 12);return false;});
	$('#converter_12_6').click(function() {converterclick(12, 6);return false;});
	$('#converter_15_20').click(function() {converterclick(15, 20);return false;});
	$('#converter_20_15').click(function() {converterclick(20, 15);return false;});
	; convert();
}
</script>-->
		
		
	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );

	?>


	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

