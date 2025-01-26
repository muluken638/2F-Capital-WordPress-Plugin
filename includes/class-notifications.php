<?php
class CPP_Notifications {

public static function send_notification($user_id, $message) {
    // Get saved settings for the user
    $telegram_chat_id = get_user_meta($user_id, 'telegram_chat_id', true);
    $slack_webhook_url = get_user_meta($user_id, 'slack_webhook_url', true);
    $email_address = get_user_meta($user_id, 'email_address', true);

    // Send Telegram notification if Chat ID is set
    if ($telegram_chat_id) {
        self::send_telegram_notification($telegram_chat_id, $message);
    }

    // Send Slack notification if Webhook URL is set
    if ($slack_webhook_url) {
        self::send_slack_notification($slack_webhook_url, $message);
    }

    // Send Email if Email Address is set
    if ($email_address) {
        self::send_email_notification($email_address, $message);
    }
}

private static function send_telegram_notification($chat_id, $message) {
    $telegram_api_url = 'https://api.telegram.org/bot' . 'YOUR_BOT_API_KEY' . '/sendMessage';
    $params = [
        'chat_id' => $chat_id,
        'text' => $message
    ];
    wp_remote_get(add_query_arg($params, $telegram_api_url));
}

private static function send_slack_notification($webhook_url, $message) {
    $payload = json_encode([
        'text' => $message
    ]);
    wp_remote_post($webhook_url, [
        'method' => 'POST',
        'body' => $payload,
        'headers' => [
            'Content-Type' => 'application/json',
        ],
    ]);
}

private static function send_email_notification($email, $message) {
    $subject = 'New Notification';
    wp_mail($email, $subject, $message);
}
}
// Initialize the notifications
// CPP_Notifications::init();