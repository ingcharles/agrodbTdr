<?php
/**
 * Lógica del negocio de MovilizacionModelo
 *
 * Este archivo se complementa con el archivo MovilizacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-04-03
 * @uses MovilizacionLogicaNegocio
 * @package MovilizacionSueros
 * @subpackage Modelos
 */
namespace Agrodb\MovilizacionSueros\Modelos;

use TCPDF;

class MovilizacionLogicaNegocio implements IModelo{
	
	private $modeloMovilizacion = null;
	
	
	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloMovilizacion = new MovilizacionModelo();
		
	}
	
	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		
		$tablaModelo = new MovilizacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdMovilizacion() != null && $tablaModelo->getIdMovilizacion() > 0){
			return $this->modeloMovilizacion->actualizar($datosBd, $tablaModelo->getIdMovilizacion());
		}else{
			unset($datosBd["id_movilizacion"]);
			$datosBd['identificador_operador'] = $_SESSION['usuario'];
			return $this->modeloMovilizacion->guardar($datosBd);
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
		$this->modeloMovilizacion->borrar($id);
	}
	
	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return MovilizacionModelo
	 */
	public function buscar($id){
		return $this->modeloMovilizacion->buscar($id);
	}
	
	/**
	 * Busca todos los registros
	 *
	 * @return array
	 */
	public function buscarTodo(){
		return $this->modeloMovilizacion->buscarTodo();
	}
	
	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloMovilizacion->buscarLista($where, $order, $count, $offset);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array
	 */
	public function buscarMovilizacion(){
		$consulta = "SELECT * FROM " . $this->modeloMovilizacion->getEsquema() . ". movilizacion";
		return $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los sitios y áreas
	 *
	 * @return array
	 */
	public function obtenerSitiosAreasXIdentificador($arrayParametros){
		$consulta = "SELECT
						distinct st.nombre_lugar,
						st.id_sitio, a.id_area,
						a.nombre_area,
						st.codigo_provincia
					FROM
						g_operadores.operaciones op
						INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
						INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
						INNER JOIN g_operadores.sitios st on a.id_sitio = st.id_sitio
						INNER JOIN g_catalogos.tipos_operacion tope on op.id_tipo_operacion = tope.id_tipo_operacion
					WHERE
						op.identificador_operador ='" . $arrayParametros['identificador_operador'] . "' AND
						tope.codigo = 'INL' AND
						tope.id_area = 'AI' AND op.estado = 'registrado'
						;";
		
		$resultado = $this->modeloMovilizacion->ejecutarConsulta($consulta);
		
		return $resultado;
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
				$busqueda = "and razon_social ILIKE '" . $arrayParametros['razon_social'] . "%'";
			}
		}
		if (array_key_exists('identificador', $arrayParametros)){
			if ($arrayParametros['identificador'] != ''){
				$busqueda .= "and identificador = '" . $arrayParametros['identificador'] . "'";
			}
		}
		if (array_key_exists('id_operador_tipo_operacion', $arrayParametros)){
			if ($arrayParametros['id_operador_tipo_operacion'] != ''){
				$busqueda .= "and dv.id_operador_tipo_operacion = '" . $arrayParametros['id_operador_tipo_operacion'] . "'";
			}
		}
		
		$consulta = "SELECT
							distinct identificador,
							case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador,
							placa_vehiculo, dv.id_operador_tipo_operacion, codigo_operador_leche
					 FROM
						g_operadores.operaciones op
					 	INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
					 	INNER JOIN g_catalogos.tipos_operacion tope on op.id_tipo_operacion = tope.id_tipo_operacion
					 	INNER JOIN g_catalogos.productos pro ON op.id_producto = pro.id_producto
					 	INNER JOIN g_catalogos.subtipo_productos stp ON pro.id_subtipo_producto = stp.id_subtipo_producto
					    INNER JOIN g_operadores.datos_vehiculos dv ON dv.id_operador_tipo_operacion = op.id_operador_tipo_operacion
						INNER JOIN g_operadores.productos_areas_operacion poa ON poa.id_operacion = op.id_operacion
					 	INNER JOIN g_operadores.codigos_operadores_leche col ON col.id_area = poa.id_area
					 WHERE
					 	tope.codigo = 'MDT' and tope.id_area = 'AI' and
					 	op.estado = 'registrado'
						" . $busqueda . ";";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los productos "industria láctea"
	 *
	 * @return array
	 */
	public function obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros){
		$consulta = "SELECT
						opr.id_producto, opr.nombre_producto
					 FROM
						g_operadores.operaciones opr
						INNER JOIN g_operadores.productos_areas_operacion pao ON opr.id_operacion = pao.id_operacion
						INNER JOIN g_catalogos.productos pr on pr.id_producto=opr.id_producto
						INNER JOIN g_catalogos.subtipo_productos spr on spr.id_subtipo_producto = pr.id_subtipo_producto
						INNER JOIN g_catalogos.tipo_productos tpr on tpr.id_tipo_producto = spr.id_tipo_producto
					  WHERE
						spr.codificacion_subtipo_producto = '" . $arrayParametros['codificacion_subtipoprod'] . "'
						and identificador_operador ='" . $arrayParametros['identificador_operador'] . "'
						AND pao.id_area = " . $arrayParametros['id_area'] . ";";
		
		$datosTipoQueso = $this->modeloMovilizacion->ejecutarConsulta($consulta);
		
		return $datosTipoQueso;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para verificar cantidad de suero a movilizar
	 *
	 * @return array
	 */
	public function vericarCantidadSuero($arrayParametros){
		$consulta = "SELECT
						    sum(dcs.cantidad_suero_restante) as total
			
						FROM
						     g_movilizacion_suero.produccion pr
						     INNER JOIN  g_movilizacion_suero.detalle_cantidad_suero dcs on dcs.id_produccion = pr.id_produccion
						WHERE
						     identificador = '" . $arrayParametros['identificador_operador'] . "' and
						     id_producto_suero = " . $arrayParametros['idProductoSuero'] . " and
						     dcs.estado in ('creado','pendiente') and
							 pr.estado in ('creado');";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		
		return $resultado;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el uso del suero
	 *
	 * @return array
	 */
	public function obtenerUsoSuero($arrayParametros){
		$busqueda = '';
		if (array_key_exists('id_uso_suero', $arrayParametros)){
			$busqueda = "and id_uso_suero = " . $arrayParametros['id_uso_suero'];
		}
		$consulta = "SELECT
							id_uso_suero,
							descripcion
					  FROM
							g_catalogos.uso_suero
					  WHERE
							estado = '" . $arrayParametros['estado'] . "'
						     " . $busqueda . " ;";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el detalle uso del suero
	 *
	 * @return array
	 */
	public function obtenerDetalleUsoSuero($arrayParametros){
		$busqueda = '';
		if (array_key_exists('id_uso_suero', $arrayParametros)){
			$busqueda = "and id_uso_suero = " . $arrayParametros['id_uso_suero'];
		}
		if (array_key_exists('id_detalle_uso_suero', $arrayParametros)){
			$busqueda .= " and id_detalle_uso_suero = " . $arrayParametros['id_detalle_uso_suero'];
		}
		$consulta = "SELECT
							id_detalle_uso_suero,
							descripcion
					  FROM
							g_catalogos.detalle_uso_suero
					  WHERE
							estado = '" . $arrayParametros['estado'] . "'
						     " . $busqueda . " ;";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
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
							id_localizacion = '" . $arrayParametros['id_localizacion'] . "';";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el nombre del sitio
	 *
	 * @return array
	 */
	public function obtenerSitioOrigen($arrayParametros){
		$consulta = "SELECT
						distinct st.nombre_lugar, provincia, canton, parroquia
					FROM
						g_operadores.operaciones op
						INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
						INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
						INNER JOIN g_operadores.sitios st on a.id_sitio = st.id_sitio
						INNER JOIN g_catalogos.tipos_operacion tope on op.id_tipo_operacion = tope.id_tipo_operacion
					WHERE
						op.identificador_operador ='" . $arrayParametros['identificador_operador'] . "' AND
						tope.codigo = 'INL' AND
						a.id_area = " . $arrayParametros['idArea'] . " and
						tope.id_area = 'AI' and
						st.id_sitio = " . $arrayParametros['idSitio'] . " ;";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el nombre del sitio
	 *
	 * @return array
	 */
	public function obtenerSecuencialMovilizacion($arrayParametros){
		$consulta = "SELECT
						COALESCE(count(*)::numeric, 0) AS numero
					FROM
						g_movilizacion_suero.movilizacion
					WHERE
						identificador_operador = '" . $arrayParametros['identificador_operador'] . "';";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el nombre de la localizacion
	 *
	 * @return array
	 */
	public function obtenerNombreLocalizacion($idLocalizacion){
		$consulta = "SELECT
						nombre
					 FROM
						g_catalogos.localizacion
					 WHERE
						id_localizacion = " . $idLocalizacion . "";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	/**
	 * extraer y unificar datos de un array
	 */
	public function unificarDatosArray($array,$idMovilizacion){
		$resultado = array();
		$resultadoFinal = array();
		$data = json_decode($array);
		foreach ( $data as $item ){
			$resultado[] = $item->idProducto;
		}
		$unificar = array_unique($resultado, SORT_REGULAR);
		foreach ( $unificar as $item ){
			$valor1 = $valor2 = '';
			$valor3 = 0;
			foreach ( $data as $valor ){
				if($valor->idProducto == $item){
					$valor1 = $valor->idProducto;
					$valor2 = $valor->producto;
					$valor3 = $valor3 + $valor->cantidad;
				}
			}
			$resultadoFinal[] = array('id_producto' => $valor1, 'nombre_producto' => $valor2, 'cantidad_producto' => $valor3, 'id_movilizacion' => $idMovilizacion);
		}
		return $resultadoFinal;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el nombre del operador
	 *
	 * @return array
	 */
	public function obtenerNombresOperador($identificador_operador){
		$consulta = "
					SELECT
      						apellido_representante ||' '|| nombre_representante as operador
					FROM
							g_operadores.operadores o
					WHERE
					     	o.identificador = '".$identificador_operador."';";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener la razon social
	 *
	 * @return array
	 */
	public function obtenerRazonSocial($arrayParametros){
		$consulta = "
					SELECT
							razon_social
  					FROM
							g_operadores.operadores
					WHERE
							identificador = '".$arrayParametros['identificador']."';";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener certificados caducados
	 *
	 * @return array
	 */
	public function obtenerCertificadoCaducado(){
		$consulta = "
					SELECT
							id_movilizacion, fecha_creacion, identificador_operador
  					FROM
							g_movilizacion_suero.movilizacion
					WHERE
							(SELECT now()) > (fecha_creacion+'2 day'::interval) and
							estado in ('Vigente') ;";
		
		$resultado = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	
	// ***************************Generar certificado laboral****************************************
	public function generarCertificadoMovilización($arrayDatosGenerales, $arrayDatosOrigen, $arrayDatosDestino, $arrayDatosMovilizacion, $arrayDatosFoot, $arrayDetalleMovilizacion){
		ob_start();
		// ************************************************** INICIO ***********************************************************
		
		$margen_superior = 40;
		$margen_inferior = 15;
		$margen_izquierdo = 6;
		$margen_derecho = 4;
		
		$pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8');
		$tipoLetra = 'helvetica';
		$pdf->SetLineWidth(0.1);
		$pdf->setCellHeightRatio(1.5);
		$pdf->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$pdf->SetAutoPageBreak(TRUE, $margen_inferior);
		$pdf->SetFont($tipoLetra, '', 9);
		$pdf->AddPage();
		
		// ***********************************QR*************************************
		$infoQRmov = '
        No. Certificado: ' . $arrayDatosGenerales['numeroCertificado'] . '
        Fecha Inicio Vigencia: ' . $arrayDatosGenerales['fechaInicioVigencia'] . '
        Fecha Fin Vigencia: ' . $arrayDatosGenerales['fechaFinVigencia'] . '
        Placa Transporte: ' . $arrayDatosMovilizacion['placaTransporte'] . '
		Total Productos: ' . count($arrayDetalleMovilizacion) . '';
		
		// ****************************************************************************
		$pdf->SetTextColor();
		$pdf->SetFont($tipoLetra, 'B', 12);
		$y = 28;
		$pdf->writeHTMLCell(0, 0, $margen_izquierdo, $y, $arrayDatosGenerales['nombreCertificado'], '', 1, 0, true, 'C', true);
		$pdf->SetFont($tipoLetra, 'B', 8);
		$pdf->writeHTMLCell(0, 0, $margen_izquierdo + (209 / 2) - 31, $y + 8, 'N° CERTIFICADO: ' . $arrayDatosGenerales['numeroCertificado'], '', 1, 0, true, 'L', true);
		$pdf->SetFont($tipoLetra, '', 8);
		
		$y = $pdf->GetY() + 3;
		$tamañoColumn = 209 - $margen_izquierdo - $margen_derecho;
		$alto = 16;
		$pdf->crearTabla($tipoLetra, $margen_izquierdo, $tamañoColumn, $y, '1. DATOS GENERALES', $alto);
		$ytxt = 5;
		$pdf->Text($margen_izquierdo, $y + $ytxt, 'Lugar Emisión:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->Text($margen_izquierdo + $xtxt, $y + $ytxt, $arrayDatosGenerales['lugarEmision']);
		$pdf->SetFont($tipoLetra, 'B', 7);
		$y = $pdf->GetY() + 4;
		$pdf->Text($margen_izquierdo, $y, 'Fecha Emisión:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->Text($margen_izquierdo + $xtxt, $y, $pdf->fecha('', 2, $arrayDatosGenerales['fechaEmision']));
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($margen_izquierdo + ($tamañoColumn / 2), $y, 'Fecha Inicio Vigencia:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->Text($margen_izquierdo + ($tamañoColumn / 2) + $xtxt, $y, $pdf->fecha('', 2, $arrayDatosGenerales['fechaInicioVigencia']));
		$y = $pdf->GetY() + 4;
		$pdf->SetFont($tipoLetra, 'B', 14);
		$pdf->writeHTMLCell(0, 0, $margen_izquierdo, $y, '<i>Fecha Fin Vigencia: ' . $pdf->fecha('', 2, $arrayDatosGenerales['fechaFinVigencia']) . '</i>', '', 0, 0, false, 'L', false);
		
		// ***********************************************************************************
		$y = $pdf->GetY();
		$ancho = ($tamañoColumn / 2) - 0.5;
		$y1 = $y + 8.5;
		$alto1 = 25;
		$pdf->crearTabla($tipoLetra, $margen_izquierdo, $ancho, $y1, '2. DATOS DE ORIGEN', $alto1);
		$ytxt = 5;
		$pdf->Text($margen_izquierdo, $y1 + $ytxt, 'Identificación de la industria láctea(RUC):');
		$xtxt = 49.5;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->Text($margen_izquierdo + $xtxt, $y1 + $ytxt, $arrayDatosOrigen['identificadorIndustria']);
		
		$y = $pdf->GetY() + 4;
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($margen_izquierdo, $y, 'Razón social de la industria láctea:');
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(53, 0, $margen_izquierdo + $xtxt, $y, $pdf->cortarTexto($arrayDatosOrigen['razonSocial'], 43), '', 0, 0, false, 'L', false);
		
		$y = $pdf->GetY() + 4;
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($margen_izquierdo, $y, 'Provincia:');
		$xtxt = 12;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(20, 0, $margen_izquierdo + $xtxt + 0.5, $y, $arrayDatosOrigen['provincia'], '', 0, 0, false, 'L', false);
		
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($margen_izquierdo + 30, $y, 'Cantón:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(20, 0, $margen_izquierdo + 12 + $xtxt, $y, $arrayDatosOrigen['canton'], '', 0, 0, false, 'L', false);
		
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($margen_izquierdo + 60, $y, 'Parroquia:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(20, 0, $margen_izquierdo + 45 + $xtxt, $y, $arrayDatosOrigen['parroquia'], '', 0, 0, false, 'L', false);
		
		$x = $ancho + $margen_izquierdo + 1;
		$pdf->crearTabla($tipoLetra, $x, $ancho, $y1, '3. DATOS DE DESTINO', $alto1);
		$ytxt = 5;
		$pdf->Text($x, $y1 + $ytxt, 'Identificación del destinatario (RUC):');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(60, 0, $x + 44, $y1 + $ytxt, $arrayDatosDestino['identificadorDestino'], '', 0, 0, false, 'L', false);
		
		$y = $pdf->GetY() + 4;
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($x, $y, 'Razón social del destinatario:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(60, 0, $x + 44, $y, $pdf->cortarTexto($arrayDatosDestino['razonSocial'], 47), '', 0, 0, false, 'L', false);
		
		$y = $pdf->GetY() + 4;
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($x, $y, 'Dirección:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(70, 0, $x + 44, $y, $pdf->cortarTexto($arrayDatosDestino['direccion'], 47), '', 0, 0, false, 'L', false);
		
		$y = $pdf->GetY() + 4;
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($x, $y, 'Uso:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(60, 0, $x + 44, $y, $pdf->cortarTexto($arrayDatosDestino['uso'], 47), '', 0, 0, false, 'L', false);
		
		$y = $pdf->GetY() + 4;
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($x, $y, 'Provincia:');
		$xtxt = 28;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(20, 0, $x + 12.5, $y, $arrayDatosDestino['provincia'], '', 0, 0, false, 'L', false);
		
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($x + 30, $y, 'Cantón:');
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(20, 0, $x + 40, $y, $arrayDatosDestino['canton'], '', 0, 0, false, 'L', false);
		
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($x + 60, $y, 'Parroquia:');
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(27, 0, $x + 73, $y, $arrayDatosDestino['parroquia'], '', 0, 0, false, 'L', false);
		
		// ***********************************************************************************
		$alto2 = 10;
		$y = $y + 9.5;
		$tamañoColumn = 209 - $margen_izquierdo - $margen_derecho;
		$pdf->crearTabla($tipoLetra, $margen_izquierdo, $tamañoColumn, $y, '4. DATOS DE MOVILIZACIÓN', $alto2);
		$ytxt = 5;
		$pdf->Text($margen_izquierdo, $y + $ytxt, 'Identificación conductor:');
		$xtxt = 49;
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(60, 0, $margen_izquierdo + $xtxt, $y + $ytxt, $arrayDatosMovilizacion['identificadorConductor'], '', 0, 0, false, 'L', false);
		
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($margen_izquierdo + ($tamañoColumn / 2), $y + $ytxt, 'Placa transporte:');
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(50, 0, $margen_izquierdo + ($tamañoColumn / 2) + $xtxt + 4, $y + $ytxt, $arrayDatosMovilizacion['placaTransporte'], '', 0, 0, false, 'L', false);
		
		$pdf->SetFont($tipoLetra, 'B', 7);
		$y = $pdf->GetY() + 4;
		$pdf->Text($margen_izquierdo, $y, 'Nombre conductor:');
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(52, 0, $margen_izquierdo + $xtxt, $y, $pdf->cortarTexto($arrayDatosMovilizacion['nombreConductor'], 31), '', 0, 0, false, 'L', false);
		
		$pdf->SetFont($tipoLetra, 'B', 7);
		$pdf->Text($margen_izquierdo + ($tamañoColumn / 2), $y, 'Número de resgitro del medio de transporte:');
		$pdf->SetFont($tipoLetra, '', 7);
		$pdf->writeHTMLCell(50, 0, $margen_izquierdo + ($tamañoColumn / 2) + $xtxt + 4, $y, $arrayDatosMovilizacion['numeroRegistroTransporte'], '', 0, 0, false, 'L', false);
		$y = $pdf->GetY() + 4;
		
		// ***********************************************************************************
		$alto2 = 10;
		$y = $y + 2.5;
		$tamañoColumn = 209 - $margen_izquierdo - $margen_derecho;
		$pdf->crearTablaHeader($tipoLetra, $margen_izquierdo, $tamañoColumn, $y, '5. DETALLE DEL PRODUCTO A MOVILIZAR', count($arrayDetalleMovilizacion));
		// ************************************************************************************
		$y = $pdf->GetY() + 8;
		$pdf->construirDetalleMovilizacion($tipoLetra, $margen_izquierdo, $y, $arrayDetalleMovilizacion, $margen_derecho);
		
		// ************************************************************************************
		$y = 296 - $margen_inferior - 50;
		$pdf->crearTablaFoot($tipoLetra, $margen_izquierdo, $tamañoColumn, $y, '6. FIRMAS Y SELLOS DE RESPONSABILIDAD', 33, $infoQRmov, $arrayDatosFoot);
		
		// ******************************* FIN DE LA EDICION ****************************************************************************************
		$pdf->Output(CERT_MOV_SUERO_TCPDF . "certificadosGenerados/" . $arrayDatosGenerales['nombreArchivo'] . ".pdf", 'F');
		ob_end_clean();
	}
}

// ********clase para tcpdf******************************************
class PDF extends TCPDF{
	
	// Page header
	public function Header(){
		$this->setJPEGQuality(90);
		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$img_file = RUTA_IMG_GENE . "fondoCertificado.png";
		$this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		$this->setPageMark();
	}
	
	public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false){
		parent::AddPage();
	}
	
	public function fecha($ciudad, $opt, $fecha){
		$date = new \DateTime($fecha);
		$meses = array(
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre");
		$dias = array(
			"domingo",
			"lunes",
			"martes",
			"miércoles",
			"jueves",
			"viernes",
			"sábado");
		if ($opt == 1){
			$fechaFinal = $ciudad . ', ' . $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
		}else if ($opt == 2){
			$fechaFinal = $dias[$date->format('w')] . ', ' . $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . '  ' . $date->format('H:i');
		}
		
		return $fechaFinal;
	}
	
	public function Footer(){
	}
	
	public function crearTabla($tipoLetra, $margenIzq, $ancho, $y, $txtHeader, $alto){
		$style = array(
			'width' => 0.4);
		$this->SetFont($tipoLetra, 'B', 7);
		$this->RoundedRect($margenIzq, $y, $ancho, 5, 1.20, '1001', 'DF', $style, array(
			200,
			200,
			200));
		$this->Text($margenIzq, $y + 0.3, $txtHeader);
		$y = $this->GetY() + 4;
		$this->RoundedRect($margenIzq, $y, $ancho, $alto, 1.20, '0110', 'DF', $style, array(
			255,
			255,
			255));
	}
	
	public function crearTablaHeader($tipoLetra, $margenIzq, $ancho, $y, $txtHeader, $total = ''){
		$style = array(
			'width' => 0.4);
		$this->SetFont($tipoLetra, 'B', 7);
		$this->RoundedRect($margenIzq, $y, $ancho, 5, 1.20, '1111', 'DF', $style, array(
			200,
			200,
			200));
		$this->Text($margenIzq, $y + 0.3, $txtHeader);
		$this->Text($margenIzq + 160, $y + 0.3, 'TOTAL PRODUCTOS:');
		$this->Text($margenIzq + 186.4, $y + 0.3, $total);
	}
	
	public function crearTablaFoot($tipoLetra, $margenIzq, $ancho, $y, $txtHeader, $alto, $infoQRmov, $arrayDatosFoot){
		$style = array(
			'width' => 0.4);
		$this->SetFont($tipoLetra, 'B', 7);
		$this->RoundedRect($margenIzq, $y, $ancho, 5, 1.20, '1001', 'DF', $style, array(
			200,
			200,
			200));
		$this->Text($margenIzq, $y + 0.3, $txtHeader);
		$y = $this->GetY() + 4;
		$style1 = array(
			'width' => 0.2,
			'cap' => 'butt',
			'join' => 'miter',
			'dash' => 0,
			'color' => array(
				0,
				0,
				0));
		
		$yCuadros = 15;
		// *******************************************************************************
		$this->RoundedRect($margenIzq, $y, ($ancho / 4) - $yCuadros, $alto, 1.20, '0110', 'DF', $style, array(
			255,
			255,
			255));
		$this->SetFont($tipoLetra, '', 7);
		$styleQr = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(
				0,
				0,
				0),
			'bgcolor' => false,
			'module_width' => 1,
			'module_height' => 1);
				$this->write2DBarcode($infoQRmov, 'QRCODE,Q', $margenIzq, $y, ($ancho / 4) - $yCuadros, $alto, $styleQr, 'N');
				$styleBarCode = array(
					'position' => '',
					'align' => 'C',
					'stretch' => false,
					'fitwidth' => true,
					'cellfitalign' => '',
					'border' => false,
					'hpadding' => 'auto',
					'vpadding' => 'auto',
					'fgcolor' => array(
						0,
						0,
						0),
					'bgcolor' => false,
					'text' => true,
					'font' => 'helvetica',
					'fontsize' => 8,
					'stretchtext' => 4);
						$this->write1DBarcode($arrayDatosFoot['numeroCertificado'], 'C128', $margenIzq, $y + $alto, ($ancho / 4) - $yCuadros, 10, 0.2, $styleBarCode, 'N');
						
						// *******************************************************************************
						$this->RoundedRect($margenIzq + (($ancho / 4) - $yCuadros), $y, $ancho / 4 + $yCuadros, $alto, 1.20, '0110', 'DF', $style, array(
							255,
							255,
							255));
						$x2 = $margenIzq + (($ancho / 4) - $yCuadros) + 5;
						$y2 = $y + 22;
						$this->Line($x2, $y2, $margenIzq + (($ancho / 4) * 2) - 5, $y2, $style1);
						$this->SetFont($tipoLetra, 'B', 7);
						$this->writeHTMLCell($ancho / 4 + $yCuadros, 10, $margenIzq + (($ancho / 4) - $yCuadros), $y2, 'Responsable de Emisión', '', 0, 0, true, 'C', true);
						$this->SetFont($tipoLetra, '', 7);
						$this->writeHTMLCell($ancho / 4 + $yCuadros, 10, $margenIzq + (($ancho / 4) - $yCuadros), $y2 + 3.5, $arrayDatosFoot['nombreEmision'], '', 0, 0, true, 'C', true);
						$this->writeHTMLCell($ancho / 4 + $yCuadros, 10, $margenIzq + (($ancho / 4) - $yCuadros), $y2 + 7, 'Identificación: ' . $arrayDatosFoot['identificadorEmision'], '', 0, 0, true, 'C', true);
						
						// *******************************************************************************
						$this->RoundedRect($margenIzq + (($ancho / 4) * 2), $y, $ancho / 4 + $yCuadros, $alto, 1.20, '0110', 'DF', $style, array(
							255,
							255,
							255));
						$x2 = $margenIzq + (($ancho / 4) * 2) + 5;
						$y2 = $y + 22;
						$this->Line($x2, $y2, $margenIzq + (($ancho / 4) * 3 + $yCuadros) - 5, $y2, $style1);
						$this->SetFont($tipoLetra, 'B', 7);
						$this->writeHTMLCell($ancho / 4 + $yCuadros, 10, $margenIzq + (($ancho / 4) * 2), $y2, 'Solicitante', '', 0, 0, true, 'C', true);
						$this->SetFont($tipoLetra, '', 7);
						$this->writeHTMLCell($ancho / 4 + $yCuadros, 10, $margenIzq + (($ancho / 4) * 2), $y2 + 3.5, $arrayDatosFoot['nombreSolicitante'], '', 0, 0, true, 'C', true);
						$this->writeHTMLCell($ancho / 4 + $yCuadros, 10, $margenIzq + (($ancho / 4) * 2), $y2 + 7, 'Identificación: ' . $arrayDatosFoot['identificadorSolicitante'], '', 0, 0, true, 'C', true);
						
						// *******************************************************************************
						$this->RoundedRect($margenIzq + (($ancho / 4) * 3 + $yCuadros), $y, ($ancho / 4) - $yCuadros, $alto, 1.20, '0110', 'DF', $style, array(
							255,
							255,
							255));
						$img_file = URL_IMG . "/logoSeguridadCSM.png";
						$this->Image($img_file, $margenIzq + (($ancho / 4) * 3 + $yCuadros) + 0.5, $y + 0.5, ($ancho / 4) - ($yCuadros + 1), $alto - 1, 'PNG', '', '', false, 300, '', false, false, 0);
						$this->SetFont($tipoLetra, 'B', 6);
						
						$this->writeHTMLCell(($ancho / 4) - $yCuadros + 1, '', $margenIzq + (($ancho / 4) * 3 + $yCuadros), $y + $alto - 3.8, 'Sello Autorización Seguridad', '', 0, 0, true, 'C', true);
						
						//$img_file = URL_IMG . "/logoEcuadorAmaLaVida.gif";
						//$this->Image($img_file, $margenIzq + (($ancho / 4) * 3 + $yCuadros) + 23, $y + $alto + 1, 5, 5, 'gif', '', '', false, 300, '', false, false, 0);
						
						// *******************************************************************************
						$this->SetFont($tipoLetra, '', 6);
						$this->writeHTMLCell('', '', $margenIzq, $y + $alto + 1, 'Documento gratuito emitido por sisteme GUIA, cualquier infracción será penalizada de acuerdo a Resolución N° 241.', '', 0, 0, true, 'C', true);
						$this->writeHTMLCell('', '', $margenIzq, $y + $alto + 3, 'En caso de no utilizar este certificado se deberá acercar a Agrocalidad para anularlo.', '', 0, 0, true, 'C', true);
						// *******************************************************************************
	}
	
	public function cortarTexto($txt, $tamanio){
		$newTxt = substr($txt, 0, $tamanio);
		return $newTxt;
	}
	
	public function construirDetalleMovilizacion($tipoLetra, $x, $y, $detalle, $margen_derecho){
		$style1 = array(
			'width' => 0.1,
			'cap' => 'butt',
			'join' => 'miter',
			'dash' => 0,
			'color' => array(0,0,0));
		$tamañoColumn = 209 - $margen_derecho;
		$this->SetFont($tipoLetra, 'B', 7);
		$this->writeHTMLCell(50, 0, $x, $y, 'Origen', '', 0, 0, false, 'L', false);
		$y1 = 50;
		$this->writeHTMLCell(50, 0, $x + $y1, $y, 'Producto', '', 0, 0, false, 'L', false);
		$this->writeHTMLCell(50, 0, $x + $y1 + 70, $y, 'Cantidad', '', 0, 0, false, 'L', false);
		$this->writeHTMLCell(50, 0, $x + $y1 + 120, $y, 'Unidad', '', 0, 0, false, 'L', false);
		$this->Line($x, $y + 4, $tamañoColumn, $y + 4, $style1);
		$fila = 4;
		$filaLine = $y + 4;
		foreach ($detalle as $detalleItems){
			$this->Line($x, $filaLine, $tamañoColumn, $filaLine, $style1);
			$this->SetFont($tipoLetra, '', 7);
			$this->writeHTMLCell(50, 0, $x, $y + $fila, $detalleItems['origen'], '', 0, 0, false, 'L', false);
			$y1 = 50;
			$this->writeHTMLCell(50, 0, $x + $y1, $y + $fila, $detalleItems['producto'], '', 0, 0, false, 'L', false);
			$this->writeHTMLCell(50, 0, $x + $y1 + 70, $y + $fila, $detalleItems['cantidad'], '', 0, 0, false, 'L', false);
			$this->writeHTMLCell(50, 0, $x + $y1 + 120, $y + $fila, $detalleItems['unidad'], '', 0, 0, false, 'L', false);
			$fila = $fila + 4;
			$filaLine = $filaLine + 4;
		}
	}
}
