<?php
/**
 * GitHub update checker integration
 */
require_once plugin_dir_path(__FILE__) . 'includes/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

add_action('init', function() {
    $updateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/UmairSDSGit/ACF-Layout-Grid-Manager',
        __FILE__,
        'acf-layout-grid-manager'
    );
    
    // Optional: Set the branch that contains the stable release
    $updateChecker->setBranch('main');
    
    // Optional: Enable releases
    $updateChecker->getVcsApi()->enableReleaseAssets();
});