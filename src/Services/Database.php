<?php

namespace Services;

use Silex\Application;
use Symfony\Component\DependencyInjection\Container;

/* Callable by using $container->get('database') */

class Database
{

    private $container;
    protected $conn;

    public function __construct(Application $app, Container $container)
    {

        $this->container = $container;
        $this->conn = $app['db'];

    }

    public function insert($table, $insert_array) {

        try {
            $this->conn->insert($table, $insert_array);
        } catch(\Exception $e) {
            return $e->getMessage();
        }

        return $this->conn->lastInsertId();
    }


    public function update($table, $update_array, $unique_field, $unique_id) {

        try {
            if(isset($update_array[$unique_field])) {
                unset($update_array[$unique_field]);
            }
            $this->conn->update($table, $update_array, array($unique_field => $unique_id));

        } catch(\Exception $e) { // on duplicate
            return $e->getMessage();
        }

        return $this->conn->lastInsertId();
    }


    public function exist($table, $column, $value) {

        return $this->conn->createQueryBuilder()
            ->addSelect('*')
            ->from($table, 't')
            ->where("{$column} = :value")->setParameter('value', $value)
            ->setMaxResults(1)
            ->execute()->fetch();

    }

}