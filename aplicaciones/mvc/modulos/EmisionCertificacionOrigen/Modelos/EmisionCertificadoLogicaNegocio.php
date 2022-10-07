<?php
/**
 * Lógica del negocio de EmisionCertificadoModelo
 *
 * Este archivo se complementa con el archivo EmisionCertificadoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-09-18
 * @uses EmisionCertificadoLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
namespace Agrodb\EmisionCertificacionOrigen\Modelos;

use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
use Agrodb\CentrosFaenamiento\Modelos\CentrosFaenamientoLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\EmisionCertificacionOrigen\Controladores\BaseControlador;

class EmisionCertificadoLogicaNegocio implements IModelo{

	private $modeloEmisionCertificado = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloEmisionCertificado = new EmisionCertificadoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new EmisionCertificadoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEmisionCertificado() != null && $tablaModelo->getIdEmisionCertificado() > 0){
			return $this->modeloEmisionCertificado->actualizar($datosBd, $tablaModelo->getIdEmisionCertificado());
		}else{
			unset($datosBd["id_emision_certificado"]);
			return $this->modeloEmisionCertificado->guardar($datosBd);
		}
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarRegistros(Array $datos){
		$datos['identificador_operador'] = $_SESSION['usuario'];
		$substraer = explode('-', $datos['identificador_movilizacion']);
		$datos['identificador_movilizacion'] = $substraer[0];
		$substraer = explode('-', $datos['sitio_origen']);
		$datos['sitio_origen'] = $substraer[0];
		$centroFae = explode('-', $datos['area_origen']);
		$vehiculo = explode('-', $datos['identificador_operador_transportista']);
		$datos['id_dato_vehiculo'] = $vehiculo[2];
		$tablaModelo = new EmisionCertificadoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEmisionCertificado() != null && $tablaModelo->getIdEmisionCertificado() > 0){
			if ($datos['contenedor'] == 'Si'){
				$lnegocioCentroFaenamiento = new CentrosFaenamientoLogicaNegocio();
				$lnegocioLocalizacion = new LocalizacionLogicaNegocio();
				$datosCF = $lnegocioCentroFaenamiento->buscar($centroFae[2]);
				$codigoCertificado = '';
				if ($datosCF->getIdCentroFaenamiento() > 0){
					$codigoCertificado = $datosCF->getCodigo() . '-';
					$extra = explode('–', $datosCF->getTipoCentroFaenamiento());
					$codigoCertificado .= rtrim($extra[0]) . '-';
					if ($datosCF->getTipoHabilitacion() == 'Intercantonal'){
						$codigoCertificado .= 'CAN';
					}else{
						$codigoCertificado .= 'NAC';
					}
				}
				$codProv = $lnegocioLocalizacion->buscar($datos['provincia_destino']);
				$codigoProvincia = substr($codProv->getCodigoVue(), 1, 2);
				$numeroCertificado = $codigoProvincia . '-' . $codigoCertificado;
				$datosBd['numero_certificado'] = $numeroCertificado;
			}else{
				$datos['estado'] = 'Notificar';
			}
			$this->modeloEmisionCertificado->actualizar($datosBd, $tablaModelo->getIdEmisionCertificado());

			return $datosBd["id_emision_certificado"];
		}else{
			if ($datos['contenedor'] == 'Si'){
				$lnegocioCentroFaenamiento = new CentrosFaenamientoLogicaNegocio();
				$datosCF = $lnegocioCentroFaenamiento->buscar($centroFae[2]);
				$codigoCertificado = '';
				if ($datosCF->getIdCentroFaenamiento() > 0){
					$codigoCertificado = $datosCF->getCodigo() . '-';
					$extra = explode('-', $datosCF->getTipoCentroFaenamiento());
					$codigoCertificado .= $extra[0] . '-';
					if ($datosCF->getTipoHabilitacion() == 'Intercantonal'){
						$codigoCertificado .= 'CAN';
					}else{
						$codigoCertificado .= 'NAC';
					}
				}
				$codigoProvincia = $this->obtenerCodigoVueLocalizacion($datos['provincia_destino']);
				$codigoProvincia = substr($codigoProvincia->current()->codigo_vue, 1, 2);
				$numeroCertificado = $codigoProvincia . '-' . $codigoCertificado;
				$datosBd['numero_certificado'] = $numeroCertificado;
			}else{
				$datos['estado'] = 'Notificar';
			}
			unset($datosBd["id_emision_certificado"]);
			return $this->modeloEmisionCertificado->guardar($datosBd);
		}
	}

	// ******************************************************************************
	public function guardarProductosMovilizar(Array $datos){
		try{
			$this->modeloEmisionCertificado = new EmisionCertificadoModelo();
			$proceso = $this->modeloEmisionCertificado->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Guardar productos');
			}
			$datos['identificador_operador'] = $_SESSION['usuario'];
			$substraer = explode('-', $datos['identificador_movilizacion']);
			$datos['identificador_movilizacion'] = $substraer[0];
			$substraer = explode('-', $datos['sitio_origen']);
			$datos['sitio_origen'] = $substraer[0];
			$substraer = explode('-', $datos['area_origen']);
			$datos['area_origen'] = $substraer[0];

			$tablaModelo = new EmisionCertificadoModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			if ($tablaModelo->getIdEmisionCertificado() != null && $tablaModelo->getIdEmisionCertificado() > 0){
				$this->modeloEmisionCertificado->actualizar($datosBd, $tablaModelo->getIdEmisionCertificado());
				$idRegistro = $tablaModelo->getIdEmisionCertificado();
			}else{
				unset($datosBd["id_emision_certificado"]);
				$idRegistro = $this->modeloEmisionCertificado->guardar($datosBd);
			}
			if (! $idRegistro){
				throw new \Exception('No se registo los datos en la tabla emision_certificado');
			}
			// *************guadar detalle de productos*************
			if ($datos['contenedor'] == 'Si'){
				if (isset($datos['tipo_especie'])){

					if ($_POST['producto_movilizar'] == 'Canal' || $_POST['producto_movilizar'] == 'Canal con subproductos'){

						if (isset($datos['codigo_canal'])){
							$substraer = explode('-', $datos['codigo_canal']);
							$datos['codigo_canal'] = $substraer[1];
							$datos['id_productos'] = $substraer[0];
						}
						if (isset($datos['tipo_producto_movilizar_canal'])){
							$datos['tipo_producto_movilizar_canal'] = ($datos['tipo_producto_movilizar_canal'] != '') ? $datos['tipo_producto_movilizar_canal'] : null;
						}else{
							$datos['tipo_producto_movilizar_canal'] = '';
						}
						$lnegocioDetalleEmisionCertificado = new DetalleEmisionCertificadoLogicaNegocio();
						$datos = array(
							'id_emision_certificado' => $idRegistro,
							'producto_movilizar' => $datos['producto_movilizar'],
							'tipo_especie' => $datos['tipo_especie'],
							'tipo_producto_movilizar_canal' => $datos['tipo_producto_movilizar_canal'],
							'codigo_canal' => ($datos['codigo_canal'] != '') ? $datos['codigo_canal'] : null,
							'destino' => (isset($datos['destino'])) ? $datos['destino'] : null,
							'id_productos' => (isset($datos['id_productos'])) ? $datos['id_productos'] : null,
							'fecha_produccion' => $datos['fecha_produccion'],
							'tipo_movilizacion_canal' => (isset($datos['tipo_movilizacion_canal'])) ? $datos['tipo_movilizacion_canal'] : null);

						if ($datos['destino'] == 'Un destino' && $datos['tipo_movilizacion_canal'] == 'Media'){
							$statement = $this->modeloEmisionCertificado->getAdapter()
								->getDriver()
								->createStatement();
							$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('detalle_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
							$sqlInsertar->columns($lnegocioDetalleEmisionCertificado->columnas());
							$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
							$statement->execute();
						}
						if ($datos['destino'] == 'Un destino' && $datos['tipo_movilizacion_canal'] == 'Cuarto'){
							$statement = $this->modeloEmisionCertificado->getAdapter()
								->getDriver()
								->createStatement();
							$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('detalle_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
							$sqlInsertar->columns($lnegocioDetalleEmisionCertificado->columnas());
							$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
							$statement->execute();

							$statement = $this->modeloEmisionCertificado->getAdapter()
								->getDriver()
								->createStatement();
							$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('detalle_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
							$sqlInsertar->columns($lnegocioDetalleEmisionCertificado->columnas());
							$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
							$statement->execute();

							$statement = $this->modeloEmisionCertificado->getAdapter()
								->getDriver()
								->createStatement();
							$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('detalle_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
							$sqlInsertar->columns($lnegocioDetalleEmisionCertificado->columnas());
							$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
							$statement->execute();
						}
						$statement = $this->modeloEmisionCertificado->getAdapter()
							->getDriver()
							->createStatement();
						$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('detalle_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
						$sqlInsertar->columns($lnegocioDetalleEmisionCertificado->columnas());
						$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
						$statement->execute();
						// /*********************************SUBPRODUCTOS*******************************
					}else if ($_POST['producto_movilizar'] == 'Subproductos'){

						$substraer = explode('-', $datos['subproducto']);
						$datos['id_productos'] = $substraer[0];
						$datos['subproducto'] = $substraer[1];
						$datos['id_subproductos'] = $substraer[2];
						$lnegocioDetalleEmisionCertificado = new DetalleEmisionCertificadoLogicaNegocio();
						$datosEmision = array(
							'id_emision_certificado' => $idRegistro,
							'producto_movilizar' => $datos['producto_movilizar'],
							'tipo_especie' => $datos['tipo_especie'],
							'tipo_producto_movilizar_canal' => null,
							'codigo_canal' => null,
							'destino' => null,
							'id_productos' => (isset($datos['id_productos'])) ? $datos['id_productos'] : null,
							'fecha_produccion' => $datos['fecha_produccion'],
							'tipo_movilizacion_canal' => null);
						$statement = $this->modeloEmisionCertificado->getAdapter()
							->getDriver()
							->createStatement();
						$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('detalle_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
						$sqlInsertar->columns($lnegocioDetalleEmisionCertificado->columnas());
						$sqlInsertar->values($datosEmision, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
						$statement->execute();
						$idDetalleEmisionCertificado = $this->modeloEmisionCertificado->adapter->driver->getLastGeneratedValue($this->modeloEmisionCertificado->getEsquema() . '.detalle_emision_certificado_id_detalle_emision_certificado_seq');

						if (! $idDetalleEmisionCertificado){
							throw new \Exception('No se registo los datos en la tabla detalle_emision_certificado');
						}

						$lNegocioSubproductoEmisionCertificado = new SubproductosEmisionCertificadoLogicaNegocio();
						$arrayDatos = array(
							"fecha_creacion" => date('Y-m-d'),
							'estado' => 'creado',
							'identificador_operador' => $_SESSION['usuario']);
						$resultado = $lNegocioSubproductoEmisionCertificado->buscarSubproductosEmision($arrayDatos);

						if ($resultado->count()){
							$num = $resultado->count() + 1;
							$loteMovilizar = str_pad($num, 3, "0", STR_PAD_LEFT);
						}else{
							$num = 1;
							$loteMovilizar = str_pad($num, 3, "0", STR_PAD_LEFT);
						}

						$lnegocioSubproductosEmisionCertificado = new SubproductosEmisionCertificadoLogicaNegocio();
						$datos = array(
							'id_detalle_emision_certificado' => $idDetalleEmisionCertificado,
							'subproducto' => $datos['subproducto'],
							'cantidad_movilizar' => $datos['cantidad_movilizar'],
							'saldo_disponible' => $datos['saldo_disponible'],
							'id_subproductos' => $datos['id_subproductos'],
							'lote_movilizar' => $loteMovilizar);
						$statement = $this->modeloEmisionCertificado->getAdapter()
							->getDriver()
							->createStatement();
						$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('subproductos_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
						$sqlInsertar->columns($lnegocioSubproductosEmisionCertificado->columnas());
						$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
						$statement->execute();
					}
				}else{
					throw new \Exception('No existe productos..!!');
				}
			}else{
				// enviar correo
			}
			$proceso->commit();
			return $idRegistro;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}

	// ********************************************************************************
	public function guardarProductosMovilizarMenor(Array $datos){
		try{
			$this->modeloEmisionCertificado = new EmisionCertificadoModelo();
			$proceso = $this->modeloEmisionCertificado->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Guardar productos');
			}
			$datos['identificador_operador'] = $_SESSION['usuario'];
			$substraer = explode('-', $datos['identificador_movilizacion']);
			$datos['identificador_movilizacion'] = $substraer[0];
			$substraer = explode('-', $datos['sitio_origen']);
			$datos['sitio_origen'] = $substraer[0];
			$substraer = explode('-', $datos['area_origen']);
			$datos['area_origen'] = $substraer[0];

			$datos['id_productos'] = $_POST['id_productos'];

			$tablaModelo = new EmisionCertificadoModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			if ($tablaModelo->getIdEmisionCertificado() != null && $tablaModelo->getIdEmisionCertificado() > 0){
				$this->modeloEmisionCertificado->actualizar($datosBd, $tablaModelo->getIdEmisionCertificado());
				$idRegistro = $tablaModelo->getIdEmisionCertificado();
			}else{
				unset($datosBd["id_emision_certificado"]);
				$idRegistro = $this->modeloEmisionCertificado->guardar($datosBd);
			}
			if (! $idRegistro){
				throw new \Exception('No se registo los datos en la tabla emision_certificado');
			}
			// *************guadar detalle de productos*************
			if ($datos['contenedor'] == 'Si'){
				if (isset($datos['tipo_especie'])){

					if (isset($datos['tipo_producto_movilizar_canal'])){
						$datos['tipo_producto_movilizar_canal'] = ($datos['tipo_producto_movilizar_canal'] != '') ? $datos['tipo_producto_movilizar_canal'] : null;
					}else{
						$datos['tipo_producto_movilizar_canal'] = '';
					}
					$lnegocioDetalleEmisionCertificado = new DetalleEmisionCertificadoLogicaNegocio();
					// $verificarLote = $lnegocioDetalleEmisionCertificado->buscarLista("fecha_creacion::date =".date('Y-m-d'));
					$arrayParametros = array(
						'identificador_operador' => $_SESSION['usuario'],
						'fecha_creacion' => date('Y-m-d'),
						'tipo_especie' => $datos['tipo_especie'],
					    'producto_movilizar' => 'Subproductos'
					);
					$verificarLote = $lnegocioDetalleEmisionCertificado->obtenerDetalleEmisionCertificadoLista($arrayParametros);
					$codigoCanal = str_pad(1, 3, "0", STR_PAD_LEFT);
					if ($verificarLote->count()){
						$num = $verificarLote->count() + 1;
						$codigoCanal = str_pad($num, 3, "0", STR_PAD_LEFT);
					}
					$datos['codigo_canal'] = $codigoCanal;

					$datos = array(
						'id_emision_certificado' => $idRegistro,
						'producto_movilizar' => $datos['producto_movilizar'],
						'tipo_especie' => $datos['tipo_especie'],
						'tipo_producto_movilizar_canal' => $datos['tipo_producto_movilizar_canal'],
						'codigo_canal' => ($datos['codigo_canal'] != '') ? $datos['codigo_canal'] : null,
						'destino' => null,
						'subproducto' => null,
						'saldo_disponible' => (isset($datos['saldo_disponible'])) ? $datos['saldo_disponible'] : null,
						'cantidad_movilizar' => (isset($datos['cantidad_movilizar'])) ? $datos['cantidad_movilizar'] : null,
						'id_productos' => (isset($datos['id_productos'])) ? $datos['id_productos'] : null,
						'fecha_produccion' => $datos['fecha_produccion'],
						'tipo_movilizacion_canal' => (isset($datos['tipo_movilizacion_canal'])) ? $datos['tipo_movilizacion_canal'] : null);
					$statement = $this->modeloEmisionCertificado->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloEmisionCertificado->guardarSql('detalle_emision_certificado', $this->modeloEmisionCertificado->getEsquema());
					$sqlInsertar->columns($lnegocioDetalleEmisionCertificado->columnas());
					$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloEmisionCertificado->getAdapter(), $statement);
					$statement->execute();
				}else{
					throw new \Exception('No existe productos..!!');
				}
			}else{
				// enviar correo
			}
			$proceso->commit();
			return $idRegistro;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloEmisionCertificado->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return EmisionCertificadoModelo
	 */
	public function buscar($id){
		return $this->modeloEmisionCertificado->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloEmisionCertificado->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloEmisionCertificado->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarEmisionCertificado(){
		$consulta = "SELECT * FROM " . $this->modeloEmisionCertificado->getEsquema() . ". emision_certificado";
		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * certificación de origen
	 */
	public function buscarCentroFaenamiento($arrayParametros){
		$busqueda = '';
		if (array_key_exists('identificador_operador', $arrayParametros)){
			$busqueda .= " and o.identificador = '" . $arrayParametros['identificador_operador'] . "'";
		}
		if (array_key_exists('razon_social', $arrayParametros)){
			$busqueda .= " and o.razon_social = '" . $arrayParametros['razon_social'] . "'";
		}
		if (array_key_exists('provincia', $arrayParametros)){
			$busqueda .= " and upper(s.provincia) = upper('" . $arrayParametros['provincia'] . "')";
		}
		if (array_key_exists('id_sitio', $arrayParametros)){
			$busqueda .= " and s.id_sitio = " . $arrayParametros['id_sitio'];
		}

		$consulta = "SELECT
                    	o.identificador as identificador_operador,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
                    	s.provincia,
                        string_agg(distinct stp.nombre,', ') as especie,
                        s.id_sitio, s.nombre_lugar,
                        a.id_area, a.nombre_area,
                        op.id_operador_tipo_operacion,
                        cf.id_centro_faenamiento,
                        cf.criterio_funcionamiento,
                        cf.codigo,
                        cf.tipo_centro_faenamiento,
                        cf.tipo_habilitacion,
                        o.identificador||'.'||s.codigo_provincia||''||s.codigo||''||a.codigo||''||a.secuencial as codigo,
                        s.canton
                    FROM
                    	g_operadores.operadores o
                    	INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                        INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                        INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion
                        INNER JOIN g_catalogos.productos p ON p.id_producto = op.id_producto
                        INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = p.id_subtipo_producto
                        LEFT JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_sitio = s.id_sitio and cf.id_area = a.id_area and cf.id_operador_tipo_operacion = op.id_operador_tipo_operacion
                    WHERE
                        top.id_area = '" . $arrayParametros['id_area_tipo_operacion'] . "'
                        and top.codigo = '" . $arrayParametros['codigo'] . "'
                        and op.estado in ('registrado')
                        and cf.criterio_funcionamiento in ('Habilitado','Activo')
                        " . $busqueda . "
                    GROUP BY
                        o.identificador, s.provincia, s.id_sitio, a.id_area, op.id_operador_tipo_operacion, cf.id_centro_faenamiento;";

		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * certificación de origen
	 */
	public function buscarCentroFaenamientoSitio($arrayParametros){
		$busqueda = '';
		if (array_key_exists('identificador_operador', $arrayParametros)){
			$busqueda .= " and o.identificador = '" . $arrayParametros['identificador_operador'] . "'";
		}
		if (array_key_exists('razon_social', $arrayParametros)){
			$busqueda .= " and o.razon_social = '" . $arrayParametros['razon_social'] . "'";
		}
		if (array_key_exists('provincia', $arrayParametros)){
			$busqueda .= " and upper(s.provincia) = upper('" . $arrayParametros['provincia'] . "')";
		}
		if (array_key_exists('id_sitio', $arrayParametros)){
			$busqueda .= " and s.id_sitio = " . $arrayParametros['id_sitio'];
		}

		$consulta = "SELECT
                    	distinct o.identificador as identificador_operador,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
                    	s.provincia,
                        string_agg(distinct stp.nombre,', ') as especie,
                        s.id_sitio, s.nombre_lugar
                    FROM
                    	g_operadores.operadores o
                    	INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                        INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                        INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion
                        INNER JOIN g_catalogos.productos p ON p.id_producto = op.id_producto
                        INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = p.id_subtipo_producto
                        LEFT JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_sitio = s.id_sitio and cf.id_area = a.id_area and cf.id_operador_tipo_operacion = op.id_operador_tipo_operacion
                    WHERE
                        top.id_area = '" . $arrayParametros['id_area_tipo_operacion'] . "'
                        and top.codigo = '" . $arrayParametros['codigo'] . "'
                        and op.estado in ('registrado')
                        and cf.criterio_funcionamiento in ('Habilitado','Activo')
                        " . $busqueda . "
                    GROUP BY
                        o.identificador, s.provincia, s.id_sitio;";

		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/*
	 * obtener las areas de centros de faenamiento
	 */
	public function buscarAreaXSitioCentroFaenamiento($arrayParametros){
		$consulta = "
                    SELECT
                        a.id_area,
                        a.nombre_area,
                        cf.id_centro_faenamiento
                    FROM  g_operadores.areas a
                        INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                        INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion
                        INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_sitio = " . $arrayParametros['id_sitio'] . "
                        and cf.id_area = a.id_area and cf.id_operador_tipo_operacion = op.id_operador_tipo_operacion
                    WHERE
                        top.id_area = '" . $arrayParametros['id_area_tipo_operacion'] . "'
                        and top.codigo = '" . $arrayParametros['codigo'] . "'
                        and op.estado in ('registrado')
                        and cf.criterio_funcionamiento in ('Habilitado','Activo')
                    GROUP BY a.id_area, cf.id_centro_faenamiento;";
		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los sitios y áreas
	 *
	 * @return array
	 */
	public function obtenerTransporteXIdentificador($arrayParametros){
		$busqueda = '';
		if (array_key_exists('razon_social', $arrayParametros)){
			if ($arrayParametros['razon_social'] != ''){
				$busqueda = " and razon_social ILIKE '" . $arrayParametros['razon_social'] . "%'";
			}
		}
		if (array_key_exists('identificador', $arrayParametros)){
			if ($arrayParametros['identificador'] != ''){
				$busqueda .= " and identificador = '" . $arrayParametros['identificador'] . "'";
			}
		}
		if (array_key_exists('id_operador_tipo_operacion', $arrayParametros)){
			if ($arrayParametros['id_operador_tipo_operacion'] != ''){
				$busqueda .= " and dv.id_operador_tipo_operacion = '" . $arrayParametros['id_operador_tipo_operacion'] . "'";
			}
		}
		if (array_key_exists('id_centro_faenamiento', $arrayParametros)){
			if ($arrayParametros['id_centro_faenamiento'] != ''){
				$busqueda .= " and cft.id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'];
			}
		}

		$consulta = "SELECT
							distinct identificador,
							case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador,
							placa_vehiculo, dv.id_operador_tipo_operacion, dv.id_dato_vehiculo
					 FROM
						g_operadores.operaciones op
					 	INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
					 	INNER JOIN g_catalogos.tipos_operacion tope on op.id_tipo_operacion = tope.id_tipo_operacion
					 	INNER JOIN g_catalogos.productos pro ON op.id_producto = pro.id_producto
					 	INNER JOIN g_catalogos.subtipo_productos stp ON pro.id_subtipo_producto = stp.id_subtipo_producto
					    INNER JOIN g_operadores.datos_vehiculos dv ON dv.id_operador_tipo_operacion = op.id_operador_tipo_operacion
						INNER JOIN g_operadores.productos_areas_operacion poa ON poa.id_operacion = op.id_operacion
                        INNER JOIN g_operadores.centros_faenamiento_transporte cft ON cft.id_operacion = op.id_operacion
					 WHERE
					 	tope.codigo = 'MDC' and tope.id_area = 'AI' 
					 	and op.estado = 'registrado'
						" . $busqueda . ";";

		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function sumarProduccion($arrayParametros){
		$consulta = "
                    SELECT
                            sum(cantidad) as resultado
                    FROM
                           g_emision_certificacion_origen.subproductos
                    WHERE
                           id_productos = " . $arrayParametros['id_productos'] . " and subproducto='" . $arrayParametros['subproducto'] . "';";
		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los sitios y áreas
	 *
	 * @return array
	 */
	public function filtrarInformacion($arrayParametros){
		$busqueda = '';
		$ban = 1;
		if (array_key_exists('sitio_origen', $arrayParametros)){
			if ($arrayParametros['sitio_origen'] != ''){
				$busqueda .= "and sitio_origen = " . $arrayParametros['sitio_origen'];
			}
		}
		if (array_key_exists('numero_certificado', $arrayParametros)){
			if ($arrayParametros['numero_certificado'] != ''){
				$busqueda .= " and numero_certificado = '" . $arrayParametros['numero_certificado'] . "'";
			}
		}
		if (array_key_exists('estado', $arrayParametros)){
			if ($arrayParametros['estado'] != ''){
				$busqueda .= " and estado = '" . $arrayParametros['estado'] . "'";
			}
		}
		if (array_key_exists('fechaInicio', $arrayParametros) && array_key_exists('fechaFin', $arrayParametros)){
			if ($arrayParametros['fechaInicio'] != '' && $arrayParametros['fechaFin'] != ''){
				$ban = 0;
				$busqueda .= " and fecha_creacion::date BETWEEN '" . $arrayParametros['fechaInicio'] . "' AND '" . $arrayParametros['fechaFin'] . "'";
			}
		}
		if ((array_key_exists('fechaInicio', $arrayParametros) || array_key_exists('fechaFin', $arrayParametros)) && $ban){
			if ($arrayParametros['fechaInicio'] != ''){
				$busqueda .= " and fecha_creacion::date >= '" . $arrayParametros['fechaInicio'] . "'";
			}elseif ($arrayParametros['fechaFin'] != ''){
				$busqueda .= " and fecha_creacion::date <= '" . $arrayParametros['fechaFin'] . "'";
			}
		}

		$consulta = "SELECT
							*
					 FROM
						g_emision_certificacion_origen.emision_certificado
					 WHERE
                        identificador_operador = '" . $arrayParametros['identificador_operador'] . "'
						" . $busqueda . ";";

		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el codigo de la provincia
	 *
	 * @return array
	 */
	public function obtenerCodigoVueLocalizacion($arrayParametros){
		$consulta = "SELECT
							codigo_vue
					  FROM
							g_catalogos.localizacion
					  WHERE
							id_localizacion = " . $arrayParametros['id_localizacion'] . ";";

		$resultado = $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
		return $resultado;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el nombre del sitio
	 *
	 * @return array
	 */
	public function obtenerSecuencialEmision($arrayParametros){
		$consulta = "SELECT
						COALESCE(count(*)::numeric+1, 0) AS numero
					FROM
						g_emision_certificacion_origen.emision_certificado
					WHERE
						identificador_operador = '" . $arrayParametros['identificador_operador'] . "';";

		$resultado = $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
		return $resultado;
	}

	/**
	 * Notificar envío de emails
	 */
	public function notificarEmail($arrayEmail){
		$asunto = 'Emisión de certificación de origen';
		$familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";

		$cuerpoMensaje = '<table><tbody>
			<tr><td style="' . $familiaLetra . '; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
            <tr><td style="' . $familiaLetra . '; padding-top:30px; font-size:14px;color:#2a2a2a;">Se le comunica que usted tiene pendiente la inspección del contenedor registrado por el siguiente operador: </td></tr>
			<tr><td style="' . $familiaLetra . '; padding-top:30px; font-size:14px;color:#2a2a2a;">Nombre Operador: ' . $arrayEmail['nombre_operador'] . '<br><br>Identificación Operador: ' . $arrayEmail['identificador_operador'] . ' <br><br>Sitio: ' . $arrayEmail['sitio'] . '<br><br>Provincia: ' . $arrayEmail['provincia'] . ' <br><br>Cantón: ' . $arrayEmail['canton'] . '<br><br>Parroquia: ' . $arrayEmail['parroquia'] . ' <br><br>Dirección: ' . $arrayEmail['direccion'] . ' <br><br>Operación: ' . $arrayEmail['operacion'] . '</td></tr>
			<tr><td style="' . $familiaLetra . '; padding-top:30px; font-size:14px;color:#2a2a2a;">Ingrese al siguiente link para revisar dicho registro:<br>  </td></tr>
			<tr><td style="' . $familiaLetra . '; padding-top:30px; font-size:14px;color:#2a2a2a;"><a>https://guia.agrocalidad.gob.ec</a><br>  </td></tr>
            <tr><td style="' . $familiaLetra . '; padding-top:30px; font-size:14px;color:#2a2a2a;">NOTA: Este correo fue generado automáticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
			</tbody></table>';

		$arrayMailsDestino = array();
		$arrayConsulta = array();
		$datos = $this->buscarEmail($arrayConsulta);
		if ($datos->count()){
			foreach ($datos as $value){
				if ($value['mail_institucional'] != ''){
					$arrayMailsDestino[] = $value['mail_institucional'];
				}elseif ($value['mail_personal'] != ''){
					$arrayMailsDestino[] = $value['mail_personal'];
				}
			}
		}
		$arrayConsulta = array(
			'provincia' => $arrayEmail['provincia']);
		$datos = $this->buscarEmail($arrayConsulta);
		if ($datos->count()){
			foreach ($datos as $value){
				if ($value['mail_institucional'] != ''){
					$arrayMailsDestino[] = $value['mail_institucional'];
				}elseif ($value['mail_personal'] != ''){
					$arrayMailsDestino[] = $value['mail_personal'];
				}
			}
		}
		$mailsDestino = array_unique($arrayMailsDestino);
		if (count($mailsDestino) > 0){
			$datosCorreo = array(
				'asunto' => $asunto,
				'cuerpo' => $cuerpoMensaje,
				'codigo_modulo' => "PRG_EMI_CERT_ORI",
				'tabla_modulo' => "g_emision_certificacion_origen.emision_certificado",
				'id_solicitud_tabla' => $arrayEmail['id_emision_certificado'],
				'estado' => 'Por enviar');
			$modeloCorreos = new \Agrodb\Correos\Modelos\CorreosModelo();
			$idCorreo = $modeloCorreos->guardar($datosCorreo);

			// Guardar correo del destino
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			foreach ($mailsDestino as $val){
				$datosDestino = array(
					'id_correo' => $idCorreo,
					'destinatario_correo' => $val);
				$destino->guardar($datosDestino);
			}
		}else{
			throw new \Exception(Constantes::EMAIL_INF_VACIO);
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada información del operador.
	 *
	 * @return array
	 */
	public function buscarDatosOperador($identificadorOperador){
		$consulta = "SELECT
					    case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador,
						o.correo
					 FROM
						g_operadores.operadores o
					 WHERE
						o.identificador = '" . $identificadorOperador . "' ;";

		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada información del operador.
	 *
	 * @return array
	 */
	public function buscarEmail($arrayParametros){
		$busqueda = '';
		if (array_key_exists('provincia', $arrayParametros)){
			$busqueda .= " and c.provincia = '" . $arrayParametros['provincia'] . "'";
		}else{
			$busqueda .= " and c.oficina = 'Oficina Planta Central'";
		}
		$consulta = "SELECT 
                            fe.mail_personal, fe.mail_institucional 
                     FROM 
                            g_uath.datos_contrato c
                            inner join g_programas.aplicaciones_registradas ar on ar.identificador = c.identificador
                            inner join g_programas.aplicaciones a on a.id_aplicacion = ar.id_aplicacion and a.codificacion_aplicacion='PRG_EMI_CERT_ORI'
                            inner join g_usuario.usuarios_perfiles up on up.identificador = ar.identificador 
                            and id_perfil= (SELECT id_perfil FROM g_usuario.perfiles WHERE codificacion_perfil ='PFL_EMI_CERT')
                            inner join g_uath.ficha_empleado fe on fe.identificador = c.identificador and fe.estado_empleado = 'activo'
                     WHERE 
                            c.estado = 1 
                            " . $busqueda . ";";

		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}

	/**
	 * *
	 * Buscar información de trasnportista
	 */
	public function buscarInfoTrasnportista($idDatoVehiculo){
		$consulta = "
                    SELECT 
                        s.nombre_lugar as sitio, s.provincia, s.canton, s.parroquia, s.direccion, t.nombre as operacion,
                        o.identificador,o.nombre_representante ||' '|| o.apellido_representante as nombre_operador
                    FROM 
                    	g_operadores.datos_vehiculos dv inner join
                    	g_operadores.areas a on a.id_area = dv.id_area inner join 
                    	g_operadores.sitios s on s.id_sitio = a.id_sitio inner join 
                    	g_catalogos.tipos_operacion t on dv.id_tipo_operacion = t.id_tipo_operacion inner join
                        g_operadores.operadores o on o.identificador=s.identificador_operador
                    WHERE id_dato_vehiculo=" . $idDatoVehiculo . ";";
		return $this->modeloEmisionCertificado->ejecutarSqlNativo($consulta);
	}
}
