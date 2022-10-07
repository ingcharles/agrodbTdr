<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>laboratorios'
      data-opcion='solicitudes/guardarFinalizar' data-destino="detalleItem"
      data-accionEnExito="ACTUALIZAR" method="post">
    <!--Inicio - Finalizar y Enviar la solicitud -->
    <fieldset>
        <legend>GENERAR PROFORMA</legend>
        <div>
            <label>¿Se requiere realizar la proforma a nombre de un tercero?</label> 
            <input type="radio" id="opProfTercero1"
                   name="opProfTercero" value="1" required="">
            <label for="opProfTercero1">SI</label>
            <input type="radio" id="opProfTercero2"
                   name="opProfTercero" value="0" required="">
            <label for="opProfTercero2">NO</label>
        </div>

        <div id="divProfTercero" style="display: none;">
            <fieldset>
                <div data-linea="1">
                    <label>C&eacute;dula/RUC</label> 
                    <input type="text" id="ci_ruc" name="ci_ruc" class="datoRequerido"
                           value="<?php echo $this->modeloSolicitudes->getPersonas()->getCiRuc(); ?>"
                           placeholder="C&eacute;dula/Ruc de la persona natural o jur&iacute;dica para emitir la proforma" maxlength="16"/>
                </div>
                <div data-linea="2">
                    <label>Nombre y apellido/empresa</label> 
                    <input type="text" id="nombre_persona" name="nombre" class="datoRequerido"
                           value="<?php echo $this->modeloSolicitudes->getPersonas()->getNombre(); ?>"
                           placeholder="Nombre y apellido o nombre de la empresa" maxlength="128"/>
                </div>
                <div data-linea="3">
                    <label>Direcci&oacute;n</label> 
                    <input type="text" id="direccion" name="direccion" class="datoRequerido"
                           value="<?php echo $this->modeloSolicitudes->getPersonas()->getDireccion(); ?>"
                           placeholder="Direcci&oacute;n" maxlength="128"/>
                </div>
                <div data-linea="4">
                    <label>Correo electr&oacute;nico</label> 
                    <input type="email" id="email" name="email" class="datoRequerido"
                           value="<?php echo $this->modeloSolicitudes->getPersonas()->getEmail(); ?>"
                           placeholder="Correo electr&oacute;nico" maxlength="64"/>
                </div>
                <div data-linea="5">
                    <label>Tel&eacute;fono</label> 
                    <input type="text" id="telefono" name="telefono" class="datoRequerido"
                           value="<?php echo $this->modeloSolicitudes->getPersonas()->getTelefono(); ?>"
                           placeholder="Tel&eacute;fono" maxlength="16"/>
                </div>
            </fieldset>
        </div>
    </fieldset>

    <div data-linea="21">
<!--        <input type="hidden" name="id_solicitud" id="id_solicitud"
               value="<?php echo $this->modeloSolicitudes->getIdSolicitud() ?>"> 

        <input type="hidden" name="id_persona" id="id_persona"
               value="<?php echo $this->modeloSolicitudes->getPersonas()->getIdPersona(); ?>"> -->

        <button type="button" class="guardar" id="btnDescargarProforma">Descargar</button>
    </div>
</form>

<!--Fin - Finalizar y Enviar la solicitud -->
<script type="text/javascript">
    $(document).ready(function () {
        <?php echo $this->codigoJS; ?>
        $('input[name=opProfTercero]').click(function () {
            if ($(this).val() === '1') {
                $('#divProfTercero').show();
                $(".datoRequerido").attr("required", true);
                distribuirLineas();
            } else {
                $(".datoRequerido").attr("required", false);
                $('#divProfTercero').hide();
            }
        });
        $('#ci_ruc').focusout(function () {
            $.post("<?php echo URL ?>laboratorios/solicitudes/getDatosPersona/" + $('#ci_ruc').val(),
                    function (data) {
                        $('#id_persona').val(data.id_persona);
                        $('#nombre_persona').val(data.nombre);
                        $('#direccion').val(data.direccion);
                        $('#telefono').val(data.telefono);
                        $('#email').val(data.email);
                    }, 'json');
        });

        $('#btnDescargarProforma').click(function () {
            alert("Descargar proforma");
        });
    });
</script>