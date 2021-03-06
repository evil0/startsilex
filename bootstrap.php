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

$app->register(new DF\Silex\Provider\YamlConfigServiceProvider(__DIR__ . "/config/database_{$app['env']}.yml"));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array( $app['env'] => $app['config']['database'] )
));

$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\FormServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), array(
    'locale' => 'en',
    'locale_fallbacks' => array('en'),
    'translator.messages' => array(),
));

$app->register(new Provider\ValidatorServiceProvider());
$app->register(new Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/development.log',
));

$app->register(
    new ConsoleServiceProvider(),
    array(
        'console.name' => 'startSilexConsole',
        'console.version' => '2.0.0',
        'console.project_directory' => __DIR__ . "/.."
    )
);

/* Controllers dependency inijection */
foreach(glob('src/Controller/*.php', GLOB_BRACE) as $file) {

    $file = str_replace(".php","", array_pop(explode("/", $file)));
    $controllerName = preg_replace_callback(
        '/(^|[a-z])([A-Z])/',
        function($m) {
            return strtolower(strlen($m[1]) ? $m[1].".".$m[2] : $m[2]);
        },
        $file
    );
    $className = "Controller\\{$file}";
    $app[$controllerName] = function ($app) use ($className) {
        return new $className($app);
    };

}

$conn = $app['db'] = $app['dbs'][$app['env']];
$app['debug'] = $app['env'] == 'prod' ? false : true;

return $app;