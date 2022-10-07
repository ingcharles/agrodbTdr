<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$crs = new ControladorRevisionSolicitudesVUE();

$qSolicitud = $cr->abrirOperacionRevision($conexion, $_POST['id']);

$qTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $qSolicitud[0]['idProducto']);

$tipo = pg_fetch_result($qTipoSubtipo, 0, 'nombre_tipo');

$subtipo = pg_fetch_result($qTipoSubtipo, 0, 'nombre_subtipo');

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

//Obtener monto a pagar
$qMonto = $crs->obtenerMontoSolicitud($conexion, $_POST['id'], 'Operadores');

//Obtener datos de entidades bancarias
$qEntidadesBancarias = $cc->listarEntidadesBancarias($conexion);
?>

<header>
	<h1>Solicitud Operador</h1>
</header>
<div id="estado"></div>

<div class="pestania">

	<fieldset>
		<legend>Registro de Operador</legend>
		<div data-linea="1">
			<label>Tipo de operación: </label> <?php echo $qSolicitud[0]['tipoOperacion']; ?> <br />
		</div>
		<?php 
   	 		if($qSolicitud[0]['nombrePais'] != ''){
    			 echo '<div data-linea="1">
      			<label>País: </label>' .  $qSolicitud[0]['nombrePais'] .'</div>';
			}
		?>
		<div data-linea="5">
			<label>Tipo producto: </label> <?php echo $tipo; ?> 
		</div>
		
		<div data-linea="6">
			<label>Subtipo producto: </label> <?php echo $subtipo; ?> 
		</div>
		
		<div data-linea="7">
			<label>Producto: </label> <?php echo $qSolicitud[0]['producto']; ?> 
		</div>

		<div data-linea="3">
			<label>Razón social: </label> <?php echo $qSolicitud[0]['ruc']; ?> <br />
		</div>

		<div data-linea="4">
			<label>Representante legal: </label> <?php echo $qSolicitud[0]['nombreRepresentante'] . ' ' . $qSolicitud[0]['apellidoRepresentante']; ?> <br />
		</div>

		<div data-linea="8">
			<label>Estado de solicitud: </label> <?php echo $qSolicitud[0]['estado']; ?> <br />
		</div>
		<!-- ?php 
		$inspectores='';
				
			if($qSolicitud[0]['estado'] == 'asignado' || $qSolicitud[0]['estado'] == 'proceso' || $qSolicitud[0]['estado'] == 'finalizado'){
		    $res = $cr->listarInspectoresAsignados($conexion, $_POST['id']);
		
		     echo '
				<div data-linea="6">
				<label>Inspectores asignados: </label>';
		
		     while($fila = pg_fetch_assoc($res)){
		     	echo $fila['apellido'].", ".$fila['nombre']."; ";
		     }
		
		     echo '</div>';
		    }
    	?-->
	</fieldset>

	<?php 
	$numeroAreaProduccion=1;
	foreach ($qSolicitud as $solicitud){
		echo '
		<fieldset>
		<legend>Área de Producción ' . $numeroAreaProduccion . '</legend>
		<div data-linea="3">
		<label>Nombre del sitio: </label> ' . $solicitud['nombreSitio'] . ' <br/>
		</div>
		<div data-linea="4">
		<label>Nombre del área: </label> ' . $solicitud['nombreArea'] . ' <br/>
		</div>
		<div data-linea="4">
		<label>Tipo de área: </label> ' . $solicitud['tipoArea'] . ' <br/>
			</div>
			<div data-linea="5">
			<label>Provincia: </label> ' . $solicitud['provincia'] . ' <br/>
		</div>
		<div data-linea="5">
		<label>Cantón: </label> ' . $solicitud['canton'] . ' <br/>
				</div>
				<div data-linea="6">
				<label>Parroquia: </label> ' . $solicitud['parroquia'] . ' <br/>
				</div>
				<div data-linea="7">
				<label>Dirección: </label> ' . $solicitud['direccionSitio'] . ' <br/>
				</div>
				<div data-linea="8">
				<label>Referencia: </label> ' . $solicitud['referencia'] . ' <br/>
					</div>
					<div data-linea="9">
					<label>Superficie utilizada: </label> ' . $solicitud['superficieArea'] . ' <br/>
					</div>
					<div data-linea="9">
					<label>Croquis: </label>'. ($solicitud['croquis']=='0'? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$solicitud['croquis'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
					</div>
					<div data-linea="10" >
					<label>Estado: </label> ' . ($solicitud['estadoArea']=='registrado'? '<span class="exito">'.$solicitud['estadoArea'].'</span>':'<span class="alerta">'.$solicitud['estadoArea'].'</span>'). '<br/>
     		</div>';
		if($solicitud['ruta_archivo']!='0' && $solicitud['observacionArea']!= ''){
					    echo   '<div data-linea="10">
				<label>Informe: </label>'. ($solicitud['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$solicitud['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
				</div>
				<div data-linea="11">
				<label>Observación: </label> ' . $solicitud['observacionArea'] . ' <br/>
				</div>';
					   }
			echo '</fieldset>';
					   $numeroAreaProduccion++;
	}
	?>


</div>
<div class="pestania">

	<form id="asignarMonto" data-rutaAplicacion="revisionFormularios" data-opcion="asignarMontoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="Operadores"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="verificacion"/>
		<input type="hidden" name="idVue" value="<?php echo $qSolicitud[0]['idVue'];?>"/>
		<input type="hidden" name="idOperador" value="<?php echo $qSolicitud[0]['identificador'];?>"/>
		
		<fieldset>
			<legend>Valor a cancelar</legend>
				<div data-linea="11" >		
					<p class="nota">Por favor ingrese el valor a cancelar por el certificado.</p>
					
					<label>Monto: </label>
						<input type="text" id="monto" name="monto" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,3})?$"/>
				</div>
		</fieldset>			
		<button type="submit" class="guardar">Autorizar pago</button>
	</form>	
	
	<form id="verificarPago" data-rutaAplicacion="revisionFormularios" data-opcion="verificarPagoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="Operadores"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="idOperador" value="<?php echo $qSolicitud[0]['identificador'];?>"/>
		<input type="hidden" name="idVue" value="<?php echo $qSolicitud[0]['idVue'];?>"/>
		
		<fieldset id="factura">
				<legend>Pago de arancel</legend>
					<div data-linea="12" >
						<label>Monto a pagar: </label> $ <?php echo pg_fetch_result($qMonto, 0, 'monto'); ?>
					</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
				<div data-linea="5">
					<label>Número de transacción: </label>
						<input type="text" id="transaccion" name="transaccion" placeholder="Ej: 153628965" data-er="^[0-9]+$"/>
				</div>
				
				<div data-linea="5">
					<label># de factura: </label>
						<input type="text" id="numeroFactura" name="numeroFactura" placeholder="Ej: 00234" data-er="^[0-9-]+$"/>
				</div>
				
				<div data-linea="6">
					<label>Entidad bancaria</label>
						<select id="codigoBanco" name="codigoBanco">
							<option value="">Seleccione....</option>
							<?php 
								while ($fila = pg_fetch_assoc($qEntidadesBancarias)){
									echo '<option value="'.$fila['id_banco']. '">'. $fila['nombre'] .'</option>';
								}
							?>
						</select>
						<input type="hidden" id="nombreBanco" name="nombreBanco"></input>
				</div>	
				
				<div data-linea="7">
					<label>Monto recaudado: </label>
						<input type="text" id="montoRecaudado" name="montoRecaudado" placeholder="Ej: 153" data-er="^[0-9]+(\.[0-9]{1,3})?$"/>
				</div>
				
				<div data-linea="7">
					<label>Fecha recaudación: </label>
						<input type="text" id="fechaRecaudacion" name="fechaRecaudacion" />
				</div>
					
				<div data-linea="8">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="inspeccion">Confirmar pago</option>
						</select>
				</div>	
				
				<div data-linea="9">
					<label>Observaciones</label>
						<input type="text" id="observacion" name="observacion"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Finalizar proceso</button>
	</form>

</div>

<script type="text/javascript">					
var estado= <?php echo json_encode($qSolicitud[0]['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	

		$("#verificarPago").hide();
		$("#asignarMonto").hide();

		if(estado == 'pago'){
			$("#asignarMonto").show();
		}else if(estado == 'verificacion'){
			$("#verificarPago").show();
		}		
	});

	$("#codigoBanco").change(function(){
    	$('#nombreBanco').val($("#codigoBanco  option:selected").text());

	});

	$("#fechaRecaudacion").datepicker({
	    changeMonth: true,
	    changeYear: true
	  });

	$("#asignarMonto").submit(function(event){
		event.preventDefault();
		chequearCamposAsignarMonto(this);
	});

	$("#verificarPago").submit(function(event){
		event.preventDefault();
		chequearCamposVerificarPago(this);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposAsignarMonto(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#monto").val()) || !esCampoValido("#monto")){
			error = true;
			$("#monto").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

	function chequearCamposVerificarPago(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#transaccion").val()) || !esCampoValido("#transaccion")){
			error = true;
			$("#transaccion").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoBanco").val())){
			error = true;
			$("#codigoBanco").addClass("alertaCombo");
		}

		if(!$.trim($("#montoRecaudado").val()) || !esCampoValido("#montoRecaudado")){
			error = true;
			$("#montoRecaudado").addClass("alertaCombo");
		}

		if(!$.trim($("#numeroFactura").val()) || !esCampoValido("#numeroFactura")){
			error = true;
			$("#numeroFactura").addClass("alertaCombo");
		}

		if(!$.trim($("#fechaRecaudacion").val())){
			error = true;
			$("#fechaRecaudacion").addClass("alertaCombo");
		}

		if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
	
</script>