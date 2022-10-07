<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$va = new ControladorVacunacion();

$fecha = date('Y/m/d');
$identificadorUsuario = $_SESSION['usuario'];
$filaTipoUsuario = pg_fetch_assoc($va->obtenerTipoUsuario($conexion, $identificadorUsuario));

$banderaSolicitante = false;
$identificadorSolicitante = "";
$identificadorOperadora = "";

switch ($filaTipoUsuario['codificacion_perfil']){
    
    case 'PFL_USUAR_INT':
        
        $qResultadoUsuarioTecnico = $va->verificarTecnicoAgrocalidad($conexion, $identificadorUsuario);
        
        if(pg_num_rows($qResultadoUsuarioTecnico) > 0){
            //echo "<br/> Es técnico con ficha  -> vacuna libre<br/>";
        }else{
            //echo "<br/> Es técnico sin ficha-> bloqueo vacunación<br/>";
            //$banderaValidarAcciones = true;
        }
        
    break;
        
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $va->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitador', 'digitadorVacunacion')", "('OPT','OPI')");
        $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
        
        $codigoTipoOperacion = $operacionesEmpresaUsuario['codigo_tipo_operacion'];
        $identificadorEmpresa = $operacionesEmpresaUsuario['identificador_empresa'];
        $idEmpresa = $operacionesEmpresaUsuario['id_empresa'];
        
        if(stristr($codigoTipoOperacion, 'OPT') == true){
            //echo "<br/> Es empleado traspatio-> vacuna libre<br/>";
			$identificadorOperadora = $identificadorEmpresa;
        }else if(stristr($codigoTipoOperacion, 'OPI') == true){
            //echo "<br/> Es empleado industrial-> vacuna de la empresa<br/>";
            $banderaSolicitante = true;
            $identificadorSolicitante = $identificadorEmpresa;
			$identificadorOperadora = $identificadorEmpresa;
        }else{
            $qOperacionesUsuario = $va->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "('OPT', 'OPI')");
            $operacionesUsuario = pg_fetch_assoc($qOperacionesUsuario);
            
            $codigoTipoOperacionUsuario = $operacionesUsuario['codigo_tipo_operacion'];
            
            if(stristr($codigoTipoOperacionUsuario, 'OPT') == true){
                //echo "<br/> Es empresa traspatio-> vacuna libre<br/>";
				$identificadorOperadora = $identificadorUsuario;
            }else if(stristr($codigoTipoOperacionUsuario, 'OPI') == true){
                //echo "<br/> Es empleado industrial-> vacuna de la empresa<br/>";
                $banderaSolicitante = true;
                $identificadorSolicitante = $identificadorUsuario;
				$identificadorOperadora = $identificadorUsuario;
            }
        }
        
    break;
        
}


$lotes = $cc->listaLotes($conexion);
$laboratorios = $cc->listaLaboratoriosVacuna($conexion);
$qTipoVacuna = $cc->listaTipoVacuna($conexion);

while ($filaVacuna = pg_fetch_assoc($qTipoVacuna)) {
	$tipoVacuna[] = array('codigo' => $filaVacuna['codigo'], 'costo' => $filaVacuna['costo'], 'id_tipo_vacuna' => $filaVacuna['id_tipo_vacuna'], 'nombre_vacuna' => $filaVacuna['nombre_vacuna'], 'id_especie' => $filaVacuna['id_especie']);
}

$qUnidadComercial = $cc->obtenerIdUnidadMedida($conexion, 'U');
$unidadComercial = pg_fetch_assoc($qUnidadComercial);

$qEspecie = $cc->obtenerEspecieXcodigo($conexion, 'PORCI');
$especie = pg_fetch_assoc($qEspecie);

?>

<header>
	<h1>Nuevo Registro de Vacunación</h1>
</header>

<form id='nuevoVacunacion' data-rutaAplicacion='vacunacion' data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $identificadorUsuario; ?>" />
	
	<div id="estado"></div>
	<input type="hidden" id="opcion" name="opcion" value="0" />
	<input type="hidden" id="numeroCertificado" name="numeroCertificado" value="0" />
	<input type="hidden" id="gArea" name="gArea" value="">
	<input type="hidden" id="gProducto" name="gProducto" value="">
	<input type="hidden" id="gOperacion" name="gOperacion" value="">
	<input type="hidden" id="gNumeroLote" name="gNumeroLote" value="">
	<input type="hidden" id="operadorVacunacion" name="operadorVacunacion" value="<?php echo $identificadorOperadora; ?>" />
	<input type="hidden" id="costoVacuna" name="costoVacuna" value="0" />
	<input type="hidden" id="identificadorOperador" name="identificadorOperador" value="0" />
	<input type="hidden" id="unidadMedida" name="unidadMedida" value="<?php echo $unidadComercial['id_unidad_medida'] ?>" />
	<input type="hidden" id="gNombreProducto" name="gNombreProducto" value="" />
	<input type="hidden" id="tipoVacunacion" name="tipoVacunacion" value="" />
	<input type="hidden" id="gIdentificadorProducto" name="gIdentificadorProducto" value="" />
	<input type="hidden" id="especie" name="especie" value="<?php echo $especie['id_especies']; ?>" />
	<input type="hidden" id="arrayIdentificador" name="arrayIdentificador" />
	
	<input type="hidden" id="idAreas" name="idAreas" />
	<input type="hidden" id="idProductos" name="idProductos" />
	<input type="hidden" id="idOperaciones" name="idOperaciones" />
	<input type="hidden" id="identificadoresProductos" name="identificadoresProductos" />
	<input type="hidden" id="cantidadProductos" name="cantidadProductos" />

	<!-- <input type="text" id="datosVacunacion" name="datosVacunacion" /> -->

	<fieldset>
		<legend>Búsqueda del Sitio</legend>
		<div data-linea="1">
			<label>N° Identificación: </label>
			<input type="text" id="identificadorSolicitante" name="identificadorSolicitante" placeholder="Ej: 9999999999" maxlength="13" <?php if ($banderaSolicitante){ ?> value="<?php echo $identificadorSolicitante; ?>" readonly="readonly" <?php }?> />
		</div>
		<div data-linea="1">
			<label>Nombre del Sitio: </label>
			<input type="text" id="nombreSitio" name="nombreSitio" value="" placeholder="Ej: Hacienda San José" maxlength="200" />
		</div>
		<div data-linea="3" style="text-align: center">
			<button type="button" id="buscarSitios" name="buscarSitios">Buscar sitio</button>
		</div>
		<hr />
		<div data-linea="4" id="resultadoSitios">
			<label>Nombre del sitio: </label>
			<select id="sitio" name="sitio">
				<option value="0">Seleccione...</option>
			</select>
		</div>
	</fieldset>

	<fieldset>
		<legend>Detalle de productos a vacunar</legend>
		<div data-linea="1" id="resultadoOperaciones">
			<label>Operación: </label>
			<select id="operacion" name="operacion">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<div data-linea="1" id="resultadoAreas">
			<label>Área: </label>
			<select id="areas" name="areas">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<hr />
		<div data-linea="2" id="resultadoLotesProducto">
		</div>
		<div data-linea="3" id="visualizarIdentificadorRango1">
			<input type="radio" name="seleccionarIdentificadorRango" value="valorIdentificador" checked> Identificador
		</div>
		<div data-linea="3" id="visualizarIdentificadorRango2">
			<input type="radio" name="seleccionarIdentificadorRango" value="valorRango"> Rango<br>
		</div>
		<div data-linea="3" id="visualizarIdentificadorRango3">
			<label>Cantidad rango: </label>
			<input type="number" id="cantidadRango" onkeypress="ValidaSoloNumeros()" name="cantidadRango" maxlength="4" data-er="^[0-9]+$" min="1" onpaste="return false" />
		</div>
		<div data-linea="4" id="resultadoProductoVacunacion">
		</div>
		<div data-linea="5" id="cantidadLote">
			<label>Existentes en Lote: </label>
			<input type="text" id="cantidadExistentePorLote" name="cantidadExistentePorLote" value="" disabled="disabled" />
		</div>
		<div data-linea="5" id="cantidadVacuna">
			<label>Cantidad a Vacunar: </label>
			<input type="number" id="cantidad" onkeypress="ValidaSoloNumeros()" name="cantidad" placeholder="Ej: 3" maxlength="4" data-er="^[0-9]+$" min="1" onpaste="return false" />
		</div>
		<div data-linea="6">
			<button type="button" id="agregarDetalleVacuna" name="agregarDetalleVacuna" class="mas">Agregar</button>
		</div>
		<div data-linea="7" id="tablaDetalles">
			<table width="100%">
				<thead>
					<tr>
						<th>N°</th>
						<th>Identificador</th>
						<th>Registros de vacunacion</th>
						<th>Opción</th>
					</tr>
				</thead>
				<tbody id="tablaDetalleVacunacion"></tbody>
			</table>
		</div>

		<div data-linea="8" id="tablaDetallesLote">
			<table width="100%">
				<thead>
					<tr>
						<th>N°</th>
						<th>Identificador</th>
						<th>Registros de vacunacion</th>
						<th>Opción</th>
					</tr>
				</thead>
				<tbody id="tablaDetalleVacunacionLote"></tbody>
			</table>
		</div>
	</fieldset>

	<fieldset>
		<legend>Búsqueda del certificado de vacunación</legend>
		<div data-linea="1">
			<label>N° Certificado: </label>
			<input type="text" id="campoBusquedaCertificado" name=campoBusquedaCertificado />
		</div>
		<div data-linea="2" style="text-align: center">
			<button type="button" id="buscarCertificado" name="buscarCertificado">Buscar No.Certificado</button>
		</div>
	</fieldset>

	<fieldset id="busquedaVacunador" name="busquedaVacunador">
		<legend>Búsqueda del Vacunador</legend>
		<div data-linea="1">
			<label>Identificación: </label>
			<input type="text" id="identificacionVacunador" name="identificacionVacunador" value="" placeholder="Ej: 9999999999" maxlength="13" />
		</div>
		<div data-linea="1">
			<label>Nombre: </label>
			<input type="text" id="nombreVacunador" name="nombreVacunador" value="" placeholder="Ej: Juan Alvarez" maxlength="200" />
		</div>
		<div data-linea="2" style="text-align: center">
			<button type="button" id="buscarVacunador" name="buscarVacunador">Buscar vacunador</button>
		</div>
	</fieldset>

	<fieldset>
		<legend>Datos del Certificado de Vacunación</legend>
		<div data-linea="1" id="resultadoNumeroCertificado">
			<label>N° Certificado:</label>
			<select id="certificadoVacunacion" name="certificadoVacunacion">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<div data-linea="1">
			<label>Fecha Vacunación: </label>
			<input type="text" id="fechaVacunacion" name="fechaVacunacion" placeholder="12/12/2016" maxlength="10" data-inputmask="'mask': '99/99/9999'" data-er="^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$" data-inputmask="'mask': '99/99/9999'" readonly />
		</div>
		<div data-linea="2" id="resultadoVacunadorTecnico">
			<label>Vacunador: </label>
			<select id="vacunador" name="vacunador">
				<?php
				switch ($filaTipoUsuario['codificacion_perfil']) {
					case 'PFL_USUAR_INT':
						$qResultadoUsuarioTecnico = $va->verificarTecnicoAgrocalidad($conexion, $identificadorUsuario);
						while ($filas = pg_fetch_assoc($qResultadoUsuarioTecnico)) {
							echo '<option value="' . $filas['identificador'] . '">' . $filas['nombres'] . ' - ' . $filas['identificador'] . '</option>';
						}
						break;
					case 'PFL_USUAR_EXT':
				?>
						<option value="0">Seleccione...</option>
				<?php
						$vacunadores = $va->listarVacunadoresEmpresa($conexion, $idEmpresa);
						while ($fila = pg_fetch_assoc($vacunadores)) {
							if ($fila['estado'] == 'activo')
								echo '<option value="' . $fila['identificador'] . '">' . $fila['nombres'] . ' - ' . $fila['identificador'] . '</option>';
						}
						break;
				}
				?>
			</select>
		</div>
		<div data-linea="2">
			<label>Distribuidor: </label>
			<select id="distribuidor" name="distribuidor">
				<option value="0">Seleccione...</option>
				<?php
				switch ($filaTipoUsuario['codificacion_perfil']) {
					case 'PFL_USUAR_INT':
						$qResultadoUsuarioTecnico = $va->listarTecnicosDistribuidores($conexion);
						while ($filas = pg_fetch_assoc($qResultadoUsuarioTecnico)) {
							echo '<option value="' . $filas['identificador'] . '">' . $filas['nombres'] . ' - ' . $filas['identificador'] . '</option>';
						}
						break;
					case 'PFL_USUAR_EXT':
						$distribuidores = $va->listarDistribuidoresEmpresa($conexion, $idEmpresa);
						while ($fila = pg_fetch_assoc($distribuidores)) {
							if ($fila['estado'] == 'activo')
								echo '<option value="' . $fila['identificador'] . '">' . $fila['nombres'] . ' - ' . $fila['identificador'] . '</option>';
						}
						break;
				}
				?>
			</select>
		</div>
		<div data-linea="3">
			<label>Tipo Vacunación: </label>
			<select id="tipoVacuna" name="tipoVacuna">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<div data-linea="3">
			<label>Laboratorio: </label>
			<select id="laboratorio" name="laboratorio">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<div data-linea="4">
			<label>Lote Vacuna: </label>
			<select id="loteVacuna" name="loteVacuna">
				<option value="0">Seleccione...</option>
			</select>
		</div>
	</fieldset>

	<fieldset>
		<legend>Observaciones</legend>
		<div data-linea="1">
			<textarea name="observacion" style="width:100%;"></textarea>
		</div>
	</fieldset>

	<button type="submit" id="btnGuardar" name="btnGuardar" class="guardar">Guardar Vacunación</button>
</form>

<script type="text/javascript">
	var array_laboratorio = <?php echo json_encode($laboratorios); ?>;
	var array_lote = <?php echo json_encode($lotes); ?>;
	
	var array_eliminados = [];
	//var array_datos_identificadores = [];

	$(document).ready(function(event) {
		distribuirLineas();
		$("#codigoAreaOrigen").numeric();
		$("#campoBusquedaCertificado").numeric();

		$("#cantidadVacuna").hide();
		$("#cantidadLote").hide();
		$("#lotesProducto").hide();
		//$("#agregarDetalleVacuna").hide();
		$("#tablaDetalles").hide();
		$("#tablaDetallesLote").hide();

		$("#visualizarIdentificadorRango1").hide();
		$("#visualizarIdentificadorRango2").hide();
		$("#visualizarIdentificadorRango3").hide();
		$("#cantidadRango").prop('disabled', true);
		$("#cantidadRango").val("");

		var fecha = <?php echo json_encode($fecha); ?>;

		fecha = new Date(fecha);
		var fechaFormateadaInicio = new Date(new Date(fecha).setDate(fecha.getDate()));
		var fechaDiaInicio = ("0" + (fechaFormateadaInicio.getDate())).slice(-2);
		var fechaMesInicio = ("0" + (fechaFormateadaInicio.getMonth() - 3)).slice(-2);
		var fechaAnioInicio = fechaFormateadaInicio.getFullYear();
		fechaInicio = fechaDiaInicio + '-' + fechaMesInicio + '-' + fechaAnioInicio;

		var fechaFormateadaFin = new Date(new Date(fecha).setDate(fecha.getDate()));

		var fechaDiaFin = ("0" + (fechaFormateadaFin.getDate())).slice(-2);
		var fechaMesFin = ("0" + (fechaFormateadaFin.getMonth() + 1)).slice(-2);
		var fechaAnioFin = fechaFormateadaFin.getFullYear();
		fechaFin = fechaDiaFin + '-' + fechaMesFin + '-' + fechaAnioFin;

		$("#fechaVacunacion").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd-mm-yy',
			minDate: fechaInicio,
			maxDate: fechaFin
		});

		if (<?php echo json_encode($filaTipoUsuario['codificacion_perfil']); ?> == "PFL_USUAR_INT")
			$("#busquedaVacunador").show();
		else
			$("#busquedaVacunador").hide();
		construirValidador();

	});
	
	$("#buscarCertificado").click(function(event) {
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($("#campoBusquedaCertificado").val() == 0) {
			error = true;
			$("#campoBusquedaCertificado").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el número de certificado").addClass('alerta');
		}

		if ($("#especie").val() == 0) {
			error = true;
			$("#especie").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione la especie").addClass('alerta');
		}

		if (!error) {

			event.preventDefault();
			event.stopImmediatePropagation();

			var array_tipoVacuna = <?php echo json_encode($tipoVacuna); ?>;
			var sTipoVacuna = '<option value="0">Seleccione...</option>';

			for (var i = 0; i < array_tipoVacuna.length; i++) {
				if ($("#especie").val() == array_tipoVacuna[i]['id_especie'])
					sTipoVacuna += '<option data-codigo="' + array_tipoVacuna[i]['codigo'] + '" data-costo="' + array_tipoVacuna[i]['costo'] + '" value="' + array_tipoVacuna[i]['id_tipo_vacuna'] + '">' + array_tipoVacuna[i]['nombre_vacuna'] + '</option>';

			}

			$('#tipoVacuna').html(sTipoVacuna);
			$("#tipoVacuna").removeAttr("disabled");
			$('select[name="tipoVacuna"]').find('option[data-codigo="NORPE"]').prop("selected", "selected");
			$("#costoVacuna").val($("#tipoVacuna option:selected").attr('data-costo'));

			var sLaboratorio = '0';
			sLaboratorio = '<option value="0">Seleccione...</option>';
			for (var i = 0; i < array_laboratorio.length; i++) {
				if ($("#especie").val() == array_laboratorio[i]['id_especie'])
					sLaboratorio += '<option data-codigo="' + array_laboratorio[i]['codigo'] + '" value="' + array_laboratorio[i]['id_laboratorio'] + '"> ' + array_laboratorio[i]['nombre_laboratorio'] + '</option>';
			}
			$('#laboratorio').html(sLaboratorio);
			$("#laboratorio").removeAttr("disabled");

			$('select[name="laboratorio"]').find('option[data-codigo="JAMBR"]').prop("selected", "selected");

			var slote = '0';
			slote = '<option value="0">Seleccione...</option>';
			for (var i = 0; i < array_lote.length; i++) {
				if (array_lote[i]['codigo'] == 'JAMBR')
					slote += '<option value="' + array_lote[i]['id_lote'] + '">' + array_lote[i]['numero_lote'] + '</option>';
			}

			$('#loteVacuna').html(slote);
			$("#loteVacuna").removeAttr("disabled");

			$("#estado").html('');
			var h = ("0000000" + $('#campoBusquedaCertificado').val()).slice(-7);
			$('#numeroCertificado').val(h);
			$('#nuevoVacunacion').attr('data-opcion', 'accionesVacunacion');
			$('#nuevoVacunacion').attr('data-destino', 'resultadoNumeroCertificado');
			$('#opcion').val('buscarCertificado');
			$('#arrayIdentificador').val(JSON.stringify(array_datos_identificadores));

			abrir($("#nuevoVacunacion"), event, false);
		}

	});

	$("#tipoVacuna").change(function(event) {
		if ($("#tipoVacuna").val() != 0) {
			$("#costoVacuna").val($("#tipoVacuna option:selected").attr('data-costo'));
		}
	});

	$("#laboratorio").change(function(event) {
		if ($("#laboratorio").val() != 0) {
			var slote = '0';
			slote = '<option value="0">Seleccione...</option>';
			for (var i = 0; i < array_lote.length; i++) {
				if ($("#laboratorio").val() == array_lote[i]['id_laboratorio'])
					slote += '<option value="' + array_lote[i]['id_lote'] + '">' + array_lote[i]['numero_lote'] + '</option>';
			}
			$('#loteVacuna').html(slote);
			$("#loteVacuna").removeAttr("disabled");
		}
	});

	$("input[name=seleccionarIdentificadorRango]").click(function(event) {
		if ($("input[name=seleccionarIdentificadorRango]:checked").val() == 'valorIdentificador') {
			$("#cantidadRango").prop('disabled', true);
			$("#cantidadRango").val("");
		} else if ($("input[name=seleccionarIdentificadorRango]:checked").val() == 'valorRango') {
			$("#cantidadRango").prop('disabled', false);
		}
	});


	$("#buscarSitios").click(function(event) {
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		reestablecerCamposIniciales();

		if ($("#identificadorSolicitante").val() == "" && $("#nombreSitio").val() == "") {
			error = true;
			$("#identificadorSolicitante").addClass("alertaCombo");
			$("#nombreSitio").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese al menos un campo para realizar la búsqueda.").addClass('alerta');
		}

		if (!error) {
			$('#nuevoVacunacion').attr('data-opcion', 'accionesVacunacion');
			$('#nuevoVacunacion').attr('data-destino', 'resultadoSitios');
			$('#opcion').val('listaSitios');
			abrir($("#nuevoVacunacion"), event, false);
			$("#estado").html("").removeClass('alerta');

		}
	});

	$("#agregarDetalleVacuna").click(function(event) {

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if ($("#tipoVacunacion").val() == 'lote') {

			var codigoValidar = $("#gNumeroLote").val();

			if ($("#tablaDetalleVacunacionLote #r_" + codigoValidar).length != 0) {
				error = true;
				$("#estado").html("No se puede ingresar el mismo lote, por favor borre el registro y vuelvalo a agregar.").addClass('alerta');
			}

			if ($("#cantidad").val() > parseInt($("#cantidadExistentePorLote").val())) {
				error = true;
				$("#cantidad").addClass("alertaCombo");
				$("#estado").html("El valor ingresado no puede ser mayor a la cantidad existente en el lote.").addClass('alerta');
			}

			if ($("#cantidad").val() == 0) {
				error = true;
				$("#cantidad").addClass("alertaCombo");
				$("#estado").html("El valor ingresado debe ser mayor a cero.").addClass('alerta');
			}

			if($.trim($("#cantidad").val()) == "" ){
				error = true;
				$("#cantidad").addClass("alertaCombo");
				$("#estado").html("Por favor ingrese la cantidad de identificadores a vacunar.").addClass('alerta');
			}	

		}else if ($("#tipoVacunacion").val() == 'identificador'){

				if ($.trim($("#cantidad").val())) {
					if (!esCampoValido("#cantidad")) {
						error = true;
						$("#cantidad").addClass("alertaCombo");
						$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
					}
				}				

				if ($("#cantidadExistente").val() <= 0 || $("#cantidadExistente").val() == "") {
					error = true;
					$("#cantidadExistente").addClass("alertaCombo");
					$("#estado").html('Por favor verifique los datos, no hay stock de productos a vacunar.').addClass("alerta");
				}

				if ($("#lote").is(":checked") == true && $("#lotesProducto").val() == 0) {
					error = true;
					$("#lotesProducto").addClass("alertaCombo");
					$("#estado").html('Por favor seleccione el número de lote.').addClass("alerta");
				}

				if ($("#areas").val() == 0) {
					error = true;
					$("#areas").addClass("alertaCombo");
					$("#estado").html('Por favor seleccione el área.').addClass("alerta");
				}

				if ($("#operacion").val() == 0) {
					error = true;
					$("#operacion").addClass("alertaCombo");
					$("#estado").html('Por favor seleccione la operación.').addClass("alerta");
				}

				$('#tablaDetalleVacunacion tr').each(function(event) {
					
					if (parseInt($(this).find('input[name="hSitio"]').val()) != $("#sitio").val()) {
						error = true;
						$("#estado").html('Solo puede escoger un mismo sitio.').addClass("alerta");
					}
				});

				if (jQuery.inArray($("#gIdentificadorProducto").val(), array_datos_identificadores) != -1 || $("#gIdentificadorProducto").val() == "") {
					error = true;
					$("#identificadorProductoAutocompletar").addClass("alertaCombo");
					$("#estado").html('El identificador no existe').addClass("alerta");
				}

				if ($("input[name=seleccionarIdentificadorRango]:checked").val() == 'valorRango') {

					if ($("#cantidadRango").val() == "" || $("#cantidadRango").val() == "0") {
						error = true;
						$("#cantidadRango").addClass("alertaCombo");
						$("#estado").html('Ingrese un valor de rango válido.').addClass("alerta");
					}

				}

				var codigoValidar = $("#gIdentificadorProducto").val();

				if ($("#tablaDetalleVacunacion #r_" + codigoValidar).length != 0) {
					error = true;
					$("#estado").html("No se puede ingresar el mismo identificador.").addClass('alerta');
				}

			}

		if (!error) {

			$('#sitio option:not(:selected)').attr('disabled',true);

			if ($("#tipoVacunacion").val() == 'lote') {

				var codigo = $("#gNumeroLote").val();

					$("#tablaDetalleVacunacionLote").append("<tr id='r_" + codigo + "'><td>" + $(this).next('label').text() + "</td><td>" + codigo + "<input type='hidden' name='hNumeroLote[]' value='" + codigo + "' disabled='disabled'>" +
						"</td><td><input type='hidden' name='hOperacion[]' value='" + $("#operacion").val() + "' disabled='disabled'>" + $("#operacion option:selected").text() + " - (" +
						"<input type='hidden' name='hIdArea[]' value='" + $("#areas option:selected").val() + "' disabled='disabled'>" + $("#areas option:selected").text() + ") - " +
						"<input type='hidden' name='hProducto[]' value='" + $("#gProducto").val() + "' disabled='disabled'>" + $("#cantidad").val() + ' de ' + $("#cantidadExistentePorLote").val() + ' ('  +  $("#gNombreProducto").val() + ')' +
						"</td><td align='center' class='borrar'><input type='hidden' name='hSitio' value='" + $("#sitio").val() + "' disabled='disabled'>" + "<input type='hidden' name='hCantidadProducto[]' value='" + $("#cantidad").val() + "' disabled='disabled'>" +
						"<button type='button' onclick='quitarDetalleVacunacionLote(\"#r_" + codigo + "\")' class='icono'></button></td></tr>");

					$("#estado").html("").removeClass('alerta');

					enumerarLote();

					$("#tablaDetalleVacunacionLote tr").each(function(){

						valorLote = $(this).find("input[id='hNumeroLote']").val();
							
						$("#lotesProducto option").each(function() {
							
							if ($(this).val() == valorLote){
								$(this).prop('disabled', true);
							}
						});

					});

			}else if ($("#tipoVacunacion").val() == 'identificador'){

				var arrayCodigo = new Array();

				$("#identificadorProductoAutocompletar").val("");

				$('#sitio option:not(:selected)').attr('disabled', true);

				if ($("input[name=seleccionarIdentificadorRango]:checked").val() == 'valorIdentificador') {

					$("#identificadorProductoAutocompletar").val("");
					$('#sitio option:not(:selected)').attr('disabled', true);

					agregarArray(array_datos_identificadores, $("#gIdentificadorProducto").val());

					var codigo = $("#gIdentificadorProducto").val();

					$("#tablaDetalleVacunacion").append("<tr id='r_" + codigo + "'><td>" + $(this).next('label').text() + "</td><td>" + codigo + "<input type='hidden' name='hIdentificadoresValidar[]' value='" + codigo + "' >" +
						"</td><td><input type='hidden' name='hOperacion[]' value='" + $("#operacion").val() + "' disabled='disabled'>" + $("#operacion option:selected").text() + " - (" +
						"<input type='hidden' name='hIdArea[]' value='" + $("#areas option:selected").val() + "' disabled='disabled'>" + $("#areas option:selected").text() + ") - " +
						"<input type='hidden' name='hProducto[]' value='" + $("#gProducto").val() + "' disabled='disabled'>" + $("#gNombreProducto").val() +
						"</td><td align='center' class='borrar'><input type='hidden' name='hSitio' value='" + $("#sitio").val() + "' disabled='disabled'>" +
						"<button type='button' onclick='quitarDetalleVacunacion(\"#r_" + codigo + "\")' class='icono'></button></td></tr>");

					$("#estado").html("").removeClass('alerta');

					enumerar();

				}else if ($("input[name=seleccionarIdentificadorRango]:checked").val() == 'valorRango') {

					var cuenta = $("#cantidadRango").val();

					var identificador = $("#gIdentificadorProducto").val();
					var codigo = 0;

					identificador = parseInt(identificador.split("EC")[1]);

					var registro;

					for (i = 0; i < cuenta; i++) {

						codigo = "EC" + String(pad(identificador, 9));

						array_datos_identificadores.forEach(function(key, index) {

							if (codigo == key.value) {

								agregarArray(array_datos_identificadores, codigo);

								$("#tablaDetalleVacunacion").append("<tr id='r_" + codigo + "'><td>" + $(this).next('label').text() + "</td><td>" + codigo + "<input type='hidden' name='hIdentificadoresValidar[]' value='" + codigo + "' >" +
									"</td><td><input type='hidden' name='hOperacion[]' value='" + $("#operacion").val() + "' disabled='disabled'>" + $("#operacion option:selected").text() + " - (" +
									"<input type='hidden' name='hIdArea[]' value='" + $("#areas option:selected").val() + "' disabled='disabled'>" + $("#areas option:selected").text() + ") - " +
									"<input type='hidden' name='hProducto[]' value='" + $("#gProducto").val() + "' disabled='disabled'>" + $("#gNombreProducto").val() +
									"</td><td align='center' class='borrar'><input type='hidden' name='hSitio' value='" + $("#sitio").val() + "' disabled='disabled'>" +
									"<button type='button' onclick='quitarDetalleVacunacion(\"#r_" + codigo + "\")' class='icono'></button></td></tr>");

								$("#estado").html("").removeClass('alerta');

							}

						});

						identificador++;

					}

					enumerar();

					$("#cantidadRango").val("");

				}
			}

		}
	});

	function enumerar() {
		var tabla = document.getElementById('tablaDetalleVacunacion');
		con = 0;
		$("#tablaDetalleVacunacion tr").each(function(row) {
			con += 1;
			$(this).find('td').eq(0).html(con);
		});
	}

	function enumerarLote() {
		var tabla = document.getElementById('tablaDetalleVacunacionLote');
		con = 0;
		$("#tablaDetalleVacunacionLote tr").each(function(row) {
			con += 1;
			$(this).find('td').eq(0).html(con);
		});
	}

	function quitarDetalleVacunacion(fila) {

		var codigo = fila.split('_')[1];

		eliminarArray(array_eliminados, codigo);

		$("#estado").html("").removeClass('alerta');
		$("#tablaDetalleVacunacion tr").eq($(fila).index()).remove();

		$("#gProducto").val("");
		$("#gNombreProducto").val("");
		$("#gIdentificadorProducto").val("");

		if ($('#tablaDetalleVacunacion tr').length == 0) {
			$('#sitio option:not(:selected)').attr('disabled', false);
		}

		enumerar();

	}

	function quitarDetalleVacunacionLote(fila) {
		
		$("#estado").html("").removeClass('alerta');
		$("#tablaDetalleVacunacionLote tr").eq($(fila).index()).remove();

		if ($('#tablaDetalleVacunacionLote tr').length == 0) {
			$('#sitio option:not(:selected)').attr('disabled', false);
		}

		$("#lotesProducto option").each(function() {	
			valor = fila.replace('#r_','');

		    if ($(this).val() == valor){
		        $(this).prop('disabled', false);
		    }
		});

		enumerarLote();

	}

	$("#nuevoVacunacion").submit(function(event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		

		if ($("#tipoVacunacion").val() == 'lote') {

			var idAreasX = '';
			var idProductosX = '';
			var idOperacionesX = '';
			var cantidadX = '';	
			var numeroLoteX = '';			

			$('#tablaDetalleVacunacionLote tr').each(function (rows) {				

				var idAreas = $(this).find('td').find('input[name="hIdArea[]"]').val();
				var idProductos = $(this).find('td').find('input[name="hProducto[]"]').val();
				var idOperaciones = $(this).find('td').find('input[name="hOperacion[]"]').val();
				var cantidad = $(this).find('td').find('input[name="hCantidadProducto[]"]').val();
				var numeroLote = $(this).find('td').find('input[name="hNumeroLote[]"]').val();

				if ($('#tablaDetalleVacunacionLote tr').length){		
					
					idAreasX += idAreas + ',';
					idProductosX += idProductos + ',';
					idOperacionesX += idOperaciones + ',';
					numeroLoteX += numeroLote + ',';
					cantidadX += cantidad + ',';
					
				}

			});

			$('#idAreas').val(quitarComa(idAreasX));
			$('#idProductos').val(quitarComa(idProductosX));
			$('#idOperaciones').val(quitarComa(idOperacionesX));
			$('#cantidadProductos').val(quitarComa(cantidadX));
			$('#identificadoresProductos').val(quitarComa(numeroLoteX));

		}else if($("#tipoVacunacion").val() == 'identificador') {

			var idAreasX = '';
			var idProductosX = '';
			var idOperacionesX = '';
			var identificadoresProductosX = '';			

			$('#tablaDetalleVacunacion tr').each(function (rows) {				

				var idAreas = $(this).find('td').find('input[name="hIdArea[]"]').val();
				var idProductos = $(this).find('td').find('input[name="hProducto[]"]').val();
				var idOperaciones = $(this).find('td').find('input[name="hOperacion[]"]').val();
				var identificadoresProductos = $(this).find('td').find('input[name="hIdentificadoresValidar[]"]').val();

				if ($('#tablaDetalleVacunacion tr').length){		
					
					idAreasX += idAreas + ',';
					idProductosX += idProductos + ',';
					idOperacionesX += idOperaciones + ',';
					identificadoresProductosX += identificadoresProductos + ',';
					
				}

			});

			$('#idAreas').val(quitarComa(idAreasX));
			$('#idProductos').val(quitarComa(idProductosX));
			$('#idOperaciones').val(quitarComa(idOperacionesX));
			$('#identificadoresProductos').val(quitarComa(identificadoresProductosX));

			var datosVacunacion=[];
			
			$("#tablaDetalleVacunacion tr").each(function(i,e){		//ARRAY	
				var tr1 = {};
				$(this).find("td").each(function(index, element){
					if(index != 0) //ignoramos el primer indice que es del número
					{
						$(this).find("input").each(function(){					
							tr1[$(this).attr('name')] = $(this).val();
						});
					}
				});
				datosVacunacion.push(tr1);
			});

			$('#datosVacunacion').val(JSON.stringify(datosVacunacion));

		}
		
		if ($("#tipoVacunacion").val() != 'lote') {
			if ($("#tablaDetalleVacunacion >tr").length == 0) {
				error = true;
				$("#estado").html('Por favor ingrese al menos un detalle de productos a movilizar').addClass("alerta");
			}
		}

		if ($("#sitio").val() == 0) {
			error = true;
			$("#sitio").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el sitio.').addClass("alerta");
		}

		if ($("#loteVacuna").val() == 0) {
			error = true;
			$("#loteVacuna").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el lote de vacuna.').addClass("alerta");
		}

		if ($("#distribuidor").val() == 0) {
			error = true;
			$("#distribuidor").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el distribuidor.').addClass("alerta");
		}

		if ($("#laboratorio").val() == 0) {
			error = true;
			$("#laboratorio").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el laboratorio.').addClass("alerta");
		}

		if ($("#tipoVacuna").val() == 0) {
			error = true;
			$("#tipoVacuna").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el tipo de vacuna.').addClass("alerta");
		}

		if ($("#vacunador").val() == 0) {
			error = true;
			$("#vacunador").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el vacunador.').addClass("alerta");
		}

		if ($("#fechaVacunacion").val() == '' || !esCampoValido("#fechaVacunacion")) {
			error = true;
			$("#fechaVacunacion").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese o revise la fecha de vacunación.').addClass("alerta");
		}

		if ($("#certificadoVacunacion").val() == 0) {
			error = true;
			$("#certificadoVacunacion").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el numero de certificado.').addClass("alerta");
		}

		if (!error) {
			$("#estado").html("").removeClass('alerta');
			$("#nuevoVacunacion").attr('data-destino', 'detalleItem');
			$('#nuevoVacunacion').attr('data-opcion', 'guardarNuevoVacunacion');
			ejecutarJson("#nuevoVacunacion");
		}
	});

	$("#buscarVacunador").click(function(event) {
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($("#identificacionVacunador").val() == "" && $("#nombreVacunador").val().length < 3) {
			error = true;
			$("#nombreVacunador").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
		}

		if ($("#identificacionVacunador").val() == "" && $("#nombreVacunador").val() == "") {
			error = true;
			$("#identificacionVacunador").addClass("alertaCombo");
			$("#nombreVacunador").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese al menos un campo para realizar la búsqueda.").addClass('alerta');
		}

		if (!error) {
			$("#estado").html('');
			$('#nuevoVacunacion').attr('data-opcion', 'accionesVacunacion');
			$('#nuevoVacunacion').attr('data-destino', 'resultadoVacunadorTecnico');
			$('#opcion').val('vacunadorTecnico');
			abrir($("#nuevoVacunacion"), event, false);
		}
	});

	function agregarArray(arr, item) {
		var registro;
		arr.forEach(function(key, index) {
			if (item == key.value) {
				array_eliminados.push(key);
				registro = index;
			}
		});

		if (registro !== -1) {
			arr.splice(registro, 1);
		}
		//console.log(array_eliminados);
	}

	function pad(str, max) {
		str = str.toString();
		return str.length < max ? pad("0" + str, max) : str;
	}

	function eliminarArray(arr, item) {
		var registro;
		arr.forEach(function(key, index) {
			if (item == key.value) {
				array_datos_identificadores.push(key);
				registro = index;
			}
		});

		if (registro !== -1) {
			arr.splice(registro, 1);
		}
		//console.log(array_eliminados);
		//console.log(array_datos_identificadores);
	}

	function quitarComa (dato){
		valorDevuelto = dato.substr(0, dato.lastIndexOf(","));
		return(valorDevuelto);
	}

	function reestablecerCamposIniciales(){

		$('#operacion').html("");
		$("#operacion").append("<option value='0'>Seleccione...</option>");
		$('#areas').html("");
		$("#areas").append("<option value='0'>Seleccione...</option>");
		$('#lotesProducto').html("");
		$("#lotesProducto").append("<option value='0'>Seleccione...</option>");
		$('#cantidadExistentePorLote').val("");
		$('#cantidad').val("");

		$("#resultadoLotesProducto").hide();
		$("#resultadoProductoVacunacion").hide();
		$("#cantidadLote").hide();
		$("#cantidadVacuna").hide();
		$("#tablaDetalles").hide();
		$("#tablaDetallesLote").hide();
		$("#tablaDetalleVacunacion tr").remove();
		$("#tablaDetalleVacunacionLote tr").remove();
		$('#gNumeroLote').val("");

		if(!$.isEmptyObject(array_eliminados)){
			array_eliminados.length = 0;
			
			console.log(array_eliminados);
		}

		if($('#tablaDetalleVacunacion tr').length){

			if(!$.isEmptyObject(array_datos_identificadores)){
				array_datos_identificadores.length = 0;			
				//console.log(array_datos_identificadores);
			}

		}

	}

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}

</script>