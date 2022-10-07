<?php

/**
 * Controlador Saldos
 *
 * Este archivo controla la lógica del negocio del modelo:  SaldosModelo y  Vistas
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses     SaldosControlador
 * @package financiero
 * @subpackage Controladores
 */

namespace Agrodb\Financiero\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Financiero\Modelos\ClientesLogicaNegocio;
use Agrodb\Financiero\Modelos\ClientesModelo;
use Agrodb\Financiero\Modelos\DetallePagoLogicaNegocio;
use Agrodb\Financiero\Modelos\OficinaRecaudacionLogicaNegocio;
use Agrodb\Financiero\Modelos\OrdenPagoModelo;
use Agrodb\Financiero\Modelos\OrdenPagoLogicaNegocio;
use Agrodb\Financiero\Modelos\SaldosLogicaNegocio;
use Agrodb\Financiero\Modelos\SaldosModelo;
use Agrodb\Financiero\Modelos\ServiciosLogicaNegocio;
use Agrodb\FinancieroAutomatico\Modelos\FinancieroCabeceraLogicaNegocio;
use Agrodb\FinancieroAutomatico\Modelos\FinancieroDetalleLogicaNegocio;

class SaldosControlador extends BaseControlador
{

    private $lNegocioSaldos = null;

    private $modeloSaldos = null;

    private $lNegocioOrdenPago = null;

    private $modeloOrdenPago = null;

    private $accion = null;

    private $resultadoConsulta = null;

    private $lNegocioClientes = null;

    private $modeloClientes = null;

    private $lNegocioOficinaRecaudacion = null;

    private $lNegocioDetallePago = null;

    private $lNegocioServicio = null;

    private $lNegocioFinancieroCabecera = null;

    private $lNegocioFinacieroDetalle = null;

    private $urlPdf = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioSaldos = new SaldosLogicaNegocio();
        $this->modeloSaldos = new SaldosModelo();
        $this->lNegocioOrdenPago = new OrdenPagoLogicaNegocio();
        $this->modeloOrdenPago = new OrdenPagoModelo();
        $this->lNegocioClientes = new ClientesLogicaNegocio();
        $this->lNegocioOficinaRecaudacion = new OficinaRecaudacionLogicaNegocio();
        $this->lNegocioDetallePago = new DetallePagoLogicaNegocio();
        $this->lNegocioServicio = new ServiciosLogicaNegocio();
        $this->lNegocioFinancieroCabecera = new FinancieroCabeceraLogicaNegocio();
        $this->lNegocioFinacieroDetalle = new FinancieroDetalleLogicaNegocio();

        // $this->modeloClientes = new ClientesModelo();

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->cargarComboIdentificador();

        require APP . 'Financiero/vistas/listaSaldosVista.php';
    }

    /**
     *Método que lista los saldos del operador
     */

    public function listarSaldoUsuario($tipoUsuario)
    {

        if ($tipoUsuario == 'interno') {
            $identificador = $_POST['identificador'];
        } else {
            $identificador = $_SESSION['usuario'];
        }

        $arrayParametros = array(
            'identificador' => $identificador,
            'fecha_inicio' => $_POST['fechaInicio'],
            'fecha_fin' => $_POST['fechaFin']
        );

        $datosSaldo = $this->lNegocioSaldos->buscarSaldoUsuarioConsumoFacturas($arrayParametros);
        
        $this->tablaHtmlSaldos($datosSaldo, $_POST['fechaInicio'], $_POST['fechaFin']);

        if ($tipoUsuario == 'interno') {
            echo \Zend\Json\Json::encode($this->itemsFiltrados);
            exit();
        }
    }


    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Saldos";
        require APP . 'Financiero/vistas/formularioSaldosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Saldos
     */
    public function guardar()
    {
        $this->lNegocioSaldos->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Saldos
     */
    public function editar()
    {
        $this->accion = "Editar Saldos";
        $this->modeloSaldos = $this->lNegocioSaldos->buscar($_POST["id"]);
        require APP . 'Financiero/vistas/detalleSaldoVista.php';
    }

    /**
     * Obtenemos los datos del saldo del usuario - Tabla: Saldos
     */
    public function detalleSaldo()
    {
        $this->accion = "Detalle saldos";
        $datos = explode("@@", $_POST["id"]);

        $this->identificadorOperador = $datos[0];
        $this->fechaInicio = $datos[1];
        $this->fechaFin = $datos[2];

        $arrayParametros = array(
            'identificador' => $datos[0],
            'fecha_inicio' => $datos[1] . ' 00:00:00',
            'fecha_fin' => $datos[2] . ' 00:00:00',
            'inicio' => $this->offsetInicio
        );

        $datosSaldo = $this->lNegocioSaldos->buscarSaldoUsuarioConsumoFacturas($arrayParametros);
        
        $this->cargarDatosUsuario($datosSaldo);
        $datosFacturaConSaldo = $this->lNegocioSaldos->buscarFacturasConSaldoUsuario($arrayParametros);
        $this->cargarDatosFactutasUsuarioConSaldo($datosFacturaConSaldo);
        require APP . 'Financiero/vistas/detalleSaldoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Saldos
     */
    public function borrar()
    {
        $this->lNegocioSaldos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Saldos
     */
    public function tablaHtmlSaldos($tabla, $fechaInicio, $fechaFIn)
    { {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['identificador'] . '@@' . $fechaInicio . '@@' . $fechaFIn . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Financiero\saldos"
                    		  data-opcion="detalleSaldo" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++$contador . '</td>
                    		  <td style="white - space:nowrap; "><b>' . $fila['identificador'] . '</b></td>
                        <td>' . $fila['razon_social'] . '</td>
                        <td>' . ($fila['cantidad_ingreso']  +  $fila['cantidad_egreso']) . '</td>                     
                    </tr>'
                );
            }
        }
    }

    /**
     * Método que busca si existen mas registros que conforman el detale del saldo en un rango de fechas
     */
    public function cargarDetalleSaldos()
    {

        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $arrayParametros = array(
            'identificador' => $_POST['identificador'],
            'fecha_inicio' => $_POST['fechaInicio'] . ' 00:00:00',
            'fecha_fin' => $_POST['fechaFin'] . ' 00:00:00',
            'inicio' => $_POST['offset']
        );

        $datosDetalle = $this->lNegocioSaldos->buscarFacturasConSaldoUsuario($arrayParametros);

        $contador = 0;
        if ($_POST['contador'] != '') {
            $contador = $_POST['contador'];
        }

        foreach ($datosDetalle as $fila) {
            $contenido = $contenido . '<tr>
                                            <td>' . ++$contador . '</td>
                                            <td>' . date('d/m/Y G:i', strtotime($fila['fecha_facturacion'])) . '</td>
                                            <td><a href=' . $fila['factura'] . ' target= "_blank">' . $fila['numero_establecimiento'] . '-' . $fila['punto_emision'] . '-' . $fila['numero_factura'] . '</a></td>
                                            <td>' . $fila['tipo'] . '</td>
                                            <td>' . ($fila['valor_ingreso'] == '' ? '0' : $fila['valor_ingreso']) . '</td>
                                            <td>' . ($fila['valor_egreso'] == '' ? '0' : $fila['valor_egreso']) . '</td>
                                            <td>' . $fila['saldo_disponible'] . '</td>
                                        </tr>';
        }

        echo json_encode(array(
            "estado" => $estado,
            "mensaje" => $mensaje,
            "contenido" => $contenido
        ));
    }

    /**
     * Método que carga filtro en listar Saldos
     */
    public function cargarComboIdentificador()
    {

        $identificador = '';
        $condicionCombo = '';

        if (!$this->usuarioInterno) {
            $identificador = $_SESSION['usuario'];
            $condicionCombo = 'readonly = "readonly"';
        }

        $this->comboIdentificador = '<table class="filtro" style="width: 400px;">
                        				<tbody>
                                            <tr>
                                                <th colspan="2">Buscar:</th>
                                            </tr>
                        					<tr >
                        						<th>Identificador:</th>
                        						<td>
                                                    <input id="identificadorFiltro" type="text" name="identificadorFiltro" style="width: 100%" required="true" class="camposRequeridos"  value="' . $identificador . '" ' . $condicionCombo . '>                                               
                        						</td>
                                            </tr>
                                            </tr>
                                            <tr >
                                                <th>Fecha inicio:</th>
                                                <td>
                                                    <input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" required="true" class="camposRequeridos" onChange="calcularFechas(this),10">
                                                </td>
                                            </tr>
                                            <tr >
                                                <th>Fecha fin:</th>
                                                <td>
                                                    <input id="fechaFin" type="text" name="fechaFin" style="width: 100%" required="true" class="camposRequeridos">
                                                </td>
                                            </tr>
                        					<tr>
                        						<td id="mensajeError"></td>
                        						<td>
                        							<button id="btnFiltrar">Buscar</button>
                        						</td>
                        					</tr>
                        				</tbody>
                        			</table>';
    }

    /**
     * Método que construye el código HTML para desplegar los datos generales del usuario para el detalle de saldos
     */
    public function cargarDatosUsuario($datosSaldo)
    {

        foreach ($datosSaldo as $fila) {

            $this->datosUsuario = '<fieldset>
                                    <legend>Datos operador</legend>
                                    <div data-linea = "1">
                                    	<label>Identificador: </label>' . $fila['identificador'] . '
                                    </div>
                                    
                                    <div data-linea = "2">
                                    	<label>Razón social: </label> ' . $fila['razon_social'] . '
                                    </div>
                                    
                                    <div data-linea = "3">
                                    	<label>Dirección: </label> ' . $fila['direccion'] . '
                                    </div>
                                    
                                    <div data-linea = "4">
                                    	<label>Monto consumo: </label>' . ($fila['valor_consumo'] == '' ? '0' : $fila['valor_consumo']) . '
                                    </div>
                                    
                                    <div data-linea = "4">
                                    	<label>Saldo disponible: </label> ' . $fila['saldo_disponible'] . '
                                    </div>
                            	</fieldset>';
        }
    }

    /**
     * Método que construye el código HTML para desplegar el historial del detalle de saldo en un rango de fechas
     */
    public function cargarDatosFactutasUsuarioConSaldo($datosFacturaConSaldo)
    {

        $contador = 0;
        $this->datosFacturaConSaldo = '<fieldset>
                                    <legend>Detalle de saldos</legend>
                                    <div id="paginacionSlados"></div>
                                    <table id="tablaDetalleSaldos" style="width:100%;text-align: center;">
                        			<thead>
                        				<tr>
                                            <th>#</th>
                        					<th>Fecha transacción</th>
                                            <th>N° Documento</th>
                        					<th>Tipo</th>
                        					<th>Valor Ingreso</th>
                                            <th>Valor Egreso</th>
                        					<th>Saldo Disponible</th>	
                        				</tr>
                                      </thead>
                                      <tbody id="detalleSaldos">';

        foreach ($datosFacturaConSaldo as $fila) {

            $this->datosFacturaConSaldo .= '<tr>
                                                <td>' . ++$contador . '</td>
                                                <td>' . date('d/m/Y G:i', strtotime($fila['fecha_facturacion'])) . '</td>
                                                <td><a href=' . $fila['factura'] . ' target= "_blank">' . $fila['numero_establecimiento'] . '-' . $fila['punto_emision'] . '-' . $fila['numero_factura'] . '</a></td>
                                                <td>' . $fila['tipo'] . '</td>
                                                <td>' . ($fila['valor_ingreso'] == '' ? '0' : $fila['valor_ingreso']) . '</td>
			                                    <td>' . ($fila['valor_egreso'] == '' ? '0' : $fila['valor_egreso']) . '</td>
                                                <td>' . $fila['saldo_disponible'] . '</td>
                                            </tr>';
        }

        $this->datosFacturaConSaldo .= '</tbody></table></fieldset>';
    }

    /**
     * Método que carga las acciones de la opción Recarga Saldo Disponible
     */
    public function listarRecargarSaldo()
    {

        $this->filtroRecargaSaldo();
        require APP . 'Financiero/vistas/listaRecargaSaldoVista.php';
    }

    /**
     * Método que carga filtro en listar Recarga Saldo Disponible
     */
    private function filtroRecargaSaldo()
    {

        $identificador = '';
        $condicionCombo = '';
        $condicionSaldo = '';

        if (!$this->usuarioInterno) {
            $identificador = $_SESSION['usuario'];
            $condicionCombo = 'readonly = "readonly"';

            $datosSaldoTotal = $this->lNegocioSaldos->buscarSaldoUsuarioTotal(array('identificador' => $_SESSION['usuario']));
            $saldoTotal = $datosSaldoTotal->current();

            if (isset($saldoTotal->saldo_disponible)) {
                $condicionSaldo = '<td><h3>Saldo Disponible: </h2></td><td style="text-align:center";><h3>' . $saldoTotal->saldo_disponible . '<h2></td>';
            } else {
                $condicionSaldo = '<td><h3>Saldo Disponible: </h2></td><td style="text-align:center";><h3>0.00<h2></td>';
            }
        }

        $this->comboRecargaSaldo = '<table class="filtro" style="width: 400px;">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">Buscar:</th>
                                        </tr>
                                        <tr >
                                            <th>Identificador:</th>
                                            <td>
                                                <input id="identificadorFiltro" type="text" name="identificadorFiltro" style="width: 100%" required="true" class="camposRequeridos"  value="' . $identificador . '" ' . $condicionCombo . '>
                                            </td>
                                        </tr>
                                        <tr >
                                            <th>Fecha inicio:</th>
                                            <td>
                                                <input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" required="true" class="camposRequeridos" onChange="calcularFechas(this),10">
                                            </td>
                                        </tr>
                                        <tr >
                                            <th>Fecha fin:</th>
                                            <td>
                                                <input id="fechaFin" type="text" name="fechaFin" style="width: 100%" required="true" class="camposRequeridos">
                                            </td>
                                        </tr>
                                        <tr>
                                            ' . $condicionSaldo . '
                                            <td colspan ="2">
                                                <button id="btnFiltrar">Buscar</button>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td id="mensajeError"></td>
                                        </tr>
                                    </tbody>
                                </table>';
    }

    /**
     * Método que carga la pantalla de crear una nueva recarga de saldo.
     */
    public function nuevoRecargaSaldos()
    {

        if (!$this->usuarioInterno) {
            $this->resultadoConsulta['identificador'] = $_SESSION['usuario'];
            $this->resultadoConsulta['readonly'] = 'readonly="readonly"';
        }

        $this->accion = "Orden de Pago Para Incremento de Saldo Disponible";
        require APP . 'Financiero/vistas/formularioRecargaSaldoVista.php';
    }

    /**
     * Método para buscar ordenes de pago de recarga de saldo
     */
    public function buscarOrdenPagoSaldoDisponible()
    {

        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $arrayParametros = array(
            'identificador_operador' => $_POST['identificadorOperador'],
            'fecha_inicio' => $_POST['fechaInicio'],
            'fecha_fin' => $_POST['fechaFin'],
            'tipo_solicitud' => 'recargaSaldo'
        );

        $ordenesRecargaSaldo = $this->lNegocioOrdenPago->buscarLista("identificador_operador = '" . $arrayParametros['identificador_operador'] . "' 
                                                            and fecha_orden_pago >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00'" . " 
                                                            and fecha_orden_pago <= DATE('" . $arrayParametros['fecha_fin'] . " 00:00:00') " . " + INTERVAL  '1 day'
                                                            and tipo_solicitud='" . $arrayParametros['tipo_solicitud'] . "'");

        $this->tablaHtmlOrdenRecargaSaldo($ordenesRecargaSaldo);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }

    /**
     * Construye el códatosFacturaConSaldodigo HTML para desplegar la lista de - ordenes de recarga de saldo
     */
    public function tablaHtmlOrdenRecargaSaldo($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_pago'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Financiero\Saldos"
                    		  data-opcion="detalleRecargaSaldo" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['identificador_operador'] . '</b></td>
                        <td> <a href="' . $fila['orden_pago'] . '" target="_blank" download>' . $fila['numero_solicitud'] . '</a> </td>                        
                        <td>' . $fila['total_pagar'] . '</td>
                        <td>' . $fila['fecha_orden_pago'] . '</td>
                    </tr>'
            );
        }
    }

    /**
     * Método que devuelve los datos personales del usuario para generar una nueva orden de pago
     */
    public function obtenerDatosClientes()
    {

        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $arrayParametros = array('identificador' => $_POST['identificador']);

        $datosCliente = $this->lNegocioClientes->buscar($arrayParametros['identificador']);

        if ($datosCliente->getIdentificador() == null) {
            $estado = 'FALLO';
            $mensaje = 'No se encuentra registrado el usuario con identificador ' . $_POST['identificador'];
        } else {
            if ($datosCliente->getCorreo() == '') {
                $estado = 'FALLO';
                $mensaje = 'No tiene registrado un correo de facturación';
            }
        }

        $contenido = array(
            'razon_social' => $datosCliente->getRazonSocial(),
            'direccion' => $datosCliente->getDireccion(),
            'telefono' => $datosCliente->getTelefono(),
            'email' => $datosCliente->getCorreo()
        );

        echo json_encode(array(
            "estado" => $estado,
            "mensaje" => $mensaje,
            "contenido" => $contenido
        ));
    }

    /**
     * Método que carga filtro en listar Recarga Saldo Disponible
     */
    public function buscarSaldoDiario()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        

        $arrayParametros = array(
            'identificador_operador' => $_POST['identificador'],
            'tipo_solicitud' => 'recargaSaldo'
        );

        $datosOrdenes = $this->lNegocioOrdenPago->buscarSaldoDiario($arrayParametros);

        $totalSaldo = $this->verificarSaldoCupo($_POST['nuevoSaldo'], $datosOrdenes->current()->total_saldo);

        if (!$totalSaldo) {
            $estado = 'FALLO';
            if($datosOrdenes->current()->total_saldo != ''){
            $mensaje = "El día de hoy ha ingresado " . $datosOrdenes->current()->total_saldo . " USD, la canitdad a ingresar eccede el cupo permitido de 5000 USD diarios.";
            } else{
                $mensaje = "La canitdad a ingresar eccede el cupo permitido de 5000 USD diarios.";
            }
        }

        $contenido = $datosOrdenes->current()->total_saldo;

        echo json_encode(array(
            "estado" => $estado,
            "mensaje" => $mensaje,
            "contenido" => $contenido
        ));
    }

    /**
     * Método que verifica que el saldo a ingresar sea meno o igual a 5000 dolares
     */
    private function verificarSaldoCupo($nuevoSaldo, $saldoingresado)
    {

        $totalSaldo = $nuevoSaldo + $saldoingresado;

        if ($totalSaldo <= 5000) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método que guarda la nueva orden de pago
     */
    public function guardarRecargaSaldo()
    {

        $estado = 'exito';
        $mensaje = 'Orden de pago generada con éxito';
        $contenido = '';

        $numeroDocumento = $this->lNegocioOrdenPago->generarNumeroDocumento();
        $datosRecaudador = $this->lNegocioOficinaRecaudacion->buscarLista(array("identificador_firmante" => Constantes::IDENTIFICADOR_RECAUDADOR));

        $arrayCabeceraOrdenPago = array(
            'identificador_operador' => $_POST['identificadorOP'],
            'numero_solicitud' => $numeroDocumento,
            'fecha_orden_pago' => 'now()',
            'total_pagar' => $_POST['cantidad'],
            'observacion' => 'Orden de pago generada para recarga de saldo',
            'estado' => '3',
            'localizacion' => $datosRecaudador->current()->oficina,
            'nombre_provincia' => $datosRecaudador->current()->provincia,
            'id_provincia' => $datosRecaudador->current()->id_provincia,
            'identificador_usuario' => $datosRecaudador->current()->identificador_firmante,
            'id_solicitud' => '0',
            'tipo_solicitud' => 'recargaSaldo',
            'id_grupo_solicitud' => '0',
            'porcentaje_iva' => $datosRecaudador->current()->iva,
        );

        $idPago = $this->lNegocioOrdenPago->guardar($arrayCabeceraOrdenPago);

        $datosDetalleRecargaSaldo = $this->lNegocioServicio->buscarLista(array("codigo" => Constantes::ITEM_TARIFARIO_RECARGA_SALDO));

        $arrayDetalleOrdenPago = array(
            'id_pago' => $idPago,
            'id_servicio' => $datosDetalleRecargaSaldo->current()->id_servicio,
            'concepto_orden' => $datosDetalleRecargaSaldo->current()->concepto,
            'cantidad' => $_POST['cantidad'],
            'precio_unitario' => $datosDetalleRecargaSaldo->current()->valor,
            'descuento' => '0',
            'iva' => '0',
            'total' => $_POST['cantidad'],
            'subsidio' => '0'
        );

        $this->lNegocioDetallePago->guardar($arrayDetalleOrdenPago);


        /*$arrayCabeceraFinancieroAutomatico = array(
            'total_pagar' => $_POST['cantidad'],
            'tipo_solicitud' => 'recargaSaldo',
            'estado' => 'Atendida',
            'id_orden_pago' => $idPago,
            'observacion' => 'Orden de pago generada para recarga de saldo',
            'tipo_proceso' => 'comprobante',
            'fecha_ingreso_cabcera' => 'now()',
            'provincia_firmante' => $datosRecaudador->current()->identificador_firmante,
            'id_provincia_firmante' => $datosRecaudador->current()->id_provincia,
            'identificador_operador' => $_POST['identificadorOP'],
            'metodo_pago' => 'Pago electrónico'
        );

        $idCabecera = $this->lNegocioFinancieroCabecera->guardar($arrayCabeceraFinancieroAutomatico);


        $arrayDetalleCabeceraFinancieroAutomatico = array(
            'id_financiero_cabecera' => $idCabecera,
            'id_servicio' => $datosDetalleRecargaSaldo->current()->id_servicio,
            'concepto_orden' => $datosDetalleRecargaSaldo->current()->concepto,
            'cantidad' => $_POST['cantidad'],
            'precio_unitario' => $datosDetalleRecargaSaldo->current()->valor,
            'descuento' => '0',
            'iva' => '0',
            'total' => $_POST['cantidad']
        );

        $this->lNegocioFinacieroDetalle->guardar($arrayDetalleCabeceraFinancieroAutomatico);*/

        $rutaOrdenPago = $this->generarCertificadoRecargaSaldo($idPago, $_POST['identificadorOP']);

        $arrayActualizacionOrden = array(
            'id_pago' => $idPago,
            'orden_pago' => $rutaOrdenPago
        );

        $this->lNegocioOrdenPago->guardar($arrayActualizacionOrden);

        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $rutaOrdenPago));

        $cuerpo = 'Estimado Cliente:<br/><br/> <b>AGROCALIDAD</b> le informa que su <b>Orden de pago</b> fue generada con fecha ' . date("F j, Y") . ', la cual se adjunta en el presente correo.';
        
        $arrayDatos = array(
                'id_tabla'=>$idPago,
                'correo' => $_POST['correo'],
                'cuerpo' => $cuerpo,
                'ruta_archivo' => $rutaOrdenPago
        );
        
        $notifiacarMail = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
        $notifiacarMail->notificarClienteordenPago($arrayDatos);
    }

    /**
     * Función para generar el certificado 
     */
    public function generarCertificadoRecargaSaldo($idPago, $identificadorOperador)
    {

        $rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

        $fecha = time();
        $fecha_partir1 = date("h", $fecha);
        $fecha_partir2 = date("i", $fecha);
        $fecha_partir4 = date("s", $fecha);
        $fecha_partir3 = $fecha_partir1 - 1;
        $reporte = "ReporteOrden";
        $nombreArchivo = $reporte . "_" . $identificadorOperador . "_" . date("Y-m-d") . "_" . $fecha_partir3 . '_' . $fecha_partir2 . '_' . $fecha_partir4;

        $this->lNegocioOrdenPago->generarOrdenPago($idPago, $rutaFecha, $nombreArchivo);

        $rutaOrdenPago = FIN_ORD_PAG_URL . $rutaFecha . "/" . $nombreArchivo . ".pdf";

        return $rutaOrdenPago;
    }

    /**
     * Método para desplegar el certificado PDF
     */
    public function mostrarReporte()
    {
        $this->urlPdf = $_POST['id'];
        require APP . 'Financiero/vistas/visorPDF.php';
    }

    /**
     * Obtenemos los datos de la recarga de saldo del usuario
     */
    public function detalleRecargaSaldo()
    {
        $this->accion = "Detalle saldos";
        $arrayParametros = array('orden' => $_POST["id"]);
        $datosCabecera = $this->lNegocioOrdenPago->buscarOrdenPagoRecargaSaldo($arrayParametros);

        $this->cargarDatosRecargaSaldo($datosCabecera);

        $datosDetalleOrden = $this->lNegocioOrdenPago->buscarDetallePago($arrayParametros);
        $this->cargarDatosRecargaSaldoDetalle($datosDetalleOrden);
        require APP . 'Financiero/vistas/detalleRecargaSaldoVista.php';
    }

    /**
     * Método que construye el código HTML para desplegar los datos generales de la orden de pago
     */
    public function cargarDatosRecargaSaldo($datos)
    {

        $this->cabeceraRecargaSaldo = '<fieldset>
                                <legend>Orden de Pago Nro. ' . $datos->current()->numero_solicitud . '</legend>
                                <div data-linea = "1">
                                    <label>Localización: </label>' . $datos->current()->localizacion . '
                                </div>
                                
                                <div data-linea = "2">
                                    <label>Razón social: </label> ' . $datos->current()->razon_social . '
                                </div>

                                <div data-linea = "2">
                                    <label>Identificación: </label> ' . $datos->current()->identificador_operador . '
                                </div>
                                
                                <div data-linea = "3">
                                    <label>Dirección: </label> ' . $datos->current()->direccion . '
                                </div>
                                
                                <div data-linea = "3">
                                    <label>Forma de Pago: </label>' . $datos->current()->metodo_pago . '
                                </div>
                                
                                <div data-linea = "4">
                                    <label>Fecha Orden: </label> ' . $datos->current()->fecha_orden_pago . '
                                </div>

                                <div data-linea = "5">
                                    <label>Observación: </label> ' . $datos->current()->observacion . '
                                </div>

                                <div data-linea = "6">
                                    <label>Total a pagar: </label> ' . $datos->current()->total_pagar . '
                                </div>

                                <div data-linea = "7">
                                    <label>Orden de pago: </label> <a href=' . $datos->current()->orden_pago . ' target="_blank" download>Descargar orden de pago</a>
                                </div>

                            </fieldset>';
    }

    /**
     * Método que prerara el detalle de la orden de paggo para ser mostrados
     */
    public function cargarDatosRecargaSaldoDetalle($datos)
    {

        $this->detalleRecargaSaldo = '<fieldset>
                                <legend>Detalle</legend>
                                <table>
                                    <thead>
                                        <th>Concepto</th>
                                        <th>Cantidad</th>
                                        <th>V Unit.</th>
                                        <th>Desc.</th>
                                        <th>Subsidio</th>
                                        <th>Iva</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>' . $datos->current()->concepto_orden . '<br/> <b>UNIDAD MEDIDA:</b> ' . $datos->current()->unidad_medida . '</td>	
                                            <td>' . $datos->current()->cantidad . '</td>	
                                            <td>' . $datos->current()->precio_unitario * '1' . '</td>
                                            <td>' . $datos->current()->descuento . '</td>
                                            <td>' . $datos->current()->subsidio * '1' . '</td>
                                            <td>' . $datos->current()->iva . '</td>
                                            <td>' . $datos->current()->total . '</td>
                                        </tr>                                   
                                    </tbody>
                                </table>
                            </fieldset>';
    }
}
