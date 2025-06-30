<?php
/**
 * ACF Fields for Layout Grid Manager
 */

if (!defined('ABSPATH')) exit;

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

    // Register settings fields with 50% width
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
                'wrapper' => [
                    'width' => '50%', // Set to 50% width
                ],
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
                'wrapper' => [
                    'width' => '50%', // Set to 50% width
                ],
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