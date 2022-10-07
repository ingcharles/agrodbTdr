<?php
 /**
 * Lógica del negocio de HallazgosAvesExternasModelo
 *
 * Este archivo se complementa con el archivo HallazgosAvesExternasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesExternasLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
 
class HallazgosAvesExternasLogicaNegocio implements IModelo 
{

	 private $modeloHallazgosAvesExternas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHallazgosAvesExternas = new HallazgosAvesExternasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HallazgosAvesExternasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHallazgosAvesExternas() != null && $tablaModelo->getIdHallazgosAvesExternas() > 0) {
		return $this->modeloHallazgosAvesExternas->actualizar($datosBd, $tablaModelo->getIdHallazgosAvesExternas());
		} else {
		unset($datosBd["id_hallazgos_aves_externas"]);
		return $this->modeloHallazgosAvesExternas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHallazgosAvesExternas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HallazgosAvesExternasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHallazgosAvesExternas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHallazgosAvesExternas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHallazgosAvesExternas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHallazgosAvesExternas()
	{
	$consulta = "SELECT * FROM ".$this->modeloHallazgosAvesExternas->getEsquema().". hallazgos_aves_externas";
		 return $this->modeloHallazgosAvesExternas->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'cabeza_hinchada',
			'porcent_cabeza_hinchada',
			'plumas_erizadas',
			'porcent_plumas_erizadas'
		);
		return $columnas;
	}

}
