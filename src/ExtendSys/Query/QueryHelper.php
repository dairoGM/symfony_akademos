<?php

namespace App\ExtendSys\Query;

class QueryHelper 
{   

    /**
     * Prepara un string search para busquedas
     */
    public static function prepareSearchParam($search)
    {
        $search = strtolower($search);
        $search = '%' . $search . '%';

        return $search;
    }

    /**
     * Prepara un campo columna para busquedas
     */
    public static function prepareSearchColunm($field)
    {
        return "lower($field)";
    }





    /**
     * Fucntion for dev, Show SQL builded
     */
    public static function showSql($queryBuilder)
    {
        pr($queryBuilder->getSQL());
    }

    /**
     * Fucntion for dev, Show SQL builded
     */
    public static function showParams($queryBuilder)
    {
        pr($queryBuilder->getParameters());
    }
}