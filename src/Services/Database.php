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
        $this->app = $app;

    }


    public function getTables() {
        return $this->conn->executeQuery("SELECT table_name as name FROM INFORMATION_SCHEMA.TABLES  WHERE table_schema = '".$this->app['config']['database']["dbname"]."'")->fetchAll();
    }

    public function getColumns($tableName) {
        return  $this->conn->executeQuery("SELECT COLUMN_NAME as name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA =  '".$this->app['config']['database']["dbname"]."' AND TABLE_NAME = '".$tableName."'")->fetchAll();
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