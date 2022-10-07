<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Laboratorios/guardar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
     <fieldset>
        <legend>Ingrese los datos de la Dirección de diagnóstico</legend>
        <div data-linea="1">
            <label for="id_sistema_guia">Sistema GUIA </label> <select
                id="id_sistema_guia" name="id_sistema_guia" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDireccionesGUIA($this->modeloLaboratorios->getIdSistemaGuia());
                ?>
            </select>
        </div>

        <div data-linea="1">
            <label for="nombre"> Nombre </label> 
            <input type="text" id="nombre" name="nombre"
                   value="<?php echo $this->modeloLaboratorios->getNombre(); ?>"
                   placeholder="Ej. DIAGNÓSTICO VEGETAL " required maxlength="128" />
        </div>

        <label for="descripcion"> Descripción </label>
        <div data-linea="3">
            <textarea id="descripcion" name="descripcion"
                      placeholder="Ingrese una descripción"><?php echo nl2br(htmlentities($this->modeloLaboratorios->getDescripcion(), ENT_COMPAT, 'utf-8')); ?></textarea>
        </div>

        <div data-linea="4">
            <label>Estado</label> 
            <select id="estado_registro" name="estado_registro" >

                <?php echo $this->combo2Estados($this->modeloLaboratorios->getEstadoRegistro()); ?>
            </select>

        </div>
        <div data-linea="4">
            <label for="codigo"> Código </label> 
            <input type="text" id="codigo" name="codigo"
                   value="<?php echo $this->modeloLaboratorios->getCodigo(); ?>"
                   placeholder="Ej. DV" required maxlength="16" />
        </div>
        <div data-linea="4">
            <label for="orden"> Orden </label> 
            <input type="number" id="orden" name="orden"
                   value="<?php echo $this->modeloLaboratorios->getOrden(); ?>"
                   required />
        </div>

        <div data-linea="5">
            <input type="hidden" name="id_laboratorio" id="id_laboratorio"
                   value="<?php echo $this->modeloLaboratorios->getIdLaboratorio(); ?>" />
            <input type="hidden" name="nivel" id="nivel" value="0" />
            <input type="hidden" name="tipo_campo" id="tipo_campo" value="DIRECCION" />
            <button type="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
    });

    $("#formulario").submit(function (event) {
        event.preventDefault();
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
    });
    
    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/Laboratorios/direccionesActualizar",
                {
                    
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
        });
    }
</script>
