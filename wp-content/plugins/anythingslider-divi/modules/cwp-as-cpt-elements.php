<?php

class CWP_Module_AS_CPT_Elements extends ET_Builder_Module {
	function init() {
		$this->name             = esc_html__( 'AS CPT Elements', 'et_builder' );
		$this->slug             = 'cwp_et_pb_as_cpt_elements';
		//$this->fb_support       = true;
		$this->defaults         = array();
		$this->featured_image_background = true;

		$this->whitelisted_fields = array(
			'title',
			'link_title',
			'meta',
			'author',
			'date',
			'date_format',
			'categories',
			'comments',
			'featured_image',
			'featured_placement',
			'image_size',
			'link_image',
			'min_height_bg',
			'force_fullwidth',
			'cpt_content',
			'content_display',
			'excerpt_length',
			'show_more_button',
			'more_text',
			'text_color',
			'text_background',
			'text_bg_color',
			'vertical_align',
			'admin_label',
			'module_id',
			'module_class',
			'show_content_on_mobile',
			'show_title_on_mobile',
			'show_meta_on_mobile',
			'show_more_button_on_mobile',
			'show_featured_image_on_mobile',
		);

		$this->fields_defaults = array(
			'title'              => array( 'on' ),
			'link_title'         => array( 'off', 'add_default_setting'  ),
			'meta'               => array( 'on' ),
			'author'             => array( 'on' ),
			'date'               => array( 'on' ),
			'date_format'        => array( 'M j, Y' ),
			'categories'         => array( 'on' ),
			'comments'           => array( 'on' ),
			'featured_image'     => array( 'on' ),
			'featured_placement' => array( 'below' ),
			'image_size' 		 => array( 'large', 'add_default_setting' ),
			'link_image' 		 => array( 'off', 'add_default_setting'  ),
			'min_height_bg' 	 => array( 'on' ),
			'force_fullwidth' 	 => array( 'on', 'add_default_setting' ),
			'cpt_content'        => array( 'off' ),
			'excerpt_length'     => array( '270' ),
			'show_more_button'   => array( 'on' ),
			'more_text'          => array( 'Read More' ),
			'parallax'           => array( 'off' ),
			'parallax_method'    => array( 'on' ),
			'text_orientation'   => array( 'left' ),
			'text_color'         => array( 'dark' ),
			'text_background'    => array( 'off' ),
			'text_bg_color'      => array( 'rgba(255,255,255,0.9)', 'only_default_setting' ),
			'vertical_align'      => array( 'flex-start' ),
			'show_content_on_mobile'  => array( 'on' ),
			'show_title_on_mobile'    => array( 'on' ),
			'show_meta_on_mobile' 	  => array( 'on' ),
			'show_more_button_on_mobile'=> array( 'on' ),
			'show_featured_image_on_mobile' => array( 'on' ),
		);

		$this->main_css_element = '%%order_class%%';

		$this->options_toggles = array(
			'general'  => array(
				'toggles' => array(
					'elements'   => esc_html__( 'Elements', 'et_builder' ),
					'background' => esc_html__( 'Background', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text'     => array(
						'title'    => esc_html__( 'Text', 'et_builder' ),
						'priority' => 49,
					),
					'sizing'     => array(
						'title'    => esc_html__( 'Sizing', 'et_builder' ),
						'priority' => 50,
					),
				),
			),
		);

		$this->advanced_options = array(
			'border' => array(
				'css' => array(
					'main' => array(
						'border_radii'  => "{$this->main_css_element}.as_et_pb_featured_bg, {$this->main_css_element}",
						'border_styles' => "{$this->main_css_element}.as_et_pb_featured_bg, {$this->main_css_element}",
					),
				),
			),
			'custom_margin_padding' => array(
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_elements_container",
					'important' => 'all',
				),
			),
			'fonts' => array(
				'title' => array(
					'label'    => esc_html__( 'Title', 'et_builder' ),
					'use_all_caps' => true,
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_elements_container h1.entry-title, {$this->main_css_element} .et_pb_elements_container h2.entry-title, {$this->main_css_element} .et_pb_elements_container h3.entry-title, {$this->main_css_element} .et_pb_elements_container h4.entry-title, {$this->main_css_element} .et_pb_elements_container h5.entry-title, {$this->main_css_element} .et_pb_elements_container h6.entry-title",
					),
					'header_level' => array(
						'default' => 'h1',
					),
				),
				'meta'   => array(
					'label'    => esc_html__( 'Meta', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_elements_container .et_pb_title_meta_container, {$this->main_css_element} .et_pb_elements_container .et_pb_title_meta_container a",
						'plugin_main' => "{$this->main_css_element} .et_pb_elements_container .et_pb_title_meta_container, {$this->main_css_element} .et_pb_elements_container .et_pb_title_meta_container a, {$this->main_css_element} .et_pb_elements_container .et_pb_title_meta_container span",
					),
				),
				'content' => array(
					'label'    => esc_html__( 'Content/Excerpt', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .post-content",
					),
				),
			),
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Button', 'et_builder' ),
					'css' => array(
						'plugin_main' => "{$this->main_css_element} .et_pb_more_button.et_pb_button",
						'alignment' => "{$this->main_css_element} .et_pb_button_wrapper",
					),
					'use_alignment' => true,
				),
			),
			'background' => array(
				'css' => array(
					'main' => "{$this->main_css_element}, {$this->main_css_element}.as_et_pb_featured_bg",
				),
			),
			'max_width' => array(
				'toggle_slug'     => 'sizing',
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_elements_container",
					'module_alignment' => "{$this->main_css_element} .et_pb_elements_container",
				),
			),
			'text'     => array(),
			'filters' => array(),
		);
		$this->custom_css_options = array(
			'post_title' => array(
				'label'    => esc_html__( 'Title', 'et_builder' ),
				'selector' => 'h1',
			),
			'post_meta' => array(
				'label'    => esc_html__( 'Meta', 'et_builder' ),
				'selector' => '.et_pb_title_meta_container',
			),
			'post_image' => array(
				'label'    => esc_html__( 'Featured Image', 'et_builder' ),
				'selector' => '.cwp_et_pb_title_featured_container',
			),
		);

	}

	function get_fields() {
		//Vertical Align Options
		$vertical_align_options = array(
			'flex-start'=>'Top',
			'center'=>'Center',
			'flex-end'=>'Bottom'
		);

		//Image Sizes.
		$options = array();
		$sizes = get_intermediate_image_sizes();

		foreach ($sizes as $size) {
			$options[$size] = $size;
		}

		$fields = array(
			'title' => array(
				'label'             => esc_html__( 'Show Title', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'toggle_slug'       => 'elements',
				'affects'           => array(
					'link_title',
				),
				'description'       => esc_html__( 'Here you can choose whether or not display the Post Title', 'et_builder' ),
			),
			'link_title' => array(
				'label'             => esc_html__( 'Link Title', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'toggle_slug'       => 'elements',
			),
			'meta' => array(
				'label'             => esc_html__( 'Show Meta', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'author',
					'date',
					'categories',
					'comments',
				),
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can choose whether or not display the Post Meta', 'et_builder' ),
			),
			'author' => array(
				'label'             => esc_html__( 'Show Author', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can choose whether or not display the Author Name in Post Meta', 'et_builder' ),
			),
			'date' => array(
				'label'             => esc_html__( 'Show Date', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'affects'           => array(
					'date_format'
				),
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can choose whether or not display the Date in Post Meta', 'et_builder' ),
			),
			'date_format' => array(
				'label'             => esc_html__( 'Date Format', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_show_if'   => 'on',
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can define the Date Format in Post Meta. Default is \'M j, Y\'', 'et_builder' ),
			),
			'categories' => array(
				'label'             => esc_html__( 'Show Post Categories', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can choose whether or not display the Categories in Post Meta. Note: This option doesn\'t work with custom post types.', 'et_builder' ),
			),
			'comments' => array(
				'label'             => esc_html__( 'Show Comments Count', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can choose whether or not display the Comments Count in Post Meta.', 'et_builder' ),
			),
			'featured_image' => array(
				'label'             => esc_html__( 'Show Featured Image', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'featured_placement',
					'force_fullwidth',
				),
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can choose whether or not display the Featured Image', 'et_builder' ),
			),
			'featured_placement' => array(
				'label'             => esc_html__( 'Featured Image Placement', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'below'      => esc_html__( 'Below Title', 'et_builder' ),
					'above'      => esc_html__( 'Above Title', 'et_builder' ),
					'background' => esc_html__( 'Title/Meta Background Image', 'et_builder' ),
				),
				'depends_show_if'   => 'on',
				'affects'           => array(
					'min_height_bg',
					'image_size',
					'link_image',
				),
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can choose where to place the Featured Image', 'et_builder' ),
			),
			'image_size' => array(
	            'label'           => __( 'Image Size', 'et_builder' ),
	            'type'            => 'select',
	            'options'         => $options,
	            'toggle_slug'       => 'elements',
	            'depends_show_if_not' => 'background',
	            'description'       => __( 'Choose a size for the featured image.', 'et_builder' ),
			),
			'link_image' => array(
				'label'             => esc_html__( 'Link Image', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'depends_show_if_not' => 'background',
				'toggle_slug'       => 'elements',
			),
			'min_height_bg' => array(
				'label'             => esc_html__( 'Min Height Background', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'depends_show_if'   => 'background',
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Enable this to sync the background div height with the CPT slider module\'s min height.', 'et_builder' ),
			),
			'force_fullwidth' => array(
			    'label'             => esc_html__( 'Force Fullwidth', 'et_builder' ),
			    'type'              => 'yes_no_button',
			    'option_category'   => 'layout',
			    'options'           => array(
			            'off' => esc_html__( "No", 'et_builder' ),
			            'on'  => esc_html__( 'Yes', 'et_builder' ),
			    ),
				'toggle_slug'       => 'elements',
				'depends_show_if'   => 'on',
			),
			'cpt_content' => array(
				'label'             => esc_html__( 'Content', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on' => esc_html__( 'Yes', 'et_builder' ),
					'off'  => esc_html__( 'No', 'et_builder' ),
				),
				'affects'           => array(
					'content_display',
				),
				'toggle_slug'       => 'elements',
				'description'        => esc_html__( 'Here you can choose whether or not display the Content.', 'et_builder' ),
			),
			'content_display' => array(
				'label'             => esc_html__( 'Content Display', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'excerpt' => esc_html__( 'Show Excerpt', 'et_builder' ),
					'full_content'  => esc_html__( 'Show Content', 'et_builder' ),
				),
				'affects'           => array(
					'excerpt_length',
				),
				'toggle_slug'       => 'elements',
				'description'        => esc_html__( 'Showing the full content will not truncate your posts on the index page. Showing the excerpt will only display your excerpt text.', 'et_builder' ),
			),
			'excerpt_length' => array(
				'label'             => esc_html__( 'Excerpt Length', 'et_builder' ),
				'type'              => 'text',
				'depends_show_if'   => 'excerpt',
				'toggle_slug'       => 'elements',
				'description'        => esc_html__( 'Control length of Excerpt here.', 'et_builder' ),
			),
			'show_more_button' => array(
				'label'             => esc_html__( 'Show Read More Button', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'on'  => esc_html__( 'yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'affects' => array(
					'more_text',
				),
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'This setting will turn on and off the read more button.', 'et_builder' ),
			),
			'more_text' => array(
				'label'             => esc_html__( 'Button Text', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'configuration',
				'depends_show_if'   => 'on',
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Define the text which will be displayed on "Read More" button. leave blank for default ( Read More )', 'et_builder' ),
			),
			'text_color' => array(
				'label'             => esc_html__( 'Text Color', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'color_option',
				'options'           => array(
					'dark'  => esc_html__( 'Dark', 'et_builder' ),
					'light' => esc_html__( 'Light', 'et_builder' ),
				),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'text',
				'description'       => esc_html__( 'Here you can choose the color for the Title/Meta text', 'et_builder' ),
			),
			'text_background' => array(
				'label'             => esc_html__( 'Use Text Background Color', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'color_option',
				'options'           => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'           => array(
					'text_bg_color',
				),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'text',
				'description'       => esc_html__( 'Here you can choose whether or not use the background color for the Title/Meta text', 'et_builder' ),
			),
			'text_bg_color' => array(
				'label'             => esc_html__( 'Text Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'depends_show_if'   => 'on',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'text',
			),
			'vertical_align' => array(
					'label'           => __( 'Vertical Align', 'et_builder' ),
					'type'            => 'select',
					'options'         => $vertical_align_options,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'sizing',
					// 'depends_show_if' => 'content',
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
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
				'toggle_slug' => 'admin_label',
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'classes',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'classes',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'show_content_on_mobile' => array(
				'label'           => esc_html__( 'Show Content On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
			'show_title_on_mobile' => array(
				'label'           => esc_html__( 'Show Title On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
			'show_meta_on_mobile' => array(
				'label'           => esc_html__( 'Show Meta On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
			'show_more_button_on_mobile' => array(
				'label'           => esc_html__( 'Show More Button On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
			'show_featured_image_on_mobile' => array(
				'label'           => esc_html__( 'Show Featured Image On Mobile', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
		);

		return $fields;
	}

	//Additional Max Width CSS to also set as 'Width'
	function get_max_width_additional_css() {

		isset( $this->shortcode_atts['max_width'] ) ? $additional_css = '; width: '. $this->shortcode_atts['max_width'] . ';' : '' ;

		return $additional_css;
	}


	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id          = $this->shortcode_atts['module_id'];
		$module_class       = $this->shortcode_atts['module_class'];
		$title              = $this->shortcode_atts['title'];
		$link_title         = $this->shortcode_atts['link_title'];
		$meta               = $this->shortcode_atts['meta'];
		$author             = $this->shortcode_atts['author'];
		$date               = $this->shortcode_atts['date'];
		$date_format        = $this->shortcode_atts['date_format'];
		$categories         = $this->shortcode_atts['categories'];
		$comments           = $this->shortcode_atts['comments'];
		$featured_image     = $this->shortcode_atts['featured_image'];
		$image_size         = $this->shortcode_atts['image_size'];
		$link_image         = $this->shortcode_atts['link_image'];
		$featured_placement = $this->shortcode_atts['featured_placement'];
		$min_height_bg 		= $this->shortcode_atts['min_height_bg'];
		$force_fullwidth 	= $this->shortcode_atts['force_fullwidth'];
		$cpt_content        = $this->shortcode_atts['cpt_content'];
		$content_display    = $this->shortcode_atts['content_display'];
		$excerpt_length     = $this->shortcode_atts['excerpt_length'];
		$show_more_button   = $this->shortcode_atts['show_more_button'];
		$more_text          = $this->shortcode_atts['more_text'];
		$button_custom      = $this->shortcode_atts['custom_button'];
		$custom_icon        = $this->shortcode_atts['button_icon'];
		$button_rel         = $this->shortcode_atts['button_rel'];
		$text_color         = $this->shortcode_atts['text_color'];
		$text_background    = $this->shortcode_atts['text_background'];
		$text_bg_color      = $this->shortcode_atts['text_bg_color'];
		$header_level       = $this->shortcode_atts['title_level'];
		$vertical_align     = $this->shortcode_atts['vertical_align'];
		$show_content_on_mobile  = $this->shortcode_atts['show_content_on_mobile'];
		$show_title_on_mobile  = $this->shortcode_atts['show_title_on_mobile'];
		$show_meta_on_mobile  = $this->shortcode_atts['show_meta_on_mobile'];
		$show_more_button_on_mobile  = $this->shortcode_atts['show_more_button_on_mobile'];
		$show_featured_image_on_mobile  = $this->shortcode_atts['show_featured_image_on_mobile'];


		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
		$this->process_additional_options( $function_name );

		$hide_on_mobile_class = self::HIDE_ON_MOBILE;

		//FULL HEIGHT BACKGROUND CLASS.
		if ( 'background' === $featured_placement && 'on' === $min_height_bg ){
			$module_class .= ' cwp_as_min_height_bg';
		}

		//Force Ful Width if image placement is BG.
		if ( 'background' === $featured_placement && 'on' === $force_fullwidth ){
			ET_Builder_Element::set_style( $function_name, array(
			    'selector'    => '%%order_class%%',
			    'declaration' => 'width: 100%;',
			) );
		}

		$output = '';
		$featured_image_output = '';
		$parallax_image_background = $this->get_parallax_image_background();
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $image_size );
		$img_src = $image[0];
		$img_alt = get_the_title();
		$img_title = get_the_title();

		if ( 'on' === $featured_image && ( 'above' === $featured_placement || 'below' === $featured_placement ) ) {


			$featured_image_output = sprintf( '
				<div class="cwp_et_pb_title_featured_container%4$s">
				%5$s
				<img src="%1$s" alt="%2$s" title="%3$s" />
				%6$s
				</div>',
				$img_src,
				$img_alt,
				$img_title,
				'on' !== $show_featured_image_on_mobile ? esc_attr( " {$hide_on_mobile_class}" ) : '',
				'on' === $link_image ? sprintf('<a href="%1$s">', esc_url( get_permalink() )) : '',
				'on' === $link_image ? '</a>' : ''
			);
			//Force Ful Width
			if ( 'on' === $force_fullwidth ) {
				ET_Builder_Element::set_style( $function_name, array(
				    'selector'    => '%%order_class%% img',
				    'declaration' => 'width: 100%;',
				) );
			}
		}

		

		if ( 'on' === $title ) {
			if ( is_et_pb_preview() && isset( $_POST['post_title'] ) && wp_verify_nonce( $_POST['et_pb_preview_nonce'], 'et_pb_preview_nonce' ) ) {
				$post_title = sanitize_text_field( wp_unslash( $_POST['post_title'] ) );
			} else {
				$post_title = get_the_title();
			}

			$output .= sprintf( '%4$s<%2$s class="entry-title%3$s">%s</%2$s>%5$s',
				$post_title,
				et_pb_process_header_level( $header_level, 'h1' ),
				'on' !== $show_title_on_mobile ? esc_attr( " {$hide_on_mobile_class}" ) : '',
				'on' === $link_title ? sprintf('<a href="%1$s">', esc_url( get_permalink() )) : '',
				'on' === $link_image ? '</a>' : ''
			);
		}

		if ( 'on' === $meta ) {
			$meta_array = array();
			foreach( array( 'author', 'date', 'categories', 'comments' ) as $single_meta ) {
				if ( 'on' === $$single_meta && ( 'categories' !== $single_meta || ( 'categories' === $single_meta && is_singular( 'post' ) ) ) ) {
					 $meta_array[] = $single_meta;
				}
			}

			$output .= sprintf( '<p class="et_pb_title_meta_container%2$s">%1$s</p>',
				et_pb_postinfo_meta( $meta_array, $date_format, esc_html__( '0 comments', 'et_builder' ), esc_html__( '1 comment', 'et_builder' ), '% ' . esc_html__( 'comments', 'et_builder' ) ),
				'on' !== $show_meta_on_mobile ? esc_attr( " {$hide_on_mobile_class}" ) : ''
			);
		}

		$post_content = '';
		if ( 'on' === $cpt_content ) {
			if ( 'full_content' === $content_display ) {
				
				//$post_content = apply_filters('the_content', get_the_content());
				$post_content = apply_filters( 'the_content', et_delete_post_first_video( get_the_content( esc_html__( '', 'et_builder' ) ) ) );
			} else {
				ob_start();
                //the_excerpt();
                $post_content = wpautop( et_delete_post_first_video( strip_shortcodes( truncate_post( $excerpt_length, false, '', true ) ) ) );
                //$post_content = ob_get_clean();
                ob_end_clean();
			}
		}

		$output .= sprintf( '<div class="post-content%2$s">%1$s</div>',
			$post_content,
			'on' !== $show_content_on_mobile ? esc_attr( " {$hide_on_mobile_class}" ) : ''
		);


		if ( 'off' !== $show_more_button && '' !== $more_text ) {
			$output .= sprintf(
				'<div class="et_pb_button_wrapper"><a href="%1$s" class="et_pb_more_button et_pb_button%4$s%5$s"%3$s%6$s>%2$s</a></div>',
				esc_url( get_permalink() ),
				esc_html( $more_text ),
				'' !== $custom_icon && 'on' === $button_custom ? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $custom_icon ) )
				) : '',
				'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
				'on' !== $show_more_button_on_mobile ? esc_attr( " {$hide_on_mobile_class}" ) : '',
				$this->get_rel_attributes( $button_rel )
			);
		}

		//Vertical Alignment.
		ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%',
						'declaration' => 'display: flex; flex-direction: column;'
		) );	
		
		if ( 'center' === $vertical_align ) {

			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_elements_container',
				'declaration' => 'margin: auto 0 !important;'
			) );	

		}
		if ( 'flex-end' === $vertical_align ) {

			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_elements_container',
				'declaration' => 'margin-top: auto !important;'
			) );	

		}


		if ( 'on' === $text_background ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_pb_elements_container',
				'declaration' => sprintf(
					'background-color: %1$s; padding: 1em 1.5em;',
					esc_html( $text_bg_color )
				),
			) );
		}

		$video_background = $this->video_background();

		$background_layout = 'dark' === $text_color ? 'light' : 'dark';
		$module_class .= ' et_pb_bg_layout_' . $background_layout;
		$module_class .= ' cwp_cpt_elements';

		$output = sprintf(
			'<div%3$s class="et_pb_module et_pb_post_title %2$s%4$s%8$s%10$s%11$s">
				%5$s
				%9$s
				%6$s
				<div class="et_pb_elements_container">
					%1$s
				</div>
				%7$s
			</div>',
			$output,
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			'on' === $featured_image && 'background' === $featured_placement ? ' as_et_pb_featured_bg' : '',
			$parallax_image_background,
			'on' === $featured_image && 'above' === $featured_placement ? $featured_image_output : '',
			'on' === $featured_image && 'below' === $featured_placement ? $featured_image_output : '',
			'' !== $video_background ? ' et_pb_section_video et_pb_preload' : '',
			$video_background,
			'' !== $parallax_image_background ? ' et_pb_section_parallax' : '',
			$this->get_text_orientation_classname()
		);

		return $output;
	}

	protected function _add_additional_border_fields() {
		parent::_add_additional_border_fields();

		$this->advanced_options['border']['css'] = array(
			'main' => array(
				'border_radii'  => "{$this->main_css_element}.as_et_pb_featured_bg, {$this->main_css_element}",
				'border_styles' => "{$this->main_css_element}.as_et_pb_featured_bg, {$this->main_css_element}",
			)
		);
	}
}

new CWP_Module_AS_CPT_Elements;