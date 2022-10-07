<?php
/**
 * JwtGenerate
 *
 * Este archivo permite generar el token JWT
 *
 * @property AGROCALIDAD
 * @date   2021-11-18
 * @uses JwtGenerate
 * @package Core
 * @subpackage Token
 * 
 */

namespace Agrodb\Token;

use Firebase\JWT\JWT;

// error_reporting(1);
class JwtGenerate
{

    /**
     * Método para genenerar el token JWT en base a token para APIS
     */
    public function generarToken($rutaKey)
    {
        $llavePrivada = file_get_contents(MVC . $rutaKey);
        $fechaActual = date('Y-m-d H:i:s');
        $fechaTokenEmision = strtotime($fechaActual);
        $fechaTokenValido = $fechaTokenEmision;
        $fechaTokenExpira = strtotime('+40 minute', strtotime($fechaActual));

        $token = array(
            "iss" => "SISTEMA GUIA TEST", // Identifica el proveedor de identidad que emitió el JWT
            "aud" => "API AGROSERVICIOS TEST", // Identifica la audiencia o receptores para lo que el JWT fue emitido, normalmente el/los servidor/es de recursos (e.g. la API protegida). Cada servicio que recibe un JWT para su validación tiene que controlar la audiencia a la que el JWT está destinado. Si el proveedor del servicio no se encuentra presente en el campo aud, entonces el JWT tiene que ser rechazado
            "iat" => $fechaTokenEmision, // 1356999524, //Identifica la fecha en qué el JWT fue emitido.
            "nbf" => $fechaTokenValido, // Identifica fecha en que el JWT comienza a ser válido. EL JWT no tiene que ser aceptado si el token es utilizando antes de este tiempo.
            "exp" => $fechaTokenExpira // Identifica fecha luego de la cual el JWT no tiene que ser aceptado
        );

        $jwt = JWT::encode($token, $llavePrivada, 'RS256');
        return array("token" => $jwt, "expiraEn" => $fechaTokenExpira);
      
    }
}

