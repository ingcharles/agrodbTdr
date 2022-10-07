<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'ReactivosLaboratorios/guardarReactivoManual' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			

    <fieldset>
        <legend>Ingreso de reactivo</legend>

        <div data-linea ="1">
            <label for="nombre"> Reactivo </label> 
            <input type ="text" value="<?php echo $this->modeloReactivosLaboratorios->getNombre(); ?>"
                   readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="2">
            <label for="cantidad"> Cantidad de ingreso (<?php echo $this->modeloReactivosLaboratorios->getUnidadMedida(); ?>) </label>
            <input type ="number" id="cantidad" required
                   name ="cantidad" value=""
                   placeholder ="Cantidad" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>
        
        <label for="observacion"> Observaci&oacute;n </label> 
        <div data-linea ="3">
            <textarea id="observacion" name ="observacion" 
                      placeholder ="Observaci&oacute;n"></textarea>
        </div>

        <div data-linea ="8">
            <input type="hidden" id="id_reactivo_laboratorio" name="id_reactivo_laboratorio" 
                   value="<?php echo $this->modeloReactivosLaboratorios->getIdReactivoLaboratorio(); ?>"/>
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>

<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });


    $("#formulario").submit(function (event) {
        event.preventDefault();
        respuesta = confirm("Se va a ingresar el saldos el reactivo");
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
