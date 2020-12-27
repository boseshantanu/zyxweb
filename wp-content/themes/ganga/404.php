<?php
/**
 * The template for displaying 404 pages (Not Found).
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
			?>

			<div class="inside-article">

				<?php
				/**
				 * ganga_before_content hook.
				 *
				 *
				 * @hooked ganga_featured_page_header_inside_single - 10
				 */
				do_action( 'ganga_before_content' );
				?>

				<header class="entry-header">
					<h1 class="entry-title" itemprop="headline"><?php echo apply_filters( 'ganga_404_title', __( 'Oops! That page can&rsquo;t be found.', 'ganga' ) ); // WPCS: XSS OK. ?></h1>
				</header><!-- .entry-header -->

				<?php
				/**
				 * ganga_after_entry_header hook.
				 *
				 *
				 * @hooked ganga_post_image - 10
				 */
				do_action( 'ganga_after_entry_header' );
				?>

				<div class="entry-content" itemprop="text">
					<?php
					echo '<p>' . apply_filters( 'ganga_404_text', __( 'It looks like nothing was found at this location. Maybe try searching?', 'ganga' ) ) . '</p>'; // WPCS: XSS OK.

					get_search_form();
					?>
				</div><!-- .entry-content -->

				<?php
				/**
				 * ganga_after_content hook.
				 *
				 */
				do_action( 'ganga_after_content' );
				?>

			</div><!-- .inside-article -->

			<?php
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
