<?php
/**
 * Dynamic section breadcrumbs.
 */
?>

<nav
    id="section-breadcrumbs"
    class="breadcrumbs"
    aria-label="Section navigation"
    data-home-url="<?php echo esc_url( home_url( '/' ) ); ?>"
>
    <ol>
        <li aria-current="location">
            <?php esc_html_e( 'Home', 'ct-custom' ); ?>
        </li>
    </ol>
</nav>