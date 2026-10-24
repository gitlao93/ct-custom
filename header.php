<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CT_Custom
 */

$theme_settings = get_option( 'ct_theme_settings', array() );

$logo_id = absint( $theme_settings['logo_id'] ?? 0 );
$phone   = $theme_settings['phone'] ?? '';

$phone_href = preg_replace( '/[^0-9+]/', '', $phone );

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ct-custom' ); ?></a>

<header id="masthead" class="site-header">
	<!-- Top bar -->
	<div class="top-bar">
		<div class="container top-bar-inner">

			<?php if ( ! empty( $phone_href ) ) : ?>
			<div class="top-bar-contact">
				<span>CALL US NOW!</span>

				<a href="<?php echo esc_attr( 'tel:' . $phone_href ); ?>">
					<?php echo esc_html( $phone ); ?>
				</a>
			</div>
			<?php endif; ?>

			<nav class="top-bar-account" aria-label="Account navigation">
				<a href="#">LOGIN</a>
				<a href="#">SIGNUP</a>
			</nav>

		</div>
	</div>

	<!-- Main header  -->
	<div class="main-header">
		<div class="container main-header-inner">


		<div class="site-branding">
			<?php
			$logo_html = $logo_id
				? wp_get_attachment_image(
					$logo_id,
					'full',
					false,
					array(
						'class' => 'custom-logo',
						'alt'   => get_bloginfo( 'name' ),
					)
				)
				: '';
			?>

			<?php if ( $logo_html ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				class="custom-logo-link"
				rel="home">
					<?php echo $logo_html; ?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				class="site-title"
				rel="home">
					YOUR<span class="highlight">LOGO</span>
				</a>
			<?php endif; ?>
		</div>


			<nav id="site-navigation"
				class="main-navigation"
				aria-label="Primary navigation">

				<button class="menu-toggle"
						type="button"
						aria-controls="primary-menu"
						aria-expanded="false">
					<?php esc_html_e( 'Menu', 'ct-custom' ); ?>
				</button>

				<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => false,
				) );
				?>

			</nav>

		</div>
	</div>

</header><!-- #masthead -->

<div id="content" class="site-content">
