<?php
// includes/meta-box-product.php - Add AI tools to WooCommerce product editor

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'sca_product_meta_box',
        '🎯 SEO Assistant - Product Generator',
        'sca_render_product_meta_box',
        'product',
        'normal',
        'high'
    );
});

function sca_render_product_meta_box($post) {
    $prompt = get_post_meta($post->ID, '_sca_prompt', true);
    $keywords = get_post_meta($post->ID, '_sca_keywords', true);
    ?>
    <div style="margin-bottom:10px;">
        <label for="sca_keywords"><strong>Keywords (comma separated):</strong></label>
        <input type="text" id="sca_keywords" name="sca_keywords" value="<?php echo esc_attr($keywords); ?>" style="width:100%;">
    </div>

    <div style="margin-bottom:10px;">
        <label for="sca_custom_prompt"><strong>Prompt:</strong></label>
        <textarea id="sca_custom_prompt" name="sca_custom_prompt" rows="4" style="width:100%;"><?php echo esc_textarea($prompt); ?></textarea>
    </div>

    <div>
        <button type="button" class="button button-primary" id="sca_generate_product_btn">Generate Content with AI</button>
        <span id="sca_product_loading" style="display:none;">⏳ Generating...</span>
        <button type="button" class="button" id="sca_copy_to_description">📋 Copy to Product Description</button>
    </div>

    <textarea id="sca_product_output" style="width:100%; height:200px; margin-top:10px;" readonly></textarea>

    <script>
        const promptBtn = document.getElementById('sca_generate_product_btn');
        const copyBtn = document.getElementById('sca_copy_to_description');
        const promptBox = document.getElementById('sca_custom_prompt');
        const keywordsBox = document.getElementById('sca_keywords');
        const outputBox = document.getElementById('sca_product_output');
        const loading = document.getElementById('sca_product_loading');

        promptBtn.addEventListener('click', function () {
            const prompt = promptBox.value || ('Write a product description using keywords: ' + keywordsBox.value);
            outputBox.value = '';
            loading.style.display = 'inline';

            fetch(ajaxurl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'sca_generate',
                    prompt
                })
            })
            .then(res => res.text())
            .then(data => {
                outputBox.value = data;
                loading.style.display = 'none';
            });
        });

        copyBtn.addEventListener('click', function () {
            const content = outputBox.value;
            const desc = document.getElementById('content'); // Classic Editor textarea
            if (desc) desc.value = content;
        });
    </script>
    <?php
}

add_action('save_post_product', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['sca_custom_prompt'])) {
        update_post_meta($post_id, '_sca_prompt', sanitize_text_field($_POST['sca_custom_prompt']));
    }
    if (isset($_POST['sca_keywords'])) {
        update_post_meta($post_id, '_sca_keywords', sanitize_text_field($_POST['sca_keywords']));
    }
});
