<?php
/**
 * The template for displaying the header.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php ganga_body_schema();?> <?php body_class(); ?>>
	<?php
	/**
	 * ganga_before_header hook.
	 *
	 *
	 * @hooked ganga_do_skip_to_content_link - 2
	 * @hooked ganga_top_bar - 5
	 * @hooked ganga_add_navigation_before_header - 5
	 */
	do_action( 'ganga_before_header' );

	/**
	 * ganga_header hook.
	 *
	 *
	 * @hooked ganga_construct_header - 10
	 */
	do_action( 'ganga_header' );

	/**
	 * ganga_after_header hook.
	 *
	 *
	 * @hooked ganga_featured_page_header - 10
	 */
	do_action( 'ganga_after_header' );
	?>

	<div id="page" class="hfeed site grid-container container grid-parent">
		<div id="content" class="site-content">
			<?php
			/**
			 * ganga_inside_container hook.
			 *
			 */
			do_action( 'ganga_inside_container' );
