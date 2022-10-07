<?php
 /**
 * Lógica del negocio de ExamenPostEctoparasitosModelo
 *
 * Este archivo se complementa con el archivo ExamenPostEctoparasitosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    ExamenPostEctoparasitosLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
  use Agrodb\InspeccionAntePostMortemCF\Modelos\IModelo;
 
class ExamenPostEctoparasitosLogicaNegocio implements IModelo 
{

	 private $modeloExamenPostEctoparasitos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloExamenPostEctoparasitos = new ExamenPostEctoparasitosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ExamenPostEctoparasitosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdExamenPostEctoparasitos() != null && $tablaModelo->getIdExamenPostEctoparasitos() > 0) {
		return $this->modeloExamenPostEctoparasitos->actualizar($datosBd, $tablaModelo->getIdExamenPostEctoparasitos());
		} else {
		unset($datosBd["id_examen_post_ectoparasitos"]);
		return $this->modeloExamenPostEctoparasitos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloExamenPostEctoparasitos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ExamenPostEctoparasitosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloExamenPostEctoparasitos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloExamenPostEctoparasitos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloExamenPostEctoparasitos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarExamenPostEctoparasitos()
	{
	$consulta = "SELECT * FROM ".$this->modeloExamenPostEctoparasitos->getEsquema().". examen_post_ectoparasitos";
		 return $this->modeloExamenPostEctoparasitos->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'id_detalle_post_animales',
			'ectoparasitos_presencia',
			'ectoparasitos_localizacion',
			'ectoparasitos_num_afectados'
		);
		return $columnas;
	}

}
