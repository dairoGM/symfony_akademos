<?php


namespace App\Export\Pregrado;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListSolicitudProgramaAcademicoToPdf extends \App\Services\ExportList
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

        $data['title_document'] = 'Lista de solicitudes de programas académicos';
        $data['table_title'] = 'Lista de solicitudes de programas académicos';
        $data['header'] = array(
            array('column' => 'nombre', 'column_title' => 'Programa académico', 'width' => '31%'),
            array('column' => 'tipoProgramaAcademico', 'column_title' => 'Tipo de programa', 'width' => '25%'),
            array('column' => 'organismoDemandante', 'column_title' => 'Organismo demandante', 'width' => '25%'),
            array('column' => 'estadoProgramaAcademico', 'column_title' => 'Estado', 'width' => '15%'),
        );

        return $data;
    }

    function getFileName()
    {
        return date('d-m-Y') . ' - Lista de solicitudes de programas académicos';
    }

    function getPropertyFile()
    {
        return null;
    }

}
