<?php

/*
 * Limit to admin user
 */
if (is_admin()) {
    add_action('admin_menu', 'consolari_plugin_menu');
    add_action('admin_init', 'consolari_admin_init');
}

/**
 * Admin init callback
 */
function consolari_admin_init()
{
    register_setting( 'consolari-options', 'consolari-options', 'consolari_options_validate' );

    add_settings_section('consolari_main', 'Account settings', 'consolari_section_text', 'consolari_plugin_options');

    add_settings_field('consolari_setting_user', 'User', 'consolari_setting_user', 'consolari_plugin_options', 'consolari_main');
    add_settings_field('consolari_setting_key', 'Key', 'consolari_setting_key', 'consolari_plugin_options', 'consolari_main');
}

/**
 * Section main text callback
 */
function consolari_section_text()
{
    echo '<p>Enter credentials here from Consolari account.</p>';
}

/**
 * Callback to generate text input
 */
function consolari_setting_user()
{
    $options = get_option('consolari-options');
    echo "<input id='plugin_text_string' name='consolari-options[user]' size='40' maxlength='100' type='text' value='{$options['user']}' />";
}

function consolari_setting_key()
{
    $options = get_option('consolari-options');
    echo "<input id='plugin_text_string' name='consolari-options[key]' size='40' maxlength='100' type='text' value='{$options['key']}' />";
}

/**
 * Callback to validate input
 *
 * @param $input
 * @return mixed
 */
function consolari_options_validate($input)
{
    return $input;
}

/**
 * Callback to build admin menu in settings
 */
function consolari_plugin_menu()
{
    add_options_page( 'Consolari Options', 'Consolari Debug Logger', 'manage_options', 'consolari_plugin_options', 'consolari_plugin_options' );
}

/**
 * Callback to build settings form
 */
function consolari_plugin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    ?>

    <div class="wrap">
        <h2>Consolari Debug Logger settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'consolari-options' ); ?>
            <?php do_settings_sections( 'consolari_plugin_options' ); ?>

            <?php submit_button(); ?>

        </form>
    </div>

    <?php
}