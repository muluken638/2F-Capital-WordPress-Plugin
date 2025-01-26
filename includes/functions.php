<?php

// Register 'Articles' Post Type
function cpp_register_articles_cpt() {
    $args = [
        'label' => 'Articles',
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'author', 'comments', 'thumbnail'],
        'menu_icon' => 'dashicons-edit',
        'capability_type' => 'cpp_article',
        'map_meta_cap' => true,
        'capabilities' => [
            'edit_post' => 'edit_cpp_article',
            'read_post' => 'read_cpp_article',
            'delete_post' => 'delete_cpp_article',
            'edit_posts' => 'edit_cpp_articles',
            'edit_others_posts' => 'edit_others_cpp_articles',
            'delete_posts' => 'delete_cpp_articles',
            'publish_posts' => 'publish_cpp_articles', // Only editor can publish
            'read_private_posts' => 'read_private_cpp_articles',
        ],
        'taxonomies' => ['cpp_categories', 'cpp_tags'],
    ];
    register_post_type('cpp_articles', $args);
}
add_action('init', 'cpp_register_articles_cpt');

// Register 'Categories' and 'Tags' Taxonomies
function cpp_register_taxonomies() {
    register_taxonomy('cpp_categories', 'cpp_articles', [
        'label' => 'Categories',
        'hierarchical' => true,
    ]);

    register_taxonomy('cpp_tags', 'cpp_articles', [
        'label' => 'Tags',
        'hierarchical' => false,
    ]);
}
add_action('init', 'cpp_register_taxonomies');

// Enqueue Custom Admin Assets
function cpp_enqueue_assets() {
    wp_enqueue_style('cpp-style', CPP_URL . 'assets/css/style.css', [], '1.0');
    wp_enqueue_script('cpp-script', CPP_URL . 'assets/js/script.js', ['jquery'], '1.0', true);
}
add_action('admin_enqueue_scripts', 'cpp_enqueue_assets');

// Set Default Post Status for Authors (Pending for Review)
function cpp_set_default_post_status($data, $postarr) {
    if (current_user_can('author') && $data['post_type'] === 'cpp_articles') {
        // If no status is set, default to 'pending' for review
        if (empty($data['post_status'])) {
            $data['post_status'] = 'pending'; // Set status to 'pending' for review
        }
    }
    return $data;
}
add_filter('wp_insert_post_data', 'cpp_set_default_post_status', 10, 2);

// Modify Author Capabilities
function cpp_modify_author_capabilities() {
    $role = get_role('author');

    if ($role) {
        // Allow authors to edit their own articles
        $role->add_cap('edit_cpp_articles');
        $role->add_cap('edit_published_cpp_articles');
        $role->add_cap('edit_pending_cpp_articles');
        $role->add_cap('delete_cpp_articles');
        
        // Remove the ability to publish posts (only editors can publish)
        $role->remove_cap('publish_cpp_articles');
    }
}
add_action('init', 'cpp_modify_author_capabilities');

// Restrict Publish Permission (Only Editors Can Publish)
function cpp_restrict_publish_permission() {
    $role = get_role('author');
    if ($role) {
        // Remove the ability to publish posts
        $role->remove_cap('publish_cpp_articles');
    }
}
add_action('init', 'cpp_restrict_publish_permission');


function remove_tools_menu_for_authors_and_editors() {
    // Check if the current user is an Author or Editor
    if (current_user_can('author') || current_user_can('editor')) {
        remove_menu_page('tools.php'); // Remove the Tools menu
        remove_menu_page('index.php');//Default Dashboard
        remove_menu_page('edit.php?post_type=post');  // Default "Articles" (posts)
        remove_menu_page('edit.php?post_type=cpp_articles');  // Custom Articles Post Type

    }
}
add_action('admin_menu', 'remove_tools_menu_for_authors_and_editors', 999);
