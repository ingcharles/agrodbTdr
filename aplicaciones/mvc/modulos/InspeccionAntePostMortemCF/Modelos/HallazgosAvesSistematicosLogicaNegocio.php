<?php
 /**
 * Lógica del negocio de HallazgosAvesSistematicosModelo
 *
 * Este archivo se complementa con el archivo HallazgosAvesSistematicosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    HallazgosAvesSistematicosLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\InspeccionAntePostMortemCF\Modelos\IModelo;
 
class HallazgosAvesSistematicosLogicaNegocio implements IModelo 
{

	 private $modeloHallazgosAvesSistematicos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHallazgosAvesSistematicos = new HallazgosAvesSistematicosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HallazgosAvesSistematicosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHallazgosAvesSistematicos() != null && $tablaModelo->getIdHallazgosAvesSistematicos() > 0) {
		return $this->modeloHallazgosAvesSistematicos->actualizar($datosBd, $tablaModelo->getIdHallazgosAvesSistematicos());
		} else {
		unset($datosBd["id_hallazgos_aves_sistematicos"]);
		return $this->modeloHallazgosAvesSistematicos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHallazgosAvesSistematicos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HallazgosAvesSistematicosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHallazgosAvesSistematicos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHallazgosAvesSistematicos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHallazgosAvesSistematicos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHallazgosAvesSistematicos()
	{
	$consulta = "SELECT * FROM ".$this->modeloHallazgosAvesSistematicos->getEsquema().". hallazgos_aves_sistematicos";
		 return $this->modeloHallazgosAvesSistematicos->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'probl_respirat',
			'porcent_probl_respirat',
			'probl_nerviosos',
			'porcent_proble_nerviosos',
			'probl_digestivos',
			'porcent_probl_digestivos'
		);
		return $columnas;
	}

}
