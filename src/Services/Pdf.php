<?php
namespace App\Services;

use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
* Identificar Fechas
*/
class Pdf
{

    /**
     * @param int $d
     * @return string
     */
    public function __construct( $nombre_o_path=false, $twig=false, $display="I" ,$margin=[0, 0, 0, 0], $size='LETTER', $h='P'  )
    {
        $PDF  =  new  Html2Pdf( $h, $size, 'es', true, 'UTF-8', $margin );
        $PDF->pdf->SetAuthor('Bismarck Sevilla');
        $PDF->pdf->SetTitle( $this->LimpiarNombre($nombre_o_path) );
        $PDF->pdf->SetDisplayMode('real');
        $PDF->addFont('courier', 'normal', 'courier');
        $PDF->addFont('courierB', 'bold', 'courierB');
        $PDF->addFont('helvetica', 'normal', 'helvetica');
        $PDF->setDefaultFont('helvetica');
        $PDF->writeHTML( $twig );

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
    private function LimpiarNombre($nombre_o_path){

        $n = explode('/',$nombre_o_path);

        if(count($n)>1):

            return $n[count($n)-1];
        else:

            return $nombre_o_path;
        endif;
    }

} # :)