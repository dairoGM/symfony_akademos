<?php

/**
 * Description of ExportListUsers
 *
 * @author josealeco05
 */

namespace App\Export\Admin;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExportListUsersToPdf extends \App\Services\ExportList {

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

        $data['title_document'] = 'Lista de usuarios';
        $data['table_title'] = 'Lista de usuarios';
        $data['header'] = array(
            array('column' => 'email', 'column_title' => $container->get('translator')->trans('Nombre'), 'width' => '60%'),
            array('column' => 'email', 'column_title' => $container->get('translator')->trans('Correo'), 'width' => '20%'),
            array('column' => 'activo', 'column_title' => 'Activo', 'width' => '16%')
        );

        return $data;
    }

    function getFileName() {
        return date('d-m-Y') . ' - Lista de usuarios';
    }

    function getPropertyFile() {
        return null;
    }

}
