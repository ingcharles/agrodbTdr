<?php
/**
 * User: carlosCarrera
 * Servicio DAO de ItemArchivo
 */
require_once $_SESSION['_ABSPATH_'].'aplicaciones/inocuidad/componentes/archivo-adjunto/modelos/Mapper.php';
class MapperDAO
{
    /*
     * Permite asociar los nombres de los esquemas y las tablas de la base de datos, nombres más legibles por los usuarios.
     * */
    public function mapearElemento($elemento, $conexion){
        $selectQuery = "SELECT elemento,nombreesquema,nombretabla FROM g_inocuidad.ic_adjunto_mapper WHERE elemento = '$elemento'";
        $mapper = null;
        try{
            $result = $conexion->ejecutarConsulta($selectQuery);
            if($result!=null)
                while ($filasMapper = pg_fetch_assoc($result)) {
                    $mapper = new Mapper($filasMapper['elemento'], $filasMapper['nombreesquema'], $filasMapper['nombretabla']);
                }
        }catch(Exception $exc){
            return new Mapper();
        }
        return $mapper;


        return $mapper;
    }
}