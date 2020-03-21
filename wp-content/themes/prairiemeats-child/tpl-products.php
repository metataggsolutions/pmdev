<?php /*
* Template name: Products Page
*/ get_header();?>
<?php
$tag = $_GET['product_tag'];
?>
<div id="et-main-area">
	<div id="main-content">
		<?php $banner_image = get_field('product_banner_image');?>
		<section class="custom_billboard" <?php if($banner_image['url']){?>style="background-image: url(<?php echo $banner_image['url'];?>);" <?php } ?>>
			<div class="default_container">
				<div class="introtext">
					<?php if(get_field('product_banner_text')){?><h1><?php the_field('product_banner_text');?></h1> <?php } ?>
				</div>
                <div class="banner_search">
				<?php // echo do_shortcode('[woof]'); ?>
					<form role="search" method="get" class="search-form" action="<?php echo get_site_url(); ?>/shop/">
						<select name="product_cat">
							<option value=""><?php echo esc_attr_e( 'Product Categories', 'textdomain' ); ?></option>
							<?php $custom_terms1 = get_terms( array(
								'taxonomy' => 'product_cat',
								'hide_empty' => false
							) ); 
							if ( !empty($custom_terms1) ) :?>
								<?php foreach($custom_terms1 as $custom_term1) {
									if( $custom_term1->parent == 0 ) { ?> 
										
										<option value="<?php echo $custom_term1->slug; ?>"><?php echo $custom_term1->name; ?></option>
									
										<?php foreach( $custom_terms1 as $subcategory ) {

											if($subcategory->parent == $custom_term1->term_id) { ?>
											
											<option value="<?php echo $subcategory->slug; ?>"><?php echo "&nbsp; - ".$subcategory->name; ?></option>
									 	
									<?php
								foreach( $custom_terms1 as $subcategory2 ) {
									if($subcategory2->parent == $subcategory->term_id) { ?>
										<option value="<?php echo $subcategory2->slug; ?>"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp; -- ".$subcategory2->name; ?></option>
								 <?php } }
									} 
									} ?>
								<?php } }?> 
								
							<?php endif; ?>
						</select>
						
						<div id="multi-select-plugin" aria-labeledby="multi-select-plugin-label">
							<span class="toggle">
								<label><?php echo esc_attr_e( 'Search By Tags', 'textdomain' ); ?></label>
							</span>
								<ul>
								<?php
									$args = array(
									'taxonomy' => 'product_tag', 
									'orderby' => 'ID',
									'order'=> 'ASC',
									'hide_empty' => 0,
									);
									$categories = get_categories($args);
									if(!empty($categories)) {
									foreach ( $categories as $category ) {	
										$term_link = get_category_link($category->term_id );
										$term_link = esc_url( $term_link );
										$tag_name = $category->cat_name;
										?>
										<li>
											<label>
												<input type="checkbox" name="product_tag[]" value="<?php echo $category->slug;?>" <?php if(in_array($category->slug, $tag)){ echo "checked";}?>/>
												<?php echo $tag_name;?>
											</label>
										</li>
									<?php } } ?>
								</ul>
						</div>

						<label class="search_box">
							<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
							<input type="search" class="search-field" placeholder="Search..." value="<?php echo esc_attr( get_search_query() ); ?>" name="ps" title="<?php echo esc_attr_x( 'Search for:', 'label' ); ?>" />
							<!--<input type="hidden" name="text">-->
						</label>
						
						<input type="submit" class="search-submit" value="FILTER" /> 

					</form>
				</div>
                
                
			</div>
		</section>
		<section class="default_section">
			<div class="default_container fluid">
				<div class="section_title">
					<?php if(get_field('product_category_title')){?><h2><?php the_field('product_category_title');?></h2> <?php } ?>
				</div>
				<ul class="product_cl default_row">
					<?php
					$terms = get_terms( array(
						'taxonomy' => 'product_cat',
						'hide_empty' => false,
					) );
					foreach ( $terms as $term ) { 
					if($term->parent==0) :
					$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true ); 
					$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true);
					
					$image = wp_get_attachment_url( $thumbnail_id ); 
					$cat_link    = get_term_link( $term->slug, 'product_cat' );?>
					<li class="col_5">
						<div class="pcl_holder">
						   <div class="pcl_img">
						   <a href="<?php echo $cat_link;?>">
						   <?php if(!empty($image)) { ?>
						   <img src="<?php echo $image;?>" alt="<?php if($image_alt){ echo $image_alt;} else{ the_title();} ?>">
						   <?php } ?>
						   </a>
						   </div>
						   <h3 class="pcl_title"><a href="<?php echo $cat_link;?>"><?php echo $term->name;?></a></h3>
					   </div>
					</li>
					<?php endif;
					} ?>
				</ul>
			</div>
		</section>
		<section class="default_section pro_tag">
			<div class="default_container fluid">
				<ul class="pclt default_row">
					<?php $i=0;
					$select_term = get_field('select_product_tag');
					foreach($select_term as $row){
						if($i==3){ break;}
						$tag = get_term_by('id', $row, 'product_tag');
						$termid = $tag->term_id;				
						$thumbnail = get_field('product_tag_image', $tag->taxonomy . '_' . $termid);
						$cat_link    = get_term_link( $tag->slug, 'product_tag' );?>
					<li class="col_3">
					   <div class="pclt_holder">
						   <div class="pclt_img"><a href="<?php echo $cat_link;?>"><img src="<?php echo $thumbnail['url'];?>" alt="<?php echo $thumbnail['alt'];?>"></a></div>
						   <h3 class="pclt_title"><a href="<?php echo $cat_link;?>"><?php echo $tag->name?></a></h3>
					   </div>
					</li>	
						
					<?php $i++; 
					}?>
				</ul>
			</div>
		</section>
		<!--<section class="default_section pro_tag">
			<div class="default_container fluid">
				<ul class="pclt default_row">
				<?php $terms =get_terms('product_tag');
					$i=0;
					foreach ( $terms as $term ) { 
					$termid = $select_term[$i];
					$thumbnail = get_field('product_tag_image', $term->taxonomy . '_' . $termid);
					//echo "<pre>";print_r($tag);
					$cat_link    = get_term_link( $term->slug, 'product_tag' );
					if($term->term_id = $select_term[$i]){
					if($i==3){ break;}?>
					<li class="col_3">
					   <div class="pclt_holder">
						   <div class="pclt_img"><a href="<?php echo $cat_link;?>"><img src="<?php echo $thumbnail['url'];;?>" alt="<?php echo $thumbnail['alt'];?>"></a></div>
						   <h3 class="pclt_title"><a href="<?php echo $cat_link;?>"><?php echo $term->name?></a></h3>
					   </div>
					</li>
					<?php } 
					$i++; } ?>
				</ul>
			</div>
		</section>-->
		<?php $sales_image = get_field('sale_banner_background_image');?>
		<section class="default_section sale_banner">
			<div class="default_container fluid"> 
                    <div class="sale_banner_img" <?php if($sales_image['url']){?>style="background-image: url(<?php echo $sales_image['url'];?>);" <?php } ?>>
                       <div class="sale_banner_copy">
							<?php the_field('sale_banner_content');
							$sale_button = get_field('sale_banner_button');
							if($sale_button['title']){
								$link_target = $sale_button['target'] ? $sale_button['target'] : '_self';?>
							<a class="flyer_btn" href="<?php echo esc_url($sale_button['url']); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($sale_button['title']); ?></a>
							<?php } ?>
                        </div>
                	</div>
			</div>
		</section>
	</div>
</div>
<?php get_footer();?>