<?php
namespace Services\Mapper;

use Services\Builder\EntityBuilder;

abstract class MapperAbstract
{

    /**
     * Create a new instance of the DomainObject that this
     * mapper is responsible for. Optionally populating it
     * from a data array.
     *
     * @param array $data
     */
    public function create(array $data = null) {
        $obj = $this->_create();
        if ($data) {
            $obj = $this->populate($obj, $data);

        }
        return $obj;
    }

    /**
     * Save the DomainObject
     *
     * Store the DomainObject in persistent storage. Either insert
     * or update the store as required.
     *
     */
    public function save($obj)
    {
        if (is_null($obj->getId())) {
            $this->_insert($obj);
        } else {
            $this->_update($obj);
        }
    }

    /**
     * Delete the DomainObject
     *
     * Delete the DomainObject from persistent storage.
     *
     */
    public function delete($obj)
    {
        $this->_delete($obj);
    }

    public function findBy($campo, $valore) {

        $dati = $this->connection->createQueryBuilder()
            ->select('*')
            ->from("{$this->table}", "a")
            ->where("{$campo} = :parametro")
            ->setParameter("parametro", $valore)
            ->execute()->fetch();

        if(!$dati) {
            return false;
        }

        return $this->populate($this->create(), $dati);
    }

    public function populate($obj, array $data) {
        $entity = new EntityBuilder($obj);
        $entity->buildEntity($data);
        return $entity->getEntity();
    }

    /**
     * Create a new instance of a DomainObject
     *
     */
    abstract protected function _create();

    /**
     * Insert the DomainObject to persistent storage
     *
     */
    abstract protected function _insert($obj);

    /**
     * Update the DomainObject in persistent storage
     *
     */
    abstract protected function _update($obj);

    /**
     * Delete the DomainObject from peristent Storage
     *
     */
    abstract protected function _delete($obj);
}
