<?php
/**
 * Extends Divi's Visual Builder.
 *
 * @package Popups_For_Divi
 */

/**
 * Ajax handler.
 */
class Popups_For_Divi_Editor {
	/**
	 * The main application instanc.
	 *
	 * @var Popups_For_Divi
	 */
	public $app = null;

	/**
	 * Called during the "plugins_loaded" action to add relevant hooks.
	 *
	 * @since  1.2.0
	 * @param  Popups_For_Divi $app The main application instance.
	 * @return void
	 */
	public function __construct( $app ) {
		$this->app = $app;

		add_action(
			'et_builder_framework_loaded',
			[ $this, 'add_hooks' ]
		);

		add_filter(
			'et_builder_get_parent_modules',
			[ $this, 'add_toggles_to_tab' ],
			10, 2
		);

		// Pre-processes the Divi section settings before they are actually saved.
		add_action(
			'wp_ajax_et_fb_ajax_save',
			[ $this, 'et_fb_ajax_save' ],
			1
		);
	}

	/**
	 * Add the Visual Builder hooks when not editing a Divi Area post.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function add_hooks() {
		add_filter(
			'et_pb_all_fields_unprocessed_et_pb_section',
			[ $this, 'add_section_confg' ]
		);

		// Todo: This filter is deprecated. Find a better way!
		add_filter(
			'et_builder_main_tabs',
			[ $this, 'add_tab' ],
			1
		);
	}

	/**
	 * Extends the configuration fields of a Divi SECTION.
	 *
	 * @filter et_pb_all_fields_unprocessed_et_pb_section
	 *
	 * @since 1.2.0
	 * @param array $fields_unprocessed Field definitions of the module.
	 * @return array The modified configuration fields.
	 */
	public function add_section_confg( $fields_unprocessed ) {
		$fields = [];

		// "General" toggle.
		$fields['da_is_popup']   = [
			'label'           => esc_html__( 'This is a Popup', 'divi-popup' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => [
				'off' => esc_html__( 'No', 'divi-popup' ),
				'on'  => esc_html__( 'Yes', 'divi-popup' ),
			],
			'default'         => 'off',
			'description'     => esc_html__( 'Turn this section into aa Divi Popup section.', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_general',
		];
		$fields['da_popup_slug'] = [
			'label'           => esc_html__( 'Popup ID', 'divi-popup' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'Assign a unique ID to the Popup. You can display this Popup by using this name in an anchor link, like "#slug".', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_general',
			'show_if'         => [
				'da_is_popup' => 'on',
			],
		];

		// "Behavior" toggle.
		$fields['da_not_modal']   = [
			'label'           => esc_html__( 'Close on Background-Click', 'divi-popup' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => [
				'on'  => esc_html__( 'Yes', 'divi-popup' ),
				'off' => esc_html__( 'No', 'divi-popup' ),
			],
			'default'         => 'on',
			'description'     => esc_html__( 'Here you can decide whether the Popup can be closed by clicking somewhere outside the Popup. When this option is disabled, the Popup can only be closed via a Close Button or pressing the ESC key on the keyboard.', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_behavior',
			'show_if'         => [
				'da_is_popup' => 'on',
			],
		];
		$fields['da_is_singular'] = [
			'label'           => esc_html__( 'Close other Popups', 'divi-popup' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => [
				'on'  => esc_html__( 'Yes', 'divi-popup' ),
				'off' => esc_html__( 'No', 'divi-popup' ),
			],
			'default'         => 'off',
			'description'     => esc_html__( 'Here you can decide whether this Popup should automatically close all other Popups when it is opened.', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_behavior',
			'show_if'         => [
				'da_is_popup' => 'on',
			],
		];
		$fields['da_exit_intent'] = [
			'label'           => esc_html__( 'Enable Exit Intent', 'divi-popup' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => [
				'on'  => esc_html__( 'Yes', 'divi-popup' ),
				'off' => esc_html__( 'No', 'divi-popup' ),
			],
			'default'         => 'off',
			'description'     => esc_html__( 'When you enable the Exit Intent trigger, this Popup is automatically opened before the user leaves the current webpage. Note that the Exit Intent only works on desktop browsers, not on touch devices.', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_behavior',
			'show_if'         => [
				'da_is_popup' => 'on',
			],
		];

		// "Close Button" toggle.
		$fields['da_has_close']  = [
			'label'           => esc_html__( 'Show Close Button', 'divi-popup' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => [
				'on'  => esc_html__( 'Yes', 'divi-popup' ),
				'off' => esc_html__( 'No', 'divi-popup' ),
			],
			'default'         => 'on',
			'description'     => esc_html__( 'Do you want to display the default Close button in the top-right corner of the Popup?', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_close',
			'show_if'         => [
				'da_is_popup' => 'on',
			],
		];
		$fields['da_dark_close'] = [
			'label'           => esc_html__( 'Button Color', 'divi-popup' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options'         => [
				'on'  => esc_html__( 'Light', 'divi-popup' ),
				'off' => esc_html__( 'Dark', 'divi-popup' ),
			],
			'default'         => 'off',
			'description'     => esc_html__( 'Here you can choose whether the Close button should be dark or light?. If the section has a light backgound, use a dark button. When the background is dark, use a light button.', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_close',
			'show_if'         => [
				'da_is_popup'  => 'on',
				'da_has_close' => 'on',
			],
		];
		$fields['da_alt_close']  = [
			'label'           => esc_html__( 'Transparent Background', 'divi-popup' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => [
				'on'  => esc_html__( 'Yes', 'divi-popup' ),
				'off' => esc_html__( 'No', 'divi-popup' ),
			],
			'default'         => 'off',
			'description'     => esc_html__( 'Here you can choose whether the Close button has a Background color or only displays the Icon.', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_close',
			'show_if'         => [
				'da_is_popup'  => 'on',
				'da_has_close' => 'on',
			],
		];

		// "Layout" toggle.
		$fields['da_has_shadow'] = [
			'label'           => esc_html__( 'Add a default Shadow', 'divi-popup' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => [
				'on'  => esc_html__( 'Yes', 'divi-popup' ),
				'off' => esc_html__( 'No', 'divi-popup' ),
			],
			'default'         => 'on',
			'description'     => esc_html__( 'Decide whether you want to add a default shadow to your Popup. You should disable this option, when you set a custom Box-Shadow for this Section.', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_layout',
			'show_if'         => [
				'da_is_popup' => 'on',
			],
		];

		// "Visibility" toggle.
		$fields['da_disable_devices'] = [
			'label'           => esc_html__( 'Disable on', 'divi-popup' ),
			'type'            => 'multiple_checkboxes',
			'option_category' => 'configuration',
			'options'         => [
				'phone'   => esc_html__( 'Phone', 'divi-popup' ),
				'tablet'  => esc_html__( 'Tablet', 'divi-popup' ),
				'desktop' => esc_html__( 'Desktop', 'divi-popup' ),
			],
			'additional_att'  => 'disable_on',
			'description'     => esc_html__( 'This will disable the Popup on selected devices', 'divi-popup' ),
			'tab_slug'        => 'da',
			'toggle_slug'     => 'da_visibility',
			'show_if'         => [
				'da_is_popup' => 'on',
			],
		];

		return array_merge( $fields_unprocessed, $fields );
	}

	/**
	 * Register new Divi Area tab in the Visual Builder.
	 *
	 * @todo This filter is deprecated. What's the new way?
	 * @filter et_builder_main_tabs
	 *
	 * @since 1.2.0
	 * @param array $tabs List of tabs to display in the Visual Builder.
	 * @return array Modified list of tabs.
	 */
	public function add_tab( $tabs ) {
		$tabs['da'] = esc_html__( 'Popup', 'divi-popup' );

		return $tabs;
	}

	/**
	 * Add a custom POPUP toggle to the SECTION module.
	 *
	 * @filter et_builder_get_parent_modules
	 *
	 * @since 1.2.0
	 * @param array  $parent_modules List of all parent elements.
	 * @param string $post_type      The post type in editor.
	 * @return array Modified parent element definition.
	 */
	public function add_toggles_to_tab( $parent_modules, $post_type ) {
		if ( isset( $parent_modules['et_pb_section'] ) ) {
			$section = $parent_modules['et_pb_section'];

			$section->settings_modal_toggles['da'] = [
				'toggles' => [
					'da_general'    => [
						'title'    => __( 'General', 'divi-popup' ),
						'priority' => 10,
					],
					'da_behavior'   => [
						'title'    => __( 'Behavior', 'divi-popup' ),
						'priority' => 15,
					],
					'da_close'      => [
						'title'    => __( 'Close Button', 'divi-popup' ),
						'priority' => 20,
					],
					'da_layout'     => [
						'title'    => __( 'Layout', 'divi-popup' ),
						'priority' => 25,
					],
					'da_visibility' => [
						'title'    => __( 'Visibility', 'divi-popup' ),
						'priority' => 30,
					],
				],
			];

			/*
			This custom field actually supports the Visual Builder:
			VB support is provided in builder.js by observing the React state object.
			*/
			unset( $section->fields_unprocessed['da_is_popup']['vb_support'] );
			unset( $section->fields_unprocessed['da_popup_slug']['vb_support'] );
			unset( $section->fields_unprocessed['da_not_modal']['vb_support'] );
			unset( $section->fields_unprocessed['da_is_singular']['vb_support'] );
			unset( $section->fields_unprocessed['da_exit_intent']['vb_support'] );
			unset( $section->fields_unprocessed['da_has_close']['vb_support'] );
			unset( $section->fields_unprocessed['da_dark_close']['vb_support'] );
			unset( $section->fields_unprocessed['da_alt_close']['vb_support'] );
			unset( $section->fields_unprocessed['da_has_shadow']['vb_support'] );
			unset( $section->fields_unprocessed['da_disable_devices']['vb_support'] );
		}

		return $parent_modules;
	}

	/**
	 * Ajax handler that is called BEFORE the actual `et_fb_ajax_save` function in
	 * Divi. This function does not save anything but it sanitizes section
	 * attributes and sets popup classes.
	 *
	 * @action wp_ajax_et_fb_ajax_save
	 *
	 * @since 1.2.0
	 */
	public function et_fb_ajax_save() {
		/**
		 * We disable phpcs for the following block, so we can use the identical
		 * code that is used inside the Divi theme.
		 *
		 * @see et_fb_ajax_save() in themes/Divi/includes/builder/functions.php
		 */
		// phpcs:disable
		if (
			! isset( $_POST['et_fb_save_nonce'] ) ||
			! wp_verify_nonce( $_POST['et_fb_save_nonce'], 'et_fb_save_nonce' )
		) {
			return;
		}

		$post_id = absint( $_POST['post_id'] );

		if ( ! et_fb_current_user_can_save( $post_id, $_POST['options']['status'] ) ) {
			return;
		}

		// Fetch the builder attributes and sanitize them.
		$shortcode_data = json_decode( stripslashes( $_POST['modules'] ), true );
		// phpcs:enable

		// Popup defaults.
		$da_default = [
			'da_is_popup'        => 'off',
			'da_popup_slug'      => '',
			'da_exit_intent'     => 'off',
			'da_has_close'       => 'on',
			'da_alt_close'       => 'off',
			'da_dark_close'      => 'off',
			'da_not_modal'       => 'on',
			'da_is_singular'     => 'off',
			'da_has_shadow'      => 'on',
			'da_disable_devices' => [ 'off', 'off', 'off' ],
		];

		foreach ( $shortcode_data as $id => $item ) {
			$type = sanitize_text_field( $item['type'] );
			if ( 'et_pb_section' !== $type ) {
				continue;
			}
			$attrs   = $item['attrs'];
			$conf    = $da_default;
			$classes = [];

			if ( ! empty( $attrs['module_id'] ) ) {
				$conf['da_popup_slug'] = $attrs['module_id'];
			}
			if ( ! empty( $attrs['module_class'] ) ) {
				$classes = explode( ' ', $attrs['module_class'] );

				if ( in_array( 'popup', $classes, true ) ) {
					$conf['da_is_popup'] = 'on';
				}
				if ( in_array( 'on-exit', $classes, true ) ) {
					$conf['da_exit_intent'] = 'on';
				}
				if ( in_array( 'no-close', $classes, true ) ) {
					$conf['da_has_close'] = 'off';
				}
				if ( in_array( 'close-alt', $classes, true ) ) {
					$conf['da_alt_close'] = 'on';
				}
				if ( in_array( 'dark', $classes, true ) ) {
					$conf['da_dark_close'] = 'on';
				}
				if ( in_array( 'is-modal', $classes, true ) ) {
					$conf['da_not_modal'] = 'off';
				}
				if ( in_array( 'single', $classes, true ) ) {
					$conf['da_is_singular'] = 'on';
				}
				if ( in_array( 'no-shadow', $classes, true ) ) {
					$conf['da_has_shadow'] = 'off';
				}
				if ( in_array( 'not-mobile', $classes, true ) ) {
					$conf['da_disable_devices'][0] = 'on';
				}
				if ( in_array( 'not-tablet', $classes, true ) ) {
					$conf['da_disable_devices'][1] = 'on';
				}
				if ( in_array( 'not-desktop', $classes, true ) ) {
					$conf['da_disable_devices'][2] = 'on';
				}
			}

			// Set all missing Divi Area attributes with a default value.
			foreach ( $conf as $key => $def_value ) {
				if ( ! isset( $attrs[ $key ] ) ) {
					if ( 'da_disable_devices' === $key ) {
						$def_value = implode( '|', $def_value );
					}
					$attrs[ $key ] = $def_value;
				}
			}

			// Remove all functional classes from the section.
			$special_classes = [
				'popup',
				'on-exit',
				'no-close',
				'close-alt',
				'dark',
				'is-modal',
				'single',
				'no-shadow',
				'not-mobile',
				'not-tablet',
				'not-desktop',
			];

			$classes = array_diff( $classes, $special_classes );

			// Finally set the class to match all attributes.
			if ( 'on' === $attrs['da_is_popup'] ) {
				$classes[] = 'popup';

				if ( 'on' === $attrs['da_exit_intent'] ) {
					$classes[] = 'on-exit';
				}
				if ( 'on' !== $attrs['da_has_close'] ) {
					$classes[] = 'no-close';
				}
				if ( 'on' === $attrs['da_alt_close'] ) {
					$classes[] = 'close-alt';
				}
				if ( 'on' === $attrs['da_dark_close'] ) {
					$classes[] = 'dark';
				}
				if ( 'on' !== $attrs['da_not_modal'] ) {
					$classes[] = 'is-modal';
				}
				if ( 'on' === $attrs['da_is_singular'] ) {
					$classes[] = 'single';
				}
				if ( 'on' !== $attrs['da_has_shadow'] ) {
					$classes[] = 'no-shadow';
				}
				if ( 'on' === $attrs['da_disable_devices'][0] ) {
					$classes[] = 'not-mobile';
				}
				if ( 'on' === $attrs['da_disable_devices'][1] ) {
					$classes[] = 'not-tablet';
				}
				if ( 'on' === $attrs['da_disable_devices'][2] ) {
					$classes[] = 'not-desktop';
				}
			}

			if ( $attrs['da_popup_slug'] ) {
				$attrs['module_id'] = $attrs['da_popup_slug'];
			}
			if ( $classes ) {
				$attrs['module_class'] = implode( ' ', $classes );
			} else {
				unset( $attrs['module_class'] );
			}

			$shortcode_data[ $id ]['attrs'] = $attrs;
		}

		$_POST['modules'] = addslashes( wp_json_encode( $shortcode_data ) );
	}
}
