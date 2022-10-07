<?php
 /**
 * Lógica del negocio de HallazgosAnimalesClinicosModelo
 *
 * Este archivo se complementa con el archivo HallazgosAnimalesClinicosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAnimalesClinicosLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\InspeccionAntePostMortemCF\Modelos\IModelo;
 
class HallazgosAnimalesClinicosLogicaNegocio implements IModelo 
{

	 private $modeloHallazgosAnimalesClinicos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHallazgosAnimalesClinicos = new HallazgosAnimalesClinicosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HallazgosAnimalesClinicosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHallazgosAnimalesClinicos() != null && $tablaModelo->getIdHallazgosAnimalesClinicos() > 0) {
		return $this->modeloHallazgosAnimalesClinicos->actualizar($datosBd, $tablaModelo->getIdHallazgosAnimalesClinicos());
		} else {
		unset($datosBd["id_hallazgos_animales_clinicos"]);
		return $this->modeloHallazgosAnimalesClinicos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHallazgosAnimalesClinicos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HallazgosAnimalesClinicosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHallazgosAnimalesClinicos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHallazgosAnimalesClinicos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHallazgosAnimalesClinicos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHallazgosAnimalesClinicos()
	{
	$consulta = "SELECT * FROM ".$this->modeloHallazgosAnimalesClinicos->getEsquema().". hallazgos_animales_clinicos";
		 return $this->modeloHallazgosAnimalesClinicos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'num_animales_nerviosos',
			'num_animales_digestivo',
			'num_animales_respiratorio',
			'num_animales_vesicular',
			'num_animales_reproductivo'
		);
		return $columnas;
	}

}
