<?php

namespace App\ExtendSys\Filter;

interface FilterNodeInterface {

    /**
     * Devuelve el Nombre o key del Filtro
     */
    function getName() : string;

    /**
     * Devuelve el valor del Filtro
     */
    function getValue();
}