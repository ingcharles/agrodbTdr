<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 21/08/18
 * Time: 10:39
 */

require_once '../controladores/ControladorRequerimiento.php';
require_once "../servicios/ServiceReportesDAO.php";
require_once '../../../clases/Conexion.php';
require_once '../Util.php';

if (isset($_POST['tipo'],$_POST['data'])) {
    $tipo = $_POST['tipo'];
    $data = $_POST['data'];
    $result = "false";
    //Evaluamos el tipo de reporte para consultar disponibilidad
    if($tipo=="360"){
        $controladorRequerimiento= new ControladorRequerimiento();
        $caso = $controladorRequerimiento->recuperarRequerimiento($data);
        if($caso!=null)
            $result="true";
    }else if($tipo == "detallado"){
        $conexion = new Conexion();
        $servicio = new ServiceReportesDAO();
        $util = new Util();

        $objeto = json_decode($data);
        $sqlWHERE = $util->getSQLWhere($objeto);
        $res = $servicio->cuentaDetallado($conexion,$sqlWHERE);
        $result = pg_fetch_row($res);
        $result = json_encode($result);
    }

    echo $result;
}