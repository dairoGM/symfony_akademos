<?php


namespace App\Export\Personal;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListResponsableToPdf extends \App\Services\ExportList
{

    private $datos;

    public function __construct($data, $filters = NULL)
    {
        parent::__construct($filters);
        $this->datos = $data;
    }

    public function buildData()
    {
        $data = array();
        $data['filters'] = $this->filters;
        $data['array_data'] = $this->datos;


        $container = $this->getContainer();

        $data['title_document'] = 'Lista de responsables';
        $data['table_title'] = 'Lista de responsables';
        $data['header'] = array(
            array('column' => 'nombreCompleto', 'column_title' => 'Nombre y apellidos', 'width' => '60%'),
            array('column' => 'carnetIdentidad', 'column_title' => 'Carné de identidad', 'width' => '36%'),
        );

        return $data;
    }

    function getFileName()
    {
        return date('d-m-Y') . ' - Lista de responsables';
    }

    function getPropertyFile()
    {
        return null;
    }

}
