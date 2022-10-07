<?php
 /**
 * Lógica del negocio de HallazgosAnimalesLocomocionModelo
 *
 * Este archivo se complementa con el archivo HallazgosAnimalesLocomocionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAnimalesLocomocionLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
 
 
class HallazgosAnimalesLocomocionLogicaNegocio implements IModelo 
{

	 private $modeloHallazgosAnimalesLocomocion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHallazgosAnimalesLocomocion = new HallazgosAnimalesLocomocionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HallazgosAnimalesLocomocionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHallazgosAnimalesLocomocion() != null && $tablaModelo->getIdHallazgosAnimalesLocomocion() > 0) {
		return $this->modeloHallazgosAnimalesLocomocion->actualizar($datosBd, $tablaModelo->getIdHallazgosAnimalesLocomocion());
		} else {
		unset($datosBd["id_hallazgos_animales_locomocion"]);
		return $this->modeloHallazgosAnimalesLocomocion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHallazgosAnimalesLocomocion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HallazgosAnimalesLocomocionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHallazgosAnimalesLocomocion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHallazgosAnimalesLocomocion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHallazgosAnimalesLocomocion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHallazgosAnimalesLocomocion()
	{
	$consulta = "SELECT * FROM ".$this->modeloHallazgosAnimalesLocomocion->getEsquema().". hallazgos_animales_locomocion";
		 return $this->modeloHallazgosAnimalesLocomocion->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'num_animales_cojera',
			'num_animales_ambulatorios'
		);
		return $columnas;
	}
}
