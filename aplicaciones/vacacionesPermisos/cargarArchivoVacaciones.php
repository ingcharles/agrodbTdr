<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cv = new ControladorVacaciones();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];

$subtipos = $cv->obtenerSubTipoPermiso($conexion);

while ($fila = pg_fetch_assoc($subtipos)){
	$resTipos[] = array(id_subtipo_permiso=>$fila['id_subtipo_permiso'],nombre=>$fila['descripcion_subtipo'],
			minutos=>$fila['minutos_permitidos'],id_tipo_permiso=>$fila['id_tipo_permiso'],
			requiere_adjunto=>$fila['requiere_adjunto'],presentacion_reintegro=>$fila['presentacion_despues_reintegro'],
			detalle_permiso=>$fila['detalle_permiso'], codigo=>$fila['codigo']);
}

$id_subtipo=$_POST['id'];
$filaSolicitud = pg_fetch_assoc($cv->obtenerPermisoSolicitado($conexion,$id_subtipo));

$saldos = pg_fetch_assoc($cv->consultarSaldoFuncionario($conexion,$_SESSION['usuario']));
?>
<header>
	<h1>Solicitud de permisos o vacaciones</h1>
</header>	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $_SESSION['usuario']; ?>" />
	<input type="hidden" id="id_registro" name="id_registro" value="<?php echo $id_subtipo; ?>" />

	<div id="estado"></div>
	
	<fieldset>
		<legend>Tipo de solicitud</legend>
		
		<div id="motivoRechazo" data-linea="99">
			<label><?php echo $filaSolicitud['estado'];?></label> 
				<?php echo ' -> '.$filaSolicitud['observacion'];?>
		</div>
		
		<div data-linea="1">
			<label>Tipo de solicitud</label> 
				<select name="tipoSolicitud" id="tipoSolicitud" disabled="disabled">
					<option value="">Seleccione....</option>
						<?php 	
							$tipoPermiso = $cv->obtenerTipoPermiso($conexion);
							while($fila = pg_fetch_assoc($tipoPermiso)){
								echo '<option value="' . $fila['id_permiso'] . '">' . $fila['descripcion_permiso'].' </option>';
							}
						?>
			</select>
		</div>

		<div data-linea="2">
			<label>Subtipo de solicitud</label> 
				<select name="subTipoSolicitud" id="subTipoSolicitud" disabled="disabled">
					<option value="">Seleccione un tipo permiso...</option>
					<?php 	
						$subTipoPermiso = $cv->obtenerSubTipoPermiso($conexion,null,$filaSolicitud['sub_tipo']);
							while($fila = pg_fetch_assoc($subTipoPermiso)){
								echo '<option value="' . $fila['id_subtipo_permiso'] . '" data-minutos="'.$fila['minutos_permitidos'].'" data-detalle="'.$fila['detalle_permiso'].'" data-codigo="'.$fila['codigo'].'">' . $fila['descripcion_subtipo'].' </option>';
							}
					?>
			</select>
		</div>
		
		<div data-linea="3" id="comisionLocal">
			<label>Destino Comisión Local</label> 
				<?php echo $filaSolicitud['destino_comision'];?>
		</div>

		<div data-linea="5" id="descripcionSolicitud">
			<?php echo $filaSolicitud['detalle_permiso'];?>
		</div>
	</fieldset>

	<fieldset id="detalle">
		<legend>Fechas y Tiempo requerido</legend>
		<div data-linea="4">
			<label>Fecha de suceso: </label>
				<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_suceso']));?>
		</div>
		
		<hr id="separador">
		
		<div data-linea="5">
			<label>Fecha de salida</label> 
				<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_inicio']));?>
		</div>

		<div data-linea="5">
			<label>Hora de salida:</label> 
				<?php echo date('H:i',strtotime($filaSolicitud['fecha_inicio']));?>
		</div>
		
		<hr>
		
		<div data-linea="6">
			<label>Fecha de retorno</label> 
				<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_fin']));?>
		</div>
		
		<div data-linea="6">
			<label>Hora de retorno:</label> 
				<?php echo date('H:i',strtotime($filaSolicitud['fecha_fin']));?>
		</div>
		
	</fieldset>

	<fieldset id="adjuntos">
		<legend>Documento para Justificación</legend>

			<div data-linea="1">
				<label>Certificado del justificativo:</label>
				<?php echo ($filaSolicitud['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$filaSolicitud['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Certificado cargado</a>')?>
			</div>
			<?php 
			
			$time = time();
			$fechaActual=strtotime(date("Y-m-d H:i:s", $time));
			$fechaMaxima=strtotime($filaSolicitud['fecha_maxima_presentar_justificacion']);
		
			if($fechaMaxima > $fechaActual ){
			?>
			<form id="subirArchivo" action="aplicaciones/vacacionesPermisos/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				
				<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
				<input type="hidden" name="id" value="<?php echo $id_subtipo;?>" /> 
				
				<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
			</form>
			<?php 	
			}else{
				
				echo ($filaSolicitud['ruta_archivo']=='0'? '<br><br><span class="alerta">Ha superado el tiempo establecido para cargar el documento justificativo del permiso.</span>': '');
			}			
			?>
			<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
	</fieldset>

<script type="text/javascript">
var array_subTipos= <?php echo json_encode($resTipos); ?>;
var fechaMaxima= new Date(<?php echo json_encode($fechaMaxima); ?>);
var fechaHoy = new Date(<?php echo json_encode($fechaActual); ?>);

$(document).ready(function(){
	distribuirLineas();
	construirValidador();

	var estado= <?php echo json_encode($filaSolicitud['estado']); ?>;
	var observacion= <?php echo json_encode($filaSolicitud['observacion']); ?>;

	cargarValorDefecto("tipoSolicitud","<?php echo $filaSolicitud['id_permiso']?>");
	cargarValorDefecto("subTipoSolicitud","<?php echo $filaSolicitud['sub_tipo']?>");

	$("#horaSalida").attr("readonly","readonly");
	$("#horaRetorno").attr("readonly","readonly");

	$("#motivoRechazo").hide();
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-ER" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-AM" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV"){
		   $("#etiquetaFechaSuceso").hide();
	       $("#fechaSuceso").hide();
	       $("#separador").hide();
	}else{
		$("#etiquetaFechaSuceso").show();
	    $("#fechaSuceso").show();
	    $("#separador").show();
	}

	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#comisionLocal").show();
	}else{
		$("#comisionLocal").hide();
	}

	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CP"){
		$("#comisionProvincial").show();
	}else{
		$("#comisionProvincial").hide();
	}

	if((estado == "Rechazado") && (observacion!='')) {
		$("#motivoRechazo").show();
	}else{
		$("#motivoRechazo").hide();
	}

	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#adjuntos").hide();
	}else{
		if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CE" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CP"){
			$("#adjuntos").show();
		}		
		
	}
 });

$("#modificar").click(function(){
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#actualizarSolicitud").submit(function(event){
 $("input").removeAttr("disabled");
 event.preventDefault();
 chequearCampos(this); 
});

$("#tipoSolicitud").change(function(){
	$("#subtipoSolicitud").show();
	$("#subtipoSolicitud").val();
	$("#fechaSuceso").val("");
	$("#fechaSalida").val("");
	$("#horaSalida").val("");
	$("#fechaRetorno").val("");
	$("#horaRetorno").val("");
	
	subTiposPermisos = '<option value="">Seleccione....</option>';
	
	for(var i=0;i<array_subTipos.length;i++){
		if ($("#tipoSolicitud").val()==array_subTipos[i]['id_tipo_permiso']){
			subTiposPermisos += '<option value="'+array_subTipos[i]['id_subtipo_permiso']+'" data-minutos="'+array_subTipos[i]['minutos']+'" data-detalle="'+array_subTipos[i]['detalle_permiso']+'" data-codigo="'+array_subTipos[i]['codigo']+'">'+array_subTipos[i]['nombre']+'</option>';
			}
	}
	
	$("#subtipoSolicitud").show();
	$('#subTipoSolicitud').html(subTiposPermisos);
	$("#subTipoSolicitud").removeAttr("disabled");
});	

$("#subTipoSolicitud").change(function(){
	$("#estado").html('');
	$("#actualizar").show();

	$("#detalle").show();
	$("#fechaSuceso").val("");
	$("#fechaSalida").val("");
	$("#fechaRetorno").val("");
	$("#horaSalida").val("08:00");
	$("#horaRetorno").val("17:00");
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-ER" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-AM" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-MH" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#horaSalida").removeAttr("readonly");
		$("#horaRetorno").removeAttr("readonly");
		$("#horaSalida").val("");
		$("#horaRetorno").val("");
	}
	
	$("#fechaSuceso").removeAttr("disabled");
	$("#fechaSalida").removeAttr("disabled");
	
	$("#descripcionSolicitud").html(($("#subTipoSolicitud option:selected").attr("data-detalle")));
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-ER" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-AM" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV"){
	   $("#etiquetaFechaSuceso").hide();
       $("#fechaSuceso").hide();
       $("#separador").hide();
	}else{
		$("#etiquetaFechaSuceso").show();
	    $("#fechaSuceso").show();
	    $("#separador").show();
	}
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV"){
		$("#adjuntos").hide();
	}else{
		$("#adjuntos").show();
	}

	//Permiso Rehabilitación
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="EN-RE"){
		$("#horaSalida").removeAttr("readonly");
		$("#horaRetorno").removeAttr("readonly");
		$("#horaSalida").val("");
		$("#horaRetorno").val("");

		if(permisoEnfermedad == 0){
			alert('No puede crear una solicitud de rehabilitación dado que no posee una licencia por enfermedad aprobada.');

			$("#detalle").hide();
			$("#adjuntos").hide();
			$("#actualizar").hide();
		}
	}

	//Permiso Recién Nacido
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN"){
		$("#horaSalida").removeAttr("readonly");
		$("#horaRetorno").removeAttr("readonly");
		$("#horaSalida").val("");
		$("#horaRetorno").val("");

		if(permisoMaternidad == 0){
			alert('No puede crear una solicitud de licencia de ciudado de recién nacido dado que no posee una licencia por maternidad aprobada.');

			$("#detalle").hide();
			$("#adjuntos").hide();
			$("#actualizar").hide();
		}
	}

	//Comisiones Locales
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#comisionLocal").show();
		$("#lugarComisionLocal").attr("required","required");
	}

	//Comisiones Provinciales
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CP"){
		$("#comisionProvincial").show();
		$("#lugarComisionProvincial").attr("required","required");
	}
	
});

$("#fechaSuceso").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {

    	var fecha=new Date($('#fechaSuceso').datepicker('getDate'));
    	
	  	$('#fechaSalida').datepicker('option', 'minDate', $("#fechaSuceso" ).val()); 
	  	
		fecha.setDate(fecha.getDate());
	  	fecha.setMonth(fecha.getMonth());
		fecha.setUTCFullYear(fecha.getUTCFullYear());  
		
		$('#fechaSalida').datepicker('option', 'maxDate', fecha);
    }
  });

$("#fechaSalida").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {

    	$("#fechaRetorno").removeAttr("disabled");
	  	$('#fechaRetorno').datepicker('option', 'minDate', $("#fechaSalida" ).val()); 
	
	    var dias=parseInt($("#subTipoSolicitud option:selected").attr("data-minutos"))/480;

		var diasDisponibles=Math.floor(parseInt($("#disponibilidad").val())/480);

    	if(($("#subTipoSolicitud option:selected").attr('data-codigo') == "VA-VA") || ($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIV")){
	    	if((dias > diasDisponibles)){
	        	dias=diasDisponibles;
	        }
    	}
	  	   
	  	var fecha=new Date($('#fechaSalida').datepicker('getDate'));
	
	  	if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-RN") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-AM") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-ER") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-PIV")){
	  		fecha.setDate(fecha.getDate()+dias);
	  	}else{
	  		fecha.setDate(fecha.getDate()+dias-1);
	  	}

		fecha.setMonth(fecha.getMonth());
		fecha.setUTCFullYear(fecha.getUTCFullYear());  
		$('#fechaRetorno').datepicker('option', 'maxDate', fecha);
    }
});

$("#fechaRetorno").datepicker({
	yearRange: "c:c+1",
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst){

    	var fechaSalida=new Date($('#fechaSalida').datepicker('getDate'));
    	var fechaRetorno=new Date($('#fechaRetorno').datepicker('getDate'));

    	var diferencia =  (Math.floor(( Date.parse(fechaRetorno) - Date.parse(fechaSalida) ) / 86400000)) + 1;

    	if(diferencia < 0){
    		diferencia = diferencia*(-1);
    	}
    	
    	//Revisar número de días para permisos (<14) y vacaciones (15>)
			
    	if($("#subTipoSolicitud option:selected").attr('data-codigo') == "VA-VA"){
	    	if(diferencia < 15){
	        	alert("Por favor seleccione un Permiso con Cargo a Vacaciones para solicitudes menores a 15 días.");

	        	cargarValorDefecto("tipoSolicitud","");
	        	$("#subTipoSolicitud").html("");
	        }
    	}else if($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIV"){
	    	if(diferencia >= 15){
	        	alert("Por favor seleccione una licencia de Vacaciones para solicitudes a partir de 15 días.");

	        	cargarValorDefecto("tipoSolicitud","");
	        	$("#subTipoSolicitud").html("");
	        }
    	}
    }
  });


$("#horaSalida").change(function(){

$("#horaSalida").removeClass('alertaCombo');
	
	var horaNueva = $("#horaSalida").val().replace(/\_/g, "0");
	$("#horaSalida").val(horaNueva);

	var hora = $("#horaSalida").val().substring(0,2);
	var minuto = $("#horaSalida").val().substring(3,5);

	if((parseInt(hora) == 17 && parseInt(minuto) > 0) || (parseInt(hora) == 17 && parseInt(minuto) == 0)){
		$("#horaSalida").addClass('alertaCombo');
		$("#estado").html("La hora ingresada está fuera del horario de oficina").addClass('alerta');

	}else if(parseInt(hora)>=8 && parseInt(hora)<18){

		if(parseInt(minuto)>=0 && parseInt(minuto)<60){

			//Maternidad Recién Nacido
			if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN")
			 {
				if(parseInt(hora)>=8 && parseInt(hora)<13){
					$("#horaSalida").val('08:00');
					$("#horaRetorno").val('10:00');
					$("#horaRetorno").attr('readonly', 'readonly');
				}else if(parseInt(hora)>=13 && parseInt(hora)<18){
					$("#horaSalida").val('15:00');
					$("#horaRetorno").val('17:00');
					$("#horaRetorno").attr('readonly', 'readonly');
				}				
			 }

			//Permisos de 2 horas
			if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-AM") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "EN-RE") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-ER")){
				if((parseInt(hora)+2) >= 17){
					$("#horaRetorno").val('17:00');
					$("#horaRetorno").attr('readonly', 'readonly');
				}else{
					$("#horaRetorno").val((parseInt(hora)+2)+':'+minuto);
					$("#horaRetorno").attr('readonly', 'readonly');
				}	
			 }
			
		}else{
			$("#horaSalida").addClass('alertaCombo');
			$("#estado").html("Los minutos ingresados están incorrecto, por favor actualice la información").addClass('alerta');
		}
				
	}else{
		$("#horaSalida").addClass('alertaCombo');
		$("#estado").html("La hora ingresada está fuera de horario de oficina").addClass('alerta');
	}


});

$("#horaRetorno").change(function(){
$("#horaRetorno").removeClass('alertaCombo');
	
	var horaNueva = $("#horaRetorno").val().replace(/\_/g, "0");
	$("#horaRetorno").val(horaNueva);

	var hora = $("#horaRetorno").val().substring(0,2);
	var minuto = $("#horaRetorno").val().substring(3,5);

	if(parseInt(hora) == 17 && parseInt(minuto) > 0){
		$("#horaRetorno").addClass('alertaCombo');
		$("#estado").html("La hora ingresada está fuera del horario de oficina").addClass('alerta');

	}else if((parseInt(hora)>=8 && parseInt(hora)<18) && (parseInt($("#horaSalida").val().substring(0,2)) < parseInt(hora))){

		if(parseInt(minuto)>=0 && parseInt(minuto)<60){

			if(parseInt($("#horaRetorno").val().substring(0,2)) >= parseInt($("#horaSalida").val().substring(0,2))){

				if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-AM") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "EN-RE")){

					if(parseInt(hora) > (parseInt($("#horaSalida").val().substring(0,2))+2)){
						alert("La hora de retorno seleccionada es superior a la permitida, por favor actualice la información, o las horas de diferencia serán restadas de las vacaciones");
					}else{
						$("#estado").html("");
					}

				}
				
			}else{
				$("#horaRetorno").addClass('alertaCombo');
				$("#estado").html("La hora de retorno es menor a la hora de salida, por favor actualice la información").addClass('alerta');
			} 
			
		}else{
			$("#horaRetorno").addClass('alertaCombo');
			$("#estado").html("Los minutos ingresados están incorrecto, por favor actualice la información").addClass('alerta');
		}
				
	}else{
		$("#horaRetorno").addClass('alertaCombo');
		$("#estado").html("La hora ingresada está fuera de horario de oficina").addClass('alerta');
	}
});
  
  
/*$('button.subirArchivo').click(function (event) {
	
    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");
    numero = Math.floor(Math.random()*100000000);
       
    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        subirArchivo(archivo, $("#identificador").val() +"_id_Certificado_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }        
});*/


/*function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
}

function chequearCampos(form){
	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#archivo").val()==""){
			error = true;
			$("#archivo").addClass("alertaCombo");
		}
			
		if (error){
			$("#estado").html("Por favor debe cargar el archivo de respaldo.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}	 
 } */

$("#archivo").click(function(){
	$("#subirArchivo button").removeAttr("disabled");});
 
</script>