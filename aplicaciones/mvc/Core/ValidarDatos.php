<?php

/**
 * * Habilitar en  php.ini la extension=php_intl.dll para que funcione zend-i18n
 * 
 * http://zendframework.github.io/zend-i18n/validators/
 * https://docs.zendframework.com/zend-validator/
 * https://olegkrivtsov.github.io/using-zend-framework-3-book/html/es/Revisar_los_datos_de_entrada_con_validadores/Ejemplos_de_uso_de_validadores.html
 */

namespace Agrodb\Core;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Zend\Validator\AbstractValidator;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\Db\NoRecordExists;

/**
 * Clase para validar datos antes de ser guardados en la base de datos
 *
 * @author DATASTAR
 */
class ValidarDatos extends AbstractValidator
{

    function __construct()
    {
        
    }

    /**
     * Valida que la cadena no esta vacia, que no sea obligatorio, longitud, quita código HTML o PHP
     * y que la que no contenga caracteres especiales
     * @param type $cadena -Cadena de caracteres alfanumérico
     * @param type $tabla -Nombre de la tabla de la base de datos
     * @param type $campo Nombre del campo (etiqueta)
     * @param type $requerido   True
     * @param type $longitud -Tamaño del campo
     * @return type -cadena correcta
     * @throws \Exception
     */
    public static function validarAlfa($cadena, $tabla = null, $campo = null, $requerido = false, $longitud = 0)
    {
        $_cadena = null;

        //Verifcamos si el campo esta vacio y es obligatorio
        if (!self::vacio($cadena, 'S'))
        {
            if ($requerido)
            {
                Mensajes::fallo(Constantes::CAMPO_OBLIGATORIO . $campo);
                throw new \Exception(Constantes::CAMPO_OBLIGATORIO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            } else
            {
                $_cadena = Constantes::CAMPO_VACIO;
            }
        } else
        {
            $_cadena = $cadena;
            //Verificamos que la longitud de la cadena corresponda con el campo de la base de datos
            if ($longitud > 0)
            {
                $objLongitud = new StringLength();
                $objLongitud->setMin(1);
                $objLongitud->setMax($longitud);
                if (!$objLongitud->isValid($_cadena))
                {
                    Mensajes::fallo(Constantes::CAMPO_LONGITUD_INCORRECTO . $longitud . ' ' . $campo . " = " . $cadena);
                    throw new \Exception(Constantes::CAMPO_LONGITUD_INCORRECTO . $longitud . ' ' . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
                } else
                {
                    //Quitamos el código HTML o PHP que pueda tener una cadena
                    $_cadena = strip_tags($_cadena);
                    //Validamos que no tenga caracteres especiales
                    $alnum = new Alnum(['allowWhiteSpace' => true]);
                    if (!$alnum->isValid($_cadena))
                    {
                        Mensajes::fallo(Constantes::ERROR_CADENA_CARACTERES . $campo . " = " . $cadena);
                        throw new \Exception(Constantes::ERROR_CADENA_CARACTERES . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
                    }
                }
            }
        }

        return (String) $_cadena;
    }

    /**
     *  Valida cadenas con caracteres especiales o html
     * @param type $cadena
     * @param type $tabla
     * @param type $campo
     * @param type $requerido
     * @param type $longitud
     * @return type
     * @throws \Exception
     */
    public static function validarAlfaEsp($cadena, $tabla = null, $campo = null, $requerido = false, $longitud = 0)
    {
        $_cadena = null;

        //Verifcamos si el campo esta vacio y es obligatorio
        if (!self::vacio($cadena, 'S'))
        {
            if ($requerido)
            {
                Mensajes::fallo(Constantes::CAMPO_OBLIGATORIO . $campo);
                throw new \Exception(Constantes::CAMPO_OBLIGATORIO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            } else
            {
                $_cadena = Constantes::CAMPO_VACIO;
            }
        } else
        {
            $_cadena = $cadena;
            //Verificamos que la longitud de la cadena corresponda con el campo de la base de datos
            if ($longitud > 0)
            {
                $objLongitud = new StringLength();
                $objLongitud->setMin(1);
                $objLongitud->setMax($longitud);
                if (!$objLongitud->isValid($_cadena))
                {
                    Mensajes::fallo(Constantes::CAMPO_LONGITUD_INCORRECTO . $longitud . ' ' . $campo . " = " . $cadena);
                    throw new \Exception(Constantes::CAMPO_LONGITUD_INCORRECTO . $longitud . ' ' . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
                }
            }
        }

        return (String) $_cadena;
    }

    /**
     * Valida una fecha
     * @param type $cadena
     * @param type $tabla -Nombre de la tabla de la base de datos
     * @param type $campo Nombre del campo (etiqueta)
     * @param type $requerido True
     * @param type $longitud -Tamaño del campo
     * @return type -cadena correcta
     * @throws \Exception
     */
    public static function validarFecha($cadena, $tabla = null, $campo = null, $requerido = false, $longitud = 0)
    {
        $_cadena = null;

        //Verifcamos si el campo esta vacio y es obligatorio
        if (!self::vacio($cadena))
        {
            if ($requerido)
            {
                Mensajes::fallo(Constantes::CAMPO_OBLIGATORIO . $campo);
                throw new \Exception(Constantes::CAMPO_OBLIGATORIO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            } else
            {
                $_cadena = Constantes::FECHA_VACIO;
            }
        } else
        {
            $_cadena = strip_tags($cadena);
            $fecha = new \Zend\Validator\Date(DATE_FORMAT);
            if (!$fecha->isValid($_cadena))
            {
                Mensajes::fallo(Constantes::ERROR_CADENA_FECHA . $campo . " = " . $cadena);
                throw new \Exception(Constantes::ERROR_CADENA_FECHA . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            }
        }
        return $_cadena;
    }

    /**
     * Valida un valor entero
     * @param type $cadena Número entero
     * @param type $tabla -Nombre de la tabla de la base de datos
     * @param type $requerido True
     * @param type $campo Nombre del campo (etiqueta)
     * @param type $longitud -Tamaño del campo
     * @return type -cadena correcta
     */
    public static function validarEntero($cadena, $tabla = null, $campo = null, $requerido = false, $longitud = 0)
    {
        $_cadena = null;

        //Verifcamos si el campo esta vacio y es obligatorio
        if (!self::vacio($cadena, 'F'))
        {
            if ($requerido)
            {
                Mensajes::fallo(Constantes::CAMPO_OBLIGATORIO . $campo);
                throw new \Exception(Constantes::CAMPO_OBLIGATORIO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            } else
            {
                $_cadena = Constantes::ENTERO_VACIO;
            }
        } else
        {
            $_cadena = strip_tags($cadena);
            $entero = new \Zend\I18n\Validator\IsInt();
            if (!$entero->isValid($_cadena))
            {
                Mensajes::fallo(Constantes::ERROR_CADENA_ENTERO . $campo . " = " . $cadena);
                throw new \Exception(Constantes::ERROR_CADENA_ENTERO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            }
        }
        return (integer) $_cadena;
    }

    /**
     * 
     * @param type $cadena Número decicmal
     * @param type $tabla -Nombre de la tabla de la base de datos
     * @param type $requerido True
     * @param type $campo Nombre del campo (etiqueta)
     * @param type $longitud -Tamaño del campo
     * @return type -cadena correcta
     * @throws \Exception Retorna el error
     */
    public static function validarDecimal($cadena, $tabla = null, $campo = null, $requerido = false, $longitud = 0)
    {
        $_cadena = null;

        //Verifcamos si el campo esta vacio y es obligatorio
        if (!self::vacio($cadena, 'F'))
        {
            if ($requerido)
            {
                Mensajes::fallo(Constantes::CAMPO_OBLIGATORIO . $campo);
                throw new \Exception(Constantes::CAMPO_OBLIGATORIO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            } else
            {
                $_cadena = Constantes::DECIMAL_VACIO;
            }
        } else
        {
            $_cadena = strip_tags($cadena);
            $decimal = new \Zend\I18n\Validator\IsFloat(array('locale' => 'en'));
            if (!$decimal->isValid($_cadena))
            {
                Mensajes::fallo(Constantes::ERROR_CADENA_DECIMAL . $campo . " = " . $cadena);
                throw new \Exception(Constantes::ERROR_CADENA_DECIMAL . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            }
        }
        return $_cadena;
    }

    /**
     * Valida el formato de un correo electrónco
     * @param type $cadena
     * @param type $tabla -Nombre de la tabla de la base de datos
     * @param type $campo  Nombre del campo (etiqueta)
     * @param type $requerido True
     * @param type $longitud  -Tamaño del campo
     * @return type -cadena correcta
     * @throws \Exception
     */
    public static function validarEmail($cadena, $tabla = null, $campo = null, $requerido = false, $longitud = 0)
    {
        $_cadena = null;

        //Verifcamos si el campo esta vacio y es obligatorio
        if (!self::vacio($cadena))
        {
            if ($requerido)
            {
                Mensajes::fallo(Constantes::CAMPO_OBLIGATORIO . $campo);
                throw new \Exception(Constantes::CAMPO_OBLIGATORIO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            } else
            {
                $_cadena = Constantes::CAMPO_VACIO;
            }
        } else
        {
            $_cadena = strip_tags($cadena);
            $email = new \Zend\Validator\EmailAddress();
            if (!$email->isValid($_cadena))
            {
                Mensajes::fallo(Constantes::ERROR_CADENA_EMAIL . $campo . " = " . $cadena);
                throw new \Exception(Constantes::ERROR_CADENA_EMAIL . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            }
        }

        return (String) $_cadena;
    }

    /**
     * Verifica si una variable esta vacia
     * https://docs.zendframework.com/zend-validator/validators/not-empty/#specifying-empty-behavior
     * @param type string | numeric  | decimal | all
     * @param type true | false
     */
    public static function vacio($value, $type = 'all')
    {
        $validator = null;
        switch ($type)
        {
            case 'S': //cadena de caracteres
                $validator = new NotEmpty(array(NotEmpty::STRING, NotEmpty::NULL));
                break;
            case 'I': //Número entero
                $validator = new NotEmpty(array(NotEmpty::INTEGER, NotEmpty::NULL));
                break;
            case 'F': //Número decimal
                $validator = new NotEmpty(array(NotEmpty::FLOAT, NotEmpty::ZERO, NotEmpty::NULL));
                break;
            case 'B': //Número decimal
                $validator = new NotEmpty(array(NotEmpty::BOOLEAN, NotEmpty::NULL));
                break;
            default :
                $validator = new NotEmpty(NotEmpty::ALL);
                break;
        }
        return $validator->isValid($value);
    }

    public function isValid($value)
    {
        
    }

    public static function validarBoolean($cadena, $tabla = null, $campo = null, $requerido = false, $longitud = 0)
    {

        $_cadena = null;

        //Verifcamos si el campo esta vacio y es obligatorio
        if (!self::vacio($cadena, 'B'))
        {
            if ($requerido)
            {
                Mensajes::fallo(Constantes::CAMPO_OBLIGATORIO . $campo);
                throw new \Exception(Constantes::CAMPO_OBLIGATORIO . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            } else
            {
                $_cadena = Constantes::CAMPO_VACIO;
            }
        } else
        {
            $_cadena = strip_tags($cadena);
            $entero = new \Zend\I18n\Validator\IsInt();
            if (!$entero->isValid($_cadena))
            {
                Mensajes::fallo(Constantes::ERROR_CADENA_BOOLEAN . $campo . " = " . $cadena);
                throw new \Exception(Constantes::ERROR_CADENA_BOOLEAN . ' Tabla: ' . $tabla . ' Campo: ' . $campo . ' Dato : ' . $cadena);
            }
        }

        return (boolean) $_cadena;
    }

    /**
     * Valida los campos creados de forma dinámica
     * @param type $campo
     * @param type $cadena
     * @param type $tabla
     * @param type $longitud
     * @return type
     */
    public static function camposDinamicos($campo, $cadena, $tabla = null, $longitud = 0)
    {
        $_cadena = "";
        if (is_array($cadena))
        {
            $_cadena = $cadena[0];
        } else
        {
            $_cadena = $cadena;
        }
        //si el nombre del campo tiene estos dos caracteres  O_ el campo es obligaorio
        $requerido = !strrpos($campo, "O_");

        $text = strrpos($campo, "texto");
        if ($text)
        {
            return self::validarAlfa($_cadena, $tabla, $campo, $requerido, $longitud);
        }

        $list = strrpos($campo, "lista");
        if ($list)
        {
            return self::validarEntero($_cadena, $tabla, $campo, $requerido, $longitud);
        }
        $date = strrpos($campo, "fecha");
        if ($date)
        {
            return self::validarFecha($_cadena, $tabla, $campo, $requerido, $longitud);
        }

        return $_cadena;
    }

    /**
     * Valida si el campo debe 
     * @param type $valor
     */
    public static function campoVacio($valor)
    {
        if ($valor == Constantes::CAMPO_VACIO)
        {
            return '';
        }else{
            return $valor;
        }
    }

}
