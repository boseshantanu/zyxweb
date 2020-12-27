<?php
/**
 * Builds our Customizer controls.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'customize_register', 'ganga_set_customizer_helpers', 1 );
/**
 * Set up helpers early so they're always available.
 * Other modules might need access to them at some point.
 *
 */
function ganga_set_customizer_helpers( $wp_customize ) {
	// Load helpers
	require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';
}

if ( ! function_exists( 'ganga_customize_register' ) ) {
	add_action( 'customize_register', 'ganga_customize_register' );
	/**
	 * Add our base options to the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function ganga_customize_register( $wp_customize ) {
		// Get our default values
		$defaults = ganga_get_defaults();

		// Load helpers
		require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';

		if ( $wp_customize->get_control( 'blogdescription' ) ) {
			$wp_customize->get_control('blogdescription')->priority = 3;
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'blogname' ) ) {
			$wp_customize->get_control('blogname')->priority = 1;
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'custom_logo' ) ) {
			$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
		}

		// Add control types so controls can be built using JS
		if ( method_exists( $wp_customize, 'register_control_type' ) ) {
			$wp_customize->register_control_type( 'Ganga_Customize_Misc_Control' );
			$wp_customize->register_control_type( 'Ganga_Range_Slider_Control' );
		}

		// Add upsell section type
		if ( method_exists( $wp_customize, 'register_section_type' ) ) {
			$wp_customize->register_section_type( 'Ganga_Upsell_Section' );
		}

		// Add selective refresh to site title and description
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector' => '.main-title a',
				'render_callback' => 'ganga_customize_partial_blogname',
			) );

			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector' => '.site-description',
				'render_callback' => 'ganga_customize_partial_blogdescription',
			) );
		}

		// Remove title
		$wp_customize->add_setting(
			'ganga_settings[hide_title]',
			array(
				'default' => $defaults['hide_title'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'ganga_settings[hide_title]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site title', 'ganga' ),
				'section' => 'title_tagline',
				'priority' => 2
			)
		);

		// Remove tagline
		$wp_customize->add_setting(
			'ganga_settings[hide_tagline]',
			array(
				'default' => $defaults['hide_tagline'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'ganga_settings[hide_tagline]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site tagline', 'ganga' ),
				'section' => 'title_tagline',
				'priority' => 4
			)
		);

		$wp_customize->add_setting(
			'ganga_settings[retina_logo]',
			array(
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'ganga_settings[retina_logo]',
				array(
					'label' => __( 'Retina Logo', 'ganga' ),
					'section' => 'title_tagline',
					'settings' => 'ganga_settings[retina_logo]',
					'active_callback' => 'ganga_has_custom_logo_callback'
				)
			)
		);

		$wp_customize->add_setting(
			'ganga_settings[side_inside_color]', array(
				'default' => $defaults['side_inside_color'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ganga_settings[side_inside_color]',
				array(
					'label' => __( 'Inside padding', 'ganga' ),
					'section' => 'colors',
					'settings' => 'ganga_settings[side_inside_color]',
					'active_callback' => 'ganga_is_side_padding_active',
				)
			)
		);

		$wp_customize->add_setting(
			'ganga_settings[text_color]', array(
				'default' => $defaults['text_color'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ganga_settings[text_color]',
				array(
					'label' => __( 'Text Color', 'ganga' ),
					'section' => 'colors',
					'settings' => 'ganga_settings[text_color]'
				)
			)
		);

		$wp_customize->add_setting(
			'ganga_settings[link_color]', array(
				'default' => $defaults['link_color'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ganga_settings[link_color]',
				array(
					'label' => __( 'Link Color', 'ganga' ),
					'section' => 'colors',
					'settings' => 'ganga_settings[link_color]'
				)
			)
		);

		$wp_customize->add_setting(
			'ganga_settings[link_color_hover]', array(
				'default' => $defaults['link_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ganga_settings[link_color_hover]',
				array(
					'label' => __( 'Link Color Hover', 'ganga' ),
					'section' => 'colors',
					'settings' => 'ganga_settings[link_color_hover]'
				)
			)
		);

		$wp_customize->add_setting(
			'ganga_settings[link_color_visited]', array(
				'default' => $defaults['link_color_visited'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_hex_color',
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ganga_settings[link_color_visited]',
				array(
					'label' => __( 'Link Color Visited', 'ganga' ),
					'section' => 'colors',
					'settings' => 'ganga_settings[link_color_visited]'
				)
			)
		);

		if ( ! function_exists( 'ganga_colors_customize_register' ) && ! defined( 'GANGA_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new Ganga_Customize_Misc_Control(
					$wp_customize,
					'colors_get_addon_desc',
					array(
						'section' => 'colors',
						'type' => 'addon',
						'label' => __( 'More info', 'ganga' ),
						'description' => __( 'More colors are available in Ganga premium version. Visit wpkoi.com for more info.', 'ganga' ),
						'url' => esc_url( GANGA_THEME_URL ),
						'priority' => 30,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);
		}

		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'ganga_layout_panel' ) ) {
				$wp_customize->add_panel( 'ganga_layout_panel', array(
					'priority' => 25,
					'title' => __( 'Layout', 'ganga' ),
				) );
			}
		}

		// Add Layout section
		$wp_customize->add_section(
			'ganga_layout_container',
			array(
				'title' => __( 'Container', 'ganga' ),
				'priority' => 10,
				'panel' => 'ganga_layout_panel'
			)
		);

		// Container width
		$wp_customize->add_setting(
			'ganga_settings[container_width]',
			array(
				'default' => $defaults['container_width'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_integer',
				'transport' => 'postMessage'
			)
		);

		$wp_customize->add_control(
			new Ganga_Range_Slider_Control(
				$wp_customize,
				'ganga_settings[container_width]',
				array(
					'type' => 'ganga-range-slider',
					'label' => __( 'Container Width', 'ganga' ),
					'section' => 'ganga_layout_container',
					'settings' => array(
						'desktop' => 'ganga_settings[container_width]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 700,
							'max' => 2000,
							'step' => 5,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 0,
				)
			)
		);

		// Add Top Bar section
		$wp_customize->add_section(
			'ganga_top_bar',
			array(
				'title' => __( 'Top Bar', 'ganga' ),
				'priority' => 15,
				'panel' => 'ganga_layout_panel',
			)
		);

		// Add Top Bar width
		$wp_customize->add_setting(
			'ganga_settings[top_bar_width]',
			array(
				'default' => $defaults['top_bar_width'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'ganga_settings[top_bar_width]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Width', 'ganga' ),
				'section' => 'ganga_top_bar',
				'choices' => array(
					'full' => __( 'Full', 'ganga' ),
					'contained' => __( 'Contained', 'ganga' )
				),
				'settings' => 'ganga_settings[top_bar_width]',
				'priority' => 5,
				'active_callback' => 'ganga_is_top_bar_active',
			)
		);

		// Add Top Bar inner width
		$wp_customize->add_setting(
			'ganga_settings[top_bar_inner_width]',
			array(
				'default' => $defaults['top_bar_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'ganga_settings[top_bar_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Inner Width', 'ganga' ),
				'section' => 'ganga_top_bar',
				'choices' => array(
					'full' => __( 'Full', 'ganga' ),
					'contained' => __( 'Contained', 'ganga' )
				),
				'settings' => 'ganga_settings[top_bar_inner_width]',
				'priority' => 10,
				'active_callback' => 'ganga_is_top_bar_active',
			)
		);

		// Add top bar alignment
		$wp_customize->add_setting(
			'ganga_settings[top_bar_alignment]',
			array(
				'default' => $defaults['top_bar_alignment'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[top_bar_alignment]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Alignment', 'ganga' ),
				'section' => 'ganga_top_bar',
				'choices' => array(
					'left' => __( 'Left', 'ganga' ),
					'center' => __( 'Center', 'ganga' ),
					'right' => __( 'Right', 'ganga' )
				),
				'settings' => 'ganga_settings[top_bar_alignment]',
				'priority' => 15,
				'active_callback' => 'ganga_is_top_bar_active',
			)
		);

		// Add Header section
		$wp_customize->add_section(
			'ganga_layout_header',
			array(
				'title' => __( 'Header', 'ganga' ),
				'priority' => 20,
				'panel' => 'ganga_layout_panel'
			)
		);

		// Add Header Layout setting
		$wp_customize->add_setting(
			'ganga_settings[header_layout_setting]',
			array(
				'default' => $defaults['header_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'ganga_settings[header_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Width', 'ganga' ),
				'section' => 'ganga_layout_header',
				'choices' => array(
					'fluid-header' => __( 'Full', 'ganga' ),
					'contained-header' => __( 'Contained', 'ganga' )
				),
				'settings' => 'ganga_settings[header_layout_setting]',
				'priority' => 5
			)
		);

		// Add Inside Header Layout setting
		$wp_customize->add_setting(
			'ganga_settings[header_inner_width]',
			array(
				'default' => $defaults['header_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'ganga_settings[header_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Header Width', 'ganga' ),
				'section' => 'ganga_layout_header',
				'choices' => array(
					'contained' => __( 'Contained', 'ganga' ),
					'full-width' => __( 'Full', 'ganga' )
				),
				'settings' => 'ganga_settings[header_inner_width]',
				'priority' => 6
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'ganga_settings[header_alignment_setting]',
			array(
				'default' => $defaults['header_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[header_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Alignment', 'ganga' ),
				'section' => 'ganga_layout_header',
				'choices' => array(
					'left' => __( 'Left', 'ganga' ),
					'center' => __( 'Center', 'ganga' ),
					'right' => __( 'Right', 'ganga' )
				),
				'settings' => 'ganga_settings[header_alignment_setting]',
				'priority' => 10
			)
		);

		$wp_customize->add_section(
			'ganga_layout_navigation',
			array(
				'title' => __( 'Primary Navigation', 'ganga' ),
				'priority' => 30,
				'panel' => 'ganga_layout_panel'
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'ganga_settings[nav_layout_setting]',
			array(
				'default' => $defaults['nav_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[nav_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Width', 'ganga' ),
				'section' => 'ganga_layout_navigation',
				'choices' => array(
					'fluid-nav' => __( 'Full', 'ganga' ),
					'contained-nav' => __( 'Contained', 'ganga' )
				),
				'settings' => 'ganga_settings[nav_layout_setting]',
				'priority' => 15
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'ganga_settings[nav_inner_width]',
			array(
				'default' => $defaults['nav_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[nav_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Navigation Width', 'ganga' ),
				'section' => 'ganga_layout_navigation',
				'choices' => array(
					'contained' => __( 'Contained', 'ganga' ),
					'full-width' => __( 'Full', 'ganga' )
				),
				'settings' => 'ganga_settings[nav_inner_width]',
				'priority' => 16
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'ganga_settings[nav_alignment_setting]',
			array(
				'default' => $defaults['nav_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[nav_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Alignment', 'ganga' ),
				'section' => 'ganga_layout_navigation',
				'choices' => array(
					'left' => __( 'Left', 'ganga' ),
					'center' => __( 'Center', 'ganga' ),
					'right' => __( 'Right', 'ganga' )
				),
				'settings' => 'ganga_settings[nav_alignment_setting]',
				'priority' => 20
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'ganga_settings[nav_position_setting]',
			array(
				'default' => $defaults['nav_position_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => ( '' !== ganga_get_setting( 'nav_position_setting' ) ) ? 'postMessage' : 'refresh'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[nav_position_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Location', 'ganga' ),
				'section' => 'ganga_layout_navigation',
				'choices' => array(
					'nav-below-header' => __( 'Below Header', 'ganga' ),
					'nav-above-header' => __( 'Above Header', 'ganga' ),
					'nav-float-right' => __( 'Float Right', 'ganga' ),
					'nav-float-left' => __( 'Float Left', 'ganga' ),
					'nav-left-sidebar' => __( 'Left Sidebar', 'ganga' ),
					'nav-right-sidebar' => __( 'Right Sidebar', 'ganga' ),
					'' => __( 'No Navigation', 'ganga' )
				),
				'settings' => 'ganga_settings[nav_position_setting]',
				'priority' => 22
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'ganga_settings[nav_dropdown_type]',
			array(
				'default' => $defaults['nav_dropdown_type'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[nav_dropdown_type]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Dropdown', 'ganga' ),
				'section' => 'ganga_layout_navigation',
				'choices' => array(
					'hover' => __( 'Hover', 'ganga' ),
					'click' => __( 'Click - Menu Item', 'ganga' ),
					'click-arrow' => __( 'Click - Arrow', 'ganga' )
				),
				'settings' => 'ganga_settings[nav_dropdown_type]',
				'priority' => 22
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'ganga_settings[nav_search]',
			array(
				'default' => $defaults['nav_search'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'ganga_settings[nav_search]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Search', 'ganga' ),
				'section' => 'ganga_layout_navigation',
				'choices' => array(
					'enable' => __( 'Enable', 'ganga' ),
					'disable' => __( 'Disable', 'ganga' )
				),
				'settings' => 'ganga_settings[nav_search]',
				'priority' => 23
			)
		);

		// Add content setting
		$wp_customize->add_setting(
			'ganga_settings[content_layout_setting]',
			array(
				'default' => $defaults['content_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'ganga_settings[content_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Content Layout', 'ganga' ),
				'section' => 'ganga_layout_container',
				'choices' => array(
					'separate-containers' => __( 'Separate Containers', 'ganga' ),
					'one-container' => __( 'One Container', 'ganga' )
				),
				'settings' => 'ganga_settings[content_layout_setting]',
				'priority' => 25
			)
		);

		$wp_customize->add_section(
			'ganga_layout_sidecontent',
			array(
				'title' => __( 'Fixed Side Content', 'ganga' ),
				'priority' => 39,
				'panel' => 'ganga_layout_panel'
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[fixed_side_content]',
			array(
				'default' => $defaults['fixed_side_content'],
				'type' => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[fixed_side_content]',
			array(
				'type' 		 => 'textarea',
				'label'      => __( 'Fixed Side Content', 'ganga' ),
				'description'=> __( 'Content that You want to display fixed on the left.', 'ganga' ),
				'section'    => 'ganga_layout_sidecontent',
				'settings'   => 'ganga_settings[fixed_side_content]',
			)
		);

		$wp_customize->add_section(
			'ganga_layout_sidebars',
			array(
				'title' => __( 'Sidebars', 'ganga' ),
				'priority' => 40,
				'panel' => 'ganga_layout_panel'
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'ganga_settings[layout_setting]',
			array(
				'default' => $defaults['layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'ganga_settings[layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Sidebar Layout', 'ganga' ),
				'section' => 'ganga_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'ganga' ),
					'right-sidebar' => __( 'Content / Sidebar', 'ganga' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'ganga' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'ganga' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'ganga' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'ganga' )
				),
				'settings' => 'ganga_settings[layout_setting]',
				'priority' => 30
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'ganga_settings[blog_layout_setting]',
			array(
				'default' => $defaults['blog_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'ganga_settings[blog_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Blog Sidebar Layout', 'ganga' ),
				'section' => 'ganga_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'ganga' ),
					'right-sidebar' => __( 'Content / Sidebar', 'ganga' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'ganga' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'ganga' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'ganga' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'ganga' )
				),
				'settings' => 'ganga_settings[blog_layout_setting]',
				'priority' => 35
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'ganga_settings[single_layout_setting]',
			array(
				'default' => $defaults['single_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'ganga_settings[single_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Single Post Sidebar Layout', 'ganga' ),
				'section' => 'ganga_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'ganga' ),
					'right-sidebar' => __( 'Content / Sidebar', 'ganga' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'ganga' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'ganga' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'ganga' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'ganga' )
				),
				'settings' => 'ganga_settings[single_layout_setting]',
				'priority' => 36
			)
		);

		$wp_customize->add_section(
			'ganga_layout_footer',
			array(
				'title' => __( 'Footer', 'ganga' ),
				'priority' => 50,
				'panel' => 'ganga_layout_panel'
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'ganga_settings[footer_layout_setting]',
			array(
				'default' => $defaults['footer_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'ganga_settings[footer_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Width', 'ganga' ),
				'section' => 'ganga_layout_footer',
				'choices' => array(
					'fluid-footer' => __( 'Full', 'ganga' ),
					'contained-footer' => __( 'Contained', 'ganga' )
				),
				'settings' => 'ganga_settings[footer_layout_setting]',
				'priority' => 40
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'ganga_settings[footer_widgets_inner_width]',
			array(
				'default' => $defaults['footer_widgets_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
			)
		);

		// Add content control
		$wp_customize->add_control(
			'ganga_settings[footer_widgets_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Footer Widgets Width', 'ganga' ),
				'section' => 'ganga_layout_footer',
				'choices' => array(
					'contained' => __( 'Contained', 'ganga' ),
					'full-width' => __( 'Full', 'ganga' )
				),
				'settings' => 'ganga_settings[footer_widgets_inner_width]',
				'priority' => 41
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'ganga_settings[footer_inner_width]',
			array(
				'default' => $defaults['footer_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'ganga_settings[footer_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Footer Width', 'ganga' ),
				'section' => 'ganga_layout_footer',
				'choices' => array(
					'contained' => __( 'Contained', 'ganga' ),
					'full-width' => __( 'Full', 'ganga' )
				),
				'settings' => 'ganga_settings[footer_inner_width]',
				'priority' => 41
			)
		);

		// Add footer widget setting
		$wp_customize->add_setting(
			'ganga_settings[footer_widget_setting]',
			array(
				'default' => $defaults['footer_widget_setting'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add footer widget control
		$wp_customize->add_control(
			'ganga_settings[footer_widget_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Widgets', 'ganga' ),
				'section' => 'ganga_layout_footer',
				'choices' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5'
				),
				'settings' => 'ganga_settings[footer_widget_setting]',
				'priority' => 45
			)
		);

		// Add footer widget setting
		$wp_customize->add_setting(
			'ganga_settings[footer_bar_alignment]',
			array(
				'default' => $defaults['footer_bar_alignment'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add footer widget control
		$wp_customize->add_control(
			'ganga_settings[footer_bar_alignment]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Bar Alignment', 'ganga' ),
				'section' => 'ganga_layout_footer',
				'choices' => array(
					'left' => __( 'Left','ganga' ),
					'center' => __( 'Center','ganga' ),
					'right' => __( 'Right','ganga' )
				),
				'settings' => 'ganga_settings[footer_bar_alignment]',
				'priority' => 47,
				'active_callback' => 'ganga_is_footer_bar_active'
			)
		);

		// Add back to top setting
		$wp_customize->add_setting(
			'ganga_settings[back_to_top]',
			array(
				'default' => $defaults['back_to_top'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_choices'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'ganga_settings[back_to_top]',
			array(
				'type' => 'select',
				'label' => __( 'Back to Top Button', 'ganga' ),
				'section' => 'ganga_layout_footer',
				'choices' => array(
					'enable' => __( 'Enable', 'ganga' ),
					'' => __( 'Disable', 'ganga' )
				),
				'settings' => 'ganga_settings[back_to_top]',
				'priority' => 50
			)
		);

		// Add Layout section
		$wp_customize->add_section(
			'ganga_blog_section',
			array(
				'title' => __( 'Blog', 'ganga' ),
				'priority' => 55,
				'panel' => 'ganga_layout_panel'
			)
		);

		$wp_customize->add_setting(
			'ganga_settings[blog_header_image]',
			array(
				'default' => $defaults['blog_header_image'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'ganga_settings[blog_header_image]',
				array(
					'label' => __( 'Blog Header image', 'ganga' ),
					'section' => 'ganga_blog_section',
					'settings' => 'ganga_settings[blog_header_image]',
					'description' => __( 'Recommended size: 1800*780px', 'ganga' )
				)
			)
		);

		// Blog header texts
		$wp_customize->add_setting(
			'ganga_settings[blog_header_title]',
			array(
				'default' => $defaults['blog_header_title'],
				'type' => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[blog_header_title]',
			array(
				'type' 		 => 'textarea',
				'label'      => __( 'Blog Header title', 'ganga' ),
				'section'    => 'ganga_blog_section',
				'settings'   => 'ganga_settings[blog_header_title]',
				'description' => __( 'HTML allowed. Put text between &#60;strong&#62;&#60;/strong&#62; for white background.', 'ganga' )
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[blog_header_text]',
			array(
				'default' => $defaults['blog_header_text'],
				'type' => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[blog_header_text]',
			array(
				'type' 		 => 'textarea',
				'label'      => __( 'Blog Header text', 'ganga' ),
				'section'    => 'ganga_blog_section',
				'settings'   => 'ganga_settings[blog_header_text]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[blog_header_button_text]',
			array(
				'default' => $defaults['blog_header_button_text'],
				'type' => 'option',
				'sanitize_callback' => 'esc_html',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[blog_header_button_text]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Blog Header button text', 'ganga' ),
				'section'    => 'ganga_blog_section',
				'settings'   => 'ganga_settings[blog_header_button_text]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[blog_header_button_url]',
			array(
				'default' => $defaults['blog_header_button_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[blog_header_button_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Blog Header button url', 'ganga' ),
				'section'    => 'ganga_blog_section',
				'settings'   => 'ganga_settings[blog_header_button_url]',
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'ganga_settings[post_content]',
			array(
				'default' => $defaults['post_content'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_blog_excerpt'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'blog_content_control',
			array(
				'type' => 'select',
				'label' => __( 'Content Type', 'ganga' ),
				'section' => 'ganga_blog_section',
				'choices' => array(
					'full' => __( 'Full', 'ganga' ),
					'excerpt' => __( 'Excerpt', 'ganga' )
				),
				'settings' => 'ganga_settings[post_content]',
				'priority' => 10
			)
		);

		if ( ! function_exists( 'ganga_blog_customize_register' ) && ! defined( 'GANGA_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new Ganga_Customize_Misc_Control(
					$wp_customize,
					'blog_get_addon_desc',
					array(
						'section' => 'ganga_blog_section',
						'type' => 'addon',
						'label' => __( 'Learn more', 'ganga' ),
						'description' => __( 'More options are available for this section in our premium version.', 'ganga' ),
						'url' => esc_url( GANGA_THEME_URL ),
						'priority' => 30,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);
		}

		// Add Performance section
		$wp_customize->add_section(
			'ganga_general_section',
			array(
				'title' => __( 'General', 'ganga' ),
				'priority' => 99
			)
		);

		if ( ! apply_filters( 'ganga_fontawesome_essentials', false ) ) {
			$wp_customize->add_setting(
				'ganga_settings[font_awesome_essentials]',
				array(
					'default' => $defaults['font_awesome_essentials'],
					'type' => 'option',
					'sanitize_callback' => 'ganga_sanitize_checkbox'
				)
			);

			$wp_customize->add_control(
				'ganga_settings[font_awesome_essentials]',
				array(
					'type' => 'checkbox',
					'label' => __( 'Load essential icons only', 'ganga' ),
					'description' => __( 'Load essential Font Awesome icons instead of the full library.', 'ganga' ),
					'section' => 'ganga_general_section',
					'settings' => 'ganga_settings[font_awesome_essentials]',
				)
			);
		}

		// Add Socials section
		$wp_customize->add_section(
			'ganga_socials_section',
			array(
				'title' => __( 'Socials', 'ganga' ),
				'priority' => 99
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_display_side]',
			array(
				'default' => $defaults['socials_display_side'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_display_side]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Display on fixed side', 'ganga' ),
				'section' => 'ganga_socials_section'
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_display_top]',
			array(
				'default' => $defaults['socials_display_top'],
				'type' => 'option',
				'sanitize_callback' => 'ganga_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_display_top]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Display on top bar', 'ganga' ),
				'section' => 'ganga_socials_section'
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_facebook_url]',
			array(
				'default' => $defaults['socials_facebook_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_facebook_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Facebook url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_facebook_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_twitter_url]',
			array(
				'default' => $defaults['socials_twitter_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_twitter_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Twitter url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_twitter_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_google_url]',
			array(
				'default' => $defaults['socials_google_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_google_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Google url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_google_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_tumblr_url]',
			array(
				'default' => $defaults['socials_tumblr_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_tumblr_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Tumblr url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_tumblr_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_pinterest_url]',
			array(
				'default' => $defaults['socials_pinterest_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_pinterest_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Pinterest url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_pinterest_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_youtube_url]',
			array(
				'default' => $defaults['socials_youtube_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_youtube_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Youtube url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_youtube_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_linkedin_url]',
			array(
				'default' => $defaults['socials_linkedin_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_linkedin_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Linkedin url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_linkedin_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_linkedin_url]',
			array(
				'default' => $defaults['socials_linkedin_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_linkedin_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Linkedin url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_linkedin_url]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_custom_icon_1]',
			array(
				'default' => $defaults['socials_custom_icon_1'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_custom_icon_1]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 1', 'ganga' ),
				'description'=> sprintf( __( 'You can add icon code for Your button.<br>Example: <code>fa-file-pdf-o</code>.<br>Use the codes from <a href="%s" target="_blank">Font Awesome</a>):', 'ganga' ), 'https://fontawesome.com/icons' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_custom_icon_1]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_custom_icon_url_1]',
			array(
				'default' => $defaults['socials_custom_icon_url_1'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_custom_icon_url_1]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 1 url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_custom_icon_url_1]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_custom_icon_2]',
			array(
				'default' => $defaults['socials_custom_icon_2'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_custom_icon_2]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 2', 'ganga' ),
				'description'=> sprintf( __( 'You can add icon code for Your button.<br>Example: <code>fa-file-pdf-o</code>.<br>Use the codes from <a href="%s" target="_blank">Font Awesome</a>):', 'ganga' ), 'https://fontawesome.com/icons' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_custom_icon_2]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_custom_icon_url_2]',
			array(
				'default' => $defaults['socials_custom_icon_url_2'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_custom_icon_url_2]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 2 url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_custom_icon_url_2]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_custom_icon_3]',
			array(
				'default' => $defaults['socials_custom_icon_3'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_custom_icon_3]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 3', 'ganga' ),
				'description'=> sprintf( __( 'You can add icon code for Your button.<br>Example: <code>fa-file-pdf-o</code>.<br>Use the codes from <a href="%s" target="_blank">Font Awesome</a>):', 'ganga' ), 'https://fontawesome.com/icons' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_custom_icon_3]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_custom_icon_url_3]',
			array(
				'default' => $defaults['socials_custom_icon_url_3'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_custom_icon_url_3]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 3 url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_custom_icon_url_3]',
			)
		);
		
		$wp_customize->add_setting(
			'ganga_settings[socials_mail_url]',
			array(
				'default' => $defaults['socials_mail_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'ganga_settings[socials_mail_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'E-mail url', 'ganga' ),
				'section'    => 'ganga_socials_section',
				'settings'   => 'ganga_settings[socials_mail_url]',
			)
		);

		// Add Ganga Premium section
		if ( ! defined( 'GANGA_PREMIUM_VERSION' ) ) {
			$wp_customize->add_section(
				new Ganga_Upsell_Section( $wp_customize, 'ganga_upsell_section',
					array(
						'pro_text' => __( 'Get Premium for more!', 'ganga' ),
						'pro_url' => esc_url( GANGA_THEME_URL ),
						'capability' => 'edit_theme_options',
						'priority' => 555,
						'type' => 'ganga-upsell-section',
					)
				)
			);
		}
	}
}

if ( ! function_exists( 'ganga_customizer_live_preview' ) ) {
	add_action( 'customize_preview_init', 'ganga_customizer_live_preview', 100 );
	/**
	 * Add our live preview scripts
	 *
	 */
	function ganga_customizer_live_preview() {
		wp_enqueue_script( 'ganga-themecustomizer', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/js/customizer-live-preview.js', array( 'customize-preview' ), GANGA_VERSION, true );
	}
}
