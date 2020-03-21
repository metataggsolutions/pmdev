<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-ribbon-wrapper">
    <?php
    $individual_ribbon_1 = isset( $psfw_advance_option[ 'individual_ribbon_1_template' ] ) ? esc_attr( $psfw_advance_option[ 'individual_ribbon_1_template' ] ) : 'default';

    if ( $individual_ribbon_1 == 'default' ) {
        if ( (isset ($psfw_option[ 'psfw_show_ribbon_1' ] ) && $psfw_option[ 'psfw_show_ribbon_1' ] == '1') ) {
            $r = 1;
            ?>
            <div class="psfw-ribbon-1-outer-wrapper psfw-clearfix">
                <?php
                if ( ( isset($psfw_option[ 'psfw_show_ribbon_1' ] ) && $psfw_option[ 'psfw_show_ribbon_1' ] == '1')){
                    include (PSFWL_PATH . '/inc/frontend/data/ribbon/ribbon-settings.php');
                }
                ?>
            </div>
            <?php
        }
    } else if ( $individual_ribbon_1 == 'template-1' || $individual_ribbon_1 == 'template-2' ) {
        $individual_ribbon = 1;
        ?>
        <div class="psfw-ribbon-1-outer-wrapper psfw-clearfix">
            <?php
                include (PSFWL_PATH . '/inc/frontend/data/ribbon/individual-ribbon.php');
            ?>
        </div><?php
    }

    $individual_ribbon_2 = isset( $psfw_advance_option[ 'individual_ribbon_2_template' ] ) ? esc_attr( $psfw_advance_option[ 'individual_ribbon_2_template' ] ) : 'default';

    if ( $individual_ribbon_2 == 'default' ) {
        if (isset( $psfw_option[ 'psfw_show_ribbon_2' ] ) && $psfw_option[ 'psfw_show_ribbon_2' ] == '1') {
            $r = 2;
            ?>
            <div class="psfw-ribbon-2-outer-wrapper psfw-clearfix">
                <?php
                if ( isset( $psfw_option[ 'psfw_show_ribbon_2' ] ) && $psfw_option[ 'psfw_show_ribbon_2' ] == '1' ) {
                    include ( PSFWL_PATH . '/inc/frontend/data/ribbon/ribbon-settings.php');
                }
                ?>
            </div>
            <?php
        }
    } else if ( $individual_ribbon_2 == 'template-1' || $individual_ribbon_2 == 'template-2'  ) {

        $individual_ribbon = 2;
        ?>
        <div class="psfw-ribbon-2-outer-wrapper psfw-clearfix">
            <?php
                include (PSFWL_PATH . '/inc/frontend/data/ribbon/individual-ribbon.php');
            ?>
        </div><?php
    }
    ?>
</div>