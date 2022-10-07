<?php

/**
 * JwtVerify
 *
 * Este archivo permite verificar el token JWT
 *
 * @property AGROCALIDAD
 * @date   2021-11-18
 * @uses JwtVerify
 * @package Core
 * @subpackage Token
 *
 */

namespace Agrodb\Token;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\JWT;
// error_reporting(0);
class JwtVerify
{

    public $datosToken = null;

    public $estado = null;

    public $mensaje = null;

    public function __construct($token, $rutaPublicKey)
    {
        $publicKey = file_get_contents(MVC . $rutaPublicKey);

        try {

            if (!isset($token)) {
                $this->estado = false;
                $this->datosToken = "Token requerido";
            }

            $this->datosToken = JWT::decode($token, $publicKey, ['RS256']);
            $this->estado = true;
            $this->mensaje = "Token válido";
        } 
        catch (ExpiredException $e) {
            $this->estado = false;
            $this->mensaje = 'Token expirado';
        } catch (SignatureInvalidException $e) {
            $this->estado = false;
            $this->mensaje = 'Verificación de firma fallida';
        } catch (BeforeValidException $e) {
            $this->estado = false;
            $this->mensaje = 'No se puede manejar el token antes de la fecha de emisión';
        } catch (Exception $e){
            $this->estado = false;
            $this->mensaje = 'Token inválido';
        }
    }
}
