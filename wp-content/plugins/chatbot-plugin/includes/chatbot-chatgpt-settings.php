<?php
/**
 * Chatbot ChatGPT for WordPress - Settings Page
 *
 * This file contains the code for the Chatbot ChatGPT settings page.
 * It allows users to configure the bot name, start status, and greetings.
 * 
 *
 * @package chatbot-chatgpt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function chatbot_chatgpt_settings_page() {
    add_options_page('Chatbot ChatGPT Settings', 'Chatbot ChatGPT', 'manage_options', 'chatbot-chatgpt', 'chatbot_chatgpt_settings_page_html');
}
add_action('admin_menu', 'chatbot_chatgpt_settings_page');

// Settings page HTML - Ver 1.3.0
function chatbot_chatgpt_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    chatbot_chatgpt_localize();

    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'bot_settings';

    if (isset($_GET['settings-updated'])) {
        add_settings_error('chatbot_chatgpt_messages', 'chatbot_chatgpt_message', 'Settings Saved', 'updated');
    }

    // REMOVED Ver 1.3.0
    // settings_errors('chatbot_chatgpt_messages');
    
    ?>
    <div class="wrap">
        <h1><span class="dashicons dashicons-format-chat"></span> <?php echo esc_html(get_admin_page_title()); ?></h1>

        <!-- Message Box - Ver 1.3.0 -->
        <div id="message-box-container"></div>

        <!-- Message Box - Ver 1.3.0 -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatgptSettingsForm = document.getElementById('chatgpt-settings-form');
                // Read the start status - Ver 1.4.1
                const chatgptStartStatusInput = document.getElementById('chatbot_chatgpt_start_status');
                const chatbot_chatgpt_start_status_new_visitorInput = document.getElementById('chatbot_chatgpt_start_status_new_visitor');
                const reminderCount = localStorage.getItem('reminderCount') || 0;

                if (reminderCount % 25 === 0 && reminderCount <= 200) {
                    const messageBox = document.createElement('div');
                    messageBox.id = 'rateReviewMessageBox';
                    messageBox.innerHTML = `
                    <div id="rateReviewMessageBox" style="background-color: white; border: 1px solid black; padding: 10px; position: relative;">
                        <div class="message-content" style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Gurabije su najbolji kolaci</span>
                            <button id="closeMessageBox" class="dashicons dashicons-dismiss" style="background: none; border: none; cursor: pointer; outline: none; padding: 0; margin-left: 10px;"></button>
                            
                        </div>
                    </div>
                    `;

                    document.querySelector('#message-box-container').insertAdjacentElement('beforeend', messageBox);

                    document.getElementById('closeMessageBox').addEventListener('click', function() {
                        messageBox.style.display = 'none';
                        localStorage.setItem('reminderCount', parseInt(reminderCount, 10) + 1);
                    });
                } else {
                    let reminderCount = +localStorage.getItem('reminderCount') || 0;
                    if (reminderCount < 200) {
                        reminderCount++;
                        localStorage.setItem('reminderCount', reminderCount);
                    }
                }
            });
        </script>
    
        <script>
            jQuery(document).ready(function($) {
                var chatgptSettingsForm = document.getElementById('chatgpt-settings-form');

                if (chatgptSettingsForm) {

                    chatgptSettingsForm.addEventListener('submit', function() {

                        // Changed const to var - Ver 1.5.0
                        // Get the input elements by their ids
                        var chatgptNameInput = document.getElementById('chatbot_chatgpt_bot_name');
                        var chatgpt_chatbot_bot_promptInput = document.getElementById('chatbot_chatgpt_bot_prompt');
                        var chatgptInitialGreetingInput = document.getElementById('chatbot_chatgpt_initial_greeting');
                        var chatgptSubsequentGreetingInput = document.getElementById('chatbot_chatgpt_subsequent_greeting');
                        var chatgptStartStatusInput = document.getElementById('chatbot_chatgpt_start_status');
                        var chatbot_chatgpt_start_status_new_visitorInput = document.getElementById('chatbot_chatgpt_start_status_new_visitor');
                        var chatgptDisclaimerSettingInput = document.getElementById('chatbot_chatgpt_disclaimer_setting');
                        // New options for max tokens and width - Ver 1.4.2
                        var chatgptMaxTokensSettingInput = document.getElementById('chatbot_chatgpt_max_tokens_setting');
                        var chatgptWidthSettingInput = document.getElementById('chatbot_chatgpt_width_setting');
                        // New options for diagnostics on/off - Ver 1.5.0
                        var chatgptDiagnosticsSettingInput = document.getElementById('chatbot_chatgpt_diagnostics');
                        // Avatar Settings - Ver 1.4.3
                        let chatgptAvatarIconSettingInput = document.getElementById('chatbot_chatgpt_avatar_icon_setting');
                        let chatgptCustomAvatarIconSettingInput = document.getElementById('chatbot_chatgpt_custom_avatar_icon_setting');
                        let chatgptAvatarGreetingSettingInput = document.getElementById('chatbot_chatgpt_avatar_greeting_setting');
                        // Custom Buttons - Ver 1.6.5
                        let chatgptEnableCustomButtonsInput = document.getElementById('chatbot_chatgpt_enable_custom_buttons');
                        let chatgptCustomButtonName1Input = document.getElementById('chatbot_chatgpt_custom_button_name_1');
                        let chatgptCustomButtonURL1Input = document.getElementById('chatbot_chatgpt_custom_button_url_1');
                        let chatgptCustomButtonName2Input = document.getElementById('chatbot_chatgpt_custom_button_name_2');
                        let chatgptCustomButtonURL2Input = document.getElementById('chatbot_chatgpt_custom_button_url_2');
                        // Allow File Uploads - Ver 1.7.6
                        let chatgptAllowFileUploadsInput = document.getElementById('chatbot_chatgpt_allow_file_uploads');

                        // Update the local storage with the input values, if inputs exist
                        if(chatgptNameInput) localStorage.setItem('chatbot_chatgpt_bot_name', chatgptNameInput.value);
                        if(chatgpt_chatbot_bot_promptInput) localStorage.setItem('chatbot_chatgpt_bot_prompt', chatgpt_chatbot_bot_promptInput.value);
                        if(chatgptInitialGreetingInput) localStorage.setItem('chatbot_chatgpt_initial_greeting', chatgptInitialGreetingInput.value);
                        if(chatgptSubsequentGreetingInput) localStorage.setItem('chatbot_chatgpt_subsequent_greeting', chatgptSubsequentGreetingInput.value);
                        if(chatgptStartStatusInput) localStorage.setItem('chatbot_chatgpt_start_status', chatgptStartStatusInput.value);
                        if(chatbot_chatgpt_start_status_new_visitorInput) localStorage.setItem('chatbot_chatgpt_start_status_new_visitor', chatbot_chatgpt_start_status_new_visitorInput.value);
                        if(chatgptDisclaimerSettingInput) localStorage.setItem('chatbot_chatgpt_disclaimer_setting', chatgptDisclaimerSettingInput.value);
                        // New options for max tokens and width - Ver 1.4.2
                        if(chatgptMaxTokensSettingInput) localStorage.setItem('chatbot_chatgpt_max_tokens_setting', chatgptMaxTokensSettingInput.value);
                        if(chatgptWidthSettingInput) localStorage.setItem('chatbot_chatgpt_width_setting', chatgptWidthSettingInput.value);
                        // New options for diagnostics on/off - Ver 1.5.0
                        if(chatgptDiagnosticsSettingInput) localStorage.setItem('chatbot_chatgpt_diagnostics', chatgptDiagnosticsSettingInput.value);
                        // Avatar Settings - Ver 1.5.0
                        if(chatgptAvatarIconSettingInput) localStorage.setItem('chatbot_chatgpt_avatar_icon_setting', chatgptAvatarIconSettingInput.value);
                        if(chatgptCustomAvatarIconSettingInput) localStorage.setItem('chatbot_chatgpt_custom_avatar_icon_setting', chatgptCustomAvatarIconSettingInput.value);
                        if(chatgptAvatarGreetingSettingInput) localStorage.setItem('chatbot_chatgpt_avatar_greeting_setting', chatgptAvatarGreetingSettingInput.value);
                        // Custom Buttons - Ver 1.6.5
                        if(chatgptEnableCustomButtonsInput) localStorage.setItem('chatbot_chatgpt_enable_custom_buttons', chatgptEnableCustomButtonsInput.value);
                        if(chatgptCustomButtonName1Input) localStorage.setItem('chatbot_chatgpt_custom_button_name_1', chatgptCustomButtonName1Input.value);
                        if(chatgptCustomButtonURL1Input) localStorage.setItem('chatbot_chatgpt_custom_button_url_1', chatgptCustomButtonURL1Input.value);
                        if(chatgptCustomButtonName2Input) localStorage.setItem('chatbot_chatgpt_custom_button_name_2', chatgptCustomButtonName2Input.value);
                        if(chatgptCustomButtonURL2Input) localStorage.setItem('chatbot_chatgpt_custom_button_url_2', chatgptCustomButtonURL2Input.value);
                        // Allow File Uploads - Ver 1.7.6
                        if(chatgptAllowFileUploadsInput) localStorage.setItem('chatbot_chatgpt_allow_file_uploads', chatgptAllowFileUploadsInput.value);
                    });
                }
            });
        </script>

        <script>
            window.onload = function() {
                // Assign the function to the window object to make it globally accessible
                window.selectIcon = function(id) {
                    var chatgptElement = document.getElementById('chatbot_chatgpt_avatar_icon_setting');
                    if(chatgptElement) {
                        // Clear border from previously selected icon
                        var previousIconId = chatgptElement.value;
                        var previousIcon = document.getElementById(previousIconId);
                        if(previousIcon) previousIcon.style.border = "none";  // Change "" to "none"

                        // Set border for new selected icon
                        var selectedIcon = document.getElementById(id);
                        if(selectedIcon) selectedIcon.style.border = "2px solid red";

                        // Set selected icon value in hidden input
                        chatgptElement.value = id;

                        // Save selected icon in local storage
                        localStorage.setItem('chatbot_chatgpt_avatar_icon_setting', id);
                    }
                }

                // If no icon has been selected, select the first one by default
                var iconFromStorage = localStorage.getItem('chatbot_chatgpt_avatar_icon_setting');
                var chatgptElement = document.getElementById('chatbot_chatgpt_avatar_icon_setting');
                if(chatgptElement) {
                    if (iconFromStorage) {
                        window.selectIcon(iconFromStorage);
                    } else if (chatgptElement.value === '') {
                        window.selectIcon('icon-001.png');
                    }
                }
            }
        </script>

        <h2 class="nav-tab-wrapper">
            <a href="?page=chatbot-chatgpt&tab=bot_settings" class="nav-tab <?php echo $active_tab == 'bot_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
            <a href="?page=chatbot-chatgpt&tab=api_model" class="nav-tab <?php echo $active_tab == 'api_model' ? 'nav-tab-active' : ''; ?>">API/Model</a>
            <a href="?page=chatbot-chatgpt&tab=avatar" class="nav-tab <?php echo $active_tab == 'avatar' ? 'nav-tab-active' : ''; ?>">Avatars</a>
        </h2>

        <form id="chatgpt-settings-form" action="options.php" method="post">
            <?php
            if ($active_tab == 'bot_settings') {
                settings_fields('chatbot_chatgpt_settings');
                do_settings_sections('chatbot_chatgpt_settings');

            } elseif ($active_tab == 'api_model') {
                settings_fields('chatbot_chatgpt_api_model');
                do_settings_sections('chatbot_chatgpt_api_model');

            }
             elseif ($active_tab == 'avatar') {
                settings_fields('chatbot_chatgpt_avatar');
                do_settings_sections('chatbot_chatgpt_avatar');
            }
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <!-- Added closing tags for body and html - Ver 1.4.1 -->
    </body>
    </html>
    <?php
}
