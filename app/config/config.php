<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'version'   => 'v4',
    'debug'     => true,
    'database'  => [
        'adapter'   => 'Mysql',
        'host'      => '127.0.0.1',
        'port'      => 3306,
        'username'  => 'root',
        'password'  => '',
        'dbname'    => 'API_CDCCORE',
        'charset'   => 'utf8',
    ],
    'application' => [
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),

        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',

        'helpersDir'     => APP_PATH . '/helpers/',
        'libraryDir'     => APP_PATH . '/library/',
        'middlewareDir'  => APP_PATH . '/middleware/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'modelsDir'      => APP_PATH . '/models/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'viewsDir'       => APP_PATH . '/views/',

        'cacheDir'       => BASE_PATH . '/cache/',
        'logsDir'        => BASE_PATH . '/logs/',
        'vendorDir'      => BASE_PATH . '/vendor/',
    ],
]);
