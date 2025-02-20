<?php

/**
 * AGCN_content_callbacks class.
 * 
 * This class handles the content callbacks for the AGCN plugin.
 * It includes methods for displaying language selection, header, title, body, sections, and sections header.
 * 
 * @since 1.0.0
 * @package AGCN
 * @author Nabil Makhnouq
 * @version 1.0
 */
class AGCN_content_callbacks
{
    private static $available_languages = null;
    private static $selected_language = null;

    /**
     * Retrieves the available languages from the plugin configuration.
     * 
     * This method checks if the available languages are already cached and returns them if so.
     * Otherwise, it retrieves the available languages from the plugin configuration and caches them.
     * 
     * @return array The available languages.
     */
    public static function agcn_get_available_languages()
    {
        if (self::$available_languages === null) {
            self::$available_languages = AGCN_plugin::agcn_get_config('available_languages');
        }
        return self::$available_languages;
    }

    /**
     * Retrieves the selected language from the plugin options.
     * 
     * This method checks if the selected language is already cached and returns it if so.
     * Otherwise, it retrieves the selected language from the plugin options and caches it.
     * 
     * @return string The selected language.
     */
    public static function agcn_get_selected_language()
    {
        if (self::$selected_language === null) {
            
            $options = get_option('agcn_options');

            $get_language = filter_input(INPUT_GET, 'language', FILTER_DEFAULT);
            $get_language = $get_language ? sanitize_text_field($get_language) : $options['config']['language'];
            $existing_languages = array_keys($options['content'] ?? []);

            return $get_language = in_array($get_language, $existing_languages) ? $get_language : $options['config']['language'];
        }
        return self::$selected_language;
    }

    /**
     * Displays the add language form.
     * 
     * This method retrieves the existing languages and checks if all available languages are already added.
     * If not, it displays a dropdown for selecting a new language and a button to add it.
     */
    public static function agcn_add_language_callback()
    {
        $options = get_option('agcn_options');
        $existing_languages = array_keys($options['content'] ?? []);

        if (count(self::agcn_get_available_languages()) === count($existing_languages)) {
            echo '<p>' . esc_html(__('All languages are already added.', 'agcn-ai-generated-content-notifier')) . '</p>';
            return;
        }
?>
        <select id="new-language">
            <?php foreach (self::agcn_get_available_languages() as $key => $name) : ?>
                <?php if (!in_array($key, $existing_languages)) : ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($name); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <button type="button" id="add-language" class="button">Add language</button>

        <script>
            document.addEventListener('DOMContentLoaded', function(e) {
                const addButton = document.getElementById('add-language');
                const languageSelect = document.getElementById('new-language');

                addButton.addEventListener('click', function(e) {

                    const language = languageSelect.value;
                    if (!language) return;

                    const formData = new FormData();
                    formData.append('action', 'agcn_add_language');
                    formData.append('language', language);
                    formData.append('nonce', '<?php echo esc_js(wp_create_nonce('agcn_add_language')); ?>');

                    fetch(ajaxurl, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (!response.success) {
                                displayAdminNotice(response.data, 'error');
                                return;
                            }
                            window.location.href = '<?php echo esc_url_raw(admin_url("options-general.php?page=agcn-settings&tab=content&language=")); ?>' + language;
                        })
                        .catch(error => displayAdminNotice(error, 'error'));
                });
            });
        </script>
    <?php
    }

    /**
     * Displays the language selection dropdown.
     * 
     * This method retrieves the available languages and the current language from the plugin options.
     * It then generates a dropdown for selecting the language.
     */
    public static function agcn_select_language_callback()
    {

        $options = get_option('agcn_options');
    ?>

        <select id="language">
            <?php foreach ($options['content'] as $key => $value) : ?>
                <?php $selected_language_name = self::agcn_get_available_languages()[$key] ?? $key; ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected(self::agcn_get_selected_language(), $key); ?>><?php echo esc_attr($selected_language_name); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" id="remove-language" class="button">Remove language</button>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const removeButton = document.getElementById('remove-language');
                const languageSelect = document.getElementById('language');

                removeButton.addEventListener('click', function() {
                    const language = languageSelect.value;
                    if (!language) return;

                    if (!confirm('<?php echo esc_js(esc_attr__('Are you sure you want to remove this language?', 'agcn-ai-generated-content-notifier')); ?>')) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('action', 'agcn_remove_language');
                    formData.append('language', language);
                    formData.append('nonce', '<?php echo esc_js(wp_create_nonce('agcn_remove_language')); ?>');

                    fetch(ajaxurl, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.success) {
                                window.location.href = 'options-general.php?page=agcn-settings&tab=content'
                            } else {
                                displayAdminNotice(response.data || 'Error removing language', 'error');
                            }
                        })
                        .catch(error => {
                            displayAdminNotice('Error removing language', 'error');
                        });
                });

                languageSelect.addEventListener('change', function() {
                    window.location.href = '<?php echo esc_url_raw(admin_url("options-general.php?page=agcn-settings&tab=content&language=")); ?>' + this.value;
                });
            });
        </script>
    <?php
    }

    /**
     * Displays the header input field.
     * 
     * This method retrieves the header text from the plugin options and displays it in an input field.
     */
    public static function agcn_header_callback()
    {
        $options = get_option('agcn_options');
        $header = esc_attr($options['content'][self::agcn_get_selected_language()]['header']) ?? '';
    ?>

        <input type="text" name="agcn_options[content][<?php echo esc_attr(self::agcn_get_selected_language()) ?>][header]" value="<?php echo esc_attr($header); ?>" class="regular-text large-text">
        <p class="description"><?php esc_html_e('This text will be displayed above the title as the modal header', 'agcn-ai-generated-content-notifier'); ?></p>
    <?php
    }

    /**
     * Displays the title input field.
     * 
     * This method retrieves the title text from the plugin options and displays it in an input field.
     */
    public static function agcn_title_callback()
    {
        $options = get_option('agcn_options');
        $title = $options['content'][self::agcn_get_selected_language()]['title'] ?? '';
    ?>

        <input type="text" name="agcn_options[content][<?php echo esc_attr(self::agcn_get_selected_language()) ?>][title]" value="<?php echo esc_attr($title); ?>" class="regular-text large-text">
        <p class="description"><?php esc_html_e('The title of the modal content', 'agcn-ai-generated-content-notifier'); ?></p>
    <?php
    }

    /**
     * Displays the body input field.
     * 
     * This method retrieves the body text from the plugin options and displays it in an editor.
     */
    public static function agcn_body_callback()
    {
        $options = get_option('agcn_options');
        $body = $options['content'][self::agcn_get_selected_language()]['body'] ?? '';

        wp_editor($body, 'agcn_editor_body', [
            'textarea_name' => "agcn_options[content][" . self::agcn_get_selected_language() . "][body]",
            'media_buttons' => false,
            'tinymce'       => [
                'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,removeformat',
            ],
            'teeny' => true,
            'quicktags' => false,
        ]);
        echo '<p class="description">' . esc_html__('The body of the modal content', 'agcn-ai-generated-content-notifier') . '</p>';
    }

    /**
     * Displays the sections header input field.
     * 
     * This method retrieves the sections header text from the plugin options and displays it in an input field.
     */
    public static function agcn_sections_header_callback()
    {
        $options = get_option('agcn_options');
        $sections_header = esc_attr($options['content'][self::agcn_get_selected_language()]['sections_header']) ?? '';
    ?>

    <input type="text" name="agcn_options[content][<?php echo esc_attr(self::agcn_get_selected_language()) ?>][sections_header]" value="<?php echo esc_attr($sections_header); ?>" class="regular-text large-text">
    <p class="description"><?php esc_html_e('This text will be displayed above the sections.', 'agcn-ai-generated-content-notifier'); ?></p>
    <?php
    }

    /**
     * Displays the sections input field.
     * 
     * This method retrieves the sections from the plugin options and displays them in a list of sections.
     */
    public static function agcn_sections_callback()
    {
        wp_enqueue_editor();
        $options = get_option('agcn_options');
        $sections = $options['content'][self::agcn_get_selected_language()]['sections'] ?? [];
    ?>
        <div id="agcn-sections-wrapper">
            <div id="agcn-sections-list" aria-label="Sections">
                <?php foreach ($sections as $index => $section) : ?>
                    <div class="agcn-section-item">
                        <input type="text"
                            class="section-slug large-text"
                            name="agcn_options[content][<?php echo esc_attr(self::agcn_get_selected_language()); ?>][sections][slug][]"
                            value="<?php echo esc_attr($section['slug']); ?>"
                            placeholder="<?php esc_attr_e( 'Slug for the section, only a-z.', 'agcn-ai-generated-content-notifier' ); ?>" />
                        <input type="text"
                            class="section-notice-text large-text"
                            name="agcn_options[content][<?php echo esc_attr(self::agcn_get_selected_language()); ?>][sections][notice_text][]"
                            value="<?php echo esc_attr($section['notice_text']); ?>"
                            placeholder="<?php esc_attr_e( 'Notice text for the section', 'agcn-ai-generated-content-notifier' ); ?>" />
                        <input type="text"
                            class="section-title large-text"
                            name="agcn_options[content][<?php echo esc_attr(self::agcn_get_selected_language()); ?>][sections][title][]"
                            value="<?php echo esc_attr($section['title']); ?>"
                            placeholder="<?php esc_attr_e('Section Title', 'agcn-ai-generated-content-notifier'); ?>" />
                        <div class="section-body-container">
                            <?php
                            wp_editor(
                                $section['body'],
                                'section_body_' . $index,
                                [
                                    'textarea_name' => "agcn_options[content][" . self::agcn_get_selected_language() . "][sections][body][]",
                                    'media_buttons' => false,
                                    'tinymce'       => [
                                        'height' => 150,
                                        'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,removeformat',
                                    ],
                                    'teeny' => true,
                                    'quicktags' => false,
                                ]
                            );
                            ?>
                        </div>
                        <button type="button" class="button button-secondary remove-section">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button button-secondary" id="add-section"><?php esc_attr_e('Add New Section', 'agcn-ai-generated-content-notifier'); ?></button>
        </div>

        <style>
            .agcn-section-item {
                margin-bottom: 20px;
                display: flex;
                flex-direction: column;
                gap: 10px;
                align-items: start;
                border-bottom: 1px solid #ddd;
                padding-bottom: 20px;
            }

            .section-body-container {
                width: 100%;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addSectionBtn = document.getElementById('add-section');
                const sectionsList = document.getElementById('agcn-sections-list');
                const language = '<?php echo esc_js(self::agcn_get_selected_language()); ?>';
                let editorCount = <?php echo count($sections); ?>;

                addSectionBtn.addEventListener('click', function() {
                    const newEditorId = 'section_body_' + editorCount;

                    const tempDiv = document.createElement('div');
                    tempDiv.className = 'agcn-section-item';

                    const slugInput = document.createElement('input');
                    slugInput.type = 'text';
                    slugInput.className = 'section-slug large-text';
                    slugInput.name = `agcn_options[content][${language}][sections][slug][]`;
                    slugInput.placeholder = '<?php esc_js(esc_attr_e('Slug for the section, only a-z.', 'agcn-ai-generated-content-notifier')); ?>';

                    const noticeInput = document.createElement('input');
                    noticeInput.type = 'text';
                    noticeInput.className = 'section-notice large-text';
                    noticeInput.name = `agcn_options[content][${language}][sections][notice_text][]`;
                    noticeInput.placeholder = '<?php esc_js(esc_attr_e('Notice text for the section', 'agcn-ai-generated-content-notifier')); ?>';

                    const titleInput = document.createElement('input');
                    titleInput.type = 'text';
                    titleInput.className = 'section-title large-text';
                    titleInput.name = `agcn_options[content][${language}][sections][title][]`;
                    titleInput.placeholder = '<?php esc_js(esc_attr_e('Section Title', 'agcn-ai-generated-content-notifier')); ?>';

                    const editorContainer = document.createElement('div');
                    editorContainer.className = 'section-body-container';

                    const textarea = document.createElement('textarea');
                    textarea.id = newEditorId;
                    textarea.name = `agcn_options[content][${language}][sections][body][]`;

                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'button button-secondary remove-section';
                    removeButton.textContent = 'Remove';

                    editorContainer.appendChild(textarea);
                    tempDiv.appendChild(slugInput);
                    tempDiv.appendChild(noticeInput);
                    tempDiv.appendChild(titleInput);
                    tempDiv.appendChild(editorContainer);
                    tempDiv.appendChild(removeButton);
                    sectionsList.appendChild(tempDiv);

                    wp.oldEditor.initialize(newEditorId, {
                        tinymce: {
                            wpautop: true,
                            toolbar1: 'bold,italic,underline,bullist,numlist,link,unlink,removeformat',
                            height: 150,
                            setup: function(editor) {
                                editor.on('change', function() {
                                    editor.save();
                                });
                            }
                        },
                        quicktags: false,
                        mediaButtons: false,
                        teeny: true
                    });

                    editorCount++;
                });

                sectionsList.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-section')) {
                        const sectionItem = e.target.closest('.agcn-section-item');
                        const editorId = sectionItem.querySelector('.wp-editor-area')?.id;

                        if (editorId && tinymce) {
                            tinymce.execCommand('mceRemoveEditor', true, editorId);
                        }

                        sectionItem.remove();
                    }
                });
            });
        </script>
<?php
    }
}
