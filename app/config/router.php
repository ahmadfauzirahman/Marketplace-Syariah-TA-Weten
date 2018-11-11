<?php

use Phalcon\Mvc\Router\Group as RouterGroup;


class Frontend extends RouterGroup
{
    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module' => 'frontend',
                'namespace' => 'Wetenrisio\Frontend\Controllers',
            ]
        );

        $this->setPrefix('');
        $this->add('/', 'index::index');

    }

}

class Backend extends RouterGroup
{
    public function initialize($di)
    {
        // Default paths
        $this->setPaths(
            [
                'module' => 'backend',
                'namespace' => 'Wetenrisio\Backend\Controllers',
            ]
        );

        // All the routes start with /blog
        $this->setPrefix('/backend');

        $this->add('/', ['controller' => 'index', 'action' => 'index']);
//        $this->add('/errors/show404', 'error::show404');

        $this->add('/:params', [
                'controller' => 'index',
                'action' => 'index',
                'params' => 3,
            ]
        );
        $this->add('/:controller/:params', [
                'controller' => 1,
                'action' => 'index',
                'params' => 3,
            ]
        );
        $this->add('/:controller/:action/:params', [
                'controller' => 1,
                'action' => 2,
                'params' => 3,
            ]
        );
    }
}

$router = $di->getRouter();

$router->mount(
    new Backend()
);
$router->mount(
    new Frontend()
);

// Define your routes here

$router->handle();
