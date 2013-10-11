<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/* To call the container use $app['container'] */

class AppController
{
    public function homeAction(Application $app)
    {
       return $app['twig']->render('index.html.twig');
    }

}
