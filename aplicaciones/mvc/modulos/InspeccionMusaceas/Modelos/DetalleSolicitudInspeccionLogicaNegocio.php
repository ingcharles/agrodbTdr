<?php
 /**
 * Lógica del negocio de DetalleSolicitudInspeccionModelo
 *
 * Este archivo se complementa con el archivo DetalleSolicitudInspeccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleSolicitudInspeccionLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\InspeccionMusaceas\Modelos\IModelo;
 
class DetalleSolicitudInspeccionLogicaNegocio implements IModelo 
{

	 private $modeloDetalleSolicitudInspeccion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleSolicitudInspeccionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleSolicitudInspeccion() != null && $tablaModelo->getIdDetalleSolicitudInspeccion() > 0) {
		return $this->modeloDetalleSolicitudInspeccion->actualizar($datosBd, $tablaModelo->getIdDetalleSolicitudInspeccion());
		} else {
		unset($datosBd["id_detalle_solicitud_inspeccion"]);
		return $this->modeloDetalleSolicitudInspeccion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleSolicitudInspeccion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleSolicitudInspeccionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleSolicitudInspeccion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleSolicitudInspeccion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleSolicitudInspeccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleSolicitudInspeccion()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleSolicitudInspeccion->getEsquema().". detalle_solicitud_inspeccion";
		 return $this->modeloDetalleSolicitudInspeccion->ejecutarSqlNativo($consulta);
	}
	public function columnas()
	{
	    $columnas = array(
	        'razon_social',
	        'area',
	        'num_cajas',
	        'id_solicitud_inspeccion',
	        'provincia',
	        'codigo_area',
	        'codigo_mag',
	        'identificador_operador'
	    );
	    return $columnas;
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function sumarCajas($idSolcitudInspeccion)
	{
	    $consulta = "
                    SELECT 
                           sum(num_cajas) as total
	                FROM 
                            g_inspeccion_musaceas.detalle_solicitud_inspeccion
	               WHERE 
                            id_solicitud_inspeccion=".$idSolcitudInspeccion.";";
	    return $this->modeloDetalleSolicitudInspeccion->ejecutarSqlNativo($consulta);
	}
}
