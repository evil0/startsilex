<?php

namespace Entity;

use \JsonSerializable;
use Silex\Application;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityAbstract implements JsonSerializable {

    protected $connection = array();
    protected $container = array();

    public function __construct(Container $container) {
        $this->container = $container;
    }


    /*  @php5.5
    public function getAttributes() {
        foreach (get_object_vars($this) as $key => $value) {
            yield $key => $value ;
         }
    } */


    public function toArray() {
        $array = array();

        foreach(get_object_vars($this) as $key => $attribute) {
            $array[$key] = $attribute;
        }

        return $array;
    }

    public function jsonSerialize() {
        return $this->toArray();
    }


} 