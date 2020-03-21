<?php /*
* Template name: Product Search Page
*/ get_header();?>
<section class="custom_billboard zsfsdaf" style="background-image: url(http://pmdev.wowfactormedia.ca/wp-content/uploads/2019/09/deli-1.jpg);">
	<div class="default_container">
		<div class="introtext">
			<h1>Deli</h1>
		</div>
		<div class="banner_search">
		<form role="search" method="get" class="search-form" action="">
			<select onchange="window.document.location.href=this.options[this.selectedIndex].value;">
				  <option value="">Product Categories</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/test-category-2/">Test Category 2</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/test-categroy/">Test Categroy</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/all-products/">All Products</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/the-butcher-shop/">The Butcher Shop</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/prairie-meats-packs/">Prairie Meats Packs</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/meals-in-minutes/">Meals In Minutes</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/prairie-meats-pantry/">Prairie Meats Pantry</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/soupssidessalads/">Soups, Sides, &amp; Salads</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/appetizers/">Appetizers</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/deli/" selected="">Deli</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/custom-processing/">Custom Processing</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-category/catering/">Bistro &amp; Catering</option>
			   			</select>
			<select onchange="window.document.location.href=this.options[this.selectedIndex].value;">
				  <option value="">Search By Tags</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-tag/feature/">Featured</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-tag/gluten-free/">Gluten Free</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-tag/vegetarian/">Vegetarian</option>
			   				   <option value="http://pmdev.wowfactormedia.ca/product-tag/wow-sale/">WOW! Sale</option>
			   			</select>
			<label>
				<span class="screen-reader-text">Search for:</span>
				<input type="search" class="search-field" placeholder="Search..." value="" name="s" title="Search for:">
			</label>
			<input type="submit" class="search-submit" value="Search">
		</form>
		</div>
	</div>
</section>
<nav class="woocommerce-breadcrumb"><a href="http://pmdev.wowfactormedia.ca">Home</a><i></i>Deli</nav>

<div id="main-content">
			<div class="container">
				<div id="content-area" class="clearfix">
					<div id="left-area">
                        <?php 

                        
           $args = array(
                                'post_type' => 'product',
                                'posts_per_page' => -1,
                                'post_status'	 => 'publish'
                                
                            );                
        $wp_query = new WP_Query( $args );                   
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		//while ( have_posts() ) {
			//the_post();
         while ($wp_query->have_posts()) { $wp_query->the_post();  

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' ); ?>
                      
</div> <!-- #left-area -->
                    
 <?php
$term = get_queried_object();
$term_name = $term->name;?>
<div id="sidebar" class="product_sidebar">
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
if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	
	<div id="" class="widget_product_categories widget_price_filter">
		<?php dynamic_sidebar('blog-section'); ?>
	</div>
	 
<?php
endif;?>
</div> <!-- end #sidebar -->                   


				</div> <!-- #content-area -->
			</div> <!-- .container -->
		</div>

<?php get_footer();?>