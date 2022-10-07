<?php
/**
 * Lógica del negocio de AlertasModelo
 *
 * Este archivo se complementa con el archivo AlertasControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses AlertasLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
namespace Agrodb\AplicacionMovilExternos\Modelos;

use Agrodb\AplicacionMovilExternos\Modelos\IModelo;

class AlertasLogicaNegocio implements IModelo{

	private $modeloAlertas = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAlertas = new AlertasModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AlertasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAlerta() != null && $tablaModelo->getIdAlerta() > 0){
			return $this->modeloAlertas->actualizar($datosBd, $tablaModelo->getIdAlerta());
		}else{
			unset($datosBd["id_alerta"]);
			unset($datosBd["estado"]);
			return $this->modeloAlertas->guardar($datosBd);
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
		$this->modeloAlertas->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AlertasModelo
	 */
	public function buscar($id){
		return $this->modeloAlertas->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAlertas->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAlertas->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAlertas(){
		$consulta = "SELECT * FROM " . $this->modeloAlertas->getEsquema() . ". alertas";
		return $this->modeloAlertas->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta consulta(SQL), para la obtención de alerta en base a una carga de offset y fectch .
	 *
	 * @return array|ResultSet
	 */
	public function obtenerAlertasOffset($arrayParametros) {
		
		$consulta = "SELECT
						*
					FROM
						a_movil_externos.alertas
					WHERE
						estado = 'activo'
					ORDER BY
						fecha_alerta desc
						offset " . $arrayParametros['incremento'] ." row
						fetch next 10 rows only;";
		
		return $this->modeloAlertas->ejecutarSqlNativo($consulta);
		
	}

	/**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar alertas usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarAlertaXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['titulo']) && ($arrayParametros['titulo'] != '')) {
            $busqueda .= "and upper(titulo) ilike upper('%" . $arrayParametros['titulo'] . "%')";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and fecha_alerta >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and fecha_alerta <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
		}
		
		$consulta= "SELECT 
						id_alerta, titulo, alerta, fecha_alerta, estado
					FROM 
						a_movil_externos.alertas
					WHERE								
						estado = '" . $arrayParametros['estado'] . "'" . $busqueda . "
						;";
        
        return $this->modeloAlertas->ejecutarSqlNativo($consulta);
    }
}
