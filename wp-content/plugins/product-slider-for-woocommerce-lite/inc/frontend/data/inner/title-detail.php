<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-title">
    <?php
    if ( isset( $psfw_option[ 'psfw_show_link_title' ] ) && $psfw_option[ 'psfw_show_link_title' ] == '1' ) {
        ?>
        <a href="<?php the_permalink(); ?>" target="_blank">
            <?php the_title(); ?></a>
        <?php
    } else {
        the_title();
    }
    ?>
</div>