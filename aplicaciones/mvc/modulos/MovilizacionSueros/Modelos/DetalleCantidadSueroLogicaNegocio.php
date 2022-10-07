<?php
/**
 * Lógica del negocio de DetalleCantidadSueroModelo
 *
 * Este archivo se complementa con el archivo DetalleCantidadSueroControlador.
 *
 * @author AGROCALIDAD
 * @date    2018-11-21
 * @uses DetalleCantidadSueroLogicaNegocio
 * @package Movilizacion_suero
 * @subpackage Modelos
 */
namespace Agrodb\MovilizacionSueros\Modelos;
use Agrodb\MovilizacionSueros\Modelos\IModelo;


class DetalleCantidadSueroLogicaNegocio implements IModelo{

	private $modeloDetalleCantidadSuero = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleCantidadSuero = new DetalleCantidadSueroModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleCantidadSueroModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleConsumoSuero() != null && $tablaModelo->getIdDetalleConsumoSuero() > 0){
			return $this->modeloDetalleCantidadSuero->actualizar($datosBd, $tablaModelo->getIdDetalleConsumoSuero());
		}else{
			unset($datosBd["id_detalle_consumo_suero"]);
			return $this->modeloDetalleCantidadSuero->guardar($datosBd);
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
		$this->modeloDetalleCantidadSuero->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleCantidadSueroModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleCantidadSuero->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleCantidadSuero->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleCantidadSuero->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleCantidadSuero(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleCantidadSuero->getEsquema() . ". detalle_cantidad_suero";
		return $this->modeloDetalleCantidadSuero->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el el detalle de la cantidad de suero
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDetalleCantidadSuero($arrayParametros){
		
		$consulta = "SELECT 
							p.id_produccion, cantidad_suero_restante, dcs.id_detalle_consumo_suero
  					FROM 
							g_movilizacion_suero.produccion p 
       						INNER JOIN g_movilizacion_suero.detalle_cantidad_suero dcs ON p.id_produccion = dcs.id_produccion
  					WHERE 
       						p.estado in ('creado') and 
							dcs.estado in ('creado','pendiente') and 
							p.id_producto_suero = " . $arrayParametros['idProductoSuero'] . " and 
							p.identificador = '". $arrayParametros['identificador_operador'] ."';";

		$resultado = $this->modeloDetalleCantidadSuero->ejecutarSqlNativo($consulta);
		return $resultado;
	}
}
