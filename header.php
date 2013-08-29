<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" dir="ltr" lang="en-US"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en-US">
<!--<![endif]-->
<html <?php language_attributes(); ?>>

<head>

<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php hybrid_document_title(); ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); // wp_head ?>

</head>

<body class="<?php hybrid_body_class(); ?>">

	<div id="container">

		<header id="header">

			<div class="wrap">

				<hgroup id="branding">
					<h1 id="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
				</hgroup><!-- #branding -->

				<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>

			</div>

		</header><!-- #header -->

		<div id="main">

			<div class="wrap">

				<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'container' => 'nav', 'separator' => '>', 'before' => __( 'You are here:', 'seamless' ) ) ); ?>