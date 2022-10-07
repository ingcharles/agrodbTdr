<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/03/18
 * Time: 23:16
 */

class ControladorMensajes
{
    /*
     * Envía correos electrónicos
     * */
    function enviarMensaje($ic_requerimiento_id,$estadoAnterior,$estadoSiguiente){
        try{
            error_log("Enviar Mensaje NO HACE NADA aun");
        }catch (Exception $e){
            error_log($e->getMessage());
        }

    }
}