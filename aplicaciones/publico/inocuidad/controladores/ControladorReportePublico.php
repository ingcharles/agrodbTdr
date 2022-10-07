<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 02/04/18
 * Time: 22:15
 */

require_once 'servicios/ServiceReportePublicoDAO.php';
require_once '../../../clases/Conexion.php';

class ControladorReportePublico
{

    private $conexion;
    private $servicios;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->servicios = new ServiceReportePublicoDAO();
    }

    public function obtenerDatosPrincipales(){
        $resultado=null;
        try{
            $resultado="";
            $registros=$this->servicios->obtenerDatosPrincipales($this->conexion);
            foreach ($registros as $indice){
                $resultado="var objDatosPrincipales={\"totalNotificaciones\":".$indice['totalnotificaciones'].",\"totalProvincias\":".$indice['totalprovincias'].",\"contaminanteFrecuente\":\"".$indice['contaminantefrecuente']."\"};";
            }
        }catch (Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }

    public function notificaciones($tipo){
        $resultado=null;
        try{
            $resultado="";
            $registros = null;
            switch ($tipo){
                case "PROVINCIA":
                    $registros=$this->servicios->notificacionesPorProvincia($this->conexion);
                    break;
                case "PRODUCTO":
                    $registros=$this->servicios->notificacionesPorProducto($this->conexion);
                    break;
                case "ORIGEN":
                    $registros=$this->servicios->notificacionesPorOrigen($this->conexion);
                    break;
                case "PROGRAMA":
                    $registros=$this->servicios->notificacionesPorPrograma($this->conexion);
                    break;

            }
            if($registros!=null){
                foreach ($registros as $indice){
                    $resultado.="[[".$indice['anterior'].",".$indice['actual']."],'".$indice['nombre']."'],";
                }
            }
        }catch (Exception $e){
            $resultado=$e->getMessage();
        }
        return $resultado;
    }
}