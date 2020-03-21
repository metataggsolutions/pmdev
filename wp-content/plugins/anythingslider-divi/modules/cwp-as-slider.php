<?php
	class CWP_ET_Builder_Module_AnythingSlider extends ET_Builder_Module {

		//CUSTOM RESPONSIVE GENERATION FUNCTION.
		static function cwp_generate_responsive_css( $values_array, $css_selector, $css_property, $extra_value = '', $function_name, $additional_css = '' ) {
			if ( ! empty( $values_array ) ) {
				foreach( $values_array as $device => $current_value ) {
					if ( '' === $current_value ) {
						continue;
					}

					$declaration = '';

					// value can be provided as a string or array in following format - array( 'property_1' => 'value_1', 'property_2' => 'property_2', ... , 'property_n' => 'value_n' )
					if ( is_array( $current_value ) && ! empty( $current_value ) ) {
						foreach( $current_value as $this_property => $this_value ) {
							if ( '' === $this_value ) {
								continue;
							}

							$declaration .= sprintf(
								'%1$s: %4$s%2$s%3$s',
								$this_property,
								esc_html( et_builder_process_range_value( $this_value ) ),
								'' !== $additional_css ? $additional_css : ';',
								'' !== $extra_value ? $extra_value : ''
							);
						}
					} else {
						$declaration = sprintf(
							'%1$s: %4$s%2$s%3$s',
							$css_property,
							esc_html( et_builder_process_range_value( $current_value ) ),
							'' !== $additional_css ? $additional_css : ';',
							'' !== $extra_value ? $extra_value : ''
						);
					}

					if ( '' === $declaration ) {
						continue;
					}

					$style = array(
						'selector'    => $css_selector,
						'declaration' => $declaration,
					);

					if ( 'desktop_only' === $device ) {
						$style['media_query'] = ET_Builder_Element::get_media_query( 'min_width_981' );
					} elseif ( 'desktop' !== $device ) {
						$current_media_query = 'tablet' === $device ? 'max_width_980' : 'max_width_767';
						$style['media_query'] = ET_Builder_Element::get_media_query( $current_media_query );
					}

					ET_Builder_Element::set_style( $function_name, $style );
				}
			}
		}

		function init() {
			$this->name            = esc_html__( 'Anything Slider', 'et_builder' );
			$this->slug            = 'cwp_et_pb_layout_slider_standard';
			$this->child_slug      = 'cwp_et_pb_layout_slide_item_standard';
			$this->child_item_text = esc_html__( 'Slide', 'et_builder' );
			
			$this->whitelisted_fields = array(
				'num_items',
				'show_arrows',
				'show_pagination',
				'auto',
				'auto_speed',
				'auto_ignore_hover',
				'admin_label',
				'module_id',
				'module_class',
				'row_pading_margin',
				'section_pading_margin',
				'arrows_position',
				'dots_position',
				'loop_slider',
				'next_label',
				'prev_label',
				'disable_np_label',
				'num_items_tablet',
				'num_items_phone',
				'num_items_last_edited',
				'slides_margin',
				'slides_margin_tablet',
				'slides_margin_phone',
				'slides_margin_last_edited',
				'dots_size',
				'dots_size_tablet',
				'dots_size_phone',
				'dots_size_last_edited',
				'auto_height',
				'dots_bg',
				'dots_bg_active',
				'controls_on_hover',
				'animation_in',
				'animation_out',
				'touch_drag',
				'show_navmenu',
				'navmenu_position',
				'navmenu_bg',
				'navmenu_bg_active',
				'use_urlhash',
				'rtl',
				'navmenu_item_margin',
				'navmenu_text_active',
				'hide_navmenu_text',
				'navmenu_wrapper_bg',
				'slide_by',
				'slide_center_item',
				'slide_on_hover',
				'equal_height',
				'min_height',
				'min_height_tablet',
				'min_height_phone',
				'min_height_last_edited',
			);

			$this->fields_defaults = array(
				'show_arrows'             => array( 'on' ),
				'show_pagination'         => array( 'on' ),
				'show_navmenu'	  	 	  => array( 'off', 'add_default_setting' ),
				'slide_on_hover'	  	  => array( 'off', 'add_default_setting' ),
				'auto'                    => array( 'off' ),
				'auto_speed'              => array( '5000', 'add_default_setting' ),
				'auto_ignore_hover'       => array( 'off' ),
				'slide_center_item'       => array( 'off', 'add_default_setting' ),
				'num_items'				  => array( '1' , 'add_default_setting' ),
				'slide_by'				  => array( '1' , 'add_default_setting' ),
				'row_pading_margin'		  => array( 'off' ),
				'section_pading_margin'	  => array( 'off' ),
				'arrows_position'	  	  => array( 'sides' ),
				'dots_position'	  	 	  => array( 'bottom-center' ),
				'loop_slider'	  	 	  => array( 'off', 'add_default_setting' ),
				'auto_height'	  	 	  => array( 'off' ),
				'slides_margin'	  	 	  => array( '10', 'add_default_setting' ),
				'dots_size'	  	 	  	  => array( '7px', 'add_default_setting' ),
				'dots_bg'	  	 	  	  => array( 'rgba(255, 255, 255, 0.5)' ),
				'dots_bg_active'	  	  => array( 'rgba(255, 255, 255, 1)' ),
				'controls_on_hover'	  	  => array( 'off','add_default_setting' ),
				'animation_in'	  	 	  => array( 'fadeIn', 'add_default_setting' ),
				'animation_out'	  	 	  => array( 'fadeOut', 'add_default_setting' ),
				'touch_drag'	  	 	  => array( 'off' ),
				'use_urlhash'	  	 	  => array( 'on', 'add_default_setting' ),
				'rtl'	  	 	  		  => array( 'off' ),
				'next_label'	  	 	  => array( 'Next', 'add_default_setting' ),
				'prev_label'	  	 	  => array( 'Prev', 'add_default_setting' ),
				'disable_np_label'	  	  => array( 'off', 'add_default_setting' ),
				'navmenu_bg'	  	 	  => array( '#efefef', 'add_default_setting' ),
				'navmenu_bg_active'	  	  => array( '#dedede', 'add_default_setting' ),
				'navmenu_text_active'	  => array( '#000000', 'add_default_setting' ),
				'navmenu_position'	  	  => array( 'top-center', 'add_default_setting' ),
				'navmenu_item_margin'	  => array( '10px', 'add_default_setting' ),
				'hide_navmenu_text'	  	  => array( 'off', 'add_default_setting' ),
				'equal_height'	  	  	  => array( 'off', 'add_default_setting' ),
				'min_height'	  	  	  => array( '50px', 'add_default_setting' )

			);

			$this->options_toggles = array(
				'general' => array(
					'toggles' => array(
						'controls'         => array(
							'title'    => esc_html__( 'Controls', 'et_builder' ),
							// 'priority' => 40,
						),
						'animation'         => array(
							'title'    => esc_html__( 'Animation', 'et_builder' ),
							// 'priority' => 40,
						),
						
						'other'         => array(
							'title'    => esc_html__( 'Other Settings', 'et_builder' ),
							// 'priority' => 40,
						),
					),
				),
				'advanced' => array(
					'toggles' => array(
						'slider_height'         => array(
							'title'    => esc_html__( 'Slider Height', 'et_builder' ),
							'priority' => 10,
						),
						'navmenu'         => array(
							'title'    => esc_html__( 'Nav Menu', 'et_builder' ),
							'priority' => 10,
						),
						'dots'         => array(
							'title'    => esc_html__( 'Dot Control', 'et_builder' ),
							'priority' => 10,
						),
						'button_prev'         => array(
							'title'    => esc_html__( 'Previous Button', 'et_builder' ),
							'priority' => 20,
						),
						'button_next'         => array(
							'title'    => esc_html__( 'Next Button', 'et_builder' ),
							'priority' => 30,
						),
						'carousel'         => array(
							'title'    => esc_html__( 'Carousel Mode', 'et_builder' ),
							// 'priority' => 40,
						),
						'other'         => array(
							'title'    => esc_html__( 'Other Styling', 'et_builder' ),
							'priority' => 50,
						),
					),
				),
			);

			$this->main_css_element = '%%order_class%%.owl-carousel';
			$this->advanced_options = array(
				'custom_margin_padding' => array(
					'css' => array(
						'main'      => "{$this->main_css_element}",
						'important' => 'all',
					),
				),
				'button' => array(
					'button_prev' => array(
						'label' => esc_html__( 'Previous Button', 'et_builder' ),
						'css'      => array(
							'main' => "{$this->main_css_element} .owl-prev a.et_pb_button_prev",
						),
						'no_rel_attr' => true,
						'toggle_slug'     => 'button_prev',

					),
					'button_next' => array(
						'label' => esc_html__( 'Next Button', 'et_builder' ),
						'css'      => array(
							'main' => "{$this->main_css_element} .owl-next a.et_pb_button_next",
						),
						'no_rel_attr' => true,
						'toggle_slug'     => 'button_next',
					),
				),
				'fonts' => array(
						'navmenu'   => array(
							'label'    => esc_html__( 'Nav Menu', 'et_builder' ),
							'css'      => array(
								'main'	=> "%%order_class%%_navMenu .navMenu_item span.navMenu_label"
							),
							'font_size' => array(
								'default' => '14',
							),
							'hide_text_align' => true,
							'toggle_slug'     => 'navmenu'
						),
				),
				'background' => array(
					'css' => array(
						'main' => '%%order_class%%'
					),
					'use_background_video' => false,
				),

			);

			$this->custom_css_options = array(
				'slide_controllers_wrapper' => array(
					'label'    => esc_html__( 'Dots Controller Wrapper', 'et_builder' ),
					'selector' => '.owl-dots',
				),
				'slide_controllers' => array(
					'label'    => esc_html__( 'Dot Controllers', 'et_builder' ),
					'selector' => '.owl-dots .owl-dot',
				),
				'slide_active_controller' => array(
					'label'    => esc_html__( 'Dot Active Controller', 'et_builder' ),
					'selector' => '.owl-dots .owl-dot.active',
				),
				'navmenu_wrapper' => array(
					'label'    => esc_html__( 'Nav Menu Wrapper', 'et_builder' ),
					'selector' => '%%order_class%%_navMenu',
				),
				'navmenu_item' => array(
					'label'    => esc_html__( 'Nav Menu Item', 'et_builder' ),
					'selector' => '%%order_class%%_navMenu .navMenu_item',
				),
				'navmenu_item_active' => array(
					'label'    => esc_html__( 'Nav Menu Item', 'et_builder' ),
					'selector' => '%%order_class%%_navMenu .navMenu_item.active',
				),
				'slide_arrows' => array(
					'label'    => esc_html__( 'Next / Prev Controller Wrapper', 'et_builder' ),
					'selector' => '.owl-nav',
				),
				'slide_arrow_prev' => array(
					'label'    => esc_html__( 'Previous Control', 'et_builder' ),
					'selector' => '.owl-nav .owl-prev',
				),
				'slide_arrows_next' => array(
					'label'    => esc_html__( 'Next Control', 'et_builder' ),
					'selector' => '.owl-nav .owl-next',
				),
			);
		}
		function get_fields() {
			$animation_options = array(
				'off' => esc_html__('Off', 'et_builder') , 'bounce' => esc_html__('Bounce', 'et_builder') , 'flash' => esc_html__('Flash', 'et_builder') , 'pulse' => esc_html__('Pulse', 'et_builder') , 'rubberBand' => esc_html__('Rubberband', 'et_builder') , 'shake' => esc_html__('Shake', 'et_builder') , 'headShake' => esc_html__('Headshake', 'et_builder') , 'swing' => esc_html__('Swing', 'et_builder') , 'tada' => esc_html__('Tada', 'et_builder') , 'wobble' => esc_html__('Wobble', 'et_builder') , 'jello' => esc_html__('Jello', 'et_builder') , 'bounceIn' => esc_html__('Bouncein', 'et_builder') , 'bounceInDown' => esc_html__('Bounceindown', 'et_builder') , 'bounceInLeft' => esc_html__('Bounceinleft', 'et_builder') , 'bounceInRight' => esc_html__('Bounceinright', 'et_builder') , 'bounceInUp' => esc_html__('Bounceinup', 'et_builder') , 'bounceOut' => esc_html__('Bounceout', 'et_builder') , 'bounceOutDown' => esc_html__('Bounceoutdown', 'et_builder') , 'bounceOutLeft' => esc_html__('Bounceoutleft', 'et_builder') , 'bounceOutRight' => esc_html__('Bounceoutright', 'et_builder') , 'bounceOutUp' => esc_html__('Bounceoutup', 'et_builder') , 'fadeIn' => esc_html__('Fadein', 'et_builder') , 'fadeInDown' => esc_html__('Fadeindown', 'et_builder') , 'fadeInDownBig' => esc_html__('Fadeindownbig', 'et_builder') , 'fadeInLeft' => esc_html__('Fadeinleft', 'et_builder') , 'fadeInLeftBig' => esc_html__('Fadeinleftbig', 'et_builder') , 'fadeInRight' => esc_html__('Fadeinright', 'et_builder') , 'fadeInRightBig' => esc_html__('Fadeinrightbig', 'et_builder') , 'fadeInUp' => esc_html__('Fadeinup', 'et_builder') , 'fadeInUpBig' => esc_html__('Fadeinupbig', 'et_builder') , 'fadeOut' => esc_html__('Fadeout', 'et_builder') , 'fadeOutDown' => esc_html__('Fadeoutdown', 'et_builder') , 'fadeOutDownBig' => esc_html__('Fadeoutdownbig', 'et_builder') , 'fadeOutLeft' => esc_html__('Fadeoutleft', 'et_builder') , 'fadeOutLeftBig' => esc_html__('Fadeoutleftbig', 'et_builder') , 'fadeOutRight' => esc_html__('Fadeoutright', 'et_builder') , 'fadeOutRightBig' => esc_html__('Fadeoutrightbig', 'et_builder') , 'fadeOutUp' => esc_html__('Fadeoutup', 'et_builder') , 'fadeOutUpBig' => esc_html__('Fadeoutupbig', 'et_builder') , 'flipInX' => esc_html__('Flipinx', 'et_builder') , 'flipInY' => esc_html__('Flipiny', 'et_builder') , 'flipOutX' => esc_html__('Flipoutx', 'et_builder') , 'flipOutY' => esc_html__('Flipouty', 'et_builder') , 'lightSpeedIn' => esc_html__('Lightspeedin', 'et_builder') , 'lightSpeedOut' => esc_html__('Lightspeedout', 'et_builder') , 'rotateIn' => esc_html__('Rotatein', 'et_builder') , 'rotateInDownLeft' => esc_html__('Rotateindownleft', 'et_builder') , 'rotateInDownRight' => esc_html__('Rotateindownright', 'et_builder') , 'rotateInUpLeft' => esc_html__('Rotateinupleft', 'et_builder') , 'rotateInUpRight' => esc_html__('Rotateinupright', 'et_builder') , 'rotateOut' => esc_html__('Rotateout', 'et_builder') , 'rotateOutDownLeft' => esc_html__('Rotateoutdownleft', 'et_builder') , 'rotateOutDownRight' => esc_html__('Rotateoutdownright', 'et_builder') , 'rotateOutUpLeft' => esc_html__('Rotateoutupleft', 'et_builder') , 'rotateOutUpRight' => esc_html__('Rotateoutupright', 'et_builder') , 'hinge' => esc_html__('Hinge', 'et_builder') , 'jackInTheBox' => esc_html__('Jackinthebox', 'et_builder') , 'rollIn' => esc_html__('Rollin', 'et_builder') , 'rollOut' => esc_html__('Rollout', 'et_builder') , 'zoomIn' => esc_html__('Zoomin', 'et_builder') , 'zoomInDown' => esc_html__('Zoomindown', 'et_builder') , 'zoomInLeft' => esc_html__('Zoominleft', 'et_builder') , 'zoomInRight' => esc_html__('Zoominright', 'et_builder') , 'zoomInUp' => esc_html__('Zoominup', 'et_builder') , 'zoomOut' => esc_html__('Zoomout', 'et_builder') , 'zoomOutDown' => esc_html__('Zoomoutdown', 'et_builder') , 'zoomOutLeft' => esc_html__('Zoomoutleft', 'et_builder') , 'zoomOutRight' => esc_html__('Zoomoutright', 'et_builder') , 'zoomOutUp' => esc_html__('Zoomoutup', 'et_builder') , 'slideInDown' => esc_html__('Slideindown', 'et_builder') , 'slideInLeft' => esc_html__('Slideinleft', 'et_builder') , 'slideInRight' => esc_html__('Slideinright', 'et_builder') , 'slideInUp' => esc_html__('Slideinup', 'et_builder') , 'slideOutDown' => esc_html__('Slideoutdown', 'et_builder') , 'slideOutLeft' => esc_html__('Slideoutleft', 'et_builder') , 'slideOutRight' => esc_html__('Slideoutright', 'et_builder') , 'slideOutUp' => esc_html__('Slideoutup', 'et_builder')
			);
			$fields = array(
				/********************************
				*			CONTENT TAB			*
				********************************/

				//NEXT/PREV CONTROL
				'show_arrows'         => array(
					'label'           => esc_html__( 'Next / Previous Control', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'affects' => array(
						'arrows_position',
						'disable_np_label',
					),
					'toggle_slug'     => 'controls',
					'description'     => esc_html__( 'This setting will turn on and off the next/previous controls.', 'et_builder' ),
				),
				'disable_np_label'         => array(
					'label'           => esc_html__( ' Disable Next/Previous Labels', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'toggle_slug'     => 'controls',
					'depends_default'   => true,
					'affects' => array(
						'next_label',
						'prev_label',
					),
				),
				'next_label' => array(
					'label'             => esc_html__( 'Next Label', 'et_builder' ),
					'type'              => 'text',
					'option_category'   => 'configuration',
					'depends_show_if'   => 'off',
					'toggle_slug'     => 'controls',
					'description'       => esc_html__( "Change the default next control text", 'et_builder' ),
				),
				'prev_label' => array(
					'label'             => esc_html__( 'Previous Label', 'et_builder' ),
					'type'              => 'text',
					'option_category'   => 'configuration',
					'depends_show_if'   => 'off',
					'toggle_slug'     => 'controls',
					'description'       => esc_html__( "Change the default previous control text", 'et_builder' ),
				),
				'arrows_position'     => array(
					'label'           => esc_html__( 'Next/Prev Control Position', 'et_builder' ),
					'type'            => 'select',
					'depends_default'   => true,
					'toggle_slug'     => 'controls',
					'option_category' => 'configuration',
					'options'         => array(
						'sides'  => esc_html__( 'Both Sides', 'et_builder' ),
						'top-left' => esc_html__( 'Top Left', 'et_builder' ),
						'top-center' => esc_html__( 'Top Center', 'et_builder' ),
						'top-right' => esc_html__( 'Top Right', 'et_builder' ),
						'bottom-left' => esc_html__( 'Bottom Left', 'et_builder' ),
						'bottom-center' => esc_html__( 'Bottom Center', 'et_builder' ),
						'bottom-right' => esc_html__( 'Bottom Right', 'et_builder' ),
					),
					'description'     => esc_html__( 'Here you set the slider Next/Prev controls position where you need it to display.', 'et_builder' ),
				),

				//NAV MENU CONTROL
				'show_navmenu' => array(
					'label'             => esc_html__( 'Nav Menu', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'affects' => array(
						'navmenu_position',
						'slide_on_hover',
						'show_pagination',
						'dots_bg',
						'dots_bg_active',
						'dots_size',
						'hide_navmenu_text'
					),
					'toggle_slug'     => 'controls',
					'description'       => esc_html__( 'This setting will turn on and off the Nav Menu control. If turned on, dot controls will be turned off, both may not work together.', 'et_builder' ),
				),
				'hide_navmenu_text' => array(
					'label'             => esc_html__( 'Disable Menu Label/Text', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'depends_default'   => true,
					'toggle_slug'     => 'controls',
					'description'       => esc_html__( 'This setting will show/hide the Nav Menu Text/label.', 'et_builder' ),
				),
				'slide_on_hover' => array(
					'label'             => esc_html__( 'Slide on Hover', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'depends_default'   => true,
					'toggle_slug'     => 'controls',
					'description'       => esc_html__( 'If enabled, slides will slide on mouse hover on Nav Menu Items.', 'et_builder' ),
				),
				'navmenu_position'     => array(
					'label'           => esc_html__( 'Nav Menu Position', 'et_builder' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'top-left' => esc_html__( 'Top Left', 'et_builder' ),
						'top-center' => esc_html__( 'Top Center', 'et_builder' ),
						'top-right' => esc_html__( 'Top Right', 'et_builder' ),
						'bottom-left' => esc_html__( 'Bottom Left', 'et_builder' ),
						'bottom-center' => esc_html__( 'Bottom Center', 'et_builder' ),
						'bottom-right' => esc_html__( 'Bottom Right', 'et_builder' ),
					),
					'depends_default'   => true,
					'toggle_slug'     => 'controls',
					'description'     => esc_html__( 'Here you set the nav menu position where you need it to display.', 'et_builder' ),
				),

				//DOTS CONTROL
				'show_pagination' => array(
					'label'             => esc_html__( 'Dot Controls', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'depends_show_if'   => 'off',
					'depends_to'        => array(
						'show_navmenu'
					),
					'affects' => array(
						'dots_position',
					),
					'toggle_slug'     => 'controls',
					'description'       => esc_html__( 'This setting will turn on and off the circle buttons at the bottom of the slider.', 'et_builder' ),
				),
				'dots_position'     => array(
					'label'           => esc_html__( 'Dot Controls Position', 'et_builder' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'top-left' => esc_html__( 'Top Left', 'et_builder' ),
						'top-center' => esc_html__( 'Top Center', 'et_builder' ),
						'top-right' => esc_html__( 'Top Right', 'et_builder' ),
						'bottom-left' => esc_html__( 'Bottom Left', 'et_builder' ),
						'bottom-center' => esc_html__( 'Bottom Center', 'et_builder' ),
						'bottom-right' => esc_html__( 'Bottom Right', 'et_builder' ),
					),
					'depends_default'   => true,
					'toggle_slug'     => 'controls',
					'description'     => esc_html__( 'Here you set the slider dot controls position where you need it to display.', 'et_builder' ),
				),
				'controls_on_hover' => array(
					'label'             => esc_html__( 'Show Controls on Hover', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'et_builder' ),
						'off' => esc_html__( 'No', 'et_builder' ),
					),
					'toggle_slug'     => 'controls',
					'description'       => esc_html__( 'If turned on the next/previous and dot controls will only display on mouse hover.', 'et_builder' ),
				),

				//ANIMATION SETTINGS
				'animation_in' => array(
					'label'             => esc_html__( 'Animation In', 'et_builder' ),
					'type'              => 'select',
					'option_category'   => 'configuration',
					'options'           => $animation_options,
					'description'       => esc_html__( 'This controls the slides animation.', 'et_builder' ),
					'toggle_slug'     => 'animation',
				),
				'animation_out' => array(
					'label'             => esc_html__( 'Animation Out', 'et_builder' ),
					'type'              => 'select',
					'option_category'   => 'configuration',
					'options'           => $animation_options,
					'description'       => esc_html__( 'This controls the slides animation.', 'et_builder' ),
					'toggle_slug'     => 'animation',
				),
				'auto' => array(
					'label'           => esc_html__( 'Automatic Animation', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'Off', 'et_builder' ),
						'on'  => esc_html__( 'On', 'et_builder' ),
					),
					'affects' => array(
						'auto_speed',
						'auto_ignore_hover',
					),
					'toggle_slug'     => 'animation',
					'description'        => esc_html__( 'If you would like the slider to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
				),
				'auto_speed' => array(
					'label'             => esc_html__( 'Automatic Animation Speed (in ms)', 'et_builder' ),
					'type'              => 'range',
					'option_category'   => 'configuration',
					'depends_default'   => true,
					'unitless'        => true,
					'range_settings' => array(
						'min'  => '1000',
						'max'  => '60000',
						'step' => '1000',
					),
					'toggle_slug'     => 'animation',
					'description'       => esc_html__( "Here you can designate how fast the slider fades between each slide, if 'Automatic Animation' option is enabled above. The higher the number the longer the pause between each rotation. 1000 = 1 second", 'et_builder' ),
				),
				'auto_ignore_hover' => array(
					'label'           => esc_html__( 'Continue Automatic Slide on Hover', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'depends_default' => true,
					'options'         => array(
						'off' => esc_html__( 'Off', 'et_builder' ),
						'on'  => esc_html__( 'On', 'et_builder' ),
					),
					'toggle_slug'     => 'animation',
					'description' => esc_html__( 'Turning this on will allow automatic sliding to continue on mouse hover.', 'et_builder' ),
				),

				//OTHER SETTINGS
				'loop_slider' => array(
					'label'             => esc_html__( 'Loop Slider', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'et_builder' ),
						'off' => esc_html__( 'No', 'et_builder' ),
					),
					'toggle_slug'     => 'other',
					'description'       => esc_html__( 'This setting will turn on and off the loop feature which will set the slider to continue after the last slide to the first slide.', 'et_builder' ),
				),
				'auto_height' => array(
					'label'           => esc_html__( 'Auto Height', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'Off', 'et_builder' ),
						'on'  => esc_html__( 'On', 'et_builder' ),
					),
					'toggle_slug'     => 'other',
					'description'        => esc_html__( 'If enabled slider height will adjust on each slide based on it\'s content.', 'et_builder' ),
				),
				'equal_height' => array(
					'label'           => esc_html__( 'Equal Height', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'Off', 'et_builder' ),
						'on'  => esc_html__( 'On', 'et_builder' ),
					),
					'toggle_slug'     => 'other',
					'description'        => esc_html__( 'If enabled slides height will become equal based on the maximum slide content.', 'et_builder' ),
				),
				'touch_drag' => array(
					'label'           => esc_html__( 'Touch / Mouse Drag', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'Off', 'et_builder' ),
						'on'  => esc_html__( 'On', 'et_builder' ),
					),
					'toggle_slug'     => 'other',
					'description'        => esc_html__( 'If enabled slider will be dragable with mouse and touch. Disable it if you are creating an interactive content and not a slider.', 'et_builder' ),
				),
				'use_urlhash' => array(
					'label'           => esc_html__( 'URLhash', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'Off', 'et_builder' ),
						'on'  => esc_html__( 'On', 'et_builder' ),
					),
					'toggle_slug'     => 'other',
					'description'        => esc_html__( 'If disabled slider will not work on custom hash URLs and you will also not see any cutom id in the url.', 'et_builder' ),
				),
				'rtl' => array(
					'label'           => esc_html__( 'Enable RTL support', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'Off', 'et_builder' ),
						'on'  => esc_html__( 'On', 'et_builder' ),
					),
					'toggle_slug'     => 'other',
				),
				'admin_label' => array(
					'label'       => esc_html__( 'Admin Label', 'et_builder' ),
					'type'        => 'text',
					'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
				),

				/********************************
				*			DESIGN TAB			*
				********************************/

				'min_height' => array(
					'label'             => esc_html__( 'Minimum Slider Height', 'et_builder' ),
					'label'             => esc_html__( 'Minimum Slider Height', 'et_builder' ),
					'type'              => 'range',
					'option_category'   => 'layout',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'slider_height',
					'validate_unit'   => true,
					'fixed_unit'      => 'px',
					'default'         => '200px',
					'mobile_options'  => true,
					'range_settings' => array(
						'min'  => '0',
						'max'  => '1000',
						'step' => '1',
					),
				),
				'min_height_tablet' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'slider_height',
				),
				'min_height_phone' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'slider_height',
				),
				'min_height_last_edited' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'slier_height',
				),
				//CAROUSEL MODE
				'num_items' => array(
					'label'             => esc_html__( 'Number of Slides per screen', 'et_builder' ),
					'type'              => 'range',
					'option_category'   => 'layout',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
					'mobile_options'  => true,
					'unitless'        => true,
					'range_settings' => array(
						'min'  => '1',
						'max'  => '12',
						'step' => '1',
					),
					'description'       => esc_html__( "Make Carousels. The number of slides you want to see per screen. Default is 1. While changing this for Tablet & Mobile make sure there is no 'px' or any unit in the text box, it should be just a number.", 'et_builder' ),
				),
				'num_items_tablet' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
				),
				'num_items_phone' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
				),
				'num_items_last_edited' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
				),
				'slide_by' => array(
					'label'             => esc_html__( 'Slide by x slide(s)', 'et_builder' ),
					'type'              => 'range',
					'option_category'   => 'layout',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
					'unitless'        => true,
					'range_settings' => array(
						'min'  => '1',
						'max'  => '12',
						'step' => '1',
					),
					'description'       => esc_html__( "Here you may adjust the number of slides to slide by on each rotate.", 'et_builder' ),
				),
				'slide_center_item'         => array(
					'label'           => esc_html__( 'Center Active Slide', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
					'options'         => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'description'     => esc_html__( 'This setting will make the active slide center', 'et_builder' ),
				),
				'slides_margin' => array(
					'label'             => esc_html__( 'Margin Between slides', 'et_builder' ),
					'type'              => 'range',
					'option_category'   => 'layout',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
					'mobile_options'  => true,
					'unitless'        => true,
					'description'       => esc_html__( "Here you may adjust the gap / margin between each slide. This is only applicable if you are using number of items above more than 1 to make it a carousle slider.", 'et_builder' ),
				),
				'slides_margin_tablet' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
				),
				'slides_margin_phone' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
				),
				'slides_margin_last_edited' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'carousel',
				),

				//Nav Menu control styling
				'navmenu_wrapper_bg' => array(
					'label'       => esc_html__( 'Nav Menu Wrapper BG Color', 'et_builder' ),
					'type'        => 'color-alpha',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'navmenu',
					'description' => esc_html__( 'Use the color picker to choose a color for Nav Menu Wrapper Div Background.', 'et_builder' ),
				),
				'navmenu_bg' => array(
					'label'       => esc_html__( 'Nav Menu BG Color - Inactive', 'et_builder' ),
					'type'        => 'color-alpha',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'navmenu',
					'description' => esc_html__( 'Use the color picker to choose a color for inactive Nav Menu Item Background.', 'et_builder' ),
				),
				'navmenu_bg_active' => array(
					'label'       => esc_html__( 'Nav Menu BG Color - Active', 'et_builder' ),
					'type'        => 'color-alpha',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'navmenu',
					'description' => esc_html__( 'Use the color picker to choose a color for active Nav Menu Item Background.', 'et_builder' ),
				),
				'navmenu_text_active' => array(
					'label'       => esc_html__( 'Nav Menu Text Color - Active', 'et_builder' ),
					'type'        => 'color-alpha',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'navmenu',
					'description' => esc_html__( 'Use the color picker to choose a color for active Nav Menu Item Text.', 'et_builder' ),
				),
				'navmenu_item_margin' => array(
					'label'           => esc_html__( 'Spacing between menu items', 'et_builder' ),
					'type'            => 'range',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'navmenu',
					'default'         => '10px',
					'range_settings' => array(
						'min'  => '0',
						'max'  => '100',
						'step' => '1',
					),
				),

				//DOT control stying
				'dots_bg' => array(
					'label'       => esc_html__( 'Dots Color - Inactive', 'et_builder' ),
					'type'        => 'color-alpha',
					'depends_show_if'   => 'off',
					'depends_to'        => array(
						'show_navmenu'
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dots',
					'description' => esc_html__( 'Use the color picker to choose a color for inactive dots.', 'et_builder' ),
				),
				'dots_bg_active' => array(
					'label'       => esc_html__( 'Dots Color - Active', 'et_builder' ),
					'type'        => 'color-alpha',
					'depends_show_if'   => 'off',
					'depends_to'        => array(
						'show_navmenu'
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dots',
					'description' => esc_html__( 'Use the color picker to choose a color for active dots.', 'et_builder' ),
				),
				'dots_size' => array(
					'label'             => esc_html__( 'Dots Size', 'et_builder' ),
					'type'              => 'range',
					'option_category'   => 'layout',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dots',
					'depends_show_if'   => 'off',
					'depends_to'        => array(
						'show_navmenu'
					),
					'mobile_options'  => true,
					'fixed_unit'          => 'px',
					'range_settings' => array(
						'min'  => '1',
						'max'  => '100',
						'step' => '1',
					),
					'description'       => esc_html__( "Here you may adjust the size of the dots. This is only applicable if 'Dot Controls' is turned ON.", 'et_builder' ),
				),
				'dots_size_tablet' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dots',
				),
				'dots_size_phone' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dots',
				),
				'dots_size_last_edited' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dots',
				),

				//Other Styling
				'section_pading_margin' => array(
					'label'             => esc_html__( 'Disable Section Padding & Margin', 'et_builder' ),
					'type'              => 'yes_no_button',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'other',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'et_builder' ),
						'off' => esc_html__( 'No', 'et_builder' ),
					),
					'description'       => esc_html__( 'This setting will turn on and off the section\'s padding & margin within this slider.', 'et_builder' ),
				),
				'row_pading_margin' => array(
					'label'             => esc_html__( 'Disable Row Padding & Margin', 'et_builder' ),
					'type'              => 'yes_no_button',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'other',				
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'et_builder' ),
						'off' => esc_html__( 'No', 'et_builder' ),
					),
					'description'       => esc_html__( 'This setting will turn on and off the row\'s padding & margin within this slider. And will also make it 100% width.', 'et_builder' ),
				),

				/********************************
				*			ADVANCED TAB		*
				********************************/
				'disabled_on' => array(
					'label'           => esc_html__( 'Disable on', 'et_builder' ),
					'type'            => 'multiple_checkboxes',
					'options'         => array(
						'phone'   => esc_html__( 'Phone', 'et_builder' ),
						'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
						'desktop' => esc_html__( 'Desktop', 'et_builder' ),
					),
					'additional_att'  => 'disable_on',
					'option_category' => 'configuration',
					'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
					'tab_slug'        => 'custom_css',
					'toggle_slug'     => 'visibility',
				),
				'module_id' => array(
					'label'           => esc_html__( 'CSS ID', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'configuration',
					'tab_slug'        => 'custom_css',
					'option_class'    => 'et_pb_custom_css_regular',
				),
				'module_class' => array(
					'label'           => esc_html__( 'CSS Class', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'configuration',
					'tab_slug'        => 'custom_css',
					'option_class'    => 'et_pb_custom_css_regular',
				),
			);
			return $fields;
		}

		function pre_shortcode_content() {
			global $et_pb_slider_hide_mobile, $et_pb_slider_item_num, $slide_admin_label, $f_menuItem,$urlHash;

			$et_pb_slider_item_num = 0;
			$slide_admin_label=[];
			$f_menuItem = [];
			$urlHash = $this->shortcode_atts['use_urlhash'];

		}

		function shortcode_callback( $atts, $content = null, $function_name ) {
   

			$module_id = $this->shortcode_atts['module_id'];
			$module_class = $this->shortcode_atts['module_class'];
			$show_arrows = $this->shortcode_atts['show_arrows'];
			$show_pagination = $this->shortcode_atts['show_pagination'];
			$auto = $this->shortcode_atts['auto'];
			$auto_speed = $this->shortcode_atts['auto_speed'];
			$auto_ignore_hover = $this->shortcode_atts['auto_ignore_hover'];
			$num_items = $this->shortcode_atts['num_items'];
			$num_items_tablet = $this->shortcode_atts['num_items_tablet'];
			$num_items_phone = $this->shortcode_atts['num_items_phone'];
			$num_items_last_edited = $this->shortcode_atts['num_items_last_edited'];
			$slides_margin = $this->shortcode_atts['slides_margin'];
			$slides_margin_tablet = $this->shortcode_atts['slides_margin_tablet'];
			$slides_margin_phone = $this->shortcode_atts['slides_margin_phone'];
			$slides_margin_last_edited = $this->shortcode_atts['slides_margin_last_edited'];
			$dots_size = $this->shortcode_atts['dots_size'];
			$dots_size_tablet = $this->shortcode_atts['dots_size_tablet'];
			$dots_size_phone = $this->shortcode_atts['dots_size_phone'];
			$dots_size_last_edited = $this->shortcode_atts['dots_size_last_edited'];
			$row_pading_margin = $this->shortcode_atts['row_pading_margin'];
			$section_pading_margin = $this->shortcode_atts['section_pading_margin'];
			$arrows_position = $this->shortcode_atts['arrows_position'];
			$dots_position = $this->shortcode_atts['dots_position'];
			$loop_slider = $this->shortcode_atts['loop_slider'];
			$next_label = $this->shortcode_atts['next_label'];
			$prev_label = $this->shortcode_atts['prev_label'];
			$disable_np_label = $this->shortcode_atts['disable_np_label'];
			$custom_icon_prev = $this->shortcode_atts['button_prev_icon'];
			$button_prev = $this->shortcode_atts['custom_button_prev'];
			$custom_icon_next = $this->shortcode_atts['button_next_icon'];
			$button_next = $this->shortcode_atts['custom_button_next'];
			$auto_height = $this->shortcode_atts['auto_height'];
			$dots_bg_active = $this->shortcode_atts['dots_bg_active'];
			$dots_bg = $this->shortcode_atts['dots_bg'];
			$controls_on_hover = $this->shortcode_atts['controls_on_hover'];
			$animation_in = $this->shortcode_atts['animation_in'];
			$animation_out = $this->shortcode_atts['animation_out'];
			$touch_drag = $this->shortcode_atts['touch_drag'];
			$show_navmenu = $this->shortcode_atts['show_navmenu'];
			$navmenu_position = $this->shortcode_atts['navmenu_position'];
			$navmenu_bg = $this->shortcode_atts['navmenu_bg'];
			$navmenu_bg_active = $this->shortcode_atts['navmenu_bg_active'];
			$use_urlhash = $this->shortcode_atts['use_urlhash'];
			$rtl = $this->shortcode_atts['rtl'];
			$navmenu_item_margin = $this->shortcode_atts['navmenu_item_margin'];
			$navmenu_text_active = $this->shortcode_atts['navmenu_text_active'];
			$hide_navmenu_text = $this->shortcode_atts['hide_navmenu_text'];
			$navmenu_wrapper_bg = $this->shortcode_atts['navmenu_wrapper_bg'];
			$slide_by = $this->shortcode_atts['slide_by'];
			$slide_center_item = $this->shortcode_atts['slide_center_item'];
			$slide_on_hover = $this->shortcode_atts['slide_on_hover'];
			$equal_height = $this->shortcode_atts['equal_height'];
			$min_height = $this->shortcode_atts['min_height'];
			$min_height_tablet = $this->shortcode_atts['min_height_tablet'];
			$min_height_phone = $this->shortcode_atts['min_height_phone'];
			$min_height_last_edited = $this->shortcode_atts['min_height_last_edited'];

			//ENQUEUE STYLES & SCRIPTS
			wp_enqueue_style('cwp_anythingslider_owl_css', 0 );
            wp_enqueue_style('cwp_anythingslider_animate_css' );        
            wp_enqueue_script('cwp_anythingslider_owl_js' ); 

            //GLOBAL VARIABLES
			global $et_pb_slider_hide_mobile, $hashNo, $slide_admin_label, $f_menuItem;

			$content = $this->shortcode_content;

			$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

			$module_order_class_only = '';
			$module_order_class_only = ET_Builder_Element::add_module_order_class( $module_order_class_only, $function_name );

			$slider_selector = str_replace(" ",".",$module_class);
			if (substr($slider_selector, 0, 1) !== '.') { 
				$slider_selector = '.'.$slider_selector;
			}
			
			$hashNo = str_replace(" cwp_et_pb_layout_slider_standard_","",$module_order_class_only);
			
			$owl_navMenu_class = 'owl-navMenu-'.$hashNo;

			if ( 'on' === $section_pading_margin ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .et_pb_section',
					'declaration' => 'padding: 0 !important; margin: 0 !important;',
				) );
			}

			if ( 'on' === $row_pading_margin ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .et_pb_row',
					'declaration' => 'padding: 0 !important; margin: 0 !important; width: 100% !important',
				) );
			}

			//Slider Equal Height.
			if ( 'off' === $auto_height && 'on' === $equal_height ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-stage',
					'declaration' => 'display: -webkit-flex;
								    display: -ms-flexbox;
								    display: flex;
								    -webkit-flex-wrap: wrap;
								    -ms-flex-wrap: wrap;
								    flex-wrap: wrap;'
				) );
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-stage .owl-item',
					'declaration' => 'display: -webkit-flex;
								    display: -ms-flexbox;
								    display: flex;
								    height: auto !important;'
				) );
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-stage .owl-item .item',
					'declaration' => 'width: 100% !important;'
				) );
			}
			
			//Slider Minimum Height
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .owl-stage .owl-item .item',
				'declaration' => sprintf('min-height: %1$s', $min_height)
			) );
			
			
			$set_dots_position = '';
			if( 'top-left' === $dots_position ){ $set_dots_position = 'top:0; left:0;'; }
			elseif ( 'top-center' === $dots_position ){ $set_dots_position = 'top:0; left:50%; transform: translate(-50%);'; }
			elseif ( 'top-right' === $dots_position ){ $set_dots_position = 'top:0; right:0;'; }
			elseif ( 'bottom-left' === $dots_position ){ $set_dots_position = 'bottom:0; left:0;'; }
			elseif ( 'bottom-center' === $dots_position ){ $set_dots_position = 'bottom:0; left:50%; transform: translate(-50%);'; }
			elseif ( 'bottom-right' === $dots_position ){ $set_dots_position = 'bottom:0; right:0;'; }
			
			$set_navmenu_position = '';
			if( 'top-left' === $navmenu_position ){ $set_navmenu_position = 'text-align:left'; }
			elseif ( 'top-center' === $navmenu_position ){ $set_navmenu_position = 'text-align:center'; }
			elseif ( 'top-right' === $navmenu_position ){ $set_navmenu_position = 'text-align:right'; }
			elseif ( 'bottom-left' === $navmenu_position ){ $set_navmenu_position = 'text-align:left'; }
			elseif ( 'bottom-center' === $navmenu_position ){ $set_navmenu_position = 'text-align:center'; }
			elseif ( 'bottom-right' === $navmenu_position ){ $set_navmenu_position = 'text-align:right'; }

			/********************************
			*		NEXT/PREV CONTROL		*
			********************************/
			
			//Set next/previous position.
			$set_arrow_position = '';
			if( 'top-left' === $arrows_position ){ $set_arrow_position = 'top:0; left:0;'; }
			elseif ( 'top-center' === $arrows_position ){ $set_arrow_position = 'top:0; left:50%; transform: translate(-50%);'; }
			elseif ( 'top-right' === $arrows_position ){ $set_arrow_position = 'top:0; right:0;'; }
			elseif ( 'bottom-left' === $arrows_position ){ $set_arrow_position = 'bottom:0; left:0;'; }
			elseif ( 'bottom-center' === $arrows_position ){ $set_arrow_position = 'bottom:0; left:50%; transform: translate(-50%);'; }
			elseif ( 'bottom-right' === $arrows_position ){ $set_arrow_position = 'bottom:0; right:0;'; }

			//Set margin on next button icon.	
			if ( 'on' === $show_arrows && '' === $next_label ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav a.et_pb_button_next:after',
					'declaration' => 'margin-left: 0.1em;'
				) );
			}
			//Set margin on prev button icon.	
			if ( 'on' === $show_arrows && '' === $prev_label ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav a.et_pb_button_prev:before',
					'declaration' => 'margin-left: -1.2em;'
				) );
			}
			//Set next/prev control styling if position is NOT set to both sides.	
			if ( 'on' === $show_arrows && 'sides' !== $arrows_position ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav',
					'declaration' => sprintf(
									 'position: absolute; 
									  z-index: 100; 
									  %2$s; 
									  color:#fff; 
									  %3$s,
									  -webkit-transition: all 0.2s ease-in-out; 
									  -moz-transition: all 0.2s ease-in-out; 
									  transition: all 0.2s ease-in-out;
									  %1$s',
									  $set_arrow_position,
									  ( 'off' === $controls_on_hover ? 'opacity:1' : 'opacity:0' ),
									  ( 'top-center' === $arrows_position ||  'bottom-center' === $arrows_position ? 'margin-top:1em; margin-bottom:1em;' : 'margin:1em;' )
									  )
				) );
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav, %%order_class%% .owl-nav .owl-prev, %%order_class%% .owl-nav .owl-next',
					'declaration' => 'display:inline-block;', 
				) );
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav .owl-prev',
					'declaration' => 'margin-right:0.5em;', 
				) );
			}

			//Set next/prev control styling if position is set to both sides.
			if ( 'on' === $show_arrows && 'sides' === $arrows_position ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav .owl-prev',
					'declaration' => sprintf('position: absolute; 
									  z-index: 100; 
									  %1$s;
									  %2$s,
									  color:#fff; 
									  -webkit-transition: all 0.2s ease-in-out; 
									  -moz-transition: all 0.2s ease-in-out; 
									  transition: all 0.2s ease-in-out;',
									  ( 'off' === $controls_on_hover ? 'opacity:1' : 'opacity:0' ),
									  'top: 50%; left: 0;'
									  ),
				) );
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav .owl-next',
					'declaration' => sprintf('position: absolute; 
									  z-index: 100; 
									  %1$s; 
									  %2$s,
									  color:#fff; 
									  -webkit-transition: all 0.2s ease-in-out; 
									  -moz-transition: all 0.2s ease-in-out; 
									  transition: all 0.2s ease-in-out;',
									  ( 'off' === $controls_on_hover ? 'opacity:1' : 'opacity:0' ),
									  'top: 50%;right: 0; '
									  ),
				) );
			}

			//Set next/prev control Opacity to 1 on slider Hover to make it visible if "Show Controls on Hover" is true.
			if ( 'on' === $show_arrows ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%:hover .owl-nav, %%order_class%%:hover .owl-nav .owl-prev, %%order_class%%:hover .owl-nav .owl-next',
					'declaration' => 'opacity:1;', 
				) );
			}
			//Set next/prev control Opacity to 0.5 and disabled pointer arrow is this is the last/first slide.
			if ( 'on' === $show_arrows ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-nav .owl-prev.disabled, %%order_class%% .owl-nav .owl-next.disabled',
					'declaration' => 'pointer-events: none;
	    							  opacity: 0.5;', 
				) );
			}

			//Previous button output.
			$prev_button_output = sprintf(
				'<a class="et_pb_button et_pb_button_prev%4$s"%3$s>%1$s</a>',
				( 'on' !== $disable_np_label ? esc_attr( $prev_label ) : '' ),
				'',
				'' !== $custom_icon_prev && 'on' === $button_prev ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_icon_prev ) )
				) : '',
				'' !== $custom_icon_prev && 'on' === $button_prev ? ' et_pb_custom_button_icon' : ''
			);
			//Next button output.
			$next_button_output = sprintf(
				'<a class="et_pb_button et_pb_button_next%4$s"%3$s>%1$s</a>',
				( 'on' !== $disable_np_label ? esc_attr( $next_label ) : '' ),
				'',
				'' !== $custom_icon_next && 'on' === $button_next ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_icon_next ) )
				) : '',
				'' !== $custom_icon_next && 'on' === $button_next ? ' et_pb_custom_button_icon' : ''
			);

			/********************************
			*		DOT CONTROL				*
			********************************/

			if ( 'on' === $show_pagination ) {
				//Set styling for dot control.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-dots .owl-dot',
					'declaration' => sprintf('margin-right: 10px;
									  display: inline-block;
									  -webkit-border-radius: %2$s;
									  -moz-border-radius: %2$s;
									  border-radius: %2$s;
									  background-color: %1$s;',
									  $dots_bg,
									  '100%'
									  )
				) );
				//Set styling for dot control ACTIVE
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-dots .owl-dot.active',
					'declaration' => sprintf('background-color: %1$s;',
										  $dots_bg_active
										  )
				) );
				//Remove margin from last dot control.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-dots .owl-dot:last-child',
					'declaration' => 'margin-right: 0;',
				) );
				//Set styling for dot control wrapper.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-dots',
					'declaration' => sprintf('z-index: 100; 
									  position: absolute;
									  %2$s; 
									  -webkit-transition: all 0.2s ease-in-out; 
									  -moz-transition: all 0.2s ease-in-out; 
									  transition: all 0.2s ease-in-out;
									  %1$s;
									  %3$s;',
									  $set_dots_position,
									  ( 'off' === $controls_on_hover ? 'opacity:1' : 'opacity:0' ),
									  ( 'top-center' === $arrows_position ||  'bottom-center' === $arrows_position ? 'margin-top:1em; margin-bottom:1em;' : 'margin:1em;' )
									  )
				) );
				//Set dot control Opacity to 1 on slider Hover to make it visible if "Show Controls on Hover" is true.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%:hover .owl-dots',
					'declaration' => 'opacity:1'
				) );	
			}

			//Set Dot control sizes.
			if ( '' !== $dots_size || '' !== $dots_size_tablet || '' !== $dots_size_phone ) {
				$dots_size_responsive_active = et_pb_get_responsive_status( $dots_size_last_edited );

				$dots_size_values = array(
					'desktop' => $dots_size,
					'tablet'  => $dots_size_responsive_active ? $dots_size_tablet : '',
					'phone'   => $dots_size_responsive_active ? $dots_size_phone : '',
				);

				et_pb_generate_responsive_css( $dots_size_values, '%%order_class%% .owl-dots .owl-dot', 'width', $function_name );
				
				et_pb_generate_responsive_css( $dots_size_values, '%%order_class%% .owl-dots .owl-dot', 'height', $function_name );
			}	

			/********************************
			*		NAV MENU CONTROL		*
			********************************/

			//Hide menu text is option enabled.
			if ( 'on' === $show_navmenu  && 'on' === $hide_navmenu_text ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%_navMenu .navMenu_item span.navMenu_label',
					'declaration' => 'display:none !important;',
				) );
			}

			if ( 'on' === $show_navmenu ) {
				//Nav Menu wrapper background styling.
				if ( isset($navmenu_wrapper_bg) ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%_navMenu',
						'declaration' => sprintf('background-color: %1$s;',
										  $navmenu_wrapper_bg
										  )
					) );
				}
				//Set Nav Menu Item Styling.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%_navMenu .navMenu_item',
					'declaration' => sprintf('margin-right: %3$s;
										padding: 10px;
									  display: inline-block;
									  cursor: pointer;
									  -webkit-border-radius: %2$s;
									  -moz-border-radius: %2$s;
									  border-radius: %2$s;
									  background-color: %1$s;
									  vertical-align:bottom;',
									  $navmenu_bg,
									  '0',
									  $navmenu_item_margin
									  )
				) );
				//Remove margin from last child.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%_navMenu .navMenu_item:last-child',
					'declaration' => 'margin-right: 0;',
				) );
				//Nav Menu item active/hover styling.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%_navMenu .navMenu_item.active, %%order_class%%_navMenu .navMenu_item:hover',
					'declaration' => sprintf('background-color: %1$s;',
										  $navmenu_bg_active
										  )
				) );
				//Nav Menu item LABEL active/hover styling.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%_navMenu .navMenu_item.active span.navMenu_label, %%order_class%%_navMenu .navMenu_item:hover span.navMenu_label',
					'declaration' => sprintf('color: %1$s!important;',
										  $navmenu_text_active
										  )
				) );
				//Nav menu wrapper styling.
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%_navMenu',
					'declaration' => sprintf('z-index: 100; 
									  %1$s;
									  %2$s;
									  %3$s;',
									  $set_navmenu_position,
									  ( 'off' === $controls_on_hover ? 'opacity:1' : 'opacity:0' ),
									  ( 'top-center' === $arrows_position ||  'bottom-center' === $arrows_position ? 'margin-top:1em; margin-bottom:1em;' : 'margin:1em;' )
									  )
				) );
			}

			//loop nav menu items and generate child output.
			$navMenu_items = '';
			$i = 0;
			foreach ($f_menuItem as $f_menuItem_i) {
				$navMenu_items .= sprintf('<div class="navMenu_item m%2$s">%1$s
						</div>',
						$f_menuItem_i,
						$i
					);
				$i++;
			}
			//Nav menu output.
			$menu_nav = sprintf(
				'<div class="%3$s cwp_navMenu %2$s">
						%1$s
				</div>',
				$navMenu_items,
				$owl_navMenu_class,
				$module_class.'_navMenu'
				
			);

			/********************************
			*		MODULE OUTPUT			*
			********************************/
			$output = sprintf(
				'%26$s
				<div%4$s class="owl-carousel %5$s">
						%2$s
				</div> <!-- .et_pb_slider -->
				%27$s
				<script type="text/javascript">
					%25$s
					$("%6$s").owlCarousel({
					nav: %3$s,
					dots: %7$s,
					items: %11$s,
					slideBy: %31$s,
					dotsEach: true,
					center: %32$s,
					autoHeight:%18$s,
					autoplay: %8$s,
					autoplayTimeout:%9$s,
					loop:%12$s,
					mouseDrag: %24$s,
					touchDrag: %24$s,
					pullDrag: %24$s,
					autoplayHoverPause:%10$s,
					navClass: [\'owl-prev\',\'owl-next\'],
					navText: [\'%13$s\', \'%14$s\'],
	    			animateIn: %22$s,
					animateOut: %23$s,
					responsive : {
								    // breakpoint from 0 to 767 (Mobile)
								    0 : {
								        items : %15$s,
								        margin: %19$s,
								    },
								    // breakpoint from 768 to 980 (Tablet)
								    768 : {
								        items : %16$s,
								        margin: %20$s,
								    },
								    // breakpoint from 980 up (Desktop)
								    980 : {
								        items : %17$s,
								        margin: %21$s,
								    }
								},
					%28$s
					%29$s
					%30$s

					});

					%33$s
					
					$("%6$s .et_pb_module.et_animated").addClass("cwp_as_animate");
					$("%6$s").on("changed.owl.carousel", function(event) {
						$("%6$s .et_pb_module").removeClass("et_animated");
						setTimeout(function(){ $("%6$s .owl-item.active .et_pb_module.cwp_as_animate").addClass("et_animated") }, 10);
					})

					});
					

				</script>
				',
				'',
				$content,
				('off' === $show_arrows ? 'false' : 'true'),
				( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
				( '' !== $module_class ? sprintf( '%1$s', esc_attr( $module_class ) ) : '' ),
				$slider_selector,
				('off' === $show_pagination && 'off' === $show_navmenu ? 'false' : 'true'),
				('off' === $auto ? 'false' : 'true'),
				('' !== $auto_speed ? $auto_speed : 5000),
				('off' === $auto_ignore_hover ? 'true' : 'false'),
				$num_items,
				( 'off' === $loop_slider ? 'false' : 'true' ),
				$prev_button_output,
				$next_button_output,
				( '' !==  $num_items_phone ? $num_items_phone : $num_items),
				( '' !==  $num_items_tablet ? $num_items_tablet : $num_items),
				$num_items,
				('off' === $auto_height ? 'false' : 'true'),
				( '' !==  $slides_margin_phone ? $slides_margin_phone : $slides_margin),
				( '' !==  $slides_margin_tablet ? $slides_margin_tablet : $slides_margin),
				$slides_margin,
				('off' === $animation_in ? '\'fadeIn\'' : '\''.$animation_in.'\''),
				('off' === $animation_out ? '\'fadeOut\'' : '\''.$animation_out.'\''),
				('off' === $touch_drag ? 'false' : 'true'),
				('off' === $auto_height ? 'jQuery(document).ready(function( $ ) {' : 'jQuery(window).load(function( $ ) {
					var $ = jQuery;'),
				( in_array($navmenu_position, array('top-left', 'top-center', 'top-right')) && 'on' === $show_navmenu ? $menu_nav : ''   ),
				(in_array($navmenu_position, array('bottom-left', 'bottom-center', 'bottom-right')) && 'on' === $show_navmenu ? $menu_nav : ''   ),
				('on' === $show_navmenu ? 'dotsContainer: \''.$slider_selector.'_navMenu\',' : ''),
				('on' === $use_urlhash ? 'URLhashListener: true, startPosition: \'URLHash\',' : ''),
				('on' === $rtl ? 'rtl:true' : ''),
				$slide_by,
				('on' === $slide_center_item ? 'true' : 'false'),
				('on' === $slide_on_hover ? sprintf('$("%1$s_navMenu .navMenu_item").hover(function() {
					    $(this).click();
					}, function() {});', $slider_selector) : '')
			);
			return $output;
		}

		/********************************
		*		BOX SHADOW				*
		********************************/
		public function process_box_shadow( $function_name ) {
			$boxShadow = ET_Builder_Module_Fields_Factory::get( 'BoxShadow' );
			$button_prev  = sprintf( '.%1$s .et_pb_button_prev', self::get_module_order_class( $function_name ) );
			$button_next  = sprintf( '.%1$s .et_pb_button_next', self::get_module_order_class( $function_name ) );

			if ( isset( $this->shortcode_atts['custom_button_prev'] ) && $this->shortcode_atts['custom_button_prev'] == 'on' ) {
				self::set_style( $function_name, array(
					'selector'    => $button_prev,
					'declaration' => $boxShadow->get_value( $this->shortcode_atts, array( 'suffix' => '_button_prev' ) )
				) );
			}

			if ( isset( $this->shortcode_atts['custom_button_next'] ) && $this->shortcode_atts['custom_button_next'] == 'on' ) {
				self::set_style( $function_name, array(
					'selector'    => $button_next,
					'declaration' => $boxShadow->get_value( $this->shortcode_atts, array( 'suffix' => '_button_next' ) )
				) );
			}

			parent::process_box_shadow( $function_name );
		}
	}
	$cwp_et_pb_layout_slider_standard = new CWP_ET_Builder_Module_AnythingSlider;
	add_shortcode( 'cwp_et_pb_layout_slider_standard', array($cwp_et_pb_layout_slider_standard, '_shortcode_callback') );


	/***************************************************************
	*															   *
	*				SLIDE ITEM - [ CHILD ]						   *
	*															   *
	***************************************************************/
	class CWP_ET_Builder_Module_AnythingSlider_Item extends ET_Builder_Module {
			
		function init() {
			$this->name                        = esc_html__( 'Slide', 'et_builder' );
			$this->slug                        = 'cwp_et_pb_layout_slide_item_standard';
			$this->type                        = 'child';
			$this->child_title_var             = 'admin_title';
			$this->child_title_fallback_var    = 'heading';

			$this->whitelisted_fields = array(
				'slide_layout',
				'use_icon',
				'font_icon',
				'icon_color',
				'use_circle',
				'circle_color',
				'use_circle_border',
				'circle_border_color',
				'image',
				'alt',
				'icon_placement',
				'admin_title',
				'use_image',
				'icon_font_size',
				'icon_custom_style',
				'icon_color_active',
				'icon_font_size_tablet',
				'icon_font_size_phone',
				'icon_font_size_last_edited',
				'slide_type',
				'content_slide',
				//Text Slide Options
				'ul_type',
				'ul_position',
				'ul_item_indent',
				'ol_type',
				'ol_position',
				'ol_item_indent',
				'quote_border_weight',
				'quote_border_color',
				//Image Slide Options
				'img_src',
				'media_position',
				'img_alt',
				'img_title_text',
				'img_show_in_lightbox',
				'img_url',
				'img_url_new_window',
				'img_align',
				'img_force_fullwidth',
				'img_always_center_on_mobile',
				//Two Buttons
				'button_one_text',
				'button_one_url',
				'button_two_text',
				'button_two_url',
				'buttons_gap',
				'buttons_align',
				//Other Options
				'vertical_align_slide',
				//Content Width
				'content_width',
				'content_width_tablet',
				'content_width_phone',
				'content_width_last_edited',
				'content_wrapper_align',
				'content_background',
				//Vertical Align
				'content_vertical_align',
				'media_vertical_align'


			);

			$this->advanced_setting_title_text = esc_html__( 'New Slide', 'et_builder' );
			$this->settings_text = esc_html__( 'Slide Settings', 'et_builder' );

			$this->options_toggles = array(
				'general'  => array(
					'toggles' => array(
						'type' => esc_html__( 'Slide Type', 'et_builder' ),
						'image' => esc_html__( 'Slide Image', 'et_builder' ),
						'layout' => esc_html__( 'Divi Layout Slide', 'et_builder' ),
						'content' => esc_html__( 'Slide Content', 'et_builder' ),
						'other' => esc_html__( 'Other Settings', 'et_builder' ),
						'menu' => esc_html__( 'Slide Menu Item', 'et_builder' ),
						'background' => esc_html__( 'Slide Background', 'et_builder' ),
					),
				),
				'advanced' => array(
					'toggles' => array(
						'image' => esc_html__( 'Image Styling', 'et_builder' ),
						'text' => array(
							'title'    => esc_html__( 'Text - Slide Type', 'et_builder' ),
							'tabbed_subtoggles' => true,
							'bb_icons_support' => true,
							'sub_toggles' => array(
								'p' => array( 'name' => 'P', 'icon' => 'text-left'),
								'a' => array( 'name' => 'A', 'icon' => 'text-link'),
								'ul' => array( 'name' => 'UL', 'icon' => 'list'),
								'ol' => array( 'name' => 'OL', 'icon' => 'numbered-list'),
								'quote' => array( 'name' => 'QUOTE', 'icon' => 'text-quote'),
							),
						),
						'header' => array(
							'title'    => esc_html__( 'Heading Text - Content Slide Type', 'et_builder' ),
							'tabbed_subtoggles' => true,
							'sub_toggles' => array(
								'h1' => array( 'name' => 'H1', 'icon' => 'text-h1' ),
								'h2' => array( 'name' => 'H2', 'icon' => 'text-h2' ),
								'h3' => array( 'name' => 'H3', 'icon' => 'text-h3' ),
								'h4' => array( 'name' => 'H4', 'icon' => 'text-h4' ),
								'h5' => array( 'name' => 'H5', 'icon' => 'text-h5' ),
								'h6' => array( 'name' => 'H6', 'icon' => 'text-h6' ),
							),
						),
						'icon_styling' => esc_html__( 'Menu Icon/Image Custom Styling', 'et_builder' ),
						'custom_margin_padding' => array(
							'title'    => esc_html__( 'Width, Vertical Alignment, Margin, Padding - Content Slide Type', 'et_builder' ),
							'priority' => 65,
						),
					),
				),
			);

			$this->main_css_element = '%%order_class%%';
			$this->advanced_options = array(
				'custom_margin_padding' => array(
					'css' => array(
						'main'      => "{$this->main_css_element} .as_slide_content_wrapper",
						'important' => 'all',
					),
				),
				'background' => array(
					'css' => array(
						'main' => "{$this->main_css_element}.item",
						'important' => 'all',
					),
					'use_background_video' => false,
				),

				'fonts' => array(
						'text'   => array(
							'label'    => esc_html__( 'Text', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} p"
							),
							'line_height' => array(
								'default' => '1.7em',
							),
							'font_size' => array(
								'default' => '14px',
							),
							'toggle_slug' => 'text',
							'sub_toggle'  => 'p',
							'hide_text_align' => true,
						),
						'link'   => array(
							'label'    => esc_html__( 'Link', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} a",
							),
							'line_height' => array(
								'default' => '1em',
							),
							'font_size' => array(
								'default' => '14px',
							),
							'toggle_slug' => 'text',
							'sub_toggle'  => 'a',
						),
						'ul'   => array(
							'label'    => esc_html__( 'Unordered List', 'et_builder' ),
							'css'      => array(
								'main'        => "{$this->main_css_element} ul",
								'line_height' => "{$this->main_css_element} ul li",
							),
							'line_height' => array(
								'default' => '1em',
							),
							'font_size' => array(
								'default' => '14px',
							),
							'toggle_slug' => 'text',
							'sub_toggle'  => 'ul',
						),
						'ol'   => array(
							'label'    => esc_html__( 'Ordered List', 'et_builder' ),
							'css'      => array(
								'main'        => "{$this->main_css_element} ol",
								'line_height' => "{$this->main_css_element} ol li",
							),
							'line_height' => array(
								'default' => '1em',
							),
							'font_size' => array(
								'default' => '14px',
							),
							'toggle_slug' => 'text',
							'sub_toggle'  => 'ol',
						),
						'quote'   => array(
							'label'    => esc_html__( 'Blockquote', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} blockquote",
							),
							'line_height' => array(
								'default' => '1em',
							),
							'font_size' => array(
								'default' => '14px',
							),
							'toggle_slug' => 'text',
							'sub_toggle'  => 'quote',
						),
						'header'   => array(
							'label'    => esc_html__( 'Heading', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h1",
							),
							'font_size' => array(
								'default' => '30px',
							),
							'toggle_slug' => 'header',
							'sub_toggle'  => 'h1',
						),
						'header_2'   => array(
							'label'    => esc_html__( 'Heading 2', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h2",
							),
							'font_size' => array(
								'default' => '26px',
							),
							'line_height' => array(
								'default' => '1em',
							),
							'toggle_slug' => 'header',
							'sub_toggle'  => 'h2',
						),
						'header_3'   => array(
							'label'    => esc_html__( 'Heading 3', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h3",
							),
							'font_size' => array(
								'default' => '22px',
							),
							'line_height' => array(
								'default' => '1em',
							),
							'toggle_slug' => 'header',
							'sub_toggle'  => 'h3',
						),
						'header_4'   => array(
							'label'    => esc_html__( 'Heading 4', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h4",
							),
							'font_size' => array(
								'default' => '18px',
							),
							'line_height' => array(
								'default' => '1em',
							),
							'toggle_slug' => 'header',
							'sub_toggle'  => 'h4',
						),
						'header_5'   => array(
							'label'    => esc_html__( 'Heading 5', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h5",
							),
							'font_size' => array(
								'default' => '16px',
							),
							'line_height' => array(
								'default' => '1em',
							),
							'toggle_slug' => 'header',
							'sub_toggle'  => 'h5',
						),
						'header_6'   => array(
							'label'    => esc_html__( 'Heading 6', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h6",
							),
							'font_size' => array(
								'default' => '14px',
							),
							'line_height' => array(
								'default' => '1em',
							),
							'toggle_slug' => 'header',
							'sub_toggle'  => 'h6',
						),
			),
			'text'      => array(
				'sub_toggle'  => 'p',
				'css' => array(
					'text_orientation' => "{$this->main_css_element} p"
				),
			),
			'button' => array(
				'button_one' => array(
					'label' => esc_html__( 'Button One', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .as_et_pb_button_one.et_pb_button",
					),
				),
				'button_two' => array(
					'label' => esc_html__( 'Button Two', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .as_et_pb_button_two.et_pb_button",
					),
				),
			),

			);
			$this->custom_css_options = array(
				'slide_container' => array(
					'label'    => esc_html__( 'Slide Container', 'et_builder' ),
					'selector' => '%%order_class%% .item',
				),
				'slide_content_wrapper' => array(
					'label'    => esc_html__( 'Slide Content Wrapper', 'et_builder' ),
					'selector' => '%%order_class%% .as_slide_content_wrapper',
				),
				'slide_image_wrapper' => array(
					'label'    => esc_html__( 'Slide Image Wrapper', 'et_builder' ),
					'selector' => '%%order_class%% .as_slide_image_wrapper',
				),
				'slide_buttons_wrapper' => array(
					'label'    => esc_html__( 'Slide Buttons Wrapper', 'et_builder' ),
					'selector' => '%%order_class%% .as_buttons_wrapper',
				),
			);
			$et_accent_color = et_builder_accent_color();

			$this->fields_defaults = array(
				'use_icon'            => array( 'off' ),
				'icon_custom_style'   => array( 'off', 'add_default_setting' ),
				'icon_color'          => array( $et_accent_color, 'add_default_setting' ),
				'use_circle'          => array( 'off' ),
				'circle_color'        => array( $et_accent_color, 'only_default_setting' ),
				'use_circle_border'   => array( 'off' ),
				'circle_border_color' => array( $et_accent_color, 'only_default_setting' ),
				'icon_placement'      => array( 'top' ),
				'icon_font_size'      => array( '14px'),
				'slide_type'          => array( 'layout', 'add_default_setting'),
				'img_align'           => array( 'left' ),
				'img_show_in_lightbox'    => array( 'on', 'add_default_setting' ),
				'media_position'   		  => array( 'top', 'add_default_setting' ),
				'buttons_gap'    		  => array( '10px', 'add_default_setting' ),
				'buttons_align'           => array( 'left' ),
				'content_wrapper_align'   => array( 'left', 'add_default_setting' ),
			);
		}

		function get_fields() {
			//Slide type Options
			$slide_type_options = array(
				'content'=>'Content/Custom',
				'layout'=>'Divi Layout',
			);
			//Media Position
			$media_position_options = array(
				'top'=>'Top',
				'left'=>'Left',
				'right'=>'Right',
				'bottom'=>'Bottom'
			);
			//Vertical Align Options
			$vertical_align_options = array(
				'flex-start'=>'Top',
				'center'=>'Center',
				'flex-end'=>'Bottom'
			);

			//Get Divi Layouts.
			$options = array();
			$layouts = get_posts(array('post_type'=>'et_pb_layout', 'posts_per_page'=>-1));
			foreach ($layouts as $layout) {
				$options[$layout->ID] = $layout->post_title;
			}

			$args = array(
				'public'   => true
			);
			$output = 'objects'; // names or objects
			$pt_options = array();

			$post_types = get_post_types( $args, $output );

			foreach ( $post_types as $post_type=>$post_type_obj ) {
				$pt_options[$post_type] = $post_type_obj->labels->name;
			}

			$et_accent_color = et_builder_accent_color();

			//Set Image/Icon placement for Nav menu items.
			$image_icon_placement = array(
				'top' => esc_html__( 'Top', 'et_builder' ),
				'left' => esc_html__( 'Left', 'et_builder' ),
				'right' => esc_html__( 'Right', 'et_builder' ),
			);

			$fields = array(
				'admin_title' => array(
					'label'       => esc_html__( 'Slide/Menu Label', 'et_builder' ),
					'type'        => 'text',
					'toggle_slug'        => 'type',
					'description' => esc_html__( 'This will serve as Menu Label & Custom HashURL & also as Admin/Slide Label', 'et_builder' ),
				),
				'slide_type' => array(
					'label'           => __( 'Slide Type', 'et_builder' ),
					'type'            => 'select',
					'options'         => $slide_type_options,
					'affects'           => array(
					    'img_src',
					    'media_position',
					    'slide_layout',
					    'img_show_in_lightbox',
					    'button_one_text',
					    'button_one_url',
					    'button_two_text',
					    'button_two_url',
					    'buttons_gap',
					    'buttons_align',
					    'img_always_center_on_mobile',
						'img_force_fullwidth',
						'media_vertical_align',
						'img_align'
					),
					'toggle_slug'        => 'type',
					'description'       => __( 'Select your slide type first then dependant fields will appear bellow.', 'et_builder' ),
				),
				//IMAGE SLIDE OPTION
				'img_src' => array(
					'label'              => esc_html__( 'Image URL', 'et_builder' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
					'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
					'depends_show_if'   => 'content',
					'affects'            => array(
						'img_alt',
						'img_title_text',
					),
					'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
					'toggle_slug'        => 'image',
				),
				'media_position' => array(
					'label'           => __( 'Image Position', 'et_builder' ),
					'type'            => 'select',
					'options'         => $media_position_options,
					'toggle_slug'        => 'image',
					'depends_show_if'   => 'content',
				),
				'img_alt' => array(
					'label'           => esc_html__( 'Image Alternative Text', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'depends_default' => true,
					'depends_to'      => array(
						'img_src',
					),
					'description'     => esc_html__( 'This defines the HTML ALT text. A short description of your image can be placed here.', 'et_builder' ),
					'toggle_slug'     => 'image',
				),
				'img_title_text' => array(
					'label'           => esc_html__( 'Image Title Text', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'depends_default' => true,
					'depends_to'      => array(
						'img_src',
					),
					'description'     => esc_html__( 'This defines the HTML Title text.', 'et_builder' ),
					'toggle_slug'     => 'image',
				),
				'img_show_in_lightbox' => array(
					'label'             => esc_html__( 'Open in Lightbox', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'off' => esc_html__( "No", 'et_builder' ),
						'on'  => esc_html__( 'Yes', 'et_builder' ),
					),
					'depends_show_if'   => 'content',
					'affects'           => array(
						'img_url',
						'img_url_new_window',
					),
					'toggle_slug'       => 'image',
					'description'       => esc_html__( 'Here you can choose whether or not the image should open in Lightbox. Note: if you select to open the image in Lightbox, url options below will be ignored.', 'et_builder' ),
				),
				'img_url' => array(
					'label'           => esc_html__( 'Link URL', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'depends_show_if' => 'off',
					'description'     => esc_html__( 'If you would like your image to be a link, input your destination URL here. No link will be created if this field is left blank.', 'et_builder' ),
					'toggle_slug'     => 'image',
				),
				'img_url_new_window' => array(
					'label'             => esc_html__( 'Url Opens', 'et_builder' ),
					'type'              => 'select',
					'option_category'   => 'configuration',
					'options'           => array(
						'off' => esc_html__( 'In The Same Window', 'et_builder' ),
						'on'  => esc_html__( 'In The New Tab', 'et_builder' ),
					),
					'depends_show_if'   => 'off',
					'toggle_slug'       => 'image',
					'description'       => esc_html__( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
				),
				'img_align' => array(
					'label'           => esc_html__( 'Image Alignment', 'et_builder' ),
					'type'            => 'text_align',
					'option_category' => 'layout',
					'options'         => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image',
					'description'     => esc_html__( 'Here you can choose the image alignment.', 'et_builder' ),
					'options_icon'    => 'module_align',
					'depends_show_if' => 'content',
				),
				'media_vertical_align' => array(
					'label'           => __( 'Image Vertical Align', 'et_builder' ),
					'type'            => 'select',
					'options'         => $vertical_align_options,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'custom_margin_padding',
					'depends_show_if' => 'content',
				),
				'img_force_fullwidth' => array(
					'label'             => esc_html__( 'Force Fullwidth', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'layout',
					'options'           => array(
						'off' => esc_html__( "No", 'et_builder' ),
						'on'  => esc_html__( 'Yes', 'et_builder' ),
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image',
					'affects' => array(
						'img_max_width',
					),
					'depends_show_if' => 'content',
				),
				'img_always_center_on_mobile' => array(
					'label'             => esc_html__( 'Always Center Image On Mobile', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'layout',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'et_builder' ),
						'off' => esc_html__( "No", 'et_builder' ),
					),
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'image',
					'depends_show_if' => 'content',
				),
				//Layout Slide Option
				'slide_layout' => array(
					'label'             => esc_html__( 'Select Divi Layout', 'et_builder' ),
					'type'              => 'select',
					'option_category'   => 'layout',
					'options'           => $options,
					'depends_show_if'   => 'layout',
					'toggle_slug'        => 'layout',
					'description'        => esc_html__( 'Choose a saved Divi layout to use as a slide.', 'et_builder' ),
				),
				//Content Slide Option
				'content_slide' => array(
					'label'       => __( 'Custom Content for Slide', 'et_builder' ),
					'type'        => 'tiny_mce',
					'toggle_slug'        => 'content',
					'description' => __( 'Use custom content as the slide. Only applicable if "Content/Editor" is selected above as slide type.', 'et_builder' ),
				),
				//Buttons
				'button_one_text' => array(
					'label'           => sprintf( esc_html__( 'Button %1$s Text', 'et_builder' ), '#1' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'description'     => esc_html__( 'Enter the text for the Button.', 'et_builder' ),
					'toggle_slug'     => 'content',
					'depends_show_if'   => 'content',
				),
				'button_one_url' => array(
					'label'           => sprintf( esc_html__( 'Button %1$s URL', 'et_builder' ), '#1' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'description'     => esc_html__( 'Enter the URL for the Button.', 'et_builder' ),
					'toggle_slug'     => 'content',
					'depends_show_if'   => 'content',
				),
				'button_two_text' => array(
					'label'           => sprintf( esc_html__( 'Button %1$s Text', 'et_builder' ), '#2' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'description'     => esc_html__( 'Enter the text for the Button.', 'et_builder' ),
					'toggle_slug'     => 'content',
					'depends_show_if'   => 'content',
				),
				'button_two_url' => array(
					'label'           => sprintf( esc_html__( 'Button %1$s URL', 'et_builder' ), '#2' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'description'     => esc_html__( 'Enter the URL for the Button.', 'et_builder' ),
					'toggle_slug'     => 'content',
					'depends_show_if'   => 'content',
				),
				'buttons_gap' => array(
					'label'           => esc_html__( 'Gap Between Buttons', 'et_builder' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'toggle_slug'     => 'content',
					'validate_unit'   => true,
					'range_settings'  => array(
						'min'  => '0',
						'max'  => '100',
						'step' => '1',
					),
					'depends_show_if'   => 'content',
				),
				'buttons_align' => array(
					'label'           => esc_html__( 'Buttons Alignment', 'et_builder' ),
					'type'            => 'text_align',
					'option_category' => 'layout',
					'options'         => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'toggle_slug'     => 'content',
					'description'     => esc_html__( 'Here you can choose the alignment for Buttons wrapper.', 'et_builder' ),
					'options_icon'    => 'module_align',
					'depends_show_if' => 'content',
				),
				// Menu Control Options
				'use_icon' => array(
					'label'           => esc_html__( 'Use Icon', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'basic_option',
					'options'         => array(
						'off' => esc_html__( 'No', 'et_builder' ),
						'on'  => esc_html__( 'Yes', 'et_builder' ),
					),
					'affects'     => array(
						'font_icon',
						'icon_custom_style',
						'use_image'
					),
					'toggle_slug'        => 'menu',
					'description' => esc_html__( 'Assign an icon to Nav Menu for this slide', 'et_builder' ),
				),
				'font_icon' => array(
					'label'               => esc_html__( 'Icon', 'et_builder' ),
					'type'                => 'text',
					'option_category'     => 'basic_option',
					'class'               => array( 'et-pb-font-icon' ),
					'renderer'            => 'et_pb_get_font_icon_list',
					'renderer_with_field' => true,
					'depends_default'     => true,
					'toggle_slug'        => 'menu',					
				),
				'icon_custom_style' => array(
					'label'           => esc_html__( 'Custom Icon Styling', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'basic_option',
					'options'         => array(
						'off' => esc_html__( 'No', 'et_builder' ),
						'on'  => esc_html__( 'Yes', 'et_builder' ),
					),
					'depends_default'     => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
					'affects'     => array(
						'use_circle',
						'icon_color',
						'icon_font_size',
						'icon_color_active'
					),
					'description' => esc_html__( 'Enable custom styling options for this slide\'s menu icon', 'et_builder' ),
				),
				'icon_color' => array(
					'label'             => esc_html__( 'Icon Color', 'et_builder' ),
					'type'              => 'color-alpha',
					'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'et_builder' ),
					'depends_default'   => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'icon_color_active' => array(
					'label'             => esc_html__( 'Icon Color - Active/Hover', 'et_builder' ),
					'type'              => 'color-alpha',
					'description'       => esc_html__( 'Here you can define a custom color for your icon for active or hover state.', 'et_builder' ),
					'depends_default'   => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'use_circle' => array(
					'label'           => esc_html__( 'Circle Icon', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'No', 'et_builder' ),
						'on'  => esc_html__( 'Yes', 'et_builder' ),
					),
					'affects'           => array(
						'use_circle_border',
						'circle_color',
					),
					'description' => esc_html__( 'Here you can choose whether icon set above should display within a circle.', 'et_builder' ),
					'depends_default'   => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'circle_color' => array(
					'label'           => esc_html__( 'Circle Color', 'et_builder' ),
					'type'            => 'color',
					'description'     => esc_html__( 'Here you can define a custom color for the icon circle.', 'et_builder' ),
					'depends_default' => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'use_circle_border' => array(
					'label'           => esc_html__( 'Show Circle Border', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'layout',
					'options'         => array(
						'off' => esc_html__( 'No', 'et_builder' ),
						'on'  => esc_html__( 'Yes', 'et_builder' ),
					),
					'affects'           => array(
						'circle_border_color',
					),
					'description' => esc_html__( 'Here you can choose whether if the icon circle border should display.', 'et_builder' ),
					'depends_default'   => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'circle_border_color' => array(
					'label'           => esc_html__( 'Circle Border Color', 'et_builder' ),
					'type'            => 'color',
					'description'     => esc_html__( 'Here you can define a custom color for the icon circle border.', 'et_builder' ),
					'depends_default' => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'icon_font_size' => array(
					'label'           => esc_html__( 'Icon Font Size', 'et_builder' ),
					'type'            => 'range',
					'option_category' => 'font_option',
					'default'         => '14px',
					'mobile_options'  => true,
					'range_settings' => array(
						'min'  => '1',
						'max'  => '120',
						'step' => '1',
					),
					'depends_default' => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'icon_font_size_tablet' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'icon_font_size_phone' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'icon_font_size_last_edited' => array(
					'type'     => 'skip',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_styling',
				),
				'use_image' => array(
					'label'           => esc_html__( 'Use Image', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'basic_option',
					'options'         => array(
						'off' => esc_html__( 'No', 'et_builder' ),
						'on'  => esc_html__( 'Yes', 'et_builder' ),
					),
					'description' => esc_html__( 'Assign an image to Nav Menu for this slide', 'et_builder' ),
					'affects'     => array(
						'image',
						'alt'
					),
					'depends_show_if'    => 'off',
					'toggle_slug'        => 'menu',
				),
				'image' => array(
					'label'              => esc_html__( 'Image', 'et_builder' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
					'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
					'depends_show_if'    => 'on',
					'toggle_slug'        => 'menu',
					'description'        => esc_html__( 'Upload an image to display at the top of your minimenu_item.', 'et_builder' ),
				),
				'alt' => array(
					'label'           => esc_html__( 'Image Alt Text', 'et_builder' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'toggle_slug'        => 'menu',
					'description'     => esc_html__( 'Define the HTML ALT text for your image here.', 'et_builder' ),
					'depends_show_if' => 'on',
				),
				'icon_placement' => array(
					'label'             => esc_html__( 'Image/Icon Placement', 'et_builder' ),
					'type'              => 'select',
					'option_category'   => 'layout',
					'options'           => $image_icon_placement,
					'toggle_slug'        => 'menu',					
					'description'       => esc_html__( 'Here you can choose where to place the icon.', 'et_builder' ),
				),
				'ul_type' => array(
				'label'             => esc_html__( 'Unordered List Style Type', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'disc'    => esc_html__( 'Disc', 'et_builder' ),
					'circle'  => esc_html__( 'Circle', 'et_builder' ),
					'square'  => esc_html__( 'Square', 'et_builder' ),
					'none'    => esc_html__( 'None', 'et_builder' ),
				),
				'priority'          => 80,
				'default'           => 'disc',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'text',
				'sub_toggle'        => 'ul',
			),
			'ul_position' => array(
				'label'             => esc_html__( 'Unordered List Style Position', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'outside' => esc_html__( 'Outside', 'et_builder' ),
					'inside'  => esc_html__( 'Inside', 'et_builder' ),
				),
				'priority'          => 85,
				'default'           => 'outside',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'text',
				'sub_toggle'        => 'ul',
			),
			'ul_item_indent' => array(
 				'label'           => esc_html__( 'Unordered List Item Indent', 'et_builder' ),
 				'type'            => 'range',
 				'option_category' => 'configuration',
 				'tab_slug'        => 'advanced',
 				'toggle_slug'     => 'text',
 				'sub_toggle'      => 'ul',
 				'priority'        => 90,
 				'default'         => '0px',
 				'range_settings'  => array(
 					'min'  => '0',
 					'max'  => '100',
 					'step' => '1',
 				),
 			),
			'ol_type' => array(
				'label'             => esc_html__( 'Ordered List Style Type', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'decimal'              => 'decimal',
					'armenian'             => 'armenian',
					'cjk-ideographic'      => 'cjk-ideographic',
					'decimal-leading-zero' => 'decimal-leading-zero',
					'georgian'             => 'georgian',
					'hebrew'               => 'hebrew',
					'hiragana'             => 'hiragana',
					'hiragana-iroha'       => 'hiragana-iroha',
					'katakana'             => 'katakana',
					'katakana-iroha'       => 'katakana-iroha',
					'lower-alpha'          => 'lower-alpha',
					'lower-greek'          => 'lower-greek',
					'lower-latin'          => 'lower-latin',
					'lower-roman'          => 'lower-roman',
					'upper-alpha'          => 'upper-alpha',
					'upper-greek'          => 'upper-greek',
					'upper-latin'          => 'upper-latin',
					'upper-roman'          => 'upper-roman',
					'none'                 => 'none',
				),
				'priority'          => 80,
				'default'           => 'decimal',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'text',
				'sub_toggle'        => 'ol',
			),
			'ol_position' => array(
				'label'             => esc_html__( 'Ordered List Style Position', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'outside' => esc_html__( 'Outside', 'et_builder' ),
					'inside'  => esc_html__( 'Inside', 'et_builder' ),
				),
				'priority'          => 85,
				'default'           => 'outside',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'text',
				'sub_toggle'        => 'ol',
			),
			'ol_item_indent' => array(
 				'label'           => esc_html__( 'Ordered List Item Indent', 'et_builder' ),
 				'type'            => 'range',
 				'option_category' => 'configuration',
 				'tab_slug'        => 'advanced',
 				'toggle_slug'     => 'text',
 				'sub_toggle'      => 'ol',
 				'priority'        => 90,
 				'default'         => '0px',
 				'range_settings'  => array(
 					'min'  => '0',
 					'max'  => '100',
 					'step' => '1',
 				),
 			),
			'quote_border_weight' => array(
				'label'           => esc_html__( 'Blockquote Border Weight', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'configuration',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'text',
				'sub_toggle'      => 'quote',
				'priority'        => 85,
				'default'         => '5px',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
			),
			'quote_border_color' => array(
				'label'           => esc_html__( 'Blockquote Border Color', 'et_builder' ),
				'type'            => 'color-alpha',
				'option_category' => 'configuration',
				'custom_color'    => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'text',
				'sub_toggle'      => 'quote',
				'field_template'  => 'color',
				'priority'        => 90,
			),
			'content_background' => array(
				'label'             => esc_html__( 'Content Wrapper Background', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'custom_margin_padding',
			),
			'content_width' => array(
				'label'           => esc_html__( 'Content Width', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'custom_margin_padding',
				'mobile_options'  => true,
				'validate_unit'   => true,
				'default'         => '50%',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
			),
			'content_width_tablet' => array (
				'type'        => 'skip',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'custom_margin_padding',
			),
			'content_width_phone' => array (
				'type'        => 'skip',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'custom_margin_padding',
			),
			'content_width_last_edited' => array(
				'type'        => 'skip',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'custom_margin_padding',
			),
			'content_wrapper_align' => array(
					'label'           => esc_html__( 'Content Wrapper Align', 'et_builder' ),
					'type'            => 'text_align',
					'option_category' => 'layout',
					'options'         => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'custom_margin_padding',
					'options_icon'    => 'module_align',
			),
			'content_vertical_align' => array(
					'label'           => __( 'Content Vertical Align', 'et_builder' ),
					'type'            => 'select',
					'options'         => $vertical_align_options,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'custom_margin_padding',
			),
			);
			return $fields;
		}

		function shortcode_callback( $atts, $content = null, $function_name ) {
			$slide_layout = $this->shortcode_atts['slide_layout'];
			$admin_title = $this->shortcode_atts['admin_title'];
			$font_icon = $this->shortcode_atts['font_icon'];
			$use_icon = $this->shortcode_atts['use_icon'];
			$use_circle = $this->shortcode_atts['use_circle'];
			$use_circle_border = $this->shortcode_atts['use_circle_border'];
			$icon_color = $this->shortcode_atts['icon_color'];
			$circle_color = $this->shortcode_atts['circle_color'];
			$circle_border_color = $this->shortcode_atts['circle_border_color'];
			$image = $this->shortcode_atts['image'];
			$alt = $this->shortcode_atts['alt'];
			$icon_placement = $this->shortcode_atts['icon_placement'];
			$use_image = $this->shortcode_atts['use_image'];
			$icon_font_size = $this->shortcode_atts['icon_font_size'];
			$icon_font_size_tablet = $this->shortcode_atts['icon_font_size_tablet'];
			$icon_font_size_phone = $this->shortcode_atts['icon_font_size_phone'];
			$icon_font_size_last_edited = $this->shortcode_atts['icon_font_size_last_edited'];
			$icon_custom_style = $this->shortcode_atts['icon_custom_style'];
			$icon_color_active = $this->shortcode_atts['icon_color_active'];
			$slide_type = $this->shortcode_atts['slide_type'];
			$content_slide = $this->shortcode_atts['content_slide'];
			$ul_type              = $this->shortcode_atts['ul_type'];
			$ul_position          = $this->shortcode_atts['ul_position'];
			$ul_item_indent       = $this->shortcode_atts['ul_item_indent'];
			$ol_type              = $this->shortcode_atts['ol_type'];
			$ol_position          = $this->shortcode_atts['ol_position'];
			$ol_item_indent       = $this->shortcode_atts['ol_item_indent'];
			$quote_border_weight  = $this->shortcode_atts['quote_border_weight'];
			$quote_border_color   = $this->shortcode_atts['quote_border_color'];
			//IMAGE SLIDE OPTIONS
			$img_src                     = $this->shortcode_atts['img_src'];
			$media_position                = $this->shortcode_atts['media_position'];
			$img_alt                     = $this->shortcode_atts['img_alt'];
			$img_title_text              = $this->shortcode_atts['img_title_text'];
			$img_url                     = $this->shortcode_atts['img_url'];
			$img_url_new_window          = $this->shortcode_atts['img_url_new_window'];
			$img_show_in_lightbox        = $this->shortcode_atts['img_show_in_lightbox'];
			$img_align                   = $this->shortcode_atts['img_align'];
			$img_force_fullwidth         = $this->shortcode_atts['img_force_fullwidth'];
			$img_always_center_on_mobile = $this->shortcode_atts['img_always_center_on_mobile'];
			//Buttons
			$button_one_text              = $this->shortcode_atts['button_one_text'];
			$button_one_url               = $this->shortcode_atts['button_one_url'];
			$button_one_rel               = $this->shortcode_atts['button_one_rel'];
			$custom_icon_1                = $this->shortcode_atts['button_one_icon'];
			$button_custom_1              = $this->shortcode_atts['custom_button_one'];
			$button_two_text              = $this->shortcode_atts['button_two_text'];
			$button_two_url               = $this->shortcode_atts['button_two_url'];
			$button_two_rel               = $this->shortcode_atts['button_two_rel'];
			$custom_icon_2                = $this->shortcode_atts['button_two_icon'];
			$button_custom_2              = $this->shortcode_atts['custom_button_two'];
			$buttons_gap              	  = $this->shortcode_atts['buttons_gap'];
			$buttons_align                = $this->shortcode_atts['buttons_align'];
			//Content Width
			$content_width                = $this->shortcode_atts['content_width'];
			$content_width_tablet         = $this->shortcode_atts['content_width_tablet'];
			$content_width_phone          = $this->shortcode_atts['content_width_phone'];
			$content_width_last_edited    = $this->shortcode_atts['content_width_last_edited'];
			$content_wrapper_align    	  = $this->shortcode_atts['content_wrapper_align'];
			$content_background    	  	  = $this->shortcode_atts['content_background'];
			//Vertical Align
			$content_vertical_align    	  = $this->shortcode_atts['content_vertical_align'];
			$media_vertical_align    	  = $this->shortcode_atts['media_vertical_align'];
			//Slide Background.
			$background_position     = $this->shortcode_atts['background_position'];
			$background_size         = $this->shortcode_atts['background_size'];
			$background_repeat       = $this->shortcode_atts['background_repeat'];
			$background_blend        = $this->shortcode_atts['background_blend'];
			$background_color        = $this->shortcode_atts['background_color'];
			$background_image        = $this->shortcode_atts['background_image'];
			$parallax                = $this->shortcode_atts['parallax'];
			$parallax_method         = $this->shortcode_atts['parallax_method'];	


			$module_class = '';
			$module_class = ET_Builder_Element::add_module_order_class( '', $function_name );
			$video_background          = $this->video_background();
			$parallax_image_background = $this->get_parallax_image_background();
			
			global $et_pb_slider_hide_mobile, $et_pb_slider_item_num, $hashNo, $slide_admin_label, $f_menuItem, $urlHash;

			//Add z-index to slide parallax bg and video background
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_parallax_bg',
				'declaration' => 'z-index: -1;',
			) );

			// Applying backround-related style to slide item since advanced_option only targets module wrapper
			if ( 'off' === $parallax ) {
				if ('' !== $background_color) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%%.item',
						'declaration' => sprintf(
							'background-color: %1$s;',
							esc_html( $background_color )
						),
					) );
				}

				if ( '' !== $background_size && 'default' !== $background_size ) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%%.item',
						'declaration' => sprintf(
							'-moz-background-size: %1$s;
							-webkit-background-size: %1$s;
							background-size: %1$s;',
							esc_html( $background_size )
						),
					) );

					if ( 'initial' === $background_size ) {
						ET_Builder_Module::set_style( $function_name, array(
							'selector'    => 'body.ie %%order_class%%.item',
							'declaration' => '-moz-background-size: auto; -webkit-background-size: auto; background-size: auto;',
						) );
					}
				}

				if ( '' !== $background_position && 'default' !== $background_position ) {
					$processed_position = str_replace( '_', ' ', $background_position );

					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%%.item',
						'declaration' => sprintf(
							'background-position: %1$s;',
							esc_html( $processed_position )
						),
					) );
				}

				if ( '' !== $background_repeat ) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%%.item',
						'declaration' => sprintf(
							'background-repeat: %1$s;',
							esc_html( $background_repeat )
						),
					) );
				}

				if ( '' !== $background_blend ) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%%.item',
						'declaration' => sprintf(
							'background-blend-mode: %1$s;',
							esc_html( $background_blend )
						),
					) );
				}
			}


			/********************************
			*		Content Slide 			*
			********************************/

			//UL styling
			if ( '' !== $ul_type || '' !== $ul_position || '' !== $ul_item_indent ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% ul',
					'declaration' => sprintf(
						'%1$s
						%2$s
						%3$s',
						'' !== $ul_type ? sprintf( 'list-style-type: %1$s;', esc_html( $ul_type ) ) : '',
						'' !== $ul_position ? sprintf( 'list-style-position: %1$s;', esc_html( $ul_position ) ) : '',
						'' !== $ul_item_indent ? sprintf( 'padding-left: %1$s;', esc_html( $ul_item_indent ) ) : ''
					),
				) );
			}
			//OL Styling
			if ( '' !== $ol_type || '' !== $ol_position || '' !== $ol_item_indent ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% ol',
					'declaration' => sprintf(
						'%1$s
						%2$s
						%3$s',
						'' !== $ol_type ? sprintf( 'list-style-type: %1$s;', esc_html( $ol_type ) ) : '',
						'' !== $ol_position ? sprintf( 'list-style-position: %1$s;', esc_html( $ol_position ) ) : '',
						'' !== $ol_item_indent ? sprintf( 'padding-left: %1$s;', esc_html( $ol_item_indent ) ) : ''
					),
				) );
			}
			//Border Styling
			if ( '' !== $quote_border_weight || '' !== $quote_border_color ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% blockquote',
					'declaration' => sprintf(
						'%1$s
						%2$s',
						'' !== $quote_border_weight ? sprintf( 'border-width: %1$s;', esc_html( $quote_border_weight ) ) : '',
						'' !== $quote_border_color ? sprintf( 'border-color: %1$s;', esc_html( $quote_border_color ) ) : ''
					),
				) );
			}

			// Handle svg image behaviour
			$img_src_pathinfo = pathinfo( $img_src );
			$is_img_src_svg = isset( $img_src_pathinfo['extension'] ) ? 'svg' === $img_src_pathinfo['extension'] : false;

			// Set display block for svg image to avoid disappearing svg image
			if ( $is_img_src_svg ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .et_pb_image_wrap',
					'declaration' => 'display: block;',
				) );
			}

			if ( 'on' === $img_always_center_on_mobile ) {
				$module_class .= ' et_always_center_on_mobile';
			}

			if ( 'on' === $img_force_fullwidth ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%',
					'declaration' => 'max-width: 100% !important;',
				) );

				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .et_pb_image_wrap, %%order_class%% img',
					'declaration' => 'width: 100%;',
				) );
			}

			if ( $this->fields_defaults['img_align'][0] !== $img_align ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%',
					'declaration' => sprintf(
						'text-align: %1$s;',
						esc_html( $img_align )
					),
				) );
			}

			if ( 'center' !== $img_align ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%%',
					'declaration' => sprintf(
						'margin-%1$s: 0;',
						esc_html( $img_align )
					),
				) );
			}

			// Image Horizontale Alignment
			switch($img_align) {
				case 'center':
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .as_slide_image_wrapper',
						'declaration' => 'align-self: center;'
					) );
				break;

				case 'right':
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .as_slide_image_wrapper',
						'declaration' => 'align-self: flex-end;'
					) );
				break;

				case 'left':
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .as_slide_image_wrapper',
						'declaration' => 'align-self: flex-start;'
					) );
				break;


			}

			//IMAGE OUTPUT
			$img_output = '';
			if ( 'content' === $slide_type && '' !== $img_src ) {
				$img_output = sprintf(
						'<div class="as_slide_image et_pb_module et_pb_image">
							%1$s
						</div>',
						sprintf(
							'<span class="et_pb_image_wrap"><img src="%1$s" alt="%2$s"%3$s /></span>',
							esc_url( $img_src ),
							esc_attr( $img_alt ),
							( '' !== $img_title_text ? sprintf( ' title="%1$s"', esc_attr( $img_title_text ) ) : '' )
						)
					);

				if ( 'on' === $img_show_in_lightbox ) {
					$img_output = sprintf( '<a href="%1$s" class="et_pb_lightbox_image" title="%3$s">%2$s</a>',
						esc_url( $img_src ),
						$img_output,
						esc_attr( $img_alt )
					);
				} else if ( '' !== $img_url ) {
					$img_output = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
						esc_url( $img_url ),
						$img_output,
						( 'on' === $img_url_new_window ? ' target="_blank"' : '' )
					);
				}
				$img_output = sprintf('<div class="as_slide_image_wrapper">
										%1$s
										</div>',
										$img_output
				);
			}
			//BUTTONS OUTPUT
			$button_output = '';
			if ( '' !== $button_one_text || '' !== $button_two_text ){
				$button_output .= '<div class="as_buttons_wrapper">';
			}
			if ( '' !== $button_one_text ) {
				$button_output .= sprintf(
					'<a href="%2$s" class="et_pb_more_button et_pb_button as_et_pb_button_one%4$s"%3$s%5$s>%1$s</a>',
					( '' !== $button_one_text ? esc_attr( $button_one_text ) : '' ),
					( '' !== $button_one_url ? esc_url( $button_one_url ) : '#' ),
					'' !== $custom_icon_1 && 'on' === $button_custom_1 ? sprintf(
						' data-icon="%1$s"',
						esc_attr( et_pb_process_font_icon( $custom_icon_1 ) )
					) : '',
					'' !== $custom_icon_1 && 'on' === $button_custom_1 ? ' et_pb_custom_button_icon' : '',
					$this->get_rel_attributes( $button_one_rel )
				);
			}

			if ( '' !== $button_two_text ) {
				$button_output .= sprintf(
					'<a href="%2$s" class="et_pb_more_button et_pb_button as_et_pb_button_two%4$s"%3$s%5$s>%1$s</a>',
					( '' !== $button_two_text ? esc_attr( $button_two_text ) : '' ),
					( '' !== $button_two_url ? esc_url( $button_two_url ) : '#' ),
					'' !== $custom_icon_2 && 'on' === $button_custom_2 ? sprintf(
						' data-icon="%1$s"',
						esc_attr( et_pb_process_font_icon( $custom_icon_2 ) )
					) : '',
					'' !== $custom_icon_2 && 'on' === $button_custom_2 ? ' et_pb_custom_button_icon' : '',
					$this->get_rel_attributes( $button_two_rel )
				);
				//Buttons Gap = margin-left on button two
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .as_buttons_wrapper a.as_et_pb_button_two',
					'declaration' => sprintf(
						'margin-left: %1$s;',
						esc_html( $buttons_gap )
					),
				) );
			}
			if ( '' !== $button_one_text || '' !== $button_two_text ){
				$button_output .= '</div>';
			}

			if ( $this->fields_defaults['buttons_align'][0] !== $buttons_align ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .as_buttons_wrapper',
					'declaration' => sprintf(
						'text-align: %1$s;',
						esc_html( $buttons_align )
					),
				) );
			}

			if ( 'center' !== $buttons_align ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .as_buttons_wrapper',
					'declaration' => sprintf(
						'margin-%1$s: 0;',
						esc_html( $buttons_align )
					),
				) );
			}

			//Content Ouput
			$content_output = sprintf('<div class="as_slide_content_wrapper">
									%1$s
									%2$s
									</div>',
									$this->shortcode_content,
									$button_output
			);
			

			


			//Responsive CSS for Content Width
			if ( '' !== $content_width_tablet || '' !== $content_width_phone || '' !== $content_width ) {
				$content_width_responsive_active = et_pb_get_responsive_status( $content_width_last_edited );

				$content_width_values = array(
					'desktop' => $content_width,
					'tablet'  => $content_width_responsive_active ? $content_width_tablet : '',
					'phone'   => $content_width_responsive_active ? $content_width_phone : '',
				);
			}

			//Vertical Alignment & display grid $ max-width.
			if ( 'content' === $slide_type ) {
				//Width based on Responsiveness
				if ('' !== $content_width_tablet || '' !== $content_width_phone || '' !== $content_width) {
					CWP_ET_Builder_Module_AnythingSlider::cwp_generate_responsive_css( $content_width_values, '%%order_class%% .as_slide_content_wrapper', 'max-width', '',$function_name );
				}
			}
			
			//Vertical Alignment of Image
			if ( 'content' === $slide_type && '' !== $img_src ) {

				if ('top' === $media_position || 'bottom' === $media_position) {
					switch($media_vertical_align) {
						
						case 'center':
						ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .as_slide_image_wrapper',
								'declaration' => 'margin: auto 0;'
						) );
						break;

						case 'flex-end':
						ET_Builder_Element::set_style( $function_name, array(
								'selector'    => '%%order_class%% .as_slide_image_wrapper',
								'declaration' => 'margin-top: auto;'
						) );
						break;		

					}
				}


					//Set align-slef on image wrapper if media position is left or right only.
					switch ($media_position) {

						case 'left':
						case 'right':

						ET_Builder_Element::set_style( $function_name, array(
										'selector'    => '%%order_class%% .as_slide_image_wrapper',
										'declaration' => sprintf('align-self: %1$s;',
														$media_vertical_align
										)
						) );

						break;	
					}

			}

			
			//Aplly CSS GRID if media alignment is left/right.
			if ( 'content' === $slide_type && '' !== $img_src & '' !== $this->shortcode_content) {
				switch ($media_position) {
				    case 'left':
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%%.media_left',
							'declaration' => 'display: grid;
											grid-template-areas: "image content";
										    grid-gap: 20px;'
						) );
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%% .as_slide_image_wrapper',
							'declaration' => 'grid-area: image;'
						) );
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%% .as_slide_content_wrapper',
							'declaration' => 'grid-area: content;'
						) );
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%% .as_slide_content_wrapper',
							'declaration' => sprintf('align-self: %1$s;',
											$content_vertical_align
							)
						) );
						ET_Builder_Element::set_style( $function_name, array(
									'selector'    => '%%order_class%% .as_slide_content_wrapper',
									'declaration' => sprintf('justify-self: %1$s;',
													$content_wrapper_align
									)
						) );	
						//Width based on Responsiveness
						if ('' !== $content_width_tablet || '' !== $content_width_phone || '' !== $content_width) {
							CWP_ET_Builder_Module_AnythingSlider::cwp_generate_responsive_css( $content_width_values, '%%order_class%%.media_left', 'grid-template-columns', 'auto ', $function_name );
						}
					break;

					case 'right':
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%%.media_right',
							'declaration' => 'display: grid;
											grid-template-areas: "content image";
										    grid-gap: 20px;'
						) );
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%% .as_slide_image_wrapper',
							'declaration' => 'grid-area: image;'
						) );
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%% .as_slide_content_wrapper',
							'declaration' => 'grid-area: content;'
						) );
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%% .as_slide_content_wrapper',
							'declaration' => sprintf('align-self: %1$s;',
											$content_vertical_align
							)
						) );
						ET_Builder_Element::set_style( $function_name, array(
									'selector'    => '%%order_class%% .as_slide_content_wrapper',
									'declaration' => sprintf('justify-self: %1$s;',
													$content_wrapper_align
									)
						) );	
						//Width based on Responsiveness
						if ('' !== $content_width_tablet || '' !== $content_width_phone || '' !== $content_width) {
							CWP_ET_Builder_Module_AnythingSlider::cwp_generate_responsive_css( $content_width_values, '%%order_class%%.media_right', 'grid-template-columns', '',$function_name , ' auto;' );
						}
					break;
				}				
			}

			//Set display flex & container width if media position is top or bottom.
			if ('content' === $slide_type) {
				if ('top' === $media_position || 'bottom' === $media_position) {
					ET_Builder_Element::set_style( $function_name, array(
							'selector'    => '%%order_class%%',
							'declaration' => 'display: flex; flex-direction: column;'
					) );
					//Width based on Responsiveness
					if ('' !== $content_width_tablet || '' !== $content_width_phone || '' !== $content_width) {
						CWP_ET_Builder_Module_AnythingSlider::cwp_generate_responsive_css( $content_width_values, '%%order_class%% .as_slide_content_wrapper', 'width', '',$function_name );
					}
					if ('center' === $content_vertical_align) {
						ET_Builder_Element::set_style( $function_name, array(
									'selector'    => '%%order_class%% .as_slide_content_wrapper',
									'declaration' => 'margin: auto;'
						) );
					}
					if ('flex-end' === $content_vertical_align) {
						ET_Builder_Element::set_style( $function_name, array(
									'selector'    => '%%order_class%% .as_slide_content_wrapper',
									'declaration' => 'margin-top: auto;'
						) );
					}
				}
			}

			//Content Wrapper Horizontal Align.
			if ( $this->fields_defaults['content_wrapper_align'][0] !== $content_wrapper_align ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .as_slide_content_wrapper',
					'declaration' => sprintf(
						'text-align: %1$s;',
						esc_html( $content_wrapper_align )
					),
				) );
			}

			if ( 'center' !== $content_wrapper_align ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .as_slide_content_wrapper',
					'declaration' => sprintf(
						'margin-%1$s: 0;',
						esc_html( $content_wrapper_align )
					),
				) );
			}

			//Content Wrapper Background Color.
			if ( '' !== $content_background ) {
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .as_slide_content_wrapper',
					'declaration' => sprintf(
						'background: %1$s;',
						esc_html( $content_background )
					),
				) );
			}

			/********************************
			*		NAV MENU 				*
			********************************/

			//Set an order class for nav menu div.
			if ($hashNo === null) {
				$navmenu_order = '.cwp_et_pb_layout_slider_standard_0_navMenu';
			} else {
				$navmenu_order = sprintf('.cwp_et_pb_layout_slider_standard_%1$s_navMenu',
										 $hashNo+1);		
			}
			//Adjust icon/image position for RTL websites.
			if ( is_rtl() && 'left' === $icon_placement ) {
				$icon_placement = 'right';
			}
			//Image output saved to $image variable.
			if ( 'off' === $use_icon ) {
				$image = ( '' !== trim( $image ) ) ? sprintf(
					'<img src="%1$s" alt="%2$s"/>',
					esc_url( $image ),
					esc_attr( $alt )
				) : '';
			} else {
					$icon_style = '';
					if ('on' === $icon_custom_style) {
						//Set nav menu icon styling when custom styling for icon is enabled.
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => sprintf('%1$s .navMenu_item.m%2$s span.et-pb-icon',
													$navmenu_order, 
													$et_pb_slider_item_num 
											),
							'declaration' => sprintf('color: %1$s;
													 font-size: %2$s;
													 %3$s
													 %4$s',
												     esc_attr( $icon_color ),
												     esc_attr( $icon_font_size ),
												     ( 'on' === $use_circle ? sprintf( 'background-color: %1$s;', esc_attr( $circle_color ) ) : '' ),
												     ( 'on' === $use_circle && 'on' === $use_circle_border ? sprintf( 'border-color: %1$s;', esc_attr( $circle_border_color ) ) : '' )
													)
						) );
						//Set ACTIVE/HOVER nav menu icon styling when custom styling for icon is enabled.
						ET_Builder_Element::set_style( $function_name, array(
								'selector'    => sprintf('%1$s .navMenu_item.active.m%2$s span.et-pb-icon, %1$s .navMenu_item.m%2$s:hover span.et-pb-icon',
													  $navmenu_order,
													  $et_pb_slider_item_num
													  ),
								'declaration' => sprintf('color: %1$s;',
													  esc_attr( $icon_color_active )
													  )
						) );
					} else {
						//Set default icon font-size when custom icon styling is not enabled.
						ET_Builder_Element::set_style( $function_name, array(
							'selector'    => sprintf('%1$s .navMenu_item.m%2$s span.et-pb-icon',
													$navmenu_order, 
													$et_pb_slider_item_num 
											),
							'declaration' => sprintf('font-size: %1$s;',
												     esc_attr( $icon_font_size )
													)
						) );
					}
				//Icon Output saved to $image variable.
				$image = ( '' !== $font_icon ) ? sprintf(
					'<span class="et-pb-icon%2$s%3$s%4$s" style="%5$s">%1$s</span>',
					esc_attr( et_pb_process_font_icon( $font_icon ) ),
					'',
					( 'on' === $use_circle ? ' et-pb-icon-circle' : '' ),
					( 'on' === $use_circle && 'on' === $use_circle_border ? ' et-pb-icon-circle-border' : '' ),
					$icon_style
				) : '';
			}
			
			//Set icon/image placement.
				if ('top' === $icon_placement) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => sprintf('%1$s .navMenu_item.m%2$s span.navMenu_label',
											  $navmenu_order,
											  $et_pb_slider_item_num
										),
						'declaration' => sprintf('%1$s',
											  ( 'top' === $icon_placement ? sprintf( 'display: %1$s;', 'block') : '' )
										)
					) );
				}
				
			//generate slide nav menu label.
			if ( '' === $admin_title ) {
				$admin_title = 's'.$hashNo.'_'.$et_pb_slider_item_num;
			}
			//Add Slide menu content to an array.
			if ( 'on' === $use_icon || 'on' === $use_image ) {
				$f_menuItem[] = ( 'right' === $icon_placement ? 
					sprintf(
						'<span class="navMenu_label">%1$s</span>
						%2$s',
						$admin_title,
						$image
						) :
						sprintf('%1$s
							<span class="navMenu_label">%2$s</span>',
						$image,					
						$admin_title
						)
						);
			} else {$f_menuItem[] = sprintf('<span class="navMenu_label">%1$s</span>',$admin_title);
				}


			$et_pb_slider_item_num++;

			$hide_on_mobile_class = self::HIDE_ON_MOBILE;


			/********************************************
			*		Slide OUTPUT based on type			*
			*********************************************/
			$slide_content = '';
			if ($slide_type === 'content') {
				switch ($media_position) {
				    case 'bottom':
					$slide_content = $content_output;
					$slide_content .= $img_output;
					break;

					default:
					$slide_content = $img_output;
					$slide_content .= $content_output;
				}
			} else {
				$slide_content = do_shortcode('[et_pb_section global_module="' . $slide_layout . '"][/et_pb_section]');
			}

			/********************************
			*		MODULE OUTPUT			*
			********************************/
			
			//Add data-hash if enabled.
			if ('on' === $urlHash) {
				$dataHash = ' data-hash="'.( '' !== $admin_title ? str_replace(" ","-",strtolower($admin_title)) : 's'.$hashNo.'_'.$et_pb_slider_item_num ).'"';
			} else {$dataHash = '';}

			//Output
			$output = sprintf(
				'<div class="item%3$s%6$s"%2$s%4$s>
					%1$s
					%5$s
				</div>',
				$slide_content,
				$dataHash,
				esc_attr( $module_class ),
				'' !== $parallax_image_background ? ' et_pb_section_parallax' : '',
				$parallax_image_background,
				( 'content' === $slide_type ? ' media_'.$media_position : '' )
				// '' !== $video_background ? ' et_pb_section_video et_pb_preload' : '',
				// $video_background,
			);
			return $output;
		}
	}
	$cwp_et_pb_layout_slide_item_standard = new CWP_ET_Builder_Module_AnythingSlider_Item;
	add_shortcode( 'cwp_et_pb_layout_slide_item_standard', array($cwp_et_pb_layout_slide_item_standard, '_shortcode_callback') );
?>