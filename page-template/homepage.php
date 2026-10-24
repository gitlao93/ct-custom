
<?php
/**
 * Template Name: Homepage
 */

get_header();

$theme_settings = get_option( 'ct_theme_settings', array() );

$phone   = $theme_settings['phone'] ?? '';
$address = $theme_settings['address'] ?? '';
$fax     = $theme_settings['fax'] ?? '';

$social_links = array(
    'facebook'  => 'Facebook',
    'twitter'   => 'X',
    'linkedin'  => 'LinkedIn',
    'pinterest' => 'Pinterest',
);
?>

<main id="primary" class="site-main homepage">
    <div class="container">

        <!-- Breadcrumbs -->
        <?php get_template_part( 'template-parts/homepage-breadcrumbs' ); ?>

        <!-- Editable Page Content -->
        <section class="page-intro">
            <div id="homepage-editor-content" class="page-intro-content">
                <?php
                while ( have_posts() ) :
                    the_post();
                    the_content();
                endwhile;
                ?>
            </div>
        </section>

        <!-- Contact Section -->
        <div class="contact-layout">

            <!-- Contact Form -->
            <section class="contact-form-section">
                <h2>Contact Us</h2>

                <div class="section-divider"></div>

                <div class="contact-form">
                    <?php
                    echo do_shortcode(
                        '[contact-form-7 id="8e04007" title="Contact Us"]'
                    );
                    ?>
                </div>
            </section>

            <!-- Business Information -->
            <section class="contact-info-section">
                <h2>Reach Us</h2>

                <div class="section-divider"></div>

                <div class="business-details">
                    <p class="business-name">
                        <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                    </p>

                    <?php if ( ! empty( $address ) ) : ?>
                        <address>
                            <?php echo nl2br( esc_html( $address ) ); ?>
                        </address>
                    <?php endif; ?>

                    <div class="business-contact">
                        <?php if ( ! empty( $phone ) ) : ?>
                            <?php
                            $phone_href = preg_replace( '/[^0-9+]/', '', $phone );
                            ?>
                            <p>
                                Phone:
                                <a href="<?php echo esc_attr( 'tel:' . $phone_href ); ?>">
                                    <?php echo esc_html( $phone ); ?>
                                </a>
                            </p>
                        <?php endif; ?>

                        <?php if ( ! empty( $fax ) ) : ?>
                            <p>
                                Fax: <?php echo esc_html( $fax ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="social-links">
                    <?php foreach ( $social_links as $key => $label ) : ?>
                        <?php
                        $url = $theme_settings[ $key ] ?? '';

                        if ( empty( $url ) ) {
                            continue;
                        }
                        ?>

                        <a
                            href="<?php echo esc_url( $url ); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="<?php echo esc_attr( $label ); ?>"
                        >
                        
                            <?php
                            $icon_url = get_template_directory_uri()
                                . '/assets/icons/'
                                . $key
                                . '.svg';
                            ?>

                            <img
                                src="<?php echo esc_url( $icon_url ); ?>"
                                alt=""
                                width="18"
                                height="18"
                            >

                        </a>
                    <?php endforeach; ?>
                </div>

            </section>

        </div>
    </div>
</main>

<?php
get_footer();
