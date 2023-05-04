<?php



namespace App\Export\Institucion;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListInstitucioToPdf extends \App\Services\ExportList {

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

        $data['title_document'] = 'Lista de IES';
        $data['table_title'] = 'Lista de IES';
        $data['header'] = array(
            array('column' => 'siglas', 'column_title' => 'Siglas', 'width' => '6%'),
            array('column' => 'nombre', 'column_title' => 'Nombre', 'width' => '60%'),
            array('column' => 'rector', 'column_title' => 'Rector', 'width' => '30%'),

        );

        return $data;
    }

    function getFileName() {
        return date('d-m-Y') . ' - Lista de IES';
    }

    function getPropertyFile() {
        return null;
    }

}
