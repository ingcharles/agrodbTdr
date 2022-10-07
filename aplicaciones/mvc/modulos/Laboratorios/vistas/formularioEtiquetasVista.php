<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>		
<header>
    <h1>Personalizar informe</h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion = 'ParametrosLaboratorios/etiquetasMuestra' data-destino ="detalleItem" 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend><?php echo $this->accion; ?></legend>	
        <fieldset>
            <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

            <div data-linea="1">
                <label for="fNombre">Tipo de campo</label> 
                <select id="tipoCampo" name="tipoCampo" required>
                    <option value="">Seleccionar....</option>
                    <option value="CLIENTE">Datos del cliente</option>
                    <option value="DGMUESTRA">Datos generales de la muestra</option>
                    <option value="DEMUESTRA">Datos especificos de la muestra</option>
                    <option value="ANALISIS">Datos de tipo de análisis</option>
                </select>
            </div>
        </fieldset>
        <table id="tablaCampos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Campo</th>
                </tr>
            </thead>
            <tbody id="tbCampos">
            </tbody>
        </table>

        <div data-linea ="17">
            <input type ="hidden" id="clientesEtiqueta" name ="clientesEtiqueta" value=""/>
            <input type ="hidden" id="generalEtiqueta" name ="generalEtiqueta" value=""/>
            <input type ="hidden" id="especificoEtiqueta" name ="especificoEtiqueta" value=""/>
            <input type ="hidden" id="analisisEtiqueta" name ="analisisEtiqueta" value=""/>
            <button type ="button" id="btnconfiguracion" class="guardar" disabled="disabled"> Guardar configuraci&oacute;n etiquetas</button>
            <button type ="button" id="btnGenerarPdf" class="btnenviar">Generar PDF</button>
            <button type ="button" id="btnGenerarCsv" class="btnenviar">Generar CSV</button>
        </div >
    </fieldset >
</form >
<div id="etiquetascvs"></div>
<iframe id="iframee" width="100%" height="100%"  src="" frameborder="0" allowfullscreen></iframe>
<script type ="text/javascript">
    $(document).ready(function () {

        distribuirLineas();
        $("#btnGenerarPdf").click(function () {
            fn_generar_etiquetas();
        });

        $("#btnGenerarCsv").click(function () {
            fn_generar_etiquetas_cvs();
        });
    });


    $("#btnconfiguracion").click(function () {
        var url = "<?php echo URL ?>Laboratorios/ParametrosLaboratorios/etiquetasMuestra";
        var data = {
            id_parametros_laboratorio: $("#id_parametros_laboratorio").val(),
            clientesEtiqueta: $("#clientesEtiqueta").val(),
            generalEtiqueta: $("#generalEtiqueta").val(),
            especificoEtiqueta: $("#especificoEtiqueta").val(),
            analisisEtiqueta: $("#analisisEtiqueta").val(),
            idLaboratorio: <?php echo $this->laboratorioUsuario(); ?>,
            idOrderTrabajo: $("#idOrderTrabajo").val()

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

                $("#tbCampos").html("");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {
                $("#btnGenerar").removeAttr("disabled");
                $("#btnconfiguracion").attr("disabled", "disabled");
            }
        });
    });

    function fn_getConfigurarCheck() {

        var tipo = $("#tipoCampo").val();
        var atributos = "";
        if (tipo == 'CLIENTE') {
            atributos = $("#clientesEtiqueta").val();
        } else if (tipo == 'DGMUESTRA') {
            atributos = $("#generalEtiqueta").val();
        } else if (tipo == 'ANALISIS') {
            atributos = $("#especificoEtiqueta").val();
        } else {
            atributos = $("#especificoEtiqueta").val();
        }
        if (atributos !== '') {
            var jsonObj = jQuery.parseJSON(atributos);
            $.each(jsonObj, function (key, value) {
                if (value.visible === 'true') {
                    $("#" + value.id).prop("checked", true);
                } else {
                    $("#" + value.id).prop("checked", false);
                }
            });
        }
    }
    function fn_seleccionar() {
        var total = $('#tablaCampos >tbody >tr').length;
        jsonObj = [];

        for (var i = 0; i < total; i++) {
            var campo = $("#tablaCampos tbody").find("tr").eq(i).find("td").eq(1).find("input").attr("id");
            var visible = $("#" + campo).prop("checked") ? 'true' : 'false';
            if (visible == 'true') {

                item = {};
                item [campo] = visible;
                jsonObj.push(item);
            }
        }

        var tipo = $("#tipoCampo").val();
        if (tipo == 'CLIENTE') {
            $("#clientesEtiqueta").val(JSON.stringify(jsonObj));
        } else if (tipo == 'DGMUESTRA') {
            $("#generalEtiqueta").val(JSON.stringify(jsonObj));
        } else {
            $("#especificoEtiqueta").val(JSON.stringify(jsonObj));
        }

    }

    $("#tipoCampo").change(function () {
        fn_cargarCampos();
    });


    function fn_cargarCampos() {
        var url = "<?php echo URL ?>Laboratorios/Datosvalidadosinforme/etiquetas";
        var data = {
            tipo: $("#tipoCampo").val(),
            idOrden: <?php echo $this->idOrdenTrabajo; ?>

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

                $("#tbCampos").html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {
                $("#btnconfiguracion").removeAttr("disabled");
                $("#btnGenerar").attr("disabled", "disabled");
                fn_getConfigurarCheck();
            }
        });

    }
    $("#btnGenerar").click(function () {

        fn_generar_etiquetas();
    });

    /**
     * Genera un archivo pdf con las etiquetas
     * @returns {undefined} */
    function  fn_generar_etiquetas()
    {
        var url = "<?php echo URL ?>Laboratorios/BandejaInformes/generarEtiquetas/" + <?php echo $this->idOrdenTrabajo; ?>;
        $.ajax({
            type: "POST",
            url: url,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (html) {
                $('#iframee').attr('src', html);
                $('#iframee').reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });
    }

    /**
     * Genera un archivo cvs con las etiquetas
     * @returns {undefined} */
    function  fn_generar_etiquetas_cvs()
    {
        var url = "<?php echo URL ?>Laboratorios/BandejaInformes/generarEtiquetasCvs/" + <?php echo $this->idOrdenTrabajo; ?>;
        $.ajax({
            type: "POST",
            url: url,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (html) {
                $('#iframee').attr('src', html);
                $('#iframee').reload();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if (respuesta.estado == 'exito')
            {
                fn_filtrar();
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
