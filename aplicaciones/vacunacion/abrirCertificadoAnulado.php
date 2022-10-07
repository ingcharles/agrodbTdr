<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$va = new ControladorVacunacion();

$idSerieDocumento=$_POST['id'];
$qbuscarCertificadoVacunacion=$va->buscarCertificadoVacunacion($conexion, $idSerieDocumento);
$filaCertificado=pg_fetch_assoc($qbuscarCertificadoVacunacion);
?>

<form id="abrirCertificadoAnulado" data-rutaAplicacion="vacunacion" data-opcion="guardarNuevoCertificadoAnulado" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="idSerieDocumento" value="<?php echo $idSerieDocumento;?>" />
		<div id="nuevoCertificadoAnulado">
		<header>
			<h1>Nuevo Registro Anular Certificado</h1>
		</header>
		<fieldset>
			<legend>Anular Certificado de Vacunación</legend>
			<div data-linea="1">	
				<label>Motivo Anulación :</label> 								   	
			   	<select id="motivoAnulacion" name="motivoAnulacion">
					<option value="0">Seleccione...</option>			
					<option value="Anulado por robo">Anulado por robo</option>
					<option value="Anulado por perdida">Anulado por pérdida</option>			
					<option value="Anulado físico">Anulado físico</option>		
					<option value="Anulado por deterioro">Anulado por deterioro</option>			
				</select>
			</div>
			<div data-linea="2">	
				<label>Provincia :</label> 								   	
			   	<select id="idProvincia" name="idProvincia" >
					<option value="0">Provincia....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select>
			</div>
		</fieldset>
		<p data-linea="2" style="text-align: center">
			<button id="guardarCertificadoAnulado" type="submit" name="guardarCertificadoAnulado" >Anular Certificado</button>
		</p>	   				
	</div>
	<div id="certificadoUtilizado">
		<header>
			<h1>Certificado utilizado</h1>
		</header>
		<fieldset>
		<legend>Certificado utilizado</legend>
			<div data-line="1" >
				<label id="nEliminar" class="alerta">No se puede anular el certificado de vacunación, ya se está utilizado!!!</label>
			</div>		
		</fieldset>
	</div>
	<div id="certificadoAnulado">
		<header>
			<h1>Certificado Anulado</h1>
		</header>
		<fieldset>
			<legend>Información Certificado Anulado</legend>		
			<div data-linea="1">
				<label>Especie: </label>
				<input type="text" id="especie" name="especie" value="<?php echo $filaCertificado['nombre_especie'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="1">
				<label>N° Certificado: </label>
				<input type="text" id="numeroCertificado" name="numeroCertificado" value="<?php echo $filaCertificado['numero_documento'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="2">
				<label>Estado: </label>
				<input type="text" id="estadoCertificado" name="estadoCertificado" value="<?php echo $filaCertificado['estado'];?>" disabled="disabled"/>						
			</div>
			<div data-linea="2">
				<label>Fecha Registro: </label>
				<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaCertificado['fecha_registro'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="3">
				<label>Motivo Anulación: </label>
				<input type="text" id="observacion" name="observacion" value="<?php echo $filaCertificado['observacion'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="4">
				<label>Fecha Modificación: </label>
				<input type="text" id="fechaModificacion" name="fechaModificacion" value="<?php echo $filaCertificado['fecha_modificacion'];?>" disabled="disabled"/>						
			</div>
			<div data-linea="4">
				<label>Usuario Modificación: </label>
				<input type="text" id="usuarioModificacion" name="usuarioModificacion" value="<?php echo $filaCertificado['usuario_modificacion'];?>" disabled="disabled"/>						
			</div>
		</fieldset>
	</div>
</form>
</body>

<script type="text/javascript">
var estadoVacunacion = <?php echo json_encode($filaCertificado['estado']); ?>;


if(estadoVacunacion=='creado'){
	$("#nuevoCertificadoAnulado").show();
	$("#certificadoUtilizado").hide();
	$("#certificadoAnulado").hide();
	
}else if(estadoVacunacion=='utilizado'){
	$("#nuevoCertificadoAnulado").hide();
	$("#certificadoUtilizado").show();
	$("#certificadoAnulado").hide();
}else{
	$("#nuevoCertificadoAnulado").hide();
	$("#certificadoUtilizado").hide();
	$("#certificadoAnulado").show();

}

$(document).ready(function(){
	distribuirLineas();
});

$("#abrirCertificadoAnulado").submit(function(event){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	
	event.preventDefault();
	if($("#motivoAnulacion").val() == 0  ){	
		error = true;		
		$("#motivoAnulacion").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione un motivo.').addClass("alerta");
	}

	if($("#idProvincia").val() == 0  ){	
		error = true;		
		$("#idProvincia").addClass("alertaCombo");	
		$("#estado").html('Por favor seleccione una provincia.').addClass("alerta");	
	}

	if (!error){   
		event.preventDefault();	
		ejecutarJson($(this));
	}
		
});
</script>