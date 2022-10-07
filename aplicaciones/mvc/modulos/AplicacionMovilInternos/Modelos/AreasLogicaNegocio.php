<?php
 /**
 * Lógica del negocio de AreasModelo
 *
 * Este archivo se complementa con el archivo AreasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-05-22
 * @uses    AreasLogicaNegocio
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilInternos\Modelos;
  
  use Agrodb\AplicacionMovilInternos\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\BuscarExcepcion;
  use Exception;
 
class AreasLogicaNegocio implements IModelo 
{

	 private $modeloAreas = null;
	 private $lNegocioToken = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAreas = new AreasModelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AreasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdArea() != null && $tablaModelo->getIdArea() > 0) {
		return $this->modeloAreas->actualizar($datosBd, $tablaModelo->getIdArea());
		} else {
		unset($datosBd["id_area"]);
		return $this->modeloAreas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAreas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AreasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAreas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAreas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAreas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAreas()
	{
	$consulta = "SELECT * FROM ".$this->modeloAreas->getEsquema().". areas";
		 return $this->modeloAreas->ejecutarSqlNativo($consulta);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada.
	*
	* @return array|ResultSet	
	*/
	public function obtenerAreasAplicacion($arrayParametros)
	{
		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			$consulta = "SELECT DISTINCT 	
							ar.id_area, ar.nombre, ar.nombre_corto, ar.id_area_padre
						FROM 
							a_movil_internos.areas ar INNER JOIN a_movil_internos.aplicaciones a on a.id_area = ar.id_area
							INNER JOIN a_movil_internos.aplicacion_perfil ap on ap.id_aplicacion = a.id_aplicacion
							INNER JOIN a_movil_internos.usuarios_perfiles us on us.id_perfil = ap.id_perfil 
							INNER JOIN a_movil_internos.perfiles P on p.id_perfil = ap.id_perfil
							and us.identificador = '".$arrayParametros['identificador']."'
							and p.estado=1
							and a.estado_aplicacion='activo'
						ORDER BY 3;";

				$consulta = "SELECT json_agg(row_to_json (filas)) as res from (
					SELECT 
						dap.id_area
						, dap.nombre
						, dap.nombre_corto
						, dap.id_area_padre 
						,(SELECT array_to_json(array_agg(row_to_json(listado))) as areas FROM (
						SELECT * FROM (
						SELECT 
							 e.id_area
							, e.nombre
							, e.nombre_corto
							, e.id_area_padre 
						FROM
							a_movil_internos.areas e
							inner join a_movil_internos.aplicaciones ap on ap.id_area = e.id_area
							WHERE
							e.id_area_padre = dap.id_area
							and ap.estado_aplicacion = arpa.estado_aplicacion
							group by e.id_area, e.nombre, e.nombre_corto, e.id_area_padre
							ORDER BY 3
						) as datos) AS listado)
					FROM
					(SELECT	
						ar.id_area_padre
						, up.identificador
						, p.estado
						, a.estado_aplicacion
					FROM 
						a_movil_internos.areas ar 
						INNER JOIN a_movil_internos.aplicaciones a ON a.id_area = ar.id_area
						INNER JOIN a_movil_internos.aplicacion_perfil ap ON ap.id_aplicacion = a.id_aplicacion
						INNER JOIN a_movil_internos.usuarios_perfiles up ON up.id_perfil = ap.id_perfil
						INNER JOIN a_movil_internos.perfiles p on p.id_perfil = ap.id_perfil
					group by ar.id_area_padre, up.identificador, p.estado, a.estado_aplicacion ) AS arpa
					INNER JOIN a_movil_internos.areas dap ON dap.id_area = arpa.id_area_padre
					WHERE
					arpa.identificador = '".$arrayParametros['identificador']."'
					and arpa.estado = 1                    
					and arpa.estado_aplicacion = 'activo'
				) filas;";

			try {
				$res = $this->modeloAreas->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
				// $array['cuerpo'] = $res;
				$array['cuerpo'] = json_decode($res->current()->res);
				http_response_code(200);
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('origen' => 'Agro servicios', 'archivo' => 'AreasLogicaNegocio', 'metodo' => 'obtenerAreasAplicacion', 'consulta' => $consulta));
			}

		} else{
			echo json_encode($arrayToken);
		}
	}

}
