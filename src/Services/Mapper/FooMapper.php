<?php
namespace Services\Mapper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Entity\Foo;
use Silex\Application;
use Symfony\Component\DependencyInjection\Container;

/* Example of implementation */
class FooMapper extends MapperAbstract {

    protected $connection = array();
    protected $container = array();
    public $table ="foos";

    public function __construct(Application $app, Container $container) {
        $this->container = $container;
        $this->connection = $app['db'];
    }

    public function _create() {

        return new Foo($this->container);
    }


    public function _insert($obj) {
    }

    public function _update($obj) {
    }

    public function _delete($obj) {
    }

} 