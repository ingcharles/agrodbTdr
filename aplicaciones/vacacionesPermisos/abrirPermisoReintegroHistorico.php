<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cv = new ControladorVacaciones();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
}

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
	<h1>Reintegro de permisos o vacaciones</h1>
</header>

<div id="estado1"></div>

<form id="actualizarSolicitud" data-rutaAplicacion="vacacionesPermisos" data-opcion="gestionVacaciones" data-accionEnExito="ACTUALIZAR">
	
	<input type="hidden" id="opcion" name="opcion" value="Actualizar" /> 
	<input type="hidden" id="disponibilidad" name="disponibilidad" value="<?php echo $saldos['minutos_disponibles']; ?>" /> 
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
								$opcionTipoPermiso=$fila['codigo'];
								echo '<option value="' . $fila['id_subtipo_permiso'] . '" data-minutos="'.$fila['minutos_permitidos'].'" data-detalle="'.$fila['detalle_permiso'].'" data-codigo="'.$fila['codigo'].'">' . $fila['descripcion_subtipo'].' </option>';
							}
					?>
			</select>
		</div>
		
		<div data-linea="3" id="comisionLocal">
			<label>Destino Comisión Local</label> 
				<input type="text" id="lugarComisionLocal" name="lugarComisionLocal" value="<?php echo $filaSolicitud['destino_comision'];?>" disabled="disabled"/>
		</div>
		<div data-linea="3" id="comisionExterior">
			<label>Destino Comisión al Exterior</label> 
				<input type="text" id="lugarComisionLocal" name="lugarComisionLocal" value="<?php echo $filaSolicitud['destino_comision'];?>" disabled="disabled"/>
		</div>		
		<div data-linea="4" id="comisionProvincial">
			<label>Destino Comisión Provincial</label> 
				<select name="lugarComisionProvincial" id="lugarComisionProvincial" disabled="disabled">
					<option value="">Seleccione....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							if($provincia['nombre'] == $filaSolicitud['destino_comision']){
								echo '<option value="' . $provincia['nombre'] . '" selected="selected">' . $provincia['nombre'] . '</option>';
							}else{
								echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
							}
						}
					?>
			</select>
		</div>

		<div data-linea="5" id="descripcionSolicitud">
			<?php echo $filaSolicitud['detalle_permiso'];?>
		</div>
	</fieldset>

	<fieldset id="detalle">
		<legend>Fechas y Tiempo requerido</legend>
					<?php echo $opcionTipoPermiso; if($opcionTipoPermiso != 'PE-DA'){?>
		<div data-linea="4">
			<label id="etiquetaFechaSuceso">Fecha de suceso:</label>
				<input type="text" id="fechaSuceso" name="fechaSuceso" value="<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_suceso']));?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" disabled="disabled" />
		</div>
		<hr id="separador">
		<div data-linea="5">
			<label>Fecha de salida</label> 
				<input type="text" id="fechaSalida" name="fechaSalida" value="<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_inicio']));?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" disabled="disabled" />
		</div>

		<div data-linea="5">
			<label>Hora de salida:</label> 
				<input id="horaSalida" name="horaSalida" class="menores" value="<?php echo date('H:i',strtotime($filaSolicitud['fecha_inicio']));?>"type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" disabled="disabled"/>
		</div>
		
		
		<hr>
		
		<div data-linea="6">
			<label>Fecha de retorno</label> 
				<input type="text" id="fechaRetorno" name="fechaRetorno" value="<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_fin']));?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" disabled="disabled"/>
		</div>
		
		<div data-linea="6">
			<label>Hora de retorno:</label> 
				<input id="horaRetorno" name="horaRetorno" class="menores" value="<?php echo date('H:i',strtotime($filaSolicitud['fecha_fin']));?>" type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" disabled="disabled"/>
		</div>
		<?php }else { $tiempoDescontado=$cv->devolverFormatoDiasDisponibles($filaSolicitud['minutos_utilizados']);	?>
					<div data-linea="4">
			<label id="tiempo">Tiempo descontado:</label>
				<input type="text" value="<?php echo $tiempoDescontado;  ?>" disabled="disabled" />
		</div>
		<?php	}?>
	</fieldset>

	<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $filaSolicitud['ruta_archivo']; ?>" />
</form>

<fieldset id="adjuntos">
		<legend>Documento para Justificación</legend>

			<div data-linea="1">
				<label>Certificado del justificativo:</label>
				<?php echo ($filaSolicitud['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$filaSolicitud['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Certificado cargado</a>')?>
			</div>
	</fieldset>
	
	<fieldset id="accionPersonal">
		<legend>Acción de Reintegro</legend>

			<div data-linea="1">
				<label>Acción de Reintegro:</label>
				<?php echo ($filaSolicitud['ruta_archivo_reintegro']==''? '<span class="alerta">No ha generado ningún archivo aún</span>':'<a href='.$filaSolicitud['ruta_archivo_reintegro'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Acción de reintegro creada</a>')?>
			</div>
	</fieldset>

<script type="text/javascript">
var array_subTipos= <?php echo json_encode($resTipos); ?>;
var usuario = <?php echo json_encode($usuario); ?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();

	$("#accionPersonal").hide();

	if(usuario == '0'){
		$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#modificar").attr("disabled", "disabled");
	}

	var estado= <?php echo json_encode($filaSolicitud['estado']); ?>;
	var observacion= <?php echo json_encode($filaSolicitud['observacion']); ?>;
	var archivoCertificado= <?php echo json_encode($filaSolicitud['ruta_archivo']); ?>;

	cargarValorDefecto("tipoSolicitud","<?php echo $filaSolicitud['id_permiso']?>");
	cargarValorDefecto("subTipoSolicitud","<?php echo $filaSolicitud['sub_tipo']?>");

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
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-DA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#adjuntos").hide();
	}else{
		$("#adjuntos").show();
	}

	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#comisionLocal").show();
	}else{
		$("#comisionLocal").hide();
	}
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CE"){
		$("#comisionExterior").show();
	}else{
		$("#comisionExterior").hide();
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
	
	if((estado == "Aprobado") && (archivoCertificado=="0")) {
		$("#cargaArchivo").hide();
	}

	if(estado == "InformeGenerado") {
		$("#accionPersonal").show();
	}
 });

$("#modificar").click(function(){
	$("#actualizarSolicitud input").removeAttr("disabled");
	$("#actualizarSolicitud select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
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
		$("#horaSalida").val("");
		$("#horaRetorno").val("");
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
	
	if(parseInt(hora)>=1 && parseInt(hora)<25){
		if(parseInt(minuto)>=0 && parseInt(minuto)<60){
			if(parseInt(hora)==24){
				minuto = '00';
				$("#horaSalida").val('24:00');
			}
	
			//Permisos de 2 horas
			if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-RN") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-AM") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "EN-RE") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-ER")){
	
					if(parseInt(hora)==24){
						$("#horaRetorno").val('02'+':'+minuto);
						$("#horaRetorno").attr('readonly', 'readonly');
					}else if((parseInt(hora)+2) > 24){
						var horaRetorno = 24- parseInt(hora);
						
						$("#horaRetorno").val('0'+horaRetorno +':'+minuto);
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
		$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
	}

});

$("#horaRetorno").change(function(){
	
	$("#horaRetorno").removeClass('alertaCombo');
	
	var horaNueva = $("#horaRetorno").val().replace(/\_/g, "0");
	$("#horaRetorno").val(horaNueva);
	
	var hora = $("#horaRetorno").val().substring(0,2);
	var minuto = $("#horaRetorno").val().substring(3,5);
	
	 if((parseInt(hora)>=1 && parseInt(hora)<25)){
	
			if(parseInt(minuto)>=0 && parseInt(minuto)<60){
	
				if(parseInt(hora)==24){
					minuto = '00';
					$("#horaRetorno").val('24:00');
				}
				
			}else{
				$("#horaRetorno").addClass('alertaCombo');
				$("#estado").html("Los minutos ingresados están incorrecto, por favor actualice la información").addClass('alerta');
			}
				
	}else{
		$("#horaRetorno").addClass('alertaCombo');
		$("#estado").html("La hora ingresada no es correcta, por favor seleccione la hora en la que finaliza su permiso.").addClass('alerta');
	}
});
  
  
$('button.subirArchivo').click(function (event) {
	
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
});


function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
}


$("#actualizarSolicitud").submit(function(event){
	 $("input").removeAttr("disabled");
	 event.preventDefault();
		
	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#horaSalida").val()==""){
			error = true;
			$("#horaSalida").addClass("alertaCombo");
		}
		
		if($("#horaRetorno").val()==""){
			error = true;
			$("#horaRetorno").addClass("alertaCombo");
		}

		if($("#fechaSalida").val()==""){
			error = true;
			$("#fechaSalida").addClass("alertaCombo");
		}

		if($("#fechaRetorno").val()==""){
			error = true;
			$("#fechaRetorno").addClass("alertaCombo");
		}

		if($("#tipoSolicitud").val()==""){
			error = true;
			$("#tipoSolicitud").addClass("alertaCombo");
		}
		
		if($("#subTipoSolicitud").val()==""){
			error = true;
			$("#subTipoSolicitud").addClass("alertaCombo");
		}
		
		if($("#disponibilidad").val()==0){
			if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA"){
				error = true;
				$("#subTipoSolicitud").addClass("alertaCombo");
				alert('Usted no posee tiempo disponible para solicitar permisos con cargo a vacaciones.');
			}
		}

		if($("#horaSalida").val()!=""){
			$("#horaSalida").removeClass('alertaCombo');
			
			var horaNueva = $("#horaSalida").val().replace(/\_/g, "0");
			$("#horaSalida").val(horaNueva);

			var hora = $("#horaSalida").val().substring(0,2);
			var minuto = $("#horaSalida").val().substring(3,5);


			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaSalida").val('24:00');
					}

					//Permisos de 2 horas
					if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-RN") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-AM") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "EN-RE") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-ER")){
							if(parseInt(hora)==24){
								$("#horaRetorno").val('02'+':'+minuto);
								$("#horaRetorno").attr('readonly', 'readonly');
							}else if((parseInt(hora)+2) > 24){
								var horaRetorno = 24- parseInt(hora);
								
								$("#horaRetorno").val('0'+horaRetorno +':'+minuto);
								$("#horaRetorno").attr('readonly', 'readonly'); 
							}else{
								$("#horaRetorno").val((parseInt(hora)+2)+':'+minuto);
								$("#horaRetorno").attr('readonly', 'readonly');
							}
					 }
				}else{
					$("#horaSalida").addClass('alertaCombo');
					error = true;
				}
			}else{
				$("#horaSalida").addClass('alertaCombo');
				error = true;
			}

		}

		if($("#horaRetorno").val()!=""){
			$("#horaRetorno").removeClass('alertaCombo');
			
			var horaNueva = $("#horaRetorno").val().replace(/\_/g, "0");
			$("#horaRetorno").val(horaNueva);

			var hora = $("#horaRetorno").val().substring(0,2);
			var minuto = $("#horaRetorno").val().substring(3,5);

			 if((parseInt(hora)>=1 && parseInt(hora)<25)){
	
					if(parseInt(minuto)>=0 && parseInt(minuto)<60){
	
						if(parseInt(hora)==24){
							minuto = '00';
							$("#horaRetorno").val('24:00');
						}

					}else{
						$("#horaRetorno").addClass('alertaCombo');
						error = true;
					}
						
			}else{
				$("#horaRetorno").addClass('alertaCombo');
				error = true;
			}
		}		
	
		if (error == false){
			ejecutarJson(this);			
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}	
			 
	});

$("#archivo").click(function(){
	$("#subirArchivo button").removeAttr("disabled");
});
</script>