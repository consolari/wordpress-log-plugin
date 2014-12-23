<?php

/*
 * Limit to admin user
 */
if (is_admin()) {
    add_action( 'admin_menu', 'consolari_plugin_menu' );
    add_action( 'admin_init', 'consolari_register_settings' );
}
 
function consolari_register_settings()
{
    register_setting( 'consolari-group', 'Email (username)' );
    register_setting( 'consolari-group', 'Key' );
}

function consolari_plugin_menu() {
    add_options_page( 'Consolari Options', 'Consolari Debug Logger', 'manage_options', 'consolari_plugin_options', 'consolari_plugin_options' );
}

function consolari_plugin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    ?>

    <div class="wrap">
        <h2>Consolari Debug Logger settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'baw-settings-group' ); ?>
            <?php do_settings_sections( 'baw-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">New Option Name</th>
                    <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Some Other Option</th>
                    <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Options, Etc.</th>
                    <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>

    </div>

    <?php
}