<?php
 /**
 * Lógica del negocio de DetallePostAnimalesModelo
 *
 * Este archivo se complementa con el archivo DetallePostAnimalesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    DetallePostAnimalesLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
 
class DetallePostAnimalesLogicaNegocio implements IModelo 
{

	 private $modeloDetallePostAnimales = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetallePostAnimales = new DetallePostAnimalesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetallePostAnimalesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetallePostAnimales() != null && $tablaModelo->getIdDetallePostAnimales() > 0) {
		return $this->modeloDetallePostAnimales->actualizar($datosBd, $tablaModelo->getIdDetallePostAnimales());
		} else {
		unset($datosBd["id_detalle_post_animales"]);
		return $this->modeloDetallePostAnimales->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetallePostAnimales->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetallePostAnimalesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetallePostAnimales->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetallePostAnimales->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetallePostAnimales->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetallePostAnimales()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetallePostAnimales->getEsquema().". detalle_post_animales";
		 return $this->modeloDetallePostAnimales->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'id_formulario_post_mortem',
			'id_detalle_ante_animales',
			'fecha_formulario',
			'estado_nodulos_linfaticos',
			'otro_diagnostico',
			'num_canales_decomiso_parcial',
			'peso_total_carne_aprobada',
			'peso_total_carne_decomisada',
			'num_canales_decomiso',
			'peso_total_carne_decomisada_productivo',
			'num_canales_aprobadas_totalmente',
			'num_canales_aprobadas_parcialmente',
			'peso_total_carne_aprobada_productivos',
			'peso_promedio_canal',
			'peso_total_visceras_decomisadas',
			'peso_carne_incineracion',
			'peso_visceras_incineracion',
			'peso_carne_rendering',
			'peso_visceras_rendering',
			'peso_carne_abono',
			'peso_visceras_abono',
			'lugar_incineracion',
			'lugar_renderizacion',
			'lugar_desconposicion',
			'nombre_gestor_ambiental',
			'descripcion_actividad_general',
			'observacion',
			'peso_carne_ambiental',
			'peso_visceras_ambiental',
			'examen_visual',
			'palpacion',
			'insicion',
			'toma_muestra',
			'organo_tejido',
			'descripcion_actividad'
		);
		return $columnas;
	}

}
