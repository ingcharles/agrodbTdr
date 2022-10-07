<form id='formularioProforma'>
    <fieldset>
        <legend>GENERAR PROFORMA</legend>
        <button id="sbmProforma" style="display: none">
        </button>

        <div class="form-horizontal">
            <div class='row'>
                <div class="form-group col-md-6">
                    <label for="" class="col-lg-4 control-label">Contacto</label>
                    <div class="col-lg-8">
                        <input type="text" id="contacto_proforma" name="contacto_proforma" required class="form-control"
                               value="" placeholder="Nombre del contacto de la instituci&oacute;n que solicita la proforma" maxlength="64"/>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="" class="col-lg-4 control-label">Tel&eacute;fono Contacto</label>
                    <div class="col-lg-8">
                        <input type="text" id="telefono_proforma" name="telefono_proforma" class="form-control"
                               value="" required
                               placeholder="Telefono/Extension del contacto de la institucion que solicita la proforma" maxlength="16"/>
                    </div>
                </div>
            </div>

            <div class='row'>
                <div class="form-group col-md-6">
                    <label for="" class="col-lg-4 control-label">N&uacute;mero de muestras</label> 
                    <div class="col-lg-8">
                        <input type="number" id="numero_muestras" name="numero_muestras" class="form-control"
                               value="" required maxlength="16" min="1"/>
                    </div>
                </div>
            </div>

            <hr>
            <?php
            if ($this->usuarioInterno)
            {
                echo "<input type='hidden' id='opProfTercero' name='opProfTercero' value='SI'>";
            } else
            {
                echo '<label>¿Se requiere realizar la proforma a nombre de un tercero?</label>';
                echo '<select id="opProfTercero" name="opProfTercero" required>
                <option value="">Seleccione..</option>
                    <option value="SI">SI</option>
                        <option value="NO">NO</option>
                </select>';
            }
            ?>
            <hr>
            <div id="divProfTercero" style="display: <?php echo ($this->usuarioInterno) ? 'block' : 'none'; ?>">
                <h3>DATOS DE LA TERCERA PERSONA</h3>
                <div class='row'>
                    <div class="form-group col-md-6">
                        <label for="" class="col-lg-4 control-label">C&eacute;dula/RUC</label> 
                        <div class="col-lg-8">
                            <input type="text" id="ci_ruc" name="ci_ruc" class="datoRequerido form-control"
                                   value="" required
                                   placeholder="C&eacute;dula/Ruc de la persona natural o jur&iacute;dica para emitir la proforma" maxlength="16"/>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="" class="col-lg-4 control-label">Nombre y Apellido/Empresa</label>
                        <div class="col-lg-8">
                            <input type="text" id="nombre_persona" name="nombre" class="datoRequerido form-control"
                                   value=""
                                   placeholder="Nombre y apellido o nombre de la empresa" maxlength="128"/>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class="form-group col-md-6">
                        <label for="" class="col-lg-4 control-label">Provincia </label>
                        <div class="col-lg-8">
                            <select id="id_localizacion" name="id_localizacion" class="datoRequerido form-control">
                                <option value="">Seleccionar....</option>
                                <?php
                                echo $this->comboProvinciasEc();
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="" class="col-lg-4 control-label">Direcci&oacute;n</label> 
                        <div class="col-lg-8">
                            <input type="text" id="direccion" name="direccion" class="datoRequerido form-control"
                                   value=""
                                   placeholder="Direcci&oacute;n" maxlength="128"/>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class="form-group col-md-6">
                        <label for="" class="col-lg-4 control-label">Correo electr&oacute;nico</label>
                        <div class="col-lg-8">
                            <input type="email" id="email" name="email" class="datoRequerido form-control"
                                   value=""
                                   placeholder="Correo electr&oacute;nico" maxlength="64"/>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="" class="col-lg-4 control-label">Tel&eacute;fono principal</label>
                        <div class="col-lg-8">
                            <input type="text" id="telefono" name="telefono" class="datoRequerido form-control"
                                   value=""
                                   placeholder="Tel&eacute;fono principal" maxlength="16"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <div data-linea="21">
        <input type="hidden" name="id_persona" id="id_persona" value=""> 
        <button type="button" class="fas fa-download" id="btnDescargarProforma">Guardar</button>
        <div id="descarga" style="visibility:hidden"> </div>
    </div>
</form>

<!--Fin - Finalizar y Enviar la solicitud -->
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>

        if ('<?php echo ($this->usuarioInterno) ?>' == '1') {
            $(".datoRequerido").attr("required", true);
        }

        $('#opProfTercero').change(function () {
            if ($(this).val() === 'SI') {
                $('#divProfTercero').show();
                $(".datoRequerido").attr("required", true);
                distribuirLineas();
            } else {
                $(".datoRequerido").attr("required", false);
                $('#divProfTercero').hide();
            }
        });
        $('#ci_ruc').focusout(function () {
            if ($(this).val() !== "") {
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/getDatosPersonaProforma/" + $('#ci_ruc').val(),
                        function (data) {
                            if (data !== null) {
                                $('#formularioProforma #id_persona').val(data.id_persona);
                                $('#formularioProforma #nombre_persona').val(data.nombre);
                                $('#formularioProforma #direccion').val(data.direccion);
                                $('#formularioProforma #telefono').val(data.telefono);
                                $('#formularioProforma #email').val(data.email);
                            }
                        }, 'json');
            }
        });

        /// Para validar los campos requeridos
        function fn_validar() {
            var continuar = 1;
            $('#formularioProforma').find('select, textarea, input').each(function () {
                var inpObj = document.getElementById($(this).attr('id'));
                if (inpObj !== null) {
                    try {
                        if (!inpObj.checkValidity()) {
                            document.getElementById("sbmProforma").click();
                            continuar = 0;
                            return false;
                        }
                    }
                    catch (err) {
                    }
                }
            });
            return continuar;
        }

        //Guarda los datos de la proforma para luego permitir descargar
        $('#btnDescargarProforma').click(function () {
            if (fn_validar() === 1) {

                var proforma_cantidades = "";
                var tiempos = "";
                $(".list_cantidad").each(function () { //las cantidades esta relacionado con el servicio ejm serv-cant
                    proforma_cantidades = proforma_cantidades + $(this).val() + ',';
                });
                $(".list_tiempo").each(function () { //las cantidades esta relacionado con el servicio ejm serv-cant
                    tiempos = tiempos + $(this).val() + ',';
                });

                var data = {
                    proformaCantidades: proforma_cantidades,
                    idLaboratorio: $("#id_laboratorio").val(),
                    codigo_auxiliar: $("#id_codigo_proforma").val(),
                    id_persona: $("#id_persona").val(),
                    opProfTercero: $("#opProfTercero").val(),
                    ci_ruc: $("#ci_ruc").val(),
                    nombre: $("#nombre_persona").val(),
                    direccion: $("#direccion").val(),
                    email: $("#email").val(),
                    telefono: $("#telefono").val(),
                    contacto_proforma: $("#contacto_proforma").val(),
                    telefono_proforma: $("#telefono_proforma").val(),
                    numero_muestras: $("#numero_muestras").val(),
                    id_localizacion: $("#id_localizacion").val(),
                    tiempos: tiempos
                };

                var url = "<?php echo URL ?>Laboratorios/Proformas/guardar";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: "text",
                    contentType: "application/x-www-form-urlencoded; charset=latin1",
                    beforeSend: function () {
                        $('#descarga').css('visibility', 'visible');
                        $("#descarga").html("<div id='cargando'>Generando proforma... Espere un momento por favor</div>").fadeIn();
                    },
                    success: function (url) {
                        $('#btnDescargarProforma').css('visibility', 'hidden');
                        $('#descarga').css('visibility', 'visible');
                        $('#descarga').html('<a href="' + url + '" target="_blank" class="far fa-file-pdf">  Descargar PDF</a>');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#detalleItem").html(
                                "<div id='error'>¡Ups!... algo no anda bien.<br />"
                                + "Se produjo un " + textStatus + " "
                                + jqXHR.status
                                + ".<br />Disculpe los inconvenientes causados.</div>");
                    },
                    complete: function () {

                    }
                });
            }
        });
    });
</script>