<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'ReactivosSolucion/guardarSaldosLaboratorios' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			

    <fieldset>
        <legend>Ingreso de reactivos de la soluci&oacute;n</legend>

        <div data-linea ="1">
            <label for="nombre"> Soluci&oacute;n </label> 
            <input type ="text" value="<?php echo $this->modeloReactivosLaboratorios->getNombre(); ?>"
                   readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="1">
            <label for="nombre"> Volumen final de la soluci&oacute;n </label> 
            <input type ="text" value="<?php echo $this->modeloReactivosLaboratorios->getVolumenFinal() . " " . $this->modeloReactivosLaboratorios->getUnidadMedida(); ?>"
                   readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="2">
            <label for="cantidad_requerida"> Cantidad de ingreso (<?php echo $this->modeloReactivosLaboratorios->getUnidadMedida(); ?>) </label>
            <input type ="number" id="cantidad_requerida" required
                   name ="cantidad_requerida" value="<?php echo $this->modeloReactivosSolucion->getCantidadRequerida(); ?>"
                   placeholder ="cantidad_requerida" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>

        <div data-linea ="2"></div>

        <div data-linea ="8">
            <input type="hidden" id="id_solucion" name="id_solucion" 
                   value="<?php echo $this->modeloReactivosLaboratorios->getIdReactivoLaboratorio(); ?>"/>
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>

<fieldset>	
    <legend>Lista de reactivos para la soluci&oacute;n</legend>
    <table width="100%">
        <thead><tr>
                <th>#</th>
                <th title="Reactivo del laboratorio">Reactivo Laboratorio</th>
                <th title="Unidad de medida">Unidad</th>
                <th title="Cantidad que se usa en la soluci&oacute;n">Cantidad</th>
                <th title="Estado del registro, si es INACTIVO no se descuenta al realizar el an&aacute;lisis">Estado</th>
                <th title="Obseraci&oacute;n sobre el reactivo usado">Observaci&oacute;n</th>
            </tr></thead>
        <tbody>   
            <?php echo $this->listaReactivosSolucion; ?>
        </tbody>
    </table>
</fieldset>


<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });


    $("#formulario").submit(function (event) {
        event.preventDefault();
        respuesta = confirm("Se va a descontar de los reactivos del laboratorio");
        if (respuesta) {
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
</script>
