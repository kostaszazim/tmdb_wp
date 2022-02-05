<?php

if (!defined('ABSPATH')) {
    exit;
}

//Abstract includes

require_once __DIR__ . '/abstract/abstract-tmdb-admin-page.php';

//Classes Includes

require_once __DIR__ . '/includes/tmdb-admin-page-dash.php';
require_once __DIR__ . '/includes/tmdb-admin-settings-page.php';
require_once __DIR__ . '/includes/tmdb-wp-settings.php';

// Utilities includes
require_once __DIR__ . '/utilities/tmdb-page-config-interceptor.php';

//MVC Includes

require_once  __DIR__ . '/controllers/tmdb-admin-ajax.php';
require_once  __DIR__ . '/models/tmdb-configuration.php';