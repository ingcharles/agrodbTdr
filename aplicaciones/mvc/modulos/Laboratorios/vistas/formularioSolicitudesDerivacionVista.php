<script src="<?php echo URL_RESOURCE ?>js/js_comunes.js" type="text/javascript"></script>   <!-- importante! -->
<script src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/casosEspeciales.js" type="text/javascript"></script>
<script src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/estilos/estiloSolicitudes.css'>
<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js" type="text/javascript"></script>
<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/fSelect.css'>

<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Solicitudes/guardarDerivacion' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Solicitudes</legend>
        <div class="pestania" id="div_paso1">
            <button id="sbm" style="display: none"/>
            <fieldset>
                <legend class='legendMuestras'>Ingrese los siguientes datos</legend>
                <div data-linea="2">
                    <label for="direccion"> Direcci&oacute;n </label> 
                    <select id="direccion" name="direccion">
                        <option value="">Seleccionar....</option>
                        <?php
                        echo $this->comboDirecciones();
                        ?>
                    </select>
                </div>
                <div data-linea="2">
                    <label>Laboratorios</label> 
                    <select id="laboratorio" name="laboratorio" disabled="disabled">
                    </select>
                    <input type="hidden" id="id_laboratorio" name="id_laboratorio" />
                </div>
                <div data-linea="3">
                    <label>Servicio</label> 
                    <select id="servicio" name="servicio" disabled="disabled">
                    </select>
                    <input type="hidden" id="id_servicio" name="id_servicio" /> <!-- Necesario para setear en Detallesolicitudes -->
                </div>
                <div data-linea="3">
                    <div id="div_analisis" style="display: none">
                        <label>An&aacute;lisis</label> 
                        <select id="analisis" name="analisis" disabled="disabled">
                        </select>
                    </div>
                </div>
                <div data-linea="4">
                    <div id="div_procedimiento" style="display: none">
                        <label>Procedimiento</label> 
                        <select id="procedimiento" name="procedimiento" disabled="disabled">
                        </select>
                    </div>
                </div>
                <div data-linea="4">
                    <div id="div_muestra" style="display: none">
                        <label>Muestra</label> 
                        <select id="muestra" name="muestra" disabled="disabled">
                        </select>
                    </div>
                </div>

                <div data-linea="5">
                    <div id="div_cantidadLotes" style="display: none">
                        <label>Cantidad de Lotes</label> 
                        <input type="number" id="cantidadLotes" name="cantidadLotes" min="1" size="5"/>
                    </div>
                </div>
                <div data-linea="5">
                </div>

                <div data-linea="6">
                    <label id="lbl_numero">N&uacute;mero de muestras</label> 
                    <!-- Numero de muestras segun lo seleccionado para la derivacion -->
                    <input type="number" id="cantidad" name="cantidad" min="1" size="5" value="<?php echo $this->numMuestras; ?>" readonly/>
                </div>
                <div data-linea="6">
                </div>

                <div data-linea="7">
                    <label>Provincia del laboratorio</label> 
                    <select id="id_laboratorios_provincia" name="id_laboratorios_provincia">
                    </select>
                </div>

                <div data-linea="7">
                </div>

                <div data-linea="8" style="text-align: center">
                    <button type="button" class="mas" onclick="agregar()"> Agregar</button>
                </div>

                <div data-linea="9">
                    <div id="div_tipo_solicitud" class="cDatosGenerales">
                        <label for="tipo_solicitud">Tipo solicitud</label>
                        <select id="tipo_solicitud" name="tipo_solicitud">
                            <option value="">Seleccionar....</option>
                            <?php
                            echo $this->tipoSolicitudRP($this->modeloSolicitudes->getTipoSolicitud());
                            ?>
                        </select>
                    </div>
                </div>
                <div data-linea="9">
                    <div id="div_estadoFisicoMuestra" class="" style="display: none">
                        <label for="estadoFisicoMuestra">Estado f&iacute;sico de la muestra</label>
                        <select id="estadoFisicoMuestra" name="estadoFisicoMuestra">
                            <option value="">Seleccionar....</option>
                            <option value="LIQUIDO">L&Iacute;QUIDO</option>
                            <option value="SOLIDO">S&Oacute;LIDO</option>
                        </select>
                    </div>
                </div>

                <div id="paginacionSolicitar" class="normal"></div>
                <table width="100%" id="grilla" class="lista" ALIGN="CENTER">
                    <thead>
                        <tr>
                            <th colspan="7">Servicios a solicitar</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Tipo de an&aacute;lisis</th>
                            <th>Cantidad</th>
                            <th>Tiempo estimado<br>días laborables</th>
                            <th>Costo U.</th>
                            <th>Subtotal</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- codigo -->      
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right">Subtotal:</td>
                            <td id="subtotalSolicitados" style="text-align: right"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right">Iva:</td>
                            <td id="ivaSolicitados" style="text-align: right"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right">Total:</td>
                            <td id="totalSolicitados" style="text-align: right"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <div id="div_requisitos"></div>
            </fieldset>

            <fieldset>
                <legend class='legendMuestras'>Otros datos</legend>
                <div data-linea="7">
                    <input type="checkbox" id="chkacepta" value=""> <label
                        for="chkacepta">Acepto haber leido el instructivo de toma de muestra</label>
                </div>
                <div data-linea="8">
                    <label for="exoneracion">Tengo exoneraci&oacute;n de pago</label> 
                    <select id="exoneracion" name="exoneracion" required>
                        <option value="">Seleccionar....</option>
                        <?php
                        echo $this->crearComboSINO($this->modeloSolicitudes->getExoneracion());
                        ?>
                    </select>
                </div>
                <div data-linea="8">
                    <div id="div_muestreo_nacional" class="cDatosGenerales">
                        <label for="muestreo_nacional">Muestreo nacional</label>
                        <select id="muestreo_nacional" name="muestreo_nacional">
                            <option value="">Seleccionar....</option>
                            <?php
                            echo $this->crearComboSINO($this->modeloSolicitudes->getMuestreoNacional());
                            ?>
                        </select>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="pestania" id="div_paso2">
            <div id="div_ubicacionMuestra" style="width: 100%;">
                <!-- Campos desplegados con ajax -->
            </div>
        </div>
        <!--Inicio - Datos específicos y tipo de Análisis de la muestra -->
        <div class="pestania" id="div_paso3">
            <div id="div_form_muestra" style="width: 100%;">
                <!-- Campos desplegados con ajax -->
            </div>
            <div id="div_form_analisis" style="width: 100%;">
                <!-- Campos desplegados con ajax -->
            </div>
        </div>
        <!--Fin - Datos específicos y tipo de Análisis de la muestra -->

        <!--Inicio - Datos adiccionales de la solicitud -->
        <div class="pestania" id="div_paso4">
            <fieldset>
                <legend>Informaci&oacute;n adicional</legend>
                <div id="div_form_inf_adiccional" style="width: 100%;">
                    <!-- Campos desplegados con ajax -->
                </div>

                <label for=observacion> Observaci&oacute;n </label>
                <div data-linea="1">
                    <textarea id="observacion" name="observacion"
                              placeholder="Ingrese una observaci&oacute;n"><?php echo $this->modeloLaboratorios->getDescripcion(); ?></textarea>
                </div>
            </fieldset>
        </div>
        <!--Fin - Datos adiccionales de la solicitud -->

        <div data-linea="21">
            <input type="hidden" name="id_solicitud" id="id_solicitud" 
                   value="<?php echo $this->modeloSolicitudes->getIdSolicitud() ?>"> 
            <input type="hidden" name="id_persona_activa" id="id_persona_activa"
                   value="<?php echo $this->usuarioActivo() ?>"> 
            <input type="hidden" name="require_nueva_muestra" id="require_nueva_muestra" 
                   value="<?php echo $this->modeloSolicitudes->getRequiereNuevaMuestra(); ?>"> 
            <input type="hidden" name="muestrasDerivar" id="muestrasDerivar" 
                   value="<?php echo $this->muestras; ?>"> 
            <input type="hidden" name="notificarCliente" id="notificarCliente" 
                   value="<?php echo $this->notificarCliente; ?>"> 
            <input type="hidden" name="usuario_guia_sol_principal" id="usuario_guia_sol_principal" 
                   value="<?php echo $this->usuario_guia_sol_principal; ?>"> 
            <input type="hidden" name="id_detalle_solicitud" id="id_detalle_solicitud" value=""> 
            <input type="hidden" name="labIngredienteActivo" id="labIngredienteActivo" value="">
            <input type="hidden" name="serMarbetes" id="serMarbetes" value="">
            <input type="hidden" name="serFAExcel" id="serFAExcel" value="">
            <input type="hidden" name="serPredeterminados" id="serPredeterminados" value="">

            <button type="submit" id="bntGuardar" class="guardar" style="display: none"> Guardar solicitud</button>
        </div>
    </fieldset>
</form>

<input type="hidden" id="list_id_servicio" value=""/>
<input type="hidden" id="list_cantidad" value=""/>

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
    var continuarFA = false;

    $("#chkacepta").click(function () {
        if ($('#chkacepta').is(':checked')) {
            $(".bsig").removeAttr("disabled");
        } else {
            $(".bsig").attr("disabled", "disabled");
        }
    });

    $(document).ready(function () {
        $(".cDatosGenerales").css('display', 'none');

        fn_sumarCosto();

        camposDinamicos = false;
        // Botones Anterior - Siguiente
        construirAnimacion($(".pestania"));
        //Formatea los campos del formulario
        distribuirLineas();
        $(".bsig").attr("disabled", "disabled");
    });

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#direccion").change(function () {
        $("#analisis").html("");
        $("#div_analisis").hide();
        $("#procedimiento").html("");
        $("#div_procedimiento").hide();
        $("#muestra").html("");
        $("#div_muestra").hide();
        $("#laboratorio").html("");
        $("#servicio").html("");
        var idDireccion = $(this).val();
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboLaboratorios/" + idDireccion, function (data) {
            $("#laboratorio").html(data);
            $("#laboratorio").removeAttr("disabled");
        });
    });
    //Cuando seleccionamos un laboratorio, llenamos el combo de servicios
    $("#laboratorio").change(function () {
        //descheckear si ha seleccionado para editar ya que debe agregar o editar
        $('.chk_detalle').each(function () {
            $(this).prop('checked', false);
        });
        $("#analisis").html("");
        $("#analisis").attr("disabled", "disabled");
        $("#procedimiento").html("");
        $("#procedimiento").attr("disabled", "disabled");
        $("#div_analisis").hide();
        $("#div_procedimiento").hide();
        $("#id_laboratorio").val($(this).val());
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicios/" + $("#id_laboratorio").val(), function (data) {
            $("#servicio").html(data);
            $("#servicio").removeAttr("disabled");
        });
        fn_obtenerDatosLaboratorio();

        fn_buscarProvincias();
    });

    //al seleccionar el laboratorio debe traer los datos atributos para los datos generales de la muestra
    function fn_obtenerDatosLaboratorio() {
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/getDatosLaboratorio/" + $("#id_laboratorio").val(), function (data) {
            if (data.atributos !== '') {
                datosGenerales = data.atributos;
                $("#labIngredienteActivo").val(data.ingredienteActivo);

                $("#serPredeterminados").val(data.serPredeterminados);    //true/false

                fn_cambiarEtiquetaNumero();
                fn_habilitarDatosGenerales();
            }
        }, 'json');
    }

    // Habilitar datos generales
    function fn_habilitarDatosGenerales() {
        //primero volver a configuracion inicial
        $(".cDatosGenerales").css('display', 'none');
        //luego configurar segun el laboratorio seleccionado
        var jsonObj = jQuery.parseJSON(datosGenerales);
        $.each(jsonObj, function (key, value) {
            if (value.id !== 'provOrigenMuestra') {
                $("#div_" + value.id).css('display', value.display);
                if (value.id === 'longitud' & value.display === 'block') {
                    $("#div_bntBuscarMapa").css('display', 'block');
                } else if (value.id === 'longitud' & value.display === 'none') {
                    $("#div_bntBuscarMapa").css('display', 'none');
                }
                if (value.required === 'true')
                {
                    $("#" + value.id).attr('required', value.required);
                }
                else {
                    $("#" + value.id).removeAttr('required');
                }
            } else if ((value.id === 'provOrigenMuestra') & ('<?php echo $this->usuarioInterno; ?>' === '1')) {
                $("#div_" + value.id).css('display', value.display);
            }
        });
        distribuirLineas();
    }

    //Cuando seleccionamos un servicio, llenamos el combo de analisis
    $("#servicio").change(function () {
        $("#analisis").html("");
        $("#div_analisis").hide();
        $("#procedimiento").html("");
        $("#div_procedimiento").hide();
        $("#muestra").html("");
        $("#div_muestra").hide();
        fn_datosServicio();
    });

    //varias acciones por el servicio seleccionado
    function fn_datosServicio() {
        var datos = $("#servicio").find(':selected').attr('data-id');
        idServicio = $("#servicio").val();
        idServicioNivel1 = idServicio;
        hijosServicioSeleccionado = fn_obtener_parametro('hijos', datos);
        valor = fn_obtener_parametro('valor', datos);
        if (hijosServicioSeleccionado === 'varias') {
            $("#div_analisis").show();
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicio/" + idServicio, function (data) {
                $("#analisis").html(data);
                $("#analisis").removeAttr("disabled");
            });
        } else if (hijosServicioSeleccionado === 'ninguna') {
            celTipoAnalisis = $("#servicio :selected").text();
        }
        $("#cantidad").focus();

        //obtener los requisitos
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/datosServicio/" + idServicio, function (data) {
            $("#div_requisitos").html(data.requisitos);
            $("#serMarbetes").val(data.serMarbetes);    //true/false
            $("#serFAExcel").val(data.serFAExcel);    //true/false

            fn_cambiarEtiquetaNumero();     //cambiar etiquetas

            fn_subirExcelFA(idServicio);    //verificar si es para subir Excel

        }, 'json');
    }

    //Cuando seleccionamos un analisis, llenamos el combo de procedimiento
    $("#analisis").change(function () {
        var datos = $(this).find(':selected').attr('data-id');
        $("#procedimiento").html("");
        $("#procedimiento").attr("disabled", "disabled");
        $("#div_procedimiento").hide();
        $("#div_muestra").hide();
        idServicio = $(this).val();
        hijosAnalisisSeleccionado = fn_obtener_parametro('hijos', datos);
        if (hijosAnalisisSeleccionado === 'varias') {
            $("#div_procedimiento").show();
            distribuirLineas();
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicio/" + idServicio, function (data) {
                $("#procedimiento").html(data);
                $("#procedimiento").removeAttr("disabled");
            });
        } else if (hijosAnalisisSeleccionado === 'ninguna') {
            celTipoAnalisis = $("#analisis :selected").text();
        }
    });

    //Cuando seleccionamos un procedimiento, llenamos el tipo de muestra
    $("#procedimiento").change(function () {
        var datos = $(this).find(':selected').attr('data-id');
        $("#muestra").html("");
        $("#muestra").attr("disabled", "disabled");
        $("#div_muestra").hide();
        idServicio = $(this).val();
        hijosProcedimientoSeleccionado = fn_obtener_parametro('hijos', datos);
        if (hijosProcedimientoSeleccionado === 'varias') {
            $("#div_muestra").show();
            distribuirLineas();
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicio/" + idServicio, function (data) {
                $("#muestra").html(data);
                $("#muestra").removeAttr("disabled");
            });
        } else if (hijosProcedimientoSeleccionado === 'ninguna') {
            celTipoAnalisis = $("#procedimiento :selected").text();
        }
    });

    //Cuando seleccionamos un procedimiento
    $("#muestra").change(function () {
        $("#cantidad").val("");
        celTipoAnalisis = $("#muestra :selected").text();
        idServicio = $(this).val();
    });

    //buscar el tiempo de respuesta
    //se ejecuta al presionar agregar
    var tiempoRespuesta;
    function fn_getTiempoRespuesta() {
        if ($("#provOrigenMuestra").is(":visible") & $("#provOrigenMuestra").val() === "") {
            mostrarMensaje("Seleccione la provincia origen muestra", "FALLO");
        } else {
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/datosServicioTiempoRespuesta",
                    {
                        idServicio: $("#servicio").val(), //nivel1
                        idServicioUltimoNivel: idServicio, //ultimo nivel
                        idLocalizacionMuestra: $("#provOrigenMuestra").val(),
                        cantidad: $("#cantidad").val()
                    },
            function (data) {
                if (data.estado === 'EXITO') {
                    tiempoRespuesta = data.tiempoRespuesta;
                    fn_agregarFila();
                } else {
                    $('#modalConfirmacionAgregarAnalisis').modal('show');
                }
            }, 'json');
        }
    }

    function fn_obtener_parametro(par_buscar, str) {
        var result;
        var array = str.split('/');
        $.each(array, function (index, value) {
            var arrayValue = value.split(':');
            if (arrayValue[0] == par_buscar) {
                result = arrayValue[1];
            }
        });
        return result;
    }

    // Función llamada al Agregar item
    function agregar() {
        // verificar nulos
        if ($('#direccion').val() === "") {
            $('#direccion').addClass("alertaCombo");
            mostrarMensaje("Seleccione la Dirección", "FALLO");
            return false;
        }
        if ($('#laboratorio').val() === "") {
            $('#laboratorio').addClass("alertaCombo");
            mostrarMensaje("Seleccione el Laboratorio", "FALLO");
            return false;
        }
        if ($('#servicio').val() === "") {
            $('#servicio').addClass("alertaCombo");
            mostrarMensaje("Seleccione el Servicio", "FALLO");
            return false;
        }
        if (hijosServicioSeleccionado === 'varias') {
            if ($('#analisis').val().trim() === "") {
                $('#analisis').addClass("alertaCombo");
                mostrarMensaje("Seleccione el Análisis", "FALLO");
                return false;
            }
        }
        if (hijosAnalisisSeleccionado === 'varias') {
            if ($('#procedimiento').val().trim() === "") {
                $('#procedimiento').addClass("alertaCombo");
                mostrarMensaje("Seleccione el Procedimiento", "FALLO");
                return false;
            }
        }
        if ($('#cantidad').val().trim() === "") {
            $('#cantidad').addClass("alertaCombo");
            mostrarMensaje("Ingrese la cantidad", "FALLO");
            $('#cantidad').focus();
            return false;
        }
        //verificar que el servicio no esté en la tabla
        var continuar = 1;
        $(".list_id_servicio").each(function () {
            if (idServicio === $(this).val()) {
                continuar = 0;
                mostrarMensaje("Este servicio ya está agregado.", "FALLO");
                return false;
            }
        });
        if (continuar === 1) {
            fn_getTiempoRespuesta();
        }
    }

    //bloquea la dirección y el laboratorio si hay datos en la tabla
    function fn_blockDirLab() {
        var num = $("#grilla tbody").find("tr").length;
        if (num > 0) {
            $('#direccion').attr("disabled", "disabled");
            $('#laboratorio').attr("disabled", "disabled");
        } else {
            $('#direccion').removeAttr("disabled");
            $('#laboratorio').removeAttr("disabled");
        }
    }

    //Función que agrega una fila en la lista
    function fn_agregarFila() {
        cadena = "<tr ALIGN='CENTER'>";
        i = $("#grilla tbody").find("tr").length;
        var campoServicioNivel1 = "<input type='hidden' readonly class='servicioNivel1' value=" + idServicioNivel1 + ">"; //identificador servicio nivel 0
        var campoTipoServicio = "<input type='hidden' readonly class='list_id_servicio' value=" + idServicio + ">"; //identificador del servicio
        cadena = cadena + "<td></td>"; //numeracion
        cadena = cadena + "<td>" + campoServicioNivel1 + campoTipoServicio + celTipoAnalisis + "</td>";
        var campoCantidad = "<input type='hidden' readonly class='list_cantidad' value=" + idServicio + "-" + $("#cantidad").val() + ">"; //cantidad por cada servicio
        cadena = cadena + "<td>" + campoCantidad + "<input type='text' readonly style='background: transparent; border: 0; text-align: center' name='numero_muestras[" + idServicio + "]' value='" + $("#cantidad").val() + "'/></td>";
        cadena = cadena + "<td><input type='text' readonly class='list_tiempo' style='background: transparent; border: 0; text-align: center' name='tiempo[" + idServicio + "]' value='" + tiempoRespuesta + "'/></td>";
        cadena = cadena + "<td style='text-align: right'>$ " + parseFloat(valor).toFixed(4) + "</td>";
        var total = valor * $("#cantidad").val();
        cadena = cadena + "<td style='text-align: right'>$ <span class='list_costo_solicitado'>" + parseFloat(total).toFixed(2) + "</span></td>";
        cadena = cadena + "<td class='borrar'><button type='button' name='eliminar' id='eliminar' class='icono' onClick='fn_eliminar(" + '"grilla"' + ",getIndex(this, " + '"eliminar"' + "))'></button></td>";
        cadena = cadena + "</tr>";
        $("#grilla tbody").append(cadena);
        fn_numerar();
        fn_sumarCosto();
    }

    //Funcion que elimina una fila de la lista 
    function fn_eliminar(nomTabla, indice) {
        respuesta = confirm("Desea eliminar");
        if (respuesta) {
            $("#" + nomTabla + " tbody").find("tr").eq(indice).remove();
            fn_numerar();
            fn_sumarCosto();
        }
    }

    //--------------------------------------------------------
    function getIndex(boton, idElemento) {
        db = document.getElementsByName(idElemento); //Crea un arreglo db con todos los botones eliminar
        ne = db.length; //Cuenta el numero de elementos del arreglo
        for (i = 0; i < ne; i++)
            if (db[i] === boton) //Si el objeto del arreglo es igual al objeto (boton) recibido como parametro devuelve su indice i
                return i;
    }

    //enumera las filas cuando se agrega y se elimina una fila
    function fn_numerar() {
        var total = $('#grilla >tbody >tr').length;
        for (var i = 1; i <= total; i++) {
            document.getElementById("grilla").rows[i + 1].cells[0].innerText = i;
        }
        fn_blockDirLab();
    }

    function fn_sumarCosto() {
        var subTotalS = 0;
        $(".list_costo_solicitado").each(function () {
            subTotalS = parseFloat(subTotalS) + parseFloat($(this).text());
        });
        $("#subtotalSolicitados").html("$ " + subTotalS.toFixed(2));
        var ivaS = subTotalS * 0.<?php echo \Agrodb\Core\Constantes::IVA; ?>;
                ivaS = ivaS.toFixed(2);
        $("#ivaSolicitados").html("$ " + ivaS);
        var totalS = parseFloat(subTotalS) + parseFloat(ivaS);
        $("#totalSolicitados").html("$ " + totalS.toFixed(2));
    }

    //*****************************************//
    //**********  CAMPOS DINÁMICOS ************//
    //*****************************************//
    //DATOS ESPECÍFICOS MUESTRA
    var list_id_servicio;
    var list_cantidad;
    var list_id_servicio_nivel1 = '';
    var nuevo = 1;
    $(".bsig").click(function () {
        var pestaniaActual = $(this).parent().parent();
        //Paso 2
        if ($(".numeroPestania:visible").text() === "Paso 1 de 4Paso 2 de 4") {
            if (fn_validarSolicitud(1, pestaniaActual) === 1) { //validar paso 1
                //en caso de nuevo
                if (!$(".chk_detalle").is(':checked')) {
                    //si es nuevo verifico que haya agregado servicios
                    list_id_servicio = '';
                    list_cantidad = '';
                    list_id_servicio_nivel1 = '';
                    $(".servicioNivel1").each(function () {
                        list_id_servicio_nivel1 = list_id_servicio_nivel1 + $(this).val() + ',';
                    });
                    $(".list_id_servicio").each(function () {
                        list_id_servicio = list_id_servicio + $(this).val() + ',';
                    });
                    $(".list_cantidad").each(function () {
                        list_cantidad = list_cantidad + $(this).val() + ',';
                    });

                    if (list_id_servicio === '') {
                        $(pestaniaActual).show('fast');
                        $(pestaniaActual).next().hide('fast', distribuirLineas);
                        mostrarMensaje("Agregar al menos un servicio o seleccione un servicio para editar.", "FALLO");
                    }
                    $("#list_id_servicio").val(list_id_servicio);
                    $("#list_cantidad").val(list_cantidad);
                }

                //DATOS DE LA MUESTRA
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/getDatosMuestra/" + $("#id_solicitud").val() + "/" + $("#id_laboratorio").val(),
                        function (data) {
                            $("#div_ubicacionMuestra").html(data);
                            distribuirLineas();
                        });
            }
        }

        //Paso 3
        if ($(".numeroPestania:visible").text() === "Paso 2 de 4Paso 3 de 4") {
            if (fn_validarSolicitud(2, pestaniaActual) === 1) { //validar paso 2
                if ($("#id_laboratorio").val() !== null) {
                    $("#id_servicio").val($("#list_id_servicio").val());

                    //DATOS DE LA MUESTRA
                    $.post("<?php echo URL ?>Laboratorios/Solicitudes/camposMuestras/" + $("#id_laboratorio").val() + "/" + $("#id_muestra").val(),
                            function (data) {
                                $("#div_form_muestra").html(data);
                                $('.checklist').fSelect();
                                distribuirLineas();
                            });
                    //TIPO DE ANÁLISIS SOLICITADO
                    if ($('#serMArbetes').val() === 'true') {
                        //MARBETES
                        $.post("<?php echo URL ?>Laboratorios/Solicitudes/camposAnalisisBarbetes",
                                {
                                    cantidadLotes: $("#cantidadLotes").val()
                                },
                        function (data) {
                            $("#div_form_analisis").html(data);
                            distribuirLineas();
                        });
                    } else {
                        //TIPO DE ANÁLISIS SOLICITADO RESTO
                        $.post("<?php echo URL ?>Laboratorios/Solicitudes/camposAnalisis/" + $("#id_laboratorio").val(),
                                {
                                    idSolicitud: $("#id_solicitud").val(),
                                    servicios: $("#list_id_servicio").val(),
                                    cantidades: $("#list_cantidad").val()
                                },
                        function (data) {
                            $("#div_form_analisis").html(data);
                            distribuirLineas();
                        });
                    }
                }
            }
        }
        //Paso 4
        if ($(".numeroPestania:visible").text() === "Paso 3 de 4Paso 4 de 4") {
            distribuirLineas();
            if (fn_validarSolicitud(3, pestaniaActual) === 1) { //validar paso 3
                if (fn_controlTotalMarbetes(pestaniaActual) === 1) { //funcion adicional si es marbetes
                    $(".bsig").attr("disabled", "disabled");
                    $("#bntGuardar").css('display', 'block');

                    var choices = $('input[name="id_servicio_guardados[]"]').map(function () {
                        return this.value;
                    }).get();

                    //INFORMACIÓN ADICCIONAL
                    $.post("<?php echo URL ?>Laboratorios/Solicitudes/parametrosServicio/",
                            {
                                servicios: list_id_servicio_nivel1, //nuevos
                                'servicios_guardados[]': choices,
                                id_detalle_solicitud: $("#id_detalle_solicitud").val()
                            },
                    function (data) {
                        $("#div_form_inf_adiccional").html(data);
                        distribuirLineas();
                    });
                    camposDinamicos = true;
                }
            }
        }
    });

    $(".bant").click(function () {
        $("#bntGuardar").css('display', 'none');
        $(".bsig").removeAttr("disabled")
    });

    //Eliminar el parametro documento de la tabla archivos_adjuntos
    function fn_eliminarParametroArchivo(idArchivosAdjuntos) {
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/eliminarParametroArchivo/" + idArchivosAdjuntos,
                function (data) {
                    mostrarMensaje(data.mensaje, data.estado);
                    if (data.estado === 'EXITO') {
                        $("#div_" + idArchivosAdjuntos).html("");
                    }
                }, 'json');
    }

    //ENVIAR A GUARDAR LA SOLICITUD
    $("#formulario").submit(function (event) {
        event.preventDefault();
        //si es diferente de Fiebre Aftosa
        if (continuarFA === false) {
            //controlar que este en el ultimo paso
            if ($(".numeroPestania:visible").text() === "Paso 4 de 4") {
                if (fn_validarAnexosSubidos() === 1) {
                    var error = false;
                    if (!error) {
                        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                        //Traemos la lista solo si guardo correctamenre
                        if (respuesta.estado == 'exito')
                        {
                            fn_filtrar();   //llama a la funcion de la vista actual listaBandejaRTDerivacionVista
                        }
                    } else {
                        $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
                    }
                }
            }
        } else {    //si es Fiebre Aftosa y acepta que es muestreo nacional
            abrir($(this), event, false);
        }
    });

    //buscar provincias del laboratorio
    function fn_buscarProvincias() {
        var idLaboratorio = $("#laboratorio").val();
        //las provincas donde esta el laboratorio
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboLaboratoriosProvincia/" + idLaboratorio, function (data) {
            $("#formulario #id_laboratorios_provincia").html(data);
        });
    }
</script>

<script>
    var nombre_archivo = "";
    var lab = "";
    var ser = "";
    var parser = "";
    function fn_subirArchivo(lab1, ser1, parser1, codServicio) {
        lab = lab1;
        ser = ser1;
        parser = parser1;

        //nombre_archivo = codServicio + "_" + lab + "_" + ser + "_" + parser;
        nombre_archivo = "<?php echo 'anexo_sol_' . (md5(time())); ?>";

        var boton = $("#btnSubirArchivo" + lab + "_" + ser + "_" + parser);
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
            var subruta = $("#subruta" + lab + "_" + ser + "_" + parser).val();
            $("#p_archi" + lab + "_" + ser + "_" + parser).val(subruta + nombre_archivo);
        };

        this.error = function (msg) {
            estado.html(msg);
            archivo.removeClass("amarillo");
            archivo.addClass("rojo");
        };
    }
</script>
