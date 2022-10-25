<?php

namespace App\Service;

use App\Service\ReportFop;

abstract class ExportListHeader implements ReportFop
{

    use ReportFilterTrait;
    
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
        $name = isset($data['table_title']) ? $data['table_title'] : 'report';
        return date('dmYhi').'-'.$name;
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
        $class_name = $this->data['class_name'];
        $method = $this->data['method'];
        
        /**
         * Variables para elementos del documento
         */
        $report_name = $this->data['title_document'];
        $table_title = isset($this->data['table_title']) ? $this->data['table_title'] : '';
        $header = $this->data['header'];

        $filters_func = array();
        $order_params = array();

        if (isset($filters['filters']))
        {
            $filters_func = $filters['filters'];
        }
        if (isset($filters['order_params']))
        {
            $order_params = $filters['order_params'];
        }
        
        $all_result = $doctrine->getRepository($class_name)->$method($filters_func, $order_params);
        
        if (is_object($all_result))
        {
            if (get_class($all_result) == 'Doctrine\ORM\QueryBuilder')
            {
                $result = $all_result->getQuery()->getResult();
            }
        }
        else
        {
            $result = $all_result;
        }

        $data = \Inagbe\AppBundle\Util\DoctrineHelper::toArray($result, 2);
//        pr($data);
        $fixedData = $this->prepareStructure(array_column(end($header), 'column'), $data);

//        pr($fixedData);
        $whitelist_user = array(
            'Inagbe\Admin\SecurityBundle\Entity\User' => array('username', 'email', 'fullName')
        );
        
        $filters_header = $this->getFiltersHeader();

        $user = \Inagbe\AppBundle\Util\DoctrineHelper::toArray($container->get('security.context')->getToken()->getUser(), 1, $whitelist_user);
        $user['create_date'] = date('d/m/Y h:i');
        $user['title_document'] = $report_name;
        $user['table_title'] = $table_title;

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

        if (!$collection)
        {
            return $result;
        }
        foreach ($collection as $item)
        {
            foreach ($item as $key => $value)
            {
                if (in_array($key, $structure) === false)
                {
                    unset($item[$key]);
                    continue;
                }

                if (is_array($value) === true)
                {
                    if (isset($value['name']))
                    {
                        $item[$key] = $value['name'];
                        continue;
                    }
                }
            }

            $newItem = array();
            foreach ($structure as $s)
            {
                $newItem[$s] = $item[$s];
            }

            $result[] = $newItem;
        }
        return $result;
    }

    function getXslPath()
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
