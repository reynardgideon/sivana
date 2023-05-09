<?php

/**
 * Register our sidebars and widgetized areas.
 *
 */
$dir = get_template_directory() . '/functions/';
$files = scandir($dir);
foreach ($files as $file) {
    if (is_file($dir.$file)) {
        include_once(get_template_directory() . '/functions/' . $file);
    }
}