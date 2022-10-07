<?php

/**
 * Lógica del negocio de TokenLogicaNegocio
 *
 *
 * @author AGROCALIDAD
 * @uses   TokenLogicaNegocio
 * @package AplicacionMovilInternoss
 * @subpackage Modelos
 */

namespace Agrodb\Token\Modelos;

use Agrodb\Token\JwtVerify;
use Agrodb\Token\JwtGenerate;

class TokenLogicaNegocio
{

    public function __construct()
    {
        require ROOT . '../vendor/autoload.php';
    }

    /**
     * Ejecuta una consulta(SQL) personalizada.
     *
     * @return array|ResultSet	
     */
    public function auth($key)
    {
        $jwtGenerate = new JwtGenerate();
        $token = $jwtGenerate->generarToken($key);
        $statusCode = 200;
        return array(
            "estado" => "exito", "mensaje" => "Credenciales válidas",
            "token" => $token["token"], "expiraEn" => $token["expiraEn"], "estatusCode" => $statusCode
        );
    }

    /**
     * Verifica un token válido para que el servicio devuelva datos
     * 
     * @param mixed $resultado El json o array que devuelve el servicio
     * @param String $tipo Tipo de dato que tiene que devolver la funcion "json" o "array"
     */
    public function devolverDatosQueryToken($resultado, $tipo,$rutaPublicKey)
    {

        $headers = apache_request_headers();
        $headers = array_change_key_case($headers, CASE_LOWER);

        if (isset($headers['authorization'])) {
            $token = str_replace("Bearer ", "", $headers['authorization']);
            $jwtVerify = new JwtVerify($token,$rutaPublicKey);

            if ($jwtVerify->estado) {

                if ($tipo == 'array') {

                    if (isset($resultado['cuerpo'])) {
                        if ($resultado['cuerpo'] != null) {
                            http_response_code(200);
                            echo json_encode($resultado);
                        } else {
                            http_response_code(404);
                            echo json_encode(array("estado" => "error", "mensaje" => "No existen datos"));
                        }
                    } else {
                        http_response_code(404);
                        echo json_encode(array("estado" => "error", "mensaje" => "No existen datos"));
                    }
                } else if ($tipo == 'json') {
                    if ($resultado != null) {
                        http_response_code(200);
                        echo $resultado;
                    } else {
                        http_response_code(404);
                        echo json_encode(array("estado" => "error", "mensaje" => "No existen datos"));
                    }
                }
            } else {
                http_response_code(401);
                echo json_encode(array("estado" => "error", "mensaje" => $jwtVerify->mensaje));
            }
        } else {

            http_response_code(401);
            echo json_encode(array("estado" => "error", "mensaje" => "Token requerido"));
        }
    }


    /**
     * Verifica un token válido
     * 
     * @return Array Retorna un array([estado] => '',[mensaje] =>'' ) Con la llave 'estado' del token (exito/error) y la llave 'mensaje' con el detalle de la validación
     */
    public function validarToken($rutaPublicKey)
    {

        $headers = apache_request_headers();
        $headers = array_change_key_case($headers, CASE_LOWER);

        if (isset($headers['authorization'])) {
            $token = str_replace("Bearer ", "", $headers['authorization']);
            $jwtVerify = new JwtVerify($token, $rutaPublicKey);

            if ($jwtVerify->estado) {
                http_response_code(200);
                return (array("estado" => "exito", "mensaje" => 'Token válido'));
            } else {
                http_response_code(401);                
                return (array("estado" => "error", "mensaje" => $jwtVerify->mensaje));
                
            }
        } else {

            http_response_code(401);           
            return (array("estado" => "error", "mensaje" => "Token requerido"));
        }
    }


    public function hash($privateKey)
    {
        return password_hash($privateKey, PASSWORD_DEFAULT, ['cost' => 13]);
    }

    /**
     * Validar si hash es correcto con la llave privada.
     *
     * @return bool	
     */
    public function validarPublicKey($privateKey,$hash)
    {
        return password_verify($privateKey, $hash);
    }
}
