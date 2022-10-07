<?php
 /**
 * Lógica del negocio de HallazgosAnimalesMuertosModelo
 *
 * Este archivo se complementa con el archivo HallazgosAnimalesMuertosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAnimalesMuertosLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
 
class HallazgosAnimalesMuertosLogicaNegocio implements IModelo 
{

	 private $modeloHallazgosAnimalesMuertos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHallazgosAnimalesMuertos = new HallazgosAnimalesMuertosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HallazgosAnimalesMuertosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHallazgosAnimalesMuertos() != null && $tablaModelo->getIdHallazgosAnimalesMuertos() > 0) {
		return $this->modeloHallazgosAnimalesMuertos->actualizar($datosBd, $tablaModelo->getIdHallazgosAnimalesMuertos());
		} else {
		unset($datosBd["id_hallazgos_animales_muertos"]);
		return $this->modeloHallazgosAnimalesMuertos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHallazgosAnimalesMuertos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HallazgosAnimalesMuertosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHallazgosAnimalesMuertos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHallazgosAnimalesMuertos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHallazgosAnimalesMuertos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHallazgosAnimalesMuertos()
	{
	$consulta = "SELECT * FROM ".$this->modeloHallazgosAnimalesMuertos->getEsquema().". hallazgos_animales_muertos";
		 return $this->modeloHallazgosAnimalesMuertos->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'num_animales_muertos',
			'causa_probable',
			'decomiso',
			'aprovechamiento');
		return $columnas;
	}

}
