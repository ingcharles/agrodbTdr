<?php
 /**
 * Lógica del negocio de RequisitosModelo
 *
 * Este archivo se complementa con el archivo RequisitosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-10
 * @uses    RequisitosLogicaNegocio
 * @package RequisitoComercializacion
 * @subpackage Modelos
 */
  namespace Agrodb\RequisitosComercializacion\Modelos;
  
  use Agrodb\RequisitosComercializacion\Modelos\IModelo;
 
class RequisitosLogicaNegocio implements IModelo 
{

	 private $modeloRequisitos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRequisitos = new RequisitosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new RequisitosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRequisito() != null && $tablaModelo->getIdRequisito() > 0) {
		return $this->modeloRequisitos->actualizar($datosBd, $tablaModelo->getIdRequisito());
		} else {
		unset($datosBd["id_requisito"]);
		return $this->modeloRequisitos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloRequisitos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return RequisitosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloRequisitos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloRequisitos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloRequisitos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarRequisitos()
	{
	$consulta = "SELECT * FROM ".$this->modeloRequisitos->getEsquema().". requisitos";
		 return $this->modeloRequisitos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de los 
     * requisitos impresos para movilización asignados a un producto.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerRequisitosImpresosProducto($arrayParametros) {
	    
	    $consulta = "SELECT
                    	ra.id_requisito_comercio,
                    	ra.requisito,
                    	r.nombre,
                    	r.detalle,
                    	r.detalle_impreso,
                    	r.tipo,
                    	ra.estado
                    FROM
                    	g_requisitos.requisitos_asignados ra,
                    	g_requisitos.requisitos r,
                    	g_requisitos.requisitos_comercializacion rc
                    WHERE
                    	rc.id_requisito_comercio = ra.id_requisito_comercio and
                    	ra.requisito = r.id_requisito and
                    	r.tipo = '".$arrayParametros['tipo']."' and
                    	rc.id_producto = ".$arrayParametros['id_producto']." and
                    	ra.estado = 'activo'
                    ORDER BY
                	    3 ASC;";
	    
	    //echo $consulta;
	    return $this->modeloRequisitos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de los
	 * requisitos impresos para movilización asignados a un producto.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerRequisitosImpresosXProductoXPais($arrayParametros) {
	    
	    $consulta = "SELECT
                    	ra.id_requisito_comercio,
                    	ra.requisito,
                    	r.nombre,
                    	r.detalle,
                    	r.detalle_impreso,
                    	r.tipo,
                    	ra.estado
                    FROM
                    	g_requisitos.requisitos_asignados ra,
                    	g_requisitos.requisitos r,
                    	g_requisitos.requisitos_comercializacion rc
                    WHERE
                    	rc.id_requisito_comercio = ra.id_requisito_comercio and
                    	ra.requisito = r.id_requisito and
                    	r.tipo = '".$arrayParametros['tipo']."' and
                    	rc.id_producto = ".$arrayParametros['id_producto']." and
                        rc.id_localizacion = ".$arrayParametros['id_pais_destino']." and
                    	ra.estado = 'activo'
                    ORDER BY
                	    3 ASC;";
	    
	    //echo $consulta;
	    return $this->modeloRequisitos->ejecutarSqlNativo($consulta);
	}
}