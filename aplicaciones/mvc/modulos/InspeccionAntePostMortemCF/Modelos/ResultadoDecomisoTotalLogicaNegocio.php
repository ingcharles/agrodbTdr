<?php
 /**
 * Lógica del negocio de ResultadoDecomisoTotalModelo
 *
 * Este archivo se complementa con el archivo ResultadoDecomisoTotalControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ResultadoDecomisoTotalLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\InspeccionAntePostMortemCF\Modelos\IModelo;
 
class ResultadoDecomisoTotalLogicaNegocio implements IModelo 
{

	 private $modeloResultadoDecomisoTotal = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloResultadoDecomisoTotal = new ResultadoDecomisoTotalModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ResultadoDecomisoTotalModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdResultadoDecomisoTotal() != null && $tablaModelo->getIdResultadoDecomisoTotal() > 0) {
		return $this->modeloResultadoDecomisoTotal->actualizar($datosBd, $tablaModelo->getIdResultadoDecomisoTotal());
		} else {
		unset($datosBd["id_resultado_decomiso_total"]);
		return $this->modeloResultadoDecomisoTotal->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloResultadoDecomisoTotal->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ResultadoDecomisoTotalModelo
	*/
	public function buscar($id)
	{
		return $this->modeloResultadoDecomisoTotal->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloResultadoDecomisoTotal->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloResultadoDecomisoTotal->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarResultadoDecomisoTotal()
	{
	$consulta = "SELECT * FROM ".$this->modeloResultadoDecomisoTotal->getEsquema().". resultado_decomiso_total";
		 return $this->modeloResultadoDecomisoTotal->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'razon_decomiso',
			'num_canales_decomisadas',
			'peso_carne_decomisada',
			'id_detalle_post_animales'
		);
		return $columnas;
	}

}
