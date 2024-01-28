<?php
/**
 * Chatbot ChatGPT for WordPress - Shortcode Registration
 *
 * This file contains the code for registering the shortcode used
 * to display the Chatbot ChatGPT on the website.
 *
 * @package chatbot-chatgpt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function chatbot_chatgpt_shortcode($atts) {

    global $chatbot_chatgpt_display_style;
    global $chatbot_chatgpt_assistant_alias;

    // EXAMPLE - Shortcode Attributes
    // [chatbot_chatgpt] - Default values, floating style, uses OpenAI's ChatGPT
    // [chatbot_chatgpt style="floating"] - Floating style, uses OpenAI's ChatGPT
    // [chatbot_chatgpt style="embedded"] - Embedded style, uses OpenAI's ChatGPT
    // [chatbot_chatgpt style="floating" assistant="primary"] - Floating style, GPT Assistant as set in Primary setting
    // [chatbot_chatgpt style="embedded" assistant="alternate"] - Embedded style, GPT Assistant as set in Alternate setting
    // [chatbot_chatgpt style-"floating" assistant="asst_xxxxxxxxxxxxxxxxxxxxxxxx"] - Floating style using a GPT Assistant ID
    // [chatbot_chatgpt style-"embedded" assistant="asst_xxxxxxxxxxxxxxxxxxxxxxxx"] - Embedded style using a GPT Assistant ID

    // Shortcode Attributes
    $chatbot_chatgpt_default_atts = array(
        'style' => 'floating', // Default value
        'assistant' => 'original' // Default value
    );

    // Combine user attributes with default attributes
    $atts = shortcode_atts($chatbot_chatgpt_default_atts, $atts, 'chatbot_chatgpt');

    // Sanitize the 'style' attribute to ensure it contains safe data
    $chatbot_chatgpt_display_style = sanitize_text_field($atts['style']);

    // Sanitize the 'assistant' attribute to ensure it contains safe data
    $chatbot_chatgpt_assistant_alias = sanitize_text_field($atts['assistant']);

    // DIAG - Diagnostics - Ver 1.7.2
    // chatbot_chatgpt_back_trace( 'NOTICE', '$chatbot_chatgpt_display_style: ' . $chatbot_chatgpt_display_style);
    // chatbot_chatgpt_back_trace( 'NOTICE', '$chatbot_chatgpt_assistant_alias: ' . $chatbot_chatgpt_assistant_alias);

    // Determine the shortcode styling where default is 'floating' or 'embedded' - Ver 1.7.1
    // echo "
    // <script>
    //     localStorage.setItem('chatbot_chatgpt_display_style', '" . $chatbot_chatgpt_display_style . "');
    //     localStorage.setItem('chatbot_chatgpt_assistant_alias', '" . $chatbot_chatgpt_assistant_alias . "');
    // </script>
    // ";

    // Store the style and the assistant value - Ver 1.7.2
    set_chatbot_chatgpt_transients( 'style' , $chatbot_chatgpt_display_style);
    set_chatbot_chatgpt_transients( 'assistant_alias' , $chatbot_chatgpt_assistant_alias);

    // Retrieve the bot name - Ver 1.1.0
    // Add styling to the bot to ensure that it is not shown before it is needed Ver 1.2.0
    $bot_name = esc_attr(get_option('chatbot_chatgpt_bot_name', 'Chatbot ChatGPT'));
    $chatbot_chatgpt_bot_prompt = esc_attr(get_option('chatbot_chatgpt_bot_prompt', 'Enter your question ...'));
    $chatbot_chatgpt_allow_file_uploads = esc_attr(get_option('chatbot_chatgpt_allow_file_uploads', 'No'));

    // If assistant is set to 'original' then do not allow file uploads - Ver 1.7.9
    if ($chatbot_chatgpt_assistant_alias == 'original') {
        $chatbot_chatgpt_allow_file_uploads = 'No';
    }

    // Retrieve the custom buttons on/off setting - Ver 1.6.5
    // global $chatbot_chatgpt_enable_custom_buttons;
    // $chatbot_chatgpt_enable_custom_buttons = esc_attr(get_option('chatbot_chatgpt_enable_custom_buttons', 'Off'));

    // Depending on the style, adjust the output - Ver 1.7.1
    if ($chatbot_chatgpt_display_style == 'embedded') {
        // Code for embed style ('embedded' is the alternative style)
        // Store the style and the assistant value - Ver 1.7.2
        set_chatbot_chatgpt_transients( 'style' , $chatbot_chatgpt_display_style);
        set_chatbot_chatgpt_transients( 'assistant_alias' , $chatbot_chatgpt_assistant_alias);   
        ob_start();
        ?>
        <div id="chatbot-chatgpt">
        <!-- REMOVED FOR EMBEDDED -->
        <!-- <div id="chatbot-chatgpt-header">
            <div id="chatgptTitle" class="title"><?php echo $bot_name; ?></div>
        </div> -->
        <div id="chatbot-chatgpt-conversation"></div>
        <div id="chatbot-chatgpt-input">
            <!-- <input type="text" id="chatbot-chatgpt-message" placeholder="<?php echo esc_attr( $chatbot_chatgpt_bot_prompt ); ?>"> -->
            <textarea id="chatbot-chatgpt-message" rows="2" placeholder="<?php echo esc_attr( $chatbot_chatgpt_bot_prompt ); ?>"></textarea>
            <!-- <button id="chatbot-chatgpt-submit">Send</button> -->
            <button id="chatbot-chatgpt-submit">
                <img src="<?php echo plugins_url('../assets/icons/paper-airplane-modern-icon.png', __FILE__); ?>" alt="Send">
            </button>
            <?php
            if ($chatbot_chatgpt_allow_file_uploads == 'Yes') {
            ?>
                <!-- Add a non-breaking space to ensure that the button is not hidden - Ver 1.7.6 -->
                <!-- &nbsp; -->
                <input type="file" id="chatbot-chatgpt-upload-file-input" style="display: none;" />
                <button id="chatbot-chatgpt-upload-file">
                    <img src="<?php echo plugins_url('../assets/icons/paper-clip-modern-icon.png', __FILE__); ?>" alt="Upload File">
                </button>
                <script type="text/javascript">
                    document.getElementById('chatbot-chatgpt-upload-file').addEventListener('click', function() {
                        document.getElementById('chatbot-chatgpt-upload-file-input').click();
                    });
                </script>
            <?php
            }
            ?>
        </div>
        <button id="chatgpt-open-btn" style="display: none;">
        <i class="dashicons dashicons-format-chat"></i>
        </button>
        <?php
        return ob_get_clean();
    } else {
        // Code for bot style ('floating' is the default style)
        // Store the style and the assistant value - Ver 1.7.2
        set_chatbot_chatgpt_transients( 'style' , $chatbot_chatgpt_display_style);
        set_chatbot_chatgpt_transients( 'assistant_alias' , $chatbot_chatgpt_assistant_alias);   
        ob_start();
        ?>
        <!-- Romoved styling as I believe this may cause problems with some themes Ver 1.6.6 -->
        <!-- <div id="chatbot-chatgpt" style="display: none;"> -->
        <div id="chatbot-chatgpt">
            <div id="chatbot-chatgpt-header">
                <div id="chatgptTitle" class="title"><?php echo $bot_name; ?></div>
            </div>
            <div id="chatbot-chatgpt-conversation"></div>
            <div id="chatbot-chatgpt-input">
                <!-- <input type="text" id="chatbot-chatgpt-message" placeholder="<?php echo esc_attr( $chatbot_chatgpt_bot_prompt ); ?>"> -->
                <textarea id="chatbot-chatgpt-message" rows="1" placeholder="<?php echo esc_attr( $chatbot_chatgpt_bot_prompt ); ?>"></textarea>
                <!-- <button id="chatbot-chatgpt-submit">Send</button> -->
                <button id="chatbot-chatgpt-submit">
                    <img src="<?php echo plugins_url('../assets/icons/paper-airplane-modern-icon.png', __FILE__); ?>" alt="Send">
                </button>
                <?php
                if ($chatbot_chatgpt_allow_file_uploads == 'Yes') {
                ?>
                    <!-- Add a non-breaking space to ensure that the button is not hidden - Ver 1.7.6 -->
                    <!-- &nbsp; -->
                    <input type="file" id="chatbot-chatgpt-upload-file-input" style="display: none;" />
                    <button id="chatbot-chatgpt-upload-file">
                        <img src="<?php echo plugins_url('../assets/icons/paper-clip-modern-icon.png', __FILE__); ?>" alt="Upload File">
                    </button>

                <?php
                }
                ?>
            </div>
            <!-- Custom buttons -->
            <?php
            $chatbot_chatgpt_enable_custom_buttons = 'Off'; // 'On' or 'Off'
            $chatbot_chatgpt_enable_custom_buttons = esc_attr(get_option('chatbot_chatgpt_enable_custom_buttons', 'Off'));
            // DIAG - Diagnostics
            // chatbot_chatgpt_back_trace( 'NOTICE', '$chatbot_chatgpt_enable_custom_buttons: ' . $chatbot_chatgpt_enable_custom_buttons);
            if ($chatbot_chatgpt_enable_custom_buttons == 'On') {
                ?>
                <div id="chatboat-chatgpt-custom-buttons" style="text-align: center;">
                    <?php
                    $chatbot_chatgpt_custom_button_name_1 = '';
                    $chatbot_chatgpt_custom_button_url_1 = '';
                    $chatbot_chatgpt_custom_button_name_2 = '';
                    $chatbot_chatgpt_custom_button_url_2 = '';
                    $chatbot_chatgpt_custom_button_name_1 = get_option('chatbot_chatgpt_custom_button_name_1');
                    $chatbot_chatgpt_custom_button_url_1 = get_option('chatbot_chatgpt_custom_button_url_1');
                    $chatbot_chatgpt_custom_button_name_2 = get_option('chatbot_chatgpt_custom_button_name_2');
                    $chatbot_chatgpt_custom_button_url_2 = get_option('chatbot_chatgpt_custom_button_url_2');
                    // DIAG - Diagnostics
                    // chatbot_chatgpt_back_trace( 'NOTICE', 'chatbot_chatgpt_custom_button_name_1: ' . $chatbot_chatgpt_custom_button_name_1);
                    // chatbot_chatgpt_back_trace( 'NOTICE', 'chatbot_chatgpt_custom_button_url_1: ' . $chatbot_chatgpt_custom_button_url_1);
                    // chatbot_chatgpt_back_trace( 'NOTICE', 'chatbot_chatgpt_custom_button_name_2: ' . $chatbot_chatgpt_custom_button_name_2);
                    // chatbot_chatgpt_back_trace( 'NOTICE', 'chatbot_chatgpt_custom_button_url_2: ' . $chatbot_chatgpt_custom_button_url_2);
                    if (!empty($chatbot_chatgpt_custom_button_name_1) && !empty($chatbot_chatgpt_custom_button_url_1)) {
                        ?>
                        <button class="chatbot-chatgpt-custom-button-class">
                        <a href="<?php echo esc_url($chatbot_chatgpt_custom_button_url_1); ?>" target="_blank"><?php echo esc_html($chatbot_chatgpt_custom_button_name_1); ?></a>
                        </button>
                        <?php
                    }
                    if (!empty($chatbot_chatgpt_custom_button_name_2) && !empty($chatbot_chatgpt_custom_button_url_2)) {
                        ?>
                        <button class="chatbot-chatgpt-custom-button-class">
                        <a href="<?php echo esc_url($chatbot_chatgpt_custom_button_url_2); ?>" target="_blank"><?php echo esc_html($chatbot_chatgpt_custom_button_name_2); ?></a>
                        </button>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            $chatbot_chatgpt_suppress_attribution = 'Off'; // 'On' or 'Off'
            $chatbot_chatgpt_suppress_attribution = esc_attr(get_option('chatbot_chatgpt_suppress_attribution', 'Off'));
            // DIAG - Diagnostics - Ver 1.6.5
            // chatbot_chatgpt_back_trace( 'NOTICE', 'chatbot_chatgpt_suppress_attribution: ' . $chatbot_chatgpt_suppress_attribution);
            if ($chatbot_chatgpt_suppress_attribution == 'Off') {
                ?>
                <div style="text-align: center;">
                    <a rel="noopener noreferrer" style="text-decoration:none; font-size: 10px;"><?php echo esc_html('Od Gurabija za Multimedijalne sisteme'); ?></a>
                </div>
                <?php
            }
            ?>
        </div>
        <button id="chatgpt-open-btn" style="display: none;">
        <i class="dashicons dashicons-format-chat"></i>
        </button>
        <?php
        return ob_get_clean();
    }

}

add_shortcode('chatbot_chatgpt', 'chatbot_chatgpt_shortcode');

// Fix Updating failed. The response is not a valid JSON response. - Version 1.7.3
// Function to output the script
function chatbot_chatgpt_shortcode_enqueue_script() {
    global $chatbot_chatgpt_display_style, $chatbot_chatgpt_assistant_alias;

    // Check if the variables are set and not empty
    $style = isset($chatbot_chatgpt_display_style) ? $chatbot_chatgpt_display_style : '';
    $assistant = isset($chatbot_chatgpt_assistant_alias) ? $chatbot_chatgpt_assistant_alias : '';

    ?>
    <script>
        // Check if the variables are not empty before setting them in localStorage
        if ('<?php echo $style; ?>' !== '') {
            localStorage.setItem('chatbot_chatgpt_display_style', '<?php echo $style; ?>');
        }
        if ('<?php echo $assistant; ?>' !== '') {
            localStorage.setItem('chatbot_chatgpt_assistant_alias', '<?php echo $assistant; ?>');
        }
    </script>
    <?php

}

// Hook this function into the 'wp_footer' action
add_action('wp_footer', 'chatbot_chatgpt_shortcode_enqueue_script');
