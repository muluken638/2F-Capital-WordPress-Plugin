<?php
class Editor_Role {
    public static function init() {
        // Add action to hook into the admin menu
        add_action('admin_menu', [__CLASS__, 'add_menus']);
        
        // Hook to remove "Add New" buttons for editors
        add_action('admin_menu', [__CLASS__, 'hide_add_new_post_button'], 999);  // Ensure it's late enough
        
        // Grant editor capabilities
        add_action('admin_init', [__CLASS__, 'grant_editor_capabilities']);

        // Hook into the comment submission action
        add_action('comment_post', [__CLASS__, 'send_telegram_notification'], 10, 2);
        // Also add the publishing action for articles
        add_action('publish_post', [__CLASS__, 'send_publish_telegram_notification'], 10, 2);

        add_action('comment_post', 'notify_on_editor_comment', 10, 2);

    }

    public static function grant_editor_capabilities() {
        $editor_role = get_role('editor');
        
        if ($editor_role) {
            $editor_role->remove_cap('create_posts');
            $editor_role->remove_cap('create_cpp_articles'); // Remove ability to create cpp_articles
        }
    }

    public static function hide_add_new_post_button() {
        if (current_user_can('editor')) {
            global $submenu;
            
            // Remove "Add New" for regular posts
            if (isset($submenu['edit.php'])) {
                foreach ($submenu['edit.php'] as $key => $value) {
                    if ($value[0] === 'Add New') {
                        unset($submenu['edit.php'][$key]);
                    }
                }
            }

            // Remove "Add New" for custom post type (cpp_articles)
            if (isset($submenu['edit.php?post_type=cpp_articles'])) {
                foreach ($submenu['edit.php?post_type=cpp_articles'] as $key => $value) {
                    if ($value[0] === 'Add New') {
                        unset($submenu['edit.php?post_type=cpp_articles'][$key]);
                    }
                }
            }
        }
    }

    public static function add_menus() {
        add_menu_page('Editor Dashboard', 'Editor Dashboard', 'editor', 'cpp-editor-dashboard', [__CLASS__, 'dashboard']);
        add_submenu_page('cpp-editor-dashboard', 'Pending Reviews', 'Pending Reviews', 'editor', 'cpp-pending-reviews', [__CLASS__, 'pending_reviews']);
        add_submenu_page('cpp-editor-dashboard', 'All Articles', 'All Articles', 'editor', 'edit.php?post_type=cpp_articles');
        // add_submenu_page('cpp-editor-dashboard', 'Analytics', 'Analytics', 'editor', 'cpp-analytics', [__CLASS__, 'analytics']);
    }

    public static function dashboard() {
        // Total articles
        $args = [
            'post_type' => 'cpp_articles',
            'post_status' => 'any',
            'posts_per_page' => -1
        ];
        $all_articles_query = new WP_Query($args);
        $total_articles = $all_articles_query->found_posts;
    
        // Published articles
        $args['post_status'] = 'publish';
        $published_articles_query = new WP_Query($args);
        $published_articles = $published_articles_query->found_posts;
    
        // Pending review articles by the logged-in author
        $args['post_status'] = 'pending';
        $pending_articles_query = new WP_Query($args);
        $pending_articles_total = $pending_articles_query->found_posts;
        
        include CPP_PATH . 'templates/editor-dashboard.php';
    }

    public static function pending_reviews() {
        // Query for pending articles with 'submitted' status (or custom taxonomy)
        $args = [
            'post_type' => 'cpp_articles',
            'post_status' => 'pending', // Adjust if using a custom status
            'posts_per_page' => -1
        ];

        $pending_articles = new WP_Query($args);

        // Include the pending reviews template
        include CPP_PATH . 'templates/pending-reviews.php';
    }

    public static function analytics() {
        // Get the total count of published and rejected articles
        $args = [
            'post_type' => 'cpp_articles',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ];
        $published_articles = new WP_Query($args);
        $total_published = $published_articles->found_posts;

        $args = [
            'post_type' => 'cpp_articles',
            'post_status' => 'draft', // Assuming rejected articles are in draft status
            'posts_per_page' => -1
        ];
        $rejected_articles = new WP_Query($args);
        $total_rejected = $rejected_articles->found_posts;

        // Pass data to the template
        include CPP_PATH . 'templates/analytics-charts.php';
    }

// Hook into comment posting

function notify_on_editor_comment($comment_ID, $comment_approved) {
    // Only proceed if the comment is approved
    if ($comment_approved) {
        // Get the comment object
        $comment = get_comment($comment_ID);

        // Get the user who made the comment
        $user_id = $comment->user_id;

        // Check if the user is an Editor
        $user = get_user_by('id', $user_id);
        if (in_array('editor', $user->roles)) {
            // If the user is an Editor, send the notification
            $editor_email = get_option('admin_email');  // Use admin's email to notify

            $subject = "New comment by Editor";
            $message = "An Editor has posted a new comment on the post titled: " . get_the_title($comment->comment_post_ID) . "\n\n";
            $message .= "Comment: " . $comment->comment_content . "\n";
            $message .= "Post Link: " . get_permalink($comment->comment_post_ID);

            // Send email notification to admin or specific email
            wp_mail($editor_email, $subject, $message);

        }
    }
}



    // Send Telegram notification on new comment
    public static function send_telegram_notification($comment_ID, $comment_approved) {
        // Check if the comment is approved
        if ($comment_approved) {
            // Get the comment object
            $comment = get_comment($comment_ID);

            // Get the post author's Telegram chat ID (store this in user meta or a separate table)
            $author_id = $comment->user_id ? $comment->user_id : $comment->comment_author;
            $author_telegram_chat_id = get_user_meta($author_id, 'telegram_chat_id', true);

            // If the author's Telegram chat ID is found, send a message
            if ($author_telegram_chat_id) {
                $message = "New comment on your article: '{$comment->comment_post_ID}'\nComment: " . $comment->comment_content;
                self::send_telegram_message($author_telegram_chat_id, $message);
            }
        }
    }

    // Send Telegram notification on article publish
    public static function send_publish_telegram_notification($post_ID, $post) {
        // Get the post author
        $author_id = $post->post_author;
        $author_telegram_chat_id = get_user_meta($author_id, 'telegram_chat_id', true);

        // If the author's Telegram chat ID is found, send a message
        if ($author_telegram_chat_id) {
            $message = "Your article '{$post->post_title}' has been published successfully!";
            self::send_telegram_message($author_telegram_chat_id, $message);
        }
    }

    // Helper function to send message to Telegram
    public static function send_telegram_message($chat_id, $message) {
        // Get Telegram Bot Token and Chat ID from settings
        $bot_token = get_option('cpp_telegram_bot_token'); // Fetch from settings page
        $telegram_url = "https://api.telegram.org/bot$bot_token/sendMessage";
        
        // Create the request payload
        $params = [
            'chat_id' => $chat_id,  // Chat ID of the user (author)
            'text' => $message,     // The message to be sent
        ];

        // Send the request using wp_remote_post
        $response = wp_remote_post($telegram_url, [
            'method'    => 'POST',
            'body'      => $params,
            'timeout'   => 60,
            'sslverify' => false,
        ]);
        
        // Check for any error in the response
        if (is_wp_error($response)) {
            error_log('Telegram notification failed: ' . $response->get_error_message());
        }
    }
}
