<?php
namespace App\Services;

/**
 * Generar Caracteres Aleatorios
 */
class Token
{
    /**
     * Limpiar slug
     */
    public static function slug( $str, $delimitador = '_' ){

        $a_reemplazar = [
            'ś'=>'s', 'ą' => 'a', 'ć' => 'c',
            'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o',
            'ź' => 'z', 'ż' => 'z', 'Ś'=>'s', 'Ą' => 'a',
            'Ć' => 'c', 'Ę' => 'e', 'Ł' => 'l', 'Ń' => 'n',
            'Ó' => 'o', 'Ź' => 'z', 'Ż' => 'z'
        ];

        $str = strtr( $str, $a_reemplazar );

        $slug = strtolower(
            trim(
                preg_replace('/[\s-]+/', $delimitador,
                    preg_replace('/[^A-Za-z0-9-]+/', $delimitador,
                        preg_replace('/[&]/', 'y',
                            preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))
                        )
                    )
                ), $delimitador
            )
        );
        
        return $slug;
    }
    
    
    /**
     * Generar token con cantidad determinada de letras.
     */
    public static function generar($len=10, $car=false)
    {
        if($car=='L'):
            $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        elseif($car=='l'):
            $caracteres = "abcdefghijklmnopqrstuvwxyz";
        elseif($car=='N'):
            $caracteres = "123456789";
        else:
            $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        endif;

        $cadena = "";

        for( $i=0 ; $i<$len ; $i++ ):
            $cadena .= substr( $caracteres, rand(0,strlen($caracteres)),1);
        endfor;

        return $cadena;
    }
} # END 