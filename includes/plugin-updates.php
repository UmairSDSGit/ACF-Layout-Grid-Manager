<?php
/**
 * Plugin Update Checker for Layout Grid Manager
 */

if (!defined('ABSPATH')) exit;

// Plugin update checker
require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/UmairSDSGit/ACF-Layout-Grid-Manager.git',
    __FILE__,
    'ACF-Layout-Grid-Manager'
);

// Optional: Set branch for updates (default: master/main)
$myUpdateChecker->setBranch('main');