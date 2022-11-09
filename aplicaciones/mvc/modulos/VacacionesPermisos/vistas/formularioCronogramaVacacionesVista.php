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
				<option value="3">Tres periodos</option>
				<option value="4">Cuatro periodos</option>
			</select>
		</div>	
		

		
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


<script type ="text/javascript">

	var identificadorFuncionario = "<?php echo $this->identificador; ?>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	});
	

	function calculo(campo,expresion)
  	{
		var elementoFechaInicio = $(campo).parents("tr").find(".piFechaInicio");
		var elementoFechaFin = $(campo).parents("tr").find(".piFechaFin");
		var elementoNumeroDias = $(campo).parents("tr").find(".piNumeroDias");
		sumarDias(campo, elementoNumeroDias, elementoFechaInicio, elementoFechaFin);
		validarNumeros(elementoNumeroDias,expresion);
  	}

	function sumarDias(acumulador, dias, campoFechaInicio, campoFechaFin  ){
		var acumuladorDias = 0;
		var input_hermanos = $('table').find(".piNumeroDias");
		$.each(input_hermanos, function(idx, x)
		{
		var num = parseInt($(x).val());

		if (!isNaN(num) && num != undefined) //Validamos si está vacío o no es un número para acumular
		acumuladorDias += num;
		});

		var date = new Date(campoFechaInicio.datepicker('getDate'));

		var num1 = parseInt(dias.val());
		if (!isNaN(num1) && num1 != undefined) //Validamos si está vacío o no es un número para acumular
		numeroDias = num1;

		if (date) {
			date.setDate(date.getDate() + numeroDias);
		}

		campoFechaFin.datepicker("setDate", date);   
		campoFechaFin.datepicker('option', 'minDate', date); 
		campoFechaFin.datepicker('option', 'maxDate', date);   
		$('#total_dias').html(acumuladorDias);
		$('#total_dias_planificados').val(acumuladorDias);
		
  }


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


					
					$(".piFechaFin").datepicker({
						changeMonth: false,
						changeYear: false,
						dateFormat: 'yy/mm/dd',
					});

					$(".piFechaInicio").datepicker({
						yearRange: "+0:+1", 
						changeMonth: true,
						changeYear: true,
						dateFormat: 'yy/mm/dd',
						minDate: '0',
						onSelect: function(dateText, inst) {
							var elementoFechaInicio = $(this).parents("tr").find(".piFechaInicio");
							var elementoFechaFin = $(this).parents("tr").find(".piFechaFin");	
							var elementoNumeroDias = $(this).parents("tr").find(".piNumeroDias");
							sumarDias(this, elementoNumeroDias, elementoFechaInicio, elementoFechaFin);
						}
					});

					var valorComboPeriodo = $("#numero_periodos_planificar option:selected").val();
					
					var valorMaximo = 0;
					var expresion = "";
					switch (valorComboPeriodo) {
						case "2":
							valorMaximo = 15;
							expresion = '^(1[5]{0,1})$';
						break;
						case "3":
							valorMaximo = 10;
						break;
						case "4":
							valorMaximo = 7;
							maxLength = 1;
						break;
						default:
							valorMaximo = 30;
						break;
					}
					$(".piNumeroDias").val(valorMaximo);
					$(".piNumeroDias").numeric();
					//$(".piNumeroDias").attr("maxlength", 2);
					
					var totalDias = parseInt(valorComboPeriodo) * valorMaximo;
					$('#total_dias').html(totalDias);
					$('#total_dias_planificados').val(totalDias);
                }
                }, 'json');
		}else{
			$("#dDatosPeriodo").html("");
		}
	});
	
	function validarNumeros(campo, expresion) {
    //let patron = new RegExp('^([1-9]|[1]?[1-9]?|[2][0-4]|10)$');
	//let patron = new RegExp('^(1[0-2]|[1-9])$');
	//let patron = new RegExp('^(3[0]|[1-9])$'); // 1-30
	//let patron = new RegExp('^(3[0]{0,1})$'); // 0-30
	//let patron = new RegExp('^(1[5]{0,1})$'); // 1-15
	//let patron = new RegExp('^(1[0]{0,1})$'); // 1-10
	//let patron = new RegExp('^([7-9])$'); // 7-9

	let patron = new RegExp(expresion);
	$(campo).bind('input', function () {
        var node = $(campo);
         if (!patron.test(node.val())) {
              var valorNumerico = node.val();
             node.val(valorNumerico.substring(0, valorNumerico.length - 1));
          }
    });
}

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			if($('#total_dias_planificados').val() > 30){
				$("#estado").html("El total de días planificados no debe ser mayor a 30.").addClass("alerta");
			}else{
				var input_hermanos = $('table').find(".piNumeroDias");
				var valorComboPeriodo = $("#numero_periodos_planificar option:selected").val();
				var valorMaximo = 0;
				var valorMaximoMensaje = "El número de días de un periodo no puede ser mayor a ";
				var valorSuperado = false;
				switch (valorComboPeriodo) {
					case "2":
						valorMaximo = 15;
					break;
					case "3":
						valorMaximo = 10;
					break;
					case "4":
						valorMaximo = 7;
					break;
					default:
						valorMaximo = 30;
					break;
				}
				valorMaximoMensaje = valorMaximoMensaje + valorMaximo + ".";
			

				$.each(input_hermanos, function(idx, x)
				{
					var num = parseInt($(x).val());

					if (!isNaN(num) && num != undefined) //Validamos si está vacío o no es un número para acumular
						if (num > valorMaximo){
							valorSuperado = true;
							exit;
						}
				});

				if(valorSuperado){
					$("#estado").html(valorMaximoMensaje).addClass("alerta");
				}else{
					abrir($(this), event, false);
					abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
				}
			}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
