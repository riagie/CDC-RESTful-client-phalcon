<?php

// use Phalcon\Escaper;
// use Phalcon\Flash\Direct as Flash;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
// use Phalcon\Mvc\View;
// use Phalcon\Mvc\View\Engine\Php as PhpEngine;
// use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Url as UrlResolver;
use Phalcon\Events\Event;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () use ($di) {
    $config = $di->get('config');

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
// $di->setShared('view', function () {
//     $config = $this->getConfig();

//     $view = new View();
//     $view->setDI($this);
//     $view->setViewsDir($config->application->viewsDir);

//     $view->registerEngines([
//         '.volt' => function ($view) {
//             $config = $this->getConfig();

//             $volt = new VoltEngine($view, $this);

//             $volt->setOptions([
//                 'path' => $config->application->cacheDir,
//                 'separator' => '_'
//             ]);

//             return $volt;
//         },
//         '.phtml' => PhpEngine::class

//     ]);

//     return $view;
// });

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($di) {
    $config = $di->get('config');

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'port'     => $config->database->port,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection     = new $class($params);
    $connectionId   = $connection->getConnectionId();

    $eventsManager = new \Phalcon\Events\Manager();
    $eventsManager->attach('db', function (\Phalcon\Events\Event $event, $connection) use ($connectionId, $di) {
        if ($connection->getConnectionId() === $connectionId && $event->getType() === 'beforeQuery') {
            $logger = $di->get('logger');
            $statement = $connection->getRealSQLStatement() ?: $connection->getSqlStatement();

            $logger->log(\Phalcon\Logger::DEBUG, $statement);
        }
    });

    $connection->setEventsManager($eventsManager);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
// $di->set('flash', function () {
//     $escaper = new Escaper();
//     $flash = new Flash($escaper);
//     $flash->setImplicitFlush(false);
//     $flash->setCssClasses([
//         'error'   => 'alert alert-danger',
//         'success' => 'alert alert-success',
//         'notice'  => 'alert alert-info',
//         'warning' => 'alert alert-warning'
//     ]);

//     return $flash;
// });

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});

$di->setShared('dispatcher', function () use ($di) {
    $config = $di->get('config');
    $dispatcher = false;

    if ($config->debug) {
        $connection = $di->get('db');
        $eventsManager = new \Phalcon\Events\Manager();
        // $eventsManager->attach('dispatch:beforeExecuteRoute', $models->createModels());

        $eventsManager->attach('dispatch:beforeExecuteRoute', function (\Phalcon\Events\Event $event, $connection) {

            echo "<pre>";
            print_r("K");
            exit;

            // $models = new App\Plugin\Models($connection);
            // $models->createModels();

        });

        echo "<pre>";
        print_r("OK");
        exit;

        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
    }

    return $dispatcher;
});

$di->setShared('logger', function ($d = false) use ($di) {
    $apiId = $di->get('apiId');
    $debug = $di->get('config')->debug;

    $formatter = new \Phalcon\Logger\Formatter\Line();
    $formatter->setFormat('[%date%][%type%]['. $apiId . '] %message%');
    $formatter->setDateFormat('D M d H:i:s A Y O');

    if ($debug || $d) {
        $adapter = new \Phalcon\Logger\Adapter\Stream(BASE_PATH . '/logs/' . date('Ymd') . '.log');
    } else {
        $adapter = new \Phalcon\Logger\Adapter\Noop();
    }

    $adapter->setFormatter($formatter);

    $logger  = new \Phalcon\Logger('messages', [
        'main' => $adapter,
    ]);
    $logger->setLogLevel(\Phalcon\Logger::DEBUG);

    return $logger;
});

$di->setShared('apiId', function () {
    $apiId = new \Libraries\Utils();
    $apiId = $apiId->apiId();

    return $apiId;
});
