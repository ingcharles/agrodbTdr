<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cv = new ControladorVacaciones();
$cc = new ControladorCatalogos();

$poseePermisoEnfermedad = 0;
$poseePermisoMaternidad = 0;

$identificador = $_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
}

$saldos = pg_fetch_assoc($cv->consultarSaldoFuncionario($conexion,$identificador));

$subtipos = $cv->obtenerSubTipoPermiso($conexion,'usuario',null);

while ($fila = pg_fetch_assoc($subtipos)){
	$resTipos[] = array(id_subtipo_permiso=>$fila['id_subtipo_permiso'],nombre=>$fila['descripcion_subtipo'],
			minutos=>$fila['minutos_permitidos'],id_tipo_permiso=>$fila['id_tipo_permiso'],
			requiere_adjunto=>$fila['requiere_adjunto'],presentacion_reintegro=>$fila['presentacion_despues_reintegro'],
			detalle_permiso=>$fila['detalle_permiso'], codigo=>$fila['codigo']);

	//Enfermedad
	if($fila['codigo'] == 'EN-EF' || $fila['codigo'] == 'EN-EC'){
		$idSubtipoEnfermedad[]=$fila['id_subtipo_permiso'];
	}

	//Maternidad
	if($fila['codigo'] == 'NA-MA'){
		$idSubtipoMaternidad[]=$fila['id_subtipo_permiso'];
	}
}
//Buscar permisos por enfermedad previamente creados y aprobados
$idSubtiposEnfermedad = implode(",", $idSubtipoEnfermedad);
$permisoEnfermedad = $cv->buscarPermisosXSubtipo($conexion, $identificador, $idSubtiposEnfermedad, "'Aprobado', 'InformeGenerado'");

if(pg_num_rows($permisoEnfermedad) > 0){
	$poseePermisoEnfermedad = 1;
}else{
	$poseePermisoEnfermedad = 0;
}

//Buscar permisos por maternidad previamente creados y aprobados
$idSubtiposMaternidad = implode(",", $idSubtipoMaternidad);
$permisoMaternidad = $cv->buscarPermisosXSubtipo($conexion, $identificador, $idSubtiposMaternidad, "'Aprobado', 'InformeGenerado'");

if(pg_num_rows($permisoMaternidad) > 0){
	$poseePermisoMaternidad = 1;
}else{
	$poseePermisoMaternidad = 0;
}

?>

<header>
	<h1>Nueva solicitud de permiso</h1>
</header>

<div id="estado"></div>
<div id="resultadoIni">
<form id="nuevaSolicitud" data-rutaAplicacion="vacacionesPermisos" data-opcion="confirmarNuevaSolicitud" data-destino="resultadoFin">
    <input type="hidden" id="opcionPermiso" name="opcionPermiso" value="ninguno"/>
	<input type="hidden" id="opcionTipoPermiso" name="opcionTipoPermiso" value="ninguno"/>
	<input type="hidden" id="opcion" name="opcion" value="Nuevo" /> 
	<input type="hidden" id="disponibilidad" name="disponibilidad" value="<?php echo $saldos['minutos_disponibles']; ?>" /> 
<input type="hidden" id="archivo" name="archivo" value="0" />
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador; ?>" />

	<div id="estado1"></div>

	<fieldset>
		<legend>Tipo de solicitud</legend>
		<div data-linea="1">
			<label>Tipo de solicitud</label> <select name="tipoSolicitud"
				id="tipoSolicitud">
				<option value="">Seleccione....</option>
				<?php 	
				$tipoPermiso = $cv->obtenerTipoPermiso($conexion);
				while($fila = pg_fetch_assoc($tipoPermiso)){
					echo '<option value="' . $fila['id_permiso'] . '">' . $fila['descripcion_permiso'].' </option>';
				}
				?>
			</select>
		</div>

		<div data-linea="2" id="subtipoSolicitud">
			<label>Subtipo de solicitud</label> <select name="subTipoSolicitud"
				id="subTipoSolicitud" disabled="disabled">
				<option value="">Seleccione....</option>
			</select>
		</div>

		<div data-linea="3" id="comisionLocal">
			<label>Destino Comisión Local</label> <input type="text"
				id="lugarComisionLocal" name="lugarComisionLocal" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0.,-9#\- ]+$" maxlength="127" />
		</div><div id="llugarComisionLocal"></div>
		<div data-linea="3" id="comisionExterior">
			<label>Destino Comisión Exterior</label> <input type="text"
				id="lugarComisionExterior" name="lugarComisionExterior" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0.,-9#\- ]+$" maxlength="127"/>
		</div><div id="llugarComisionExterior"></div>
		<div data-linea="4" id="comisionProvincial">
			<label>Destino Comisión Provincial</label> <select
				name="lugarComisionProvincial" id="lugarComisionProvincial">
				<option value="">Seleccione....</option>
				<?php 
				$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
				foreach ($provincias as $provincia){
					echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
				}
				?>
			</select>
		</div>

		<div data-linea="5" id="descripcionSolicitud"></div>
	</fieldset>
     <fieldset id="seleccionDetalle">
		<legend>Opcion</legend>
		<div data-linea="5">
		<table id="busqueda">
                <tr>
                    <td class="obligatorio">Seleccionar opcion: </td>                                   
                    <td ><input name="tipo" type="radio" id="busqueda1" onclick="verificar(id)"></td >
                    <td >Horas</td>                                   
                    <td ><input name="tipo" type="radio" id="busqueda2" onclick="verificar(id)"></td >
                    <td >Dia(s)</td>  
                    <td ><input name="tipo" type="radio" id="busqueda3" onclick="verificar(id)"></td >
                    <td >Dias-Horas (08:00-16:30)</td>                                   
                </tr>              
            </table>  
		</div>

	</fieldset>
	<fieldset id="detalle">
		<legend>Fechas y Tiempo requerido</legend>
		<div data-linea="4">
			<label id="etiquetaFechaSuceso">Fecha de suceso:</label> <input
				type="text" id="fechaSuceso" name="fechaSuceso"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" disabled="disabled" readonly/>
		</div>
		<hr id="separador">
		<div data-linea="5">
			<label>Fecha de salida</label> <input type="text" id="fechaSalida"
				name="fechaSalida" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"
				disabled="disabled" readonly/>
		</div>
		<div data-linea="5">
			<label>Hora de salida:</label> <input id="horaSalida"
				name="horaSalida" class="menores" type="text" placeholder="10:30"
				data-inputmask="'mask': '99:99'" />
		</div>
		<hr>
		<div data-linea="6">
			<label>Fecha de finalización</label> <input type="text" id="fechaRetorno"
				name="fechaRetorno" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly />
		</div>
		<div data-linea="6">
			<label>Hora de finalización:</label> <input id="horaRetorno"
				name="horaRetorno" class="menores" type="text" placeholder="10:30"
				data-inputmask="'mask': '99:99'" />
		</div>
	</fieldset>

	<fieldset id="adjuntos">
		<legend>Documento para Justificación</legend>

		<div data-linea="8">
			<input type="file" class="archivo" name="informe"
				accept="application/pdf" /> 
				<input type="hidden" class="rutaArchivo" name="archivo" value="0" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo;
				<?php echo ini_get('upload_max_filesize');?>
				B)
			</div>
			<button type="button" class="subirArchivo"
				data-rutaCarga="aplicaciones/vacacionesPermisos/certificados">Subir
				archivo</button>
		</div>
	</fieldset>

	<p>
		
		<button id="agregar" type="submit" class="editar">Verificar</button>					
	</p>
</form>
</div>
<div id="resultadoFin"></div>
<script type="text/javascript">
var array_subTipos= <?php echo json_encode($resTipos); ?>;
var permisoEnfermedad= <?php echo json_encode($poseePermisoEnfermedad); ?>;
var permisoMaternidad= <?php echo json_encode($poseePermisoMaternidad); ?>;
var usuario = <?php echo json_encode($usuario); ?>;
var opcionPermisoHoras = 0; 
var opcionPermisoDias = 0;
var opcionTipoPermiso = 0; 


$(document).ready(function(){	

	distribuirLineas();
	construirValidador();
	limpiar();
	$("#subtipoSolicitud").hide();
	$("#actualizar").hide();
	$("#agregar").hide();

	if(usuario == '0'){
		$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#actualizar").attr("disabled", "disabled");
	}
	
	if($("#disponibilidad").val()==0){
		alert('Usted no posee tiempo disponible para solicitar permisos con cargo a vacaciones.');
		$("#estado").html('Usted no posee tiempo disponible para solicitar permisos con cargo a vacaciones.').addClass('alerta');
	}
});

function limpiar(){
	$("#resultado").html("");
	$("#seleccionDetalle").hide();
	$("#detalle").hide();
	$("#adjuntos").hide();
	$("#comisionLocal").hide();
	$("#comisionExterior").hide();
	$("#comisionProvincial").hide();
	$("#horaSalida").attr("placeholder","10:30");
	$("#horaRetorno").attr("placeholder","10:30");
	$("#horaSalida").val("08:00");
	$("#horaRetorno").val("16:30");
	$("#horaSalida").attr("readonly","readonly");
	$("#horaRetorno").attr("readonly","readonly");	
	$("input[type=radio]").prop('checked', false);

}


$("#tipoSolicitud").change(function(){
	limpiar();
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

function verificar(id)
{
	$("#resultado").html("");
	$("#actualizar").hide();
	$("#agregar").show();
if(id == 'busqueda1'){
	$("#opcionPermiso").val("hora");
	$("#horaSalida").removeAttr("readonly");
	$("#horaRetorno").removeAttr("readonly");
	$("#horaSalida").val("");
	$("#horaRetorno").val("");	
	$("#horaSalida").attr("placeholder","10:30");
	$("#horaRetorno").attr("placeholder","10:30");	
	$("#detalle").show();
	$("#fechaSuceso").val("");
	$("#fechaSalida").val("");
	$("#fechaRetorno").val("");

	opcionPermisoHoras = 1; 
	opcionPermisoDias = 0;
	}
if(id == 'busqueda2'){
	$("#opcionPermiso").val("dias");
	$("#detalle").show();
	$("#fechaSuceso").val("");
	$("#fechaSalida").val("");
	$("#fechaRetorno").val("");

	$("#horaSalida").val("08:00");
	$("#horaRetorno").val("16:30");
	$("#horaSalida").attr("readonly","readonly");
	$("#horaRetorno").attr("readonly","readonly");

	opcionPermisoHoras = 0; 
	opcionPermisoDias = 1;	
	}  	
if(id == 'busqueda3'){
	$("#opcionPermiso").val("horaDia");
	$("#detalle").show();
	$("#horaSalida").attr("placeholder","08:00");
	$("#horaRetorno").attr("placeholder","16:30");
	$("#fechaSuceso").val("");
	$("#fechaSalida").val("");
	$("#fechaRetorno").val("");

	$("#horaSalida").removeAttr("readonly");
	$("#horaRetorno").removeAttr("readonly");
	$("#horaSalida").val("");
	$("#horaRetorno").val("");		
	}  	
}

$("#subTipoSolicitud").change(function(){
	limpiar();
	$("#estado").html('');
	$("#agregar").show();
	$("#detalle").show();
	$("#fechaSuceso").val("");
	$("#fechaSalida").val("");
	$("#fechaRetorno").val("");
	$("#horaSalida").val("08:00");
	$("#horaRetorno").val("16:30");
   	$("#opcionTipoPermiso").val($("#subTipoSolicitud option:selected").attr('data-codigo'));
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF"  ){
	   	$("#seleccionDetalle").show();
		$("#detalle").hide();
	}

	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-ER" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-AM" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-MH" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-MH" ){
		$("#horaSalida").removeAttr("readonly");
		$("#horaRetorno").removeAttr("readonly");
		$("#horaSalida").val("");
		$("#horaRetorno").val("");
	}
	
	$("#fechaSuceso").removeAttr("disabled");
	$("#fechaSalida").removeAttr("disabled");
	
	$("#descripcionSolicitud").html(($("#subTipoSolicitud option:selected").attr("data-detalle")));
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-ER" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-AM" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF"){
	   $("#etiquetaFechaSuceso").hide();
       $("#fechaSuceso").hide();
       $("#separador").hide();

	}else{
		$("#etiquetaFechaSuceso").show();
	    $("#fechaSuceso").show();
	    $("#separador").show();
	}
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF"){
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
			alert('No puede crear una solicitud de licencia de cuidado de recién nacido dado que no posee una licencia por maternidad aprobada.');

			$("#detalle").hide();
			$("#adjuntos").hide();
			$("#actualizar").hide();
		}
	}

	//Permiso Nacimiento Múltiple
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="NA-PM"){
		if(permisoMaternidad == 0){  
			alert('No puede crear una solicitud de licencia por Parto Múltiple dado que no posee una licencia por maternidad aprobada.');

			$("#detalle").hide();
			$("#adjuntos").hide();
			$("#actualizar").hide();
		}
	}
	
	//Comisiones Locales
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#comisionLocal").show();
		$("#comisionProvincial").hide();
		$("#comisionProvincial").val('');
		$("#comisionExterior").hide();
		$("#comisionExterior").val('');
		$("#horaSalida").val("");
		$("#horaRetorno").val("");
		$("#adjuntos").hide();
	}

	//Comisiones Provinciales
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CP"){
		$("#comisionProvincial").show();
		$("#comisionLocal").hide();
		$("#comisionLocal").val('');
		$("#comisionExterior").hide();
		$("#comisionExterior").val('');
		$("#lugarComisionProvincial").attr("required","required");
	}
	//Comisiones exterior
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CE"){
		$("#comisionExterior").show();
		$("#comisionLocal").hide();
		$("#comisionLocal").val('');
		$("#comisionProvincial").hide();
		$("#comisionProvincial").val('');
		$("#adjuntos").hide();
	}
	
});

$("#fechaSuceso").datepicker({ 
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {

    	var fecha=new Date($('#fechaSuceso').datepicker('getDate')); 	 

	  	if($("#subTipoSolicitud option:selected").attr('data-codigo') != "MA-MA"){
	  		$('#fechaSalida').datepicker('option', 'minDate', $("#fechaSuceso" ).val());

	  		fecha.setDate(fecha.getDate());
		  	fecha.setMonth(fecha.getMonth());
			fecha.setUTCFullYear(fecha.getUTCFullYear());  
			
			$('#fechaSalida').datepicker('option', 'maxDate', fecha);
	  	}else{
		  	var fechaSalida=fecha.setDate(fecha.getDate()-3);
	  		$('#fechaSalida').datepicker('option', 'minDate', fechaSalida);
			$('#fechaSalida').datepicker('option', 'maxDate', fechaSalida+6);
	  	}
    }
  });

$("#fechaSalida").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    beforeShowDay:function(dt){
    	if($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIVF"){
    		return [dt.getDay() == 0 || dt.getDay() == 6, ""];
    	}else if ($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIV"){
    		return [dt.getDay() == 1 || dt.getDay() == 2 || dt.getDay() == 3 || dt.getDay() == 4 || dt.getDay() == 5, ""];
    	}else{
    		return [dt.getDay() == 0 || dt.getDay() == 1 || dt.getDay() == 2 || dt.getDay() == 3 || dt.getDay() == 4 || dt.getDay() == 5 || dt.getDay() == 6, ""];
    	} 
    },
    onSelect: function(dateText, inst) {

        //Ver el tipo de permiso q es laborables findes
    	$("#fechaRetorno").removeAttr("disabled");
	  	$('#fechaRetorno').datepicker('option', 'minDate', $("#fechaSalida" ).val()); 
	
	    var dias=parseInt($("#subTipoSolicitud option:selected").attr("data-minutos"))/480;

		var diasDisponibles=Math.floor(parseInt($("#disponibilidad").val())/480);

    	if(($("#subTipoSolicitud option:selected").attr('data-codigo') == "VA-VA") || ($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIV") || ($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF")){
	    	if((dias > diasDisponibles)){
	        	dias=diasDisponibles;
	        }	        
    	}
	  	var fecha=new Date($('#fechaSalida').datepicker('getDate'));
	
	  	if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-RN") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-ER") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF" )){
	  		if(	opcionPermisoHoras == 1) fecha.setDate(fecha.getDate()+1);
	  		else fecha.setDate(fecha.getDate()+dias);
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
    beforeShowDay:function(dt){
    	if($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIVF"){
    		return [dt.getDay() == 0 || dt.getDay() == 6, ""];
    	}else if ($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIV"){
    		return [dt.getDay() == 1 || dt.getDay() == 2 || dt.getDay() == 3 || dt.getDay() == 4 || dt.getDay() == 5, ""];
    	}else{
    		return [dt.getDay() == 0 || dt.getDay() == 1 || dt.getDay() == 2 || dt.getDay() == 3 || dt.getDay() == 4 || dt.getDay() == 5 || dt.getDay() == 6, ""];
    	}
    },
    onSelect: function(dateText, inst){

    	var fechaSalida=new Date($('#fechaSalida').datepicker('getDate'));
    	var fechaRetorno=new Date($('#fechaRetorno').datepicker('getDate'));
    	var fechaSuceso=new Date($('#fechaSuceso').datepicker('getDate'));

    	var diferencia =  (Math.floor(( Date.parse(fechaRetorno) - Date.parse(fechaSalida) ) / 86400000)) + 1;

    	if(diferencia < 0){
    		diferencia = diferencia*(-1);
    	}
    	
    	//Revisar número de días para permisos (<14) y vacaciones (15>)
			
    	if($("#subTipoSolicitud option:selected").attr('data-codigo') == "VA-VA"){
	    	if(diferencia < 7){
	        	alert("Por favor seleccione un Permiso con Cargo a Vacaciones para solicitudes menores a 7 días.");
	        	cargarValorDefecto("tipoSolicitud","");
	        	$("#subTipoSolicitud").html("");
	        }
    	}else if($("#subTipoSolicitud option:selected").attr('data-codigo') == "PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF"){
	    	if(diferencia >= 7){
	        	alert("Por favor seleccione una licencia de Vacaciones para solicitudes a partir de 7 días.");
	        	cargarValorDefecto("tipoSolicitud","");
	        	$("#subTipoSolicitud").html("");
	        }
    	}else if($("#subTipoSolicitud option:selected").attr('data-codigo') == "MA-MA"){
    		diferencia =  (Math.floor(( Date.parse(fechaSuceso) - Date.parse(fechaRetorno) ) / 86400000)) + 1;

			if((Math.floor(( Date.parse(fechaSuceso) == Date.parse(fechaRetorno+2) ) / 86400000))){
				alert('fechaSuceso'+fechaSuceso+'-'+'fechaRetorno'+fechaRetorno);
				alert('mayor');
			}else if((Math.floor(( Date.parse(fechaSuceso-2) == Date.parse(fechaSalida) ) / 86400000))){ 
				alert('fechaSuceso'+fechaSuceso+'-'+'fechaSalida'+fechaSalida);
				alert('mayor');
    		}else{
				alert('fechaSuceso'+fechaSuceso+'-'+'fechaRetorno'+fechaRetorno);
				alert('menor');
			}


    		if(diferencia < 0){
        		diferencia = diferencia*(-1);
        	}

	    	if(diferencia == 2 || diferencia == 0){
	        	alert("Por favor seleccione una fecha que incluya como salida o finalización del permiso la fecha del suceso.");
	        	$("#fechaRetorno").val('');
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
			if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-RN") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "EN-RE") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-ER")){

					if(parseInt(hora)==24){
						$("#horaRetorno").val('02'+':'+minuto);
						$("#horaRetorno").attr('readonly', 'readonly');
					}else if((parseInt(hora)+2) > 24){
						var horaRetorno = 24- parseInt(hora);
						
						$("#horaRetorno").val('0'+horaRetorno +':'+minuto);
						$("#horaRetorno").attr('readonly', 'readonly'); 
					}else{
						var horaR = parseInt(hora)+2;
						if(horaR < 10)horaR='0'+horaR;
						var horaRetor=horaR+':'+minuto;
						$("#horaRetorno").val(horaRetor);
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
        subirArchivo(archivo, $("#identificador").val() +"_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("0");
    }        
});

function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
}

 $("#nuevaSolicitud").submit(function(event){
	 event.preventDefault();
	 $("#llugarComisionLocal").text('');	
	 $("#llugarComisionExterior").text('');
	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		//Comisiones Locales
		if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
					if(!esCampoValido("#lugarComisionLocal")){
						error = true;
						$("#lugarComisionLocal").addClass("alertaCombo");
						$("#llugarComisionLocal").text('No utilizar caracteres especiales...').addClass("alerta");
						}
					if($.trim($("#lugarComisionLocal").val())=="" ){
						error = true;
						$("#lugarComisionLocal").addClass("alertaCombo");
						 $("#llugarComisionLocal").text('');	
					}
					if($("#lugarComisionLocal").val().length > 127){
						error = true;
						$("#lugarComisionLocal").addClass("alertaCombo");
						$("#llugarComisionLocal").text('Cantidad de caracteres es mayor a la permitida ...').addClass("alerta");
					}
					
		}
		//Comisiones exterior
		if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CE"){
			if(!esCampoValido("#lugarComisionExterior")){
				error = true;
				$("#lugarComisionExterior").addClass("alertaCombo");
				$("#llugarComisionExterior").text('No utilizar caracteres especiales...').addClass("alerta");
				}
			if($.trim($("#lugarComisionExterior").val())=="" ){
				error = true;
				$("#lugarComisionExterior").addClass("alertaCombo");
				$("#llugarComisionExterior").text('');
			}
			if($("#lugarComisionExterior").val().length > 127){
				error = true;
				$("#lugarComisionExterior").addClass("alertaCombo");
				$("#llugarComisionExterior").text('Cantidad de caracteres es mayor a la permitida ...').addClass("alerta");
			}
			
		}	

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
			if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA"){
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
					if(($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-RN") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-MH") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "EN-RE") || ($("#subTipoSolicitud option:selected").attr('data-codigo')== "PE-ER")){
							if(parseInt(hora)==24){
								$("#horaRetorno").val('02'+':'+minuto);
								$("#horaRetorno").attr('readonly', 'readonly');
							}else if((parseInt(hora)+2) > 24){
								var horaRetorno = 24- parseInt(hora);
								
								$("#horaRetorno").val('0'+horaRetorno +':'+minuto);
								$("#horaRetorno").attr('readonly', 'readonly'); 
							}else{

								var horaR = parseInt(hora)+2;
								if(horaR < 10)horaR='0'+horaR;
								$("#horaRetorno").val(horaR+':'+minuto);
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
			//$("#agregar").hide();
			$("#estado").html("");
			 abrir($(this), event, false);	
			//ejecutarJson(this);			
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}	
 });
</script>
