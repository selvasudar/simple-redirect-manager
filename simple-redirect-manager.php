<?php
/**
 * Plugin Name: Simple Redirect Manager
 * Description: A plugin to manage URL redirects with logging and delete options.
 * Version: 1.0
 * Author: Selvakumar Duraipandian
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Activation hook to create database table
function srm_create_redirect_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        source_url TEXT NOT NULL,
        target_url TEXT NOT NULL,
        redirect_type VARCHAR(3) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'srm_create_redirect_table');

// Admin menu setup
function srm_register_menu() {
    add_menu_page('Redirect Manager', 'Redirect Manager', 'manage_options', 'srm-redirect-manager', 'srm_render_admin_page', 'dashicons-randomize');
}
add_action('admin_menu', 'srm_register_menu');

// Handle form submission and deletion
function srm_handle_form_submission() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['srm_add_redirect'])) {
        $source_url = sanitize_text_field($_POST['source_url']);
        $target_url = sanitize_text_field($_POST['target_url']);
        $redirect_type = sanitize_text_field($_POST['redirect_type']);

        $wpdb->insert($table_name, [
            'source_url' => $source_url,
            'target_url' => $target_url,
            'redirect_type' => $redirect_type
        ]);
    }

    if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
        $wpdb->delete($table_name, ['id' => intval($_GET['delete'])]);
    }
}
add_action('admin_init', 'srm_handle_form_submission');

// Render the admin page
function srm_render_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';
    $redirects = $wpdb->get_results("SELECT * FROM $table_name");
    ?>
    <div class="wrap">
        <h1>Redirect Manager</h1>
        <form method="POST">
            <table class="form-table">
                <tr>
                    <th><label for="source_url">Source URL</label></th>
                    <td><input type="text" name="source_url" id="source_url" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="target_url">Target URL</label></th>
                    <td><input type="text" name="target_url" id="target_url" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="redirect_type">Redirect Type</label></th>
                    <td>
                    <select name="redirect_type" id="redirect_type">
                        <option value="301">301 - Moved Permanently</option>
                        <option value="302">302 - Found (Temporary Redirect)</option>
                        <option value="303">303 - See Other</option>
                        <option value="307">307 - Temporary Redirect</option>
                        <option value="308">308 - Permanent Redirect</option>
                    </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="srm_add_redirect" id="srm_add_redirect" class="button button-primary" value="Add Redirect">
            </p>
        </form>

        <h2>Existing Redirects</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Source URL</th>
                    <th>Target URL</th>
                    <th>Redirect Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redirects as $redirect): ?>
                    <tr>
                        <td><?php echo $redirect->id; ?></td>
                        <td><?php echo esc_html($redirect->source_url); ?></td>
                        <td><?php echo esc_html($redirect->target_url); ?></td>
                        <td><?php echo esc_html($redirect->redirect_type); ?></td>
                        <td>
                            <a href="?page=srm-redirect-manager&delete=<?php echo $redirect->id; ?>" class="button button-secondary">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Handle redirects on the frontend
function srm_handle_redirects() {
    if (is_admin()) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';
    $requested_url = home_url($_SERVER['REQUEST_URI']);

    $redirect = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE source_url = %s", $requested_url));

    if ($redirect) {
        wp_redirect($redirect->target_url, intval($redirect->redirect_type));
        exit;
    }
}
add_action('template_redirect', 'srm_handle_redirects');
