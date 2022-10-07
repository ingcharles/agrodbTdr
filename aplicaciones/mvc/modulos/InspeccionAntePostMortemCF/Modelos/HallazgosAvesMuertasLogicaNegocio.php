<?php
 /**
 * Lógica del negocio de HallazgosAvesMuertasModelo
 *
 * Este archivo se complementa con el archivo HallazgosAvesMuertasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesMuertasLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
 
class HallazgosAvesMuertasLogicaNegocio implements IModelo 
{

	 private $modeloHallazgosAvesMuertas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHallazgosAvesMuertas = new HallazgosAvesMuertasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HallazgosAvesMuertasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHallazgosAvesMuertas() != null && $tablaModelo->getIdHallazgosAvesMuertas() > 0) {
		return $this->modeloHallazgosAvesMuertas->actualizar($datosBd, $tablaModelo->getIdHallazgosAvesMuertas());
		} else {
		unset($datosBd["id_hallazgos_aves_muertas"]);
		return $this->modeloHallazgosAvesMuertas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHallazgosAvesMuertas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HallazgosAvesMuertasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHallazgosAvesMuertas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHallazgosAvesMuertas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHallazgosAvesMuertas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHallazgosAvesMuertas()
	{
	$consulta = "SELECT * FROM ".$this->modeloHallazgosAvesMuertas->getEsquema().". hallazgos_aves_muertas";
		 return $this->modeloHallazgosAvesMuertas->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'aves_muertas',
			'porcent_aves_muertas',
			'causa_probable'
		);
		return $columnas;
	}

}
