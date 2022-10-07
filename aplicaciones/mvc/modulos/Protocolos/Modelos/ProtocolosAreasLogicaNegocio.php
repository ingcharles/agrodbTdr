<?php
/**
 * Lógica del negocio de ProtocolosAreasModelo
 *
 * Este archivo se complementa con el archivo ProtocolosAreasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ProtocolosAreasLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\Protocolos\Modelos;

use Agrodb\Protocolos\Modelos\IModelo;

class ProtocolosAreasLogicaNegocio implements IModelo
{

    private $modeloProtocolosAreas = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloProtocolosAreas = new ProtocolosAreasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ProtocolosAreasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdProtocoloArea() != null && $tablaModelo->getIdProtocoloArea() > 0) {
            return $this->modeloProtocolosAreas->actualizar($datosBd, $tablaModelo->getIdProtocoloArea());
        } else {
            unset($datosBd["id_protocolo_area"]);
            return $this->modeloProtocolosAreas->guardar($datosBd);
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
        $this->modeloProtocolosAreas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ProtocolosAreasModelo
     */
    public function buscar($id)
    {
        return $this->modeloProtocolosAreas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloProtocolosAreas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloProtocolosAreas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarProtocolosAreas()
    {
        $consulta = "SELECT * FROM " . $this->modeloProtocolosAreas->getEsquema() . ". protocolos_areas";
        return $this->modeloProtocolosAreas->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada, para obtener la información de los
     * protocolos de comercialización asignados a un producto y país de destino.
     *
     * @return array|ResultSet
     */
    public function obtenerProtocolosAreasXProductoXPais($arrayParametros) {
        
        $consulta = "SELECT 
                        	pa.*,
                        	paa.id_protocolo,
                        	paa.estado_protocolo_asignado,
                        	top.codigo,
                        	top.id_area
                        FROM g_protocolos.protocolos_areas pa
                        	INNER JOIN g_protocolos.protocolos_areas_asignados paa ON pa.id_protocolo_area = paa.id_protocolo_area
                        	INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = pa.id_tipo_operacion
                        WHERE
                        	top.codigo in (".$arrayParametros['codigoTipoOperacion'].") and
                            top.id_area = 'SV' and
                        	paa.id_protocolo = ".$arrayParametros['idProtocolo']." and
                            pa.id_area = ".$arrayParametros['idArea']." and
                        	paa.estado_protocolo_asignado in ('aprobado', 'implementacion');";
        
        //echo $consulta;
        return $this->modeloProtocolosAreas->ejecutarSqlNativo($consulta);
    }
}