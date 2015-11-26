<?php
namespace Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

abstract class BaseController
{

    public function __construct(Application $app) {
        $this->container = $app["container"];
        $this->app = $app;
    }

}
