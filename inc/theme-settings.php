
<?php
/**
 * Theme settings.
 *
 * @package CT_Custom
 */

function ct_custom_add_theme_settings_page() {
    add_theme_page(
        'Theme Settings',
        'Theme Settings',
        'manage_options',
        'ct-theme-settings',
        'ct_custom_render_theme_settings_page'
    );
}
add_action( 'admin_menu', 'ct_custom_add_theme_settings_page' );

/**
 * Register theme settings.
 */
function ct_custom_register_theme_settings() {
    register_setting(
        'ct_theme_settings_group',
        'ct_theme_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'ct_custom_sanitize_theme_settings',
            'default'           => array(),
        )
    );

    add_settings_section(
        'ct_general_section',
        'General Information',
        '__return_false',
        'ct-theme-settings'
    );

    $general_fields = array(
        'logo_id' => 'Website Logo',
        'phone'   => 'Phone Number',
        'address' => 'Address',
        'fax'     => 'Fax Number',
    );

    foreach ( $general_fields as $key => $label ) {
        add_settings_field(
            'ct_' . $key,
            $label,
            'ct_custom_render_setting_field',
            'ct-theme-settings',
            'ct_general_section',
            array( 'key' => $key )
        );
    }

    add_settings_section(
        'ct_social_section',
        'Social Media Links',
        '__return_false',
        'ct-theme-settings'
    );

    $social_fields = array(
        'facebook'  => 'Facebook URL',
        'twitter'   => 'Twitter / X URL',
        'linkedin'  => 'LinkedIn URL',
        'pinterest' => 'Pinterest URL',
    );

    foreach ( $social_fields as $key => $label ) {
        add_settings_field(
            'ct_' . $key,
            $label,
            'ct_custom_render_setting_field',
            'ct-theme-settings',
            'ct_social_section',
            array( 'key' => $key )
        );
    }
}
add_action( 'admin_init', 'ct_custom_register_theme_settings' );

/**
 * Sanitize saved values.
 */
function ct_custom_sanitize_theme_settings( $input ) {
    $input = is_array( $input ) ? $input : array();

    $output = array();

    $output['logo_id'] = absint( $input['logo_id'] ?? 0 );
    $output['phone']   = sanitize_text_field( $input['phone'] ?? '' );
    $output['address'] = sanitize_textarea_field( $input['address'] ?? '' );
    $output['fax']     = sanitize_text_field( $input['fax'] ?? '' );

    foreach ( array( 'facebook', 'twitter', 'linkedin', 'pinterest' ) as $key ) {
        $output[ $key ] = esc_url_raw( $input[ $key ] ?? '' );
    }

    return $output;
}

/**
 * Render individual setting fields.
 */
function ct_custom_render_setting_field( $args ) {
    $key     = $args['key'];
    $options = get_option( 'ct_theme_settings', array() );
    $value   = $options[ $key ] ?? '';
    $name    = 'ct_theme_settings[' . $key . ']';

    if ( 'logo_id' === $key ) {
        $image_url = $value
            ? wp_get_attachment_image_url( absint( $value ), 'medium' )
            : '';

        ?>
        <div class="ct-logo-setting">
            <input
                type="hidden"
                id="ct-logo-id"
                name="<?php echo esc_attr( $name ); ?>"
                value="<?php echo esc_attr( $value ); ?>"
            >

            <img
                id="ct-logo-preview"
                src="<?php echo esc_url( $image_url ?: '' ); ?>"
                alt="Selected logo preview"
                style="max-width:200px;height:auto;<?php echo $image_url ? '' : 'display:none;'; ?>"
            >

            <p>
                <button type="button" class="button" id="ct-upload-logo">
                    Select Logo
                </button>

                <button type="button" class="button" id="ct-remove-logo">
                    Remove Logo
                </button>
            </p>
        </div>
        <?php

        return;
    }

    if ( 'address' === $key ) {
        ?>
        <textarea
            name="<?php echo esc_attr( $name ); ?>"
            rows="4"
            class="large-text"
        ><?php echo esc_textarea( $value ); ?></textarea>
        <?php

        return;
    }

    $url_fields = array( 'facebook', 'twitter', 'linkedin', 'pinterest' );
    $type       = in_array( $key, $url_fields, true ) ? 'url' : 'text';

    ?>
    <input
        type="<?php echo esc_attr( $type ); ?>"
        name="<?php echo esc_attr( $name ); ?>"
        value="<?php echo esc_attr( $value ); ?>"
        class="regular-text"
    >
    <?php
}

/**
 * Display the admin settings page.
 */
function ct_custom_render_theme_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Theme Settings</h1>

        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php
            settings_fields( 'ct_theme_settings_group' );
            do_settings_sections( 'ct-theme-settings' );
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

/**
 * Load WordPress Media Library for logo selection.
 */
function ct_custom_theme_settings_assets( $hook ) {
    if ( 'appearance_page_ct-theme-settings' !== $hook ) {
        return;
    }

    wp_enqueue_media();

    wp_enqueue_script(
        'ct-theme-settings',
        get_template_directory_uri() . '/js/theme-settings.js',
        array( 'jquery' ),
        filemtime( get_template_directory() . '/js/theme-settings.js' ),
        true
    );
}
add_action( 'admin_enqueue_scripts', 'ct_custom_theme_settings_assets' );
