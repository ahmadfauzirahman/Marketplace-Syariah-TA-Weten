<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

defined('FRONTEND_PATH') || define('FRONTEND_PATH', APP_PATH . '/frontend');
defined('BACKEND_PATH') || define('BACKEND_PATH', APP_PATH . '/backend');
//defined('LPPM_PATH') || define('LPPM_PATH', APP_PATH . '/lppm');

return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'test',
        'charset' => 'utf8',
    ],
    'application' => [
        'appDir' => APP_PATH . '/',

        //modul frontend
        'frontendModule' => FRONTEND_PATH,
        'frontendModelsDir' => FRONTEND_PATH . '/models/',
        'frontendViewsDir' => FRONTEND_PATH . '/views/',
        'frontendControllersDir' => FRONTEND_PATH . '/controllers/',

        //modul backend
        'backendModule' => BACKEND_PATH,
        'backendModelsDir' => BACKEND_PATH . '/models/',
        'backendViewsDir' => BACKEND_PATH . '/views/',
        'backendControllersDir' => BACKEND_PATH . '/controllers/',

        'migrationsDir' => APP_PATH . '/migrations/',
        'pluginsDir' => APP_PATH . '/plugins/',
        'libraryDir' => APP_PATH . '/library/',
        'cacheDir' => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri' => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ]
]);
