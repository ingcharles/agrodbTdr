<?php
 /**
 * Lógica del negocio de ProductosModelo
 *
 * Este archivo se complementa con el archivo ProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    ProductosLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class ProductosLogicaNegocio implements IModelo 
{

	 private $modeloProductos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloProductos = new ProductosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ProductosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProductos() != null && $tablaModelo->getIdProductos() > 0) {
		return $this->modeloProductos->actualizar($datosBd, $tablaModelo->getIdProductos());
		} else {
		unset($datosBd["id_productos"]);
		return $this->modeloProductos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloProductos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ProductosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloProductos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloProductos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloProductos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarProductos()
	{
	$consulta = "SELECT * FROM ".$this->modeloProductos->getEsquema().". productos";
		 return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
	    $columnas = array(
	        'id_registro_produccion',
	        'num_canales_obtenidos',
	        'num_canales_obtenidos_uso',
	        'num_canales_uso_industri',
	        'tipo_especie',
	        'num_animales_recibidos',
	        'fecha_recepcion',
	        'codigo_canal',
	        'fecha_faenamiento'
	    );
	    return $columnas;
	}
	
	public function buscarCantidadProductos($arrayParametros){
	    $busqueda = '';
	    if (array_key_exists('subproducto', $arrayParametros)) {
	        $busqueda .= " and sub.subproducto = '" . $arrayParametros['subproducto'] . "'";
	    }
	    if (array_key_exists('id_productos', $arrayParametros)) {
	        $busqueda .= " and sub.id_productos = '" . $arrayParametros['id_productos'] . "'";
	    }
	    $consulta = "
                    SELECT
                          sub.id_productos, sub.subproducto, sub.cantidad
                    FROM
                          g_emision_certificacion_origen.registro_produccion rp inner join 
                          g_emision_certificacion_origen.productos p on p.id_registro_produccion = rp.id_registro_produccion inner join 
                          g_emision_certificacion_origen.subproductos sub on p.id_productos = sub.id_productos
                           
            	    WHERE
                        p.fecha_faenamiento='" . $arrayParametros['fecha_faenamiento'] . "'
                        and p.tipo_especie='" . $arrayParametros['tipo_especie'] . "'
                        and rp.identificador_operador ='" . $_SESSION['usuario'] . "'
                        ".$busqueda.";";
	    return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

}
