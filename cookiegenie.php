<?php
/**
 * Plugin Name: CookieGenie
 * Version: 1.0.0
 * Plugin URI: https://www.graphicgenie.nl/cookiegenie
 * Description: Ask user for permission before loading analytics and tracking scripts and cookies.
 * Author: Ronald Gerssen - GraphicGenie
 * Author URI: https://www.graphicgenie.nl
 * Requires at least: 5.9.3
 * Tested up to: 5.9.3
 *
 * Text Domain: cookiegenie
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Ronald Gerssen
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load plugin class files.
require_once 'includes/class-cookiegenie.php';
require_once 'includes/class-cookiegenie-settings.php';
require_once 'includes/class-cookiegenie-init.php';

// Load plugin libraries.
require_once 'includes/lib/class-cookiegenie-admin-api.php';

function cookiegenie()
{
    $instance = CookieGenie::instance(__FILE__, '1.0.0');

    if (is_null($instance->settings)) {
        $instance->settings = CookieGenie_Settings::instance($instance);
    }

    $instance->cookiegenie = CookieGenie_Init::instance($instance);

    return $instance;
}

cookiegenie();
