<?php


namespace App\Export\Traza;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListTrazaToPdf extends \App\Services\ExportList
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

        $data['title_document'] = 'Lista de trazas';
        $data['table_title'] = 'Lista de trazas';
        $data['header'] = array(
            array('column' => 'creado', 'column_title' => 'Fecha', 'width' => '7%'),
            array('column' => 'nombreCompleto', 'column_title' => 'Usuario', 'width' => '20%'),
            array('column' => 'tipoTraza', 'column_title' => 'Tipo de traza', 'width' => '10%'),
            array('column' => 'accion', 'column_title' => 'Acción', 'width' => '11%'),
            array('column' => 'objeto', 'column_title' => 'Objeto', 'width' => '11%'),
            array('column' => 'ip', 'column_title' => 'IP', 'width' => '11%'),
            array('column' => 'navegador', 'column_title' => 'Navegador', 'width' => '11%'),
            array('column' => 'sistemaOperativo', 'column_title' => 'Sistema operativo', 'width' => '15%'),
        );

        return $data;
    }

    function getFileName()
    {
        return date('d-m-Y') . ' - Lista de trazas';
    }

    function getPropertyFile()
    {
        return null;
    }

}
