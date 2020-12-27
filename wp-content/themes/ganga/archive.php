<?php
/**
 * The template for displaying Archive pages.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php ganga_content_class(); ?>>
		<main id="main" <?php ganga_main_class(); ?>>
			<?php
			/**
			 * ganga_before_main_content hook.
			 *
			 */
			do_action( 'ganga_before_main_content' );

			if ( have_posts() ) :

				/**
				 * ganga_archive_title hook.
				 *
				 *
				 * @hooked ganga_archive_title - 10
				 */
				do_action( 'ganga_archive_title' );

				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile;

				ganga_content_nav( 'nav-below' );

			else :

				get_template_part( 'no-results', 'archive' );

			endif;

			/**
			 * ganga_after_main_content hook.
			 *
			 */
			do_action( 'ganga_after_main_content' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php
	/**
	 * ganga_after_primary_content_area hook.
	 *
	 */
	 do_action( 'ganga_after_primary_content_area' );

	 ganga_construct_sidebars();

get_footer();
