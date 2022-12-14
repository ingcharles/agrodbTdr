<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php 
	echo $this->datosGenerales; 
	echo $this->periodoCronograma;
	echo $this->bloqueAprobacionReprogramacion;
?>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='RevisionCronogramaVacaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" name="id_cronograma_vacacion" id="id_cronograma_vacacion" value="<?php echo $_POST['id']; ?>" readonly="readonly" />
	<?php echo $this->resultadoRevision; ?>
	<input type="hidden" name="es_reprogramacion" id="es_reprogramacion" value="<?php echo  $this->esReprogramacion; ?>" readonly="readonly" />
	<div data-linea="7">
		<button type="submit" class="guardar">Guardar</button>
	</div>
	
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		//event.preventDefault();
		mostrarMensaje("", "");
		construirValidador();
		distribuirLineas();
	 });
    let esReprogamacion="<?php echo  $this->esReprogramacion; ?>";
	//Si es reprogramacion se MUES la columna reprogramado
if(esReprogamacion==0){
	$('td:nth-child(5)').toggle();
	$('th:nth-child(5)').toggle();
}
	$("#formulario").submit(function (event) {
		event.preventDefault();
		mostrarMensaje("", "");
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		$('#formulario .validacion').each(function(i, obj) {
 			if(!$.trim($(this).val())){
 				error = true;
 				$(this).addClass("alertaCombo");
 			}
 		});

		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				JSON.parse(ejecutarJson($("#formulario")).responseText);
			}, 1000);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
