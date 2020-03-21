<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); 
$type = 'individual_ribbon_' . $individual_ribbon . '_template';
?>
<div class="psfw-ribbon-<?php echo $individual_ribbon; ?>-wrap psfw-ribbon psfw-ribbon-<?php echo $psfw_advance_option[ $type ]; ?>">
    <?php
    if ( isset( $psfw_advance_option[ 'individual_ribbon_type_' . $individual_ribbon . '' ] ) && $psfw_advance_option[ 'individual_ribbon_type_' . $individual_ribbon . '' ] == 'text' ) {
        ?>
        <span>
            <?php
            echo esc_attr( $psfw_advance_option[ 'individual_ribbon_' . $individual_ribbon . '_text' ] );
            ?>
        </span>
        <?php
    }else {
        if ( ! empty( $psfw_advance_option[ 'individual_ribbon_' . $individual_ribbon . '_icon' ] ) ) {
            $v = explode( '|', $psfw_advance_option[ 'individual_ribbon_' . $individual_ribbon . '_icon' ] );
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