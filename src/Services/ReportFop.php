<?php

namespace App\Services;

/**
 * Interfaz para el tratamiento de exportar documentos PDF usando FOP
 * 
 * @author Jose Alejandro <josealeco05@gmail.com>
 * 
 */
interface ReportFop
{

    /**
     * Nombre del fichero a generar.
     *
     * @return string
     */
    public function getFileName();
    
    /**
     * Container.
     *
     * @return string
     */
    public function setContainer($container);
    
    /**
     * Container.
     *
     * @return string
     */
    public function getContainer();
    
    /**
     * Propiedades del documento.
     * 
     * array('orientation'=>'landscape','size'=>'carta')
     *
     * @return array
     */
    public function getPropertyFile();

    /**
     * Arreglo de objetos o de arreglos para generar el xml.
     *
     * @return stdClas []
     */
    public function getToXmlContent();

    /**
     * Ruta del xsl en el negocio.
     *
     * @return string
     */
    public function getXslPath();

    /**
     * Metodo que determina si se elimina o no la plantilla xsl usada
     * 
     * ************** CAUTION **************
     * 
     * Si retorna TRUE elimina el xsl debes ser cuidadoso con este retorno.
     * Solo debes cambiar este valor en caso de que uses una plantilla auto-generada en tiempo de ejecucion
     * 
     * ************** CAUTION **************
     *
     * @return boolean
     */
    public function getDeleteTemplate();

    /**
     * Metodo que determina si se realiza la descarga en el navegador o solo se almacena
     * 
     * ************** CAUTION **************
     * 
     * Si le dices FALSE no descarga el documento a travez del navegador solo lo genera en la ruta espesificada.
     *
     * ************** CAUTION **************
     * 
     * @return boolean
     */
    public function getDownload();

    /**
     * Debe espesificar el string definido en la configuracion del sistema que represente 
     * la ruta donde va a almacenar el documento a exportar.
     * 
     * @example <expedient> Representa la clave inagbe.documents_storage.<expedient>
     * @return string
     */
    public function getStoragePath();   
}
