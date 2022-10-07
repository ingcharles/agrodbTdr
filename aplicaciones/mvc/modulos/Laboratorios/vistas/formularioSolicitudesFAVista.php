<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/estilos/estiloSolicitudes.css'>

<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Solicitudes/guardarFA' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Solicitudes</legend>
        <div class="pestania" id="div_paso1">
            <button id="sbm" style="display: none"/>
            <fieldset>
                <legend class='legendMuestras'>Ingrese los siguientes datos</legend>
                <div data-linea="2">
                    <label for="direccion"> Dirección </label> 
                    <input type="text" style="background: transparent; border: 0" value="<?php echo $this->datos['nomDireccion']; ?>"/>
                </div>
                <div data-linea="2">
                    <label>Laboratorios</label> 
                    <input type="text" style="background: transparent; border: 0" value="<?php echo $this->datosLaboratorio->getNombre(); ?>"/>
                </div>
                <div data-linea="3">
                    <label>Servicio</label> 
                    <input type="text" style="background: transparent; border: 0" value="<?php echo $this->datosServicio->getNombre(); ?>"/>
                </div>

                <div data-linea="8">
                    <div id="div_muestreoNacional">
                        <label for="muestreoNacional">Muestreo nacional</label>
                        <input type="text" name="muestreo_nacional" style="background: transparent; border: 0" value="<?php echo $this->modeloSolicitudes->getMuestreoNacional(); ?>"/>
                    </div>
                </div>
            </fieldset>
            <fieldset>			
                <legend>Archivo con las muestras generado por SIFAE</legend>	
                <div>
                    <div id="mensajeExcel" ></div>
                    <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
                    <input type="hidden" id="archivo" name="archivo" value=""/>
                    <input type="hidden" id="extension" name="extension" value=""/>
                    <input type="file" id="file" class="archivo" required
                           accept="application/excel" />
                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
                </div><br>
                <div data-linea="2">
                    <label for="inicio"> Primera fila </label> 
                    <input type="number" id="inicio" name="inicio" value="" min="2" required
                           placeholder="Primera fila de datos"/>
                </div>
                <div data-linea="2">
                    <label for="inicio"> &Uacute;ltima fila </label> 
                    <input type="number" id="fin" name="fin" value="" min="2" required
                           placeholder="&Uacute;ltima fila de datos"/>
                </div>
                <br>
                <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto"
                        data-rutaCarga="<?php echo URL_DIR_LAB_FA ?>">Subir archivo</button>
                <div id="ayudaMuestrasFA">
                    <h1>Formato del archivo Excel</h1> 
                </div>
            </fieldset>
        </div>

        <div data-linea="21">
            <input type="hidden" name="id_direccion" value="<?php echo $this->datosLaboratorio->getFkIdLaboratorio(); ?>"/>
            <input type="hidden" name="id_laboratorio" value="<?php echo $this->datosLaboratorio->getIdLaboratorio(); ?>"/>
            <input type="hidden" name="id_servicio" value="<?php echo $this->modeloDetallesolicitudes->getIdServicio()[0]; ?>"/>
            <input type="hidden" name="id_solicitud" id="id_solicitud" 
                   value="<?php echo $this->modeloSolicitudes->getIdSolicitud() ?>"> 
            <input type="hidden" name="id_persona_activa" id="id_persona_activa"
                   value="<?php echo $this->usuarioActivo() ?>"> 
            <button type="submit" id="bntGuardar" class="guardar"> Guardar solicitud</button>
        </div>
    </fieldset>
</form>

<!-- Código javascript -->
<script type="text/javascript">
<?php echo $this->codigoJS; ?>
    $('.checklist').fSelect();

    // ******************
    // ***** PASO 1 *****
    // ******************
    var valor = 0;
    var tiempoEstimado;
    var celTipoAnalisis;
    var hijosServicioSeleccionado = ''; //una/varias/ninguna
    var hijosAnalisisSeleccionado = ''; //una/varias/ninguna
    var idLaboratorio;
    var idServicio;
    var idServicioNivel1;
    var datosGenerales = '';

    $("#chkacepta").click(function () {
        if ($('#chkacepta').is(':checked')) {
            $(".bsig").removeAttr("disabled");
        } else {
            $(".bsig").attr("disabled", "disabled");
        }
    });

    $(document).ready(function () {
        //Formatea los campos del formulario
        distribuirLineas();
    });

    //ENVIAR A GUARDAR LA SOLICITUD
    $("#formulario").submit(function (event) {
        event.preventDefault();
        if (fn_validarDatos() === 1) {
            var error = false;
            if (!error) {
                var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado == 'exito')
                {
                    fn_filtrarSolicitudes();
                }
            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }
        }
    });
</script>

<script>
    /********************************************************/
    // Para validar los datos
    function fn_validarDatos() {
        var valid = 1;
        if ($("#archivo").val() === "") {
            mostrarMensaje("Dar clic en Subir archivo.", "FALLO");
            valid = 0;
        }
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
        nombre_archivo = "<?php echo 'fa' . (md5(time())); ?>";

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
            estado.html('Formato incorrecto, solo se admite archivos en formato EXCEL');
            archivo.val("");
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

