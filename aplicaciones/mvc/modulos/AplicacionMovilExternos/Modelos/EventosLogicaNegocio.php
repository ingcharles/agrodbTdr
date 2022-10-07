<?php
/**
 * Lógica del negocio de EventosModelo
 *
 * Este archivo se complementa con el archivo EventosControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-07-24
 * @uses EventosLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
namespace Agrodb\AplicacionMovilExternos\Modelos;

use Agrodb\AplicacionMovilExternos\Modelos\IModelo;

class EventosLogicaNegocio implements IModelo{

	private $modeloEventos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloEventos = new EventosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new EventosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEvento() != null && $tablaModelo->getIdEvento() > 0){
			return $this->modeloEventos->actualizar($datosBd, $tablaModelo->getIdEvento());
		}else{
			unset($datosBd["id_evento"]);
			return $this->modeloEventos->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloEventos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return EventosModelo
	 */
	public function buscar($id){
		return $this->modeloEventos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloEventos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloEventos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarEventos(){
		$consulta = "SELECT * FROM " . $this->modeloEventos->getEsquema() . ". eventos";
		return $this->modeloEventos->ejecutarSqlNativo($consulta);
	}

	/**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar eventos usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarEventoXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['nombre']) && ($arrayParametros['nombre'] != '')) {
            $busqueda .= "and upper(nombre_evento) ilike upper('%" . $arrayParametros['nombre'] . "%')";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and fecha_evento >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and fecha_evento <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
		}
		
		$consulta= "SELECT 
						id_evento, nombre_evento, estado, fecha_evento, ruta_imagen, 
						descripcion, ruta_recurso
					FROM 
						a_movil_externos.eventos
					WHERE						
						estado = '" . $arrayParametros['estado'] . "'" . $busqueda . "
						;";
        
        return $this->modeloEventos->ejecutarSqlNativo($consulta);
    }
}
