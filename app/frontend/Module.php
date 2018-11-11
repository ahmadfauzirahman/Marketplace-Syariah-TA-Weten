<?php

namespace Wetenrisio\Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Db\Adapter\Pdo\Mysql as Database;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
//use Phalcon\Mvc\Dispatcher;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $config = $di->getConfig();

        $loader = new Loader();
        $loader->registerNamespaces(
            [
                'Wetenrisio\Forms' => $config->application->formsDir,
                'Wetenrisio\Library' => $config->application->libraryDir,
                'Wetenrisio\Migrations' => $config->application->migrationsDir,
                'Wetenrisio\Plugins' => $config->application->libraryDir,


                'Wetenrisio\Backend\Models' => $config->application->backendModelsDir,
                'Wetenrisio\Backend\Views' => $config->application->backendViewsDir,
                'Wetenrisio\Backend\Controllers' => $config->application->backendControllersDir,

                'Wetenrisio\Frontend\Models' => $config->application->frontendModelsDir,
                'Wetenrisio\Frontend\Views' => $config->application->frontendViewsDir,
                'Wetenrisio\Frontend\Controllers' => $config->application->frontendControllersDir,
            ]
        );

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {

        // Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Wetenrisio\Frontend\Controllers\\');
            return $dispatcher;
        });


        // Registering the view component
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir($this->getConfig()->application->frontendViewsDir);

            $view->registerEngines([
                '.volt' => function ($view) {
                    $config = $this->getConfig();

                    $volt = new VoltEngine($view, $this);

                    $volt->setOptions([
                        'compiledPath' => $config->application->cacheDir,
                        'compiledSeparator' => '_'
                    ]);

                    return $volt;
                },
                '.phtml' => PhpEngine::class,
                '.php' => PhpEngine::class

            ]);

            return $view;
        });


        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->set('db', function () {
            $config = $this->getConfig();

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
            $params = [
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbnameOffice,
                'charset' => $config->database->charset
            ];

            if ($config->database->adapter == 'Postgresql') {
                unset($params['charset']);
            }

            $connection = new $class($params);

            return $connection;
        });
    }
}
