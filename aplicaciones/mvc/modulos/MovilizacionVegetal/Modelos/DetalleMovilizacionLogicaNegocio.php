<?php
 /**
 * Lógica del negocio de DetalleMovilizacionModelo
 *
 * Este archivo se complementa con el archivo DetalleMovilizacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    DetalleMovilizacionLogicaNegocio
 * @package MovilizacionVegetal
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionVegetal\Modelos;
  
  use Agrodb\MovilizacionVegetal\Modelos\IModelo;
 
class DetalleMovilizacionLogicaNegocio implements IModelo 
{

	 private $modeloDetalleMovilizacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleMovilizacion = new DetalleMovilizacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleMovilizacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		
		//print_r($datosBd);
		if ($tablaModelo->getIdDetalleMovilizacion() != null && $tablaModelo->getIdDetalleMovilizacion() > 0) {
		      return $this->modeloDetalleMovilizacion->actualizar($datosBd, $tablaModelo->getIdDetalleMovilizacion());
		} else {
    		unset($datosBd["id_detalle_movilizacion"]);
    		return $this->modeloDetalleMovilizacion->guardar($datosBd);
	    }
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleMovilizacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleMovilizacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleMovilizacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleMovilizacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleMovilizacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleMovilizacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleMovilizacion->getEsquema().". detalle_movilizacion";
		 return $this->modeloDetalleMovilizacion->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar Información de detalles de movilización creados por certificado con información completa.
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleXMovilizacion($idMovilizacion)
	{
	    $consulta = "SELECT
                    	dm.*
                     FROM
                        g_movilizacion_vegetal.detalle_movilizacion dm
                     WHERE
                    	dm.id_movilizacion = ". $idMovilizacion .";";
	    
	    $detalleMovilizacion = $this->modeloDetalleMovilizacion->ejecutarSqlNativo($consulta);
	    
	    return $detalleMovilizacion;
	}
}
