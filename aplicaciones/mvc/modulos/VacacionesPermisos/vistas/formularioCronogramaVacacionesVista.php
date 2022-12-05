<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<style>
	td>input {
		width: 100%;
	}
</style>


<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='cronogramavacaciones/guardarPlanificacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	
    <?php echo $this->datosGenerales; ?>
	<?php echo $this->datosPeriodoCronograma; ?>
</form>


<script type="text/javascript">
	var identificadorFuncionario = "<?php echo $this->identificador; ?>";
	var anioPlanificacion = "<?php echo $this->anioPlanificacion; ?>";
	
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	});


	function calculo(campo, expresion) {
		var elementoFechaInicio = $(campo).parents("tr").find(".piFechaInicio");
		var elementoFechaFin = $(campo).parents("tr").find(".piFechaFin");
		var elementoNumeroDias = $(campo).parents("tr").find(".piNumeroDias");
		sumarDias(campo, elementoNumeroDias, elementoFechaInicio, elementoFechaFin);
		validarNumeros(elementoNumeroDias, expresion);
	}

	function sumarDias(acumulador, dias, campoFechaInicio, campoFechaFin) {
		var acumuladorDias = 0;
		var input_hermanos = $('table').find(".piNumeroDias");
		$.each(input_hermanos, function(idx, x) {
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

	$("#numero_periodos").change(function(event) {
		mostrarMensaje("", "EXITO");
		if ($("#numero_periodos").val() != "") {
			var numeroPeriodosPlanificar = $("#numero_periodos").val();

			$.post("<?php echo URL ?>VacacionesPermisos/CronogramaVacaciones/construirPlanificarPeriodos", {
				numero_periodos_planificar: numeroPeriodosPlanificar
			}, function(data) {
				if (data.estado === 'EXITO') {
					$("#dDatosPeriodo").html(data.datosPlanificarPeriodos);



					$(".piFechaFin").datepicker({
						yearRange: "c:c",
						changeMonth: false,
						changeYear: false,
						dateFormat: 'yy/mm/dd',
						maxDate: 0,
						minDate: new Date(anioPlanificacion + '/01/01'),
					});

					$(".piFechaInicio").datepicker({
						yearRange: "+0:+0",
						changeMonth: true,
						changeYear: false,
						dateFormat: 'yy/mm/dd',
						minDate: new Date(anioPlanificacion + '/01/01'),
						
						onSelect: function(dateText, inst) {
							var elementoFechaInicio = $(this).parents("tr").find(".piFechaInicio");
							var elementoFechaFin = $(this).parents("tr").find(".piFechaFin");
							var elementoNumeroDias = $(this).parents("tr").find(".piNumeroDias");
							elementoFechaInicio.removeClass("alerta");
							sumarDias(this, elementoNumeroDias, elementoFechaInicio, elementoFechaFin);
							var date = new Date(elementoFechaFin.datepicker('getDate'));
    					    var anioFin = date.getFullYear();

							if(parseInt(anioPlanificacion) != parseInt(anioFin)){
								elementoFechaFin.val('');
								elementoFechaFin.addClass("alerta"); 
								$("#estado").html("La fecha de inicio + el Número de días, no puede superar el año "+ anioPlanificacion).addClass("alerta");
							}else{
								elementoFechaFin.removeClass("alerta");
								$("#estado").html(""); 		
							}
						}
					});

					var valorComboPeriodo = $("#numero_periodos option:selected").val();

					var valorMaximo = 0;
					switch (valorComboPeriodo) {
						case "2":
							valorMaximo = 15;
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
		} else {
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
		$(campo).bind('input', function() {
			var node = $(campo);
			if (!patron.test(node.val())) {
				var valorNumerico = node.val();
				node.val(valorNumerico.substring(0, valorNumerico.length - 1));
			}
		});
	}

	$("#formulario").submit(function(event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			if ($('#total_dias_planificados').val() > 30) {
				$("#estado").html("El total de días planificados no debe ser mayor a 30.").addClass("alerta");
			} else {
				var input_hermanos = $('table#tPeriodosPlanificar').find(".piNumeroDias");
				var filas = $('table#tPeriodosPlanificar').find("tr");
				var banderaTablaVacia = true;
				var banderaTablaFechas = true;
				var banderaSuperaAnioPlanificacion = true;
				var valorComboPeriodo = $("#numero_periodos option:selected").val();
				var valorMaximo = 0;
				var valorMaximoMensaje = "El número de días de un periodo no puede ser mayor a ";
				var valorSuperado = false;
				var arrayFechas = [];
				switch (valorComboPeriodo) {
					case "2":
						valorMaximo = 15;
						break;
					case "3":
						valorMaximo = 10;
						break;
					case "4":
						valorMaximo = 9;
					break;
					default:
						valorMaximo = 30;
						break;
				}
				valorMaximoMensaje = valorMaximoMensaje + valorMaximo + ".";

				$(filas).each(function(index) {
					var fechaInicio;
					var fechaFin;
					$(this).find('td').each(function(indexx) {
						if ($(this).find('input').val() == "") {
							banderaTablaVacia = false;
							return false;
						}
						console.log(indexx + "------------");
						if ($(this).find('.piFechaInicio').val()) {
							fechaInicio = $(this).find('.piFechaInicio').val();
						}
						if ($(this).find('.piFechaFin').val()) {
							fechaFin = $(this).find('.piFechaFin').val();

							var date = new Date(fechaFin);
							var anioFin = date.getFullYear();

							if(parseInt(anioPlanificacion) != parseInt(anioFin)){
								console.log("esta mal fecha anio siguiente");
								banderaSuperaAnioPlanificacion = false;
								return false;
							}

						}
					});
					if (fechaInicio !== undefined && fechaFin !== undefined)
						arrayFechas.push({
							fecha_inicio: fechaInicio,
							fecha_fin: fechaFin
						})

				});

				for (let index = 0; index < arrayFechas.length - 1; index++) {
					const element = arrayFechas[index];
					const elementNext = arrayFechas[index + 1];
					var fechaInicio1 = new Date(elementNext.fecha_inicio);
					var fechaFin1 = new Date(element.fecha_fin);
					if (fechaInicio1 <= fechaFin1) {
						banderaTablaFechas = false;
						console.log("esta mal");
						$('.piFechaInicio').eq(index + 1).addClass("alerta"); //.css({color:'red'});
						//break;
					}
					

				}

				$.each(input_hermanos, function(idx, x) {
					var num = parseInt($(x).val());

					if (!isNaN(num) && num != undefined) //Validamos si está vacío o no es un número para acumular
						if (num > valorMaximo) {
							valorSuperado = true;
							return false;
						}
				});

				if (valorSuperado) {
					$("#estado").html(valorMaximoMensaje).addClass("alerta");
				} else {
					if (!banderaTablaVacia) {
						$("#estado").html("Complete los campos de los periodos seleccionados.").addClass("alerta");
					} else {
						if (!banderaTablaFechas) {
							$("#estado").html("Revise los rangos de fechas ingresados.").addClass("alerta");
						} else {
							if (!banderaSuperaAnioPlanificacion) {
								$("#estado").html("La fecha de inicio + el Número de días, no puede superar el año " + anioPlanificacion).addClass("alerta");
							} else {
								//console.log(ejecutarJson($("#formulario")).responseText);
								JSON.parse(ejecutarJson($("#formulario")).responseText);
							}
						}
					}
				}
			}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>