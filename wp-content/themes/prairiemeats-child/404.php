<?php get_header(); ?>
<?php $img = get_field('default_banner_image','option');
$image = $img['url']; ?>
<section class="custom_billboard" style="background-image: url(<?php echo $image; ?>);">
	<div class="default_container">
		<div class="introtext">
			<h1>404</h1>
			<?php echo apply_filters( 'genesis_404_entry_content', '<p>' . sprintf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <br><br><a class="pmbtn" href="%s">homepage</a>', 'genesis' ), trailingslashit( home_url() ) ) . '</p>' ); ?>
		</div>
	</div>
</section>

<?php

get_footer();
