<?php
/**
 * Plugin Name: ACF Layout Grid Manager
 * Description: Enhances ACF Flexible Content with customizable layout preview grids.
 * Version: 1.1.2
 * Author: UmairSDS
 * License: GPL2
 * Text Domain: acf-lgm
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Define plugin constants
define('ACF_LGM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ACF_LGM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ACF_LGM_DEFAULT_GRID_COLUMNS', 4);
define('ACF_LGM_DEFAULT_ITEM_HEIGHT', 100);
define('ACF_LGM_DEFAULT_BG_COLOR', '#ffffff');

/**
 * Register ACF Options Page and Fields
 */
add_action('acf/include_fields', 'acf_lgm_register_acf_fields');
function acf_lgm_register_acf_fields() {
    if (!function_exists('acf_add_options_page')) return;

    // Add main options page
    acf_add_options_page([
        'page_title' => __('Layout Grid Settings', 'acf-lgm'),
        'menu_title' => __('Layout Grid', 'acf-lgm'),
        'menu_slug'  => 'acf-layout-grid-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-grid-view'
    ]);

    // Register settings fields
    acf_add_local_field_group([
        'key' => 'group_acf_lgm_settings',
        'title' => __('Layout Grid Configuration', 'acf-lgm'),
        'fields' => [
            [
                'key' => 'field_acf_lgm_grid_settings',
                'label' => __('Grid Display Settings', 'acf-lgm'),
                'name' => 'acf_lgm_grid_settings',
                'type' => 'group',
                'instructions' => __('Configure how the layout grid appears in the admin area.', 'acf-lgm'),
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_acf_lgm_grid_columns',
                        'label' => __('Number of Columns', 'acf-lgm'),
                        'name' => 'grid_columns',
                        'type' => 'number',
                        'instructions' => __('Set the number of columns in the grid (1-6)', 'acf-lgm'),
                        'default_value' => ACF_LGM_DEFAULT_GRID_COLUMNS,
                        'min' => 1,
                        'max' => 6,
                        'step' => 1
                    ],
                    [
                        'key' => 'field_acf_lgm_item_height',
                        'label' => __('Preview Image Height (px)', 'acf-lgm'),
                        'name' => 'item_height',
                        'type' => 'number',
                        'instructions' => __('Set the height for preview images in pixels', 'acf-lgm'),
                        'default_value' => ACF_LGM_DEFAULT_ITEM_HEIGHT,
                        'min' => 50,
                        'max' => 300,
                        'step' => 10
                    ],
                    [
                        'key' => 'field_acf_lgm_bg_color',
                        'label' => __('Background Color', 'acf-lgm'),
                        'name' => 'bg_color',
                        'type' => 'color_picker',
                        'instructions' => __('Set the background color for the grid items', 'acf-lgm'),
                        'default_value' => ACF_LGM_DEFAULT_BG_COLOR
                    ],
                    [
                        'key' => 'field_acf_lgm_hover_effect',
                        'label' => __('Hover Effect', 'acf-lgm'),
                        'name' => 'hover_effect',
                        'type' => 'true_false',
                        'instructions' => __('Enable hover effects on grid items', 'acf-lgm'),
                        'default_value' => 1,
                        'ui' => 1
                    ]
                ]
            ],
            [
                'key' => 'field_acf_lgm_layouts_repeater',
                'label' => __('Layout Previews', 'acf-lgm'),
                'name' => 'acf_lgm_layouts',
                'type' => 'repeater',
                'instructions' => __('Add your layout previews by specifying the layout name and uploading an image.', 'acf-lgm'),
                'min' => 1,
                'layout' => 'block',
                'button_label' => __('Add Layout Preview', 'acf-lgm'),
                'sub_fields' => [
                    [
                        'key' => 'field_acf_lgm_layout_name',
                        'label' => __('Layout Name', 'acf-lgm'),
                        'name' => 'layout_name',
                        'type' => 'text',
                        'instructions' => __('Enter the exact layout name as defined in your flexible content field (e.g. hero_section)', 'acf-lgm'),
                        'required' => 1,
                        'placeholder' => 'layout_name'
                    ],
                    [
                        'key' => 'field_acf_lgm_layout_image',
                        'label' => __('Preview Image', 'acf-lgm'),
                        'name' => 'layout_image',
                        'type' => 'image',
                        'instructions' => __('Upload a preview image for this layout (recommended size: 300x200px)', 'acf-lgm'),
                        'required' => 1,
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                        'library' => 'all'
                    ],
                    [
                        'key' => 'field_acf_lgm_layout_description',
                        'label' => __('Description', 'acf-lgm'),
                        'name' => 'layout_description',
                        'type' => 'text',
                        'instructions' => __('Optional description that appears below the layout name', 'acf-lgm'),
                        'required' => 0
                    ]
                ]
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-layout-grid-settings'
                ]
            ]
        ]
    ]);
}

/**
 * Enqueue admin assets and pass configuration to JavaScript
 */
add_action('acf/input/admin_enqueue_scripts', 'acf_lgm_enqueue_admin_assets');
function acf_lgm_enqueue_admin_assets() {
    // CSS file path and URL
    $css_file = 'assets/css/acf-lgm-admin.css';
    $css_path = ACF_LGM_PLUGIN_DIR . $css_file;
    $css_url = ACF_LGM_PLUGIN_URL . $css_file;
    
    // JS file path and URL
    $js_file = 'assets/js/acf-lgm-admin.js';
    $js_path = ACF_LGM_PLUGIN_DIR . $js_file;
    $js_url = ACF_LGM_PLUGIN_URL . $js_file;
    
    // Enqueue styles if file exists
    if (file_exists($css_path)) {
        wp_enqueue_style(
            'acf-lgm-admin-style',
            $css_url,
            [],
            filemtime($css_path)
        );
    } else {
        // Fallback to prevent errors
        wp_enqueue_style(
            'acf-lgm-admin-style',
            $css_url,
            [],
            '1.0'
        );
    }
    
    // Enqueue scripts if file exists
    if (file_exists($js_path)) {
        wp_enqueue_script(
            'acf-lgm-admin-script',
            $js_url,
            ['jquery'],
            filemtime($js_path),
            true
        );
    } else {
        // Fallback to prevent errors
        wp_enqueue_script(
            'acf-lgm-admin-script',
            $js_url,
            ['jquery'],
            '1.0',
            true
        );
    }

    // Get current post ID in ACF admin screen
    global $post;
    $post_id = $post ? $post->ID : (isset($_GET['post']) ? intval($_GET['post']) : 0);

    // Initialize data array
    $data = [
        'layouts' => [],
        'settings' => [
            'grid_columns' => ACF_LGM_DEFAULT_GRID_COLUMNS,
            'item_height' => ACF_LGM_DEFAULT_ITEM_HEIGHT,
            'bg_color' => ACF_LGM_DEFAULT_BG_COLOR,
            'hover_effect' => true
        ],
        'placeholder' => ACF_LGM_PLUGIN_URL . 'assets/images/placeholder.jpg'
    ];

    // Get grid settings from options
    if (function_exists('get_field')) {
        $grid_settings = get_field('acf_lgm_grid_settings', 'option');
        if ($grid_settings) {
            $data['settings'] = wp_parse_args($grid_settings, $data['settings']);
        }

        // Get layout previews from options
        $repeater = get_field('acf_lgm_layouts', 'option');
        $repeater_map = [];

        if (is_array($repeater)) {
            foreach ($repeater as $layout) {
                if (is_array($layout) && !empty($layout['layout_name'])) {
                    $repeater_map[$layout['layout_name']] = [
                        'image' => $layout['layout_image'] ?? '',
                        'description' => $layout['layout_description'] ?? ''
                    ];
                }
            }
        }

        // Get only layouts used in current post's flexible fields
        $active_layouts = [];

        if ($post_id) {
            $field_groups = acf_get_field_groups(['post_id' => $post_id]);
            foreach ($field_groups as $group) {
                $fields = acf_get_fields($group['key']);
                if (is_array($fields)) {
                    foreach ($fields as $field) {
                        if ($field['type'] === 'flexible_content' && !empty($field['layouts'])) {
                            foreach ($field['layouts'] as $layout) {
                                if (!empty($layout['name'])) {
                                    $active_layouts[$layout['name']] = true;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Merge active layouts with preview images
        foreach ($active_layouts as $layout_name => $enabled) {
            $data['layouts'][$layout_name] = [
                'image' => $repeater_map[$layout_name]['image'] ?? $data['placeholder'],
                'description' => $repeater_map[$layout_name]['description'] ?? ''
            ];
        }
    }

    // Localize script with configuration data
    wp_localize_script('acf-lgm-admin-script', 'acfLayoutGridManager', $data);
}

/**
 * Create plugin assets directory and default files on activation
 */
register_activation_hook(__FILE__, 'acf_lgm_create_assets');
function acf_lgm_create_assets() {
    $assets = [
        'css' => ACF_LGM_PLUGIN_DIR . 'assets/css',
        'js' => ACF_LGM_PLUGIN_DIR . 'assets/js',
        'images' => ACF_LGM_PLUGIN_DIR . 'assets/images'
    ];

    // Create directories if they don't exist
    foreach ($assets as $dir) {
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }
    }

    // Create default CSS file if it doesn't exist
    $css_file = $assets['css'] . '/acf-lgm-admin.css';
    if (!file_exists($css_file)) {
        file_put_contents($css_file, "/* ACF Layout Grid Manager - Admin Styles */\n");
    }

    // Create default JS file if it doesn't exist
    $js_file = $assets['js'] . '/acf-lgm-admin.js';
    if (!file_exists($js_file)) {
        file_put_contents($js_file, "/* ACF Layout Grid Manager - Admin Script */\n");
    }

    // Copy placeholder image if it doesn't exist
    $placeholder_src = ACF_LGM_PLUGIN_DIR . 'assets/images/placeholder.jpg';
    if (!file_exists($placeholder_src)) {
        // You would need to provide a default placeholder image
        // file_put_contents($placeholder_src, file_get_contents('URL_TO_DEFAULT_PLACEHOLDER'));
    }
}