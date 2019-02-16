<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
// use App\Controller\NumeradorController;
// use PHPUnit\Framework\TestCase;

class NumeradorControllerTest extends WebTestCase
{
    public function tests_proyecto_numerador()
    {
        $cliente = static::createClient();
        $tractor = $cliente->request('GET','/proyecto/numerador');
        $this->assertEquals(200,  $cliente->getResponse()->getStatusCode());
    }

    public function tests_proyecto_numerador_form()
    {
        $cliente = static::createClient();
        $tractor = $cliente->request('GET','/proyecto/numerador');
        $form = $tractor->selectButton('Guardar')->form();
        $form['form[nombre]'] = 'test-unit';
        $this->assertTrue( $cliente->getResponse()->isSuccessful() ); 
    }

    public function tests_proyecto_numerador_data()
    {
        $cliente = static::createClient();
        $tractor = $cliente->request('GET','/proyecto/numerador/test-unit');
        $this->assertEquals( 200,  $cliente->getResponse()->getStatusCode());
    }

    public function tests_proyecto_numerador_borrar()
    {
        $cliente = static::createClient();
        $tractor = $cliente->request('GET','/proyecto/numerador/borrar');
        $this->assertTrue( $cliente->getResponse()->isSuccessful() );
    }
}