<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();
$cf = new ControladorFinanciero();

$comprobante = $_POST['comprobante'];
$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];
$provincia = $_POST['provincia'];
$establecimiento = $_POST['establecimiento'];
$opcionReporte = $_POST['opcionReporte'];
$cliente = $_POST['cliente'];
$ruc = $_POST['ruc'];


///////INICIO MGNM////
$area =  $_POST['area'];
$codigoItem = ($_POST['transaccion'] == "") ? 'todos' : $_POST['transaccion'];
//////FIN MGNM////

$contador = 0;
$var = 0;

switch ($opcionReporte){
	case '1':
		
		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0);
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';

		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporte.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
					
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

		</form>';

	break;

	case '2':

		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0); //provincia
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteXItem.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
			
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

		</form>';

	break;

	case '3':

		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0); //provincia
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteXBanco.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
				
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

			</form>';

	break;

	case '4':

		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0); //provincia
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteXFactura.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>	
					
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

			</form>';

	break;


	case '5':

		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0); //provincia
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteXPartidaPresupuestaria.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>	
					
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

				</form>';

	break;

	case '6':

		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0); //provincia
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteXPuntoRecaudacion.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>

				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

				</form>';

	break;

	case '8':
		
		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $ruc, $valor=1);  //punto de venta
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporte.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>

				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

				</form>';

	break;

	case '11':		
				
		$res = $cc -> listarRecaudacionExcedentes($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $valor=0, $cliente, $ruc);  //provincia
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			if( $var != $fila['identificador_operador']){
				echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.date('d/m/Y',strtotime($fila['fecha_facturacion'])).'</td>
					<td> '.number_format(($fila['saldo_disponible']),2).'</td>
					<td> '.$fila['numero_factura'].'</td>
							</tr>';
				$var = $fila['identificador_operador'];
			}
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteExcedentesFactura.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="cliente" value="'.$cliente.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>

				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

				</form>';

	break;

	case '12':
		
		$res = $cc -> listarRecaudacionExcedentes($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $valor=1, $cliente, $ruc);  //punto de venta
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			if( $var != $fila['identificador_operador']){
				echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.date('d/m/Y',strtotime($fila['fecha_facturacion'])).'</td>
					<td> '.number_format(($fila['saldo_disponible']),2).'</td>
					<td> '.$fila['numero_factura'].'</td>
							</tr>';
				$var = $fila['identificador_operador'];
			}
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteExcedentesFactura.php" target="_blank" method="post">

				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="cliente" value="'.$cliente.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
					
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

			</form>';

	break;
			
	case '13':
		
		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0);  //provincia
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteIngresoCaja.php" target="_blank" method="post">
							
				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
						
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
							
				</form>';
			
	break;
			
	case '14':
		
		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $ruc, $valor=1);  //punto de venta
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteIngresoCaja.php" target="_blank" method="post">
							
				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
						
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
								
			</form>';
			
	break;
	
	case '15':
		
		$res = $cc -> filtrarRecaudacionXEstablecimientoXItem($conexion, $establecimiento, $area, $codigoItem, $fechaInicio, $fechaFin, $provincia, $ruc, $comprobante);
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Provincia</th>
						<th>Num. Establecimiento</th>
						<th>Código</th>
						<th>Servicio</th>
						<th># Items Facturados</th>
						<th>Precio Unitario</th>
						<th>Total</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$provincia.'"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td>'.$provincia.'</td>
					<td>'.$fila['numero_establecimiento'].'</td>
					<td>'.$fila['codigo'].'</td>
					<td>'.$fila['concepto_orden'].'</td>
					<td>'.$fila['cantidad'].'</td>
					<td>'.number_format($fila['precio_unitario'],2,',','.').'</td>
					<td>'.number_format($fila['total'],2,',','.').'</td>
				</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteXEstablecimientoXItem.php" target="_blank" method="post">
				
				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="area" value="'.$area.'"/>
				<input type="hidden" name="item" value="'.$codigoItem.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>

				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

			</form>';
			
	break;
		
	case 17:
		
		$res = $cf->obtenerComprovantesVuePorFechas($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, 'individual', $ruc);
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Saldo disponible</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
			
			$qSaldo = 	$cf->obtenerMaxSaldoPorIdentificadorFechas($conexion, $fila['identificador_operador'], 'saldoVue', $fechaInicio, $fechaFin);
			
			if(pg_num_rows($qSaldo)== 0){
				$fecha = 'No disponible.';
				$saldo = 'No disponible.';
			}else{
				$saldo = pg_fetch_assoc($qSaldo);
				$fecha = date('d/m/Y G:i',strtotime($saldo['fecha_deposito']));
				$saldo = number_format($saldo['saldo_disponible'],2,',','.');
			}
		
			echo '<tr
					id="'.$fila['identificador_operador'].'">
					<td>'.++$contador.'</td>
					<td>'.$fila['identificador_operador'].'</td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.$fecha.'</td>
					<td>'.$saldo.'</td>
				</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteComprobanteVueXpunto.php" target="_blank" method="post">
		
				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
		
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
		
			</form>';
		
	break;
	
	case '18':
		
		$res = $cc -> filtraPorPuntoRecaudacion($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $ruc, $valor=1);
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
			
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirIngresoDeCaja"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_facturacion'])): ($comprobante) == 'ingresoCaja'? date('d/m/Y',strtotime($fila['fecha_facturacion'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']): ($comprobante)== 'ingresoCaja'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
					</tr>';
		}
		
		echo '</table>';
		
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteCuadreCaja.php" target="_blank" method="post">
		
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
					
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
					
		</form>';
		
		break;
}

?>

<script type="text/javascript"> 

$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		
	});

</script>
