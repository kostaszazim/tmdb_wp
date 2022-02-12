<?php

if (!defined('ABSPATH')) {
    exit;
}

//Abstract includes

require_once __DIR__ . '/abstract/abstract-tmdb-admin-page.php';
require_once __DIR__ . '/abstract/abstract-tmdb-wp-settings.php';

//Classes Includes

require_once __DIR__ . '/includes/tmdb-admin-page-dash.php';
require_once __DIR__ . '/includes/tmdb-admin-settings-page.php';
require_once __DIR__ . '/includes/tmdb-wp-api-settings.php';
require_once __DIR__ . '/includes/tmdb-wp-configuration-settings.php';
require_once __DIR__ . '/includes/tmdb-woo-taxonomy-settings.php';

// Utilities includes
require_once __DIR__ . '/utilities/tmdb-page-config-interceptor.php';
require_once __DIR__ . '/utilities/tmdb-language-setup.php';

//MVC Includes

require_once  __DIR__ . '/controllers/tmdb-admin-ajax.php';
require_once  __DIR__ . '/controllers/tmdb-form-submits.php';
require_once  __DIR__ . '/models/tmdb-configuration.php';
require_once  __DIR__ . '/models/tmdb-settings-db.php';


// Error Includes

require_once __DIR__ . '/errors/tmdb-error.php';