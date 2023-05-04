<?php



namespace App\Export\Pregrado;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListPlanEstudioToPdf extends \App\Services\ExportList {

    private $datos;
    
    public function __construct($data, $filters = NULL) {
        parent::__construct($filters);
        $this->datos = $data;
    }

    public function buildData() {
        $data = array();
        $data['filters'] = $this->filters;        
        $data['array_data'] = $this->datos;        
        

        $container = $this->getContainer();

        $data['title_document'] = 'Lista de planes de estudios';
        $data['table_title'] = 'Lista de planes de estudios';
        $data['header'] = array(
            array('column' => 'nombre_carrera', 'column_title' => 'Carrera', 'width' => '56%'),
            array('column' => 'nombre_curso_academico', 'column_title' => 'Curso académico', 'width' => '20%'),
            array('column' => 'annoAprobacion', 'column_title' => 'Año de aprobación', 'width' => '20%'),

        );

        return $data;
    }

    function getFileName() {
        return date('d-m-Y') . ' - Lista de planes de estudios';
    }

    function getPropertyFile() {
        return null;
    }

}
