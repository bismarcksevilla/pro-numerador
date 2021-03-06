<?php
namespace App\Services;

use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * generar PDF fácil 
 */
class Pdf
{
    /**
     * Parametros de configuración
     * @param string [$nombre_o_path] // Depende de la salida 
     * @param string [$html] // html generado por twig
     * @param string [$salida] // Tipo de salida del PDF: I (En Línea), D(Descargar), F(Guardar en path)
     * @param array [$margenes] // Al borde del PDF
     * @param string [$medida] // Medid de página soportado por la libreria Html2Pdf ('LETTER','OFFICE', etc)
     * @param string [$orientar] // Horizontal (P) o Vertical (L)
     * @return object
     */
    public function __construct( $nombre_o_path, $html, $display="I" ,$margenes=[0, 0, 0, 0], $medida='LETTER', $orientar='P'  )
    {
        $PDF  =  new  Html2Pdf( $orientar, $medida, 'es', true, 'UTF-8', $margenes );
        $PDF->pdf->SetAuthor('Bismarck Sevilla');
        $PDF->pdf->SetTitle( $this->LimpiarNombre($nombre_o_path) );
        $PDF->pdf->SetDisplayMode('real');
        $PDF->addFont('courier', 'normal', 'courier');
        $PDF->addFont('courierB', 'bold', 'courierB');
        $PDF->addFont('helvetica', 'normal', 'helvetica');
        $PDF->setDefaultFont('helvetica');
        $PDF->writeHTML( $html );
        return $PDF->output( $nombre_o_path , $display);
    } #END


    /**
     * Salida PDF
     */
    public static function Out($path){
        $response = new BinaryFileResponse( $path );
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            self::LimpiarNombre($path)
        );
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
    

    /**
     * Limpiar Nombre
     */
    private static function LimpiarNombre($nombre_o_path){

        $n = explode('/',$nombre_o_path);

        if(count($n)>1):

            return $n[count($n)-1];
        else:

            return $nombre_o_path;
        endif;
    }
} # :)