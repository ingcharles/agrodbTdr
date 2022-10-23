<?php
 /**
 * Lógica del negocio de RevisionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo RevisionCronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-10-22
 * @uses    RevisionCronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\VacacionesPermisos\Modelos\IModelo;
 
class RevisionCronogramaVacacionesLogicaNegocio implements IModelo 
{

	 private $modeloRevisionCronogramaVacaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new RevisionCronogramaVacacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRevisionCronogramaVacacion() != null && $tablaModelo->getIdRevisionCronogramaVacacion() > 0) {
		return $this->modeloRevisionCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdRevisionCronogramaVacacion());
		} else {
		unset($datosBd["id_revision_cronograma_vacacion"]);
		return $this->modeloRevisionCronogramaVacaciones->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloRevisionCronogramaVacaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return RevisionCronogramaVacacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloRevisionCronogramaVacaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloRevisionCronogramaVacaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloRevisionCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarRevisionCronogramaVacaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloRevisionCronogramaVacaciones->getEsquema().". revision_cronograma_vacaciones";
		 return $this->modeloRevisionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

}
