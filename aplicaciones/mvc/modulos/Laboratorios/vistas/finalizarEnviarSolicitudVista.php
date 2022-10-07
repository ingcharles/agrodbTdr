<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Solicitudes/guardarFinalizar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <!--Inicio - Finalizar y Enviar la solicitud -->

    <div id="divFacturacion" style="display: none;">
        <fieldset>
            <legend>DATOS PARA FACTURAR</legend>
            <div data-linea="2">
                <label> Selecione la Provincia del laboratorio </label> 
                <select id="id_laboratorios_provincia" name="id_laboratorios_provincia">
                    <?php echo $this->comboLaboratoriosProvincia($this->modeloMuestras->getIdLaboratorio()); ?>
                </select>
            </div>
            <div>
                <label>¿Requiere que la factura salga a nombre de un tercero?</label> 
                <input type="radio" id="opFactTercero1"
                       name="opFactTercero" value="1" <?php echo ($this->usuarioInterno) ? "checked" : ""; ?>/>
                <label for="opFactTercero1">SI</label>
                <input type="radio" id="opFactTercero2"
                       name="opFactTercero" value="0" <?php echo ($this->usuarioInterno) ? "disabled" : ""; ?>/>
                <label for="opFactTercero2">NO</label>
            </div>

            <div id="divFactTercero" style="display: none;">
                <fieldset>

                    <div data-linea="1">
                        <input type="radio" id="opTipo1"
                               name="tipo_identificacion" value="05" class="datoRequerido">
                        <label for="opTipo1">C&eacute;dula</label>
                        <input type="radio" id="opTipo2"
                               name="tipo_identificacion" value="04" class="datoRequerido">
                        <label for="opTipo2">RUC</label>
                    </div>

                    <div data-linea="1">
                        <label>C&eacute;dula/RUC</label> 
                        <input type="text" id="ci_ruc" name="ci_ruc" class="datoRequerido"
                               value="<?php echo $this->modeloSolicitudes->getPersonas()->getCiRuc(); ?>"
                               placeholder="C&eacute;dula/Ruc de la persona natural o jur&iacute;dica para emitir la factura" maxlength="16"/>
                    </div>
                    <div data-linea="2">
                        <label>Nombre y apellido/empresa</label> 
                        <input type="text" id="nombre_persona" name="nombre" class="datoRequerido"
                               value="<?php echo $this->modeloSolicitudes->getPersonas()->getNombre(); ?>"
                               placeholder="Propietario de la muestra" maxlength="128"/>
                    </div>
                    <div data-linea="3">
                        <label>Direcci&oacute;n</label> 
                        <input type="text" id="direccion" name="direccion" class="datoRequerido"
                               value="<?php echo $this->modeloSolicitudes->getPersonas()->getDireccion(); ?>"
                               placeholder="Direcci&oacute;n del Propietario" maxlength="128"/>
                    </div>
                    <div data-linea="4">
                        <label>Correo electr&oacute;nico</label> 
                        <input type="email" id="email" name="email" class="datoRequerido"
                               value="<?php echo $this->modeloSolicitudes->getPersonas()->getEmail(); ?>"
                               placeholder="Correo electr&oacute;nico del Propietario" maxlength="64"/>
                    </div>
                    <div data-linea="5">
                        <label>Tel&eacute;fono</label> 
                        <input type="text" id="telefono" name="telefono" class="datoRequerido"
                               value="<?php echo $this->modeloSolicitudes->getPersonas()->getTelefono(); ?>"
                               placeholder="Tel&eacute;fono del Propietario" maxlength="16"/>
                    </div>
                </fieldset>
            </div>
        </fieldset>
    </div>

    <div id="divExoneracion" style="display: none;">
        <fieldset>
            <legend>DATOS EXONERACI&Oacute;N DE PAGO</legend>

            <div data-linea="1">
                <label for="file">Oficio/memorando de exoneraci&oacute;n</label>
                <input type="hidden" id="nom_archivo_oficio" name="nom_archivo_oficio" value=""/>
                <input type="file" class="archivo datoRequeridoExoneracion" accept="application/pdf"/>
                <div class="estadoCarga">En espera de archivo... (Tama&nacute;o m&aacute;ximo' <?php ini_get('upload_max_filesize') ?> 'B)</div>
                <!--<label >Des...</label>';-->
                <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto" data-rutaCarga="<?php echo $this->rutArcExo; ?>">Subir archivo</button>
            </div>
            <div data-linea="2">
                <label>N&uacute;mero de memorando</label> 
                <input type="text" id="oficio_exoneracion" name="oficio_exoneracion" class="datoRequeridoExoneracion"
                       value="<?php echo $this->modeloSolicitudes->getOficioExoneracion(); ?>" style="text-transform:uppercase;"
                       placeholder="En caso de existir exoneracion se debe registrar el numero de oficio o memo donde se autoriza" maxlength="32"/>
            </div>
            <div data-linea="2">
                <label>Cantidad de muestras exoneradas</label> 
                <input type="number" id="num_muestras_exoneradas" name="num_muestras_exoneradas" class="datoRequeridoExoneracion"
                       value="<?php echo $this->modeloSolicitudes->getNumMuestrasExoneradas(); ?>"
                       placeholder="N&uacute;mero de muestras exoneradas" maxlength="4" min="1"/>
            </div>
            <div data-linea="3">
                <label>Saldo</label> 
                <input type="text" id="saldoAhora" name="saldoAhora" value="" readonly style="background-color: transparent; border: 0"
                       placeholder="" maxlength="32"/>
            </div>
            <div data-linea="3"></div>

            <div id="divDatosMemo" style="display: none">
                <fieldset>
                    <legend>DETALLE CONSUMO MEMO INGRESADO</legend>
                    <table width="100%" id="tablaDatosMemo" class="lista" ALIGN="CENTER">
                        <thead>
                            <tr>
                                <th colspan="7">Servicios solicitados</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>C&oacute;digo</th>
                                <th>Fecha registro</th>
                                <th>Oficio exoneraci&oacute;n</th>
                                <th>Cantidad de muestras exoneradas</th>
                                <th>Total muestras en la solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- codigo -->      
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" style="text-align: right">Total:</td>
                                <td style="text-align: right">
                                    <input type="text" id="total" name="total" value="0" readonly style="background-color: transparent; border: 0; text-align: right"/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align: right">Saldo:</td>
                                <td style="text-align: right">
                                    <input type="text" id="saldo" name="saldo" value="" readonly style="background-color: transparent; border: 0; text-align: right"/>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </fieldset>
            </div>
        </fieldset>
    </div>

    <div data-linea="21">
        <input type="hidden" name="id_solicitud" id="id_solicitud"
               value="<?php echo $this->modeloSolicitudes->getIdSolicitud() ?>"> 
        <input type="hidden" name="exoneracion" id="exoneracion"
               value="<?php echo $this->modeloSolicitudes->getExoneracion() ?>"> 
        <button type="submit" id="btnEnviar" class="fas fa-share-square"> Enviar Solicitud</button>
    </div>
</form>

<!-- Código javascript -->
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        //Formatea los campos del formulario
        distribuirLineas();

        //si tiene exoneracion
        if ("<?php echo $this->modeloSolicitudes->getExoneracion(); ?>" === "SI") {
            $('#divFacturacion').hide();
            $("#opFactTercero1").attr("required", false);
            $("#opFactTercero2").attr("required", false);
            $('#divExoneracion').show();
            $(".datoRequeridoExoneracion").attr("required", true);
            $(".datoRequerido").attr("required", false);
            distribuirLineas();
        } else { //facturacion
            $('#divFacturacion').show();
            $("#opFactTercero1").attr("required", true);
            $("#opFactTercero2").attr("required", true);
            $('#divExoneracion').hide();
            $(".datoRequeridoExoneracion").attr("required", false);
            $("#id_laboratorios_provincia").attr("required", true);
            if ("<?php echo $this->usuarioInterno; ?>" === "1") {
                $('#divFactTercero').show();    //Obligatorio mostrar facturación para tercera persona
                $(".datoRequerido").attr("required", true);
            }
            distribuirLineas();
        }

        $("#oficio_exoneracion").keyup(function () {
            $("#num_muestras_exoneradas").val("");
            $("#saldoAhora").val("");
            $('#divDatosMemo').hide();
            $("#total").val("0");
            $("#saldo").val("");
        });

        $("#num_muestras_exoneradas").focusout(function () {
            fn_validar();
        });

        function fn_validar() {
            var continuar = 1;
            //sumaTotal = total registrado + total análisis en esta solicitud
            var sumaTotal = parseInt($("#total").val()) + parseInt($("#totalSolicitados").val());
            var saldoAhora = parseInt($("#num_muestras_exoneradas").val()) - sumaTotal;
            $("#saldoAhora").val(saldoAhora);
            if (saldoAhora < 0) {
                continuar = 0;
                mostrarMensaje("La cantidad de muestras exoneradas debe igual o mayor al total de muestras de la solicitud.", "FALLO");
            }
            return continuar;
        }

        //ENVIAR A GUARDAR LA SOLICITUD
        $("#formulario").submit(function (event) {
            event.preventDefault();
            if ("<?php echo $this->modeloSolicitudes->getExoneracion(); ?>" === "SI" & $("#nom_archivo_oficio").val() === "") {
                mostrarMensaje("Dar clic en Subir archivo.", "FALLO");
            } else {
                if (fn_validar() === 1) {
                    var error = false;
                    if (!error) {
                        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado == 'exito')
                {
                    $("#fEstado").val('');
                    $("#fCodigo").val('');
                   fn_filtrarSolicitudes();
                }

                    } else {
                        $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
                    }
                }
            }
        });

        $('input[name=opFactTercero]').click(function () {
            if ($(this).val() === '1') {
                $('#divFactTercero').show();
                $(".datoRequerido").attr("required", true);
                distribuirLineas();
            } else {
                $(".datoRequerido").attr("required", false);
                $('#divFactTercero').hide();
            }
        });
        $('#ci_ruc').focusout(function () {
            if ($('#ci_ruc').val().trim() !== "") {
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/getDatosPersona/" + $('#ci_ruc').val(),
                        function (data) {
                            $('#nombre_persona').val(data.nombre);
                            $('#direccion').val(data.direccion);
                            $('#telefono').val(data.telefono);
                            $('#email').val(data.email);
                        }, 'json');
            }
        });

        //para saber si existe un usuario receptor de la provincia seleccionada
        $('#id_laboratorios_provincia').change(function () {
            if ($(this).val() !== "") {
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/buscarUsuarioRecaudador/" + $(this).val(),
                        function (data) {
                            if (data.estado === 'ERROR') {
                                $("#btnEnviar").attr("disabled", "disabled");
                                mostrarMensaje(data.mensaje, "FALLO");
                            } else {
                                $("#btnEnviar").removeAttr("disabled");
                            }
                        }, 'json');
            }
        });

        //Retorna la tabla con el número de registros sobre el memo ingresado
        $('#oficio_exoneracion').focusout(function () {
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/buscarDatosMemo/" + $('#oficio_exoneracion').val(),
                    function (data) {
                        if (data.tabla !== "") {
                            $('#divDatosMemo').show();
                            $("#tablaDatosMemo tbody").html(data.tabla);
                            $("#total").val(data.total);
                            $("#saldo").val(data.saldo);
                            $("#num_muestras_exoneradas").val("");
                            fn_validar();
                        } else {
                            $('#divDatosMemo').hide();
                            $("#total").val(0);
                            $("#saldo").val("");
                        }
                    }, 'json');
        });
    });

    function fn_subirArchivo() {
        nombre_archivo = "<?php echo 'oficio_exoneracion-' . (md5(time())); ?>";

        $("#nom_archivo_oficio").val(nombre_archivo + '.pdf');

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