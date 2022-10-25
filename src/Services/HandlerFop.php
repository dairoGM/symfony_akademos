<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Services\ReportFop;

/**
 * Clase para la generacion de documentos PDF usando FOP
 *
 * @Service("hand.fop")
 *
 * @author Jose Alejandro <josealeco05@gmail.com>
 *
 */
class HandlerFop
{

    private $container;
    private $XSLPATH;
    private $XMLTMPPATH;
    private $PDFTMPPATH;
    private $FOPPATH;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->XSLPATH = $container->getParameter('xsl_path');
        $this->XMLTMPPATH = $container->getParameter('xml_temp');
        $this->PDFTMPPATH = $container->getParameter('pdf_temp');
        $this->FOPPATH = $container->getParameter('fop_exc');
    }

    public function arrayToXml($object, $nivel = 0, $root = 'root', $element = 'element', $use_head = false)
    {
        set_time_limit('180');
        ini_set('memory_limit', '512M');
        $xml = "";
        if ($nivel == 0)
            $xml = ($use_head) ? "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" . $xml . "<{$root}>\n" : $xml . "<{$root}>\n";

        if (!is_array($object) && !is_object($object)) {
            $xml = str_replace(array('>', '<', '&'), array('&gt;', '&lt;', '&amp;'), $object);
        }

        if (is_array($object) || is_object($object)) {
            $attr = $object;
            if (is_object($object))
                $attr = get_object_vars($object);

            foreach ($attr as $tag => $value) {
                $tab = str_repeat("  ", $nivel + 1);
                if (is_int($tag) || is_numeric($tag[0]))
                    $tag = $element;
                $xml = $xml . $tab . "<" . $tag . ">";

                if (is_array($value) || is_object($value)) {
                    if ($value != NULL)
                        $xml = $xml . "\n";
                } else {
                    $tab = "";
                }

                if ($value == NULL)
                    $tab = "";

                $xml = $xml . $this->arrayToXml($value, $nivel + 1, $root, $element, $use_head);
                $xml = $xml . $tab . "</" . $tag . ">\n";
            }
        }

        if ($nivel == 0)
            $xml = $xml . "</{$root}>";

        return $xml;
    }

    public function saveXml($xml, $file_dir)
    {
        $x = new DOMDocument;
        $x->formatOutput = true;
        $x->loadXML($xml);
        $x->save($file_dir);
    }

    function exportToPdf(ReportFop $param)
    {
        $param->setContainer($this->container);
//        pr($param->getToXmlContent());
        $xml_content = $this->arrayToXml($param->getToXmlContent());

        return $this->export($param->getFileName(), 'pdf', $xml_content, $param->getXslPath(), $param->getDeleteTemplate(), $param->getDownload(), $param->getStoragePath());
    }

    function exportToExcel($html, $filename)
    {
        set_time_limit('180');
        ini_set('memory_limit', '512M');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $html;
        die;
    }

    private function export($file_name, $outpu_format, $xml_content, $xsl, $delete_template = FALSE, $download = TRUE, $storage = '')
    {
        set_time_limit('180');
        ini_set('memory_limit', '512M');
        $file_name = $this->escapeFileName($file_name);

        $tmp_time = ($download) ? time() : '';

        $xsl_path = $this->XSLPATH . $xsl;

        $export_path = $this->PDFTMPPATH . $file_name . $tmp_time . '.' . $outpu_format;


        if (!empty($storage)) {
            //is_dir($this->PDFTMPPATH);
            $route = $this->PDFTMPPATH . $storage;
            @ mkdir($route);

            $export_path = $route . '/' . $file_name . $tmp_time . '.' . $outpu_format;
        }


        if ($outpu_format == 'fo') {
            $outpu_format_comand = 'foout';
        } else {
            $outpu_format_comand = $outpu_format;
        }

        $xml_path = $this->XMLTMPPATH . $file_name . $tmp_time . '.xml';
        //guardar el xml con los datos a utilizar para crear el documento
        if (!is_a($xml_content, 'SimpleXMLElement')) {
            $xml = new \SimpleXMLElement($xml_content);
        } else {
            $xml = $xml_content;
        }
        $xml->asXML($xml_path);
        //generar el documento en el formato especificado

        $execute = $this->FOPPATH . " -xml " . $xml_path . " -xsl " . $xsl_path . " -" . $outpu_format_comand . ' ' . $export_path;

//        echo $execute;die;
        exec($execute, $salida, $retorno);

        if (!file_exists($export_path)) {
            throw new \Exception('SYS006');
        }

        //obtener el contenido del documento generado
        $export_content = file_get_contents($export_path);

        //eliminar los ficheros temporales
        unlink($xml_path);
        //obligar la descarga del documento
        if ($download) {
            unlink($export_path);

            $response = new Response();
            $response->headers->set('Content-type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . $file_name . ".$outpu_format" . '"');
            $response->setContent($export_content);

            if ($delete_template) {
                unlink($xsl_path);
            }
            return $response;
        } else {
            return $export_path;
        }
    }

    private function escapeFileName($file_name)
    {
        $string = strtolower($file_name);
        //Strip any unwanted characters
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);

        return $string;
    }

    public function saveSVG($post_xml)
    {
        $svg = str_replace('undefined', '1', $post_xml['svg']);
        $name_saved = uniqid() . ".svg";
        $this->container->get('session')->set($post_xml['name'], $name_saved);
        $file = fopen($this->XMLTMPPATH . "temp/" . $name_saved, "w");
        fwrite($file, $svg);
        fclose($file);

        return $svg;
    }

}
