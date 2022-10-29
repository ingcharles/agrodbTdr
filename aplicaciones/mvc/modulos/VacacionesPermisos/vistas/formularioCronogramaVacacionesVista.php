<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php echo $this->datosGenerales; ?>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='cronogramavacaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Datos generales</legend>

		<div data-linea="5">
			<label for="identificador_backup">Funcionario reemplazo: </label>
			<select name="identificador_backup" id="identificador_backup" class="validacion">
				<?php echo $this->datosFuncionarioBackup; ?>
			</select>
		</div>				

		<div data-linea="6">
			<label for="numero_periodos_planificar">Número de periodos a planificar: </label>
			<select name="numero_periodos_planificar" id="numero_periodos_planificar">
				<option value="">Seleccionar...</option>
				<option value="1">Un periodo</option>
				<option value="2">Dos periodos</option>
			</select>
		</div>	
		

		<!-- div data-linea="7">
			<label for="observacion">observacion </label>
			<input type="text" id="observacion" name="observacion" value="<?php echo $this->modeloCronogramaVacaciones->getObservacion(); ?>"
			placeholder="Observaciones de la aprobación o rechazo de la planificacion de vacaciones" maxlength="512" />
		</div>				

		<div data-linea="16">
			<label for="identificador_revisor">identificador_revisor </label>
			<input type="text" id="identificador_revisor" name="identificador_revisor" value="<?php echo $this->modeloCronogramaVacaciones->getIdentificadorRevisor(); ?>"
			placeholder="Cedula de funcionario que tiene altualmente el tramite" maxlength="13" />
		</div>				

		<div data-linea="9">
			<label for="id_area_revisor">id_area_revisor </label>
			<input type="text" id="id_area_revisor" name="id_area_revisor" value="<?php echo $this->modeloCronogramaVacaciones->getIdAreaRevisor(); ?>"
			placeholder="Area de funcionario que tiene altualmente el tramite" maxlength="13" />
		</div>				

		<div data-linea="10">
			<label for="usuario_creacion">usuario_creacion </label>
			<input type="text" id="usuario_creacion" name="usuario_creacion" value="<?php echo $this->modeloCronogramaVacaciones->getUsuarioCreacion(); ?>"
			placeholder="Cedula de funcionario que registra la planificacion las vacaciones" maxlength="13" />
		</div>				

		<div data-linea="11">
			<label for="usuario_modificacion">usuario_modificacion </label>
			<input type="text" id="usuario_modificacion" name="usuario_modificacion" value="<?php echo $this->modeloCronogramaVacaciones->getUsuarioModificacion(); ?>"
			placeholder="Cedula de funcionario que actualiza la planificacion las vacaciones" maxlength="16" />
		</div>				

		<div data-linea="12">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCronogramaVacaciones->getFechaCreacion(); ?>"
			placeholder="Fecha de registro en el sistema" maxlength="16" />
		</div>				

		<div data-linea="13">
			<label for="fecha_modificacion">fecha_modificacion </label>
			<input type="text" id="fecha_modificacion" name="fecha_modificacion" value="<?php echo $this->modeloCronogramaVacaciones->getFechaModificacion(); ?>"
			placeholder="Fecha de modificación en el sistema" maxlength="16" />
		</div>				

		<div data-linea="14">
			<label for="estado_cronograma_vacacion">estado_cronograma_vacacion </label>
			<input type="text" id="estado_cronograma_vacacion" name="estado_cronograma_vacacion" value="<?php echo $this->modeloCronogramaVacaciones->getEstadoCronogramaVacacion(); ?>"
			placeholder="Estado de la revisión del registro de planificacion de vacaciones" maxlength="16" />
		</div>				

		<div data-linea="15">
			<label for="estado_solicitud">estado_solicitud </label>
			<input type="text" id="estado_solicitud" name="estado_solicitud" value="<?php echo $this->modeloCronogramaVacaciones->getEstadoSolicitud(); ?>"
			placeholder="Estado del registro Activo/Inactivo" maxlength="16" />
		</div>				

		<div data-linea="16">
			<label for="anio_cronograma_vacacion">anio_cronograma_vacacion </label>
			<input type="text" id="anio_cronograma_vacacion" name="anio_cronograma_vacacion" value="<?php echo $this->modeloCronogramaVacaciones->getAnioCronogramaVacacion(); ?>"
			placeholder="" maxlength="16" />
		</div -->
		
		
	</fieldset>

	<div id="dDatosPeriodo"></div>
		
		<!-- div data-linea="11">
			<label for="total_dias_planificados">total_dias_planificados </label>
			<input type="text" id="total_dias_planificados" name="total_dias_planificados" value="<?php echo $this->modeloCronogramaVacaciones->getTotalDiasPlanificados(); ?>"
			placeholder="Número total de dias planificados de vacaciones" maxlength="16" />
		</div -->

	
	<div id="datosPlanificarPeriodos"> </div>

	<div data-linea="17">
			<button type="submit" class="guardar">Guardar</button>
	</div>

</form >

<!-- <table>
  <tbody>
    <tr>
      <td>
         <input class="form-control input-sm" type="text" onkeyup="calculo(this);"name="_cantidad80[]">
      </td>
      <td>
        <input class="form-control input-sm" type="text" onkeyup="calculo(this);"name="_cantidad100[]">
      </td>
    </tr>
    <tr>
      <td>
        <input class="form-control input-sm" type="text" onkeyup="calculo(this);"name="_cantidad80[]">
      </td>
      <td>
        <input class="form-control input-sm" type="text" onkeyup="calculo(this);"name="_cantidad100[]">
      </td>
    </tr>
    <tr>
      <td>
        <input class="form-control input-sm" type="text" onkeyup="calculo(this);"name="_cantidad80[]">
        </td>
      <td>
        <input class="form-control input-sm" type="text" onkeyup="calculo(this);"name="_cantidad100[]">
      </td>
    </tr>
    
    <h2>
      La suma de la columna es: <span id="rpta"></span>
    <h2>
  </tbody>
</table> -->


<script type ="text/javascript">

	var identificadorFuncionario = "<?php echo $this->identificador; ?>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	});

	function calculo(e)
  {
    var acumulador = 0;
    var nombre_input = e.name;
    var hermanos = 'input[name="' + nombre_input + '"]';
    var input_hermanos = $('table').find(hermanos);
    $.each(input_hermanos, function(idx, x)
    {
      var num = parseInt($(x).val());
      if (!isNaN(num) && num != undefined) //Validamos si está vacío o no es un número para acumular
        acumulador += num;
    });
	console.log(acumulador);
	$('#hFechaFin').val(acumulador);
    $('#rpta').html(acumulador);
  }


	
    /*$("#identificador_backup").change(function (event) {
        mostrarMensaje("", "EXITO");
        //if ($("#identificador_backup").val() != '') {
            $.post("<?php echo URL ?>VacacionesPermisos/CronogramaVacaciones/obtenerDatosFuncionarioBackup",
                {
                    identificador_funcionario: identificadorFuncionario
                }, function (data) {
                    if (data.estado === 'EXITO') {
                        $("#identificador_backup").html(data.comboSubtipoProducto);
                    }
                }, 'json');
        //} else {
        //    mostrarMensaje("Por favor seleccione un valor", "FALLO");
        //}
    });*/

	$("#numero_periodos_planificar").change(function (event) {		
		mostrarMensaje("", "EXITO");
		if($("#numero_periodos_planificar").val() != ""){
			var numeroPeriodosPlanificar = $("#numero_periodos_planificar").val();

			$.post("<?php echo URL ?>VacacionesPermisos/CronogramaVacaciones/construirPlanificarPeriodos",
                {
                    numero_periodos_planificar: numeroPeriodosPlanificar
                }, function (data) {
                    if (data.estado === 'EXITO') {
                        $("#dDatosPeriodo").html(data.datosPlanificarPeriodos);
                    }
                }, 'json');
		}
	});


	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
