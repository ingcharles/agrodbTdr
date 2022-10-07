<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/estilos/estiloModal.css'>
<link rel='stylesheet' href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<header>
    <h1>Validaci&oacute;n an&aacute;lisis muestras</h1>
</header>

<form id='formularioMuestras' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='BandejaResponsableTecnico/guardarValidacion' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Validaci&oacute;n del an&aacute;lisis de las muestras</legend>
        <fieldset>
            <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
            <div data-linea="10">
                <label for="fcodigo">C&oacute;digo</label> 
                <input type="text" id="fcodigo" name="fcodigo" placeholder="C&oacute;digo de la muestra"/>
            </div>

            <div data-linea="10">
                <label for="fAnalisis">Análisis</label> 
                <input type="text" id="fAnalisis" name="fAnalisis" placeholder="Nombre an&aacute;lisis"/>
            </div>

            <button type="button" id="btnFiltrarRA" class="fas fa-search"> Filtrar lista</button>
            <button type="button" id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
        </fieldset>
        <div id="paginacionMuestras" class="normal" style="width: 100%"></div>
        <table id="tablaItemsMuestras" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th title="Opci&oacute;n para ver datos de la muestra en ventana modal"></th>
                    <th title="C&oacute;digo de la muestra generado por el sistema">C&oacute;digo</th>
                    <th title="C&oacute;digo de campo de la muestra"><?php echo $this->obtenerAtributoLaboratorio($this->idLaboratorio, 'm_cod_campo'); ?></th>
                    <th title="Nombre del an&aacute;lisis">An&aacute;lisis</th>
                    <th title="Seleccionar si desea aprobar la muestra">Aprobar?</br>Todos<input type='checkbox' value="on" onclick="fn_selectAllCmbByClass(this, 'cls_selectAllCmbByClass')" /></th>
                    <th title="Observaci&oacute;n de la validaci&oacute;n de los resultados de los an&aacute;lisis">Observaci&oacute;n</th>
                    <th title="Se podr&aacute; aplicar una nueva prueba">Requiere confimaci&oacute;n de an&aacute;lisis</th>
                    <th title="Para ver los resultados ingresados">Ver resultados</th>
                    <th title="Estado de la muestra">Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5">Dar clic en Filtrar lista para mostrar los datos</td></tr>
            </tbody>
        </table>
        <div data-linea="1">
            <button type="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
    <input type="hidden" name="identificador" id="identificador" value="<?php echo $this->usuarioActivo() ?>">
</form>

<!-- Modal para desplegar los campos dinámicos del resultados -->
<div class="modal fade" id="modalResultado" role="dialog">
    <div class="modal-dialog">
        <div class="center-block" class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ver resultados</h4>
                </div>
                <div class="form-horizontal">
                    <div class="modal-body">
                        <form>
                            <span class="clearfix"></span>
                            <div id="divCamposResultado" class="center-block"></div>
                            <input type="hidden" id="id_orden_trabajo" name="id_orden_trabajo" value="<?php echo $this->idOrdenTrabajo ?>"/>
                        </form>
                    </div>
                </div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //es necesario mostrar los datos sin la fn_filtrar
        $('.checklist').fSelect();
        $("#btnFiltrarRA").click(function () {
            fn_filtrarRA();
        });
    });
    /*##########################PAGINACION################################*/
    function construirPaginacionMuestras(elemento, itemsFiltrados) {
        itemsMuestras = new Array();
        $(elemento).html(
                '<span>' +
                'Mostrar' +
                '<select id="itemsAMostrarMuestras">' +
                '	<option value="10">10 items</option>' +
                '	<option value="15">15 items</option>' +
                '	<option value="30">30 items</option>' +
                '	<option value="*">Todos</option>' +
                '</select>' +
                'en pantalla.' +
                '</span>' +
                '<span style="float:right;">' +
                '	Items del ' +
                '	<select id="paginaMuestras">' +
                '	</select>' +
                '	de <span id="totalItemsMuestras"></span>' +
                '	<button id="pagAnteriorMuestras" type="button">&lt;</button>' +
                '	<button id="pagSiguienteMuestras"type="button">&gt;</button>' +
                '</span>'
                );
        itemsMuestras = itemsFiltrados;
        $("#itemsAMostrarMuestras option[value='*']").attr("value", itemsMuestras.length);
        $("#totalItemsMuestras").html(itemsMuestras.length);
        construirListaPaginasMuestras();
        mostrarItemsMuestras(0);
    }

    function mostrarItemsMuestras(itemInicial) {
        $("#tablaItemsMuestras tbody").html("");
        desplazamientoMuestras = parseInt($("#itemsAMostrarMuestras").val());
        itemFinalMuestras = ((itemInicial + desplazamientoMuestras < itemsMuestras.length) ? itemInicial + desplazamientoMuestras : itemsMuestras.length) - 1;
        for (var contador = itemInicial; contador <= itemFinalMuestras; contador++)
            $("#tablaItemsMuestras tbody").append(itemsMuestras[contador]);
    }

    function construirListaPaginasMuestras() {
        $("#paginaMuestras").html("");
        numeroOpcionesMuestras = itemsMuestras.length / parseInt($("#itemsAMostrarMuestras").val());
        desplazamientoMuestras = parseInt($("#itemsAMostrarMuestras").val());
        for (var contador = 0, itemInicial = 1; contador < numeroOpcionesMuestras; contador++, itemInicial = itemInicial + desplazamientoMuestras) {
            itemFinalMuestras = (itemInicial + desplazamientoMuestras < itemsMuestras.length) ? itemInicial + desplazamientoMuestras - 1 : itemsMuestras.length;
            $("#paginaMuestras").append("<option value='" + (itemInicial - 1) + "'>" + (itemInicial) + "-" + (itemFinalMuestras) + "</option>");
        }
    }

    $("#ventanaAplicacion").on("change", " #itemsAMostrarMuestras", function () {
        construirListaPaginasMuestras();
        mostrarItemsMuestras(parseInt($("#paginaMuestras").val()));
    });

    $("#ventanaAplicacion").on("change", " #paginaMuestras", function () {
        mostrarItemsMuestras(parseInt($("#paginaMuestras").val()));
    });

    $("#ventanaAplicacion").on("click", " #pagSiguienteMuestras", function () {
        mostrarNuevaPagina($("#paginaMuestras option[value='" + $("#paginaMuestras").val() + "']").next().attr("value"));
    });

    $("#ventanaAplicacion").on("click", " #pagAnteriorMuestras", function () {
        mostrarNuevaPagina($("#paginaMuestras option[value='" + $("#paginaMuestras").val() + "']").prev().attr("value"));
    });

    function mostrarNuevaPagina(nuevaOpcion) {
        if (nuevaOpcion != undefined) {
            $("#paginaMuestras").val(nuevaOpcion);
            mostrarItemsMuestras(parseInt($("#paginaMuestras").val()));
        }
    }

    // Función para filtrar
    function fn_filtrarRA() {
        $("#paginacionMuestras").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/BandejaResponsableTecnico/listarDatosValidacion/" + $("#id_orden_trabajo").val(),
                {
                    codigo: $("#fcodigo").val(),
                    analisis: $("#fAnalisis").val()
                },
        function (data) {
            construirPaginacionMuestras($("#paginacionMuestras"), JSON.parse(data));
            $('.checklist').fSelect();
        });
    }

    function fn_camposResultado(idRecepcionMuestras, idServicio, s_rama) {
        var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/verResultadosIngresados";
        var data = {
            idRecepcionMuestras: idRecepcionMuestras,
            idServicio: idServicio,
            rama: s_rama
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (html) {
                $('#modalResultado').modal('show');
                $("#divCamposResultado").html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });

    }

    $("#bntGuardarResultadoAnalisis").click(function () {
        var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/guardar";
        $.ajax({
            type: "POST",
            url: url,
            data: $("#frmResultados").serialize(),
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (html) {
                $('#modalResultado').modal('hide');
                fn_filtrarRA();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });
    });

    //Para selecionar todos los combos
    function fn_selectAllCmbByClass(thisCheck, nombreClase) {
        var isChecked = $(thisCheck).is(":checked");
        if (isChecked === true) {
            $("." + nombreClase + " option[value='APROBADO']").prop('selected', true);
        } else {
            $("." + nombreClase + " option[value='NO APROBADO']").prop('selected', true);
        }
    }

    $("#formularioMuestras").submit(function (event) {
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

