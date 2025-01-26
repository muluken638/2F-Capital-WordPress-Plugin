<?php
/*
Plugin Name: Custom Post Plugin
Description: Custom post plugin with roles for Admin, Editor, and Author.
Version: 1.0
Author: Your Name
*/

// Prevent direct access
if (!defined('ABSPATH')) exit;

// Define constants
define('CPP_PATH', plugin_dir_path(__FILE__));
define('CPP_URL', plugin_dir_url(__FILE__));

// Include required files
require_once CPP_PATH . 'includes/functions.php';
require_once CPP_PATH . 'includes/class-author.php';
require_once CPP_PATH . 'includes/class-editor.php';
require_once CPP_PATH . 'includes/class-notifications.php';
require_once CPP_PATH . 'includes/class-analytics.php';
// In your plugin or theme's function to display the settings page:
function cpp_notifications_settings_page() {
    include( plugin_dir_path( __FILE__ ) . 'templates/notifications-settings.php');
}

// custom-post-plugin.php

// Include the notifications class
include_once plugin_dir_path(__FILE__) . 'includes/class-notifications.php';

// Initialize notifications
// CPP_Notifications::init();

// Initialize Plugin
function cpp_init() {
    Author_Role::init();
    Editor_Role::init();
    // CPP_Notifications::init();
    CPP_Analytics::init();
}
add_action('plugins_loaded', 'cpp_init');

// Enqueue plugin styles
function cpp_enqueue_styles() {
    wp_enqueue_style('cpp-style', plugin_dir_url(__FILE__) . 'css/style.css'); // Correct path to the CSS file
}
add_action('wp_enqueue_scripts', 'cpp_enqueue_styles');
function remove_posts_menu() {
    remove_menu_page('edit.php');  // Removes the Posts menu
}
add_action('admin_menu', 'remove_posts_menu', 999);


function cpp_register_settings() {
    // Register settings for Telegram, Slack, and Email
    register_setting('cpp_notifications_settings_group', 'cpp_telegram_bot_token');
    register_setting('cpp_notifications_settings_group', 'cpp_telegram_chat_id');
    register_setting('cpp_notifications_settings_group', 'cpp_slack_webhook_url');
    register_setting('cpp_notifications_settings_group', 'cpp_notification_email');
}

add_action('admin_init', 'cpp_register_settings');



function cpp_register_settings_page() {
    add_menu_page(
        'Notification Settings',           // Page title
        'Notification Settings',           // Menu title
        'manage_options',                  // Capability required (admin role)
        'cpp-notifications',               // Menu slug
        'cpp_notifications_settings_page', // Function to display settings page
        'dashicons-email-alt',             // Icon for the menu
        80                                  // Position of the menu item
    );
}

add_action('admin_menu', 'cpp_register_settings_page');


function cpp_register_post_type() {
    register_post_type('cpp_articles', [
        'label' => 'Articles',
        'public' => true,
        'show_in_menu' => true,
        'supports' => ['title', 'editor', 'author', 'comments'],
        'capability_type' => 'cpp_article',
        'map_meta_cap' => true,
        'capabilities' => [
            'edit_posts' => 'edit_cpp_articles',
            'edit_others_posts' => 'edit_others_cpp_articles',
            'publish_posts' => 'publish_cpp_articles',
            'read_private_posts' => 'read_private_cpp_articles',
            'delete_posts' => 'delete_cpp_articles',
            'delete_others_posts' => 'delete_others_cpp_articles',
        ],
    ]);
}
add_action('init', 'cpp_register_post_type');
