<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); 
$type = 'ribbon_' . $r . '_template';
?>
<div class="psfw-ribbon-<?php echo $r ?>-wrap psfw-ribbon psfw-ribbon-<?php echo $psfw_option[ $type ]; ?>">
    <?php
    if ( isset( $psfw_option[ 'ribbon_type_' . $r . '' ] ) && $psfw_option[ 'ribbon_type_' . $r . '' ] == 'text' ) {
        ?>
        <span>
            <?php echo esc_attr( $psfw_option[ 'ribbon_' . $r . '_text' ] ); ?>
        </span>
        <?php
    }else {
        if ( ! empty( $psfw_option[ 'ribbon_' . $r . '_icon' ] ) ) {
            $v = explode( '|', $psfw_option[ 'ribbon_' . $r . '_icon' ] );
            $font = $v[ 0 ] . ' ' . $v[ 1 ];
        } else {
            $font = 'fa fa-calendar';
        }
        ?>
        <span>
            <i class="<?php echo esc_attr( $font ); ?>" aria-hidden="true"></i>
        </span>
        <?php
    }
    ?>
</div>