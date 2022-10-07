<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 20/02/18
 * Time: 23:01
 */

require_once "../Modelo/Muestra.php";
require_once "../controladores/ControladorRequerimiento.php";
require_once "../controladores/ControladorProducto.php";
require_once "../Modelo/RegistroValor.php";
require_once "../Modelo/Laboratorio.php";
require_once '../Util.php';

class ServiceLaboratorioDAO
{

    public function getAllLaboratorioInStep($conexion){
        $queryAll="SELECT fecha_envio_lab, cantidad_muestras_lab, cantidad_contra_muestra, 
                       ultimo_insumo_aplicado_id, produccion_estimada, fecha_ultima_aplicacion, 
                       tecnica_muestreo, mu.ic_muestra_id, ic_analisis_muestra_id, medio_refrigeracion, 
                       am.observaciones, am.activo, req.ic_tipo_requerimiento_id, req.ic_requerimiento_id,
                       req.ic_producto_id
                  FROM g_inocuidad.ic_analisis_muestra am
                  JOIN g_inocuidad.ic_muestra mu on mu.ic_muestra_id = am.ic_muestra_id
                  JOIN g_inocuidad.ic_requerimiento req on mu.ic_requerimiento_id = req.ic_requerimiento_id
                  WHERE REQ.cancelado='N' AND am.activo and ic_analisis_muestra_id not in (SELECT ic_analisis_muestra_id FROM g_inocuidad.ic_evaluacion_analisis)
                  ORDER BY ic_tipo_requerimiento_id, req.ic_requerimiento_id, mu.ic_muestra_id";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $laboratorio = new Laboratorio($filasProducto['ic_analisis_muestra_id'], $filasProducto['ic_muestra_id'],
                    $filasProducto['activo']);
                $laboratorio->setObservaciones($filasProducto['observaciones']);
                $laboratorio->setIcTipoRequerimientoId($filasProducto['ic_tipo_requerimiento_id']);
                $laboratorio->setIcRequerimientoId($filasProducto['ic_requerimiento_id']);
                $laboratorio->setIcProductoId($filasProducto['ic_producto_id']);
                array_push($filas, $laboratorio);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }


    public function getLaboratorioById($ic_analisis_muestra_id, $conexion){
        $queryAll="SELECT fecha_envio_lab, cantidad_muestras_lab, cantidad_contra_muestra, 
                       ultimo_insumo_aplicado_id, produccion_estimada, fecha_ultima_aplicacion, 
                       tecnica_muestreo, mu.ic_muestra_id, ic_analisis_muestra_id, medio_refrigeracion, 
                       am.observaciones, am.activo, req.ic_tipo_requerimiento_id, req.ic_requerimiento_id,
                       req.ic_producto_id
            FROM g_inocuidad.ic_analisis_muestra am
            JOIN g_inocuidad.ic_muestra mu on mu.ic_muestra_id = am.ic_muestra_id
            JOIN g_inocuidad.ic_requerimiento req on mu.ic_requerimiento_id = req.ic_requerimiento_id
            WHERE ic_analisis_muestra_id = $ic_analisis_muestra_id";

        $laboratorio = null;
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $laboratorio = new Laboratorio($filasProducto['ic_analisis_muestra_id'], $filasProducto['ic_muestra_id'],
                    $filasProducto['activo']);
                $laboratorio->setObservaciones($filasProducto['observaciones']);
                $laboratorio->setIcTipoRequerimientoId($filasProducto['ic_tipo_requerimiento_id']);
                $laboratorio->setIcRequerimientoId($filasProducto['ic_requerimiento_id']);
                $laboratorio->setIcProductoId($filasProducto['ic_producto_id']);
            }
        }catch(Exception $exc){
            return new Laboratorio();
        }
        return $laboratorio;
    }

    public function saveAndUpdateRegistroValor(RegistroValor $registroValor, $conexion)
    {
        $result = null;
        $querySave = "";
        if (isset($registroValor)) {
            $ic_registro_valor_id   = $registroValor->getIcRegistroValorId();
            $valor                  = $registroValor->getValor();
            $observaciones          = $registroValor->getObservaciones();

            $querySave = "UPDATE g_inocuidad.ic_registro_valor
                           SET valor=$valor, observaciones='$observaciones'
                         WHERE ic_registro_valor_id=$ic_registro_valor_id";

            try{
                $conexion->ejecutarConsulta($querySave);
            }catch (Exception $exc){
                $result = $exc->getMessage();
            }

            return $result;

        }
    }

    public function desactivarLaboratorio($ic_analisis_muestra_id,$conexion){
        $querySave = "UPDATE g_inocuidad.ic_analisis_muestra
                           SET activo=FALSE 
                         WHERE ic_analisis_muestra_id=$ic_analisis_muestra_id";
        try{
           $result = $conexion->ejecutarConsulta($querySave);
        }catch (Exception $exc){
            $result = $exc->getMessage();
        }
        return $result;
    }

    public function saveAndUpdateLaboratorio(Laboratorio $laboratorio, $conexion){
        $result=null;
        $querySave="";
        $util=new Util();
        if(isset($laboratorio)) {

            $ic_analisis_muestra_id    = $laboratorio->getIcAnalisisMuestraId();
            $ic_muestra_id             = $laboratorio->getIcMuestraId();
            $observaciones             = $laboratorio->getObservaciones();
            $activo                    = $laboratorio->getActivo();

            if($laboratorio->getIcAnalisisMuestraId()!=null){
                $querySave = "UPDATE g_inocuidad.ic_analisis_muestra
                       SET ic_muestra_id=$ic_muestra_id, 
                           observaciones='$observaciones'
                     WHERE ic_analisis_muestra_id = $ic_analisis_muestra_id";

            }else
                throw new Exception("El registro de Laboratorio debe originarse en la Muestra [ic_analisis_muestra = null]");

            try{
                $conexion->ejecutarConsulta($querySave);
            }catch (Exception $exc){
                $result = $exc->getMessage();
            }

            return $result;
        }
    }

    public function creaLaboratorioMuestra(Muestra $muestra, $cantidadContraMuestra, $conexion){
        $result=null;
        $querySave="";
        $controladorRequerimiento = new ControladorRequerimiento();

        $idMuestra = $muestra->getIcMuestraId();
        $caso = $controladorRequerimiento->recuperarRequerimiento($muestra->getIcRequerimientoId());

        if(isset($idMuestra)) {
            $sequenceQuery ='SELECT nextval(\'g_inocuidad.ic_analisis_muestra_ic_analisis_muestra_id_seq\')';
            $analisisMuestra=$this->obtenerSecuencial($conexion,$sequenceQuery);
            $querySave="INSERT INTO g_inocuidad.ic_analisis_muestra 
                         (ic_analisis_muestra_id,ic_muestra_id) 
                  VALUES ($analisisMuestra,$idMuestra)";

        }else
            throw new Exception("El registro de laboratorio debe originarse en la Muestra [ic_analisis_muestra_id = null]");

        try{
            $result=$conexion->ejecutarConsulta($querySave);
            $result=$analisisMuestra;
            $this->crearRegistroValores($muestra,$caso,$analisisMuestra,$conexion);
        }catch (Exception $exc){
            $result = $exc->getMessage();
        }

        return $result;
    }

    public function getAllRegistroValorByLaboratorio($ic_analisis_muestra_id,$conexion){
        $queryAll=" SELECT ic_registro_valor_id, ic_analisis_muestra_id, ic_producto_id, 
                       ic_insumo_id, valor, observaciones, um
                  FROM g_inocuidad.ic_registro_valor
                  WHERE ic_analisis_muestra_id=$ic_analisis_muestra_id";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $registroValor = new RegistroValor($filasProducto['ic_registro_valor_id'],$filasProducto['ic_analisis_muestra_id'],
                    $filasProducto['ic_producto_id'],$filasProducto['ic_insumo_id'],$filasProducto['valor'],
                    $filasProducto['observaciones'],$filasProducto['um']);
                array_push($filas, $registroValor);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function crearRegistroValores(Muestra $muestra, Caso $caso, $ic_analisis_muestra_id, $conexion){
        $result = null;
        $querySave="";
        $controladorProducto = new ControladorProducto();
        $productoInsumos = $controladorProducto->listarProductosInsumosGrouped($caso->getProductoId());

        /* @var $insumo ProductoInsumo*/
        foreach ($productoInsumos as $insumo) {
            $sequenceQuery = 'SELECT nextval(\'g_inocuidad.ic_registro_valor_ic_registro_valor_id_seq\')';
            $registroValorId = $this->obtenerSecuencial($conexion, $sequenceQuery);

            $ic_producto_id     =$insumo->getIcProductoId();
            $ic_insumo_id       =$insumo->getIcInsumoId();
            $um                 =$insumo->getUm();

            $querySave="INSERT INTO g_inocuidad.ic_registro_valor(
                            ic_registro_valor_id, ic_analisis_muestra_id, ic_producto_id, 
                            ic_insumo_id, valor, observaciones, um)
                    VALUES ($registroValorId, $ic_analisis_muestra_id, $ic_producto_id, 
                            $ic_insumo_id, 0, NULL , '$um')";

            try{
                $result=$conexion->ejecutarConsulta($querySave);
            }catch (Exception $exc){
                $result = $exc->getMessage();
            }
        }

        return $result;
    }

    public function getLaboratorioRO($laboratorio_id,$conexion){
        $queryAll = "select * from G_INOCUIDAD.IC_V_ANALISIS_MUESTRA WHERE ic_analisis_muestra_id=$laboratorio_id";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    private function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }
}