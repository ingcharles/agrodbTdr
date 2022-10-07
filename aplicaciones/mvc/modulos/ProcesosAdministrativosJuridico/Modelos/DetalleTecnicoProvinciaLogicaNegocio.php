<?php
 /**
 * Lógica del negocio de DetalleTecnicoProvinciaModelo
 *
 * Este archivo se complementa con el archivo DetalleTecnicoProvinciaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    DetalleTecnicoProvinciaLogicaNegocio
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  
  use Agrodb\ProcesosAdministrativosJuridico\Modelos\IModelo;
 
class DetalleTecnicoProvinciaLogicaNegocio implements IModelo 
{

	 private $modeloDetalleTecnicoProvincia = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleTecnicoProvincia = new DetalleTecnicoProvinciaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleTecnicoProvinciaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleTecnicoProvincia() != null && $tablaModelo->getIdDetalleTecnicoProvincia() > 0) {
		return $this->modeloDetalleTecnicoProvincia->actualizar($datosBd, $tablaModelo->getIdDetalleTecnicoProvincia());
		} else {
		unset($datosBd["id_detalle_tecnico_provincia"]);
		return $this->modeloDetalleTecnicoProvincia->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleTecnicoProvincia->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleTecnicoProvinciaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleTecnicoProvincia->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleTecnicoProvincia->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleTecnicoProvincia->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleTecnicoProvincia()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleTecnicoProvincia->getEsquema().". detalle_tecnico_provincia";
		 return $this->modeloDetalleTecnicoProvincia->ejecutarSqlNativo($consulta);
	}

}
