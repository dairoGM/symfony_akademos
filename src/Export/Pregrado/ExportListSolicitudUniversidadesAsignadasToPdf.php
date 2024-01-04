<?php


namespace App\Export\Pregrado;

use App\Services\ExportList;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListSolicitudUniversidadesAsignadasToPdf extends ExportList
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
        $asignadaA = $data['filters']['asignada_a'] ?? null;


        $container = $this->getContainer();

        $data['title_document'] = 'Lista de universidades asignadas';
        $data['table_title'] = "Programa académico: $asignadaA";
        $data['header'] = array(
            array('column' => 'nombre', 'column_title' => 'Nombre', 'width' => '60%'),
            array('column' => 'centro_rector', 'column_title' => 'Centro rector', 'width' => '15%'),
            array('column' => 'categoria_acreditacion', 'column_title' => 'Categoría de acreditación', 'width' => '19%'),

        );

        return $data;
    }

    function getFileName()
    {
        return date('d-m-Y') . ' - Lista de universidades asignadas';
    }

    function getPropertyFile()
    {
        return null;
    }

}
