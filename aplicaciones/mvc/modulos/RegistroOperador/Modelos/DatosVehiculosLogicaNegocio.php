<?php
 /**
 * Lógica del negocio de CodigosPoaModelo
 *
 * Este archivo se complementa con el archivo CodigosPoaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-01-10
 * @uses    CodigosPoaLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\RegistroOperador\Modelos\IModelo;
 
class DatosVehiculosLogicaNegocio implements IModelo 
{

	 private $modeloDatosVehiculos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDatosVehiculos = new DatosVehiculosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DatosVehiculosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDatoVehiculo() != null && $tablaModelo->getIdDatoVehiculo() > 0) {
		return $this->modeloDatosVehiculos->actualizar($datosBd, $tablaModelo->getIdDatoVehiculo());
		} else {
		unset($datosBd["id_dato_vehiculo"]);
		return $this->modeloDatosVehiculos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDatosVehiculos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DatosVehiculosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDatosVehiculos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDatosVehiculos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDatosVehiculos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDatosVehiculos()
	{
	$consulta = "SELECT * FROM ".$this->modeloDatosVehiculos->getEsquema().". datos_vehiculos";
		 return $this->modeloDatosVehiculos->ejecutarSqlNativo($consulta);
	}

	public function buscarDatosVehiculosActivo($arrayParametros)
	{
		$consulta = "SELECT * from g_operadores.datos_vehiculos dv
			INNER JOIN
			(SELECT max(id_dato_Vehiculo) as id_dato_vehiculo 
			from g_operadores.datos_vehiculos 
			where placa_vehiculo = '" . $arrayParametros["placa_vehiculo"] . "'
			and id_tipo_operacion = 101
			and estado_dato_vehiculo = 'activo') tdv
			ON tdv.id_dato_vehiculo = dv.id_dato_vehiculo";
			return $this->modeloDatosVehiculos->ejecutarSqlNativo($consulta);
	}

}
