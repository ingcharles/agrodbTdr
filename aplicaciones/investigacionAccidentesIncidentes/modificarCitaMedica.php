<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();

$identificador=$_SESSION['usuario_seleccionado'];
$solicitud=$_POST['id'];


$valores_accidentes=pg_fetch_array($cai->listarDatosAccidente($conexion,'', '','','','',$solicitud,''));
$solicitud=$valores_accidentes['cod_datos_accidente'];
$datosCitaMedica=pg_fetch_array($cai->buscarCitaMedica($conexion,$solicitud));

?>
<header>
	<h1>
		Solicitud #
		<?php echo $solicitud;?>
	</h1>
</header>

<form id="guardarCitaMedica"
	data-rutaAplicacion="investigacionAccidentesIncidentes"
	data-opcion="actualizarCitaMedica" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="solicitud" name="solicitud"
		value="<?php echo $solicitud;?>" />
	<input type="hidden" id="identificadorAccidentado" name="identificadorAccidentado"
		value="<?php echo $valores_accidentes['identificador_accidentado'];?>" />
	<div id="estado"></div>

	<fieldset>
		<legend>Información de Cita Médica Programada</legend>
		<div data-linea="1">
			<label>* Fecha de Atención:</label> <input type="text" id="fechaCita"
				name="fechaCita" value="<?php echo $datosCitaMedica['fecha_cita'];?>" />
		</div>
		<div data-linea="2">
			<label>* Hora:</label> <input id="horaCita" name="horaCita"
				class="menores" value="<?php echo $datosCitaMedica['hora_cita'];?>" type="text" placeholder="10:30"
				data-inputmask="'mask': '99:99'" />
		</div>
		<div data-linea="3">
			<label>* Nombre del Médico que Atiende:</label> <input type="text" maxlength="64"
				id="nombreMedico" name="nombreMedico" value="<?php echo $datosCitaMedica['nombre_medico'];?>" />
		</div>
		<div data-linea="4">
			<label>* Dirección de Atención Medica:</label> <input type="text" maxlength="64"
				id="direccionMedico" name="direccionMedico" value="<?php echo $datosCitaMedica['direccion_medico'];?>" />
		</div>
		<div data-linea="5">
			<label>* Reporte de Ingreso de Aviso de Accidentes</label>
		</div>
				<?php 
			    echo $datosCitaMedica['archivo_aviso_accidente']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCitaMedica['archivo_aviso_accidente'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
		
		<div data-linea="6">
			<input type="file" class="archivo" name="archivo" id="archivo"
				accept="application/pdf" /> <input type="hidden" class="rutaArchivo"
				name="reporte" id="reporte" value="<?php echo $datosCitaMedica['archivo_aviso_accidente'];?>" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo
				<?php echo ini_get('upload_max_filesize'); ?>
				B)
			</div>
			<button type="button" class="subirArchivo adjunto"
				data-rutaCarga="aplicaciones/investigacionAccidentesIncidentes/archivos">Subir
				archivo</button>
		</div>
	</fieldset>
	<button id="guardarForm" type="submit" class="guardar">Guardar</button>

</form>

<script type="text/javascript">

var fechaIngreso= <?php echo json_encode(date('Y-m-d_H:i:s')); ?>;
var cedula= <?php echo json_encode($valores_accidentes['identificador_accidentado']); ?>;


	$("#guardarCitaMedica").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		error = verificarHoraIngresada();
		if($("#horaCita").val()==""){
			error = true;
			$("#horaCita").addClass("alertaCombo");
		}
		if($("#fechaCita").val()==""){
			error = true;
			$("#fechaCita").addClass("alertaCombo");
		}
		if($("#nombreMedico").val()==""){
			error = true;
			$("#nombreMedico").addClass("alertaCombo");
		}
		if($("#direccionMedico").val()==""){
			error = true;
			$("#direccionMedico").addClass("alertaCombo");
		}
		if($("#nombreMedico").val()==""){
			error = true;
			$("#nombreMedico").addClass("alertaCombo");
		}
		if($("#reporte").val()==""){
			error = true;
			$("#archivo").addClass("alertaCombo");
		}
		
		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}
});

	$('button.subirArchivo').click(function (event) {	
		
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

        		subirArchivo(
    	                archivo
    	                , $("#solicitud").val()+'_reporteAvisoAccidentes'+'_'+cedula
    	                , boton.attr("data-rutaCarga")
    	                , rutaArchivo
    	                , new carga(estado, archivo, boton)
    	            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });
  
	$(document).ready(function(){
					    
		$( "#fechaCita" ).datepicker({
			changeMonth: true,
		      changeYear: true
		    });
	    
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));
	});


	$("#horaCita").change(function(){
		verificarHoraIngresada();
		});

	function verificarHoraIngresada(){
		var error = false;
		$("#horaCita").removeClass('alertaCombo');
			
			var horaNueva = $("#horaCita").val().replace(/\_/g, "0");
			$("#horaCita").val(horaNueva);
			
			var hora = $("#horaCita").val().substring(0,2);
			var minuto = $("#horaCita").val().substring(3,5);
			
			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaCita").val('24:00');
					}
				}else{
					error = true;
					$("#horaCita").addClass('alertaCombo');
					$("#estado").html("Los minutos ingresados están incorrecto, por favor actualice la información").addClass('alerta');
				}
			}else{
				error = true;
				$("#horaCita").addClass('alertaCombo');
				$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
			}
		return error;

		}
	
</script>
