<?php
 /**
 * Lógica del negocio de SubproductosEmisionCertificadoModelo
 *
 * Este archivo se complementa con el archivo SubproductosEmisionCertificadoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    SubproductosEmisionCertificadoLogicaNegocio
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class SubproductosEmisionCertificadoLogicaNegocio implements IModelo 
{

	 private $modeloSubproductosEmisionCertificado = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSubproductosEmisionCertificado = new SubproductosEmisionCertificadoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SubproductosEmisionCertificadoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSubproductosEmisionCertificado() != null && $tablaModelo->getIdSubproductosEmisionCertificado() > 0) {
		return $this->modeloSubproductosEmisionCertificado->actualizar($datosBd, $tablaModelo->getIdSubproductosEmisionCertificado());
		} else {
		unset($datosBd["id_subproductos_emision_certificado"]);
		return $this->modeloSubproductosEmisionCertificado->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSubproductosEmisionCertificado->borrar($id);
	}
	public function borrarPorParametro($param, $value) {
	    $this->modeloSubproductosEmisionCertificado->borrarPorParametro($param, $value);
	}
	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SubproductosEmisionCertificadoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSubproductosEmisionCertificado->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSubproductosEmisionCertificado->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSubproductosEmisionCertificado->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSubproductosEmisionCertificado()
	{
	$consulta = "SELECT * FROM ".$this->modeloSubproductosEmisionCertificado->getEsquema().". subproductos_emision_certificado";
		 return $this->modeloSubproductosEmisionCertificado->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
	    $columnas = array(
	        'id_detalle_emision_certificado',
	        'subproducto',
	        'cantidad_movilizar',
	        'saldo_disponible',
	        'subproducto',
	        'id_subproductos',
	        'lote_movilizar'
	    );
	    return $columnas;
	}
	
	/**
	 * certificación de origen
	 */
	public function buscarSubproductosEmision($arrayParametros)
	{
	    $busqueda = '';
	    if(array_key_exists('id_emision_certificado', $arrayParametros)){
	        $busqueda .= " and ec.id_emision_certificado = ". $arrayParametros['id_emision_certificado'];
	    }
	    if(array_key_exists('id_emision_certificado_no', $arrayParametros)){
	        $busqueda .= " and ec.id_emision_certificado != ". $arrayParametros['id_emision_certificado_no'];
	    }
	    if (array_key_exists('identificador_operador', $arrayParametros)) {
	        $busqueda .= " and identificador_operador = '" . $arrayParametros['identificador_operador'] . "'";
	    }
	    if (array_key_exists('tipo_especie', $arrayParametros)) {
	        $busqueda .= " and rtrim(dec.tipo_especie) = rtrim('" . $arrayParametros['tipo_especie'] . "')";
	    }
	    if (array_key_exists('tipo_especie_no', $arrayParametros)) {
	        $busqueda .= " and rtrim(dec.tipo_especie) not in ('" . $arrayParametros['tipo_especie_no'] . "')";
	    }
	    if(array_key_exists('fecha_produccion', $arrayParametros)){
	        $busqueda .= " and dec.fecha_produccion  = '". $arrayParametros['fecha_produccion'] ."'";
	    }
	    if(array_key_exists('subproducto', $arrayParametros)){
	        $busqueda .= " and sec.subproducto = '". $arrayParametros['subproducto']."'";
	    }
	    if(array_key_exists('estadoEmision', $arrayParametros)){
	        $busqueda .= " and ec.estado = '". $arrayParametros['estadoEmision']."'";
	    }
	    if(array_key_exists('fecha_creacion', $arrayParametros)){
	        $busqueda .= " and ec.fecha_creacion::date  = '". $arrayParametros['fecha_creacion'] ."'";
	    }
	    
 	 $consulta = "SELECT
                            dec.fecha_produccion, dec.tipo_especie, sec.subproducto, lote_movilizar, sec.cantidad_movilizar, sec.saldo_disponible, id_subproductos_emision_certificado, dec.id_emision_certificado, dec.id_detalle_emision_certificado
                     FROM
                            g_emision_certificacion_origen.emision_certificado ec
                            inner join g_emision_certificacion_origen.detalle_emision_certificado dec on dec.id_emision_certificado = ec.id_emision_certificado
                            inner join g_emision_certificacion_origen.subproductos_emision_certificado sec on sec.id_detalle_emision_certificado = dec.id_detalle_emision_certificado
                     where
                            sec.estado='" . $arrayParametros['estado'] . "'
                            " . $busqueda . "
                           ORDER BY sec.id_subproductos_emision_certificado ASC ;";
	    return $this->modeloSubproductosEmisionCertificado->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * certificación de origen
	 */
	public function buscarSubproductosEmisionLista($arrayParametros)
	{
	    $busqueda = '';
	    if(array_key_exists('id_emision_certificado', $arrayParametros)){
	        $busqueda .= " and ec.id_emision_certificado = ". $arrayParametros['id_emision_certificado'];
	    }
	    if (array_key_exists('identificador_operador', $arrayParametros)) {
	        $busqueda .= " and identificador_operador = '" . $arrayParametros['identificador_operador'] . "'";
	    }
	    if (array_key_exists('tipo_especie', $arrayParametros)) {
	        $busqueda .= " and dec.tipo_especie = '" . $arrayParametros['tipo_especie'] . "'";
	    }
	    if(array_key_exists('fecha_produccion', $arrayParametros)){
	        $busqueda .= " and dec.fecha_produccion  = '". $arrayParametros['fecha_produccion'] ."'";
	    }
	    if(array_key_exists('subproducto', $arrayParametros)){
	        $busqueda .= " and sec.subproducto = '". $arrayParametros['subproducto']."'";
	    }
	    if(array_key_exists('estadoEmision', $arrayParametros)){
	        $busqueda .= " and ec.estado = '". $arrayParametros['estadoEmision']."'";
	    }
	    if(array_key_exists('fecha_creacion', $arrayParametros)){
	        $busqueda .= " and sec.fecha_creacion = '". $arrayParametros['fecha_creacion']."'";
	    }
	   $consulta = "SELECT
                            dec.fecha_produccion, dec.tipo_especie, sec.subproducto, lote_movilizar, sec.cantidad_movilizar, sec.saldo_disponible, 
                            id_subproductos_emision_certificado, dec.id_emision_certificado, dec.id_detalle_emision_certificado
                     FROM
                            g_emision_certificacion_origen.emision_certificado ec
                            inner join g_emision_certificacion_origen.detalle_emision_certificado dec on dec.id_emision_certificado = ec.id_emision_certificado
                            inner join g_emision_certificacion_origen.subproductos_emision_certificado sec on sec.id_emision_certificado = ec.id_emision_certificado
                     where
                            sec.estado='" . $arrayParametros['estado'] . "'
                            " . $busqueda . "
                           ORDER BY sec.id_subproductos_emision_certificado ASC ;";
	    
	    return $this->modeloSubproductosEmisionCertificado->ejecutarSqlNativo($consulta);
	}

}
