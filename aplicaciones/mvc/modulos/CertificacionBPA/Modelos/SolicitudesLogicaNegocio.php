<?php
 /**
 * Lógica del negocio de SolicitudesModelo
 *
 * Este archivo se complementa con el archivo SolicitudesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    SolicitudesLogicaNegocio
 * @package CertificacionBPA
 * @subpackage Modelos
 */
  namespace Agrodb\CertificacionBPA\Modelos;
  
  use Agrodb\CertificacionBPA\Modelos\IModelo;
 
class SolicitudesLogicaNegocio implements IModelo 
{

	 private $modeloSolicitudes = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSolicitudes = new SolicitudesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SolicitudesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		
		if ($tablaModelo->getIdSolicitud() != null && $tablaModelo->getIdSolicitud() > 0) {
		    return $this->modeloSolicitudes->actualizar($datosBd, $tablaModelo->getIdSolicitud());
		} else {
    		unset($datosBd["id_solicitud"]);
    		return $this->modeloSolicitudes->guardar($datosBd);
    	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSolicitudes->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SolicitudesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSolicitudes->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSolicitudes->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSolicitudes->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSolicitudes()
	{
	$consulta = "SELECT * FROM ".$this->modeloSolicitudes->getEsquema().". solicitudes";
		 return $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
	}

	public function buscarEstadoSolicitudes($identificador)
	{
	    $consulta = "   SELECT 
                            DISTINCT estado
                        FROM
                            g_certificacion_bpa.solicitudes
                        WHERE
                            identificador in ('$identificador')
                        GROUP BY
                            estado; ";
	    
	    return $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
	}
}