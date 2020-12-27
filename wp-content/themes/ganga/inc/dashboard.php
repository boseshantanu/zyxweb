<?php
/**
 * Builds our admin page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'ganga_create_menu' ) ) {
	add_action( 'admin_menu', 'ganga_create_menu' );
	/**
	 * Adds our "Ganga" dashboard menu item
	 *
	 */
	function ganga_create_menu() {
		$ganga_page = add_theme_page( 'Ganga', 'Ganga', apply_filters( 'ganga_dashboard_page_capability', 'edit_theme_options' ), 'ganga-options', 'ganga_settings_page' );
		add_action( "admin_print_styles-$ganga_page", 'ganga_options_styles' );
	}
}

if ( ! function_exists( 'ganga_options_styles' ) ) {
	/**
	 * Adds any necessary scripts to the Ganga dashboard page
	 *
	 */
	function ganga_options_styles() {
		wp_enqueue_style( 'ganga-options', get_template_directory_uri() . '/css/admin/admin-style.css', array(), GANGA_VERSION );
	}
}

if ( ! function_exists( 'ganga_settings_page' ) ) {
	/**
	 * Builds the content of our Ganga dashboard page
	 *
	 */
	function ganga_settings_page() {
		?>
		<div class="wrap">
			<div class="metabox-holder">
				<div class="ganga-masthead clearfix">
					<div class="ganga-container">
						<div class="ganga-title">
							<a href="<?php echo esc_url(GANGA_THEME_URL); ?>" target="_blank"><?php esc_html_e( 'Ganga', 'ganga' ); ?></a> <span class="ganga-version"><?php echo GANGA_VERSION; ?></span>
						</div>
						<div class="ganga-masthead-links">
							<?php if ( ! defined( 'GANGA_PREMIUM_VERSION' ) ) : ?>
								<a class="ganga-masthead-links-bold" href="<?php echo esc_url(GANGA_THEME_URL); ?>" target="_blank"><?php esc_html_e( 'Premium', 'ganga' );?></a>
							<?php endif; ?>
							<a href="<?php echo esc_url(GANGA_WPKOI_AUTHOR_URL); ?>" target="_blank"><?php esc_html_e( 'WPKoi', 'ganga' ); ?></a>
                            <a href="<?php echo esc_url(GANGA_DOCUMENTATION); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'ganga' ); ?></a>
						</div>
					</div>
				</div>

				<?php
				/**
				 * ganga_dashboard_after_header hook.
				 *
				 */
				 do_action( 'ganga_dashboard_after_header' );
				 ?>

				<div class="ganga-container">
					<div class="postbox-container clearfix" style="float: none;">
						<div class="grid-container grid-parent">

							<?php
							/**
							 * ganga_dashboard_inside_container hook.
							 *
							 */
							 do_action( 'ganga_dashboard_inside_container' );
							 ?>

							<div class="form-metabox grid-70" style="padding-left: 0;">
								<h2 style="height:0;margin:0;"><!-- admin notices below this element --></h2>
								<form method="post" action="options.php">
									<?php settings_fields( 'ganga-settings-group' ); ?>
									<?php do_settings_sections( 'ganga-settings-group' ); ?>
									<div class="customize-button hide-on-desktop">
										<?php
										printf( '<a id="ganga_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
											esc_url( admin_url( 'customize.php' ) ),
											esc_html__( 'Customize', 'ganga' )
										);
										?>
									</div>

									<?php
									/**
									 * ganga_inside_options_form hook.
									 *
									 */
									 do_action( 'ganga_inside_options_form' );
									 ?>
								</form>

								<?php
								$modules = array(
									'Backgrounds' => array(
											'url' => GANGA_THEME_URL,
									),
									'Blog' => array(
											'url' => GANGA_THEME_URL,
									),
									'Colors' => array(
											'url' => GANGA_THEME_URL,
									),
									'Copyright' => array(
											'url' => GANGA_THEME_URL,
									),
									'Disable Elements' => array(
											'url' => GANGA_THEME_URL,
									),
									'Demo Import' => array(
											'url' => GANGA_THEME_URL,
									),
									'Hooks' => array(
											'url' => GANGA_THEME_URL,
									),
									'Import / Export' => array(
											'url' => GANGA_THEME_URL,
									),
									'Menu Plus' => array(
											'url' => GANGA_THEME_URL,
									),
									'Page Header' => array(
											'url' => GANGA_THEME_URL,
									),
									'Secondary Nav' => array(
											'url' => GANGA_THEME_URL,
									),
									'Spacing' => array(
											'url' => GANGA_THEME_URL,
									),
									'Typography' => array(
											'url' => GANGA_THEME_URL,
									),
									'Elementor Addon' => array(
											'url' => GANGA_THEME_URL,
									)
								);

								if ( ! defined( 'GANGA_PREMIUM_VERSION' ) ) : ?>
									<div class="postbox ganga-metabox">
										<h3 class="hndle"><?php esc_html_e( 'Premium Modules', 'ganga' ); ?></h3>
										<div class="inside" style="margin:0;padding:0;">
											<div class="premium-addons">
												<?php foreach( $modules as $module => $info ) { ?>
												<div class="add-on activated ganga-clear addon-container grid-parent">
													<div class="addon-name column-addon-name" style="">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php echo esc_html( $module ); ?></a>
													</div>
													<div class="addon-action addon-addon-action" style="text-align:right;">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php esc_html_e( 'More info', 'ganga' ); ?></a>
													</div>
												</div>
												<div class="ganga-clear"></div>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php
								endif;

								/**
								 * ganga_options_items hook.
								 *
								 */
								do_action( 'ganga_options_items' );
								?>
							</div>

							<div class="ganga-right-sidebar grid-30" style="padding-right: 0;">
								<div class="customize-button hide-on-mobile">
									<?php
									printf( '<a id="ganga_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
										esc_url( admin_url( 'customize.php' ) ),
										esc_html__( 'Customize', 'ganga' )
									);
									?>
								</div>

								<?php
								/**
								 * ganga_admin_right_panel hook.
								 *
								 */
								 do_action( 'ganga_admin_right_panel' );

								  ?>
                                
                                <div class="wpkoi-doc">
                                	<h3><?php esc_html_e( 'Ganga documentation', 'ganga' ); ?></h3>
                                	<p><?php esc_html_e( 'If You`ve stuck, the documentation may help on WPKoi.com', 'ganga' ); ?></p>
                                    <a href="<?php echo esc_url(GANGA_DOCUMENTATION); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Ganga documentation', 'ganga' ); ?></a>
                                </div>
                                
                                <div class="wpkoi-social">
                                	<h3><?php esc_html_e( 'WPKoi on Facebook', 'ganga' ); ?></h3>
                                	<p><?php esc_html_e( 'If You want to get useful info about WordPress and the theme, follow WPKoi on Facebook.', 'ganga' ); ?></p>
                                    <a href="<?php echo esc_url(GANGA_WPKOI_SOCIAL_URL); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Go to Facebook', 'ganga' ); ?></a>
                                </div>
                                
                                <div class="wpkoi-review">
                                	<h3><?php esc_html_e( 'Help with You review', 'ganga' ); ?></h3>
                                	<p><?php esc_html_e( 'If You like Ganga theme, show it to the world with Your review. Your feedback helps a lot.', 'ganga' ); ?></p>
                                    <a href="<?php echo esc_url(GANGA_WORDPRESS_REVIEW); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Add my review', 'ganga' ); ?></a>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ganga_admin_errors' ) ) {
	add_action( 'admin_notices', 'ganga_admin_errors' );
	/**
	 * Add our admin notices
	 *
	 */
	function ganga_admin_errors() {
		$screen = get_current_screen();

		if ( 'appearance_page_ganga-options' !== $screen->base ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
			 add_settings_error( 'ganga-notices', 'true', esc_html__( 'Settings saved.', 'ganga' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'imported' == $_GET['status'] ) {
			 add_settings_error( 'ganga-notices', 'imported', esc_html__( 'Import successful.', 'ganga' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'reset' == $_GET['status'] ) {
			 add_settings_error( 'ganga-notices', 'reset', esc_html__( 'Settings removed.', 'ganga' ), 'updated' );
		}

		settings_errors( 'ganga-notices' );
	}
}
