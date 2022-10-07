<?php
 /**
 * Lógica del negocio de SubproductosTempModelo
 *
 * Este archivo se complementa con el archivo SubproductosTempControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    SubproductosTempLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
 
class SubproductosTempLogicaNegocio implements IModelo 
{

	 private $modeloSubproductosTemp = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSubproductosTemp = new SubproductosTempModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SubproductosTempModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSubproductosTemp() != null && $tablaModelo->getIdSubproductosTemp() > 0) {
		return $this->modeloSubproductosTemp->actualizar($datosBd, $tablaModelo->getIdSubproductosTemp());
		} else {
		unset($datosBd["id_subproductos_temp"]);
		return $this->modeloSubproductosTemp->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSubproductosTemp->borrar($id);
	}
	public function borrarPorParametro($param, $value) {
	    $this->modeloSubproductosTemp->borrarPorParametro($param, $value);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SubproductosTempModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSubproductosTemp->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSubproductosTemp->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSubproductosTemp->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSubproductosTemp()
	{
	$consulta = "SELECT * FROM ".$this->modeloSubproductosTemp->getEsquema().". subproductos_temp";
		 return $this->modeloSubproductosTemp->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarSubproductosXProductos($arrayParametros)
	{
	    $busqueda='';
	    if (in_array('id_productos_temp', $arrayParametros)) {
	        $busqueda = "and pt.id_productos_temp = ".$arrayParametros['id_productos_temp'];
	    }
	        $consulta ="
            SELECT
                pt.id_productos_temp, subt.id_subproductos_temp, pt.tipo_especie,  pt.fecha_faenamiento,pt.fecha_recepcion, subt.subproducto, cantidad,
                (SELECT sum(cantidad) FROM g_emision_certificacion_origen.subproductos_temp WHERE id_productos_temp = pt.id_productos_temp) as resultado
            FROM
                g_emision_certificacion_origen.subproductos_temp subt
                inner join g_emision_certificacion_origen.productos_temp pt on subt.id_productos_temp = pt.id_productos_temp
            WHERE
                pt.identificador_operador = '".$arrayParametros['identificador_operador']."' 
                ".$busqueda." order by 1,2;";
	        return $this->modeloSubproductosTemp->ejecutarConsulta($consulta);
	}
	
	/**
	 * verificar cantidad ingresada de productos
	 */
	
	
	public function sumarCantidadSubProductos($arrayParametros){
	   $consulta ="
            SELECT
                sum(cantidad) as total, id_subproductos_temp
            FROM
                g_emision_certificacion_origen.subproductos_temp
            WHERE
                id_productos_temp=".$arrayParametros['id_productos_temp']."
                and subproducto='".$arrayParametros['subproducto']."'
           group by id_subproductos_temp;";
	    
	    return $this->modeloSubproductosTemp->ejecutarSqlNativo($consulta);
	}
	

}
