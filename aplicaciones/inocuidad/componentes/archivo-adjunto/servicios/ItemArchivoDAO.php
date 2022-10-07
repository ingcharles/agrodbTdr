<?php
/**
 * User: carlosCarrera
 * Servicio DAO de ItemArchivo
 */
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/componentes/archivo-adjunto/modelos/ItemArchivo.php';
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/componentes/archivo-adjunto/modelos/ItemArchivoModelo.php';
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/Util.php';
class ItemArchivoDAO
{

    private $sequenceQuery ='SELECT nextval(\'g_inocuidad.ic_adjunto_item_ic_adjunto_item_id_seq\')';
    private $sequenceModelQuery ='SELECT nextval(\'g_inocuidad.ic_adjunto_model_ic_adjunto_model_id_seq\')';
    public function __construct()
    {
    }
    /*
     *Guarda un Item(Archivo) en la base de datos, según los parámetros ingresados
     * */
    public function guardarItemArchivo(ItemArchivo $archivo,ItemArchivoModelo $modelo,$rutaFile,$extension,$conexion){
        $resultado = null;

        $existingModel = $this->obtenerModeloPorTablayRegistro($modelo,$conexion);
        try{
            if($existingModel!=null && $existingModel->getId()>0) {
                $secuencialModelo = $existingModel->getId();
                $modelo->setId($existingModel->getId());
            } else {
                $secuencialModelo = $this->obtenerSecuencial($conexion, $this->sequenceModelQuery);
                $insertModel = $this->construyeModeloQuery($secuencialModelo, $modelo);
                $resultado=$conexion->ejecutarConsulta($insertModel);
            }
            $insertArchivo=$this->construyeArchivoQuery($secuencialModelo,$archivo->getId(),$archivo);
            $conexion->ejecutarConsulta($insertArchivo);
            $resultado=$rutaFile;
        }catch (Exception $ex){
            $resultado = null;
        }

        return $resultado;
    }


    /*
     * Construye el insert para almacenar el modelo de Archivo en la base de datos
     * */
    private function construyeModeloQuery($secuencialModelo,ItemArchivoModelo $modelo){
        $nombreTabla=$modelo->getNombreTabla();
        $registro=$modelo->getRegistro();
        $insertModelQuery="INSERT INTO g_inocuidad.ic_adjunto_model(
            ic_adjunto_model_id, nombre_tabla, registro) ";
        $insertModelQuery.="VALUES($secuencialModelo,'$nombreTabla',$registro); ";

        return $insertModelQuery;
    }

    /*
     * Construye el select para obtener el Item de Archivo en la base de datos según el modelo
     * */
    private function obtenerModeloPorTablayRegistro(ItemArchivoModelo $modelo, $conexion){
        $nombreTabla=$modelo->getNombreTabla();
        $registro=$modelo->getRegistro();
        $selectQuery = "SELECT ic_adjunto_model_id, nombre_tabla, registro 
                      FROM g_inocuidad.ic_adjunto_model 
                      WHERE nombre_tabla='$nombreTabla' AND registro=$registro ";
        $file = null;
        try {
            $result = $conexion->ejecutarConsulta($selectQuery);

            while ($filasPrd = pg_fetch_assoc($result)) {
                $file = new ItemArchivoModelo($filasPrd['ic_adjunto_model_id'],$filasPrd['nombre_tabla'],$filasPrd['registro']);
            }
        }catch(Exception $exc){
            return $selectQuery;
        }
        return $file;
    }

    /*
     * Construye el insert para almacenar el Item de Archivo en la base de datos según el modelo
     * */
    private function construyeArchivoQuery($secuencialModelo,$secuencialArchivo,ItemArchivo $archivo){
       $insertQuery = "INSERT INTO g_inocuidad.ic_adjunto_item(
            ic_adjunto_item_id, ic_adjunto_model_id, nombre, descripcion,
            fecha_carga, etiqueta, ruta) ";
        $util = new Util();
        $nombre=$archivo->getNombre();
        $descr=$archivo->getDescripcion();
        $fecha=$archivo->getFechaCarga();
        $fechaFormated = $util->formatDate($fecha);
        $etiqueta=$archivo->getEtiqueta();
        $ruta=$archivo->getRuta();
        $insertQuery.="VALUES($secuencialArchivo,$secuencialModelo,'$nombre','$descr','$fechaFormated','$etiqueta','$ruta') ";
        return $insertQuery;
    }

    public function recuperarArchivoPorId($id){

    }
    public function recuperarArchivoItemPorModelo($nombre_tabla, $registro,$conexion){
        $selectQuery="SELECT fi.ic_adjunto_item_id as id, fi.nombre as nombre, fi.ruta as ruta, fi.fecha_carga as fecha, ";
        $selectQuery.=" fi.ic_adjunto_model_id as modelid, fi.etiqueta as etiqueta , fi.descripcion as descripcion ";
        $selectQuery.=" FROM g_inocuidad.ic_adjunto_item fi, g_inocuidad.ic_adjunto_model mo ";
        $selectQuery.=" WHERE fi.ic_adjunto_model_id = mo.ic_adjunto_model_id ";
        $selectQuery.=" AND mo.nombre_tabla = '$nombre_tabla' AND mo.registro= $registro ";
        $filas = array();
        try {
            $result = $conexion->ejecutarConsulta($selectQuery);

            while ($filasPrd = pg_fetch_assoc($result)) {
               $file = new ItemArchivo($filasPrd['modelid'],$filasPrd['nombre'],$filasPrd['descripcion'],$filasPrd['fecha'],$filasPrd['etiqueta'],
                   $filasPrd['ruta']);
                array_push($filas, $file);
            }
        }catch(Exception $exc){
            return $selectQuery;
        }
        return $filas;

    }

    public function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }

    /*
     * Obtiene los parámetros definidos para la configuración del componente desde la tabla catalogo
     * */
    public function obtenerCatalogoParametros($conexion,$grupo){
        $queryCatalogo = "SELECT ic_catalogo_id, grupo, nombre, valor, valor_num, descripcion, referencia_id FROM g_inocuidad.ic_catalogo WHERE grupo='$grupo' ORDER BY  nombre";
        $res = $conexion->ejecutarConsulta($queryCatalogo);
        $sec=pg_fetch_assoc($res);
        return $sec;
    }
}