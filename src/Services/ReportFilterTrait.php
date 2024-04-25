<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;

/**
 * Created by PhpStorm.
 * User: joelmcs6@gmail.com
 * Date: 08/09/14
 * Time: 16:43
 */
trait ReportFilterTrait {

    public $total = 0;
    public $result = array();
    public $row = 0;
    public $maxColumns = 20;

    public function prepareHeaderFilters($filters) {
        $obj = new \ArrayObject($filters);
        /**
         * @var \ArrayIterator
         */
        $iterator = $obj->getIterator();

        // set step
        $step = $this->setStep($iterator);
        $count = 1;

        while ($iterator->valid()) {
            $current = $iterator->current();
            $iterator->next();
            $isLast = $iterator->valid() === false;

            $next = $isLast ? array() : $iterator->current();
            $over = !1;
            if ($isLast || $count % 3 === 0) {
                $current['cols'] = $this->maxColumns - $this->total;
            } else {
                if (isset($current['cols'])) {
                    $current['cols'] = $current['cols'];
                } else {
                    $current['cols'] = $step;
                }
            }

            if ($this->total + $current['cols'] <= $this->maxColumns) {
                $nextValue = isset($next['cols']) ? $next['cols'] : $this->maxColumns - $this->total - $current['cols']; //make sure only go over if $next[cols] is defined

                if ($this->total + $current['cols'] + $nextValue > $this->maxColumns) {
                    $value = $this->maxColumns - $this->total;
                    $current['cols'] = $value === 0 ? $current['cols'] : $value;
                    $over = true;
                }

                $this->total += $current['cols'];

                $over = $over || $this->total == $this->maxColumns;
                $this->result[$this->row][] = $current;
                $count++;
                if ($over) {
                    $this->row++;
                    $this->total = 0;
                    $count = 1;
                }
            } else {
                throw new \Exception(sprintf('The number of columns defined for a filter must be less than %s, value: %s given.', $this->maxColumns, $this->total + $current['cols']));
            }
        }

        return $this->result;
    }

    public function getResult() {
        $this->result;
    }

    public function setStep(\ArrayIterator $iterator) {
        $length = $iterator->count();
        switch ($length) {
            case 1: return 20;
            case 2: return 10;
            default: return 7;
        }
    }

    private function getNameGivenFilter($enter_key, $array_filters) {
        $keys = array_keys((array) $array_filters); //obtengo todas los indices del arreglo
        $new_key = $enter_key . '_name';
        if (in_array($new_key, $keys)) {//pregunto si el indice existe en el arreglo de indice
            $temp = (array) $array_filters->$new_key;
            return $temp[0];
        }
    }

    public function prepareDinamicFilters($filter_header, $filters_grid) {
        /* Filtro del arreglo de filtros solo los que tiene valores,solo los filtros que vienen de la vista */
        $copy_filter_header = null;
        foreach ($filter_header as $key => $value) {
            if (!empty($value['value'])) {
                $temp = $this->getNameGivenFilter($value['key'], $filters_grid);
                $value['value'] = $temp;
                if (substr_count($value['value'], ',') >= 5) {
                    $value['cols'] = 20;
                }
                $copy_filter_header[] = $value;
            }
        } 
        return $copy_filter_header;
    }

    public function prepareFiltersColumns($filters, $columns, $filters_of_view) {
        $new_columns = array();
        /* Filtros (XX) candidatos a dejar de ser columnas y ser cabeceras */
        $filter_enter_whit_value = null;
        $simple_filter = null;

        foreach ($filters_of_view as $key => $value) {
            $is_column = false;
            foreach ($columns as $key1 => $value1) {
                if ($key == $value1['key_filter']) {
                    $is_column = true;
                    break;
                }
            }
            if (is_array($value) && count($value) > 1 || $is_column) {
                $filter_enter_whit_value[$key] = $value;
            } else if (is_array($value) && count($value) == 1) {
                $simple_filter[$key] = $value;
            }
        }
        if (count($filter_enter_whit_value) > 0) {
            /* Buscar de los filtros (XX) cuales estan como columnas */
            $column_update = null;
            foreach ($filter_enter_whit_value as $key => $v) {
                foreach ($columns as $value) {
                    $entro = false;
                    foreach ($value as $k => $val) {
                        if ($key == $val) {
                            $column_update[] = $key;
                            $entro = true;
                            break;
                        }
                    }
                    if ($entro) {
                        break;
                    }
                }
            }
            /* Modifico el arreglo de columnas quitando las que tienen mas de dos elementos y la agrego al arreglo de filtros */
            $column_delete = array();
            foreach ($columns as $key => $value) {
                foreach ($column_update as $value1) {
                    if ($value['key_filter'] == $value1) {
                        $column_delete[] = $value;
                    }
                }
            }

            /* Agrego las columnas quitadas al arreglo de filtros y calculo los porcientos eliminados para ser distribuido en el resto de las columnas que no sufrieron cambios */
            $porcent_delete = 0;
            if (count($column_delete) > 0) {
                foreach ($column_delete as $value) {
                    $value_filter = $value['key_filter'] . '_name';
                    $cols = $value['key_filter'] . '_cols';
                    $temp = explode('%', $value['width']);
                    $porcent_delete += (int) $temp[0];
                    /* El indice value tendra como valor el indice que tendrá el arreglo de filtros que viene de la vista
                     * Este arreglo tendra que tener los valores de cada ids de los filtros en un indice que sea igual al nombre del indice original concatenado con _name                  
                     */
                    if (isset($filters_of_view->$cols)) {
                        $obj = array(
                            'key_filter' => $value['key_filter'],
                            'column' => $value['column'],
                            /** @Ignore */'label' => $value['column_title'],
                            'value' => $filters_of_view->$value_filter,
                            'cols' => $filters_of_view->$cols
                        );
                    } else {
                        $obj = array(
                            'key_filter' => $value['key_filter'],
                            'column' => $value['column'],
                            /** @Ignore */'label' => $value['column_title'],
                            'value' => $filters_of_view->$value_filter
                        );
                    }

                    $filters[] = $obj;
                }
            }

            foreach ($columns as $key => $value) {
                $entro = false;
                foreach ($column_delete as $key1 => $value1) {
                    if ($value['key_filter'] == $value1['key_filter']) {
                        $entro = true;
                        break;
                    }
                }
                if (!$entro) {
                    $new_columns[] = $value;
                }
            }

            /* Distribuyendo los porcientos eliminados.... */

            $cant_colum = count($new_columns) - 1;
            $parte_equitativa = floor($porcent_delete / $cant_colum);
            $column_temp = array();

            $sum_res = 0;
            foreach ($new_columns as $key => $value) {
                $temp = explode('%', $value['width']);
                $new_width = null;

                if ($key != count($new_columns) - 1) {
                    $new_width = (int) $temp[0] + (int) $parte_equitativa . '%';
                    $sum_res += $new_width;
                } else {
                    $new_width = (int) (96 - $sum_res) . '%';
                }

                $obj = array(
                    'key_filter' => $value['key_filter'],
                    'column' => $value['column'],
                    'column_title' => $value['column_title'],
                    'width' => $new_width
                );
                $column_temp[] = $obj;
            }
            $resul['columns'] = $column_temp;
        } else {
            $resul['columns'] = $columns;
        }

        $new_filters = array();
        foreach ($filters as $key => $value) {
            if ($key != 0 && !empty($value['value'])) {
                $new_filters[] = $value;
            }
        }

        /* Mostrar los filtros dinamicos */

        foreach ($simple_filter as $key => $value) {
            $ind = $key . '_name';
            $value_filter = $filters_of_view->$ind;

            $is_column = false;
            foreach ($columns as $tem) {
                if ($key == $tem['key_filter']) {
                    $is_column = true;
                    break;
                }
            }
            if (!empty($value_filter) && !$is_column) {
                $label = '';
                foreach ($filters as $value1) {
                    if ($value1['key_filter'] == $key) {
                        $label = $value1['label'];
                        break;
                    }
                }
                $obj = array(
                    'key_filter' => $key,
                    'column' => '',
                    /** @Ignore */'label' => $label,
                    'value' => $value_filter
                );
                $new_filters[] = $obj;
            }
        }

        $resul['filters'] = $new_filters;
        return $resul;
    }

}
