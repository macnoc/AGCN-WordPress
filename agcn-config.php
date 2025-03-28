<?php

/**
 * AGCN configuration array.
 * 
 * This array contains the default options and content for the AGCN plugin.
 * 
 * @return array The configuration array.
 */
return array(
    //Default options for the AGCN plugin.
    'options_default' => array(
        'config' => array(
            'language' => 'en',
            'support' => false,
            'show_badge' => true,
            'badge_position' => 'top-left'
        ),
        'content' => array(
            'en' => array(
                'header' => 'AI Contributions on This Website',
                'title' => 'Our AI Helps Improve Your Experience',
                'body' => 'This website uses AI to enhance the user experience. AI helps generate, modify, personalize, recommend, and assist in creating content.',
                'sections_header' => 'Below are some ways AI contributes to your experience:',
                'sections' => array(
                    array(
                        'slug' => 'generated',
                        'notice_text' => 'AI generated content',
                        'title' => 'Personalized Content Suggestions',
                        'body' => 'Our AI algorithms analyze your browsing behavior to provide personalized content suggestions.'
                    ),
                    array(
                        'slug' => 'recommended',
                        'notice_text' => 'AI recommended content',
                        'title' => 'Recommended Products',
                        'body' => 'Our AI system recommends products based on your browsing history and preferences.'
                    )
                )
            )
        )
    ),
    //Default styles for the AGCN plugin.
    'styles_default' => array(
        'colors' => array(
            'main-color' =>  '#0ea5e9',
            'badge-color' =>  '#f1f5f9',
            'badge-bg-color' =>  '#1e293b',
            'badge-bg-color-hover' =>  '#0f172a',
            'badge-border-color' =>  '#334155',

            'modal-color' =>  '#0F172A',
            'modal-bg-color' =>  '#f8fafc',
            'modal-border-color' =>  '#cbd5e1',
            'modal-icon-color' =>  '#f1f5f9',
            'modal-support-color' =>  '#94a3b8',
            'modal-accordion-open-bg-color' =>  '#f1f5f9',

            'notice-color' =>  '#64748b',
            'notice-bg-color' =>  '#f1f5f9',
            'notice-border-color' =>  '#cbd5e1'
        ),
        'badge-offset' => '8rem'
    ),
    //Available languages for the AGCN plugin.
    'available_languages' => array(
        'en' => 'English',
        'sv' => 'Svenska',
        'da' => 'Dansk',
        'nb' => 'Norsk (Bokmål)',
        'fi' => 'Suomi',
        'de' => 'Deutsch',
        'fr' => 'Français',
        'es' => 'Español',
        'it' => 'Italiano',
        'pt' => 'Português',
        'nl' => 'Nederlands',
        'pl' => 'Polski',
        'cs' => 'Čeština',
        'sk' => 'Slovenčina',
        'hr' => 'Hrvatski',
        'ro' => 'Română',
        'hu' => 'Magyar',
        'lv' => 'Latviešu',
        'lt' => 'Lietuvių',
        'et' => 'Eesti',
        'sl' => 'Slovenščina',
        'ca' => 'Català',
        'eu' => 'Euskara',
        'gl' => 'Galego',
        'mt' => 'Malti',
        'sw' => 'Kiswahili',
        'ha' => 'Hausa',
        'yo' => 'Yorùbá',
        'ig' => 'Igbo',
        'tr' => 'Türkçe',
        'id' => 'Bahasa Indonesia',
        'ms' => 'Bahasa Melayu',
        'vi' => 'Tiếng Việt',
        'tl' => 'Tagalog',
        'fil' => 'Filipino',
        'tk' => 'Türkmençe',
        'uz' => 'Oʻzbekcha',
        'gn' => 'Avañe\'ẽ',
        'ht' => 'Kreyòl ayisyen'
    )
);
