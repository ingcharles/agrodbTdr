<?php
 /**
 * Lógica del negocio de VehiculoTransporteAnimalesExpiradoModelo
 *
 * Este archivo se complementa con el archivo VehiculoTransporteAnimalesExpiradoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-11-22
 * @uses    VehiculoTransporteAnimalesExpiradoLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\RegistroOperador\Modelos\IModelo;
 
class VehiculoTransporteAnimalesExpiradoLogicaNegocio implements IModelo 
{

	 private $modeloVehiculoTransporteAnimalesExpirado = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloVehiculoTransporteAnimalesExpirado = new VehiculoTransporteAnimalesExpiradoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new VehiculoTransporteAnimalesExpiradoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdVehiculoTransporteAnimalesExpirado() != null && $tablaModelo->getIdVehiculoTransporteAnimalesExpirado() > 0) {
		return $this->modeloVehiculoTransporteAnimalesExpirado->actualizar($datosBd, $tablaModelo->getIdVehiculoTransporteAnimalesExpirado());
		} else {
		unset($datosBd["id_vehiculo_transporte_animales_expirado"]);
		return $this->modeloVehiculoTransporteAnimalesExpirado->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloVehiculoTransporteAnimalesExpirado->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return VehiculoTransporteAnimalesExpiradoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloVehiculoTransporteAnimalesExpirado->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloVehiculoTransporteAnimalesExpirado->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloVehiculoTransporteAnimalesExpirado->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarVehiculoTransporteAnimalesExpirado()
	{
	$consulta = "SELECT * FROM ".$this->modeloVehiculoTransporteAnimalesExpirado->getEsquema().". vehiculo_transporte_animales_expirado";
		 return $this->modeloVehiculoTransporteAnimalesExpirado->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function verificarVehiculoTransporteAnimalesExpirado($arrayParametros){
	    
	    $consulta = "SELECT 
                    	vtae.id_vehiculo_transporte_animales_expirado
                    	, vtae.id_dato_vehiculo_antiguo
                    	, vtae.id_dato_vehiculo_nuevo
                    	, vtae.fecha_registro
                    	, dtav.identificador_propietario_vehiculo
                    FROM
                    	g_operadores.vehiculo_transporte_animales_expirado vtae
                    INNER JOIN (SELECT 
                    					id_dato_vehiculo_transporte_animales 
                    					, identificador_propietario_vehiculo
                    				FROM 
                    					g_operadores.datos_vehiculo_transporte_animales) dtav ON vtae.id_dato_vehiculo_nuevo = dtav.id_dato_vehiculo_transporte_animales
                    WHERE
                    	vtae.id_dato_vehiculo_antiguo = " . $arrayParametros['id_dato_vehiculo_antiguo'] . ";";
	    
	    return $this->modeloVehiculoTransporteAnimalesExpirado->ejecutarSqlNativo($consulta);
	}

}
