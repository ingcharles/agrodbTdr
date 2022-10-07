<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			
      data-opcion = 'ReactivosBodega/guardar' data-destino ="detalleItem" data-accionEnExito ="NADA" method="post">
    <button id="sbm" style="display: none"/>
    <fieldset>	
        <legend>Subir archivo Excel con saldos de Reactivos</legend>	

        <div data-linea="1">
            <label for="id_bodega"> Bodega </label> 
            <select id="id_bodega" name ="id_bodega" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboBodegasDelUsuario();
                ?>
            </select>
        </div>

        <div data-linea="2">
            <div id="mensajeExcel" ></div>
            <input type="hidden" id="archivo" name="archivo" value=""/> <!-- nombre del archivo -->
            <input type="hidden" id="extension" name="extension" value=""/> <!-- nombre del archivo -->
            <input type="file" id="file" class="archivo" required
                   accept="application/excel" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>

            <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto" 
                    data-rutaCarga="<?php echo URL_DIR_REA_EXCEL ?>">Subir archivo</button>
        </div>

        <label for="descripcion"> Descripci&oacute;n </label> 
        <div data-linea="3">
            <textarea id="descripcion" name="descripcion" placeholder="Descrici&oacute;n del archivo de excel: Ej. Saldo inicial 2018"></textarea>
        </div>

        <div data-linea="4">
            <label for="inicio"> Primera fila con datos a cargar </label> 
            <input type="number" id="inicio" name="inicio" min="2" required
                   placeholder="Primera fila de datos"/>
        </div>

        <div data-linea="4">
            <label for="inicio"> &Uacute;ltima fila con datos a cargar </label> 
            <input type="number" id="fin" name="fin" min="2" required
                   placeholder="&Uacute;ltima fila de datos"/>
        </div>
        <br>

        <div id="ayudaSubirSaldos">
            <h1>Formato del archivo Excel</h1> 
        </div>

        <button type="submit" class="guardar"> Guardar</button>
    </fieldset>
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();

        //ENVIAR A GUARDAR LA SOLICITUD
        $("#formulario").submit(function (event) {
            event.preventDefault();
            if ($("#archivo").val() === "") {
                mostrarMensaje("Presionar en Subir archivo.", "FALLO");
            } else {
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
            }
        });
    });

    /// Para validar los campos de cada paso
    function fn_validar() {
        var valid = 1;
        $('#formulario').find('select, textarea, input, file').each(function () {
            var inpObj = document.getElementById($(this).attr('id'));
            if (inpObj !== null) {
                if (!inpObj.checkValidity()) {
                    document.getElementById("sbm").click();
                    valid = 0;
                    return false;
                }
            }
        });
        var inicio = parseFloat($("#inicio").val());
        var fin = parseFloat($("#fin").val());
        if (fin < inicio) {
            mostrarMensaje("Última fila debe ser mayor o igual a Primera fila.", "FALLO");
            valid = 0;
            var input = document.getElementById('inicio');
            input.oninvalid = function (event) {
                event.target.setCustomValidity('Última fila debe ser mayor o igual a Primera fila');
            }
        }
        return valid;
    }

    function fn_subirArchivo() {
        if (fn_validar() === 1) {
            nombre_archivo = "<?php echo 'reactivos' . (md5(time())); ?>";

            $("#archivo").val(nombre_archivo);

            var boton = $("#btnSubirArchivo");
            var archivo = boton.parent().find(".archivo");
            var rutaArchivo = boton.parent().find(".rutaArchivo");
            var extension = archivo.val().split('.');
            $("#extension").val(extension[extension.length - 1]);
            var estado = boton.parent().find(".estadoCarga");
            if (extension[extension.length - 1].toUpperCase() == 'XLS' | extension[extension.length - 1].toUpperCase() == 'XLSX') {

                subirArchivo(
                        archivo
                        , nombre_archivo
                        , boton.attr("data-rutaCarga")
                        , rutaArchivo
                        , new carga(estado, archivo, boton)
                        );
            } else {
                estado.html('Formato incorrecto, solo se admite archivos en formato xls/xlsx');
                archivo.val("");
            }
        }
    }

    function carga(estado, archivo, boton) {
        this.esperar = function (msg) {
            estado.html("Cargando el archivo...");
            archivo.addClass("amarillo");
        };

        this.exito = function (msg) {
            estado.html("El archivo ha sido cargado.");
            archivo.removeClass("amarillo");
            archivo.addClass("verde");
            boton.attr("disabled", "disabled");
        };

        this.error = function (msg) {
            estado.html(msg);
            archivo.removeClass("amarillo");
            archivo.addClass("rojo");
        };
    }
</script>
