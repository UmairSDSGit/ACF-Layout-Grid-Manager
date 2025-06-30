<?php
/**
 * Plugin Update Checker for Layout Grid Manager
 */
if (!defined('ABSPATH')) exit;

// Load the update checker class
require_once ACF_LGM_PLUGIN_DIR . 'includes/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/UmairSDSGit/ACF-Layout-Grid-Manager',
    ACF_LGM_PLUGIN_DIR . 'acf-layout-preview-manager.php',
    'acf-layout-preview-manager'
);

// Optional: Set branch name
$myUpdateChecker->setBranch('main');