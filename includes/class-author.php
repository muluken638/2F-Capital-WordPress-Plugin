<?php

class Author_Role {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menus']);
        add_action('admin_init', [__CLASS__, 'grant_author_capabilities']);
    }

    public static function add_menus() {
        add_menu_page('Author Dashboard', 'Author Dashboard', 'author', 'cpp-author-dashboard', [__CLASS__, 'dashboard']);
        add_submenu_page('cpp-author-dashboard', 'All Articles', 'All Articles', 'author', 'edit.php?post_type=cpp_articles');
        add_submenu_page('cpp-author-dashboard', 'Add New', 'Add New', 'author', 'post-new.php?post_type=cpp_articles');
        add_submenu_page('cpp-author-dashboard', 'Categories', 'Categories', 'author', 'edit-tags.php?taxonomy=cpp_categories&post_type=cpp_articles');
        add_submenu_page('cpp-author-dashboard', 'Tags', 'Tags', 'author', 'edit-tags.php?taxonomy=cpp_tags&post_type=cpp_articles');
    
        add_submenu_page('cpp-author-dashboard', 'Notification Settings', 'Notification Settings', 'author', 'cpp-author-notifications', [__CLASS__, 'notifications']);

    
    }

    public static function dashboard() {
        $current_user_id = get_current_user_id();

        // Total articles by the logged-in author
        $args = [
            'post_type' => 'cpp_articles',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'author' => $current_user_id,
        ];
        $all_articles_query = new WP_Query($args);
        $total_articles = $all_articles_query->found_posts;

        // Draft articles by the logged-in author
        $args['post_status'] = 'draft';
        $draft_articles_query = new WP_Query($args);
        $draft_articles_total = $draft_articles_query->found_posts;

        // Pending review articles by the logged-in author
        $args['post_status'] = 'pending';
        $pending_articles_query = new WP_Query($args);
        $pending_articles_total = $pending_articles_query->found_posts;

        // Published articles by the logged-in author
        $args['post_status'] = 'publish';
        $published_articles_query = new WP_Query($args);
        $published_articles_total = $published_articles_query->found_posts;

        include CPP_PATH . 'templates/author-dashboard.php';
    }

    public static function notifications() {
        // Get the current user
        $current_user_id = get_current_user_id();
    
        // Fetch all posts authored by the current user
        $args = array(
            'author' => $current_user_id,
            'post_type' => 'post', // Adjust this if you have custom post types
            'post_status' => 'any', // Include all statuses
            'fields' => 'ids', // We only need post IDs
        );
        $author_posts = get_posts($args);
    
        // Collect notifications
        $notifications = [];
        foreach ($author_posts as $post_id) {
            $post_notifications = get_post_meta($post_id, '_editor_notifications', true);
            if (!empty($post_notifications) && is_array($post_notifications)) {
                foreach ($post_notifications as $notification) {
                    $notifications[] = [
                        'post_title' => get_the_title($post_id),
                        'post_id' => $post_id,
                        'message' => $notification,
                    ];
                }
            }
        }
    
        // Include the template to display notifications
        include CPP_PATH . 'templates/notifications-settings.php';
    }
    

    public static function grant_author_capabilities() {
        $author_role = get_role('author');

        // Grant the author role the capabilities to manage terms (categories and tags)
        if ($author_role) {
            $author_role->add_cap('manage_categories');
            $author_role->add_cap('edit_terms');
            $author_role->add_cap('assign_terms');
        }
    }

    
    
}

