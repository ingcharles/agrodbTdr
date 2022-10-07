<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'ParametrosLaboratorios/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Parámetros de laboratorio</legend>

        <div data-linea="1">
            <label for="id_direccion"> Dirección </label> 
            <select id="id_direccion" name="id_direccion" required>
                <option value="">Seleccionar....</option>
                <?php echo $this->comboDirecciones(); ?>
            </select>
        </div>

        <div data-linea="1">
            <label for="id_laboratorio">Laboratorio</label> 
            <select id="id_laboratorio" name="id_laboratorio" required>
            </select>
        </div>

        <div data-linea="2">
            <label for="nombre"> Nombre </label> 
            <input type="text" id="nombre"
                   name="nombre"
                   value="<?php echo $this->modeloParametrosLaboratorios->getNombre(); ?>"
                   placeholder="Nombre que identifique el parámetro" required
                   maxlength="256" />
        </div>
        <div data-linea ="2">
            <label for="valor_aux1"> Valor auxiliar 1</label> 
            <input type ="text" id="valor_aux1"
                   name ="valor_aux1" value="<?php echo $this->modeloParametrosLaboratorios->getValorAux1(); ?>"
                   placeholder ="Valor auxiliar del par&aacute;metro"
                   maxlength="512" />
        </div >
        <div data-linea ="3">
            <label for="valor_aux2"> Valor auxiliar 2</label> 
            <input type ="text" id="valor_aux2"
                   name ="valor_aux2" value="<?php echo $this->modeloParametrosLaboratorios->getValorAux2(); ?>"
                   placeholder ="Valor auxiliar del par&aacute;metro"
                   maxlength="512" />
        </div >
        <div data-linea ="3">
            <label for="valor_aux3"> Valor auxiliar 3</label> 
            <input type ="text" id="valor_aux3"
                   name ="valor_aux3" value="<?php echo $this->modeloParametrosLaboratorios->getValorAux3(); ?>"
                   placeholder ="Valor auxiliar del par&aacute;metro"
                   maxlength="512" />
        </div >
        <div data-linea="4">
            <label>Estado</label> 
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloParametrosLaboratorios->getEstado()); ?>
            </select>
        </div>
        <div data-linea="4">
            <label for="obligatorio"> Obligatorio </label> <select
                id="obligatorio" name="obligatorio" required="true">
                <option value="">Seleccionar....</option>
                <?php echo $this->crearComboSINO($this->modeloParametrosLaboratorios->getObligatorio()); ?>
            </select>
        </div>
        
        <label for="descripcion"> Descripción </label>
        <div data-linea="5">
            <textarea id="descripcion" name="descripcion" 
                      placeholder="Ingrese una descripción"><?php echo $this->modeloParametrosLaboratorios->getDescripcion(); ?></textarea>
        </div>

        <div data-linea ="6" style="visibility: <?php echo $this->devVisible(); ?>">
        <label for="atributos_extras"> Atributos </label> 
            <textarea id="atributos_extras" name="atributos_extras" 
                      placeholder="Se puede poner c&oacute;digo auxiliar para ejecutar el parametro"><?php echo $this->modeloParametrosLaboratorios->getAtributosExtras(); ?></textarea>
        </div >
        <div data-linea="7" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="codigo"> Código </label> 
            <input type="text" id="codigo"
                   name="codigo"
                   value="<?php echo $this->modeloParametrosLaboratorios->getCodigo(); ?>"
                   placeholder="Código del parámetro, éste es utilizado en la programación por lo que una vez establecido no debe ser cambiado."
                   required maxlength="16" />
        </div>
        <div data-linea="7"></div>

        <div data-linea ="9">			
            <input type ="hidden" name="id_parametros_laboratorio" id="id_parametros_laboratorio" value ="<?php echo $this->modeloParametrosLaboratorios->getIdParametrosLaboratorio() ?>">			
            <input type ="hidden" name="atributos_extras" id="atributos_extras" value ="<?php echo $this->modeloParametrosLaboratorios->getAtributosExtras() ?>">

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
    $('#id_direccion option[value="<?php echo $this->modeloParametrosLaboratorios->getIdDireccion(); ?>"]').prop('selected', true);
    fn_cargarLaboratorios();

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#id_direccion").change(function () {
        fn_cargarLaboratorios();
    });

    function fn_cargarLaboratorios() {
        var idDireccion = $("#id_direccion").val();
        if (idDireccion !== "") {
            //Cargamos los laboratorios
            $.post("<?php echo URL ?>Laboratorios/ParametrosLaboratorios/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").html(data);
                $('#id_laboratorio option[value="<?php echo $this->modeloParametrosLaboratorios->getIdLaboratorio(); ?>"]').prop('selected', true);
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

