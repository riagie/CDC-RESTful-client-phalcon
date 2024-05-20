<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\ControllerBase;
use App\Library\Utils;

class ValidationController extends ControllerBase
{
    public function validate()
    {
        return (new \Phalcon\Http\Response())
            ->setStatusCode(500)->sendHeaders()
            ->setContentType((new \Phalcon\Http\Request())->getBestAccept())
            ->setContent(Utils::setContent((new \Phalcon\Http\Request())->getBestAccept(), [
                'RC' => '2222',
                'RCM' => 'INTERNAL SERVER ERROR',
            ]))
            ->send();
    }

    public function token()
    {
        return (new \Phalcon\Http\Response())
            ->setStatusCode(500)->sendHeaders()
            ->setContentType((new \Phalcon\Http\Request())->getBestAccept())
            ->setContent(Utils::setContent((new \Phalcon\Http\Request())->getBestAccept(), [
                'RC' => '3333',
                'RCM' => 'INTERNAL SERVER ERROR',
            ]))
            ->send();
    }

    public function validateAction()
    {
        echo 'OK';
        exit;
    }

    public function indexAction()
    {
        echo '[' . __METHOD__ . ']';
        exit;
    }

    public function index()
    {
        echo '[' . __METHOD__ . ']';
        exit;
    }

}
