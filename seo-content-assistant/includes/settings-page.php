<?php
// includes/settings-page.php - Advanced AI settings with keyword input and prompt form

if (!defined('ABSPATH')) exit;

function sca_render_settings_page() {
    $options = get_option('sca_options');
    $provider = $options['provider'] ?? 'openrouter';
    $api_key = $options['api_key'] ?? '';
    $prompt = $options['default_prompt'] ?? '';
    $keywords = $options['default_keywords'] ?? '';
    ?>
    <div class="wrap">
        <h1>SEO Content Assistant Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sca_options_group');
            do_settings_sections('seo_content_assistant');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Select AI Provider</th>
                    <td>
                        <select name="sca_options[provider]">
                            <option value="openrouter" <?php selected($provider, 'openrouter'); ?>>OpenRouter.ai</option>
                            <option value="openai" <?php selected($provider, 'openai'); ?>>OpenAI</option>
                            <option value="claude" <?php selected($provider, 'claude'); ?>>Claude (Anthropic)</option>
                            <option value="bard" <?php selected($provider, 'bard'); ?>>Gemini / Bard</option>
                            <option value="yunwu" <?php selected($provider, 'yunwu'); ?>>Yunwu AI</option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">API Key</th>
                    <td><input type="text" name="sca_options[api_key]" value="<?php echo esc_attr($api_key); ?>" size="60"></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Default Prompt</th>
                    <td><textarea name="sca_options[default_prompt]" rows="4" style="width: 100%;"><?php echo esc_textarea($prompt); ?></textarea></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Default Keywords<br><small>(Separate with comma)</small></th>
                    <td><input type="text" name="sca_options[default_keywords]" value="<?php echo esc_attr($keywords); ?>" style="width: 100%;"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <hr>
        <h2>Prompt Playground</h2>
        <form id="sca_prompt_test_form">
            <textarea id="sca_test_prompt" rows="5" style="width:100%;" placeholder="Write your prompt here..."></textarea>
            <br>
            <button type="button" class="button button-primary" id="sca_test_btn">Generate Content</button>
            <span id="sca_test_loading" style="display:none;">⏳ Generating...</span>
            <textarea id="sca_test_output" style="width:100%; height:250px; margin-top:10px;" readonly></textarea>
        </form>
    </div>

    <script>
    document.getElementById('sca_test_btn').addEventListener('click', function () {
        const prompt = document.getElementById('sca_test_prompt').value;
        const output = document.getElementById('sca_test_output');
        const loading = document.getElementById('sca_test_loading');

        loading.style.display = 'inline';
        output.value = '';

        fetch(ajaxurl, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'sca_generate',
                prompt
            })
        })
        .then(res => res.text())
        .then(data => {
            output.value = data;
            loading.style.display = 'none';
        });
    });
    </script>
    <?php
}

add_action('admin_init', function () {
    register_setting('sca_options_group', 'sca_options');
});
