<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion = 'UsuariosSolicitud/guardar' 
      data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">	
    <fieldset>			
        <legend>Usuarios de la Solicitud</legend>

        <div data-linea ="1">
            <label for="identificador"> C&eacute;dula</label> 
            <input type ="text" id="identificador"   
                   name ="identificador" value="<?php echo $this->modeloUsuariosSolicitud->getIdentificador(); ?>"
                   placeholder ="Cédula de identidad o pasaporte."
                   required  maxlength="13" />
        </div>

        <div data-linea ="1">
            <label for="identificador"> Nombre</label> 
            <input type ="text" id="nombreU" value="" readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea="1">
            <label>Provincia</label> 
            <select id="id_localizacion" name="id_localizacion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboProvinciasEc();
                ?>
            </select>
        </div>

        <div data-linea ="2">
            <label for="fecha_inicio">Fecha inicio</label> 
            <input type ="date" id="fecha_inicio"   
                   name ="fecha_inicio" value="<?php echo $this->modeloUsuariosSolicitud->getFechaInicio(); ?>"
                   placeholder ="Fecha inicio"
                   required  maxlength="10" />
        </div>

        <div data-linea ="2">
            <label for="fecha_fin">Fecha fin</label> 
            <input type ="date" id="fecha_fin"   
                   name ="fecha_fin" value="<?php echo $this->modeloUsuariosSolicitud->getFechaFin(); ?>"
                   placeholder ="Fecha fin"
                   required  maxlength="10" />
        </div>

        <div data-linea ="3">
            <label for="motivo"> Motivo</label> 
            <input type ="text" id="motivo"   
                   name ="motivo" value="<?php echo $this->modeloUsuariosSolicitud->getMotivo(); ?>"
                   placeholder ="Por qué se registra mas de un usuarios para ingresar las muestras"
                   maxlength="128" />
        </div>

        <div data-linea="3">
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloUsuariosSolicitud->getEstado()); ?>
            </select>
        </div>

        <div data-linea="4">
            <button type ="submit" class="guardar"> <?php echo empty($this->modeloUsuariosSolicitud->getIdUsuariosSolicitud()) ? 'Agregar' : 'Actualizar'; ?></button>
        </div>

        <div data-linea ="8">	
            <input type ="hidden" name="id_solicitud" id="id_solicitud" value ="<?php echo $this->modeloUsuariosSolicitud->getIdSolicitud() ?>">
            <input type ="hidden" name="id_usuarios_solicitud" id="id_usuarios_solicitud" value ="<?php echo $this->modeloUsuariosSolicitud->getIdUsuariosSolicitud() ?>">
        </div >
    </fieldset>
</form>

<fieldset>	
    <legend>Lista de usuarios de la Solicitud</legend>
    <i class="fas fa-info-circle"></i><span> Dar doble clic para editar.</span>
    <div id="paginacionUsuarios" class="normal"></div>
    <table id="grid">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Provincia</th>
                <th>Estado</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="datos">
            <?php
            echo $this->itemsFiltrados;
            ?>
        </tbody>
    </table>
</fieldset>

<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();

        fn_buscarUsuario();

        $('#id_localizacion option[value="<?php echo $this->modeloUsuariosSolicitud->getIdLocalizacion(); ?>"]').prop('selected', true);
    });

    $('#identificador').focusout(function () {
        fn_buscarUsuario();
    });

    //Busca el usuario
    function fn_buscarUsuario() {
        $.post("<?php echo URL ?>Laboratorios/UsuariosSolicitud/buscarUsuarios/" + $('#identificador').val(),
                function (data) {
                    if (data.existe === 'NO') {
                        //sms si el usuario no está habilitado para usar Laboratorios
                        mostrarMensaje(data.mensaje, "FALLO");
                    } else {
                        $("#nombreU").val(data.nombre);
                    }
                }, 'json');
    }

    //Eliminar un item
    function eliminar(id) {
        if (confirm("Está seguro de eliminar el usuario de esta Solicitud?")) {
            $.ajax({
                type: "POST",
                url: "<?php echo URL ?>Laboratorios/UsuariosSolicitud/borrar/" + id,
                beforeSend: function () {
                    $("#paginacionUsuarios").html("<div id='cargando'>Cargando...</div>");
                },
                success: function (data) {
                    $("#paginacionUsuarios").html("");
                    $("#datos").html(data);
                }
            });
        }
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        if (new Date($("#fecha_fin").val()) < new Date($("#fecha_inicio").val())) {
            mostrarMensaje("La fecha final debe ser mayor o igual a la fecha inicial", "FALLO");
        } else {
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
        }
    });
</script>
