<?php
// Get the current logged-in user ID
$user_id = get_current_user_id();

// Assuming the user is logged in and has appropriate permissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save the user inputs
    update_user_meta($user_id, 'telegram_chat_id', sanitize_text_field($_POST['telegram_chat_id']));
    update_user_meta($user_id, 'slack_webhook_url', sanitize_text_field($_POST['slack_webhook_url']));
    update_user_meta($user_id, 'email_address', sanitize_email($_POST['email_address']));
    echo '<div class="updated"><p>Settings updated successfully.</p></div>';
}

// Get the stored settings from user meta
$telegram_chat_id = get_user_meta($user_id, 'telegram_chat_id', true);
$slack_webhook_url = get_user_meta($user_id, 'slack_webhook_url', true);
$email_address = get_user_meta($user_id, 'email_address', true);
?>

<div class="wrap">
    <h1>Notification Settings</h1>
    <form method="POST">
        <table class="form-table">
            <tr>
                <th><label for="telegram_chat_id">Telegram Chat ID</label></th>
                <td><input type="text" id="telegram_chat_id" name="telegram_chat_id" value="<?php echo esc_attr($telegram_chat_id); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="slack_webhook_url">Slack Webhook URL</label></th>
                <td><input type="text" id="slack_webhook_url" name="slack_webhook_url" value="<?php echo esc_attr($slack_webhook_url); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="email_address">Email Address</label></th>
                <td><input type="email" id="email_address" name="email_address" value="<?php echo esc_attr($email_address); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php submit_button('Save Settings'); ?>
    </form>
</div>
