<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ModificacionProductoRia'
	data-opcion='SolicitudesProductos/guardar' data-destino="detalleItem"
	method="post">

    <?php echo $this->datosGenerales; ?>

    <fieldset>
		<legend>Producto a modificar</legend>

		<div data-linea="1">
			<label for="id_area">Área temática: </label> <select id="id_area"
				name="id_area" class="validacion">
				<option value="">Seleccione...</option>
				<option value="IAP">Agrícola</option>
				<option value="IAF">Fertilizante</option>
				<option value="IAV">Pecuario</option>
			</select>
		</div>

		<div data-linea="2">
			<label for="id_tipo_producto">Tipo de producto:</label> <select
				id="id_tipo_producto" name="id_tipo_producto" class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="3">
			<label for="id_subtipo_producto">Subtipo de producto:</label> <select
				id="id_subtipo_producto" name="id_subtipo_producto"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="4">
			<label for="id_producto">Producto:</label> <select id="id_producto"
				name="id_producto" class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="5">
			<label for="numero_registro">Número de registro: </label> <input
				type="text" id="numero_registro" name="numero_registro"
				required="required" readonly="readonly" maxlength="32" />
		</div>

	</fieldset>

	<fieldset id="fTipoModificacion">
		<legend>Modificación producto</legend>

		<div data-linea="1">
			<label for="tipo_modificacion_producto">Tipo de modificación:</label>
			<select id="tipo_modificacion_producto"
				name="tipo_modificacion_producto" class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="2">
			<button id="agregarTipoModificacion" type="button" class="mas">Agregar</button>
		</div>
	</fieldset>

	<fieldset>
		<legend>Tipos de modificaciones seleccionadas</legend>
		<table id="tTipoModificacion" style="width: 100%;">
			<thead>
				<tr>
					<th>Tipo de modificación</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</fieldset>

	<div data-linea="3">
		<button type="submit" class="guardar">Guardar</button>
	</div>
	<input type="hidden" id="id" name="id" />
</form>
<script type="text/javascript">
    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
    });

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;

        $('#formulario .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

            if (respuesta.estado === 'exito'){
            	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
                $("#id").val(respuesta.contenido);
                $("#formulario").attr('data-opcion', 'SolicitudesProductos/editar');
                abrir($("#formulario"), event, false);
            }else {
                $("#estado").html(respuesta.mensaje).addClass("alerta");
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    $("#id_area").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_area").val() != '') {
            $.post("<?php echo URL ?>ModificacionProductoRia/SolicitudesProductos/obtenerTipoProductoPorIdArea",
                {
                    id_area: $("#id_area").val()

                }, function (data) {
                    if (data.estado === 'EXITO') {
                        $("#id_tipo_producto").html(data.comboTipoProducto);
                        $("#tipo_modificacion_producto").html(data.comboTipoModificacion);
                    }
                }, 'json');
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#id_tipo_producto").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_tipo_producto").val() != '') {
            $.post("<?php echo URL ?>ModificacionProductoRia/SolicitudesProductos/obtenerSubtipoProductoPorIdTipoProducto",
                {
                    id_tipo_producto: $("#id_tipo_producto").val()

                }, function (data) {
                    if (data.estado === 'EXITO') {
                        $("#id_subtipo_producto").html(data.comboSubtipoProducto);
                    }
                }, 'json');
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#id_subtipo_producto").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_subtipo_producto").val() != '') {
            $.post("<?php echo URL ?>ModificacionProductoRia/SolicitudesProductos/obtenerProductoPorIdSubtipoProducto",
                {
                    id_subtipo_producto: $("#id_subtipo_producto").val()

                }, function (data) {
                    if (data.estado === 'EXITO') {
                        $("#id_producto").html(data.comboProducto);
                    }
                }, 'json');
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#id_producto").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_producto").val() != '') {
            $.post("<?php echo URL ?>ModificacionProductoRia/SolicitudesProductos/obtenerNumeroRegistroProducto",
                {
                    id_producto: $("#id_producto").val()

                }, function (data) {
                    if (data.estado === 'EXITO') {
                        $("#numero_registro").val(data.numeroRegistro);
                    }
                }, 'json');
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#agregarTipoModificacion").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        $('#fTipoModificacion .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            var codigoTipoModificacion = 'r_' + $("#tipo_modificacion_producto").val();
            var cadena = '';

            if ($("#tTipoModificacion tbody #" + codigoTipoModificacion.replace(/ /g, '')).length == 0) {

                cadena = "<tr id='" + codigoTipoModificacion.replace(/ /g, '') + "'>" +
                    "<td>" + $("#tipo_modificacion_producto option:selected").text() +
                    "<input name='id_tipo_modificacion_producto[]' value='" + $("#tipo_modificacion_producto").val() + "' type='hidden'>" +
                    "<input name='tipo_modificacion[]' value='" + $("#tipo_modificacion_producto option:selected").text() + "' type='hidden'>" +
                    "<input name='tiempo_atencion[]' value='" + $("#tipo_modificacion_producto option:selected").attr("data-tiempoatencion") + "' type='hidden'>" +
                    "</td>" +
                    "<td style='text-align: right'>" +
                    "<button type='button' onclick='quitarTipoModificacion(" + codigoTipoModificacion.replace(/ /g, '') + ")' class='menos'>Quitar</button>" +
                    "</td>" +
                    "</tr>"

                $("#tTipoModificacion tbody").append(cadena);

            } else {
                mostrarMensaje("No puede ingresar dos registros iguales.", "FALLO");
            }

        } else {
            mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
        }
    });

    function quitarTipoModificacion(fila){
        $("#tTipoModificacion tbody tr").eq($(fila).index()).remove();
    }

</script>
