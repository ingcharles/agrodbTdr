<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='ordenSaldo' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>financiero' data-opcion='saldos/guardarRecargaSaldo' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">

    <input type="hidden" id="id" name="id" />

    <fieldset>
        <legend>Cabecera</legend>

        <div data-linea="1">
            <label for="identificadorOP">RUC / CI: </label>
            <input type="text" id="identificadorOP" name="identificadorOP" value="<?php echo $this->resultadoConsulta['identificador']; ?>" <?php echo $this->resultadoConsulta['readonly']; ?>maxlength="13" />
        </div>

        <div data-linea="2">
            <label for="razonSocial">Razón Social: </label>
            <input type="text" id="razonSocial" name="razonSocial" readonly="readonly" maxlength="13" />
        </div>

        <div data-linea="3">
            <label for="direccion">Dirección: </label>
            <input type="text" id="direccion" name="direccion" readonly="readonly" maxlength="13" />
        </div>

        <div data-linea="4">
            <label for="telefono">Teléfono: </label>
            <input type="text" id="telefono" name="telefono" readonly="readonly" maxlength="13" />
        </div>

        <div data-linea="5">
            <label for="correo">Correo(Facturación): </label>
            <input type="text" id="correo" name="correo" readonly="readonly" maxlength="13" />
        </div>


    </fieldset>

    <fieldset>
        <legend>información Adicional</legend>

        <div data-linea="1">
            <label for="motivo">Motivo: </label>
            <input type="text" id="motivo" name="motivo" value="Solicitud de Incremento de Saldo Disponible" readonly="readonly" />
        </div>

        <div data-linea="2">
            <label for="cantidad">Cantidad: USD</label>
            <input type="text" id="cantidad" name="cantidad" maxlength="4" onkeypress="soloNumeros()" />
        </div>

        <br /><br />
        <div data-linea="3" class="alerta" style="text-align: center;">La orden de pago generada tiene vigencia por 5 días</div>
        <br /><br />

        <div> <a href="aplicaciones/mvc/modulos/Financiero/archivos/acuerdo.pdf" target="_blank">(DESCARGAR ACUERDO) </a></div>

        <div style="font-style: italic"><input type="checkbox" id="terminos" name="terminos" onchange="verificarTerminos()" /><label for="terminos"> He leido y acepto las condiciones de mantener un saldo Disponible en Agrocalidad y el Control ecuatoriano de lavado de activos.</label></div>
    </fieldset>

    <div id="cargarMensajeTemporal"></div>

    <button id="gnerarOrden" type="submit" disabled="disabled">Generar</button>

</form>

<script type="text/javascript">
    $(document).ready(function() {
        distribuirLineas();
        fn_cargarDatos();
    });

    //función para ingresar solo números a un input
    function soloNumeros() {
        if ((event.keyCode < 48) || (event.keyCode > 57))
            event.returnValue = false;
    }

    $("#identificadorOP").change(function() {
        if (($(this).val !== "")) {
            fn_cargarDatos();
        }
    });

    function verificarTerminos() {
        if ($("#terminos").prop('checked')) {
            $("#gnerarOrden").attr("disabled", false);
        } else {
            $("#gnerarOrden").attr("disabled", true);
        }
    }

    $("#ordenSaldo").submit(function(event) {
        event.preventDefault();
        var error = false;
        var identificador = $("#identificadorOP").val();
        var nuevoSaldo = $("#cantidad").val();

        $(".alertaCombo").removeClass("alertaCombo");

        if (!$.trim($("#identificadorOP").val()) || !esCampoValido("#identificadorOP")) {
            error = true;
            $("#identificadorOP").addClass("alertaCombo");
        }

        if (!$.trim($("#cantidad").val()) || !esCampoValido("#cantidad")) {
            error = true;
            $("#cantidad").addClass("alertaCombo");
        }

        if (!error) {
            $("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
            $.post("<?php echo URL ?>Financiero/Saldos/buscarSaldoDiario", {
                identificador: identificador,
                nuevoSaldo: nuevoSaldo,
            }, function(data) {
                if (data.estado == 'FALLO') {
                    mostrarMensaje(data.mensaje, "FALLO");
                    $("#cargarMensajeTemporal").html("");
                } else {
                    setTimeout(function() {
                        var respuesta = JSON.parse(ejecutarJson($("#ordenSaldo")).responseText);
                        if (respuesta.estado == 'exito') {
                            $("#id").val(respuesta.contenido);
                            $("#ordenSaldo").attr('data-opcion', 'saldos/mostrarReporte');
                            abrir($("#ordenSaldo"), event, false);
                        }
                    }, 1000);
                }
            }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //función para cargar los datos del usuario
    function fn_cargarDatos() {
        $(".alertaCombo").removeClass("alertaCombo");
        mostrarMensaje("", "EXITO");
        var identificadorUsuario = $("#identificadorOP").val();

        if (identificadorUsuario !== "") {

            $.post("<?php echo URL ?>Financiero/Saldos/obtenerDatosClientes", {
                identificador: identificadorUsuario,
            }, function(data) {

                $('#razonSocial').val(data.contenido.razon_social);
                $('#direccion').val(data.contenido.direccion);
                $('#telefono').val(data.contenido.telefono);
                $('#correo').val(data.contenido.email);

                if (data.estado != 'EXITO') {
                    $("#terminos").attr("disabled", true).prop("checked", false);
                    mostrarMensaje(data.mensaje, "FALLO");
                    verificarTerminos();
                    if (data.contenido.identificador != '' && data.contenido.email == '') {
                        $("#correo").addClass("alertaCombo");
                    }
                } else{
                    $("#terminos").attr("disabled", false).prop("checked", false);
                }
            }, 'json');
        }
    }
</script>