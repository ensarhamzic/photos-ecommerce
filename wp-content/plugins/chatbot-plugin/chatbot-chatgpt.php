<?php
/*
 * Plugin Name: Chatbot ChatGPT
 * Plugin URI:  TODO: Add the plugin URI
 * Description: Simple plugin for chatGPT
 * Version:     1.8.0
 * Author:      Gurabije
*/

// If this file is called directly, die.
defined( 'WPINC' ) || die;

// If this file is called directly, die.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Main plugin file
define('CHATBOT_CHATGPT_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

// Declare Globals here
global $wpdb; // Declare the global $wpdb object

// Uniquely Identify the Visitor
global $sessionId; // Declare the global $sessionID variable

if ($sessionId == '') {
    session_start();
    $sessionId = session_id();
    session_write_close();
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-globals.php';

require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-call-gpt-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-call-gpt-assistant.php';

// Include necessary files - Knowledge Navigator
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-acquire.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-acquire-words.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-acquire-word-pairs.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-analysis.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-db.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-enhance-response.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-scheduler.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-kn-settings.php'; 

require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-db-management.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-file-upload.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-api-model.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-api-test.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-avatar.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-custom-gpts.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-links.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-localization.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-localize.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-notices.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-registration.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-setup.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-settings-skins.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-threads.php'; 
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-transients.php'; 
require_once plugin_dir_path(__FILE__) . 'includes/chatbot-chatgpt-upgrade.php'; 

add_action('init', 'my_custom_buffer_start');
function my_custom_buffer_start() {
    ob_start();
}

// Check for Upgrades
// if (!esc_attr(get_option('chatbot_chatgpt_upgraded'))) {
//     chatbot_chatgpt_upgrade();
//     update_option('chatbot_chatgpt_upgraded', 'Yes');
// }

// Diagnotics on/off setting can be found on the Settings tab
update_option('chatbot_chatgpt_diagnostics', 'Off');
global $chatbot_chatgpt_diagnostics;
$chatbot_chatgpt_diagnostics = esc_attr(get_option('chatbot_chatgpt_diagnostics', 'Off'));

// Custom buttons on/off setting can be found on the Settings tab
global $chatbot_chatgpt_enable_custom_buttons;
$chatbot_chatgpt_enable_custom_buttons = esc_attr(get_option('chatbot_chatgpt_enable_custom_buttons', 'Off'));

// Allow file uploads on/off setting can be found on the Settings tab
global $chatbot_chatgpt_allow_file_uploads;
// TEMP OVERRIDE
// update_option('chatbot_chatgpt_allow_file_uploads', 'No');
$chatbot_chatgpt_allow_file_uploads = esc_attr(get_option('chatbot_chatgpt_allow_file_uploads', 'No'));

// Suppress Notices on/off setting can be found on the Settings tab
global $chatbot_chatgpt_suppress_notices;
$chatbot_chatgpt_suppress_notices = esc_attr(get_option('chatbot_chatgpt_suppress_notices', 'Off'));

// Suppress Attribution on/off setting can be found on the Settings tab
global $chatbot_chatgpt_suppress_attribution;
$chatbot_chatgpt_suppress_attribution = esc_attr(get_option('chatbot_chatgpt_suppress_attribution', 'Off'));

// Suppress Learnings Message
global $chatbot_chatgpt_suppress_learnings;
$chatbot_chatgpt_suppress_learnings = esc_attr(get_option('chatbot_chatgpt_suppress_learnings', 'Random'));

// Context History 
$context_history = [];

function chatbot_chatgpt_enqueue_admin_scripts() {
    wp_enqueue_script('chatbot_chatgpt_admin', plugins_url('assets/js/chatbot-chatgpt-admin.js', __FILE__), array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'chatbot_chatgpt_enqueue_admin_scripts');

// Activation, deactivation, and uninstall functions
register_activation_hook(__FILE__, 'chatbot_chatgpt_activate');
register_deactivation_hook(__FILE__, 'chatbot_chatgpt_deactivate');
register_uninstall_hook(__FILE__, 'chatbot_chatgpt_uninstall');
add_action('upgrader_process_complete', 'chatbot_chatgpt_upgrade_completed', 10, 2);

// Enqueue plugin scripts and styles
function chatbot_chatgpt_enqueue_scripts() {

    // Enqueue the styles
    wp_enqueue_style('dashicons');
    wp_enqueue_style('chatbot-chatgpt-css', plugins_url('assets/css/chatbot-chatgpt.css', __FILE__));

    // Enqueue the scripts
    wp_enqueue_script('chatbot-chatgpt-js', plugins_url('assets/js/chatbot-chatgpt.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('chatbot-chatgpt-local', plugins_url('assets/js/chatbot-chatgpt-local.js', __FILE__), array('jquery'), '1.0', true);
    // wp_enqueue_script('chatbot-chatgpt-file-upload-js', plugins_url('assets/js/chatbot-chatgpt-file-upload.js', __FILE__), array('jquery'), '1.0', true);
    
    // Localize the data for user id and page id
    $user_id = get_current_user_id();
    $page_id = get_the_ID();
    $script_data_array = array(
        'user_id' => $user_id,
        'page_id' => $page_id
    );

    $defaults = array(
        'chatbot_chatgpt_bot_name' => 'Chatbot ChatGPT',
        'chatbot_chatgpt_bot_prompt' => 'Enter your question ...',
        'chatbot_chatgpt_initial_greeting' => 'Hello! How can I help you today?',
        'chatbot_chatgpt_subsequent_greeting' => 'Hello again! How can I help you?',
        'chatbot_chatgpt_display_style' => 'floating',
        'chatbot_chatgpt_assistant_alias' => 'primary',
        'chatbot_chatgpt_start_status' => 'closed',
        'chatbot_chatgpt_start_status_new_visitor' => 'closed',
        'chatbot_chatgpt_disclaimer_setting' => 'No',
        'chatbot_chatgpt_max_tokens_setting' => '150',
        'chatbot_chatgpt_width_setting' => 'Narrow',
        'chatbot_chatgpt_diagnostics' => 'Off',
        'chatbot_chatgpt_avatar_icon_setting' => 'icon-001.png',
        'chatbot_chatgpt_avatar_icon_url_setting' => '',
        'chatbot_chatgpt_custom_avatar_icon_setting' => 'icon-001.png',
        'chatbot_chatgpt_avatar_greeting_setting' => 'Howdy!!! Great to see you today! How can I help you?',
        'chatbot_chatgpt_model_choice' => 'gpt-3.5-turbo',
        'chatbot_chatgpt_max_tokens_setting' => 150,
        'chatbot_chatgpt_conversation_context' => 'You are a versatile, friendly, and helpful assistant designed to support me in a variety of tasks.',
        'chatbot_chatgpt_enable_custom_buttons' => 'Off',
        'chatbot_chatgpt_custom_button_name_1' => '',
        'chatbot_chatgpt_custom_button_url_1' => '',
        'chatbot_chatgpt_custom_button_name_2' => '',
        'chatbot_chatgpt_custom_button_url_2' => '',
        'chatbot_chatgpt_allow_file_uploads' => 'No'
    );

    $option_keys = array(
        'chatbot_chatgpt_bot_name',
        'chatbot_chatgpt_bot_prompt',
        'chatbot_chatgpt_initial_greeting',
        'chatbot_chatgpt_subsequent_greeting',
        'chatbot_chatgpt_display_style',
        'chatbot_chatgpt_assistant_alias',
        'chatbot_chatgpt_start_status',
        'chatbot_chatgpt_start_status_new_visitor',
        'chatbot_chatgpt_disclaimer_setting',
        'chatbot_chatgpt_max_tokens_setting',
        'chatbot_chatgpt_width_setting',
        'chatbot_chatgpt_diagnostics',
        'chatbot_chatgpt_avatar_icon_setting',
        'chatbot_chatgpt_avatar_icon_url_setting',
        'chatbot_chatgpt_custom_avatar_icon_setting',
        'chatbot_chatgpt_avatar_greeting_setting',
        'chatbot_chatgpt_enable_custom_buttons',
        'chatbot_chatgpt_custom_button_name_1',
        'chatbot_chatgpt_custom_button_url_1',
        'chatbot_chatgpt_custom_button_name_2',
        'chatbot_chatgpt_custom_button_url_2',
        'chatbot_chatgpt_allow_file_uploads'
    );

    $chatbot_settings = array();
    foreach ($option_keys as $key) {
        $default_value = isset($defaults[$key]) ? $defaults[$key] : '';
        $chatbot_settings[$key] = esc_attr(get_option($key, $default_value));
    }

    $chatbot_settings['chatbot_chatgpt_icon_base_url'] = plugins_url( 'assets/icons/', __FILE__ );

    // Localize the data for javascripts
    wp_localize_script('chatbot-chatgpt-js', 'php_vars', $script_data_array);

    wp_localize_script('chatbot-chatgpt-js', 'plugin_vars', array(
        'pluginUrl' => plugins_url('', __FILE__ ),
    ));

    wp_localize_script('chatbot-chatgpt-local', 'chatbotSettings', $chatbot_settings);

    wp_localize_script('chatbot-chatgpt-js', 'chatbot_chatgpt_params', array(
        'pluginUrl' => plugins_url('', __FILE__ ),
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

    wp_localize_script('chatbot-chatgpt-upload-trigger-js', 'chatbot_chatgpt_params', array(
        'pluginUrl' => plugins_url('', __FILE__ ),
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

    $chatbot_settings = array();
    foreach ($option_keys as $key) {
        $default_value = isset($defaults[$key]) ? $defaults[$key] : '';
        $chatbot_settings[$key] = esc_attr(get_option($key, $default_value));
    }

    echo "<script type=\"text/javascript\">
    document.addEventListener('DOMContentLoaded', (event) => {
        // Encode the chatbot settings array into JSON format for use in JavaScript
        let chatbotSettings = " . json_encode($chatbot_settings) . ";

        Object.keys(chatbotSettings).forEach((key) => {
            if(!localStorage.getItem(key)) {
                // DIAG - Log the key and value
                // console.log('Chatbot ChatGPT: NOTICE: Setting ' + key + ' in localStorage');
                localStorage.setItem(key, chatbotSettings[key]);
            } else {
                // DIAG - Log the key and value
                // console.log('Chatbot ChatGPT: NOTICE: ' + key + ' is already set in localStorage');
            }
        });
    });
    </script>";
    
}
add_action('wp_enqueue_scripts', 'chatbot_chatgpt_enqueue_scripts');

// Settings and Deactivation Links 
function enqueue_jquery_ui() {
    wp_enqueue_style('wp-jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-dialog');
}
add_action( 'admin_enqueue_scripts', 'enqueue_jquery_ui' );

// Schedule Cleanup of Expired Transients
if (!wp_next_scheduled('chatbot_chatgpt_cleanup_event')) {
    wp_schedule_event(time(), 'daily', 'chatbot_chatgpt_cleanup_event');
}
add_action('chatbot_chatgpt_cleanup_event', 'clean_specific_expired_transients');

// Schedule Conversation Log Cleanup
if (!wp_next_scheduled('chatbot_chatgpt_conversation_log_cleanup_event')) {
    wp_schedule_event(time(), 'daily', 'chatbot_chatgpt_conversation_log_cleanup_event');
}
add_action('chatbot_chatgpt_conversation_log_cleanup_event', 'chatbot_chatgpt_conversation_log_cleanup');

// Handle Ajax requests
function chatbot_chatgpt_send_message() {

    // Global variables
    global $sessionId;
    global $thread_Id;

    // Retrieve the API key
    $api_key = esc_attr(get_option('chatbot_chatgpt_api_key'));
    // Retrieve the Use GPT Assistant Id
    $model = esc_attr(get_option('chatbot_chatgpt_model_choice', 'gpt-3.5-turbo'));
    if ($model == 'gpt-4-turbo') {
        $model = 'gpt-4-1106-preview';
    }
    // DIAG - Diagnostics
    // chatbot_chatgpt_back_trace( 'NOTICE', '$model: ' . $model);
    // Retrieve the Max tokens
    $max_tokens = esc_attr(get_option('chatbot_chatgpt_max_tokens_setting', 150));
    // Send only clean text via the API
    $message = sanitize_text_field($_POST['message']);

    // Check API key and message
    if (!$api_key || !$message) {
        wp_send_json_error('Invalid API key or message');
    }

    $thread_Id = '';
    $assistant_id = '';
    $user_id = '';
    $page_id = '';
    // error_log ('$sessionId ' . $sessionId);
    
    // Check the transient for the Assistant ID
    $user_id = intval($_POST['user_id']);
    $page_id = intval($_POST['page_id']); 
    // DIAG - Diagnostics
    // chatbot_chatgpt_back_trace( 'NOTICE', '$user_id ' . $user_id);
    // chatbot_chatgpt_back_trace( 'NOTICE', '$page_id ' . $page_id);
    $chatbot_settings = get_chatbot_chatgpt_transients( 'dipslay_style', $user_id, $page_id);
    $chatbot_settings = get_chatbot_chatgpt_transients( 'assistant_alias', $user_id, $page_id);
    $display_style = isset($chatbot_settings['display_style']) ? $chatbot_settings['display_style'] : '';
    $chatbot_chatgpt_assistant_alias = isset($chatbot_settings['assistant_alias']) ? $chatbot_settings['assistant_alias'] : '';
    $chatbot_settings = get_chatbot_chatgpt_threads($user_id, $page_id);
    $assistant_id = isset($chatbot_settings['assistantID']) ? $chatbot_settings['assistantID'] : '';
    $thread_Id = isset($chatbot_settings['threadID']) ? $chatbot_settings['threadID'] : '';

    // Assistants
    // $chatbot_chatgpt_assistant_alias == 'original'; // Default
    // $chatbot_chatgpt_assistant_alias == 'primary';
    // $chatbot_chatgpt_assistant_alias == 'alternate';
    // $chatbot_chatgpt_assistant_alias == 'asst_xxxxxxxxxxxxxxxxxxxxxxxx'; // GPT Assistant Id
  
    // Which Assistant ID to use
    if ($chatbot_chatgpt_assistant_alias == 'original') {
        $use_assistant_id = 'No';
        // error_log ('Using Original GPT Assistant Id');
    } elseif ($chatbot_chatgpt_assistant_alias == 'primary') {
        $assistant_id = esc_attr(get_option('chatbot_chatgpt_assistant_id'));
        $use_assistant_id = 'Yes';
        // error_log ('Using Primary GPT Assistant Id ' . $assistant_id);
        // Check if the GPT Assistant Id is blank, null, or "Please provide the Customer GPT Assistant Id."
        if (empty($assistant_id) || $assistant_id == "Please provide the Customer GPT Assistant Id.") {
            // Override the $use_assistant_id and set it to 'No'
            $use_assistant_id = 'No';
            // error_log ('Falling back to ChatGPT API');
        }
    } elseif ($chatbot_chatgpt_assistant_alias == 'alternate') {
        $assistant_id = esc_attr(get_option('chatbot_chatgpt_assistant_id_alternate'));
        $use_assistant_id = 'Yes';
        // error_log ('Using Alternate GPT Assistant Id ' . $assistant_id);
        if (empty($assistant_id) || $assistant_id == "Please provide the Customer GPT Assistant Id.") {
            // Override the $use_assistant_id and set it to 'No'
            $use_assistant_id = 'No';
            // error_log ('Falling back to ChatGPT API');
        }
    } else {
        // Reference GPT Assistant IDs directly
        if (substr($chatbot_chatgpt_assistant_alias, 0, 5) === 'asst_') {
            // DIAG - Diagnostics
            // chatbot_chatgpt_back_trace( 'NOTICE', 'Using GPT Assistant Id: ' . $chatbot_chatgpt_assistant_alias);
            $assistant_id = $chatbot_chatgpt_assistant_alias;
            $use_assistant_id = 'Yes';
            // error_log ('Using GPT Assistant Id ' . $assistant_id);
        } else {
            // DIAG - Diagnostics
            // chatbot_chatgpt_back_trace( 'NOTICE', 'Using ChatGPT API: ' . $chatbot_chatgpt_assistant_alias);
            // Override the $use_assistant_id and set it to 'No'
            $use_assistant_id = 'No';
            // error_log ('Falling back to ChatGPT API');
        }
    }

    // Decide whether to use an Assistant or ChatGPT
    if ($use_assistant_id == 'Yes') {
        // chatbot_chatgpt_back_trace( 'NOTICE', 'Using GPT Assistant Id: ' . $use_assistant_id);

        // chatbot_chatgpt_back_trace( 'NOTICE', '* * * chatbot-chatgpt.php * * *');
        // chatbot_chatgpt_back_trace( 'NOTICE', '$user_id ' . $user_id);
        // chatbot_chatgpt_back_trace( 'NOTICE', '$page_id ' . $page_id);
        // chatbot_chatgpt_back_trace( 'NOTICE', '* * * chatbot-chatgpt.php * * *');

        // Send message to Custom GPT API

        // error_log ('$message ' . $message);
        append_message_to_conversation_log($sessionId, $user_id, $page_id, 'Visitor', $thread_Id, $assistant_id, $message);
        
        $response = chatbot_chatgpt_custom_gpt_call_api($api_key, $message, $assistant_id, $thread_Id, $user_id, $page_id);

        // error_log ('$response ' . $response);
        append_message_to_conversation_log($sessionId, $user_id, $page_id, 'Chatbot', $thread_Id, $assistant_id, $response);

        // Use TF-IDF to enhance response
        $response = $response . chatbot_chatgpt_enhance_with_tfidf($message);
        // chatbot_chatgpt_back_trace( 'NOTICE', ['message' => 'response', 'response' => $response]);
        ob_clean();
        if (substr($response, 0, 6) === 'Error:' || substr($response, 0, 7) === 'Failed:') {
            // Return response
            wp_send_json_error('Oops! Something went wrong on our end. Please try again later');
        } else {
            // Return response
            wp_send_json_success($response);
        }
    } else {
        // DIAG - Diagnostics
        // chatbot_chatgpt_back_trace( 'NOTICE', 'Using ChatGPT API: ' . $use_assistant_id);
        // chatbot_chatgpt_back_trace( 'NOTICE', '$assistant_id: ' . $assistant_id);
        $response = chatbot_chatgpt_call_api($api_key, $message);
        // DIAG - Diagnostics
        // chatbot_chatgpt_back_trace( 'NOTICE', ['message' => 'BEFORE CALL TO ENHANCE TFIDF', 'response' => $response]);
        // Use TF-IDF to enhance response
        $response = $response . chatbot_chatgpt_enhance_with_tfidf($message);
        // DIAG - Diagnostics
        // chatbot_chatgpt_back_trace( 'NOTICE', ['message' => 'AFTER CALL TO ENHANCE TFIDF', 'response' => $response]);
        // Return response
        wp_send_json_success($response);
    }

    wp_send_json_error('Oops, I fell through the cracks!');

}

add_action('wp_ajax_chatbot_chatgpt_send_message', 'chatbot_chatgpt_send_message');
add_action('wp_ajax_nopriv_chatbot_chatgpt_send_message', 'chatbot_chatgpt_send_message');

add_action('wp_ajax_chatbot_chatgpt_upload_file_to_assistant', 'chatbot_chatgpt_upload_file_to_assistant');
add_action('wp_ajax_nopriv_chatbot_chatgpt_upload_file_to_assistant', 'chatbot_chatgpt_upload_file_to_assistant');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'chatbot_chatgpt_plugin_action_links');

function chatbot_chatgpt_kn_status_activation() {
    add_option('chatbot_chatgpt_kn_status', 'Never Run');
    // clear any old scheduled runs
    if (wp_next_scheduled('crawl_scheduled_event_hook')) {
        wp_clear_scheduled_hook('crawl_scheduled_event_hook');
    }
    if (wp_next_scheduled('knowledge_navigator_scan_hook')) {
        wp_clear_scheduled_hook('knowledge_navigator_scan_hook'); // Clear scheduled runs
    }
}
register_activation_hook(__FILE__, 'chatbot_chatgpt_kn_status_activation');

// Clean Up in Aisle 4
function chatbot_chatgpt_kn_status_deactivation() {
    delete_option('chatbot_chatgpt_kn_status');
    wp_clear_scheduled_hook('knowledge_navigator_scan_hook'); 
}
register_deactivation_hook(__FILE__, 'chatbot_chatgpt_kn_status_deactivation');

function addEntry($transient_name, $newEntry) {
    $context_history = get_transient($transient_name);
    if (!$context_history) {
        $context_history = [];
    }

    // Determine the total length of all existing entries
    $totalLength = 0;
    foreach ($context_history as $entry) {
        if (is_string($entry)) {
            $totalLength += strlen($entry);
        } elseif (is_array($entry)) {
            $totalLength += strlen(json_encode($entry)); // Convert to string if an array
        }
    }

    // IDEA - How will the new threading option from OpenAI change how this works?
    // Define thresholds for the number of entries to keep
    $maxEntries = 30; // Default maximum number of entries
    if ($totalLength > 5000) { // Higher threshold
        $maxEntries = 20;
    }
    if ($totalLength > 10000) { // Lower threshold
        $maxEntries = 10;
    }

    while (count($context_history) >= $maxEntries) {
        array_shift($context_history); // Remove the oldest element
    }

    if (is_array($newEntry)) {
        $newEntry = json_encode($newEntry); // Convert the array to a string
    }

    array_push($context_history, $newEntry); // Append the new element
    set_transient($transient_name, $context_history); // Update the transient
}

function concatenateHistory($transient_name) {
    $context_history = get_transient($transient_name);
    if (!$context_history) {
        return ''; // Return an empty string if the transient does not exist
    }
    return implode(' ', $context_history); // Concatenate the array values into a single string
}

function enqueue_greetings_script() {
    global $chatbot_chatgpt_diagnostics;
    // chatbot_chatgpt_back_trace( 'NOTICE', "enqueue_greetings_script() called");

    wp_enqueue_script('greetings', plugin_dir_url(__FILE__) . 'assets/js/greetings.js', array('jquery'), null, true);

    $greetings = array(
        'initial_greeting' => esc_attr(get_option('chatbot_chatgpt_initial_greeting', 'Hello! How can I help you today?')),
        'subsequent_greeting' => esc_attr(get_option('chatbot_chatgpt_subsequent_greeting', 'Hello again! How can I help you?')),
    );

    wp_localize_script('greetings', 'greetings_data', $greetings);

}
add_action('wp_enqueue_scripts', 'enqueue_greetings_script');
