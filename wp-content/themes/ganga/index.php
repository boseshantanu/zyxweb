<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php ganga_content_class();?>>
		<main id="main" <?php ganga_main_class(); ?>>
			<?php
			/**
			 * ganga_before_main_content hook.
			 *
			 */
			do_action( 'ganga_before_main_content' );

			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile;

				ganga_content_nav( 'nav-below' );

			else :

				get_template_part( 'no-results', 'index' );

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
