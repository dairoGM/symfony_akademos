<?php



namespace App\Export\Estructura;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListEstructuraToPdf extends \App\Services\ExportList {

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

        $data['title_document'] = 'Lista de estructuras';
        $data['table_title'] = 'Lista de estructuras';
        $data['header'] = array(
            array('column' => 'nombre', 'column_title' => 'Nombre', 'width' => '60%'),
            array('column' => 'categoria', 'column_title' => 'Categoría de estructura', 'width' => '20%'),
            array('column' => 'activo', 'column_title' => 'Estado', 'width' => '16%')
        );

        return $data;
    }

    function getFileName() {
        return date('d-m-Y') . ' - Lista de estructuras';
    }

    function getPropertyFile() {
        return null;
    }

}
