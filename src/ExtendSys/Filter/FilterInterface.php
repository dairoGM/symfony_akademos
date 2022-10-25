<?php

namespace App\ExtendSys\Filter;

interface FilterInterface {

    /**
     * Devuelve el Filtro Search
     */
    function getSearch() : ?string;

    /**
     * Devuelve una lista de filtros
     */
    function getFilters() : array;

    /**
     * Devuelve un Filtro dado su nombre
     */
    function getFilter($name) : ?FilterNodeInterface;
}