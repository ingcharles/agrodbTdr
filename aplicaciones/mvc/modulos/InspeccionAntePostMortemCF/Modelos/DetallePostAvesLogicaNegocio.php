<?php
 /**
 * Lógica del negocio de DetallePostAvesModelo
 *
 * Este archivo se complementa con el archivo DetallePostAvesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-05-27
 * @uses    DetallePostAvesLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
  
 
class DetallePostAvesLogicaNegocio implements IModelo 
{

	 private $modeloDetallePostAves = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetallePostAves = new DetallePostAvesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetallePostAvesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetallePostAves() != null && $tablaModelo->getIdDetallePostAves() > 0) {
		return $this->modeloDetallePostAves->actualizar($datosBd, $tablaModelo->getIdDetallePostAves());
		} else {
		unset($datosBd["id_detalle_post_aves"]);
		return $this->modeloDetallePostAves->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetallePostAves->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetallePostAvesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetallePostAves->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetallePostAves->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetallePostAves->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetallePostAves()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetallePostAves->getEsquema().". detalle_post_aves";
		 return $this->modeloDetallePostAves->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
		$columnas = array(
			'id_formulario_post_mortem',
			'id_detalle_ante_aves',
			'fecha_formulario',
			'num_descarte',
			'porcent_num_descarte',
			'num_colibacilosis',
			'porcent_num_colibacilosis',
			'num_pododermatitis',
			'porcent_num_pododermatitis',
			'num_lesiones_piel',
			'porcent_num_lesiones_piel',
			'num_mal_sangrado',
			'porcent_num_mal_sangrado',
			'num_contusion_pierna',
			'porcent_num_contusion_pierna',
			'num_contusion_ala',
			'porcent_num_contusion_ala',
			'num_contusion_pechuga',
			'porcent_num_contusion_pechuga',
			'num_alas_rotas',
			'porcent_num_alas_rotas',
			'num_piernas_rotas',
			'porcent_num_piernas_rotas',
			'total_canales_aprobados',
			'peso_total_canales_aprobados_totalmente',
			'total_canales_aprobados_parcialmente',
			'peso_total_canales_aprobados_parcialmente',
			'canales_decomiso_parcial',
			'canales_decomiso_total',
			'peso_promedio_canales',
			'total_carne_decomisada',
			'destino_decomisos',
			'lugar_disposicion_final',
			'observacion'
			
		);
		return $columnas;
	}
}
