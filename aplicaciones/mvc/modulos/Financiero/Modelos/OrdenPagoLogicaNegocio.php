<?php

/**
 * Lógica del negocio de  OrdenPagoModelo
 *
 * Este archivo se complementa con el archivo   OrdenPagoControlador.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       OrdenPagoLogicaNegocio
 * @package financiero
 * @subpackage Modelos
 */

namespace Agrodb\Financiero\Modelos;

use Agrodb\Core\Constantes;
use Agrodb\Financiero\Modelos\IModelo;
use Agrodb\Core\JasperReport;

class OrdenPagoLogicaNegocio implements IModelo
{

	private $modeloOrdenPago = null;


	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloOrdenPago = new OrdenPagoModelo();
	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{
		$tablaModelo = new OrdenPagoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPago() != null && $tablaModelo->getIdPago() > 0) {
			return $this->modeloOrdenPago->actualizar($datosBd, $tablaModelo->getIdPago());
		} else {
			unset($datosBd["id_pago"]);
			return $this->modeloOrdenPago->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloOrdenPago->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return OrdenPagoModelo
	 */
	public function buscar($id)
	{
		return $this->modeloOrdenPago->buscar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return OrdenPagoModelo
	 */
	public function buscarFacturaPorIdentificador($id)
	{
		$resultado = $this->modeloOrdenPago->buscar($id);
		$rutaXml = explode(Constantes::RUTA_APLICACION . '/', $resultado->getRutaXml());
		$resultado->setRutaRecortadaXML($rutaXml[1]);
		$resultado->setFechaFacturacion(date('d/m/Y G:i', strtotime($resultado->getFechaFacturacion())));
		return $resultado;
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloOrdenPago->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloOrdenPago->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarOrdenPago()
	{
		$consulta = "SELECT * FROM " . $this->modeloOrdenPago->getEsquema() . ". orden_pago";
		return $this->modeloOrdenPago->ejecutarSqlNativo($consulta);
	}

	public function buscarFacturasUsuario($arrayParametros)
	{

		$arrayParametros['id_vue'] = $arrayParametros['id_vue'] != '' ? "'" . $arrayParametros['id_vue'] . "'" : "NULL";
		$arrayParametros['numero_factura'] = $arrayParametros['numero_factura'] != '' ? "'" . $arrayParametros['numero_factura'] . "'" : "NULL";
		$arrayParametros['numero_orden_vue'] = $arrayParametros['numero_orden_vue'] != '' ? "'" . $arrayParametros['numero_orden_vue'] . "'" : "NULL";
		$arrayParametros['numero_solicitud'] = $arrayParametros['numero_solicitud'] != '' ? "'" . $arrayParametros['numero_solicitud'] . "'" : "NULL";
		$arrayParametros['fecha_facturacion'][0] = $arrayParametros['fecha_facturacion'][0] != "" ? "'" . $arrayParametros['fecha_facturacion'][0] . " 00:00:00'"  : "NULL";
		$arrayParametros['fecha_facturacion'][1] = $arrayParametros['fecha_facturacion'][1] != "" ? "'" . $arrayParametros['fecha_facturacion'][1] . " 24:00:00'"  : "NULL";

		switch ($arrayParametros['tipo_solicitud']) {

			case "Importación":
				$tabla = 'g_financiero.orden_pago op INNER JOIN g_importaciones.importaciones i ON i.id_importacion = op.id_solicitud::int';
				$busqueda = 'and op.tipo_solicitud = ' . "'" . $arrayParametros['tipo_solicitud'] . "'" . '
							 and (' . $arrayParametros['id_vue'] . ' is NULL or i.id_vue = ' . $arrayParametros['id_vue'] . ')
							 and (' . $arrayParametros['numero_orden_vue'] . ' is NULL or op.numero_orden_vue = ' . $arrayParametros['numero_orden_vue'] . ')';
				break;
			case "Fitosanitario":
				$tabla = 'g_financiero.orden_pago op INNER JOIN g_fito_exportacion.fito_exportaciones e ON e.id_fito_exportacion = op.id_solicitud::int';
				$busqueda = 'and op.tipo_solicitud = ' . "'" . $arrayParametros['tipo_solicitud'] . "'" . '
							and (' . $arrayParametros['id_vue'] . ' is NULL or e.id_vue = ' . $arrayParametros['id_vue'] . ')
							and (' . $arrayParametros['numero_orden_vue'] . ' is NULL or op.numero_orden_vue = ' . $arrayParametros['numero_orden_vue'] . ')';
				break;

			case "Otros":
				$tabla = 'g_financiero.orden_pago op';
				$busqueda = 'and (' . $arrayParametros['numero_factura'] . ' is NULL or op.numero_factura = ' . $arrayParametros['numero_factura'] . ')
	                         and (' . $arrayParametros['numero_solicitud'] . ' is NULL or op.numero_solicitud = ' . $arrayParametros['numero_solicitud'] . ')';
				break;
		}

		$consulta = "SELECT
						op.id_pago, op.identificador_operador, op.numero_solicitud, op.numero_establecimiento, op.punto_emision, 
                        op.numero_factura, op.total_pagar, op.estado_sri, to_char(op.fecha_facturacion,'DD/MM/YYYY') fecha_facturacion
					FROM
						$tabla
					WHERE
						op.identificador_operador='" . $arrayParametros['identificador'] . "'
						and op.estado_sri IN  ('AUTORIZADO','NO AUTORIZADO')
						and (" . $arrayParametros['fecha_facturacion'][0] . " is NULL or op.fecha_facturacion >= " . $arrayParametros['fecha_facturacion'][0] . ")
						and (" . $arrayParametros['fecha_facturacion'][1] . " is NULL or op.fecha_facturacion <= " . $arrayParametros['fecha_facturacion'][1] . ") 
						$busqueda";
		//echo $consulta;
		return $this->modeloOrdenPago->ejecutarSqlNativo($consulta);
	}

	// total de ordenes de pago para recargaSaldo
	public function buscarSaldoDiario($arrayParametros)
	{
		$tabla = $this->modeloOrdenPago->getEsquema() . ". orden_pago" ;
		
		$consulta = "SELECT sum(total_pagar) as total_saldo 
					FROM 
						$tabla 
					WHERE
						identificador_operador = '" . $arrayParametros['identificador_operador'] . "' 
						and fecha_orden_pago > now() - interval '24 hour'
						and tipo_solicitud='" . $arrayParametros['tipo_solicitud'] . "'";
		
		return $this->modeloOrdenPago->ejecutarSqlNativo($consulta);
	}

	/**
     * Método que genera el número de documento de la orden de pago
     */
	public function generarNumeroDocumento(){

		$anioActual = date('Y');

		$codigo= '%AGR-'.$anioActual.'%';
			
		$consulta = "SELECT
						MAX(numero_solicitud) as numero
					FROM 
						g_financiero.orden_pago
					WHERE 
						numero_solicitud LIKE '$codigo';";
		
		$secuancial = $this->modeloOrdenPago->ejecutarSqlNativo($consulta);

		
        //$secuancial = $this->lNegocioOrdenPago->generarNumeroDocumento('%AGR-'.$anioActual.'%');
		$tmp= explode("-", $secuancial->current()->numero);
		$incremento = end($tmp)+1;
        $numeroSolicitud = 'AGR-'.$anioActual.'-'.str_pad($incremento, 9, "0", STR_PAD_LEFT);
        
        return $numeroSolicitud;
	}

	/**
     * Método que genera el documento de orden de pago
     */
	public function generarOrdenPago($idPago, $rutaFecha, $nombreArchivo){

		$jasper = new JasperReport();
	    $datosReporte = array();
	    
	    $ruta = FIN_ORD_PAG_URL_ALL . $rutaFecha . '/';
	    
	    if (! file_exists($ruta)){
	        mkdir($ruta, 0777, true);
	    }
	    
	    $datosReporte = array(
	        'rutaReporte' => '../../financiero/reportes/reporteOrden.jasper',
	        'rutaSalidaReporte' => '../../financiero/documentos/ordenPago/'. $rutaFecha . '/' .$nombreArchivo,
	        'tipoSalidaReporte' => array('pdf'),
	        'parametrosReporte' => array('idpago' => $idPago,
	        							'fondoCertificado'=> RUTA_IMG_GENE.'fondoCertificado.png',
								        'totalSubsidio'=> '0'),
	        'conexionBase' => 'SI'
	    );
	    
	    $jasper->generarArchivo($datosReporte);

	}

	/**
     * Método que busca los datos generales de las ordenes de pago de recarga de saldo
     */
	public function buscarOrdenPagoRecargaSaldo($orden){

		$consulta = "SELECT 
						op.id_pago, op.identificador_operador, op.numero_solicitud, op.fecha_orden_pago, 
						op.total_pagar, op.observacion, op.estado, op.localizacion, op.orden_pago, cl.razon_social, cl.direccion, fc.metodo_pago
					FROM 
						g_financiero.orden_pago op, g_financiero.clientes cl, g_financiero_automatico.financiero_cabecera fc
					WHERE
						id_pago='".$orden['orden']."' 
						and op.identificador_operador = cl.identificador 
						and fc.id_orden_pago = op.id_pago ;";

		return $this->modeloOrdenPago->ejecutarSqlNativo($consulta);
	}

	/**
     * Método que busca el detalle de una orden de pago
     */
	public function buscarDetallePago($orden){

		$consulta = "SELECT
							d.*,
							s.unidad_medida
						FROM
							g_financiero.detalle_pago d,
							g_financiero.servicios s
						WHERE
							d.id_servicio = s.id_servicio and
							d.id_pago ='".$orden['orden']."';";

		return $this->modeloOrdenPago->ejecutarSqlNativo($consulta);
	}
}
