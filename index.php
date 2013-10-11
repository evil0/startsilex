<?php

include_once 'bootstrap.php';

use Symfony\Component\HttpFoundation\Response;

/*
 * Error Handling
 */

$app->error(function (\Exception $e, $code) use ($app) {
    switch($code) {
        case 404:
            return new Response( $app['twig']->render('404.html.twig'), 404);
            break;
    }
    if($app['env'] == 'prod') {
        return new Response( $app['twig']->render('500.html.twig'), 500);
    }
});

$app->run();