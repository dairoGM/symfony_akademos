<?php

namespace App\Services;

abstract class ExportList implements \App\Services\ReportFop
{

    private $data;
    private $token;
    private $filters_header;
    private $container;
    protected $filters = array();

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    function getFileName()
    {
        $data = $this->getData();
        $name = isset($data['table_title']) ? $data['table_title'] : 'report';
        return date('dmYhi') . '-' . $name;
    }

    final function getToXmlContent()
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine.orm.entity_manager');
        $this->data = $this->buildData();

        
        /**
         * Variables para las llamadas a los metodos
         */
        $filters = $this->data['filters'];
        $class_name = isset($this->data['class_name']) ? $this->data['class_name'] : FALSE;
        $service_name = isset($this->data['service_name']) ? $this->data['service_name'] : FALSE;
        $method = isset($this->data['method']) ? $this->data['method'] : FALSE;
        $data = isset($this->data['array_data']) ? $this->data['array_data'] : array();
        /**
         * Variables para elementos del documento
         */
        $report_name = $this->data['title_document'];
        $table_title = isset($this->data['table_title']) ? $this->data['table_title'] : '';
        $header = $this->prepareHead($this->data['header']);
    
        if ($class_name !== FALSE) {
            $filters_func = array();
            $order_params = array();
            $criteria = array();

            if (isset($filters['filters'])) {
                $filters_func = $filters['filters'];
            }
            if (isset($filters['order_params'])) {
                $order_params = $filters['order_params'];
            }
            if (isset($filters['criteria'])) {
                $criteria = $filters['criteria'];
            }

            $all_result = $doctrine->getRepository($class_name)->$method($filters_func, $order_params, $criteria);

            if (is_object($all_result)) {
                if (get_class($all_result) == 'Doctrine\ORM\QueryBuilder') {
                    $result = $all_result->getQuery()->getResult();
                }
            } else {
                $result = $all_result;
            }

            $data = \Inagbe\AppBundle\Util\DoctrineHelper::toArray($result, 2);
        } elseif ($service_name !== FALSE) {
            if (is_callable(array($this->container->get($service_name), $method))) {
                $filters_func = array();
                $order_params = array();

                if (isset($filters['filters'])) {
                    $filters_func = $filters['filters'];
                }
                if (isset($filters['order_params'])) {
                    $order_params = $filters['order_params'];
                }
                $data = $this->container->get($service_name)->$method($filters_func, $order_params);
            } else {
                $data = array();
            }
        }
        
        $header_total = array();
        $header_total = array_merge($header_total, array_column((isset($header['first']) ? $header['column']: $header), 'column'));
        $fixedData = $this->prepareStructure($header_total, $data);
              

        $filters_header = $this->getFiltersHeader();

        $user = \App\Services\DoctrineHelper::toArray($this->container->get('security.token_storage')->getToken()->getUser(), 1);
        
        $user['username'] = $user['email'];
        $user['create_date'] = date('d/m/Y H:i');
        $user['title_document'] = $report_name;
        $user['table_title'] = $table_title;
        
        if(isset($this->getPropertyFile()->ceo))
        {
            $output = $container->get('inagbe.configuration.output_configuration_manager');
            $ceo = $output->getCeo();
            $user['ceo'] = $ceo;
        }

        return $data_array = array('filters_header' => $filters_header, 'header' => $header, 'data' => $fixedData, 'user' => $user);
    }

    /**
     * Prepare collection array due to a given structure
     *
     * @param $structure Array
     * @param $collection Array
     * @return Array
     */
    private function prepareStructure($structure, $collection)
    {
        $result = array();

        if (!$collection) {
            return $result;
        }
        foreach ($collection as $item) {
            foreach ($item as $key => $value) {
                if (in_array($key, $structure) === false) {
                    unset($item[$key]);
                    continue;
                }

                if (is_array($value) === true) {
                    if (isset($value['name'])) {
                        $item[$key] = $value['name'];
                        continue;
                    }
                }
            }

            $newItem = array();
            foreach ($structure as $s) {
                $newItem[$s] = $item[$s];
            }

            $result[] = $newItem;
        }
        return $result;
    }

    private function prepareHead($header)
    {

        if (empty(array_column($header, 'column_spanned'))) {
           $xsl =  $this->getXslPath();
            return strpos($xsl, 'complex')===FALSE ? $header : array('column' => $header, 'first' => $header, 'last' => array());
        }
        $firtRow = array();
        $column = array();
        $lastRow = array();
        foreach ($header as $k => $el) {
            if (isset($el['column_spanned'])) {
                $firtRow[] = array(
                    'column_spanned' => 'group_' . $k,
                    'column_title' => $el['column_title'],
                    'column_spanned' => count($el['column_spanned']),
                    'row_spanned' => 1
                );
                foreach ($el['column_spanned'] as $subEl) {
                    $lastRow[] = array_merge($subEl, array('row_spanned' => 1, 'column_spanned' => 1));
                    $column[] = array('width' => $subEl['width'], 'column' => $subEl['column']);
                }
            } else {
                $firtRow[] = array_merge($el, array('row_spanned' => 2, 'column_spanned' => 1));
                $column[] = array('width' => $el['width'], 'column' => $el['column']);
            }
        }
        return array('column' => $column, 'first' => $firtRow, 'last' => $lastRow);
    }

    public function getXslPath()
    {
        return 'generals_templates/list_basic.xsl';
    }

    public function getDeleteTemplate()
    {
        return FALSE;
    }

    public function getDownload()
    {
        return TRUE;
    }

    public function getStoragePath()
    {
        return '';
    }

    public function getPropertyFile()
    {
        return '';
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setFiltersHeader($filters_header)
    {
        $this->filters_header = $this->prepareHeaderFilters($filters_header);
    }

    public function getFiltersHeader()
    {
        return $this->filters_header;
    }

    abstract public function buildData();
}
