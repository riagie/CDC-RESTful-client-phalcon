<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->helpersDir,
        $config->application->libraryDir,
        $config->application->middlewareDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
        $config->application->vendorDir,
    ]
)->register();

$loader->registerNamespaces(
    [
        'App\Controllers'    => APP_PATH . '/controllers/',
        'App\Controllers\v1' => APP_PATH . '/controllers/v1/',
        'App\Helpers'        => APP_PATH . '/helpers/',
        'App\Library'        => APP_PATH . '/library/',
        'App\Middleware'     => APP_PATH . '/middleware/',
        'App\Migrations'     => APP_PATH . '/migrations/',
        'App\Model'          => APP_PATH . '/models/',
        'App\Plugin'         => APP_PATH . '/plugins/',
    ]
)->register();

$loader->registerFiles(
    [
        BASE_PATH . '/vendor/autoload.php',
    ]
)->loadFiles();
