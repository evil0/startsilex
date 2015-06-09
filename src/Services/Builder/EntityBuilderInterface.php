<?php
namespace Services\Builder;

interface EntityBuilderInterface {

    /**
     * @param array $values
     * @return mixed
     */
    public function buildEntity(array $values);

    /**
     * @return mixed
     */
    public function getEntity();

    /**
     * @param $attribute
     * @return mixed
     */
    public function getAttributeSetter($attribute);

}