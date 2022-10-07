<?php
 /**
 * Lógica del negocio de SeguimientosCuarentenariosModelo
 *
 * Este archivo se complementa con el archivo SeguimientosCuarentenariosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022/02/02
 * @uses    SeguimientosCuarentenariosLogicaNegocio
 * @package SeguimientoCuarentenario
 * @subpackage Modelos
 */
  namespace Agrodb\SeguimientoCuarentenario\Modelos;
  
  use Agrodb\SeguimientoCuarentenario\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\BuscarExcepcion;
  use Exception;

class SeguimientosCuarentenariosLogicaNegocio implements IModelo 
{

	 private $modeloSeguimientosCuarentenarios = null;
	 private $lNegocioToken = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSeguimientosCuarentenarios = new SeguimientosCuarentenariosModelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SeguimientosCuarentenariosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSeguimientoCuarentenario() != null && $tablaModelo->getIdSeguimientoCuarentenario() > 0) {
		return $this->modeloSeguimientosCuarentenarios->actualizar($datosBd, $tablaModelo->getIdSeguimientoCuarentenario());
		} else {
		unset($datosBd["id_seguimiento_cuarentenario"]);
		return $this->modeloSeguimientosCuarentenarios->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSeguimientosCuarentenarios->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SeguimientosCuarentenariosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSeguimientosCuarentenarios->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSeguimientosCuarentenarios->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSeguimientosCuarentenarios->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSeguimientosCuarentenarios()
	{
	$consulta = "SELECT * FROM ".$this->modeloSeguimientosCuarentenarios->getEsquema().". seguimientos_cuarentenarios";
		 return $this->modeloSeguimientosCuarentenarios->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) para obtener las solicitudes de seguimiento cuarentenario.
	 *
	 * Token requerido 
	 * 
	 * @return array|ResultSet
	 */
	public function obtenerSolicitudesSeguimientoCuarentenario() {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){
			
			$consulta = "SELECT row_to_json(res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as seguimientos FROM (
				SELECT 
					sc.id_seguimiento_cuarentenario, 
					da.identificador_operador,
					case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end razon_social,
					sc.numero_seguimientos AS numero_seguimientos_planificados,
					sc.numero_plantas AS numero_plantas_ingreso,
					da.id_pais_exportador AS id_pais_origen,
					da.pais_exportacion AS pais_origen,
					da.id_vue,
					(
						SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
							SELECT 
								ddap.id_producto,
								ddap.nombre_producto as producto, 
								ddap.peso, 
								ddap.unidad_peso AS unidad,
								sp.nombre AS subtipo
							FROM 
								g_dda.destinacion_aduanera_productos ddap,
								g_catalogos.productos p,
								g_catalogos.subtipo_productos sp
							WHERE 
								ddap.id_destinacion_aduanera = da.id_destinacion_aduanera AND 
								ddap.id_producto = p.id_producto AND 
								p.id_subtipo_producto = sp.id_subtipo_producto
						) l_a
					) AS solicitud_productos,
					(
						SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
							SELECT
								a.id_area as id_guia, a.nombre_area, a.tipo_area, s.identificador_operador, s.nombre_lugar,
								s.identificador_operador ||'.'|| s.codigo_provincia || s.codigo || a.codigo || a.secuencial as codigo_area
								FROM
									g_operadores.sitios s
								INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
								INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
								INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
								INNER JOIN g_catalogos.tipos_operacion tp ON op.id_tipo_operacion = tp.id_tipo_operacion
								WHERE 
									tp.codigo = 'CUA' AND tp.id_area= 'SV' AND op.estado='registrado' and s.identificador_operador = da.identificador_operador
						) l_a		
					) AS areas_cuarentena
				FROM
					g_seguimiento_cuarentenario.seguimientos_cuarentenarios sc
					INNER JOIN 	g_dda.destinacion_aduanera da ON sc.id_destinacion_aduanera = da.id_destinacion_aduanera
					INNER JOIN g_operadores.operadores op ON da.identificador_operador = op.identificador
				WHERE	
					sc.estado = 'abierto'
			) as listado ) AS res;";
			
			try {
				$res = $this->modeloSeguimientosCuarentenarios->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";			
				$array['cuerpo'] = json_decode($res->current()->res);
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('archivo' => 'SeguimientosCuarentenariosLogicaNegocio', 'metodo' => 'obtenerSolicitudesSeguimientoCuarentenario', 'consulta' => $consulta));
			}
		} else{
			echo json_encode($arrayToken);
		}

	}
}
