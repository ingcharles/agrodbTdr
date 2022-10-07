<?php
 /**
 * Lógica del negocio de Certificacionf11Modelo
 *
 * Este archivo se complementa con el archivo Certificacionf11Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    Certificacionf11LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Certificacionf11LogicaNegocio implements IModelo 
{

	 private $modeloCertificacionf11 = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCertificacionf11 = new Certificacionf11Modelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Certificacionf11Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloCertificacionf11->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloCertificacionf11->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCertificacionf11->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Certificacionf11Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloCertificacionf11->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCertificacionf11->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCertificacionf11->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCertificacionf11()
	{
	$consulta = "SELECT * FROM ".$this->modeloCertificacionf11->getEsquema().". certificacionf11";
		 return $this->modeloCertificacionf11->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Verifica que el formulario de inspeccion de tablet sea del exportador al pais y con el producto a exportar.
	 *
	 * @return array|ResultSet
	 */
	public function verificarFormularioInspeccionCfe($arrayParametros)
	{
	    $identificadorExportador = $arrayParametros["identificadorExportador"];
	    $numeroFormularioInspeccion = $arrayParametros["numeroFormularioInspeccion"];
	    $idProducto = $arrayParametros["idProducto"];
	    $idPaisDestino = $arrayParametros["idPaisDestino"];
	    
	    $consulta = "SELECT
                    	c.numero_reporte
                    	, c.ruc
                    	, cde.pais_destino
                    	, cde.id_producto
                    FROM
                    	f_inspeccion.certificacionf11 c
                    	INNER JOIN f_inspeccion.certificacionf11_detalle_envios cde ON c.id = cde.id_padre
                    WHERE
                    	c.numero_reporte = '" . $numeroFormularioInspeccion . "'
                    	and c.ruc = '" . $identificadorExportador . "'
                    	and cde.pais_destino = '" . $idPaisDestino . "'
                    	and cde.id_producto = '" . $idProducto . "'
                        and utilizado_cfe = false;";
	    
	    $inspecciones = $this->modeloCertificacionf11->ejecutarSqlNativo($consulta);
	    
	    return $inspecciones;
	}

}
