<?php
 /**
 * Lógica del negocio de DatosVehiculoTransporteAnimalesModelo
 *
 * Este archivo se complementa con el archivo DatosVehiculoTransporteAnimalesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-11-22
 * @uses    DatosVehiculoTransporteAnimalesLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\RegistroOperador\Modelos\IModelo;
 
class DatosVehiculoTransporteAnimalesLogicaNegocio implements IModelo 
{

	 private $modeloDatosVehiculoTransporteAnimales = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDatosVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DatosVehiculoTransporteAnimalesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDatoVehiculoTransporteAnimales() != null && $tablaModelo->getIdDatoVehiculoTransporteAnimales() > 0) {
		return $this->modeloDatosVehiculoTransporteAnimales->actualizar($datosBd, $tablaModelo->getIdDatoVehiculoTransporteAnimales());
		} else {
		unset($datosBd["id_dato_vehiculo_transporte_animales"]);
		return $this->modeloDatosVehiculoTransporteAnimales->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDatosVehiculoTransporteAnimales->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DatosVehiculoTransporteAnimalesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDatosVehiculoTransporteAnimales->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDatosVehiculoTransporteAnimales->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDatosVehiculoTransporteAnimales->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDatosVehiculoTransporteAnimales()
	{
	$consulta = "SELECT * FROM ".$this->modeloDatosVehiculoTransporteAnimales->getEsquema().". datos_vehiculo_transporte_animales";
		 return $this->modeloDatosVehiculoTransporteAnimales->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDatosVehiculoTransporteAnimalesPorIdOperadorTipoOperacion($arrayParametros){
	    
	    $consulta = "SELECT 
                        id_dato_vehiculo_transporte_animales
                        , id_area
                        , id_tipo_operacion
                        , id_operador_tipo_operacion
                        , id_historial_operacion
                        , codigo_certificado
                        , placa_vehiculo
                        , identificador_propietario_vehiculo
                        , marca_vehiculo
                        , modelo_vehiculo
                        , anio_vehiculo
                        , color_vehiculo
                        , clase_vehiculo
                        , tipo_vehiculo
                        , tamanio_contenedor_vehiculo
                        , caracteristica_contenedor_vehiculo
                        , fecha_modificacion
                        , fecha_creacion
                        , fecha_aprobacion
                        , estado_vehiculo
                    FROM 
                        g_operadores.datos_vehiculo_transporte_animales
                    WHERE
                        id_operador_tipo_operacion =  " . $arrayParametros['id_operador_tipo_operacion'] . ";";
	    
	    return $this->modeloDatosVehiculoTransporteAnimales->ejecutarSqlNativo($consulta);
	}

}
