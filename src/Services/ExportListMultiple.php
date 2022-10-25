<?php

namespace App\Service;

use App\Service\ReportFop;

abstract class ExportListMultiple implements ReportFop
{
    private $data;
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
        $name = isset($data[0]['table_title']) ? $data[0]['table_title'] : 'report';
        return date('dmYhi') . '-' . $name;
    }

    final function getToXmlContent()
    {
        $container = $this->getContainer();

        $doctrine = $container->get('doctrine.orm.entity_manager');

        $this->buildData();

        $data_build = $this->getData();

        $export_elemts = array();

        foreach ($data_build as $element) {
            /**
             * Variables para las llamadas a los metodos
             */
            $filters = $element['filters'];
            $class_name = isset($element['class_name']) ? $element['class_name'] : FALSE;
            $service_name = isset($element['service_name']) ? $element['service_name'] : FALSE;
            $method = $element['method'];
            $data = isset($element['array_data'])  ? $element['array_data'] : array();

            /**
             * Variables para elementos del documento
             */
            $report_name = $element['title_document'];
            $header = $this->prepareHead($element['header']);
            $this->prepareHead($header);
            if ($class_name !== FALSE) {
                $filters_func = array();
                $order_params = array();
                if (isset($filters['filters'])) {
                    $filters_func = $filters['filters'];
                }
                if (isset($filters['order_params'])) {
                    $order_params = $filters['order_params'];
                }
                $all_result = $doctrine->getRepository($class_name)->$method($filters_func, $order_params);

                if (is_object($all_result)) {
                    if (get_class($all_result) == 'Doctrine\ORM\QueryBuilder') {
                        $result = $all_result->getQuery()->getResult();
                    }
                } else {
                    $result = $all_result;
                }
                $data = \Inagbe\AppBundle\Util\DoctrineHelper::toArray($result, 2);
            }elseif($service_name!==false){
                if(is_callable(array($this->container->get($service_name), $method))){
                    $filters_func = array();
                    $order_params = array();
                    if (isset($filters['filters'])) {
                        $filters_func = $filters['filters'];
                    }
                    if (isset($filters['order_params'])) {
                        $order_params = $filters['order_params'];
                    }
                    $data = $this->container->get($service_name)->$method($filters_func, $order_params);
                }
                else{
                    $data = array();
                }

            }
            $header_total = array();
            $header_total = array_merge($header_total, array_column((isset($header['first']) ? $header['column']: $header), 'column'));
            $fixedData = $this->prepareStructure($header_total, $data);

            $header['table_title'] = isset($element['table_title']) ? $element['table_title'] : '';

            $export_elemts[] = array('header' => $header, 'data' => $fixedData);
        }
//        pr($fixedData);
        $whitelist_user = array(
            'Inagbe\Admin\SecurityBundle\Entity\User' => array('username', 'email', 'fullName')
        );

        $user = \Inagbe\AppBundle\Util\DoctrineHelper::toArray($container->get('security.context')->getToken()->getUser(), 1, $whitelist_user);
        $user['create_date'] = date('d/m/Y h:i');
        $user['title_document'] = $report_name;

        $filters_header = $this->getFiltersHeader();

        return $data_array = array('filters_header' => $filters_header, 'data_elements' => $export_elemts, 'user' => $user);
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
    function getXslPath()
    {
        return 'generals_templates/list_basic_multiple.xsl';
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
        return array();
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function addData($data)
    {
        $this->data[] = $data;
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
