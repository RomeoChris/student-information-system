<?php

namespace App\Core\Routing;


use App\Core\App\AppHttpRequest;
use App\Core\App\IApp;

class Dispatcher
{
    private $request;
    private $route;

    public function __construct(AppHttpRequest $request, IApp $app)
    {
        $this->request = $request;
        $this->route = new Route($app);
    }

    public function runRequest()
    {
        $this->route->requestedPath($this->request->getRequestPath());
    }
}
