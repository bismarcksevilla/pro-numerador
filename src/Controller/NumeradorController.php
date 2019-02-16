<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
 
use App\Services\Pdf;
use App\Services\Token;

/**
 * @Route("/proyecto/numerador")
 */
class NumeradorController extends AbstractController
{
    private $path       = "proyecto/numerador/";
    private $maxReg     = 250;

    /**
     * @Route("/{slug}", name="proyecto_numerador",
     *      requirements={
     *          "slug"="[a-zA-Z0-9_-]{0,80}"
     *      }
     * )
     */
    public function generadorAction(Request $request, $slug=false)
    {
        if ( !$slug || !$data=$this->getJson($slug) ){
            
            $data = new \stdClass();
            $data->nombre = $slug?$slug:(new \DateTime('now'))->format('YmdH').Token::generar(3,"N");
            $data->campos= 2;
            $data->medidaFuente= 15;
            $data->paginas= 1;
            $data->prefijo="No. ";
            $data->sufijo=null;
            $data->fuente=null;
            $data->digitos=4;
            $data->medidaPagina=null;
            $data->orientacion="P";
            $data->color= "red";
            $data->numero_i_1 = 1;
        }

        $form = $this->createFormBuilder()->getForm();
        
        $form->add('nombre', TextType::class, [
            'required' => true,
            'data' => $data->nombre,
            'attr' => [ 
                'maxlength' => 15,
                'class' => 'form-control'
            ], 
        ]);
            
        $form->add('campos', ChoiceType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'choices'=>[
                '1 Campo' => 1, 
                '2 Campos'=> 2, 
                '3 Campos'=> 3, 
                '4 Campos'=> 4, 
                '5 Campos'=> 5, 
                '6 Campos'=> 6, 
                '7 Campos'=> 7, 
                '8 Campos'=> 8, 
            ],
            'data' =>$data->campos,
        ]);

        $form->add('paginas', IntegerType::class, [
            'required' => true,
            'data' =>$data->paginas,
            'attr' => [
                'scale' => 1,
                'max' => $this->maxReg,
                'min' => 1,
                'class' => 'form-control'
            ], 
        ]);

        $form->add('color', ChoiceType::class, [
            'data' =>$data->color,
            'attr' => [
                'class' => 'form-control'
                
            ],
            'choices'=>[
                'Negro'     => 'black', 
                'Rojo'      => 'red', 
                'Azul'      => 'blue', 
                'Verde'     => '#00913f', 
                'Amarillo'  => '#ffff00', 
                'Cian'      => '#00ffff', 
                'Magenta'   => '#ff0090', 
            ], 
        ]);

        $form->add('fuente', ChoiceType::class, [
            'choices'=>[
                'Default' => '',
                'Courier' => 'courier',
            ],
            'required' => false,
            'data' =>$data->fuente,
            'attr' => [ 
                'class'=> 'form-control'
            ], 
        ]);

        $form->add('medidaFuente', ChoiceType::class, [
            'choices'=>[
                '7 pxs'  => 7,
                '8 pxs'  => 8,
                '9 pxs'  => 9,
                '10 pxs' =>10,
                '11 pxs' =>11,
                '12 pxs' =>12,
                '13 pxs' =>13,
                '14 pxs' =>14,
                '15 pxs' =>15,
                '16 pxs' =>16,
                '17 pxs' =>17,
                '18 pxs' =>18,
                '19 pxs' =>19,
                '20 pxs' =>20,
                '21 pxs' =>21,
                '22 pxs' =>22,
                '23 pxs' =>23,
                '24 pxs' =>24,
                '25 pxs' =>25,
                '26 pxs' =>26,
                '27 pxs' =>27,
                '28 pxs' =>28,
                '29 pxs' =>29,
                '30 pxs' =>30,
            ],
            'data' =>$data->medidaFuente,
            'attr' => [ 
                'class'=> 'form-control'
            ], 
        ]);

        $form->add('medidaPagina', ChoiceType::class, [
            'choices'=>[
                'Carta' =>'LETTER',
                '1/16' =>'STATEMENT',
                'LEGAL' =>'OFICE',
            ],
            'data' =>$data->medidaPagina,
            'attr' => [ 
                'class'=> 'form-control'
            ], 
        ]);

        $form->add('orientacion', ChoiceType::class, [
            'choices'=>[
                'Vertical ↕' =>'P',
                'Horizontal' =>'L',
            ],
            'data' =>$data->orientacion,
            'attr' => [ 
                'class'=> 'form-control'
            ], 
        ]);

        $form->add('digitos', ChoiceType::class, [
            'choices'=>[
                '1: X' =>"%01d",
                '2: 0X' =>"%02d",
                '3: 00X' =>"%03d",
                '4: 000X' =>"%04d",
                '5: 0000X' =>"%05d",
                '6: 00000X' =>"%06d",
                '7: 000000X' =>"%07d",
                '8: 0000000X' =>"%08d",
            ],
            'data' =>$data->digitos,
            'attr' => [ 
                'class'=> 'form-control'
            ], 
        ]);

        $form->add('prefijo', TextType::class, [
            'data' =>$data->prefijo,
            'required' => false,
            'attr' => [ 
                'maxlength' => 5,
                'class'=> 'form-control'
            ], 
        ]);

        $form->add('sufijo', TextType::class, [
            'data' =>$data->sufijo,
            'required' => false,
            'attr' => [ 
                'maxlength' => 5,
                'class'=> 'form-control'
            ], 
        ]);

        $form->add('guardar', SubmitType::class, [
            'attr' =>[
                'class'=> 'btn btn-success form-control'
            ],
        ]);
        
        $campos = $data->campos;
        
        for($i=1; $i<=$campos; $i++):
            
            $paginas = $data->paginas?$data->paginas:50;

            if ($i== 1) {
                $numeroInicio = $data->numero_i_1?$data->numero_i_1:1;
                $numero = $numeroInicio;                
            }else{
                $numero = (($paginas * ($i-1)) + $numeroInicio);
            }

            $form->add('etiqueta_'.$i, TextType::class, [
                'data'  => @$data->{"etiqueta_".$i}?$data->{"etiqueta_".$i}:"E".$i,
                'required' => false,
                'attr' => [ 
                    'maxlength' => 4,
                    'class' => 'form-control'
                ], 
            ]);

            $form->add('numero_i_'.$i, IntegerType::class, [
                'data'  => @$data->{"numero_i_".$i}?$data->{"numero_i_".$i}:$numero,
                'required' => false,
                'attr' => [ 
                    'class' => 'form-control'
                ],
            ]);
 
            $form->add('x_'.$i, IntegerType::class, [
                'data' =>@$data->{"x_".$i}?$data->{"x_".$i}:0,
                'required' => false,
                'attr' => [
                    'scale' => 2,
                    'max' => 355.60,
                    'min' => 0,
                    'step' =>0.5,
                    'class' => 'form-control'
                ],
            ]);

            $form->add('y_'.$i, IntegerType::class, [
                'data' =>@$data->{"y_".$i}?$data->{"y_".$i}:0,
                'required' => false,
                'attr' => [
                    'scale' => 2,
                    'max' => 355.60,
                    'min' => 0,
                    'step' => 0.5,
                    'class' => 'form-control'
                ],
            ]);
        endfor; 

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ){ 
            try{
                $slug = Token::slug($form["nombre"]->getData());

                $fs = new Filesystem;
                $fs->dumpFile( $this->getPath('json',$slug), json_encode($form->getData()));
                $fs->remove(   $this->getPath('pdf', $slug));
                return $this->redirectToRoute('proyecto_numerador',['slug'=>$slug]);
            }catch(IOException $e) {
                return $this->redirectToRoute('proyecto_numerador',['slug'=>$slug]);
            }
        }

        return $this->render('numerador/configurar.html.twig',[
            "form" => $form->createView(),
            "lista" => $this->getLista(),
            "campos" => $campos,
            "slug" => $slug,
        ]);
    }


    /**
     * @Route("/pdf/{slug}.pdf", name="proyecto_numerador_pdf",
     *      requirements={
     *          "slug"="[a-zA-Z0-9_-]{0,30}"
     *      }
     * )
     */
    public function pdfAction(Request $request, $slug=false)
    { 
        $data = $this->getJson($slug); 

        if( isset($data->paginas) && $data->paginas>0){

            $path = $this->getPath('pdf', $slug);
            
            if(!file_exists( $path )){
                try {
                    $html_twig = $this->renderView('numerador/generar.pdf.twig',["data" => $data]);
                } catch (\Throwable $th) {
                    return $this->redirectToRoute('proyecto_numerador');
                }
                new Pdf( $path, $html_twig, "F", [0,0,0,0], $data->medidaPagina,  $data->orientacion); //I, F
            }

            // return $this->redirect($slug.".pdf");
            return Pdf::out($path);        

        }else{
            return $this->redirectToRoute('proyecto_numerador');
        }
    }


    /**
     * @Route("/borrar/{slug}", name="proyecto_numerador_borrar",
     *      requirements={
     *          "slug"="[a-zA-Z0-9_-]{0,30}"
     *      }
     * )
     */
    public function borrarAction($slug=false)
    {
        $fs = new Filesystem;
        $fs->remove( $this->getPath('json', $slug));
        $fs->remove( $this->getPath('pdf', $slug));
        return $this->redirectToRoute('proyecto_numerador');
    }


    # # # # # # # # #
    # P R I V A T E #
    # # # # # # # # #


    /**
     * Recupera datos de archivo
     * 
     * return array
     */
    private function getJson($slug)
    {
        $path = $this->getPath('json', $slug);

        if(file_exists($path)){
            
            return json_decode(file_get_contents($path));
        }else{

            return [];
        }
    }


    /**
     * Recupera lista de archivos
     * 
     * return string | array
     */
    public function getLista()
    {
        $finder = new Finder();
        return $finder->files()->in( $this->getPath('json'))->sortByModifiedTime();        
    } #


    /**
     * Path al archivo de configuración
     * 
     * return string
     */
    public function getPath($dir=false, $slug=false)
    {
        if($dir=='json'){

            $path =  "../public/".$this->path."json/";
        }else if($dir=='pdf'){ 

            $path = __DIR__ ."/../../public/".$this->path."pdf/";
        }else{

            $path =  $this->path;
        }
        
        if(!file_exists($path)){
        // if(!is_dir(dirname($path))){
            $fs = new Filesystem;
            $fs->mkdir($path);
        }
        if($slug) return $path.$slug.".".$dir;
        else return $path;
    } #
}