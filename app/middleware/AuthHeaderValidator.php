<?php

namespace App\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class AuthHeaderValidator implements MiddlewareInterface
{
    public function call(Micro $application)
    {
        $request = new \Phalcon\Http\Request;

        if (!$request->getHeader("X-Client-Id")) {
            $application->response
                ->setStatusCode(400)->sendHeaders()
                ->setContentType($request->getBestAccept())
                ->setContent(\App\Library\Utils::setContent($request->getBestAccept(), [
                    'RC' => '0400',
                    'RCM' => 'INVALID REQUEST PARAMETER',
                ]))
                ->send();
            $application->stop();

            return false;
        }

        if ((!$request->getHeader("X-Signature") && !$request->getHeader("X-Access-Token")) && !strstr($request->getURI(), '/reset_password', true)) {
            $application->response
                ->setStatusCode(400)->sendHeaders()
                ->setContentType($request->getBestAccept())
                ->setContent(\App\Library\Utils::setContent($request->getBestAccept(), [
                    'RC' => '0400',
                    'RCM' => 'INVALID REQUEST PARAMETER',
                ]))
                ->send();
            $application->stop();

            return false;
        }

        return true;
    }
}
