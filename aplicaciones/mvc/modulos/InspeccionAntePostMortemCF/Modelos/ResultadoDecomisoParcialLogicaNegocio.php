<?php
 /**
 * Lógica del negocio de ResultadoDecomisoParcialModelo
 *
 * Este archivo se complementa con el archivo ResultadoDecomisoParcialControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ResultadoDecomisoParcialLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
 
class ResultadoDecomisoParcialLogicaNegocio implements IModelo 
{

	 private $modeloResultadoDecomisoParcial = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloResultadoDecomisoParcial = new ResultadoDecomisoParcialModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ResultadoDecomisoParcialModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdResultadoDecomisoParcial() != null && $tablaModelo->getIdResultadoDecomisoParcial() > 0) {
		return $this->modeloResultadoDecomisoParcial->actualizar($datosBd, $tablaModelo->getIdResultadoDecomisoParcial());
		} else {
		unset($datosBd["id_resultado_decomiso_parcial"]);
		return $this->modeloResultadoDecomisoParcial->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloResultadoDecomisoParcial->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ResultadoDecomisoParcialModelo
	*/
	public function buscar($id)
	{
		return $this->modeloResultadoDecomisoParcial->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloResultadoDecomisoParcial->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloResultadoDecomisoParcial->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarResultadoDecomisoParcial()
	{
	$consulta = "SELECT * FROM ".$this->modeloResultadoDecomisoParcial->getEsquema().". resultado_decomiso_parcial";
		 return $this->modeloResultadoDecomisoParcial->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'id_detalle_post_animales',
			'razon_decomiso',
			'num_canales_decomisadas',
			'peso_carne_aprobada',
			'peso_carne_decomisada'
			
		);
		return $columnas;
	}
}
