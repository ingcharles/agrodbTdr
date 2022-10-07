<?php
/**
 * Lógica del negocio de ResponsablesModelo
 *
 * Este archivo se complementa con el archivo ResponsablesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-07
 * @uses    ResponsablesLogicaNegocio
 * @package Estructura
 * @subpackage Modelos
 */
namespace Agrodb\Estructura\Modelos;

use Agrodb\Estructura\Modelos\IModelo;

class ResponsablesLogicaNegocio implements IModelo
{

    private $modeloResponsables = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloResponsables = new ResponsablesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ResponsablesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdArea() != null && $tablaModelo->getIdArea() > 0) {
            return $this->modeloResponsables->actualizar($datosBd, $tablaModelo->getIdArea());
        } else {
            unset($datosBd["id_area"]);
            return $this->modeloResponsables->guardar($datosBd);
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
        $this->modeloResponsables->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ResponsablesModelo
     */
    public function buscar($id)
    {
        return $this->modeloResponsables->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloResponsables->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloResponsables->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarResponsables()
    {
        $consulta = "SELECT * FROM " . $this->modeloResponsables->getEsquema() . ". responsables";
        return $this->modeloResponsables->ejecutarSqlNativo($consulta);
    }
    
    /**
    * Ejecuta una consulta(SQL) personalizada .
    * Buscar solicitudes usando filtros.
    *
    * @return array|ResultSet
    */
    public function buscarResponsableProvincial($nombreProvincia)
    {
        $consulta = "select
                    	f.identificador,
                    	fe.nombre,
                    	fe.apellido,
                        fe.nombre ||' '|| fe.apellido as nombre_director,
                        ar.nombre as nombre_area,
                        ar.id_area
                    from
                    	g_estructura.responsables f,
                    	g_uath.ficha_empleado fe,
                        g_estructura.area ar
                    where
                        ar.id_area = f.id_area and
                    	f.estado = 1 and
                    	f.responsable is true and
                    	activo = 1 and
                    	f.identificador = fe.identificador and
                    	f.id_area = (select
                    					a.id_area
                    				from
                    					g_estructura.area a
                    				where
                    					nombre like '%$nombreProvincia%' and
                    					estado =1 and
                    					categoria_area=3)";
        
        return $this->modeloResponsables->ejecutarSqlNativo($consulta);
    }
}