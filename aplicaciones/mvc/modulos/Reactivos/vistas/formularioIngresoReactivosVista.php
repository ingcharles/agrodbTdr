<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'SolicitudRequerimiento/Guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Ingreso de reactivos</legend>	
        <div data-linea ="1">
            <?php echo $this->requerimientosSolicitados; ?>
        </div>

        <div id="listaSolicitud">
            <table width="100%" id="tbrequerimiento">
                <thead><tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Reactivo</th>
                        <th>Cantidad solicitada</th>
                        <th>Cantidad recibida</th>
                        <th>Unidad</th>
                    </tr></thead>
                <tbody>
                    <?php
                    foreach ($this->itemsRequeridos as $fila)
                    {
                        echo $fila[0];
                    }
                    ?>
                </tbody>
            </table>
            <div data-linea ="8">
                <button type ="submit" class="guardar"> Guardar</button>
            </div>
        </div>

        <hr>

        <label for="observacion"> Observación </label> 
        <div data-linea ="9">
            <textarea id="observacion" name ="observacion"
                      placeholder="Escribir una observación antes de enviar el requerimiento"></textarea>
            <button type ="button"  id="enviarRequerimiento" class="btnenviar">Enviar solicitud</button>
        </div>
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        construirValidador();
        distribuirLineas();
    });

    function eliminarRequerimiento(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo URL ?>Reactivos/SolicitudRequerimiento/borrar/" + id + "/" + <?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>,
            beforeSend: function () {

                $("#listaSolicitud").css("background", "#FFF url(aplicaciones/general/img/cargando.gif) no-repeat 165px");
            },
            success: function (data) {

                $("#listaSolicitud").css("background", "#FFF");
            }
        });
    }

    $("#enviarRequerimiento").click(function () {
        if (confirm("Está seguro de enviar la solicitud?")) {
            $.post("<?php echo URL ?>Reactivos/SolicitudRequerimiento/enviarRequerimiento",
                    {
                        id_solicitud_cabecera:<?php echo $this->idSolicitudCabecera; ?>,
                        observacion: $("#observacion").val()
                    },
            function (data) {
                $("#formulario").empty();
            });

        } else {
            return false;
        }
    });

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
           var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if(respuesta.estado == 'exito')
            {
            fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
