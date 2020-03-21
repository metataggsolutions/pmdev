<?php
/**
 * The front page template file
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 */
get_header(); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/owl.carousel.css">
<div id="main-content">
<!--Billboard-->
<?php if(have_rows('billboard_slider_section')):?>
<section class="homeslider">
	<div id="home_slider" class="owl-carousel owl-theme">
		<?php $i=1;
		while(have_rows('billboard_slider_section')): the_row();
		$slider_background_image = get_sub_field('slider_background_image');
		$image = $slider_background_image['url'];
		$is_wowproduct = get_sub_field('is_wow_product');
        $show_hide_slide = get_sub_field('show_hide_slide');
        
		
		$is_background_overlay = get_sub_field('is_slider_background_overlay');
        //echo 'Option:'.$show_hide_slide;
        if($show_hide_slide == 0){
		?>
		<?php if($is_wowproduct ==0){?>
		<div class="item <?php if($is_background_overlay == 1){ echo "bgdark_overlay"; }?>" <?php if($image){?> style="background-image: url(<?php echo $image; ?>)" <?php } ?>>
			
			<div class="slider_container">
				<?php $is_overlay = get_sub_field('is_slider_content_overlay');?>
				<div class="slider_copy <?php if($is_overlay == 1) { echo "white_overlay";} ?>">
				<?php if(get_sub_field('slider_title')){?><h1>
				<?php $title = get_sub_field('slider_title');
				echo substr($title, 3); 
				?></h1> <?php } ?>
				<?php the_sub_field('slider_content');?>
				<?php 
                $show_hide_sliderbtn1 = get_sub_field('show_hide_slider_button1');
				$show_hide_sliderbtn2 = get_sub_field('show_hide_slider_button2');
                $slider_button1 = get_sub_field('slider_button1');
				$slider_button2 = get_sub_field('slider_button2'); ?>
				
				<?php
                if($show_hide_sliderbtn1 == 0){                     
                    if(!empty($slider_button1['title'])){
					$slider_button1_target = $slider_button1['target'] ? $slider_button1['target'] : '_self';?>
				<a class="pmbtn yellow" href="<?php echo esc_url($slider_button1['url']); ?>" target="<?php echo esc_attr($slider_button1_target); ?>"><?php echo esc_html($slider_button1['title']); ?></a>
				<?php } } 
                  if($show_hide_sliderbtn2 == 0){                   
                    if(!empty($slider_button2['title'])){
					$slider_button2_target = $slider_button2['target'] ? $slider_button2['target'] : '_self';?>
				<a class="pmbtn" href="<?php echo esc_url($slider_button2['url']); ?>" target="<?php echo esc_attr($slider_button2_target); ?>"><?php echo esc_html($slider_button2['title']); ?></a>
				<?php } } ?>
				</div>
			</div> 
		</div>
		<?php } else{ $post_object = get_sub_field('select_wow_product');
		if(!empty($post_object)) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_object->ID ), 'single-post-thumbnail' );
		?>
		<div class="item" <?php if($image[0]){?> style="background-image: url(<?php echo $image[0]; ?>)" <?php } ?>>
			
			<div class="slider_container">
				<div class="product_slider_copy">
				<div class="slider_wow_tag"><span>WOW! SALE</span></div>
				<h1><?php echo $post_object->post_title;?></h1>
				<p><?php echo $post_object->post_excerpt;?></p>
				<?php $product = wc_get_product( $post_object->ID ); 
				if(!empty($product)) {
				if($product->is_type('variable')){
					// Regular price min and max
					$min_regular_price = $product->get_variation_regular_price( 'min' );
					$max_regular_price = $product->get_variation_regular_price( 'max' );

					// Sale price min and max
					if( $product->is_on_sale() ) {
					$min_sale_price = $product->get_variation_sale_price( 'min' );
					$max_sale_price = $product->get_variation_sale_price( 'max' );
					}

					// The active price min and max
					$min_price = $product->get_variation_price( 'min' );
					$max_price = $product->get_variation_price( 'max' );
					
					/*
					foreach($product->get_available_variations() as $variation ){
						$variation_id = $variation['variation_id'];
						
						
						$attributes = array();
						foreach( $variation['attributes'] as $key => $value ){
							$taxonomy = str_replace('attribute_', '', $key );
							$taxonomy_label = get_taxonomy( $taxonomy )->labels->singular_name;
							$term_name = get_term_by( 'slug', $value, $taxonomy )->name;
							$attributes[] = $taxonomy_label.': '.$term_name;
						}
						$active_price = floatval($variation['display_price']);
						$regular_price = floatval($variation['display_regular_price']); 
						if( $active_price != $regular_price ){
							$sale_price = $active_price; 
						}
						$pro_price[] = $variation['price_html'];
					}
					*/
				?>
				<div class="slide_price">
				<?php if(!empty($min_sale_price)) { 
					echo "FROM ". woocommerce_price($min_sale_price);?>/ <?php echo "REG. ". woocommerce_price($min_regular_price);
				}
				else
				{
					echo "FROM ". woocommerce_price($min_regular_price) ;
				}
				?>
				</div>
				<?php $url = get_permalink( $post_object->ID ) ;?>
				<a class="pmbtn yellow" href="<?php echo $url;?>" target="">View Product</a>
				<?php
				}
				else
				{

					if( $product->is_on_sale() ) {
						$sale_price=  $product->get_sale_price();
					}
					$reg_price=  $product->get_regular_price();
					?>

				<div class="slide_price">
				
				<?php if(!empty($sale_price)) { 
					echo "FROM ". woocommerce_price($sale_price);?>/ <?php echo "REG. ". woocommerce_price($reg_price);
				}
				else
				{
					echo "FROM ". woocommerce_price($reg_price) ;
				}
					?></div>
				<?php $url = get_permalink( $post_object->ID ) ;?>
				<a class="pmbtn yellow" href="<?php echo $url;?>" target="">View Product</a>
					<?php

				}
				}
				
				?>
				
				</div>
			</div> 
		</div>
		<?php } } ?>
        <?php } /* close for show/hide slide */ ?>
		<?php endwhile; ?>
	</div>
</section>
<?php endif;?>
<!--/Billboard-->

<?php
while ( have_posts() ) : the_post();
	the_content();
endwhile; // End of the loop. ?>    
	

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->

<!-- Include all compiled plugins (below), or include individual files as needed -->

	<script> 
	 jQuery(document).ready(function($) {
			$('#home_slider').owlCarousel({
                 items:1,
                 loop:true,
                 nav:true,
                 dots:false,
                 autoplay: false,
                 autoHeight: true,
                 animateIn:'fadeIn',
                 animateOut:'fadeOut',
                 mouseDrag: false,
                 touchDrag: false,
                 autoplayHoverPause:true,
                responsive:{
                    0:{items:1},
                    768:{items:1},
                    992:{items:1},
                }
             }); 
	}); 
    </script>
</div>	
<?php get_footer();
 