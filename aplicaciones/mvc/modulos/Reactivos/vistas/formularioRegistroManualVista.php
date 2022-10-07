			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			<form id = 'formulario' 
                  data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			
                  data-opcion = 'registromanual/guardar' data-destino ="detalleItem"			
                  data-accionEnExito ="NADA" method="post">			<fieldset>			<legend>RegistroManual</legend>			
        <div data-linea ="1">
            <label for="id_registro_manual">  </label> 
            <input type ="text" id="id_registro_manual"
                   name ="id_registro_manual" value="<?php echo $this->modeloRegistroManual->getIdRegistroManual(); ?>"
                   placeholder =""
                   required  maxlength="512" />
        </div >

        <div data-linea ="2">
            <label for="id_reactivo_laboratorio">  </label> <input type ="text" id="id_reactivo_laboratorio"
                                                                   name ="id_reactivo_laboratorio" value="<?php echo $this->modeloRegistroManual->getIdReactivoLaboratorio(); ?>"
                                                                   placeholder =""
                                                                   required  maxlength="512" />
        </div >

        <div data-linea ="3">
            <label for="fecha_inicio">  </label> <input type ="text" id="fecha_inicio"
                                                        name ="fecha_inicio" value="<?php echo $this->modeloRegistroManual->getFechaInicio(); ?>"
                                                        placeholder =""
                                                        required  maxlength="512" />
        </div >

        <div data-linea ="4">
            <label for="fecha_fin">  </label> <input type ="text" id="fecha_fin"
                                                     name ="fecha_fin" value="<?php echo $this->modeloRegistroManual->getFechaFin(); ?>"
                                                     placeholder =""
                                                     required  maxlength="512" />
        </div >

        <div data-linea ="5">
            <button type ="submit" class="guardar"> Guardar</button>
        </div >
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        construirValidador();
        distribuirLineas();
    });
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if (respuesta.estado == 'exito')
            {
                fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
