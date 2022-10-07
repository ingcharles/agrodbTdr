<!--Inicio - Ubicación de la muestra -->
<fieldset>
    <legend>Datos generales de la muestra</legend>
    <div data-linea="1">
        <div id="div_fecha_toma" class="cDatosGenerales">
            <label>Fecha de toma</label> 
            <input type="date" id="fecha_toma" name="fecha_toma" 
                   value="<?php echo $this->modeloMuestras->getFechaToma(); ?>"/> 
        </div>
    </div>
    <div data-linea="1">
        <div id="div_responsable_toma" class="cDatosGenerales">
            <label>Responsable de la toma</label> 
            <input type="text" id="responsable_toma" name="responsable_toma" 
                   value="<?php echo $this->modeloMuestras->getResponsableToma(); ?>"
                   placeholder="Responsable que toma la muestra" maxlength="128"/>
        </div>
    </div>
    <div data-linea="2">
        <div id="div_id_localizacion" class="cDatosGenerales">
            <label>Provincia</label> 
            <select id="id_localizacion" name="id_localizacion">
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboProvinciasEc($this->modeloMuestras->getFkIdLocalizacion());
                ?>
            </select>
        </div>
    </div>
    <div data-linea="2">
        <div id="div_fk_id_localizacion" class="cDatosGenerales">
            <label>Cant&oacute;n</label> 
            <select id="fk_id_localizacion"
                    name="fk_id_localizacion" disabled="disabled">
            </select>
        </div>
    </div>

    <div data-linea="3">
        <div id="div_fk_id_localizacion2" class="cDatosGenerales">
            <label>Parroquia</label> 
            <select id="fk_id_localizacion2"
                    name="fk_id_localizacion2" disabled="disabled">
            </select>
        </div>
    </div>
    <div data-linea="3">
        <div id="div_referencia_ubicacion" class="cDatosGenerales">
            <label>Referencia</label> 
            <input type="text" id="referencia_ubicacion" name=referencia_ubicacion 
                   value="<?php echo $this->modeloMuestras->getReferenciaUbicacion(); ?>"
                   placeholder="Ej: Sector El Guabo..."
                   data-er="^[A-Za-zÃ±Ã‘ï¿½?Ã¡Ã‰Ã©ï¿½?Ã­Ã“Ã³ÃšÃºÃœÃ¼.#-/Â°0-9 ]+$" maxlength="512"/>
        </div>
    </div>
    <div data-linea="4">
        <div id="div_longitud" class="cDatosGenerales">
            <label>Longitud (X)</label> 
            <input type="text" id="longitud" name="longitud" 
                   value="<?php echo $this->modeloMuestras->getLongitud(); ?>"
                   placeholder="Elija en el mapa" maxlength="32"/>
        </div>
    </div>
    <div data-linea="4">
        <div id="div_latitud" class="cDatosGenerales">
            <label>Latitud (Y)</label> 
            <input type="text" id="latitud" name="latitud"
                   value="<?php echo $this->modeloMuestras->getLatitud(); ?>"
                   placeholder="Elija en el mapa" maxlength="32">
        </div>
    </div>
    <div data-linea="4">
        <div id="div_altura" class="cDatosGenerales">
            <label>Altitud (msnm) </label> 
            <input type="number" id="altura" name="altura"
                   value="<?php echo $this->modeloMuestras->getAltura(); ?>"
                   placeholder="msnm" maxlength="16" min="1"/>
        </div>
    </div>
    <input type="hidden" id="id_muestra" name="id_muestra" value="<?php echo $this->modeloMuestras->getIdMuestra(); ?>" />
</fieldset>

<div id="div_propietarioUsuario">
    <fieldset>
        ¿El propietario de la muestra es el mismo del sistema GUIA?
        <input type="radio" id="opPropietario1"
               name="opPropietario" value="1">
        <label for="opPropietario1">SI</label>
        <input type="radio" id="opPropietario2"
               name="opPropietario" value="0">
        <label for="opPropietario2">NO</label>
    </fieldset>
</div>

<fieldset id="datosPropietario" style="display: none">
    <legend>Datos del propietario</legend>
    <div data-linea="1">
        <label>C&eacute;dula/Ruc</label> 
        <input type="number" id="cedula_propietario" name="ci_ruc" 
               value="<?php echo $this->modeloMuestras->getPersona()->getCiRuc(); ?>"
               placeholder="C&eacute;dula/Ruc del Propietario" maxlength="16"/>
    </div>
    <div data-linea="1">
        <label>Propietario</label> 
        <input type="text" id="nombre_propietario" name="nombre" 
               value="<?php echo $this->modeloMuestras->getPersona()->getNombre(); ?>"
               placeholder="Propietario de la muestra" maxlength="128"/>
    </div>
    <div data-linea="2">
        <label>Direcci&oacute;n</label> 
        <input type="text" id="direccion_propietario" name="direccion"
               value="<?php echo $this->modeloMuestras->getPersona()->getDireccion(); ?>"
               placeholder="Direcci&oacute;n del Propietario" maxlength="128"/>
    </div>
    <div data-linea="2">
        <label>Tel&eacute;fono</label> 
        <input type="text" id="telefono_propietario" name="telefono"
               value="<?php echo $this->modeloMuestras->getPersona()->getTelefono(); ?>"
               placeholder="Tel&eacute;fono del Propietario" maxlength="16"/>
    </div>
    <div data-linea="3">
        <label>Correo electr&oacute;nico</label> 
        <input type="email" id="email_propietario" name="email"
               value="<?php echo $this->modeloMuestras->getPersona()->getEmail(); ?>"
               placeholder="Correo electr&oacute;nico del Propietario" maxlength="64"/>
    </div>
    <div data-linea="4">
        <label>Contacto proforma</label> 
        <input type="text" id="contacto_proforma" name="contacto_proforma"
               value="<?php echo $this->modeloMuestras->getPersona()->getContactoProforma(); ?>"
               placeholder="Contacto proforma" maxlength="64"/>
    </div>
    <div data-linea="4">
        <label>Tel&eacute;fono proforma</label> 
        <input type="text" id="telefono_proforma" name="telefono_proforma"
               value="<?php echo $this->modeloMuestras->getPersona()->getTelefonoProforma(); ?>"
               placeholder="Tel&eacute;fono proforma" maxlength="16"/>
    </div>
    <input type="hidden" id="id_persona" name="id_persona" value="<?php echo $this->modeloMuestras->getPersona()->getIdPersona(); ?>" />
</fieldset>
<div id="div_bntBuscarMapa">
    <fieldset id="mapaContenedor">
        <legend class='legendMuestras'>Ubicaci&oacute;n de la muestra</legend>
        <table style="width: 100%">
            <tr>
                <td style="width: 100px"></td>
                <td colspan="2" style="text-align: center">
                    <button type="button" id="search" class="fas fa-map-marker-alt"> Buscar en mapa</button>
                </td>
                <td style="width: 100px"></td>
            </tr>
        </table>

        <p class="nota">Por favor marque en el mapa la ubicaci&oacute;n del
            sitio donde tomar&aacute; la muestra. Puede ampliar el mismo para
            indicar la posici&oacute;n exacta.</p>
        <div id="mapCanvas" style="width: 600px"></div>
    </fieldset>
</div>

<!--Fin - Ubicación de la muestra -->
<script>
<?php echo $this->codigoJS; ?>
    // en esta vista se llama nuevamente al la funcion para que habilite correctamente los atributos
    fn_habilitarDatosGenerales();

    $('#id_localizacion option[value="<?php echo $this->modeloMuestras->getIdLocalizacion(); ?>"]').prop('selected', true);
    fn_buscarCantones($("#id_localizacion").val(), '<?php echo $this->modeloMuestras->getFkIdLocalizacion(); ?>');

    //Paso->Datos generales de la muestra
    if ($("#id_persona").val() === '') { //no existe persona
        $('#opPropietario1').prop('checked', true);
        $('#datosPropietario').hide();
    } else {
        $('#opPropietario2').prop('checked', true);
        $('#datosPropietario').show();
        distribuirLineas();
    }
    $('input[name=opPropietario]').click(function () {
        if ($(this).val() === '0') {
            $('#datosPropietario').show();
            distribuirLineas();
        } else
            $('#datosPropietario').hide();
    });

    //Paso->Datos generales de la muestra: Seleccionar Provincia
    $("#id_localizacion").change(function () {
        fn_buscarCantones($(this).val(), '');
        $("#fk_id_localizacion2").html("");
    });

    //Paso->Datos generales de la muestra: Seleccionar Cantón
    function fn_buscarCantones(idProvincia, guardado) {
        if (idProvincia !== "") {
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboCantones/" + idProvincia, function (data) {
                $("#fk_id_localizacion").removeAttr("disabled");
                $("#fk_id_localizacion").html(data);
                $('#fk_id_localizacion option[value="' + guardado + '"]').prop('selected', true);
                if (guardado !== '') {
                    fn_buscarParroquias($("#fk_id_localizacion").val(), '<?php echo $this->modeloMuestras->getFkIdLocalizacion2(); ?>');
                }
            });
        }
    }

    $("#fk_id_localizacion").change(function () {
        fn_buscarParroquias($(this).val(), '');
    });

    function fn_buscarParroquias(idCanton, guardado) {
        if (idCanton !== "") {
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboParroquias/" + idCanton, function (data) {
                $("#fk_id_localizacion2").removeAttr("disabled");
                $("#fk_id_localizacion2").html(data);
                $('#fk_id_localizacion2 option[value="' + guardado + '"]').prop('selected', true);
            });
        }
    }

    $('#cedula_propietario').focusout(function () {
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/getDatosPersona/" + $('#cedula_propietario').val(),
                function (data) {
                    $('#id_persona').val(data.id_persona);
                    $('#nombre_propietario').val(data.nombre);
                    $('#direccion_propietario').val(data.direccion);
                    $('#telefono_propietario').val(data.telefono);
                    $('#email_propietario').val(data.email);
                    $('#contacto_proforma').val(data.contacto_proforma);
                    $('#telefono_proforma').val(data.telefono_proforma);
                }, 'json');
    });
</script>

<script type="text/javascript">
    var cambio_locacion = 0;
    //luego de ingrear pais/provincia/canto/parroquia y direccion debe buscar en el mapa
    $('#search').click(function () {
        var zoom = 10;
        if ($('#id_localizacion').length === 0) {   //si existe el combo
            $("#id_localizacion").addClass("alertaCombo");
            mostrarMensaje("Seleccione la Provincia.", "FALLO");
            return false;
        }
        cambio_locacion = 1; //estoy cambiando de locacion actual
        var buscar = "Ecuador, ";

        if ($("#id_localizacion").val() !== "") {
            zoom = 10;
            buscar = buscar + $("#id_localizacion :selected").text() + ', ';
        }
        if ($("#fk_id_localizacion").val() !== "") {
            zoom = 12;
            buscar = buscar + $("#fk_id_localizacion :selected").text() + ', ';
        }
        if ($("#fk_id_localizacion2").val() !== "" & $("#fk_id_localizacion2").val() !== null) {
            zoom = 15;
            buscar = buscar + $("#fk_id_localizacion2 :selected").text() + ', ';
        }
        initialize(buscar, zoom);
    });
    function geocodePosition(pos, geoCoder, address) {
        geoCoder.geocode({
            latLng: pos
        }, function (responses) {
            if (responses && responses.length > 0) {
                updateMarkerAddress(responses[0].formatted_address, address);
            } else {
                updateMarkerAddress('No se puede determinar la ubicación.');
            }
        });
    }

    function updateMarkerStatus(str) {
        //document.getElementById('markerStatus').innerHTML = str;
    }

    function updateMarkerPosition(latLng) {
        $("#latitud").val(latLng.lat()); // coordenada y
        $("#longitud").val(latLng.lng()); // coordenada x
    }

    function updateMarkerAddress(str, address) {
        //document.getElementById(address).innerHTML = str;
    }

    function initialize(address, zoom) {
        try {
            var geoCoder = new google.maps.Geocoder(address);
            var request = {address: address};
            geoCoder.geocode(request, function (result, status) {

                var latlng = new google.maps.LatLng(result[0].geometry.location.lat(), result[0].geometry.location.lng());

                var myOptions = {
                    zoom: zoom,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById("mapCanvas"), myOptions);
                var marker = new google.maps.Marker({position: latlng, map: map, title: 'title', draggable: true});
                // Update current position info.
                updateMarkerPosition(latlng);
                geocodePosition(latlng, geoCoder, 'address');
                // Add dragging event listeners.
                google.maps.event.addListener(marker, 'dragstart', function () {
                    updateMarkerAddress('Dragging...', 'address');
                });
                google.maps.event.addListener(marker, 'drag', function () {
                    updateMarkerStatus('Dragging...');
                    updateMarkerPosition(marker.getPosition());
                });
                google.maps.event.addListener(marker, 'dragend', function () {
                    updateMarkerStatus('Drag ended');
                    geocodePosition(marker.getPosition(), geoCoder, 'address');
                });
            });
        } catch (e) {
            mostrarMensaje("Mapa no disponible!", "FALLO");
        }
    }
</script>