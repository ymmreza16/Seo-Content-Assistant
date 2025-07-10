<?php
if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'sca_meta_box',
        'SEO Content Assistant',
        'sca_render_meta_box',
        ['post', 'page'],
        'normal',
        'high'
    );
});

function sca_render_meta_box($post) {
    $keyword = get_post_meta($post->ID, '_sca_keyword', true);
    ?>
    <div style="margin-bottom: 10px;">
        <label for="sca_keyword"><strong>Target Keyword:</strong></label>
        <input type="text" id="sca_keyword" name="sca_keyword" value="<?php echo esc_attr($keyword); ?>" style="width: 100%;">
    </div>
    <div>
        <button type="button" class="button button-primary" id="sca_generate_btn">Generate Content</button>
        <span id="sca_loading" style="display:none;">Generating...</span>
    </div>
    <textarea id="sca_output" style="width:100%; height:200px; margin-top:10px;" readonly></textarea>
    <?php
}
add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['sca_keyword'])) {
        update_post_meta($post_id, '_sca_keyword', sanitize_text_field($_POST['sca_keyword']));
    }
});
