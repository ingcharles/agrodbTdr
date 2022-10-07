<?php
 /**
 * Lógica del negocio de AplicacionesModelo
 *
 * Este archivo se complementa con el archivo AplicacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    AplicacionesLogicaNegocio
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilInternos\Modelos;
  
  use Agrodb\AplicacionMovilInternos\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\BuscarExcepcion;
  use Exception;

 
class AplicacionesLogicaNegocio implements IModelo 
{

	 private $modeloAplicaciones = null;
	 private $lNegocioToken = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAplicaciones = new AplicacionesModelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AplicacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAplicacion() != null && $tablaModelo->getIdAplicacion() > 0) {
		return $this->modeloAplicaciones->actualizar($datosBd, $tablaModelo->getIdAplicacion());
		} else {
		unset($datosBd["id_aplicacion"]);
		return $this->modeloAplicaciones->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAplicaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AplicacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAplicaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAplicaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAplicaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAplicaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloAplicaciones->getEsquema().". aplicaciones";
		 return $this->modeloAplicaciones->ejecutarSqlNativo($consulta);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada.
	*
	* @return array|ResultSet	
	*/
	public function obtenerAplicacionesPorUsuario($arrayParametros)
	{

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){
			$consulta = "SELECT DISTINCT 
							a.id_aplicacion, a.nombre, a.version, a.descripcion, a.color_inicio, a.color_fin, 
							a.codificacion_aplicacion, a.estado_aplicacion, a.id_area, a.vista
						FROM 
							a_movil_internos.aplicaciones a
						INNER JOIN a_movil_internos.aplicacion_perfil ap on ap.id_aplicacion = a.id_aplicacion
						INNER JOIN a_movil_internos.usuarios_perfiles us on us.id_perfil = ap.id_perfil 
						INNER JOIN a_movil_internos.perfiles P on p.id_perfil = ap.id_perfil
						and us.identificador = '".$arrayParametros['identificador']."'
						and p.estado = 1 
						and a.estado_aplicacion = 'activo'
						ORDER BY 9,2;";
			
			$consulta = "SELECT DISTINCT 
							a.id_aplicacion, a.nombre, a.version, a.descripcion, ac.color_inicio, ac.color_fin, 
							a.codificacion_aplicacion, a.estado_aplicacion, a.id_area, a.vista, ac.orden
						FROM 
							a_movil_internos.aplicaciones a
							INNER JOIN a_movil_internos.aplicacion_perfil ap on ap.id_aplicacion = a.id_aplicacion
							INNER JOIN a_movil_internos.usuarios_perfiles us on us.id_perfil = ap.id_perfil 
							INNER JOIN a_movil_internos.perfiles P on p.id_perfil = ap.id_perfil
							INNER JOIN a_movil_internos.aplicacion_colores ac on a.id_aplicacion_color = ac.id_aplicacion_color
							and us.identificador = '".$arrayParametros['identificador']."'
							and p.estado = 1 
							and a.estado_aplicacion = 'activo'
						ORDER BY 9,11;";

			try {
				$res = $this->modeloAplicaciones->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
				$array['cuerpo'] = $res->toArray();
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('archivo' => 'AplicacionesLogicaNegocio', 'metodo' => 'obtenerAplicacionesPorUsuario', 'consulta' => $consulta));
			}

		} else{
			echo json_encode($arrayToken);
		}
	
		
	}
	
	

	

}
