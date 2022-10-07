<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/02/18
 * Time: 8:52
 */

require_once '../Modelo/Insumo.php';
class ServiceInsumoDAO
{
    /**
     * ServiceInsumoDAO constructor.
     */
    public function __construct()
    {
    }

    public function getAllInsumos($conexion){
        $queryAll=" SELECT ic_insumo_id, nombre, descripcion, programa_id";
        $queryAll.=" FROM g_inocuidad.ic_insumo";
        $queryAll.=" ORDER BY programa_id, nombre";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasInsumo = pg_fetch_assoc($result)) {
                $insumo = new Insumo($filasInsumo['ic_insumo_id'],$filasInsumo['nombre'],$filasInsumo['descripcion'],$filasInsumo['programa_id']);
                array_push($filas, $insumo);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function getInsumoById($insumoId,$conexion){
        $queryAll=" SELECT ic_insumo_id, nombre, descripcion, programa_id";
        $queryAll.=" FROM g_inocuidad.ic_insumo";
        $queryAll.=" WHERE ic_insumo_id=$insumoId";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasInsumo = pg_fetch_assoc($result)) {
                $insumo = new Insumo($filasInsumo['ic_insumo_id'],$filasInsumo['nombre'],$filasInsumo['descripcion'],$filasInsumo['programa_id']);
            }
        }catch(Exception $exc){
            return new Insumo();
        }
        return $insumo;
    }

    public function getIngredienteActivo($insumoId,$conexion){
        $queryAll=" SELECT id_producto as id_ingrediente_activo, nombre_cientifico as ingrediente_quimico, nombre_comun as ingrediente_activo, 0";
        $queryAll.=" FROM g_catalogos.productos";
        $queryAll.=" WHERE id_producto=$insumoId";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasInsumo = pg_fetch_assoc($result)) {
                $insumo = new Insumo($filasInsumo['id_ingrediente_activo'],$filasInsumo['ingrediente_activo'],$filasInsumo['ingrediente_quimico'],0);
            }
        }catch(Exception $exc){
            return new Insumo();
        }
        return $insumo;
    }

    public function saveAndUpdateInsumo(Insumo $insumo,$conexion){
        $result=null;
        $querySave="";
        $sequenceQuery ="SELECT nextval('g_inocuidad.ic_insumo_ic_insumo_id_seq')";
        if(isset($insumo)) {

            $ic_insumo_id = $insumo->getIcInsumoId();
            $nombre = $insumo->getNombre();
            $descripcion = $insumo->getDescripcion();
            $programa_id = $insumo->getProgramaId();

            if ($insumo->getIcInsumoId() != null) {
                $querySave = " UPDATE g_inocuidad.ic_insumo";
                $querySave .= "   SET nombre='$nombre', descripcion='$descripcion', programa_id=$programa_id";
                $querySave .= " WHERE ic_insumo_id=$ic_insumo_id";
            } else {
                $ic_insumo_id = $this->obtenerSecuencial($conexion,$sequenceQuery);
                $querySave = " INSERT INTO g_inocuidad.ic_insumo(ic_insumo_id,nombre, descripcion, programa_id)";
                $querySave .= " VALUES($ic_insumo_id,'$nombre','$descripcion',$programa_id)";
            }
            try{
                $result=$conexion->ejecutarConsulta($querySave);
                $result=$ic_insumo_id;
            }catch (Exception $exc){
                $result = $exc->getMessage();
            }

            return $result;
        }
    }

    public function deleteInsumo($insumoId,$conexion){
        $queryDelete="DELETE FROM g_inocuidad.ic_insumo WHERE ic_insumo_id=$insumoId";
        $result = $conexion->ejecutarConsulta($queryDelete);
        return $result;
    }
    private function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }


}