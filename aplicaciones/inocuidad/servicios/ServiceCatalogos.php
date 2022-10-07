<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 01/02/18
 * Time: 9:09
 */
session_start();
require_once "../controladores/ControladorCatalogosInc.php";
require_once "../controladores/ControladorProducto.php";
$controladorCatalogos = new ControladorCatalogosInc();

/*
 * Este servicio invoca los catálogos desde javascript y los obtiene en ajax.
 * Recibimos 2 variables:
 * @catalogo: EL CATALOGO A EJECUTAR
 * @selectData: EL VALOR DE REFERENCIA, por ejempl, el país de las provincias que vamos a cargar.
 * */
if (isset($_POST['catalogo'],$_POST['selectData'])) {

    $selectData = $_POST['selectData'];
    $catalogo = $_POST['catalogo'];
    $usuario = $_SESSION['usuario'];
    $dataCatalogos = [];

    /*
     * Vamos al controlador de catalogos a obtener la lista
     * */
    switch ($catalogo){
        case 'INSUMOS_PROGRAMA':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("INSUMOS_PROGRAMA",$selectData);
            break;

        case 'TIPO_PRODUCTO':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("TIPO_PRODUCTO",$selectData);
            break;

        case 'SUBTIPO_PRODUCTO':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("SUBTIPO_PRODUCTO",$selectData);
            break;

        case 'PRODUCTO_BASE':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("PRODUCTO_BASE",$selectData);
            break;

        case 'AREA_PRODUCTOS':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("AREA_PRODUCTOS",$selectData);
            break;

        case 'CANTONES':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("CANTONES",$selectData);
            break;

        case 'PARROQUIAS':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("PARROQUIAS",$selectData);
            break;
        case 'PARAMETROS':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("PARAMETROS",$selectData);
            break;
        case 'FINCAS':
            $objFinca = json_decode($selectData);
            $provincia = $objFinca->{'provincia'};
            $canton = $objFinca->{'canton'};
            $parroquia = $objFinca->{'parroquia'};
            $dataCatalogos=$controladorCatalogos->listaSitiosFincas($provincia,$canton,$parroquia);
            break;
        case 'DATOS_FINCA':
            $dataCatalogos=$controladorCatalogos->obtenerSitioPorId($selectData);
            break;
        case 'DATOS_FITOSANITARIO':
            $dataCatalogos=$controladorCatalogos->obtenerImportacionPorCertificado($selectData);
            break;
        case 'CANCELAR_REGISTRO':
            $objCancelar = json_decode($selectData);
            $ic_requerimiento_id = $objCancelar->{'ic_requerimiento_id'};
            $mensaje = $objCancelar->{'mensaje'};
            $dataCatalogos=$controladorCatalogos->cancelarRegistro($ic_requerimiento_id,$mensaje,$usuario);
            break;
        case 'CUENTA_PRODUCTO_INSUMO':
            $controladorProductos = new ControladorProducto();
            $dataCatalogos=$controladorProductos->consultarInsumosDeProducto($selectData);
            break;
        case 'CREAR_PRODUCTO_INSUMO' :
            $objCancelar = json_decode($selectData);
            $ic_producto_id = $objCancelar->{'ic_producto_id'};
            $producto_id = $objCancelar->{'producto_id'};
            $dataCatalogos=$controladorCatalogos->crearProductoInsumo($ic_producto_id,$producto_id);
            break;
        case 'LISTAR_PRODUCTO_INSUMO_JSON' :
            $controladorProductos = new ControladorProducto();
            $dataCatalogos=$controladorProductos->listarProductosInsumosJSON($selectData);
            break;
        case 'CARGAR_INSPECTORES':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("INSPECTORES",$selectData);
            break;
        case 'DATOS_INSPECTOR':
            $dataCatalogos=$controladorCatalogos->obtenerInspectorPorIdentificacion($selectData);
            break;
        case 'CARGAR_TECNICOS':
            $dataCatalogos=$controladorCatalogos->obtenerComboCatalogosOpcionesParam("TECNICOS",$selectData);
            break;

    }

    echo json_encode($dataCatalogos);
}
?>