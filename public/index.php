<?php

date_default_timezone_set('Asia/Jakarta');

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

use Phalcon\Di\FactoryDefault;
use App\Library\Utils;

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
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Micro($di);

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    echo "<pre>";
    print_r($application->dispatcher);
    exit;

    $application->handle($_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
    if ($application) {
        return $application->response
            ->setStatusCode(500)->sendHeaders()
            ->setContentType($application->request->getBestAccept())
            ->setContent(Utils::setContent($application->request->getBestAccept(), [
                'RC' => '0500',
                // 'RCM' => 'INTERNAL SERVER ERROR',
                'RCM' => $e,
            ]))
            ->send();
    }

    include APP_PATH . '/config/loader.php';

    return (new Phalcon\Http\Response())
        ->setStatusCode(500)->sendHeaders()
        ->setContentType((new Phalcon\Http\Request())->getBestAccept())
        ->setContent(Utils::setContent((new Phalcon\Http\Request())->getBestAccept(), [
            'RC' => $e->getCode(),
            'RCM' => $e->getMessage(),
        ]))
        ->send();
}
