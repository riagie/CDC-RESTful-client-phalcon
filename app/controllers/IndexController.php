<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\ControllerBase;

class IndexController extends ControllerBase
{
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
