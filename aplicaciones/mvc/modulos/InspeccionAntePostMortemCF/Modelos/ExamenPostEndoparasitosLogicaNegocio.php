<?php
 /**
 * Lógica del negocio de ExamenPostEndoparasitosModelo
 *
 * Este archivo se complementa con el archivo ExamenPostEndoparasitosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ExamenPostEndoparasitosLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\InspeccionAntePostMortemCF\Modelos\IModelo;
 
class ExamenPostEndoparasitosLogicaNegocio implements IModelo 
{

	 private $modeloExamenPostEndoparasitos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloExamenPostEndoparasitos = new ExamenPostEndoparasitosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ExamenPostEndoparasitosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdExamenPostEndoparasitos() != null && $tablaModelo->getIdExamenPostEndoparasitos() > 0) {
		return $this->modeloExamenPostEndoparasitos->actualizar($datosBd, $tablaModelo->getIdExamenPostEndoparasitos());
		} else {
		unset($datosBd["id_examen_post_endoparasitos"]);
		return $this->modeloExamenPostEndoparasitos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloExamenPostEndoparasitos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ExamenPostEndoparasitosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloExamenPostEndoparasitos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloExamenPostEndoparasitos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloExamenPostEndoparasitos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarExamenPostEndoparasitos()
	{
	$consulta = "SELECT * FROM ".$this->modeloExamenPostEndoparasitos->getEsquema().". examen_post_endoparasitos";
		 return $this->modeloExamenPostEndoparasitos->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'id_detalle_post_animales',
			'endoparasitos_presencia',
			'endoparasitos_localizacion',
			'endoparasitos_num_afectados'
		);
		return $columnas;
	}

}
