<?php

namespace App\ExtendSys\Filter;

use App\ExtendSys\Filter\FilterNodeInterface;

class FilterNodeImpl implements FilterNodeInterface {

    /**
     * Filter key name
     */
    private $name;

    /**
     * Filter value
     */
    private $value;


    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }


    /**
     * Devuelve el Nombre o key del Filtro
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Devuelve el valor del Filtro
     */
    public function getValue()
    {
        return $this->value;
    }
}