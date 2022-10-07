<header>
    <h1>Activar orden de trabajo con exoneraci&oacute;n de pago</h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='BandejaRecepcion/aceptarExoneracion' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Activar orden de trabajo con exoneraci&oacute;n de pago</legend>

        <div data-linea ="1">
            <label for="numero_deposito"> N&uacute;mero de Memorando </label> 
            <input type ="text" value="<?php echo $this->modeloSolicitudes->getOficioExoneracion(); ?>"
                   style="background: transparent; border: 0" readonly/>
        </div>

        <div data-linea ="2">
            <label for="fecha_deposito"> Cantidad exonerada </label> 
            <input type ="text" value="<?php echo $this->modeloSolicitudes->getNumMuestrasExoneradas(); ?>"
                   style="background: transparent; border: 0" readonly/>
        </div>

        <div data-linea ="2">
            <label for="fecha_deposito"> Saldo </label> 
            <input type ="text" value="<?php echo $this->saldo; ?>"
                   style="background: transparent; border: 0" readonly/>
        </div>

        <fieldset>
            <legend>DETALLE CONSUMO MEMO INGRESADO</legend>
            <table width="100%" id="grilla" class="lista" ALIGN="CENTER">
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
                    <?php echo $this->datosMemo; ?>
                </tbody>
            </table>
        </fieldset>
        <input type ="hidden" id="idOrdenTrabajo" name ="idOrdenTrabajo" value="<?php echo $this->idOrdenTrabajo; ?>"/>
        <input type ="hidden" id="idSolicitud" name ="idSolicitud" value="<?php echo $this->modeloSolicitudes->getIdSolicitud(); ?>"/>
        <button type="submit" class="guardar"> Activar orden de trabajo</button>
    </fieldset>
</form>

<!-- Código javascript -->
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
    });

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
            ejecutarJson($(this));
            fn_filtrar();
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>