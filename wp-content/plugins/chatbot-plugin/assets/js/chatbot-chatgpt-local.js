jQuery(document).ready(function ($) {
  function chatbot_chatgpt_localize() {
    // let chatbotSettings = " . json_encode($chatbot_settings) . ";

    // console.log('Chatbot ChatGPT: NOTICE: Entering chatbot_chatgpt_localize');

    // Access the variables passed from PHP using the chatbotSettings object - Ver 1.4.1
    var chatgptName =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_bot_name
        ? chatbotSettings.chatbot_chatgpt_bot_name
        : "Chatbot ChatGPT";
    var chatbot_chatgpt_bot_prompt =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_bot_prompt
        ? chatbotSettings.chatbot_chatgpt_bot_prompt
        : "Enter your question ...";
    var chatgptInitialGreeting =
      typeof chatbotSettings !== "undefined" && chatbotSettings.initial_greeting
        ? chatbotSettings.initial_greeting
        : "Hello! How can I help you today?";
    var chatgptSubsequentGreeting =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_subsequent_greeting
        ? chatbotSettings.chatbot_chatgpt_subsequent_greeting
        : "Hello again! How can I help you?";
    var chatbot_chatgpt_display_style =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_display_style
        ? chatbotSettings.chatbot_chatgpt_display_style
        : "floating";
    var chatbot_chatgpt_assistant_alias =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_assistant_alias
        ? chatbotSettings.chatbot_chatgpt_assistant_alias
        : "primary";
    var chatbot_chatgpt_start_status =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbotStartStatus
        ? chatbotSettings.chatbotStartStatus
        : "closed";
    var chatbot_chatgpt_start_status_new_visitor =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_start_status_new_visitor
        ? chatbotSettings.chatbot_chatgpt_start_status_new_visitor
        : "closed";
    var chatgptDisclaimerSetting =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_disclaimer_setting
        ? chatbotSettings.chatbot_chatgpt_disclaimer_setting
        : "Yes";
    var chatgptMaxTokensSetting =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_max_tokens_setting
        ? chatbotSettings.chatbot_chatgpt_max_tokens_setting
        : "150";
    var chatgptWidthSetting =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_width_setting
        ? chatbotSettings.chatbot_chatgpt_width_setting
        : "Narrow";
    var chatgptDiagnosticsSetting =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatgpt_diagnotics
        ? chatbotSettings.chatgpt_diagnotics
        : "Off";
    // Avatar Setting
    var chatgptAvatarIconSettingInput =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_avatar_icon_setting
        ? chatbotSettings.chatbot_chatgpt_avatar_icon_setting
        : "icon-001.png";
    var chatgptAvatarIconURLSettingInput =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_avatar_icon_url_setting
        ? chatbotSettings.chatbot_chatgpt_avatar_icon_url_setting
        : "icon-001.png";
    var chatgptCustomAvatarIconSettingInput =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_custom_avatar_icon_setting
        ? chatbotSettings.chatbot_chatgpt_custom_avatar_icon_setting
        : "icon-001.png";
    var chatgptAvatarGreetingSettingInput =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_avatar_greeting_setting
        ? chatbotSettings.chatbot_chatgpt_avatar_greeting_setting
        : "Great to see you today! How can I help you?";
    // Custom Buttons
    var chatgptEnableCustomButtonsInput =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_enable_custom_buttons
        ? chatbotSettings.chatbot_chatgpt_enable_custom_buttons
        : "Off";
    var chatgptCustomButtonName1Input =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_custom_button_name_1
        ? chatbotSettings.chatbot_chatgpt_custom_button_name_1
        : "";
    var chatgptCustomButtonURL1Input =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_custom_button_url_1
        ? chatbotSettings.chatbot_chatgpt_custom_button_url_1
        : "";
    var chatgptCustomButtonName2Input =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_custom_button_name_2
        ? chatbotSettings.chatbot_chatgpt_custom_button_name_2
        : "";
    var chatgptCustomButtonURL2Input =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_custom_button_url_2
        ? chatbotSettings.chatbot_chatgpt_custom_button_url_2
        : "";
    var chatgptAllowFileUploads =
      typeof chatbotSettings !== "undefined" &&
      chatbotSettings.chatbot_chatgpt_allow_file_uploads
        ? chatbotSettings.chatbot_chatgpt_allow_file_uploads
        : "No";
    // let chatbotSettings = " . json_encode($chatbot_settings) . ";

    Object.keys(chatbotSettings).forEach((key) => {
      if (!localStorage.getItem(key)) {
        // DIAG - Log the key and value
        // console.log('Chatbot ChatGPT: NOTICE: Setting ' + key + ' in localStorage');
        localStorage.setItem(key, chatbotSettings[key]);
      } else {
        // DIAG - Log the key and value
        // console.log('Chatbot ChatGPT: NOTICE: ' key + ' is already set in localStorage');
      }
    });

    // Get the input elements
    var chatgptNameInput = document.getElementById("chatbot_chatgpt_bot_name");
    var chatbot_chatgpt_bot_prompt = document.getElementById(
      "chatbot_chatgpt_bot_prompt"
    );
    var chatgptInitialGreetingInput = document.getElementById(
      "chatbot_chatgpt_initial_greeting"
    );
    var chatgptSubsequentGreetingInput = document.getElementById(
      "chatbot_chatgpt_subsequent_greeting"
    );
    var chatbot_chatgpt_display_style = document.getElementById(
      "chatbot_chatgpt_display_style"
    );
    var chatbot_chatgpt_assistant_alias = document.getElementById(
      "chatbot_chatgpt_assistant_alias"
    );
    var chatgptStartStatusInput = document.getElementById(
      "chatbot_chatgpt_start_status"
    );
    var chatbot_chatgpt_start_status_new_visitorInput = document.getElementById(
      "chatbot_chatgpt_start_status_new_visitor"
    );
    var chatgptDisclaimerSettingInput = document.getElementById(
      "chatbot_chatgpt_disclaimer_setting"
    );
    var chatgptMaxTokensSettingInput = document.getElementById(
      "chatbot_chatgpt_max_tokens_setting"
    );
    var chatgptWidthSettingInput = document.getElementById(
      "chatbot_chatgpt_width_setting"
    );
    var chatgptDiagnosticsSettingInput = document.getElementById(
      "chatbot_chatgpt_diagnostics"
    );
    var chatgptAvatarIconSettingInput = document.getElementById(
      "chatbot_chatgpt_avatar_icon_setting"
    );
    var chatgptCustomAvatarIconSettingInput = document.getElementById(
      "chatbot_chatgpt_custom_avatar_icon_setting"
    );
    var chatgptAvatarGreetingSettingInput = document.getElementById(
      "chatbot_chatgpt_avatar_greeting_setting"
    );
    var chatgptEnableCustomButtonsInput = document.getElementById(
      "chatbot_chatgpt_enable_custom_buttons"
    );
    var chatgptCustomButtonName1Input = document.getElementById(
      "chatbot_chatgpt_custom_button_name_1"
    );
    var chatgptCustomButtonURL1Input = document.getElementById(
      "chatbot_chatgpt_custom_button_url_1"
    );
    var chatgptCustomButtonName2Input = document.getElementById(
      "chatbot_chatgpt_custom_button_name_2"
    );
    var chatgptCustomButtonURL2Input = document.getElementById(
      "chatbot_chatgpt_custom_button_url_2"
    );
    var chatgptAllowFileUploadsInput = document.getElementById(
      "chatbot_chatgpt_allow_file_uploads"
    );

    if (chatgptNameInput) {
      chatgptNameInput.addEventListener("change", function () {
        localStorage.setItem("chatbot_chatgpt_bot_name", this.value);
      });
    }

    if (chatbot_chatgpt_bot_prompt) {
      chatbot_chatgpt_bot_prompt.addEventListener("change", function () {
        localStorage.setItem("chatbot_chatgpt_bot_prompt", this.value);
      });
    }

    if (chatgptInitialGreetingInput) {
      chatgptInitialGreetingInput.addEventListener("change", function () {
        localStorage.setItem("chatbot_chatgpt_initial_greeting", this.value);
      });
    }

    if (chatgptSubsequentGreetingInput) {
      chatgptSubsequentGreetingInput.addEventListener("change", function () {
        localStorage.setItem("chatbot_chatgpt_subsequent_greeting", this.value);
      });
    }

    if (chatgptStartStatusInput) {
      chatgptStartStatusInput.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_start_status",
          this.options[this.selectedIndex].value
        );
      });
    }

    if (chatbot_chatgpt_start_status_new_visitorInput) {
      chatbot_chatgpt_start_status_new_visitorInput.addEventListener(
        "change",
        function () {
          localStorage.setItem(
            "chatbot_chatgpt_start_status_new_visitor",
            this.options[this.selectedIndex].value
          );
        }
      );
    }

    if (chatgptDisclaimerSettingInput) {
      chatgptDisclaimerSettingInput.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_disclaimer_setting",
          this.options[this.selectedIndex].value
        );
      });
    }

    if (chatgptMaxTokensSettingInput) {
      chatgptMaxTokensSettingInput.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_max_tokens_setting",
          this.options[this.selectedIndex].value
        );
      });
    }

    if (chatgptWidthSettingInput) {
      chatgptWidthSettingInput.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_width_setting",
          this.options[this.selectedIndex].value
        );
      });
    }

    if (chatgptDiagnosticsSettingInput) {
      chatgptDiagnosticsSettingInput.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_diagnostics",
          this.options[this.selectedIndex].value
        );
      });
    }

    if (chatgptEnableCustomButtonsInput) {
      chatgptEnableCustomButtonsInput.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_enable_custom_buttons",
          this.options[this.selectedIndex].value
        );
      });
    }

    if (chatgptCustomButtonName1Input) {
      chatgptCustomButtonName1Input.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_custom_button_name_1",
          this.value
        );
      });
    }

    if (chatgptCustomButtonURL1Input) {
      chatgptCustomButtonURL1Input.addEventListener("change", function () {
        localStorage.setItem("chatbot_chatgpt_custom_button_url_1", this.value);
      });
    }

    if (chatgptCustomButtonName2Input) {
      chatgptCustomButtonName2Input.addEventListener("change", function () {
        localStorage.setItem(
          "chatbot_chatgpt_custom_button_name_2",
          this.value
        );
      });
    }

    if (chatgptCustomButtonURL2Input) {
      chatgptCustomButtonURL2Input.addEventListener("change", function () {
        localStorage.setItem("chatbot_chatgpt_custom_button_url_2", this.value);
      });
    }

    // Avatar Settings
    if (document.getElementById("chatbot_chatgpt_avatar_icon_setting")) {
      document
        .getElementById("chatbot_chatgpt_avatar_icon_setting")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_avatar_icon_setting",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_custom_avatar_icon_setting")) {
      document
        .getElementById("chatbot_chatgpt_custom_avatar_icon_setting")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_custom_avatar_icon_setting",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_avatar_greeting_setting")) {
      document
        .getElementById("chatbot_chatgpt_avatar_greeting_setting")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_avatar_greeting_setting",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_diagnostics")) {
      document
        .getElementById("chatbot_chatgpt_diagnostics")
        .addEventListener("change", function () {
          localStorage.setItem("chatbot_chatgpt_diagnostics", this.value);
        });
    }

    if (document.getElementById("chatbot_chatgpt_enable_custom_buttons")) {
      document
        .getElementById("chatbot_chatgpt_enable_custom_buttons")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_enable_custom_buttons",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_custom_button_name_1")) {
      document
        .getElementById("chatbot_chatgpt_custom_button_name_1")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_name_1",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_custom_button_url_1")) {
      document
        .getElementById("chatbot_chatgpt_custom_button_url_1")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_url_1",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_custom_button_name_2")) {
      document
        .getElementById("chatbot_chatgpt_custom_button_name_2")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_name_2",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_custom_button_url_2")) {
      document
        .getElementById("chatbot_chatgpt_custom_button_url_2")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_url_2",
            this.value
          );
        });
    }

    if (document.getElementById("chatbot_chatgpt_allow_file_uploads")) {
      document
        .getElementById("chatbot_chatgpt_allow_file_uploads")
        .addEventListener("change", function () {
          localStorage.setItem(
            "chatbot_chatgpt_allow_file_uploads",
            this.value
          );
        });
    }

    // Update the localStorage values when the form is submitted
    // chatgpt-settings-form vs. your-form-id
    var chatgptSettingsForm = document.getElementById("chatgpt-settings-form");

    if (chatgptSettingsForm) {
      chatgptSettingsForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        var chatgptNameInput = document.getElementById(
          "chatbot_chatgpt_bot_name"
        );
        var chatbot_chatgpt_bot_prompt = document.getElementById(
          "chatbot_chatgpt_bot_prompt"
        );
        var chatgptInitialGreetingInput = document.getElementById(
          "chatbot_chatgpt_initial_greeting"
        );
        var chatgptSubsequentGreetingInput = document.getElementById(
          "chatbot_chatgpt_subsequent_greeting"
        );
        var chatgptStartStatusInput = document.getElementById(
          "chatbot_chatgpt_start_status"
        );
        var chatbot_chatgpt_start_status_new_visitorInput =
          document.getElementById("chatbot_chatgpt_start_status_new_visitor");
        var chatgptDisclaimerSettingInput = document.getElementById(
          "chatbot_chatgpt_disclaimer_setting"
        );
        var chatgptMaxTokensSettingInput = document.getElementById(
          "chatbot_chatgpt_max_tokens_setting"
        );
        var chatgptWidthSettingInput = document.getElementById(
          "chatbot_chatgpt_width_setting"
        );
        var chatgptDiagnosticsSettingInput = document.getElementById(
          "chatbot_chatgpt_diagnostics"
        );
        var chatgptAvatarIconSettingInput = document.getElementById(
          "chatbot_chatgpt_avatar_icon_setting"
        );
        var chatgptCustomAvatarIconSettingInput = document.getElementById(
          "chatbot_chatgpt_custom_avatar_icon_setting"
        );
        var chatgptAvatarGreetingSettingInput = document.getElementById(
          "chatbot_chatgpt_avatar_greeting_setting"
        );
        var chatgptEnableCustomButtonsInput = document.getElementById(
          "chatbot_chatgpt_enable_custom_buttons"
        );
        var chatgptCustomButtonName1Input = document.getElementById(
          "chatbot_chatgpt_custom_button_name_1"
        );
        var chatgptCustomButtonURL1Input = document.getElementById(
          "chatbot_chatgpt_custom_button_url_1"
        );
        var chatgptCustomButtonName2Input = document.getElementById(
          "chatbot_chatgpt_custom_button_name_2"
        );
        var chatgptCustomButtonURL2Input = document.getElementById(
          "chatbot_chatgpt_custom_button_url_2"
        );
        var chatgptAllowFileUploadsInput = document.getElementById(
          "chatbot_chatgpt_allow_file_uploads"
        );

        if (chatgptNameInput) {
          localStorage.setItem(
            "chatbot_chatgpt_bot_name",
            chatgptNameInput.value
          );
        }

        if (chatbot_chatgpt_bot_prompt) {
          localStorage.setItem(
            "chatbot_chatgpt_bot_prompt",
            chatbot_chatgpt_bot_prompt.value
          );
        }

        if (chatgptInitialGreetingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_initial_greeting",
            chatgptInitialGreetingInput.value
          );
        }

        if (chatgptSubsequentGreetingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_subsequent_greeting",
            chatgptSubsequentGreetingInput.value
          );
        }

        if (chatgptStartStatusInput) {
          localStorage.setItem(
            "chatbot_chatgpt_start_status",
            chatgptStartStatusInput.value
          );
        }

        if (chatbot_chatgpt_start_status_new_visitorInput) {
          localStorage.setItem(
            "chatbot_chatgpt_start_status_new_visitor",
            chatbot_chatgpt_start_status_new_visitorInput.value
          );
        }

        if (chatgptDisclaimerSettingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_disclaimer_setting",
            chatgptDisclaimerSettingInput.value
          );
        }

        if (chatgptMaxTokensSettingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_max_tokens_setting",
            chatgptMaxTokensSettingInput.value
          );
        }

        if (chatgptWidthSettingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_width_setting",
            chatgptWidthSettingInput.value
          );
        }

        if (chatgptDiagnosticsSettingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_diagnostics",
            chatgptDiagnosticsSettingInput.value
          );
        }

        // Avatar Settings
        if (chatgptAvatarIconSettingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_avatar_icon_setting",
            chatgptAvatarIconSettingInput.value
          );
        }

        // Avatar Settings
        if (chatgptCustomAvatarIconSettingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_custom_avatar_icon_setting",
            chatgptCustomAvatarIconSettingInput.value
          );
        }

        // Avatar Settings
        if (chatgptAvatarGreetingSettingInput) {
          localStorage.setItem(
            "chatbot_chatgpt_avatar_greeting_setting",
            chatgptAvatarGreetingSettingInput.value
          );
        }

        // Custom Buttons
        if (chatgptEnableCustomButtonsInput) {
          localStorage.setItem(
            "chatbot_chatgpt_enable_custom_buttons",
            chatgptEnableCustomButtonsInput.value
          );
        }

        if (chatgptCustomButtonName1Input) {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_name_1",
            chatgptCustomButtonName1Input.value
          );
        }

        if (chatgptCustomButtonURL1Input) {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_url_1",
            chatgptCustomButtonURL1Input.value
          );
        }

        if (chatgptCustomButtonName2Input) {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_name_2",
            chatgptCustomButtonName2Input.value
          );
        }

        if (chatgptCustomButtonURL2Input) {
          localStorage.setItem(
            "chatbot_chatgpt_custom_button_url_2",
            chatgptCustomButtonURL2Input.value
          );
        }

        // Allow file uploads
        if (chatgptAllowFileUploadsInput) {
          localStorage.setItem(
            "chatbot_chatgpt_allow_file_uploads",
            chatgptAllowFileUploadsInput.value
          );
        }
      });
    }

    // DIAG - Log exiting the function
    // console.log('Chatbot ChatGPT: NOTICE: Exiting chatbot_chatgpt_localize');
  }

  chatbot_chatgpt_localize();
});
