<?php
namespace Services\Builder;

abstract class EntityBuilderAbstract {

    /**
     * @var string
     */
    protected $setter_prefix = "set";


    /**
     * @param $attribute
     * @return string
     */
    public function getAttributeSetter($attribute) {
        $attribute = str_replace(' ','',ucwords(str_replace('_',' ',$attribute)));
        return $this->setter_prefix.ucfirst($attribute);
    }

}