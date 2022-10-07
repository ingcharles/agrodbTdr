<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<?php echo $this->datosGenerales; ?>

<form id='formularioAsignarRevisor'>

    <input type="hidden" id="solicitudes" name="solicitudes" value="<?php echo ($_POST['id'] === '_asignar' ? $_POST['elementos'] : $_POST['id']); ?>">

    <fieldset>
        <legend>Técnicos</legend>

        <div data-linea="1">
            <label for="identificador_revisor">Técnico:</label>
            <select id="identificador_revisor"
                    name="identificador_revisor" class="validacion">
                <option value="">Seleccionar....</option>
                <?php echo $this->comboPerfilRevision; ?>
            </select>
        </div>

        <div data-linea="2">
            <button type="submit" class="mas" id="agregarAsignarRevisor">Asignar técnico</button>
        </div>
    </fieldset>

    <fieldset>
        <legend>Técnico asignado</legend>
        <table id="tTecnicoRevision" style="width: 100%;">
            <thead>
            <tr>
                <th># Solicitud</th>
                <th>Tipo de solicitud</th>
                <th>Técnico asignado</th>
                <th>Provincia</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php echo $this->generarFilaRevisorAsignado; ?>
            </tbody>
        </table>
    </fieldset>

    <div data-linea="3">
        <button id="enviarSolicitud" type="button" class="guardar">Guardar</button>
    </div>
</form>
<script type="text/javascript">

    var tipoSolicitud = "modificacionProductoRia";
    var tipoInspector = "Técnico";

    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
    });

    $("#formularioAsignarRevisor").submit(function (event) {
        event.preventDefault();
        var error = false;

        $('#formularioAsignarRevisor .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {
            $("#estado").html("").removeClass('alerta');
            var filas = 0;

            $.post("<?php echo URL ?>ModificacionProductoRia/RevisionSolicitudesProducto/guardarAsignacionRevisor",
                {
                    revisorAsignado : $("#identificador_revisor").val(),
                    nombreRevisorAsignado : $("#identificador_revisor option:selected").text(),
                    idSolicitud : $("#solicitudes").val(),
                    tipoSolicitud : tipoSolicitud,
                    tipoInspector : tipoInspector
                },
                function (data) {
                    if (data.validacion == 'Fallo'){
                        mostrarMensaje(data.resultado,"FALLO");
                        setTimeout(function(){
                            $("#estado").html("").removeClass('alerta');
                        },2000);
                    }else{
                        $("#tTecnicoRevision tbody").append(data.filaRevisorAsignado);
                        mostrarMensaje(data.resultado,"EXITO");
                        limpiarDetalle("revisorAsignado");
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que limpia el valor de los elementos
    function limpiarDetalle(valor){
        $("#identificador_revisor").val("");
    }

    //Funcion que elimina una fila del detalle de revisores asignados
    function fn_eliminarDetalleRevisorAsignado(idAsignacionCoordinador, idSolicitudProducto ) {
        $("#estado").html("").removeClass('alerta');
        $.post("<?php echo URL ?>ModificacionProductoRia/RevisionSolicitudesProducto/eliminarAsignacionRevisor",
            {
                idAsignacionCoordinador: idAsignacionCoordinador,
                idSolicitudProducto : idSolicitudProducto
            },
            function (data) {
                $("#fila" + idAsignacionCoordinador).remove();
            });
    }

    $("#enviarSolicitud").click(function (event) {

        if($("#tTecnicoRevision >tbody >tr").length != 0){
            $("#_actualizar").click();
        }else{
            $("#estado").html("Por favor ingrese un técnico").addClass("alerta");
        }

    });

</script>
