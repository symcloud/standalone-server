<?php

namespace Symcloud\Application\BlobStorage\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public static function index(Request $request)
    {
        return new Response('Hello World');
    }
}
