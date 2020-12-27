<?php
/**
 * The template used for displaying page content in page.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php ganga_article_schema( 'CreativeWork' ); ?>>
	<div class="inside-article">
		<?php
		/**
		 * ganga_before_content hook.
		 *
		 *
		 * @hooked ganga_featured_page_header_inside_single - 10
		 */
		do_action( 'ganga_before_content' );

		if ( ganga_show_title() ) : ?>

			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
			</header><!-- .entry-header -->

		<?php endif;

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
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ganga' ),
				'after'  => '</div>',
			) );
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
</article><!-- #post-## -->
