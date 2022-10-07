<?php
 /**
 * Lógica del negocio de HallazgosAvesCaractModelo
 *
 * Este archivo se complementa con el archivo HallazgosAvesCaractControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesCaractLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
 
class HallazgosAvesCaractLogicaNegocio implements IModelo 
{

	 private $modeloHallazgosAvesCaract = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHallazgosAvesCaract = new HallazgosAvesCaractModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HallazgosAvesCaractModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHallazgosAvesCaract() != null && $tablaModelo->getIdHallazgosAvesCaract() > 0) {
		return $this->modeloHallazgosAvesCaract->actualizar($datosBd, $tablaModelo->getIdHallazgosAvesCaract());
		} else {
		unset($datosBd["id_hallazgos_aves_caract"]);
		return $this->modeloHallazgosAvesCaract->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHallazgosAvesCaract->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HallazgosAvesCaractModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHallazgosAvesCaract->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHallazgosAvesCaract->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHallazgosAvesCaract->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHallazgosAvesCaract()
	{
	$consulta = "SELECT * FROM ".$this->modeloHallazgosAvesCaract->getEsquema().". hallazgos_aves_caract";
		 return $this->modeloHallazgosAvesCaract->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'decaidas',
			'porcent_decaidas',
			'num_traumas',
			'porcent_traumas'
		);
		return $columnas;
	}

}
