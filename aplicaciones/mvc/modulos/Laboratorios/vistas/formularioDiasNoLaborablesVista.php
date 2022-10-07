			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'DiasNoLaborables/guardar' data-destino ="detalleItem"			
      data-accionEnExito ="NADA" method="post">			
    <fieldset>	<legend>Dias No Laborables</legend>
        <div data-linea="1"> 
            <input type="checkbox" id="alcance" name="alcance"   <?php echo $this->modeloDiasNoLaborables->getAlcance(); ?>> 
            <label for="alcance">Este día no laborable aplica para todos los laboratorios</label>
        </div> 
        <div data-linea="2"> 	
            <label for="id_direccion"> Dirección </label> 
            <select id="id_direccion" name="id_direccion">
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloDiasNoLaborables->getIdDireccion());
                ?>
            </select>
        </div>      
        <div data-linea="2">
            <label>Laboratorios</label> 
            <select id="id_laboratorio" name="id_laboratorio" disabled="disabled">
            </select>
        </div>  


        <div data-linea ="4">
            <label for="fecha"> Fecha </label> 
            <input type ="date" id="fecha"
                   name ="fecha" value="<?php echo $this->modeloDiasNoLaborables->getFecha(); ?>"
                   placeholder ="Indica la fecha que no es laborable"
                   required  maxlength="512" />
        </div>

        <div data-linea ="4">
            <label>Estado</label> 
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloDiasNoLaborables->getEstado()); ?>
            </select>
        </div>

        <div data-linea ="5">
            <label for="descripcion"> Descripcion </label> 
            <input type ="text" id="descripcion"
                   name ="descripcion" value="<?php echo $this->modeloDiasNoLaborables->getDescripcion(); ?>"
                   placeholder ="Breve descripción del dia no laborable"
                   required  maxlength="512" />
        </div>

        <div data-linea ="6">			
            <input type ="hidden" name="id_dias_no_laborables" id="id_dias_no_laborables" value ="<?php echo $this->modeloDiasNoLaborables->getIdDiasNoLaborables() ?>">			
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
    });

    $("#alcance").click(function () {
        if ($('#alcance').is(':checked')) {
            $("#id_direccion").attr("disabled", "disabled");
            $("#id_laboratorio").attr("disabled", "disabled");
        } else {
            $("#id_direccion").removeAttr("disabled");
            $("#id_laboratorio").removeAttr("disabled");
        }
    });

    fn_cargarLaboratorios();

    //Para cargar los laboratorios una vez sleccionado la dirección
    function fn_cargarLaboratorios() {
        var idDireccion = $("#id_direccion").val();
        if (idDireccion !== "") {
            $.post("<?php echo URL ?>Laboratorios/DiasNoLaborables/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").removeAttr("disabled");
                $("#id_laboratorio").html(data);
                $('#id_laboratorio option[value="<?php echo $this->modeloDiasNoLaborables->getIdLaboratorio(); ?>"]').prop('selected', true);
            });
        }
    }

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#id_direccion").change(function () {
        if ($(this).val !== "") {
            fn_cargarLaboratorios();
        }
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

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/DiasNoLaborables/listaActualizar",
                {
                },
                function (data) {
                    construirPaginacion($("#paginacion"), JSON.parse(data));
                });
    }
</script>
