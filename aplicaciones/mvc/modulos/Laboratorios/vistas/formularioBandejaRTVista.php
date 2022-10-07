<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<header>
    <h1>Verificaci&oacute;n muestras</h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='BandejaResponsableTecnico/guardarMuestras' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Verificaci&oacute;n de idoneidad de las muestras</legend>
        <table id="muestras">
            <thead>
                <tr>
                    <th>#</th>
                    <th title="Opci&oacute;n para ver datos de la muestra en ventana modal">Datos</br>Muestra</th>
                    <th title="C&oacute;digo de campo de la muestra"><?php echo $this->obtenerAtributoLaboratorio($this->idLaboratorio, 'm_cod_campo'); ?></th>
                    <th title="Nombre del an&aacute;lisis">An&aacute;lisis</th>
                    <th title="Para seleccionar si la muestra es id&oacute;nea o no">Muestra Id&oacute;nea?</br>Todos<input type='checkbox' value="on" onclick="fn_selectAllCmbByClass(this, 'cls_selectAllCmbByClass')" /></th>
                    <th title="Fecha de inicio del an&aacute;lisis">Fecha inicio An&aacute;lisis</th>
                    <th title="Observaci&oacute;n de la verificaci&oacute;n">Observaci&oacute;n</th>
                    <th title="Estado de la muestra">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->itemsMuestras as $muestra)
                {
                    echo $muestra[0];
                }
                ?>
            </tbody>
        </table>

        <div data-linea="1">
            <input type ="hidden" id="id_solicitud" name ="id_solicitud" value="<?php echo $this->modeloSolicitudes->getIdSolicitud(); ?>"/>
            <input type ="hidden" id="usuario_guia" name ="usuario_guia" value="<?php echo $this->modeloSolicitudes->getUsuarioGuia(); ?>"/>
            <input type ="hidden" id="idLaboratorio" name ="idLaboratorio" value="<?php echo $this->idLaboratorio; ?>"/>
            <input type ="hidden" id="idOrdenTrabajo" name ="idOrdenTrabajo" value="<?php echo $this->idOrdenTrabajo; ?>"/>
            <input type ="hidden" id="notificar" name ="notificar" value="0"/>
            <textarea id="atributos" style="display: none"><?php echo $this->modeloLaboratorios->getAtributos(); ?></textarea>
        </div>

        <div id="divAdjuntar" style="display: none;">
            <fieldset>
                <legend>Archivos adjuntos</legend>

                <div data-linea="1">
                    <label for="file">Fotograf&iacute;a de la muestra</label> 
                    <input type="hidden" id="archivo" name="archivo" value=""/>
                    <input type="file" id="file" class="archivo" accept="application/pdf"/>
                    <div class="estadoCarga">En espera de archivo... (Tama&nacute;o m&aacute;ximo' <?php ini_get('upload_max_filesize') ?> 'B)</div>
                    <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto" 
                            data-rutaCarga="<?php echo URL_ADJUNTOS_NO_IDONEAS; ?>">Subir archivo</button>
                </div>               
            </fieldset>
        </div>

        <div id="div_temperatura" style="display: none">
            <label for="temperatura">Temperatura recepci&oacute;n muestra/s en el laboratorio °C:</label>
            <input type ="number" id="temperatura" name ="temperatura" value="" step="0.01" value="0.00" placeholder="0.00" min="0.01" lang="en"/>
        </div>

        <?php if ($this->modeloOrdenTrabajo->getEstado() == 'ACTIVA'): ?>
            <button type="submit" class="guardar"> Guardar</button>
        <?php elseif ($this->modeloOrdenTrabajo->getEstado() == 'EN PROCESO' & $this->idoneaEnProceso) : ?>
            <button type="submit" class="guardar"> Guardar</button>
        <?php endif; ?>
    </fieldset>
</form>

<!-- Código javascript -->
<script type="text/javascript">
<?php echo $this->codigoJS ?>
    distribuirLineas();

    fn_habilitarCamposLaboratorio($("#atributos").text());

    $("#formulario").submit(function (event) {
        event.preventDefault();
        if ($("#notificar").val() === "1" & $("#archivo").val() === "") {
            mostrarMensaje("Seleccionar y dar clic en Subir archivo.", "FALLO");
        } else {
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
        }
    });

    //Para selecionar todos los combos
    //no se hace uso del js_comunes para usar la funcion fn_verificarSI()
    function fn_selectAllCmbByClass(thisCheck, nombreClase) {
        var isChecked = $(thisCheck).is(":checked");
        if (isChecked === true) {
            $("." + nombreClase + " option[value=SI]").prop('selected', true);
        } else {
            $("." + nombreClase + " option[value=NO]").prop('selected', true);
        }
        fn_verificarSI();
    }

    //Si ha seleccionado NO en cualquier muestra, entonces mostrar para adjuntar archivos
    function fn_verificarSI() {
        var mostrarAdjuntar = '0';
        $('.esIdonea').each(function () {
            if ($(this).val() === 'NO') {
                mostrarAdjuntar = '1';
                return false;
            }
        });
        if (mostrarAdjuntar === '1') {
            $("#divAdjuntar").css('display', 'block');
            $("#notificar").val(1);
            $("#file").attr('required', 'required');
            distribuirLineas();
        } else {
            $("#divAdjuntar").css('display', 'none');
            $("#notificar").val(0);
            $("#file").removeAttr('required');
        }
    }
</script>

<script>
    var nombre_archivo = "";
    function fn_subirArchivo() {
        nombre_archivo = "<?php echo 'reporte-tecnico-' . (md5(time())); ?>";

        $("#archivo").val(nombre_archivo + '.pdf');

        var boton = $("#btnSubirArchivo");
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                    archivo
                    , nombre_archivo
                    , boton.attr("data-rutaCarga")
                    , rutaArchivo
                    , new carga(estado, archivo, boton)
                    );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
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