<?php
declare(strict_types=1);

namespace App\Controllers\v1;

use App\Controllers\ControllerBase;

class AboutController extends ControllerBase
{
    public function index()
    {
        echo '[' . __METHOD__ . ']';
        exit;
    }

    public function indexAction()
    {
        echo '[' . __METHOD__ . ']';
        exit;
    }

}
