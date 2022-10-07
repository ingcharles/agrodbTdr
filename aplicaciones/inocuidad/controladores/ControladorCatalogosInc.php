<?php
require_once '../servicios/CatalogosDAO.php';
require_once '../servicios/ServiceCasoDAO.php';
require_once '../servicios/ServiceProductoDAO.php';
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorCatalogos.php';
require_once '../../../clases/ControladorRequisitos.php';
/**
 * Created by Carlos Carrera.
 * Date: 1/30/18
 * Time: 9:38 PM
 */

class ControladorCatalogosInc
{
private $conexion;

    /**
     * ControladorCatalogosInc constructor.
     * @param $conexion
     */
    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    /*
     * Controlador centralizado para obtener los valores de los combos en las pantallas de la aplicación. Devuelve un array de opciones.
     * */
    public function obtenerComboCatalogosOpcionesParam($tipoCatalogo,$param){
        $dataCatalogos=[];
        $catagosServ=new CatalogosDAO();

        switch ($tipoCatalogo){
            case 'PROGRAMAS':
                $filas=$catagosServ->obtenerProgramas($this->conexion);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_catalogo_id']. '" data-grupo="'. $filasPrd['ic_catalogo_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'ORIGEN_MUESTRA':
                $filas=$catagosServ->obtenerCatalogoPorGrupo($this->conexion,"origen_informacion");
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_catalogo_id']. '" data-value="'. $filasPrd['valor']. '" data-grupo="'. $filasPrd['grupo'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'TIPO_MUESTRA':
                $filas=$catagosServ->obtenerCatalogoPorGrupo($this->conexion,"tipo_muestreo");
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_catalogo_id']. '" data-value="'. $filasPrd['valor']. '" data-grupo="'. $filasPrd['grupo'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'FUENTE_DENUNCIA':
                $filas=$catagosServ->obtenerFuentesDenuncia($this->conexion);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_catalogo_id']. '" data-grupo="'. $filasPrd['ic_catalogo_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'TECNICAS_MUESTREO':
                $filas=$catagosServ->obtenerCatalogoPorGrupo($this->conexion,"tecnica_muestreo");
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_catalogo_id']. '" data-grupo="'. $filasPrd['ic_catalogo_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'MEDIOS_REFRIGERACION':
                $filas=$catagosServ->obtenerCatalogoPorGrupo($this->conexion,"medio_refrigeracion");
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_catalogo_id']. '" data-grupo="'. $filasPrd['ic_catalogo_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'UNIDAD_MEDIDA':
                $catalogosCore = new ControladorCatalogos();
                $composicion = $catalogosCore->listarUnidadesMedidaXTipo($this->conexion,"composicion");
                while ($fila = pg_fetch_assoc($composicion)) {
                    $dataCatalogos[]='<option value="'.$fila['id_unidad_medida']. '" data-grupo="'. $fila['codigo'] . '">'. $fila['nombre'] ." (". $fila['codigo'] .")".'</option>';
                }
                $capacidad = $catalogosCore->listarUnidadesMedidaXTipo($this->conexion,"capacidad");
                while ($fila = pg_fetch_assoc($capacidad)) {
                    $dataCatalogos[]='<option value="'.$fila['id_unidad_medida']. '" data-grupo="'. $fila['codigo'] . '">'. $fila['nombre'] ." (". $fila['codigo'] .")".'</option>';
                }
                $unidad = $catalogosCore->obtenerUnidadMedida($this->conexion,34);
                while ($fila = pg_fetch_assoc($unidad)) {
                    $dataCatalogos[]='<option value="'.$fila['id_unidad_medida']. '" data-grupo="'. $fila['codigo'] . '">'. $fila['nombre'] ." (". $fila['codigo'] .")".'</option>';
                }
                break;
            case 'PRODUCTOS':
                $filas=$catagosServ->obtenerIcProductos($this->conexion);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_producto_id']. '" data-grupo="'. $filasPrd['ic_producto_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'INSUMOS_PROGRAMA':
                $filas=$catagosServ->obtenerIcInsumosXprograma($this->conexion, $param);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_insumo_id']. '" data-grupo="'. $filasPrd['ic_insumo_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'INSUMOS':
                $filas=$catagosServ->obtenerIcInsumos($this->conexion);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_insumo_id']. '" data-grupo="'. $filasPrd['ic_insumo_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'INSUMO_APLICADO':
                $filas=$catagosServ->obtenerIcInsumoAplicado($this->conexion, $param);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['id_ingrediente_activo']. '" data-grupo="'. $filasPrd['ingrediente_quimico'] . '">'. $filasPrd['ingrediente_activo'] .'</option>';
                }
                unset($filas);
                break;
            case 'LMRS':
                $filas=$catagosServ->obtenerIcLmr($this->conexion);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_lmr_id']. '" data-grupo="'. $filasPrd['ic_lmr_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'AREA_PRODUCTOS':
                $catalogosCore = new ControladorCatalogos();
                $productos=$catalogosCore->obtenerAreaProductos($this->conexion, $param);
                while ($fila = pg_fetch_assoc($productos)) {
                    $dataCatalogos[]='<option value="'.$fila['id_area']. '" data-grupo="'. $fila['id_subtipo_producto'] . '">'. $fila['nombre'] .'</option>';
                }
                unset($productos);
                break;
            case 'PRODUCTO_BASE':
                $catalogosCore = new ControladorCatalogos();
                $productos=$catalogosCore->listarProductoXsubTipoProducto($this->conexion, $param);
                while ($fila = pg_fetch_assoc($productos)) {
                    $dataCatalogos[]='<option value="'.$fila['id_producto']. '" data-grupo="'. $fila['id_subtipo_producto'] . '">'. $fila['nombre_comun'] .'</option>';
                }
                unset($productos);
                break;
            case 'REQUERIMIENTOS':
                $filas=$catagosServ->obtenerTipoRequerimiento($this->conexion);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['codigo']. '" data-grupo="'. $filasPrd['ic_tipo_requerimiento_id'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
            case 'PROVINCIAS':
                $catalogosCore = new ControladorCatalogos();
                $provincias=$catalogosCore->listarSitiosLocalizacion($this->conexion,"PROVINCIAS");
                $keys=array_keys($provincias);
                for($i = 0; $i < count($provincias); $i++) {
                    $laProvincia=$provincias[$keys[$i]];
                    $dataCatalogos[]='<option value="'.$laProvincia['codigo']. '" data-grupo="'. $laProvincia['codigo'] . '">'. $laProvincia['nombre'] .'</option>';


                }
                break;
            case 'CANTONES':
                $dataCatalogos[] = $this->listarSitiosLocalizacionFiltrado($param,'CANTONES');
                break;
            case 'PARROQUIAS':
                $dataCatalogos[] = $this->listarSitiosLocalizacionFiltrado($param,'PARROQUIAS');
                break;
            case 'PAISES':
                $catalogosCore = new ControladorCatalogos();
                $paises=$catalogosCore->listarSitiosLocalizacion($this->conexion,"PAIS");
                $keys=array_keys($paises);
                for($i = 0; $i < count($paises); $i++) {
                    $elPais=$paises[$keys[$i]];
                    $dataCatalogos[]='<option value="'.$elPais['codigo']. '" data-grupo="'. $elPais['codigo'] . '">'. $elPais['nombre'] .'</option>';


                }
                break;
            case 'TIPO_PRODUCTO':
                $catalogosCore = new ControladorCatalogos();
                $productos=$this->listarTipoProductosInocuidadXarea($this->conexion,$param);
                while ($fila = pg_fetch_assoc($productos)) {
                    $dataCatalogos[]='<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
                }
                unset($productos);
                break;
            case 'SUBTIPO_PRODUCTO':
                $catalogosCore = new ControladorCatalogos();
                $productos=$catalogosCore->listarSubTipoProductoXtipoProducto($this->conexion,$param);
                while ($fila = pg_fetch_assoc($productos)) {
                    $dataCatalogos[]='<option value="'.$fila['id_subtipo_producto']. '" data-grupo="'. $fila['id_tipo_producto'] . '">'. $fila['nombre'] .'</option>';
                }
                unset($productos);
                break;
            case 'PARAMETROSFILE':
                $filas=$catagosServ->obtenerCatalogoPorGrupo($this->conexion,"archivo_parametros");
                $dataCatalogos[] =$filas;
                unset($filas);
                break;
            case 'INSPECTORES':
                if($param==null || $param=='')
                    $param = 0;
                $filas=$catagosServ->obtenerInspectoresPorProvincia($this->conexion,$param);
                while ($fila = pg_fetch_assoc($filas)) {
                    $dataCatalogos[]='<option value="'.$fila['identificador']. '" data-grupo="'. $fila['identificador'] . '">'. $fila['nombre_completo'] .'</option>';
                }
                unset($filas);
                break;
            case 'TECNICOS':
                if($param==null || $param=='')
                    $param = 0;
                $filas=$catagosServ->obtenerInspectoresPorProvincia($this->conexion,$param);
                while ($fila = pg_fetch_assoc($filas)) {
                    $dataCatalogos[]='<option value="'.$fila['identificador']. '" data-grupo="'. $fila['identificador'] . '">'. $fila['nombre_completo'] .'</option>';
                }
                unset($filas);
                break;
            case 'RESULTADO_DESICION':
                $filas=$catagosServ->obtenerResultadosDesicion($this->conexion);
                while($filasPrd=pg_fetch_assoc($filas)){
                    $dataCatalogos[]='<option value="'.$filasPrd['ic_resultado_decision_id']. '" data-grupo="'. $filasPrd['tipo_desicion'] . '">'. $filasPrd['nombre'] .'</option>';
                }
                unset($filas);
                break;
        }

        return $dataCatalogos;
    }

    /*
     * Obtener combo de catalogos que reciben parámetros
     * */
    public function obtenerComboCatalogosOpciones($tipoCatalogo){
        return $this -> obtenerComboCatalogosOpcionesParam($tipoCatalogo, "");
    }

    /*A partir de aquí, funciones auxiliares para obtener valores concretos, utilizados en las ventanas de la aplicación*/
    public function obtenerNombrePrograma($programa_id){
        $catagosServ=new CatalogosDAO();
        $result="";

        $programas=$catagosServ->obtenerProgramaById($programa_id,$this->conexion);
        $fila = pg_fetch_assoc($programas);
        $result=$fila['nombre'];

        unset($programas);
        return $result;
    }

    public function obtenerNombreTipoRequerimiento($ic_tipo_requerimiento_id){
        $catagosServ=new CatalogosDAO();
        $result="";

        $requerimiento=$catagosServ->obtenerTipoRequerimientoById($this->conexion,$ic_tipo_requerimiento_id);
        $fila = pg_fetch_assoc($requerimiento);
        $result=$fila['nombre'];

        unset($registro);
        return $result;
    }

    public function obtenerSitios($provincia,$canton,$parroquia){
        $catagosServ=new CatalogosDAO();
        $filas=$catagosServ->obtenerSitios($provincia,$canton,$parroquia,$this->conexion);
        $filasPrd=pg_fetch_assoc($filas);
        unset($filas);
        return $filasPrd;
    }

    public function listaSitiosFincas($provincia,$canton,$parroquia){
        $dataCatalogos=[];
        $catagosServ=new CatalogosDAO();
        $filas=$catagosServ->obtenerSitios($provincia,$canton,$parroquia,$this->conexion);
        while($filasPrd=pg_fetch_assoc($filas)){
            $dataCatalogos[]='<option value="'.$filasPrd['id_sitio']. '" data-grupo="'. $filasPrd['direccion'] . '">'. $filasPrd['nombre_lugar'] .'</option>';
        }
        unset($filas);
        return $dataCatalogos;
    }

    public function obtenerSitioPorId($id_sitio){
        $catagosServ=new CatalogosDAO();
        $filas=$catagosServ->obtenerSitioPorId($id_sitio,$this->conexion);
        $filasPrd=pg_fetch_assoc($filas);
        unset($filas);
        return $filasPrd;
    }

    public function obtenerInspectorPorIdentificacion($identificacion){
        $catagosServ=new CatalogosDAO();
        $filas=$catagosServ->obtenerInspectorPorIdentificacion($identificacion,$this->conexion);
        $filasPrd=pg_fetch_assoc($filas);
        unset($filas);
        return $filasPrd;
    }

    public function obtenerNombreProvincia($id_provincia){
        $catagosServ=new CatalogosDAO();
        $result="";

        $filas=$catagosServ->obtenerProvincia($id_provincia,$this->conexion);
        $filasPrd=pg_fetch_assoc($filas);
        $result=$filasPrd['nombre'];

        unset($filas);
        return $result;
    }

    public function obtenerImportacionPorCertificado($certificado){
        $catagosServ=new CatalogosDAO();
        $filas=$catagosServ->obtenerImportacionPorCertificado($certificado,$this->conexion);
        $filasPrd=pg_fetch_assoc($filas);
        unset($filas);
        return $filasPrd;
    }

    public function obtenerNombreIcProducto($ic_producto_id){
        $catagosServ=new CatalogosDAO();
        $result="";

        $requerimiento=$catagosServ->obtenerIcProductoById($ic_producto_id,$this->conexion);
        $fila = pg_fetch_assoc($requerimiento);
        $result=$fila['nombre'];

        unset($requerimiento);
        return $result;
    }

    public function obtenerNombreAreaProducto($id_area){
        $catagosServ=new CatalogosDAO();
        $area=$catagosServ->obtenerAreaById($id_area,$this->conexion);
        $current = pg_fetch_assoc($area);
        $result=$current['nombre'];

        unset($area);
        return $result;
    }

    public function obtenerInsumoById($id_insumo){
        $catalogoInsumo = new ServiceInsumoDAO();
        $insumo = $catalogoInsumo->getInsumoById($id_insumo, $this->conexion);
        return $insumo->getNombre();
    }

    public function obtenerIngredienteActivoById($id_insumo){
        $catalogoInsumo = new ServiceInsumoDAO();
        $insumo = $catalogoInsumo->getIngredienteActivo($id_insumo, $this->conexion);
        return $insumo->getNombre();
    }

    public function obtenerUnidadMedidadById($id_um){

        $catalogosCore = new ControladorCatalogos();
        $um = $catalogosCore->obtenerUnidadMedida($this->conexion,$id_um);
        $current = pg_fetch_assoc($um);
        $result=$current['nombre']." (". $current['codigo'] .")";
        return $result;
    }

    public function obtenerNombreIcCatalogo($ic_catalogo_id){
        $catagosServ=new CatalogosDAO();
        $area=$catagosServ->obtenerCatalogoPorId($this->conexion,$ic_catalogo_id);
        $current = pg_fetch_assoc($area);
        $result=$current['nombre'];

        unset($area);
        return $result;
    }

    public function obtenerTipoSegunDecision($ic_resultado_decision_id){
        $catagosServ=new CatalogosDAO();
        $area=$catagosServ->obtenerRegistroDesicionById($this->conexion,$ic_resultado_decision_id);
        $current = pg_fetch_assoc($area);
        $result=$current['tipo_desicion'];

        unset($area);
        return $result;
    }

    /*
     * IC_INSUMO_ID en la tabla Productos ahora hace referencia a Ingredente Activo
     * */
    public function obtenerNombreIngredienteActivo($ic_insumo_id){
        $catalogosCore = new ControladorRequisitos();
        $row = $catalogosCore->abrirProducto($this->conexion,$ic_insumo_id);
        $ingrediente = pg_fetch_assoc($row);
        return $ingrediente['nombre_comun'];
    }

    public function obtenerEstadoModuloLaboratorio(){
        $catagosServ=new CatalogosDAO();
        $filas=$catagosServ->obtenerEstadoMouduloLaboratorio($this->conexion);
        $dataCatalogos = pg_fetch_assoc($filas);
        return $dataCatalogos['valor'];
    }

    private function listarSitiosLocalizacionFiltrado($data, $tipo){
        $dataCatalogos=[];
        $catalogosCore = new ControladorCatalogos();
        $cantones=$catalogosCore->listarSitiosLocalizacion($this->conexion,$tipo);
        $keys=array_keys($cantones);
        for($i = 0; $i < count($cantones); $i++) {
            $laCanton=$cantones[$keys[$i]];
            if($laCanton['padre']==$data)
                $dataCatalogos[]='<option value="'.$laCanton['codigo']. '" data-grupo="'. $laCanton['codigo'] . '">'. $laCanton['nombre'] .'</option>';
        }
        return $dataCatalogos;
    }

    /*Acción Auxiliar para cancelar los registros. Esta acción se ejecuta en el Dashboard*/
    public function cancelarRegistro($ic_requerimiento_id, $mensaje, $usuario){
        $casoService = new ServiceCasoDAO();
        return $casoService->cancelarRegistro($ic_requerimiento_id,$mensaje, $usuario, $this->conexion);
    }

    /*Utilidad para crear los insumos de un producto, cuando se invoca por Ajax desde la ventana de Crear productos*/
    public function crearProductoInsumo($ic_producto_id,$producto_id){
        $productoService = new ServiceProductoDAO();
        $return = "{\"mensaje\":\"Se han creado los Insumos con éxito\", \"estado\":\"EXITO\"}";
        $insumos=$productoService->getInsumosProductos($ic_producto_id,$this->conexion);
        if(count($insumos)<=0){
            $ret=$productoService->crearInsumosProducto($ic_producto_id,$producto_id,$this->conexion);
            if($ret!=null){
                $return = "{\"mensaje\":\"Errores al insertar los insumos: ".$ret."\", \"estado\":\"FALLO\"}";
            }
        }else{
            $return = "{\"mensaje\":\"Ya existen insumos asociados al producto\", \"estado\":\"FALLO\"}";
        }

        return $return;
    }

    public function listarTipoProductosInocuidadXarea($conexion, $idArea){
        $res = $conexion->ejecutarConsulta("select
									 			*
											from
												g_catalogos.tipo_productos
											where 
												id_area = '$idArea'
                                            --AND codificacion_tipo_producto = 'PRD_CULTIVO_IAP'
											order by 2;");
        return $res;
    }
}