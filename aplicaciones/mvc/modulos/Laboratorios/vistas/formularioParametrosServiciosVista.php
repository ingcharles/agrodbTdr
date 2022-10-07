<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='ParametrosServicios/guardar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Par&aacute;metros de servicios</legend>
        <div data-linea="1">
            <label for="id_direccion"> Direcci&oacute;n </label> 
            <select id="id_direccion" name="id_direccion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloLaboratorios->getFkIdLaboratorio());
                ?>
            </select>
        </div>

        <div data-linea="1">
            <label for="id_laboratorio">Laboratorio</label> 
            <select id="id_laboratorio" name="id_laboratorio" required>
            </select>
        </div>

        <div data-linea="2">
            <label for="id_servicio"> Servicio </label> 
            <select id="id_servicio" name="id_servicio" required>
            </select>
        </div>
        <div data-linea="2">
            <label for="tipo_campo"> Tipo de campo </label> 
            <select id="tipo_campo" name="tipo_campo" 
                    required>
                <option value="">Seleccionar....</option>
                <option value="ARCHIVO">Archivo adjunto</option>
                <option value="REFERENCIA">Tabla de referenicia - Informe</option>
            </select>
        </div>

        <div data-linea="4">
            <label for="nombre"> Nombre </label> 
            <input type="text" id="nombre"
                   name="nombre"
                   value="<?php echo $this->modeloParametrosServicios->getNombre(); ?>"
                   placeholder="Nombre que identifique el parámetro" required
                   maxlength="256" />
        </div>
        <div data-linea ="5">
            <label for="valor_aux1"> Valor auxiliar 1</label> 
            <input type ="text" id="valor_aux1"
                   name ="valor_aux1" value="<?php echo $this->modeloParametrosServicios->getValorAux1(); ?>"
                   placeholder ="Valor auxiliar del par&aacute;metro"
                   maxlength="512" />
        </div>
        <div data-linea ="5">
            <label for="valor_aux2"> Valor auxiliar 2</label> 
            <input type ="text" id="valor_aux2"
                   name ="valor_aux2" value="<?php echo $this->modeloParametrosServicios->getValorAux2(); ?>"
                   placeholder ="Valor auxiliar del par&aacute;metro"
                   maxlength="512" />
        </div>
        <div data-linea ="5">
            <label for="valor_aux3"> Valor auxiliar 3</label> 
            <input type ="text" id="valor_aux3"
                   name ="valor_aux3" value="<?php echo $this->modeloParametrosServicios->getValorAux3(); ?>"
                   placeholder ="Valor auxiliar del par&aacute;metro"
                   maxlength="512" />
        </div >
        <div data-linea="6">
            <label>Estado</label> 
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloParametrosServicios->getEstado()); ?>
            </select>
        </div>
        <div data-linea="6">
            <label for="obligatorio"> Obligatorio </label> <select
                id="obligatorio" name="obligatorio" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->crearComboSINO($this->modeloParametrosServicios->getObligatorio());
                ?>
            </select>
        </div>
        
        <label for="descripcion"> Descripción </label>
        <div data-linea="7">
            <textarea id="descripcion" name="descripcion" 
                      placeholder="Ingrese una descripción"><?php echo $this->modeloParametrosServicios->getDescripcion(); ?></textarea>
        </div>

        <div data-linea ="8" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="atributos_extras"> Atributos </label> 

            <textarea id="atributos_extras" name="atributos_extras" 
                      placeholder="Se puede poner c&oacute;digo auxiliar para ejecutar el parametro"><?php echo $this->modeloParametrosServicios->getAtributosExtras(); ?></textarea>
        </div>
        <div data-linea="9" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="codigo"> Código </label> 
            <input type="text" id="codigo"
                   name="codigo"
                   value="<?php echo $this->modeloParametrosServicios->getCodigo(); ?>"
                   placeholder="Código del parámetro, éste es utilizado en la programación por lo que una vez establecido no debe ser cambiado."
                   required maxlength="16" />
        </div>
        <div data-linea="9" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="orden"> Orden </label> 
            <input type="number" id="orden"
                   name="orden"
                   value="<?php echo $this->modeloParametrosServicios->getOrden(); ?>"
                   placeholder="Orden que deben presentarse en el formulario."
                   required maxlength="512" maxlength="3"/>
        </div>

        <div data-linea="10">
            <input type="hidden" name="id_parametros_servicio"
                   id="id_parametros_servicio"
                   value="<?php echo $this->modeloParametrosServicios->getIdParametrosServicio() ?>">
            <button type="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();

        tinymce.init({
            selector: '#descripcion',
            language: 'es',
            height: 80,
            menubar: false,
            plugins: [
                'advlist lists link image table'
            ],
            toolbar: 'insert | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | table',
        });
    });

    $('#id_direccion option[value="<?php echo $this->modeloParametrosServicios->getIdDireccion(); ?>"]').prop('selected', true);
    $('#tipo_campo option[value="<?php echo $this->modeloParametrosServicios->getTipoCampo(); ?>"]').prop('selected', true);
    fn_cargarLaboratorios();

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#id_direccion").change(function () {
        fn_cargarLaboratorios();
    });

    function fn_cargarLaboratorios() {
        var idDireccion = $("#id_direccion").val();
        if (idDireccion !== "") {
            //Cargamos los laboratorios
            $.post("<?php echo URL ?>Laboratorios/ParametrosServicios/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").html(data);
                $('#id_laboratorio option[value="<?php echo $this->modeloParametrosServicios->getIdLaboratorio(); ?>"]').prop('selected', true);
                fn_cargarServicios();
            });
        }
    }

    //Cuando seleccionamos un laboratorio, llenamos el combo de servicios
    $("#id_laboratorio").change(function () {
        fn_cargarServicios();
    });

    function fn_cargarServicios() {
        var idLaboratorio = $("#id_laboratorio").val();
        if (idLaboratorio !== "") {
            //OJO verificar
            $.post("<?php echo URL ?>Laboratorios/ParametrosServicios/comboServiciosSimple/" + idLaboratorio, function (data) {
                $("#id_servicio").html(data);
                $('#id_servicio option[value="<?php echo $this->modeloParametrosServicios->getIdServicio(); ?>"]').prop('selected', true);
            });
        }
    }

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
</script>
