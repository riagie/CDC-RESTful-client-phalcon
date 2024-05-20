<?php

use Phalcon\Mvc\Micro\Collection as MicroCollection;

$uri = false;
if (strstr($_SERVER['REQUEST_URI'], '/' . basename(BASE_PATH) . '/', false)) {
    $uri = '/' . basename(BASE_PATH);
}

// Define your routes here

$OAuth = new MicroCollection();
$OAuth->setPrefix($uri);
$OAuth->setHandler(\App\Controllers\v1\AboutController::class, true);
$OAuth->get('/client_credential/access_token', 'index');
$application->mount($OAuth);

// $api = new MicroCollection();
// $api->setPrefix($uri);
// $api->setHandler(\App\Controllers\IndexController::class, true);
// $api->get('/', 'index');
// $application->mount($api);

// $application->before(new App\Middleware\AuthHeaderValidator());
// $application->before(new App\Plugin\CreateModels());
// echo "<pre>";
// // print_r($config);
// print_r(new App\Plugin\CreateModels($application));
// exit;

$application->notFound(function () use ($application) {
    $application->response
        ->setStatusCode(400)->sendHeaders()
        ->setContentType($application->request->getBestAccept())
        ->setContent(\App\Library\Utils::setContent($application->request->getBestAccept(), [
            'RC' => '0400',
            'RCM' => 'INVALID REQUEST PARAMETER',
        ]))
        ->send();
});
