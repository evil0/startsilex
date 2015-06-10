<?php

require_once  __DIR__ . '/vendor/autoload.php';

use Silex\Application;
use Silex\Provider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as ContainerYamlLoader;
use Knp\Provider\ConsoleServiceProvider;

$container = new ContainerBuilder();
$container->setParameter("ROOT_PATH", dirname(__DIR__));
$loader = new ContainerYamlLoader($container, new FileLocator(__DIR__ . '/config'));
$loader->load('services.yml');

$app = $container->get('silex.app');

$app['env'] = 'dev';
$app['container'] = $container;
$app['routes'] = $app->extend('routes', function (RouteCollection $routes, Application $app) {
    $loader     = new YamlFileLoader(new FileLocator(__DIR__ . '/config'));
    $collection = $loader->load('routes.yml');
    $routes->addCollection($collection);

    return $routes;
});

$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . "/config/database_{$app['env']}.yml"));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array( $app['env'] => $app['config']['database'] )
));

$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\FormServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new Provider\ValidatorServiceProvider());
$app->register(new Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

/* If we are on development environment,
 * do the toolbar !


if($app['env'] == 'dev') {
    $app->register(new Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}
 */
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/development.log',
));

$app->register(
    new ConsoleServiceProvider(),
    array(
        'console.name' => 'startSilexConsole',
        'console.version' => '1.3.0',
        'console.project_directory' => __DIR__ . "/.."
    )
);

$conn = $app['db'] = $app['dbs'][$app['env']];
$app['debug'] = $app['env'] == 'prod' ? false : true;

return $app;
