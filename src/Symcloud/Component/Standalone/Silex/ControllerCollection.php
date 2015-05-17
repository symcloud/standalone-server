<?php

namespace Symcloud\Component\Standalone\Silex;

use React\Http\Request;
use React\Http\Response;
use Silex\ControllerCollection as BaseControllerCollection;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ControllerCollection extends BaseControllerCollection
{
    public function match($pattern, $to = null)
    {
        $wrapped = $this->wrapController($to);

        return parent::match($pattern, $wrapped);
    }

    private function wrapController($controller)
    {
        return function (SymfonyRequest $sfRequest) use ($controller) {
            /** @var Response $response */
            $response = $sfRequest->attributes->get('react.espresso.response');
            /** @var SymfonyResponse $sfResponse */
            $sfResponse = call_user_func($controller, $sfRequest);

            $response->writeHead($sfResponse->getStatusCode(), $sfResponse->sendHeaders());
            $response->end($sfResponse->getContent());

            return $sfResponse;
        };
    }
}
