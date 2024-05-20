<?php

namespace App\Controllers;

use App\Controllers\ControllerBase;
use Phalcon\Dispatcher;

class AuthPlugin extends ControllerBase
{
    public function beforeExecuteRoute($dispatcher)
    {
        echo "<pre>";
        var_dump("OOO");
        exit;
        // This is executed before every found action
        if ($dispatcher->getActionName() === 'save') {
            $this->flash->error(
                "You do not have permission to save invoices"
            );

            $this->dispatcher->forward(
                [
                    'controller' => 'home',
                    'action'     => 'index',
                ]
            );

            return false;
        }
    }
}