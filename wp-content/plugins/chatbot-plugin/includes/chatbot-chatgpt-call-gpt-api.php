<?php
/**
 * Chatbot ChatGPT for WordPress - ChatGPT API - Ver 1.6.9
 *
 * This file contains the code for table actions for reporting
 * to display the Chatbot ChatGPT on the website.
 *
 * @package chatbot-chatgpt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Call the ChatGPT API
function chatbot_chatgpt_call_api($api_key, $message) {
    global $chatbot_chatgpt_diagnostics;
    global $learningMessages;
    global $errorResponses;
    global $stopWords;

    global $wpdb;

    // The current ChatGPT API URL endpoint for gpt-3.5-turbo and gpt-4
    $api_url = 'https://api.openai.com/v1/chat/completions';

    $headers = array(
        'Authorization' => 'Bearer ' . $api_key,
        'Content-Type' => 'application/json',
    );

    // Select the OpenAI Model
    // Get the saved model from the settings or default to "gpt-3.5-turbo"
    $model = esc_attr(get_option('chatbot_chatgpt_model_choice', 'gpt-3.5-turbo'));
    // FIXME - For now switch gpt-4-turbo back got gpt-4-1106-preview
    if ($model == 'gpt-4-turbo') {
        $model = 'gpt-4-1106-preview';
    }
    $max_tokens = intval(esc_attr(get_option('chatbot_chatgpt_max_tokens_setting', '150')));

    $context = "";
    $context = esc_attr(get_option('chatbot_chatgpt_conversation_context', 'You are a versatile, friendly, and helpful assistant designed to support me in a variety of tasks.'));
 
     $chatgpt_last_response = concatenateHistory('context_history');
    // chatbot_chatgpt_back_trace( 'NOTICE', '$chatgpt_last_response: ' . $chatgpt_last_response);
    
    // IDEA Strip any href links and text from the $chatgpt_last_response
    $chatgpt_last_response = preg_replace('/\[URL:.*?\]/', '', $chatgpt_last_response);

    // IDEA Strip any $learningMessages from the $chatgpt_last_response
    if (get_locale() !== "en_US") {
        $localized_learningMessages = get_localized_learningMessages(get_locale(), $learningMessages);
    } else {
        $localized_learningMessages = $learningMessages;
    }
    $chatgpt_last_response = str_replace($localized_learningMessages, '', $chatgpt_last_response);

    // IDEA Strip any $errorResponses from the $chatgpt_last_response
    if (get_locale() !== "en_US") {
        $localized_errorResponses = get_localized_errorResponses(get_locale(), $errorResponses);
    } else {
        $localized_errorResponses = $errorResponses;
    }
    $chatgpt_last_response = str_replace($localized_errorResponses, '', $chatgpt_last_response);
    
    // Knowledge Navigator keyword append for context
    $chatbot_chatgpt_kn_conversation_context = get_option('chatbot_chatgpt_kn_conversation_context', '');

    $context = $chatgpt_last_response . ' ' . $context . ' ' . $chatbot_chatgpt_kn_conversation_context;

    // chatbot_chatgpt_back_trace( 'NOTICE', '$context: ' . $context);

    $body = array(
        'model' => $model,
        'max_tokens' => $max_tokens,
        'temperature' => 0.5,
        'messages' => array(
            array('role' => 'system', 'content' => $context),
            array('role' => 'user', 'content' => $message)
            ),
    );

    addEntry('context_history', $message);

    // chatbot_chatgpt_back_trace( 'NOTICE', '$storedc: ' . $chatbot_chatgpt_kn_conversation_context);
    // chatbot_chatgpt_back_trace( 'NOTICE', '$context: ' . $context);
    // chatbot_chatgpt_back_trace( 'NOTICE', '$message: ' . $message);  

    $args = array(
        'headers' => $headers,
        'body' => json_encode($body),
        'method' => 'POST',
        'data_format' => 'body',
        'timeout' => 50, // Increase the timeout values to 15 seconds to wait just a bit longer for a response from the engine
    );

    $response = wp_remote_post($api_url, $args);
    // chatbot_chatgpt_back_trace( 'NOTICE', '$response: ' . $response);

    // Handle any errors that are returned from the chat engine
    if (is_wp_error($response)) {
        return 'Error: ' . $response->get_error_message().' Please check Settings for a valid API key or your OpenAI account for additional information.';
    }

    // Return json_decode(wp_remote_retrieve_body($response), true);
    $response_body = json_decode(wp_remote_retrieve_body($response), true);
    if (isset($response_body['message'])) {
        $response_body['message'] = trim($response_body['message']);
        if (substr($response_body['message'], -1) !== '.') {
            $response_body['message'] .= '.';
        }
    }

    if (isset($response_body['choices']) && !empty($response_body['choices'])) {
        // Handle the response from the chat engine
        addEntry('context_history', $response_body['choices'][0]['message']['content']);
        return $response_body['choices'][0]['message']['content'];
    } else {
        // FIXME - Decide what to return here - it's an error
        if (get_locale() !== "en_US") {
            $localized_errorResponses = get_localized_errorResponses(get_locale(), $errorResponses);
        } else {
            $localized_errorResponses = $errorResponses;
        }
        $errorReturned = "";
        // Return a random error message
        $errorReturned = $localized_errorResponses[array_rand($localized_errorResponses)];
        return $errorReturned;
    }
    
}
