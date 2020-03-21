<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div class="psfw-general-outer-wrap">
    <div class="psfw-ribbon-description">
        <p class="description"><?php _e( 'Note: Only the carousel layout takes the ribbon.', PSFWL_TD ) ?></p>
    </div>
    <?php for ( $r = 1; $r <= 1; $r ++ ) { ?>
        <div class="psfw-ribbon-outer-wrap">
            <div class="psfw-post-option-wrap psfw-extra-wrap">
                <div class="psfw-down-wrap">
                    <label for="psfw-ribbon-view-check" class="psfw-ribbon-view">
                        <?php _e( 'Enable Ribbon', PSFWL_TD ); ?>
                    </label>
                    <img src="<?php echo PSFWL_IMG_DIR . '/demo/banner/' . $r . '.png' ?>">
                </div>
                <div class="psfw-post-field-wrap">
                    <label class="psfw-switch">
                        <input type="checkbox" class="psfw-show-ribbon psfw-checkbox" value="<?php
                        if ( isset( $psfw_option[ 'psfw_show_ribbon_' . $r ] ) ) {
                            echo esc_attr( $psfw_option[ 'psfw_show_ribbon_' . $r ] );
                        } else {
                            echo '0';
                        }
                        ?>" name="psfw_option[psfw_show_ribbon_<?php echo $r; ?>]" <?php if ( isset( $psfw_option[ 'psfw_show_ribbon_' . $r ] ) && $psfw_option[ 'psfw_show_ribbon_' . $r ] == '1' ) { ?>checked="checked"<?php } ?> />
                        <div class="psfw-slider round"></div>
                    </label>
                    <p class="description"><?php _e( 'Enable to show ribbon/banner position ' . $r, PSFWL_TD ) ?></p>
                </div>
            </div>
            <div class="psfw-ribbon-settings"
                 <?php if ( isset( $psfw_option[ 'psfw_show_ribbon_' . $r ] ) && $psfw_option[ 'psfw_show_ribbon_' . $r ] == '1' ) { ?> style="display: block;" <?php } else { ?> style="display: none;" <?php } ?> >
                <div class="psfw-post-option-wrap">
                    <label for="psfw-label" class="psfw-label">
                        <?php _e( 'Ribbon Text', PSFWL_TD ); ?>
                    </label>
                    <div class="psfw-post-field-wrap">

                        <input type="hidden" value="text" name="psfw_option[ribbon_type_<?php echo $r; ?>]"  class="psfw-ribbon-type"/>

                        <div class="psfw-ribbon-text-wrap" >
                            <input type="text" class="psfw-ribbon-<?php echo $r; ?>-text" name="psfw_option[ribbon_<?php echo $r; ?>_text]" value="<?php
                            if ( isset( $psfw_option[ 'ribbon_' . $r . '_text' ] ) ) {
                                echo esc_attr( $psfw_option[ 'ribbon_' . $r . '_text' ] );
                            }
                            ?>">
                        </div>
                    </div>
                </div>
                <div class ="psfw-post-option-wrap">
                    <label><?php _e( 'Ribbon Template', PSFWL_TD ); ?></label>
                    <div class="psfw-post-field-wrap">
                        <select name="psfw_option[ribbon_<?php echo $r; ?>_template]" class="psfw-ribbon-template">
                            <?php
                            $psfw_ribbon_names = array( 'Gemstone template', 'Plain border template' );
                            $rn = 1;
                            foreach ( $psfw_ribbon_names as $psfw_ribbon_name ) {
                                ?>
                                <option value="template-<?php echo $rn; ?>" <?php if ( ! empty( $psfw_option[ 'ribbon_' . $r . '_template' ] ) ) selected( $psfw_option[ 'ribbon_' . $r . '_template' ], 'template-' . $rn ); ?>><?php echo $psfw_ribbon_name; ?></option>
                                <?php
                                $rn ++;
                            }
                            ?>    </select>
                        <div class="psfw-ribbon-demo psfw-preview-image">
                            <?php
                            for ( $cnt = 1; $cnt <= 2; $cnt ++ ) {
                                $ribbon_template = (isset( $psfw_option[ 'ribbon_' . $r . '_template' ] ) && $psfw_option[ 'ribbon_' . $r . '_template' ] != '') ? esc_attr( $psfw_option[ 'ribbon_' . $r . '_template' ] ) : 'template-1';
                                $option_value = $ribbon_template;
                                $exploed_array = explode( '-', $option_value );
                                $cnt_num = $exploed_array[ 1 ];
                                if ( $cnt != $cnt_num ) {
                                    $style = "style='display:none;'";
                                } else {
                                    $style = '';
                                }
                                ?>
                                <div class="psfw-ribbon-common" id="psfw-ribbon-demo-<?php echo $cnt; ?>" <?php if ( isset( $style ) ) echo $style; ?>>
                                    <h4><?php _e( 'Template', PSFWL_TD ); ?> <?php echo $cnt; ?> <?php _e( 'Preview', PSFWL_TD ); ?></h4>
                                    <img src="<?php echo PSFWL_IMG_DIR . '/demo/ribbons/ribbon' . $cnt . '.png' ?>"/>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>