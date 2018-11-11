<?php

use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';
    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';


    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    // Register the installed modules
    $application->registerModules([
        'frontend' => [
            'className' => 'Wetenrisio\Frontend\Module',
            'path' => $config->application->frontendModule . '/Module.php'
        ],
        'backend' => [
            'className' => 'Wetenrisio\Backend\Module',
            'path' => $config->application->backendModule . '/Module.php'
        ]
    ]);

    echo str_replace(["\n", "\r", "\t"], '', $application->handle()->getContent());

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}


