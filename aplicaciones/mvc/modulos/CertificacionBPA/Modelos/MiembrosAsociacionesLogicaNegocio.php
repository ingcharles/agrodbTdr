<?php
/**
 * Lógica del negocio de MiembrosAsociacionesModelo
 *
 * Este archivo se complementa con el archivo MiembrosAsociacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    MiembrosAsociacionesLogicaNegocio
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\CertificacionBPA\Modelos\IModelo;

class MiembrosAsociacionesLogicaNegocio implements IModelo
{

    private $modeloMiembrosAsociaciones = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloMiembrosAsociaciones = new MiembrosAsociacionesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new MiembrosAsociacionesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        if ($tablaModelo->getIdMiembroAsociacion() != null && $tablaModelo->getIdMiembroAsociacion() > 0) {
            return $this->modeloMiembrosAsociaciones->actualizar($datosBd, $tablaModelo->getIdMiembroAsociacion());
        } else {
            unset($datosBd["id_miembro_asociacion"]);
            return $this->modeloMiembrosAsociaciones->guardar($datosBd);
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
        $this->modeloMiembrosAsociaciones->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return MiembrosAsociacionesModelo
     */
    public function buscar($id)
    {
        return $this->modeloMiembrosAsociaciones->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloMiembrosAsociaciones->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloMiembrosAsociaciones->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarMiembrosAsociaciones()
    {
        $consulta = "SELECT * FROM " . $this->modeloMiembrosAsociaciones->getEsquema() . ". miembros_asociaciones";
        return $this->modeloMiembrosAsociaciones->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los miembros de una asociación por el 
     * identificador del registro (RUC).
     *
     * @return array|ResultSet
     */
    public function obtenerMiembrosAsociacionXRuc($identificador) {
        
        $consulta = "   SELECT
                        	ma.identificador_miembro
                        FROM
                        	g_certificacion_bpa.miembros_asociaciones ma
                        	INNER JOIN g_certificacion_bpa.asociaciones a ON a.id_asociacion = ma.id_asociacion
                        WHERE
                        	a.identificador = '$identificador' and
                        	a.estado = 'Activo';";
        
        return $this->modeloMiembrosAsociaciones->ejecutarSqlNativo($consulta);
        
    }
        
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener todos los productos de los miembros de una asociación por el
     * id de la asociacion.
     *
     * @return array|ResultSet
     */
    public function obtenerProductosMiembrosAsociacionPorIdAsociacion($idAsociacion) {
        
        $consulta = "SELECT
                        ma.id_asociacion
                        , string_agg(distinct p.nombre_comun, ', ') as productos_miembros_asociacion
                     FROM
                        g_certificacion_bpa.miembros_asociaciones ma
                        INNER JOIN g_operadores.operaciones op ON ma.identificador_miembro = op.identificador_operador
                        INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                     WHERE
                        id_asociacion = " . $idAsociacion . "
                     GROUP BY ma.id_asociacion;";
        
        return $this->modeloMiembrosAsociaciones->ejecutarSqlNativo($consulta);
        
    }
    
}