<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorComplementos.php';
require_once '../../clases/ControladorEtiquetas.php';

$conexion = new Conexion();
$ce = new ControladorEtiquetas();
$cco = new ControladorComplementos();

$idEtiqueta=$_POST['id'];
$qDatosSolicitudEtiqueta=$ce->abrirSolicitudEtiquetasEnviada($conexion, $idEtiqueta);
$qDatosSolicitudEtiquetaSitios=$ce->obtenerSolicitudesEtiquetasSitios($conexion, $idEtiqueta);
$cantidadItemsSitio=pg_num_rows($qDatosSolicitudEtiquetaSitios);
$banderaAprobado=false;
$banderaPorPagar=false;
$banderaEnviado=false;

if($qDatosSolicitudEtiqueta[0]['estado']=="Por Pagar" ){
	$banderaPorPagar=true;
	$qDatosOrdenPago=$ce->buscarOrdenPagoPorTipoYidSolicitud($conexion, 'Emisión de Etiquetas', $idEtiqueta,$qDatosSolicitudEtiqueta[0]['identificador']);
	$totalPagar=pg_fetch_result($qDatosOrdenPago, 0, 'total_pagar');

}elseif($qDatosSolicitudEtiqueta[0]['estado']=='Enviado' ){
	$banderaEnviado=true;

}elseif($qDatosSolicitudEtiqueta[0]['estado']=='Aprobado' ){
	$banderaAprobado=true;
}

?>
<div id="estado"></div>
<form id='abrirSolicitarEtiquetas' data-rutaAplicacion='etiquetas'  >
	<input type="hidden" id="idSolicitudEtiqueta" name="idSolicitudEtiqueta" value="<?php echo $idEtiqueta;?>"  />
	<input type="hidden" id="opcion" name="opcion" value="0" /> 
	<div id="aprobado">
		<header>
			<h1>Etiquetas a Imprimir</h1>
		</header>
		
		<fieldset>		
			<legend>Número de Etiquetas por Sitio</legend>
			<table style="width:100%;">
					<thead>	
						<tr>
							<th>Seleccione</th>
							<th># Solicitud</th>
							<th>Nombre del Sitio</th>
							<th>Nombre del Área</th>
							<th># Etiquetas</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="tablaDetalle">
					<?php 
					 while($fila=pg_fetch_assoc($qDatosSolicitudEtiquetaSitios)){
						echo '<tr>';
						echo  '<td><input type="radio" class="itemsRadio" name="idSitio" value='.$fila['id_sitio'].'><input type="hidden" class="itemsArea" disabled="disabled" id="idArea" name="idArea" value='.$fila['id_area'].'><input type="hidden" class="itemsArea" disabled="disabled" id="nombreSitio" name="nombreSitio" value="'.$fila['nombre_sitio'].'"></td><td>'.$fila['numero_solicitud'] . '</td><td>'.$fila['nombre_sitio'].'</td><td>'.$fila['nombre_area'].'</td><td>'.$fila['saldo_etiqueta'].'</td>';
						echo '</tr>';
					}
					?>
					</tbody>
				</table>
		</fieldset>
		<div data-linea="1" id="resultadoEtiquetaSitio"></div>
		<button type="submit" id="btnGuardar"  name="btnGuardar" > Imprimir </button>
	</div>
</form>

<div id="porPagar">
	<header>
		<h1>Solicitudes por Pagar</h1>
	</header>
	
	<fieldset>
		<legend>Datos Operador</legend>
		
		<div data-linea="1">
			<label>Número de Identificación: </label> 
			<?php echo $qDatosSolicitudEtiqueta[0]['identificador'];?>
		</div>
		
		<div data-linea="2">
			<label>Razón Social: </label> 
			<?php echo $qDatosSolicitudEtiqueta[0]['razonSocial'];?>
		</div>	
		
		<hr/>	
		<?php 
			$qSitioOperador=$ce->buscarSitiosOperadoresPorCodigoyAreaOperacion($conexion, $qDatosSolicitudEtiqueta[0]['identificador'],'{ACO,COM}','{SV}');
			$contador=3;
			while($datosSitios=pg_fetch_assoc($qSitioOperador)){
				echo '<div data-linea='.$contador.'>
					<label>Nombre Sitio: </label> 
					'.$datosSitios['nombre_sitio'].'
				</div>
				<div data-linea='.++$contador.'>
					<label>Provincia: </label> 
					'.$datosSitios['provincia'].'
				</div>
				<div data-linea='.$contador.'>
					<label>Cantón: </label> 
					'.$datosSitios['canton'].'
				</div>
				<div data-linea='.$contador.'>
					<label>Parroquia: </label> 
					'.$datosSitios['parroquia'].'
				</div>
				<div data-linea='.++$contador.'>
					<label>Dirección: </label> 
					'.$datosSitios['direccion'].'
				</div><br>';
				$contador++;
			}
		?>	
	</fieldset>
	
	<div data-linea="1" id="resultadoEtiquetaSitio">
	
	</div>
	<fieldset>
		<legend>Pago de Solicitud</legend>
		
		<div data-linea="1">
			<label>Número de Etiquetas Solicitadas: </label> 
		</div>
	
		<div data-linea="1">
			<?php echo $qDatosSolicitudEtiqueta[0]['cantidadEtiqueta'];?>
		</div>
		
		<div data-linea="2">
			<label>Monto a Pagar: </label> 
		</div>
		
		<div data-linea="2">
			<?php echo '$'.$totalPagar;?>
		</div>
		
		<br>
		<div data-linea="3">
			<label>Nota:</label>
			<span> Por favor cancele solo el valor indicado.</span>
		</div> 
	</fieldset> 
</div>


<div id="enviado">
	<header>
		<h1>Solicitudes Enviada</h1>
	</header>
	
	<fieldset>
		<legend>Datos Solicitud</legend>
		
		<div data-linea="1">
			<label>Número de Identificación: </label> 
			<?php echo $qDatosSolicitudEtiqueta[0]['identificador'];?>
		</div>
		
		<div data-linea="2">
			<label>Razón Social: </label> 
			<?php echo $qDatosSolicitudEtiqueta[0]['razonSocial'];?>
		</div>	
		
		<div data-linea="3">
		<label>Número de Solicitud: </label> 
			<?php echo $qDatosSolicitudEtiqueta[0]['numeroSolicitud'];?>
		</div>
		
	</fieldset>
</div>

<?php echo $cco->cargarPopup('Agrocalidad Informa', 'Estimado Usuario:', 'Se ha detectado que la versión de su navegador no es compatible para la impresión de etiquetas, por favor actualizar su navegador a la versión mas actual, o de otra forma no podrá realizar la impresión de las mismas.', 'texto-popup-titulo-medio','texto-popup-subtitulo-medio','texto-popup-mensaje-medio');?>


<script type="text/javascript">		  

var controlBanderaEnviado=<?php echo json_encode($banderaEnviado); ?>;
var controlBanderaPorPagar=<?php echo json_encode($banderaPorPagar); ?>;
var controlBanderaAprobado=<?php echo json_encode($banderaAprobado); ?>;
var cantidadItemsSitio=<?php echo json_encode($cantidadItemsSitio); ?>;

$(document).ready(function(){
	distribuirLineas();
	$("#btnGuardar").hide();
	if(controlBanderaEnviado){
		$("#porPagar").hide();
		$("#aprobado").hide();
		$("#enviado").show();
	}else if(controlBanderaPorPagar){
		$("#porPagar").show();
		$("#aprobado").hide();
		$("#enviado").hide();
	}else if (controlBanderaAprobado){
		$("#aprobado").show();
		$("#porPagar").hide();
		$("#enviado").hide();
		var navegador=detectarNavegador();
		alert(navegador.version);
		if((navegador.browser=='Chrome' && navegador.version<=63) || 
			(navegador.browser=='Firefox' && navegador.version<=56) ||
			 (navegador.browser!='Chrome' && navegador.browser!='Firefox')){
			$("#btnGuardar").attr("disabled",true);
			$('#popup').fadeIn('slow');
			$('.popup-overlay').fadeIn('slow');
			$('.popup-overlay').height($(window).height());
		}
	}
});

function mostrarSeccionImprimirEtiqueta(event){
	$("#btnGuardar").show();
	$('#abrirSolicitarEtiquetas').attr('data-destino','resultadoEtiquetaSitio');
	$('#abrirSolicitarEtiquetas').attr('data-opcion','accionesEtiquetas');
    $('#opcion').val('solicitudSitio');
	abrir($("#abrirSolicitarEtiquetas"),event,false); 
	
}

$("#abrirSolicitarEtiquetas").submit(function(event){
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#numeroEtiquetasImprimir").val() == ''  ){	
		error = true;		
		$("#numeroEtiquetasImprimir").addClass("alertaCombo");
		$("#estado").html('Por favor ingrese el número de etiquetas.').addClass("alerta");
	}

	if(parseInt($("#numeroEtiquetasImprimir").val())>parseInt($("#saldoEtiquetasSitio").val())  ){	
		error = true;		
		$("#numeroEtiquetasImprimir").addClass("alertaCombo");
		$("#estado").html('Supera el límite máximo de etiquetas disponibles.').addClass("alerta");
	}
	
	if(!esCampoValido("#numeroEtiquetasImprimir") ){
		error = true;
		$("#numeroEtiquetasImprimir").addClass("alertaCombo");
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}

	if(parseInt($("#numeroEtiquetasImprimir").val())>1000 ){
		error = true;
		$("#numeroEtiquetasImprimir").addClass("alertaCombo");
		$("#estado").html("El límite máximo de etiquetas a imprimir es de 1000 por impresión.").addClass('alerta');
	}
	
	
	if (!error){
		$("#estado").html("").removeClass('alerta');
		$('#abrirSolicitarEtiquetas').attr('data-opcion','generarEtiquetas');    
		$('#abrirSolicitarEtiquetas').attr('data-destino','detalleItem');	 
		abrir($("#abrirSolicitarEtiquetas"),event,false); 
	}	
});

$("#tablaDetalle").on("change"," .itemsRadio:checked",function (event){
	$(".itemsArea").attr('disabled',true);
	$(this).parent().find('input').attr('disabled',false);
	mostrarSeccionImprimirEtiqueta(event);
});

$("#tablaDetalle").on("soloItems"," .itemsRadio",function (event){
	$(".itemsArea").attr('disabled',false);
	$(this).attr("checked",true);
	mostrarSeccionImprimirEtiqueta(event);
});

if(cantidadItemsSitio==1){
	$(".itemsRadio").trigger("soloItems");
}

</script>	