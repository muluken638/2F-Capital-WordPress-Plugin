
# 2F Capital WordPress Plugin

## Description

The 2F Capital WordPress plugin is designed to provide a portal for authors to write, manage, and track their articles. It includes a dashboard for the editor-in-chief to manage, review, and approve articles. This plugin supports custom notifications via Slack, Telegram, and WordPress notifications. It also includes role-based access control for Admin, Editor, and Author, and a responsive dashboard with charts and cards.

## Features

- **Author Portal**: Allows authors to write, track, and manage their articles.
- **Editor Dashboard**: Editors can review, approve, and manage articles submitted by authors.
- **Admin Dashboard**: Admins have full control over all articles, users, and settings.
- **Custom Notifications**: Notifications can be sent via Slack, Telegram, and Email when articles are submitted, reviewed, or published.
- **Role-Based Access Control**: Different roles (Admin, Editor, and Author) have different access levels and permissions.
- **Dashboard Cards and Charts**: The dashboards are equipped with cards for quick insights and charts for visual data representation.

## Installation

1. **Download the Plugin**: 
   - Download the plugin ZIP file from the GitHub repository.
   =>https://github.com/muluken638/2F-Capital-WordPress-Plugin-Development.git
2. **Install the Plugin**: 
   - Go to your WordPress Admin Dashboard.
   - Navigate to `Plugins` → `Add New` → `Upload Plugin`.
   - Click `Choose File` and select the ZIP file you downloaded.
   - Click `Install Now`.
   
3. **Activate the Plugin**: 
   - After installation, click `Activate` to enable the plugin on your WordPress site.

4. **Configure Plugin Settings**:
   - Go to `Settings` → `2F Capital Plugin` to configure the plugin settings.

## Notification Configuration

### Email Notifications

The plugin sends email notifications when articles are published or when comments are added to an article. 

1. **Admin Email**: Ensure that your WordPress site is set up to send emails. Go to `Settings` → `General` to set up the site's email address.
2. **Article Publish Email**: Authors will receive an email when their article is published. The subject line will be “Your Article Has Been Published” and will include the article title.
3. **New Comment Email**: Authors will receive an email notification when an editor leaves a comment on their article.

### Telegram Notifications

To send Telegram notifications to authors, the plugin requires the author's Telegram Chat ID.

1. **Get the Chat ID**: 
   - The author must provide their Telegram Chat ID to receive notifications.
   - The Chat ID can be entered in the user profile page in the WordPress Admin Panel.
   
2. **Configure Telegram Token**:
   - In the plugin settings page, enter the Telegram Bot Token (obtained from BotFather in Telegram).
   
3. **Telegram Notification**: 
   - Once configured, Telegram notifications will be sent when an article is published or when an editor adds a comment.

### Slack Notifications

To send notifications via Slack, the plugin needs a Slack Webhook URL.

1. **Get the Webhook URL**:
   - Go to your Slack workspace and create an incoming webhook URL through the Slack API or Slack App settings.
   
2. **Configure Slack**:
   - In the plugin settings page, enter the Slack Webhook URL.
   
3. **Slack Notification**: 
   - Slack notifications will be sent to the specified channel when an article is published or when a comment is added by an editor.

## User Roles and Permissions

### Admin Role
- **Access**: Full access to all features of the plugin.
- **Permissions**: 
  - Can manage all articles and users.
  - Can approve or disapprove articles.
  - Can configure the plugin settings and notifications.
  - Can view all data in the dashboard, including charts and analytics.
  
### Editor Role
- **Access**: Can manage and review articles submitted by authors.
- **Permissions**: 
  - Can edit, approve, and reject articles.
  - Can add comments to articles for authors.
  - Can view their dashboard with cards and charts but only for the articles they manage.
  
### Author Role
- **Access**: Can write, track, and manage their own articles.
- **Permissions**: 
  - Can submit articles for review and track the status.
  - Can view their own dashboard with insights on their submitted articles.
  - Can receive email, Telegram, and Slack notifications for their articles.

## Dashboard Overview

The dashboard is designed for each user role to provide relevant data. The following features are included:

- **Cards**: Display quick metrics like the number of articles submitted, approved, and pending approval.
- **Charts**: Provide visual data representation, such as the number of articles per status (pending, published, rejected), and other metrics specific to the user's role.
- **Role-Specific Data**: Admins have access to global data, Editors can see their assigned articles, and Authors can view their own submitted articles and track the approval process.

### Admin Dashboard
- **Statistics**: Overview of all articles, including counts of pending, published, and rejected articles.
- **Activity Charts**: Visual representations of article statuses over time, such as pending approvals, published articles, etc.

### Editor Dashboard
- **Article Review**: Editors can see the list of articles that need to be reviewed, along with their current status.
- **Comment Section**: Editors can leave comments for authors regarding their articles.

### Author Dashboard
- **Article Management**: Authors can see a list of their articles and track the status (pending, approved, rejected).
- **Notifications**: Authors receive notifications via email, Telegram, and Slack about changes to their article.

## Troubleshooting

- **Email Not Sending**: Ensure your WordPress site is configured to send emails. You can use SMTP plugins if emails are not being sent by default.
- **Telegram Notifications Not Working**: Ensure the correct Telegram Bot Token and Chat ID are entered in the plugin settings.
- **Slack Notifications Not Sending**: Double-check the Slack Webhook URL in the settings.
