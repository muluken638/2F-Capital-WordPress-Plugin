<?php
// Display all pending articles
$token  = '8122008791:AAFHF9dBn63PnWh8o2V9YcIaoaK0urX_jIw'; // Change to a correct Token
if ($pending_articles->have_posts()) :
    echo '<table class="gridtable">';
    echo '<thead>
            <tr>
                <th>Title</th>
                <th>Excerpt</th>
                <th>Actions</th>
                <th>Comment</th>
            </tr>
          </thead>';
    echo '<tbody>';

    while ($pending_articles->have_posts()) : $pending_articles->the_post();
        ?>
        <tr>
            <td><?php the_title(); ?></td>
            <td><?php the_excerpt(); ?></td>
            <td>
                <div class="actions">
                    <!-- Edit -->
    <div class="buttons">
        <?php if (current_user_can('edit_posts')): ?>
            <a href="<?php echo get_edit_post_link(); ?>"><i class="fas fa-edit"></i> Edit</a>
        <?php endif; ?>
    </div>

    <!-- Preview -->
    <div class="buttons">
        <a href="<?php echo get_permalink(); ?>" target="_blank"><i class="fas fa-eye"></i> Preview</a>
    </div>
    
                    <!-- Preview -->
                    <!-- Publish -->
                    <?php if (current_user_can('publish_posts')): ?>
                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?php the_ID(); ?>">
                            <input type="submit" name="publish_article" value="Publish">
                        </form>
                    <?php endif; ?>

                </div>


            </td>
            <td class="flex">
                <!-- Comment Form for Editors -->
                <?php if (current_user_can('edit_posts')): ?>
                    <form method="post" action="">
                        <input type="hidden" name="post_id" value="<?php the_ID(); ?>">
                        <textarea name="editor_comment" rows="3" placeholder="Add a comment for the author..."></textarea>
                        <input type="submit" name="submit_comment" value="Send Comment">
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    endwhile;

    echo '</tbody>';
    echo '</table>';
else :
    echo 'No pending articles to review.';
endif;



// Include the existing publish and comment handling logic below.


// Handle the publish action
if (isset($_POST['publish_article']) && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    if (current_user_can('publish_posts')) {
        // Publish the article
        $post = array(
            'ID' => $post_id,
            'post_status' => 'publish'
        );
        wp_update_post($post);
        echo '<p>Article published successfully!</p>';

        // Get the Telegram and Slack chat ID/URL from the author (user metadata)
        $user_id = get_post_field('post_author', $post_id);  // Get the post author ID
        $chatID = get_user_meta($user_id, 'telegram_chat_id', true); // Get the user's Telegram Chat ID
        $slack_url = get_user_meta($user_id, 'slack_webhook_url', true); // Get the user's Slack Webhook URL
        $author_email = get_the_author_meta('user_email', $user_id); // Get the author's email address

        // Check if a Telegram Chat ID exists and send the message
        if ($chatID) {
            $message = 'The article has been published!'; // Customize your message
            echo YourTelegramMessageFunction($chatID, $message, $token);
        }

        // Check if a Slack Webhook URL exists and send the message
        if ($slack_url) {
            $message = 'The article has been published!'; // Customize your message
            slack($slack_url, $message); // Send the message to Slack
        } else {
            echo 'No Slack Webhook URL found for the author.';
        }

        // Send email notification
        if ($author_email) {
            $subject = 'Your Article Has Been Published!';
            $email_message = "Hello, your article has been successfully published on the website.\n\nTitle: " . get_the_title($post_id);
            wp_mail($author_email, $subject, $email_message);
            echo 'Email notification sent to the author.';
        }
    }
}

// Handle the comment submission
if (isset($_POST['submit_comment']) && isset($_POST['post_id']) && isset($_POST['editor_comment'])) {
    $post_id = intval($_POST['post_id']);
    $editor_comment = sanitize_text_field($_POST['editor_comment']);
    
    if (current_user_can('edit_posts') && !empty($editor_comment)) {
        $existing_comments = get_post_meta($post_id, '_editor_comments', true);

        if (!is_array($existing_comments)) {
            $existing_comments = [];
        }

        // Add the new comment
        $existing_comments[] = $editor_comment;
        update_post_meta($post_id, '_editor_comments', $existing_comments);

        // Get the Telegram and Slack details from the author
        $user_id = get_post_field('post_author', $post_id);  // Get the post author ID
        $chatID = get_user_meta($user_id, 'telegram_chat_id', true); // Get the user's Telegram Chat ID
        $slack_url = get_user_meta($user_id, 'slack_webhook_url', true); // Get the user's Slack Webhook URL
        $author_email = get_the_author_meta('user_email', $user_id); // Get the author's email address

        // Send the comment to Telegram if a chat ID exists
        if ($chatID) {
            YourTelegramMessageFunction($chatID, $editor_comment, $token);
        }

        // Send the comment to Slack if a Slack URL exists
        if ($slack_url) {
            slack($slack_url, $editor_comment); // Send the comment to Slack
        }

        // Send email notification to the author
        if ($author_email) {
            $subject = 'New Comment on Your Article';
            $email_message = "Hello, you have received a new comment on your article:\n\n" . $editor_comment;
            wp_mail($author_email, $subject, $email_message);
            echo 'Email notification sent to the author.';
        }
    }
}

function YourTelegramMessageFunction($chatID, $message, $token) {
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    $data = ['chat_id' => $chatID, 'text' => $message, 'parse_mode' => 'HTML'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    // Handle cURL error
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return json_encode(array('error' => $error_msg));
    }

    $http_code_message = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code_message >= 200 && $http_code_message < 300) {
        $result = json_decode($response, true);
        if ($result['ok']) {
            $result = json_encode(array('success' => $result['ok']));
        } else {
            $result = json_encode(array('error' => $result));
        }
    } else {
        // Handle HTTP error
        $result = json_encode(array('error' => 'HTTP error ' . $http_code_message));
    }

    curl_close($ch);
    return $result;
}

// Function to send Slack notifications
function slack($slack_url, $message) {
    if ($slack_url && !empty($message)) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $slack_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"text\":\"$message\"}");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    } else {
        echo 'Slack Webhook URL or message is empty.';
    }
}


?>
<style>
    /* General Table Styles */
    .gridtable { width: 100%; }
@media screen and (max-width:768px) {
  .gridtable tr {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    grid-gap: 3px;
    margin-bottom: 3px;
  }
}

th, td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #f2f2f2;
}

th {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

td {
    color: #333;
    font-size: 14px;
}

/* Table Row Hover Effect */
tr:hover {
    background-color: #f9f9f9;
}

/* Buttons Styling */
/* General Button Styling */
.actions{
    display: flex;
    justify-content:space-between;
    align-items:center;
    gap:0px;
    width: 100%;
}
.buttons a {
    display: inline-flex;
    align-items: center;
    color: white;
    background-color: #007bff;
    padding: 8px 15px;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.buttons a i {
    margin-right: 8px; /* Space between the icon and text */
}

/* Button Hover Effect */
.buttons a:hover {
    background-color: #0056b3;
}

/* Icon Styling */
.buttons a i {
    font-size: 16px;
}

/* Edit Button Color */
.buttons .fa-edit {
    color: #28a745; /* Green color for edit button */
}

/* Preview Button Color */
.buttons .fa-eye {
    color: #ffc107; /* Yellow color for preview button */
}

/* Button Hover Effect for Icons */
.buttons a:hover .fa-edit {
    color: #218838; /* Dark green on hover */
}

.buttons a:hover .fa-eye {
    color: #e0a800; /* Darker yellow on hover */
}


/* Publish Button */
input[type="submit"][name="publish_article"] {
    background-color: #28a745;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

input[type="submit"][name="publish_article"]:hover {
    background-color: #218838;
}

/* Comment Section */
.flex {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

textarea {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    resize: vertical;
    font-size: 14px;
}

textarea:focus {
    outline: none;
    border-color: #007bff;
}

/* Comment Submit Button */
input[type="submit"][name="submit_comment"] {
    background-color: #ffc107;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}

input[type="submit"][name="submit_comment"]:hover {
    background-color: #e0a800;
}

/* Responsive Design */
@media (max-width: 1024px) {
    th, td {
        padding: 10px;
    }

    input[type="submit"], a {
        padding: 6px 12px;
    }

    textarea {
        width: 100%;
        font-size: 12px;
    }
}

/* Mobile Design */
@media (max-width: 768px) {
    table {
        font-size: 12px;
    }

    th, td {
        padding: 8px 10px;
    }

    td, th {
        text-align: left;
    }

    input[type="submit"], a {
        padding: 6px 12px;
    }

    textarea {
        width: 100%;
        font-size: 12px;
        padding: 8px;
    }

    .flex {
        flex-direction: column;
        gap: 8px;
    }

    form {
        width: 100%;
    }
}

@media (max-width: 480px) {
    table {
        font-size: 12px;
    }

    th, td {
        padding: 8px 8px;
    }

    textarea {
        padding: 8px;
        font-size: 12px;
    }

    input[type="submit"], a {
        padding: 5px 10px;
    }

    .flex {
        gap: 5px;
    }
}

</style>
