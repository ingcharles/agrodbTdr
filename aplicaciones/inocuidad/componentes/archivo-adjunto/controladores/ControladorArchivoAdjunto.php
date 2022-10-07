<?php
/**
 * ccarrera
 */
session_start();
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/componentes/archivo-adjunto/modelos/ItemArchivo.php';
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/componentes/archivo-adjunto/modelos/ItemArchivoModelo.php';
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/componentes/archivo-adjunto/servicios/ItemArchivoDAO.php';
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/componentes/archivo-adjunto/servicios/MapperDAO.php';
require_once $_SESSION['_ABSPATH_'].'/clases/Conexion.php';

class ControladorArchivoAdjunto
{
    private $conexion;
    private $servicios;
    private $mapperSvr;
    /**
     * ControladorArchivoAdjunto constructor.
     */
    public function __construct()
    {
        $this->conexion= new Conexion();
        $this->servicios= new ItemArchivoDAO();
        $this->mapperSvr = new MapperDAO();
    }

    /*
     * Almacena el archivo en el directorio definido y la metadata en la base de datos para indexarlo.
     * */
    public function guardarItemArchivo($itemArchivo,$modelo){
        try {
            $sequenceQuery = 'SELECT nextval(\'g_inocuidad.ic_adjunto_item_ic_adjunto_item_id_seq\')';
            $parametros = $this->servicios->obtenerCatalogoParametros($this->conexion, "archivo_parametros");
            $path = $parametros['valor'];
            $extension = '';
            if (isset($_FILES['adjunto_file']) && $_FILES['adjunto_file']['error'] == 0) {
                $extension = pathinfo($_FILES['adjunto_file']['name'], PATHINFO_EXTENSION);
            }

            $secuencialArchivo = $this->servicios->obtenerSecuencial($this->conexion, $sequenceQuery);
            $pathArchivo = $path . $modelo->getNombreTabla() . '-' . $modelo->getRegistro() . '-' . $secuencialArchivo;
            $itemArchivo->setRuta($pathArchivo . '.' . $extension);
            $itemArchivo->setId($secuencialArchivo);
            $result = $this->copiarArchivoPath($pathArchivo);

            if ($result == '{"result":"success"}') {
                $this->servicios->guardarItemArchivo($itemArchivo, $modelo, $path, $extension, $this->conexion);
            }
        }catch (Exception $ex){
            $result = '{"result":"error"}';
        }
        echo $result;
    }

    public function copiarArchivoPath($fileName){
        $allowed = array('jpg', 'jpeg', 'pdf', 'txt', 'doc', 'docx','xls','xlsx','png');
        if(isset($_FILES['adjunto_file']) && $_FILES['adjunto_file']['error'] == 0){
            $extension = pathinfo($_FILES['adjunto_file']['name'], PATHINFO_EXTENSION);
            if(!in_array(strtolower($extension), $allowed)){
                return '{"result":"extension","permitted":'.json_encode($allowed)."}";
                exit;
            }
            if(@move_uploaded_file($_FILES['adjunto_file']['tmp_name'], $fileName . '.'.$extension)){
                return '{"result":"success"}';
                exit;
            }

            return '{"result":"error","message":"'.$_FILES['adjunto_file']['tmp_name'].' -> '.$fileName . '.'.$extension.'"}';
        }
    }

    public function obtenerArchivosPorTabla($tabla,$registro){
        $res =$this->servicios->recuperarArchivoItemPorModelo($tabla,$registro,$this->conexion);
        return $res;
    }

    /*
     * Construye la tabla con la metadata de los archivos para visualizarse en el componente.
     * */
    public function crearTablaArchivos($tabla,$registro){
        $tableFiles="";
        if(isset($registro)) {
            $mapper = $this->mapperSvr->mapearElemento($tabla,$this->conexion);
            if($mapper!=null && $mapper->getNombreEsquema()!=null && $mapper->getNombreTabla()!=null)
                $title = "Módulo: ".$mapper->getNombreEsquema()." - Tabla: ".$mapper->getNombreTabla()." - Registro: $registro";
            else
                $title = "Módulo: ".strtok($tabla,".")." - Tabla: ".strtok(".")." - Registro: $registro";
            $tableFiles ="<div class='adjuntos_header_title'><label>$title</label></div>";
            $tableFiles .= " <table class='adjuntos_detalle'>
        <tr class='adjuntos_header'>
            <th style='width:22%'>Nombre</th>
            <th style='width:22%'>Descripción</th>
            <th style='width:24%'>Fecha de registro</th>
            <th style='width:22%'>Etiqueta</th>
            <th style='width:10%'>Acciones</th>
        </tr>";
            $archivos = $this->obtenerArchivosPorTabla($tabla,$registro);

            /* @var $file ItemArchivo */
            foreach ($archivos as $file) {
                $ruta = $file->getRuta();
                $tableFiles .= "<tr class='adjuntos_fila'>";
                $tableFiles .= "<td>";
                $tableFiles .= $file->getNombre();
                $tableFiles .= "</td>";
                $tableFiles .= "<td>";
                $tableFiles .= $file->getDescripcion();
                $tableFiles .= "</td>";
                $tableFiles .= "<td>";
                $tableFiles .= $this->formatDate($file->getFechaCarga());
                $tableFiles .= "</td>";
                $tableFiles .= "<td>";
                $tableFiles .= $file->getEtiqueta();
                $tableFiles .= "</td>";
                $tableFiles .= "<td>";
                $tableFiles .= "<a id='adjuntos_file_download' class='material_link' href='aplicaciones/inocuidad/componentes/archivo-adjunto/viewFile.php?file=$ruta' target='_blank'><i class='material-icons'>file_download</i></a>";
                $tableFiles .= "</td>";
                $tableFiles .= "</tr>";
            }
            $tableFiles .= "</table>";
        }
        return $tableFiles;
    }

    public function formatDate($dateString){
        $date = new DateTime($dateString);
        return $date->format('Y/m/d H:i');
    }

}