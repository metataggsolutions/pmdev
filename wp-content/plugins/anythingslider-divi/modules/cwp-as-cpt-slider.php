<?php
	class CWP_Module_AS_CPT extends ET_Builder_Module {

		function init() {
			$this->name            = esc_html__( 'AS CPT Slider', 'et_builder' );
			$this->slug            = 'cwp_et_pb_as_cpt_slider';
		
			
			$this->whitelisted_fields = array(
				'post_type',
				'posts_number',
				'advance_arg',
				'offset_number',
				'include_tax',
				'include_tax_terms',
				'slide_layout',
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
				'rtl',
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
				'rtl'	  	 	  		  => array( 'off' ),
				'next_label'	  	 	  => array( 'Next', 'add_default_setting' ),
				'prev_label'	  	 	  => array( 'Prev', 'add_default_setting' ),
				'disable_np_label'	  	  => array( 'off', 'add_default_setting' ),
				'equal_height'	  	  	  => array( 'off', 'add_default_setting' ),
				'min_height'	  	  	  => array( '50px', 'add_default_setting' ),
				'post_type'      	      => array( 'post', 'add_default_setting' ),
				'advance_arg'      	      => array( 'off', 'add_default_setting' ),
				'posts_number'      	  => array( 3, 'add_default_setting' ),
				'offset_number'      	  => array( 0, 'add_default_setting' ),


			);

			$this->options_toggles = array(
				'general' => array(
					'toggles' => array(
						'slides'         => array(
							'title'    => esc_html__( 'Slides', 'et_builder' ),
							// 'priority' => 40,
						),
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


			$fields = array(
				/********************************
				*			CONTENT TAB			*
				********************************/
				//Slide Selection Options
				'post_type' => array(
					'label'             => esc_html__( 'Source/Post Type', 'et_builder' ),
					'type'              => 'select',
					'options'           => $pt_options,
					'toggle_slug'        => 'slides',
					'description'        => esc_html__( 'Select a source/post type.', 'et_builder' ),
				),
				'posts_number' => array(
					'label'             => esc_html__( 'Number of Slides', 'et_builder' ),
					'type'              => 'text',
					'toggle_slug'        => 'slides',
					'description'       => esc_html__( 'Select how many slides you would like to display in this slider.', 'et_builder' ),
				),
				'slide_layout' => array(
					'label'             => esc_html__( 'Select Slides Layout', 'et_builder' ),
					'type'              => 'select',
					'option_category'   => 'layout',
					'options'           => $options,
					'toggle_slug'        => 'slides',
					'description'        => esc_html__( 'Choose a saved Divi layout to use as a slide layout/structure.', 'et_builder' ),
				),
				'advance_arg'         => array(
					'label'           => esc_html__( 'Advance Query Arguments', 'et_builder' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'affects' => array(
						'offset_number',
						'include_tax',
						'include_tax_terms',
					),
					'toggle_slug'     => 'slides',
					'description'     => esc_html__( 'This setting will turn on and off advance options to filter down your query.', 'et_builder' ),
				),
				'offset_number' => array(
					'label'           => esc_html__( 'Offset Number', 'et_builder' ),
					'type'            => 'text',
					'depends_show_if'   => 'on',
					'toggle_slug'        => 'slides',
					'description'     => esc_html__( 'Select how many items you would like to offset by from the source.', 'et_builder' ),
				),
				'include_tax' => array(
					'label'           => esc_html__( 'Selective Taxonomy Only', 'et_builder' ),
					'type'            => 'text',
					'depends_show_if'   => 'on',
					'toggle_slug'        => 'slides',
					'description'     => esc_html__( 'Filter the query by specific taxonomy slug.', 'et_builder' ),
				),
				'include_tax_terms' => array(
					'label'           => esc_html__( 'Selective Taxonomy Terms', 'et_builder' ),
					'type'            => 'text',
					'depends_show_if'   => 'on',
					'toggle_slug'        => 'slides',
					'description'     => esc_html__( 'Filter the query by the above taxonomy and these comma separated term slugs.', 'et_builder' ),
				),
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

				//DOTS CONTROL
				'show_pagination' => array(
					'label'             => esc_html__( 'Dot Controls', 'et_builder' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'on'  => esc_html__( 'ON', 'et_builder' ),
						'off' => esc_html__( 'OFF', 'et_builder' ),
					),
					'affects' => array(
						'dots_position',
						'dots_bg',
						'dots_bg_active',
						'dots_size',
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

				//DOT control stying
				'dots_bg' => array(
					'label'       => esc_html__( 'Dots Color - Inactive', 'et_builder' ),
					'type'        => 'color-alpha',
					'depends_default'   => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dots',
					'description' => esc_html__( 'Use the color picker to choose a color for inactive dots.', 'et_builder' ),
				),
				'dots_bg_active' => array(
					'label'       => esc_html__( 'Dots Color - Active', 'et_builder' ),
					'type'        => 'color-alpha',
					'depends_default'   => true,
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
					'depends_default'   => true,
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


		function shortcode_callback( $atts, $content = null, $function_name ) {
   

			$module_id = $this->shortcode_atts['module_id'];
			$module_class = $this->shortcode_atts['module_class'];
			$posts_number        = $this->shortcode_atts['posts_number'];
			$post_type        = $this->shortcode_atts['post_type'];
			$advance_arg       = $this->shortcode_atts['advance_arg'];
			$offset_number       = $this->shortcode_atts['offset_number'];
			$include_tax         = $this->shortcode_atts['include_tax'];
			$include_tax_terms      = $this->shortcode_atts['include_tax_terms'];
			$slide_layout = $this->shortcode_atts['slide_layout'];
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
			$rtl = $this->shortcode_atts['rtl'];
			$slide_by = $this->shortcode_atts['slide_by'];
			$slide_center_item = $this->shortcode_atts['slide_center_item'];
			$equal_height = $this->shortcode_atts['equal_height'];
			$min_height = $this->shortcode_atts['min_height'];
			$min_height_tablet = $this->shortcode_atts['min_height_tablet'];
			$min_height_phone = $this->shortcode_atts['min_height_phone'];
			$min_height_last_edited = $this->shortcode_atts['min_height_last_edited'];

			//ENQUEUE STYLES & SCRIPTS
			wp_enqueue_style('cwp_anythingslider_owl_css', 0 );
            wp_enqueue_style('cwp_anythingslider_animate_css' );        
            wp_enqueue_script('cwp_anythingslider_owl_js' ); 


			$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

			$slider_selector = str_replace(" ",".",$module_class);
			if (substr($slider_selector, 0, 1) !== '.') { 
				$slider_selector = '.'.$slider_selector;
			}
			

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
					'selector'    => '
									%%order_class%% .owl-stage, 
									%%order_class%% .owl-stage .owl-item, 
									%%order_class%% .owl-stage .owl-item .item, 
									%%order_class%% .owl-stage .owl-item .item .et_pb_section,
									%%order_class%% .owl-stage .owl-item .item .et_pb_row
									',
					'declaration' => 'display: -webkit-flex;
								    display: -ms-flexbox;
								    display: flex;
								    flex: 1;'
				) );
				ET_Builder_Element::set_style( $function_name, array(
					'selector'    => '%%order_class%% .owl-stage .owl-item .item .et_pb_column',
					'declaration' => 'display: -webkit-flex;
								    display: -ms-flexbox;
								    display: flex;
								    -webkit-flex-wrap: wrap;
								    -ms-flex-wrap: wrap;
								    flex-wrap: wrap;
								    height: auto !important;'
				) );
			}
			//Slider Minimum Height
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .owl-stage .owl-item .item,
								  %%order_class%% .owl-stage .owl-item .item .cwp_as_min_height_bg',
				'declaration' => sprintf('min-height: %1$s', $min_height)
			) );
			
			
			$set_dots_position = '';
			if( 'top-left' === $dots_position ){ $set_dots_position = 'top:0; left:0;'; }
			elseif ( 'top-center' === $dots_position ){ $set_dots_position = 'top:0; left:50%; transform: translate(-50%);'; }
			elseif ( 'top-right' === $dots_position ){ $set_dots_position = 'top:0; right:0;'; }
			elseif ( 'bottom-left' === $dots_position ){ $set_dots_position = 'bottom:0; left:0;'; }
			elseif ( 'bottom-center' === $dots_position ){ $set_dots_position = 'bottom:0; left:50%; transform: translate(-50%);'; }
			elseif ( 'bottom-right' === $dots_position ){ $set_dots_position = 'bottom:0; right:0;'; }
			

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
			*			LOOP				*
			********************************/

			$args = array(
				'post_type' => $post_type,
				'posts_per_page' => $posts_number,
			);
			//Advance Arguments.
			if ('off' !== $advance_arg) {

				//Set Offset
				$args['offset'] = $offset_number;
				
				//Filter Taxonomy and Tax_term
				if ($include_tax && $include_tax_terms) {
					if (strpos($include_tax, '|') !== false) {
						$include_tax = explode('|', $include_tax);
						$include_tax_terms = explode('|', $include_tax_terms);
						
						$args['tax_query'] = array();
						
						for ($i = 0; $i < count($include_tax); $i++) {
							$args['tax_query'][] = array(
									'taxonomy' => $include_tax[$i],
									'field'    => 'slug',
									'terms'    => explode(',', $include_tax_terms[$i]),
							);
						}
					} else {
						$args['tax_query'] = array(
							array(
									'taxonomy' => $include_tax,
									'field'    => 'slug',
									'terms'    => explode(',', $include_tax_terms),
							)
						);
					}
				}

			}
			

			query_posts( $args );

			ob_start();

			if ( have_posts() ) {
				//$shortcodes = '';
				
				$i = 0;
				
				while ( have_posts() ) {
					the_post();
					
					
					echo '<div class="item">';
					echo do_shortcode('[et_pb_section global_module="' . $slide_layout . '"][/et_pb_section]');
					echo '</div>';
					
					
					$i++;
					
					
				} // endwhile
				
				wp_reset_query();
			} else {
				if ( et_is_builder_plugin_active() ) {
					include( ET_BUILDER_PLUGIN_DIR . 'includes/no-results.php' );
				} else {
					get_template_part( 'includes/no-results', 'index' );
				}
			}

			$content = ob_get_contents();

			ob_end_clean();

 			wp_reset_postdata();  


			/********************************
			*		MODULE OUTPUT			*
			********************************/
			$output = sprintf(
				'<div%4$s class="owl-carousel %5$s">
						%2$s
				</div> <!-- .et_pb_slider -->
				<script type="text/javascript">
					%25$s
					$("%6$s").owlCarousel({
					nav: %3$s,
					dots: %7$s,
					items: %11$s,
					slideBy: %27$s,
					dotsEach: true,
					center: %28$s,
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
					%26$s
					});

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
				('off' === $show_pagination ? 'false' : 'true'),
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
				('on' === $rtl ? 'rtl:true' : ''),
				$slide_by,
				('on' === $slide_center_item ? 'true' : 'false')
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
	$cwp_et_pb_as_cpt_slider = new CWP_Module_AS_CPT;
	add_shortcode( 'cwp_et_pb_as_cpt_slider', array($cwp_et_pb_as_cpt_slider, '_shortcode_callback') );
?>