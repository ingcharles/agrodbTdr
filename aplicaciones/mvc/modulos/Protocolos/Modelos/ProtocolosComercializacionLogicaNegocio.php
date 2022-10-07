<?php
/**
 * Lógica del negocio de ProtocolosComercializacionModelo
 *
 * Este archivo se complementa con el archivo ProtocolosComercializacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ProtocolosComercializacionLogicaNegocio
 * @package Protocolos
 * @subpackage Modelos
 */
namespace Agrodb\Protocolos\Modelos;

use Agrodb\Protocolos\Modelos\IModelo; 

class ProtocolosComercializacionLogicaNegocio implements IModelo
{

    private $modeloProtocolosComercializacion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloProtocolosComercializacion = new ProtocolosComercializacionModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ProtocolosComercializacionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdProtocoloComercio() != null && $tablaModelo->getIdProtocoloComercio() > 0) {
            return $this->modeloProtocolosComercializacion->actualizar($datosBd, $tablaModelo->getIdProtocoloComercio());
        } else {
            unset($datosBd["id_protocolo_comercio"]);
            return $this->modeloProtocolosComercializacion->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloProtocolosComercializacion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ProtocolosComercializacionModelo
     */
    public function buscar($id)
    {
        return $this->modeloProtocolosComercializacion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloProtocolosComercializacion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloProtocolosComercializacion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarProtocolosComercializacion()
    {
        $consulta = "SELECT * FROM " . $this->modeloProtocolosComercializacion->getEsquema() . ". protocolos_comercializacion";
        return $this->modeloProtocolosComercializacion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada, para obtener la información de los
     * protocolos de comercialización asignados a un producto y país de destino.
     *
     * @return array|ResultSet
     */
    public function obtenerProtocolosComercializacionXProductoXPais($arrayParametros) {
        
        $consulta = "   SELECT 
                        	pc.id_protocolo_comercio, 
                        	pc.id_localizacion, 
                        	pc.id_producto, 
                        	pc.nombre_pais,
                        	pc.nombre_producto,
                        	pa.id_protocolo,
                        	p.nombre_protocolo,
                        	pa.estado
                        FROM g_protocolos.protocolos_comercializacion pc
                        	INNER JOIN g_protocolos.protocolos_asignados pa ON pc.id_protocolo_comercio = pa.id_protocolo_comercio
                        	INNER JOIN g_protocolos.protocolos p ON p.id_protocolo = pa.id_protocolo
                        WHERE
                        	pc.id_localizacion = ".$arrayParametros['idLocalizacion']." and
                        	pc.id_producto = ".$arrayParametros['idProducto']." and
                        	pa.estado = 'activo' and 
                            p.estado_protocolo = '1' ;";
        
        //echo $consulta;
        return $this->modeloProtocolosComercializacion->ejecutarSqlNativo($consulta);
    }
}