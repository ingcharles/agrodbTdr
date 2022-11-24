<?php
 /**
 * Lógica del negocio de ConfiguracionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo ConfiguracionCronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-11-21
 * @uses    ConfiguracionCronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\VacacionesPermisos\Modelos\IModelo;
 
class ConfiguracionCronogramaVacacionesLogicaNegocio implements IModelo 
{

	 private $modeloConfiguracionCronogramaVacaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ConfiguracionCronogramaVacacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdConfiguracionCronogramaVacacion() != null && $tablaModelo->getIdConfiguracionCronogramaVacacion() > 0) {
		return $this->modeloConfiguracionCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdConfiguracionCronogramaVacacion());
		} else {
		unset($datosBd["id_configuracion_cronograma_vacacion"]);
		return $this->modeloConfiguracionCronogramaVacaciones->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloConfiguracionCronogramaVacaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ConfiguracionCronogramaVacacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	public function buscarEstadosConfiguracionCronogramaVacaciones()
	{
		$consulta = "SELECT DISTINCT estado_configuracion_cronograma_vacacion as estado FROM ".$this->modeloConfiguracionCronogramaVacaciones->getEsquema().". configuracion_cronograma_vacaciones";
		 return $this->modeloConfiguracionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}
	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarConfiguracionCronogramaVacaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloConfiguracionCronogramaVacaciones->getEsquema().". configuracion_cronograma_vacaciones";
		 return $this->modeloConfiguracionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

}
