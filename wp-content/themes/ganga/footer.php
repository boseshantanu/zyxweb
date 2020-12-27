<?php
/**
 * The template for displaying the footer.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div><!-- #content -->
</div><!-- #page -->

<?php
/**
 * ganga_before_footer hook.
 *
 */
do_action( 'ganga_before_footer' );
?>

<div <?php ganga_footer_class(); ?>>
	<?php
	/**
	 * ganga_before_footer_content hook.
	 *
	 */
	do_action( 'ganga_before_footer_content' );

	/**
	 * ganga_footer hook.
	 *
	 *
	 * @hooked ganga_construct_footer_widgets - 5
	 * @hooked ganga_construct_footer - 10
	 */
	do_action( 'ganga_footer' );

	/**
	 * ganga_after_footer_content hook.
	 *
	 */
	do_action( 'ganga_after_footer_content' );
	?>
</div><!-- .site-footer -->

<?php
/**
 * ganga_after_footer hook.
 *
 */
do_action( 'ganga_after_footer' );

wp_footer();
?>

</body>
</html>
