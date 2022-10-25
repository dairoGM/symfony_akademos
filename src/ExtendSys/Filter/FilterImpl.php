<?php

namespace App\ExtendSys\Filter;

use App\ExtendSys\Filter\FilterInterface;
use App\ExtendSys\Filter\FilterNodeInterface;

class FilterImpl implements FilterInterface
{

    private $search;

    private $filters = array();

    /**
     * Devuelve el Filtro Search
     */
    public function getSearch() : ?string 
    {
        return isset($this->search) && !empty($this->search) ? $this->search : null;
    }

    /**
     * Devuelve una lista de filtros
     */
    public function getFilters() : array
    {
        return $this->filters;
    }

    /**
     * Devuelve un Filtro dado su nombre.
     * Si no es null o tiene un valor vacio
     */
    public function getFilter($name) : ?FilterNodeInterface
    {
        if(array_key_exists($name, $this->filters))
        {
            $filter = $this->filters[$name];

            if (isset($filter)){
                if(null !== $filter->getValue() && !empty($filter->getValue()))
                {
                    return $filter;
                }
            }        
        }

        return null;
    }

    /**
     * Adiciona un Filtro 
     */
    public function addFilter($filterImpl)
    {

    $this->filters[$filterImpl->getName()] = $filterImpl;
    }

    /**
     * Adiciona el search
     */
    public function setSearch($search) 
    {
        $this->search = $search;
    }

    /**
     * Crea y Adiciona los filtros desde un array
     *
     * array(
     *      'search' = > 'cadena'
     *      'filtro1' => valor1
     *  	'filtro2 => valor2
     * ) 
     *
     * @param [type] $array_map
     * @return void
     */
    public static function createFiltersFromArray($array_map) : FilterImpl
    {
       $filter = new FilterImpl();

       foreach($array_map as $key=>$val)
       {            
            if($key == 'search')
            {
                $filter->setSearch($val);
            }
            else
            {
                $filter->addFilter(new FilterNodeImpl($key, $val));
            }            
       } 

       return $filter;
    }
}
