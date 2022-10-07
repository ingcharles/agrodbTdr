<?php
 /**
 * Lógica del negocio de ProductosTempModelo
 *
 * Este archivo se complementa con el archivo ProductosTempControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    ProductosTempLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class ProductosTempLogicaNegocio implements IModelo 
{

	 private $modeloProductosTemp = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloProductosTemp = new ProductosTempModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ProductosTempModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProductosTemp() != null && $tablaModelo->getIdProductosTemp() > 0) {
		return $this->modeloProductosTemp->actualizar($datosBd, $tablaModelo->getIdProductosTemp());
		} else {
		unset($datosBd["id_productos_temp"]);
		return $this->modeloProductosTemp->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloProductosTemp->borrar($id);
	}
	public function borrarPorParametro($param, $value) {
	    $this->modeloProductosTemp->borrarPorParametro($param, $value);
	}
	
	public function borrarTablasTemporales() {
	    $arrayParametros = array('identificador_operador' => $_SESSION['usuario']);
	    $consulta = $this->obtenerEspecie($arrayParametros);
	    $lnegocioSubproducto = new SubproductosTempLogicaNegocio();
	    foreach ($consulta as $value) {
	        $lnegocioSubproducto->borrarPorParametro('id_productos_temp',$value['id_productos_temp']);
	    }
	    $this->modeloProductosTemp->borrarPorParametro('identificador_operador',"'".$_SESSION['usuario']."'");
	}
	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ProductosTempModelo
	*/
	public function buscar($id)
	{
		return $this->modeloProductosTemp->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloProductosTemp->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloProductosTemp->buscarLista($where, $order, $count, $offset);
	}

	public function obtenerEspecie($arrayParametros){
	   $consulta ="
            SELECT 
                tipo_especie, id_productos_temp, codigo_canal, num_Canales_obtenidos, row_number() over() as contador
            FROM 
                g_emision_certificacion_origen.productos_temp
            WHERE 
                identificador_operador='".$arrayParametros['identificador_operador']."' order by 2;";    
	   return $this->modeloProductosTemp->ejecutarConsulta($consulta);
	}
	
	public function obtenerSumaProduccionTemp($arrayParametros){
	    $buscar='id_productos_temp';
	    if(array_key_exists('num_canales_obtenidos_uso', $arrayParametros)){
	        $buscar = "sum(num_canales_obtenidos_uso) as total";
	    }
	    if(array_key_exists('num_canales_uso_industri', $arrayParametros)){
	        $buscar = "sum(num_canales_uso_industri) as total";
	    }
	    $consulta ="
            SELECT
                ".$buscar."
            FROM
                g_emision_certificacion_origen.productos_temp
            WHERE
                identificador_operador='".$arrayParametros['identificador_operador']."' 
                and tipo_especie ='".$arrayParametros['tipo_especie']."';";
	    return $this->modeloProductosTemp->ejecutarSqlNativo($consulta);
	}
	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarProductosTemp()
	{
	$consulta = "SELECT * FROM ".$this->modeloProductosTemp->getEsquema().". productos_temp";
		 return $this->modeloProductosTemp->ejecutarSqlNativo($consulta);
	}

}
