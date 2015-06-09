<?php
namespace Services\Builder;

class EntityBuilder extends EntityBuilderAbstract implements EntityBuilderInterface {

    private $entity;


    public function __construct($entity) {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param array $values
     */
    public function buildEntity(array $values) {
        foreach ($values as $attribute => $value)
        {
            $setter = $this->getAttributeSetter($attribute);
            if (method_exists($this->entity, $setter)) {
                $this->entity->$setter($value);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getEntity() {
        return $this->entity;
    }

}