<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/* To call the container use $app['container'] */

class AppController extends BaseController
{
    public function homeAction()
    {
        $foo = $this->app["container"]->get("mapper.foo")->create();
        $foo->setName("Marco (evil0) Costantino");

        return $this->app['twig']->render('index.html.twig', array("author" => $foo->getName()));
    }

}
