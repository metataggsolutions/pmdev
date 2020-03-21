<?php
if ( ( is_single() || is_page() ) && in_array( get_post_meta( get_queried_object_id(), '_et_pb_page_layout', true ), array( 'et_full_width_page', 'et_no_sidebar' ) ) ) {
	return;
}?>

<?php if(!is_singular()){ 
$term = get_queried_object();
$term_name = $term->name;
$category = isset($_GET['product_cat']) ? $_GET['product_cat'] : '';
$tag = isset($_GET['product_tag']) ? $_GET['product_tag'] : '';
$ps = isset($_GET['ps']) ? $_GET['ps'] : '';
?>
<div id="sidebar" class="product_sidebar">
	<form name="shopfilter" role="search" method="get" class="search-form" action="<?php echo get_site_url(); ?>/shop/">
		<div class="product_sidebar_block">
		<input type="checkbox" id="onsale" <?php if(isset($_GET['onsale']) && $_GET['onsale']=="true") { echo 'checked="checked"'; } ?>> View only products available online 
		</div>
		<div class="product_sidebar_block">
		<label class="search_box">
			<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
			<input type="search" class="search-field" placeholder="Search..." value="<?php echo $ps; ?>" name="ps" title="<?php echo esc_attr_x( 'Search for:', 'label' ); ?>" />
		</label>
		</div>
		<div class="product_sidebar_block">
		<h4>Sort By:</h4>
		<select name="sortcustomname" id="sorting_name_sort">
		<option value="sortingasc"  <?php if(isset($_GET['sortcustomname']) && $_GET['sortcustomname']=="sortingasc") { echo 'selected="selected"'; } ?>>Product Type (A-Z)</option>
		<option value="sortingdesc" <?php if(isset($_GET['sortcustomname']) && $_GET['sortcustomname']=="sortingdesc") { echo 'selected="selected"'; } ?>>Product Type (Z-A)</option>
		<option value="sortingdefaultasc" <?php if(isset($_GET['sortcustomname']) && $_GET['sortcustomname']=="sortingdefaultasc") { echo 'selected="selected"'; } ?>> Product Name (A-Z) </option>
		<option value="sortingdefaultdesc" <?php if(isset($_GET['sortcustomname']) && $_GET['sortcustomname']=="sortingdefaultdesc") { echo 'selected="selected"'; } ?>>Product Name (Z-A)</option>
		
		</select>
		</div>
		<div class="product_sidebar_block">
		<h4>Product Categories</h4>
		<?php 
		$instance = array();
		$instance['title'] = '';
		$instance['orderby'] = 'order';
		the_widget('WC_Widget_Product_Categories', $instance);  ?> 
		<div class="product_sidebar_block">
		<h4>Product Tags</h4>
		<div id="multi-select-checkbox" style="border:2px solid #ccc; height: 200px; overflow-y: scroll;">
								<?php
									$args = array(
									'taxonomy' => 'product_tag', 
									'orderby' => 'ID',
									'order'=> 'ASC',
									'hide_empty' => 0,
									);
									$categories = get_categories($args);
									foreach ( $categories as $category ) {	
										$term_link = get_category_link($category->term_id );
										$term_link = esc_url( $term_link );
										$tag_name = $category->cat_name?>
										<label>
										<input type="checkbox" name="product_tag[]" value="<?php echo $category->slug;?>" <?php if($tag != "") { if(in_array($category->slug, $tag)){ echo "checked";} } ?>/> 
										<?php echo $tag_name;?>
										</label>
										<br/>
									<?php } ?>
						</div>
		</div>
		<?php if(is_product_category()) { ?>
			<input type="hidden" name="product_cat" value="<?php echo get_queried_object()->slug; ?>" /> 	
		<?php } ?>
		<input type="submit" class="search-submit" value="FILTER" /> 
		<input type="reset" class="search-submit" value="RESET" onclick='window.location="<?php echo get_site_url(); ?>/shop/";return false;'/> 
	</form>
    <?php //echo do_shortcode('[woof_text_filter]');
    /*
    ?>
<select name="cat" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
	  <option value=""><?php echo esc_attr_e( 'Search By Tags', 'textdomain' ); ?></option>
   <?php
   $args = array(
			  'taxonomy' => 'product_tag', 
			  'orderby' => 'ID',
			  'order'=> 'ASC',
			  'hide_empty' => 0,
		   );
   $categories = get_categories($args);
   foreach ( $categories as $category ) {
	   $term_link = get_category_link($category->term_id );
	   $term_link = esc_url( $term_link );
	   $tag_name = $category->cat_name;?>
	   <option value="<?php echo $term_link;?>" <?php if($term_name == $tag_name){ echo "selected";}?>><?php echo $category->cat_name;?></option>
   <?php }
   ?>
</select>

<?php 
*/
/*if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	
	<div id="" class="widget_product_categories widget_price_filter">
		<?php dynamic_sidebar('blog-section'); ?>
	</div>
	 
<?php
endif;*/ ?>
</div> <!-- end #sidebar -->
<?php } ?>
<?php if(is_singular('post')){?><div id="sidebar" class="blog_sidebar">
	
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
<?php }
