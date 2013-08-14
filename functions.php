<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    Seamless
 * @subpackage Functions
 * @version    0.3.0
 * @since      0.1.0
 * @author     James Geiger <james@seamlessthemes.com>
 * @copyright  Copyright (c) 2013, James Geiger
 * @link       http://themehybrid.com/themes/seamless
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'seamless_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function seamless_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Register menus. */
	add_theme_support( 
		'hybrid-core-menus', 
		array( 'primary') 
	);

	/* Register sidebars. */
	add_theme_support( 
		'hybrid-core-sidebars', 
		array( 'primary', 'subsidiary' ) 
	);

	/* Load scripts. */
	add_theme_support( 
		'hybrid-core-scripts', 
		array( 'comment-reply' ) 
	);

	/* Load styles. */
	add_theme_support( 
		'hybrid-core-styles', 
		array( '25px', 'gallery', 'parent', 'style' ) 
	);

	/* Load widgets. */
	add_theme_support( 'hybrid-core-widgets' );

	/* Load shortcodes. */
	add_theme_support( 'hybrid-core-shortcodes' );

	/* Enable custom template hierarchy. */
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* Enable theme layouts (need to add stylesheet support). */
	add_theme_support( 
		'theme-layouts', 
		array( '1c', '2c-l', '2c-r' ),
		array( 'default' => '1c', 'customizer' => true ) 
	);

	/* Allow per-post stylesheets. */
	add_theme_support( 'post-stylesheets' );

	/* Support pagination instead of prev/next links. */
	add_theme_support( 'loop-pagination' );

	/* The best thumbnail/image script ever. */
	add_theme_support( 'get-the-image' );

	/* Use breadcrumbs. */
	add_theme_support( 'breadcrumb-trail' );

	/* Nicer [gallery] shortcode implementation. */
	add_theme_support( 'cleaner-gallery' );

	/* Better captions for themes to style. */
	add_theme_support( 'cleaner-caption' );

	/* Automatically add feed links to <head>. */
	add_theme_support( 'automatic-feed-links' );

	/* Post formats. */
	add_theme_support( 
		'post-formats', 
		array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' ) 
	);

	/* Add support for a custom header image. */
	add_theme_support(
		'custom-header',
		array(  'header-text' => false, 
				'default-image' => '',
				'random-default' => false,
				'width' => 1140,
				'height' => 0,
				'flex-height' => false,
				'flex-width' => false,
				'default-text-color' => '',
				'header-text' => true,
				'uploads' => true,
				'wp-head-callback' => '',
				'admin-head-callback' => '',
				'admin-preview-callback' => '',
	) );

	/* Custom background. */
	add_theme_support( 
		'custom-background',
		array( 'default-color' => 'ffffff' )
	);

	/* Handle content width for embeds and images. */
	hybrid_set_content_width( 742 );

	/* Enqueue scripts (and related stylesheets) */
	add_action( 'wp_enqueue_scripts', 'seamless_scripts' );

	/* Wrap embeds with some custom HTML to handle responsive layout. */
	add_filter( 'embed_handler_html', 'seamless_embed_html' );
	add_filter( 'embed_oembed_html',  'seamless_embed_html' );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'seamless_disable_sidebars' );
	add_action( 'template_redirect', 'seamless_one_column' );

	/** Hybrid Core 1.6 changes **/
	add_filter( "{$prefix}_sidebar_defaults", 'seamless_sidebar_defaults' );
	add_filter( 'cleaner_gallery_defaults',   'seamless_gallery_defaults' );
	add_filter( 'the_content', 'seamless_aside_infinity', 9 );
	/****************************/
}

/* === HYBRID CORE 1.6 CHANGES. === 
 *
 * The following changes are slated for Hybrid Core version 1.6 to make it easier for 
 * theme developers to build awesome HTML5 themes. The code will be removed once 1.6 
 * is released.
 */

	/**
	 * Content template.  This is an early version of what a content template function will look like
	 * in future versions of Hybrid Core.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	function seamless_get_content_template() {

		$templates = array();
		$post_type = get_post_type();

		if ( post_type_supports( $post_type, 'post-formats' ) ) {

			$post_format = get_post_format() ? get_post_format() : 'standard';

			$templates[] = "content-{$post_type}-{$post_format}.php";
			$templates[] = "content-{$post_format}.php";
		}

		$templates[] = "content-{$post_type}.php";
		$templates[] = 'content.php';

		return locate_template( $templates, true, false );
	}

	/**
	 * Sidebar parameter defaults.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $defaults
	 * @return array
	 */
	function seamless_sidebar_defaults( $defaults ) {

		$defaults = array(
			'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		);

		return $defaults;
	}

	/**
	 * Gallery defaults for the Cleaner Gallery extension.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  array  $defaults
	 * @return array
	 */
	function seamless_gallery_defaults( $defaults ) {

		$defaults['itemtag']    = 'figure';
		$defaults['icontag']    = 'div';
		$defaults['captiontag'] = 'figcaption';

		return $defaults;
	}

	/**
	 * Adds an infinity character "&#8734;" to the end of the post content on 'aside' posts.  This 
	 * is from version 0.1.1 of the Post Format Tools extension.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  string $content The post content.
	 * @return string $content
	 */
	function seamless_aside_infinity( $content ) {

		if ( has_post_format( 'aside' ) && !is_singular() )
			$content .= ' <a class="permalink" href="' . get_permalink() . '" title="' . the_title_attribute( array( 'echo' => false ) ) . '">&#8734;</a>';

		return $content;
	}

/* End Hybrid Core 1.6 section. */

/**
 * Registers scripts for the theme and enqueue those used sitewide.
 *
 * @since 0.1.0.
 */

function seamless_scripts() {

	wp_register_script( 'navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20120206', true );

	wp_enqueue_script( 'navigation' );

}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function seamless_one_column() {

	if ( !is_active_sidebar( 'primary' ) )
		add_filter( 'theme_mod_theme_layout', 'seamless_theme_layout_one_column' );

	elseif ( is_attachment() && wp_attachment_is_image() && 'default' == get_post_layout( get_queried_object_id() ) )
		add_filter( 'theme_mod_theme_layout', 'seamless_theme_layout_one_column' );

}

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since  0.1.0
 * @param  string $layout The layout of the current page.
 * @return string
 */
function seamless_theme_layout_one_column( $layout ) {
	return '1c';
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since  0.1.0
 * @param  array $sidebars_widgets A multidimensional array of sidebars and widgets.
 * @return array $sidebars_widgets
 */

function seamless_disable_sidebars( $sidebars_widgets ) {
	global $wp_customize;

	$customize = ( is_object( $wp_customize ) && $wp_customize->is_preview() ) ? true : false;

	if ( !is_admin() && !$customize && '1c' == get_theme_mod( 'theme_layout' ) )
		$sidebars_widgets['primary'] = false;

	return $sidebars_widgets;
}

/**
 * Wraps embeds with <div class="embed-wrap"> to help in making videos responsive.
 *
 * @since  0.1.0
 * @access public
 * @return void
 * @author Justin Tadlock <justin@justintadlock.com>
 */
function seamless_embed_html( $html ) {

	if ( in_the_loop() && has_post_format( 'video' ) && preg_match( '/(<embed|object|iframe)/', $html ) )
		$html = '<div class="embed-wrap">' . $html . '</div>';

	return $html;
}

?>