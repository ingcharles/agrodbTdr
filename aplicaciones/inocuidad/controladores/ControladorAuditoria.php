<?php

require_once '../servicios/ServiceAuditoriaDAO.php';
require_once '../controladores/ControladorMensajes.php';

/*
* Controlador para manejar las pistas de auditoría y controlar las notificaciones.
* */
class ControladorAuditoria {

    private $servicios;
    private $mensaje;
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceAuditoriaDAO();
        $this->mensaje = new ControladorMensajes();
    }


    public function auditarRegistroInsert($usuario,$registro){
        $this->auditarRegistro($usuario,$registro,'I');
    }

    public function auditarRegistroUpdate($usuario,$registro){
        $this->auditarRegistro($usuario,$registro,'U');
    }

    public function auditarRegistroCancelar($usuario,$registro){
        $this->auditarRegistro($usuario,$registro,'C');
    }

    private function auditarRegistro($usuario,$registro,$tipo){
        $this->mensaje->enviarMensaje(null,"INSERT",$registro);
        $this->servicios->auditar($usuario,$registro,$tipo,$this->conexion);
    }

    /**/
    public function incrementarNumeroNotificacion($identificador){
        $this->servicios->actualizarNotificaion($identificador,'+',$this->conexion);
    }

    public function reducirNumeroNotificacion($identificador){
        $this->servicios->actualizarNotificaion($identificador,'-',$this->conexion);
    }
}