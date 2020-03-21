<?php
		class CWP_ET_Builder_Module_AS_Menu extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'AS Menu', 'et_builder' );
		$this->slug       = 'cwp_et_pb_mini_menu';
		$this->child_slug      = 'cwp_et_pb_mini_menu_items';
		$this->child_item_text = esc_html__( 'Menu Item', 'et_builder' );

		$this->whitelisted_fields = array(
			// 'menu_id',
			'background_color',
			'text_orientation',
			'admin_label',
			'module_id',
			'module_class',
			'active_link_color',
			'menu_orientation',
			'menu_item_bg_color',
			'active_bg_color',
			'slide_on_hover'
		);

		$this->main_css_element = '%%order_class%%.et_pb_mini_menu';

		$this->advanced_options = array(
			'fonts' => array(
				'menu' => array(
					'label'    => esc_html__( 'Menu', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} li.et_pb_minimenu_item a",
						'plugin_main' => "{$this->main_css_element} li a, {$this->main_css_element} li",
					),
					'line_height' => array(
						'default' => '1em',
					),
					'font_size' => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '12',
							'max'  => '24',
							'step' => '1',
						),
					),
					'letter_spacing' => array(
						'default' => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '8',
							'step' => '1',
						),
					),
				),
			),
			'custom_margin_padding' => array(
				'css' => array(
					'main'      => "{$this->main_css_element} li a",
					'important' => 'all',
				),
			),
		);

		$this->custom_css_options = array(
			'menu_link' => array(
				'label'    => esc_html__( 'Menu Link', 'et_builder' ),
				'selector' => '%%order_class%% li a',
			),
			'active_menu_link' => array(
				'label'    => esc_html__( 'Active Menu Link', 'et_builder' ),
				'selector' => '%%order_class%% li a.active',
			),
			'menu_img' => array(
				'label'    => esc_html__( 'Menu Image', 'et_builder' ),
				'selector' => '%%order_class%% li a img',
			),
			'active_menu_img' => array(
				'label'    => esc_html__( 'Active Menu Image', 'et_builder' ),
				'selector' => '%%order_class%% li a.active img',
			),
			'menu_icon' => array(
				'label'    => esc_html__( 'Menu Icon', 'et_builder' ),
				'selector' => '%%order_class%% li a span.et-pb-icon',
			),
			'active_menu_icon' => array(
				'label'    => esc_html__( 'Active Menu Icon', 'et_builder' ),
				'selector' => '%%order_class%% li a.active span.et-pb-icon',
			),

		);

		$this->fields_defaults = array(
			'background_color'        => array( '#ffffff', 'only_default_setting' ),
			'text_orientation'        => array( 'left' ),
			'menu_orientation'        => array( 'horizontal' ),
			'hover_animation'        => array( 'off' ),
			'slide_on_hover'        => array( 'off', 'only_default_setting' ),
		);
	}

	function get_fields() {

		$fields = array(
			'slide_on_hover' => array(
				'label'             => esc_html__( 'Slide on Hover', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'ON', 'et_builder' ),
					'off' => esc_html__( 'OFF', 'et_builder' ),
				),
				'description'       => esc_html__( 'If enabled, slides will slide on mouse hover on Nav Menu Items.', 'et_builder' ),
			),
			'menu_orientation' => array(
				'label'             => esc_html__( 'Menu Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'         => array(
					'horizontal' => esc_html__( 'Horizontal', 'et_builder' ),
					'vertical'  => esc_html__( 'Vertical', 'et_builder' ),
				),
			),
			'text_orientation' => array(
				'label'             => esc_html__( 'Menu Align', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'left' => esc_html__( 'Left', 'et_builder' ),
					'center'  => esc_html__( 'Center', 'et_builder' ),
					'right'  => esc_html__( 'Right', 'et_builder' ),
				),
				'description'       => esc_html__( 'This controls the how your menu is aligned.', 'et_builder' ),
			),
			'background_color' => array(
				'label'       => esc_html__( 'Menu Background Color', 'et_builder' ),
				'type'        => 'color-alpha',
				'description' => esc_html__( 'This will be applied to whole menu div.', 'et_builder' ),
				'tab_slug'     => 'advanced',
			),
			'menu_item_bg_color' => array(
				'label'        => esc_html__( 'Menu Item BG Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
				'description' => esc_html__( 'This will be applied to menu item.', 'et_builder' ),
			),
			'active_bg_color' => array(
				'label'        => esc_html__( 'Active / Hover Item BG Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
				'description' => esc_html__( 'This will be applied to menu item.', 'et_builder' ),
			),
			'active_link_color' => array(
				'label'        => esc_html__( 'Active / Hover Menu Color', 'et_builder' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
				'description' => esc_html__( 'This will be applied to menu item.', 'et_builder' ),
			),
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
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
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
		global $cwp_et_pb_menu_item_num;

		$cwp_et_pb_menu_item_num = 0;

	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id         = $this->shortcode_atts['module_id'];
		$module_class      = $this->shortcode_atts['module_class'];
		$background_color  = $this->shortcode_atts['background_color'];
		$text_orientation  = $this->shortcode_atts['text_orientation'];
		$menu_orientation  = $this->shortcode_atts['menu_orientation'];
		// $menu_id           = $this->shortcode_atts['menu_id'];
		$active_link_color        = $this->shortcode_atts['active_link_color'];
		$menu_item_bg_color   = $this->shortcode_atts['menu_item_bg_color'];
		$active_bg_color   = $this->shortcode_atts['active_bg_color'];
		$slide_on_hover   = $this->shortcode_atts['slide_on_hover'];

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$style = 'list-style-type: none; padding: 0;';

		if ( '' !== $background_color ) {
			$style .= sprintf( ' background-color: %s;',
				esc_attr( $background_color )
			);
		}

		if ( 'horizontal' === $menu_orientation ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_mini_menu li',
				'declaration' => 'display: inline-block !important;'
			) );
		}

		if ( 'vertical' === $menu_orientation ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_mini_menu li',
				'declaration' => sprintf(
					'text-align: %1$s !important;
					text-align: -webkit-%1$s !important;
					text-align: -moz-%1$s !important;',
					esc_html( $text_orientation )
				),
			) );
		}

		$content = $this->shortcode_content;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		$class = " et_pb_text_align_{$text_orientation}";


		if ( '' !== $active_link_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_mini_menu a.active p, %%order_class%%.et_pb_mini_menu li a.active span.et-pb-icon, %%order_class%%.et_pb_mini_menu li a:hover p, %%order_class%%.et_pb_mini_menu li a:hover span.et-pb-icon',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $active_link_color )
				),
			) );
		}
		if ( '' !== $menu_item_bg_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_mini_menu li a',
				'declaration' => sprintf(
					'background-color: %1$s !important;',
					esc_html( $menu_item_bg_color )
				),
			) );
		}
		if ( '' !== $active_bg_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_mini_menu li a.active, %%order_class%%.et_pb_mini_menu li a:hover',
				'declaration' => sprintf(
					'background-color: %1$s !important;',
					esc_html( $active_bg_color )
				),
			) );
		}

		$menu_selector = str_replace(" ",".",$module_class);
		if (substr($menu_selector, 0, 1) !== '.') { 
			$menu_selector = '.'.$menu_selector;
		}

		$output = sprintf(
			'<ul%4$s class="et_pb_mini_menu%3$s%5$s"%2$s>
					%1$s
			</ul>
			<script>
			jQuery(document).ready(function( $ ) {
			    $(\'li.et_pb_minimenu_item a\').on(\'click\', function (e) {
			        
			        $(\'li.et_pb_minimenu_item a\').each(function () {
			            $(this).removeClass(\'active\');
			        })
			        $(this).addClass(\'active\');

			    });

			    %6$s

			});


			</script>',
			//$menu,
			$content,
			'style="'.$style.'"',
			esc_attr( $class ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			('on' === $slide_on_hover ? sprintf(' $("%1$s li a").attr("onmouseover","click();");', $menu_selector) : '')
		);
		return $output;
	}
}
		$cwp_et_pb_mini_menu = new CWP_ET_Builder_Module_AS_Menu;
		add_shortcode( 'cwp_et_pb_mini_menu', array($cwp_et_pb_mini_menu, '_shortcode_callback') );


		/***************************************************************
		*															   *
		*				Menu ITEM - [ CHILD ]						   *
		*															   *
		***************************************************************/
		class CWP_ET_Builder_Module_AS_Menu_Item extends ET_Builder_Module {
			
	function init() {
		$this->name                        = esc_html__( 'Menu Item', 'et_builder' );
		$this->slug                        = 'cwp_et_pb_mini_menu_items';
		//$this->fb_support                  = true;
		$this->type                        = 'child';
		$this->child_title_var             = 'title';
		// $this->child_title_fallback_var    = 'heading';

		$this->whitelisted_fields = array(
			'title',
			'url',
			'admin_title',
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
			'animation',
			'max_width',
			'use_icon_font_size',
			'icon_font_size',
			'max_width_tablet',
			'max_width_phone',
			'max_width_last_edited',
			'icon_font_size_tablet',
			'icon_font_size_phone',
			'icon_font_size_last_edited',
			'horizontal_align',
			'vertical_align'
		);

		$et_accent_color = et_builder_accent_color();

		$this->advanced_setting_title_text = esc_html__( 'New Menu Item', 'et_builder' );
		$this->settings_text               = esc_html__( 'Menu Settings', 'et_builder' );
		$this->main_css_element = '%%order_class%%';
		
		$this->fields_defaults = array(
			'use_icon'            => array( 'off' ),
			'icon_color'          => array( $et_accent_color, 'add_default_setting' ),
			'use_circle'          => array( 'off' ),
			'circle_color'        => array( $et_accent_color, 'only_default_setting' ),
			'use_circle_border'   => array( 'off' ),
			'circle_border_color' => array( $et_accent_color, 'only_default_setting' ),
			'icon_placement'      => array( 'top' ),
			'animation'           => array( 'top' ),
			'use_icon_font_size'  => array( 'off' ),
			'horizontal_align'  => array( 'center' ),
			'vertical_align'  => array( 'middle' ),
		);
	}

	function get_fields() {

		$et_accent_color = et_builder_accent_color();

		$image_icon_placement = array(
			'top' => esc_html__( 'Top', 'et_builder' ),
			'left' => esc_html__( 'Left', 'et_builder' ),
			'right' => esc_html__( 'Right', 'et_builder' ),
		);
		
		$fields = array(
			'title' => array(
				'label'             => esc_html__( 'Menu Label', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'description'       => esc_html__( "Provide label / text for this menu item. Leave blank if you wish to create icon menu.", 'et_builder' ),
			),
			'url' => array(
				'label'           => esc_html__( 'Slide #Url', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your slide #URL here.', 'et_builder' ),
			),
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
					'use_circle',
					'icon_color',
					'image',
					'alt',
				),
				'description' => esc_html__( 'Here you can choose whether icon set below should be used.', 'et_builder' ),
			),
			'font_icon' => array(
				'label'               => esc_html__( 'Icon', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'basic_option',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'description'         => esc_html__( 'Choose an icon to display with your minimenu_item.', 'et_builder' ),
				'depends_default'     => true,
			),
			'icon_color' => array(
				'label'             => esc_html__( 'Icon Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'et_builder' ),
				'depends_default'   => true,
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
			),
			'circle_color' => array(
				'label'           => esc_html__( 'Circle Color', 'et_builder' ),
				'type'            => 'color',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle.', 'et_builder' ),
				'depends_default' => true,
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
			),
			'circle_border_color' => array(
				'label'           => esc_html__( 'Circle Border Color', 'et_builder' ),
				'type'            => 'color',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle border.', 'et_builder' ),
				'depends_default' => true,
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'depends_show_if'    => 'off',
				'description'        => esc_html__( 'Upload an image to display at the top of your minimenu_item.', 'et_builder' ),
			),
			'alt' => array(
				'label'           => esc_html__( 'Image Alt Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the HTML ALT text for your image here.', 'et_builder' ),
				'depends_show_if' => 'off',
			),
			'icon_placement' => array(
				'label'             => esc_html__( 'Image/Icon Placement', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => $image_icon_placement,
				'description'       => esc_html__( 'Here you can choose where to place the icon.', 'et_builder' ),
			),
			'horizontal_align' => array(
				'label'             => esc_html__( 'Horizontal Alignment', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'center'    => esc_html__( 'Center', 'et_builder' ),
					'left'   	=> esc_html__( 'Left', 'et_builder' ),
					'right'  	=> esc_html__( 'Right', 'et_builder' ),
				),
				'description'       => esc_html__( 'Align icon/image and text horizontally, this is only applicable if icon placment is set to Top.', 'et_builder' ),
			),
			'vertical_align' => array(
				'label'             => esc_html__( 'Vertical Alignment', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'top'    => esc_html__( 'Top', 'et_builder' ),
					'middle'   	=> esc_html__( 'Middle', 'et_builder' ),
					'baseline'  	=> esc_html__( 'Bottom', 'et_builder' ),
				),
				'description'       => esc_html__( 'Align icon/image and text vertically, this is only applicable if icon placment is set to Left/Right.', 'et_builder' ),
			),
			'animation' => array(
				'label'             => esc_html__( 'Image/Icon Animation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'top'    => esc_html__( 'Top To Bottom', 'et_builder' ),
					'left'   => esc_html__( 'Left To Right', 'et_builder' ),
					'right'  => esc_html__( 'Right To Left', 'et_builder' ),
					'bottom' => esc_html__( 'Bottom To Top', 'et_builder' ),
					'off'    => esc_html__( 'No Animation', 'et_builder' ),
				),
				'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
			),
			'max_width' => array(
				'label'           => esc_html__( 'Image Max Width', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'mobile_options'  => true,
				'validate_unit'   => true,
			),
			'use_icon_font_size' => array(
				'label'           => esc_html__( 'Use Icon Font Size', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'font_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'icon_font_size',
				),
				'tab_slug' => 'advanced',
			),
			'icon_font_size_last_edited' => array(
				'type'     => 'skip',
				'tab_slug' => 'advanced',
			),
			'icon_font_size' => array(
				'label'           => esc_html__( 'Icon Font Size', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'default'         => '96px',
				'range_settings' => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'mobile_options'  => true,
				'depends_default' => true,
			),
			'max_width_tablet' => array (
				'type'     => 'skip',
				'tab_slug' => 'advanced',
			),
			'max_width_phone' => array (
				'type'     => 'skip',
				'tab_slug' => 'advanced',
			),
			'max_width_last_edited' => array(
				'type'     => 'skip',
				'tab_slug' => 'advanced',
			),
			'icon_font_size_tablet' => array(
				'type'     => 'skip',
				'tab_slug' => 'advanced',
			),
			'icon_font_size_phone' => array(
				'type'     => 'skip',
				'tab_slug' => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$title   			   = $this->shortcode_atts['title'];
		$url   			   = $this->shortcode_atts['url'];
		$image                 = $this->shortcode_atts['image'];
		$alt                   = $this->shortcode_atts['alt'];
		$animation             = $this->shortcode_atts['animation'];
		$icon_placement        = $this->shortcode_atts['icon_placement'];
		$font_icon             = $this->shortcode_atts['font_icon'];
		$use_icon              = $this->shortcode_atts['use_icon'];
		$use_circle            = $this->shortcode_atts['use_circle'];
		$use_circle_border     = $this->shortcode_atts['use_circle_border'];
		$icon_color            = $this->shortcode_atts['icon_color'];
		$circle_color          = $this->shortcode_atts['circle_color'];
		$circle_border_color   = $this->shortcode_atts['circle_border_color'];
		$max_width             = $this->shortcode_atts['max_width'];
		$max_width_tablet      = $this->shortcode_atts['max_width_tablet'];
		$max_width_phone       = $this->shortcode_atts['max_width_phone'];
		$max_width_last_edited = $this->shortcode_atts['max_width_last_edited'];
		$use_icon_font_size    = $this->shortcode_atts['use_icon_font_size'];
		$icon_font_size        = $this->shortcode_atts['icon_font_size'];
		$icon_font_size_tablet = $this->shortcode_atts['icon_font_size_tablet'];
		$icon_font_size_phone  = $this->shortcode_atts['icon_font_size_phone'];
		$icon_font_size_last_edited  = $this->shortcode_atts['icon_font_size_last_edited'];
		$horizontal_align  			 = $this->shortcode_atts['horizontal_align'];
		$vertical_align  			 = $this->shortcode_atts['vertical_align'];


		if ( 'off' !== $use_icon_font_size ) {
			$font_size_responsive_active = et_pb_get_responsive_status( $icon_font_size_last_edited );

			$font_size_values = array(
				'desktop' => $icon_font_size,
				'tablet'  => $font_size_responsive_active ? $icon_font_size_tablet : '',
				'phone'   => $font_size_responsive_active ? $icon_font_size_phone : '',
			);

			et_pb_generate_responsive_css( $font_size_values, '%%order_class%% .et-pb-icon', 'font-size', $function_name );
		}

		if ( '' !== $max_width_tablet || '' !== $max_width_phone || '' !== $max_width ) {
			$max_width_responsive_active = et_pb_get_responsive_status( $max_width_last_edited );

			$max_width_values = array(
				'desktop' => $max_width,
				'tablet'  => $max_width_responsive_active ? $max_width_tablet : '',
				'phone'   => $max_width_responsive_active ? $max_width_phone : '',
			);

			$additional_css = ' !important;';
			et_pb_generate_responsive_css( $max_width_values, '%%order_class%%.et_pb_minimenu_item img', 'max-width', $function_name, $additional_css );
		}

		if ( is_rtl() && 'left' === $icon_placement ) {
			$icon_placement = 'right';
		}


		if ( '' !== $title && '' !== $url ) {
			$title = sprintf( '<p>%1$s</p>',
				esc_html( $title )
				
			);
		}

		if ( '' !== $title ) {
			$title = "{$title}";
		}

		if ( 'off' === $use_icon ) {
			$image = ( '' !== trim( $image ) ) ? sprintf(
				'<img src="%1$s" alt="%2$s" class="et-waypoint%3$s"%4$s />',
				esc_url( $image ),
				esc_attr( $alt ),
				esc_attr( " et_pb_animation_{$animation}" ),
				( 'left' === $icon_placement || 'right' === $icon_placement ? 'style="display: table-cell;"' : '')
			) : '';
		} else {
			$icon_style = sprintf( 'color: %1$s;', esc_attr( $icon_color ) );

			if ( 'on' === $use_circle ) {
				$icon_style .= sprintf( ' background-color: %1$s;', esc_attr( $circle_color ) );

				if ( 'on' === $use_circle_border ) {
					$icon_style .= sprintf( ' border-color: %1$s;', esc_attr( $circle_border_color ) );
				}
			}

			$image = ( '' !== $font_icon ) ? sprintf(
				'<span class="et-pb-icon et-waypoint%2$s%3$s%4$s" style="%5$s">%1$s</span>',
				esc_attr( et_pb_process_font_icon( $font_icon ) ),
				esc_attr( " et_pb_animation_{$animation}" ),
				( 'on' === $use_circle ? ' et-pb-icon-circle' : '' ),
				( 'on' === $use_circle && 'on' === $use_circle_border ? ' et-pb-icon-circle-border' : '' ),
				$icon_style
			) : '';
		}
		$image = $image ? sprintf(
			'%1$s',
			( '' !== $url
				? sprintf(
					'%1$s',
					$image
				)
				: $image
			)
		) : '';

		global $cwp_et_pb_menu_item_num;


		$cwp_et_pb_menu_item_num++;

		$hide_on_mobile_class = self::HIDE_ON_MOBILE;
		$class= '';
		$class = ET_Builder_Element::add_module_order_class( $class, $function_name );
		
		if ( 'top' === $icon_placement && '' !== $horizontal_align ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_minimenu_item',
				'declaration' => sprintf(
					'text-align: %1$s',
					esc_html( $horizontal_align )
				),
			) );
		}
		if ( 'top' !== $icon_placement && '' !== $vertical_align ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%.et_pb_minimenu_item p',
						'declaration' => sprintf(
							'vertical-align: %1$s !important;',
							esc_html( $vertical_align )
						),
					) );
				}
		if ( 'top' !== $icon_placement ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_minimenu_item p',
				'declaration' => 'display: table-cell;'
			) );
		}

		$output = sprintf(
			'<li class="et_pb_minimenu_item%4$s">
				<a href="%8$s"%9$s>
					%7$s
				</a>
			</li> <!-- .et_pb_minimenu_item -->',
			( 'left' === $icon_placement || 'right' === $icon_placement ? 'style="display: table-cell;"' : ''),
			$image,
			$title,
			esc_attr( $class ),
			'',
			'',
			( 'right' === $icon_placement ? 
				sprintf(
					'%1$s
					%2$s',
					$title,
					$image
					) :
					sprintf('%1$s
						%2$s',
					$image,					
					$title
					)
					),
			$url,
			'style="display:table;"'

		);

		return $output;
	}

		}

		$cwp_et_pb_mini_menu_items = new CWP_ET_Builder_Module_AS_Menu_Item;
		add_shortcode( 'cwp_et_pb_mini_menu_items', array($cwp_et_pb_mini_menu_items, '_shortcode_callback') );
?>