<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>		
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion = 'CamposResultadosInformes/guardar' data-destino ="detalleItem"			
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Campos de Resultados de Informes</legend>

        <div data-linea="1">
            <label for="id_direccion"> Dirección </label> 
            <select id="id_direccion" name="id_direccion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloCamposResultadosInformes->getIdDireccion());
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
            <label for="fk_id_campos_resultados_inf"> Padre </label> 
            <select class="easyui-combotree" name="fk_id_campos_resultados_inf" id="fk_id_campos_resultados_inf"/>
        </div>

        <div data-linea ="3">
            <label for="nombre"> Nombre</label> 
            <input type ="text" id="nombre"
                   name ="nombre" value="<?php echo $this->modeloCamposResultadosInformes->getNombre(); ?>"
                   placeholder ="Nombre del campo de resultados"
                   required  maxlength="512" />
        </div>

        <div data-linea="3">
            <label for="tipo_campo"> Tipo de campo </label> 
            <select
                id="tipo_campo" name="tipo_campo" required="true">
                <option value="">Seleccionar....</option>
                <option value="CONTENEDOR">CONTENEDOR</option>
                <option value="CHECK">CHECK</option>
                <option value="COMBOBOX">COMBOBOX</option>
                <option value="ENTERO">ENTERO</option>
                <option value="DECIMAL">DECIMAL</option>
                <option value="ETIQUETA">ETIQUETA</option>
                <option value="FECHA">FECHA</option>
                <option value="CHECKLIST">CHECKLIST</option>
                <option value="BOOLEANO">BOOLEANO</option>
                <option value="RADIOBUTTON">RADIOBUTTON</option>
                <option value="SUBETIQUETA">SUBETIQUETA</option>
                <option value="TEXTO">TEXTO</option>
                <option value="TEXTAREA">TEXTAREA</option>
            </select>
        </div>

        <div data-linea = "5" >
            <label for="obligatorio">Obligatorio </label> <select
                id = "obligatorio" name = "obligatorio" required>
                <option value = ""> Seleccionar....</option >
                <?php
                echo $this->crearComboSINO($this->modeloCamposResultadosInformes->getObligatorio());
                ?>
            </select>
        </div>

        <div data-linea="5">
            <label>Estado</label> 
            <select id="estado_registro" name="estado_registro" required>
                <?php echo $this->combo2Estados($this->modeloCamposResultadosInformes->getEstadoRegistro()); ?>
            </select>
        </div>

        <div data-linea = "5" >
            <label for="despliegue">Despliegue </label> <select
                id = "despliegue" name = "despliegue" required>
                <option value = ""> Seleccionar....</option >
                <?php
                echo $this->comboDespliegue($this->modeloCamposResultadosInformes->getDespliegue());
                ?>
            </select>
        </div>

        <div data-linea ="6">
            <label for="valor_defecto">Valor por defecto</label> 
            <input type ="text" id="valor_defecto"
                   name ="valor_defecto" value="<?php echo $this->modeloCamposResultadosInformes->getValorDefecto(); ?>"
                   placeholder ="Valor por defecto del campo"
                   maxlength="256" />
        </div>

        <div data-linea ="6">
            <label for="orden">Orden</label> 
            <input type ="text" id="orden"
                   name ="orden" value="<?php echo $this->modeloCamposResultadosInformes->getOrden(); ?>"
                   placeholder ="Orden que se debe presentar en la pantalla"
                   required  maxlength="512" />
        </div>
        <label for="interpretacion_resultado" > Interpretación</label> 
        <div data-linea ="10" >

            <textarea id="interpretacion_resultado" name="interpretacion_resultado"  
                      placeholder="Tabla de interpretación de resultados"><?php echo $this->modeloCamposResultadosInformes->getAtributosExtras(); ?></textarea>
        </div>

        <div data-linea ="9" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="codigo"> Código (Desarrollo)</label>
            <input type ="text" id="codigo"
                   name ="codigo" value="<?php echo $this->modeloCamposResultadosInformes->getCodigo(); ?>"
                   placeholder ="Código del parámetro, este es utilizado en la programacion por lo que una vez establecido no debe ser cambiado."
                   maxlength="512" />
        </div>
        <label for="atributos_extras" style="visibility: <?php echo $this->devVisible(); ?>"> Atributos (Desarrollo) </label> 
        <div data-linea ="8" style="visibility: <?php echo $this->devVisible(); ?>">

            <textarea id="atributos_extras" name="atributos_extras" 
                      placeholder="Se puede poner c&oacute;digo auxiliar para ejecutar el parametro"><?php echo $this->modeloCamposResultadosInformes->getAtributosExtras(); ?></textarea>
        </div>

        <div data-linea ="17">			
            <input type ="hidden" name="id_campos_resultados_inf" id="id_campos_resultados_inf" value ="<?php echo $this->modeloCamposResultadosInformes->getIdCamposResultadosInf() ?>">			
            <button type="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
        construirValidador();

        $('#id_direccion option[value="<?php echo $this->modeloCamposResultadosInformes->getIdDireccion(); ?>"]').prop('selected', true);
        $('#tipo_campo option[value="<?php echo $this->modeloCamposResultadosInformes->getTipoCampo(); ?>"]').prop('selected', true);
        fn_cargarLaboratorios();
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#id_direccion").change(function () {
            fn_cargarLaboratorios();
        });

        function fn_cargarLaboratorios() {
            var idDireccion = $("#id_direccion").val();
            if (idDireccion !== "") {
                //Cargamos los laboratorios
                $.post("<?php echo URL ?>Laboratorios/CamposResultadosInformes/comboLaboratorios/" + idDireccion, function (data) {
                    $("#id_laboratorio").html(data);
                    $('#id_laboratorio option[value="<?php echo $this->modeloCamposResultadosInformes->getIdLaboratorio(); ?>"]').prop('selected', true);
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
                //Cargamos el combotree segun el laboratorio seleccionado
                $.post("<?php echo URL ?>Laboratorios/CamposResultadosInformes/comboServiciosSinJoinGuia/" + idLaboratorio, function (data) {
                    $("#id_servicio").html(data);
                    $('#id_servicio option[value="<?php echo $this->modeloCamposResultadosInformes->getIdServicio(); ?>"]').prop('selected', true);
                    fn_cargarCampos(<?php echo $this->modeloCamposResultadosInformes->getIdServicio(); ?>);
                });
            }
        }

        $("#id_servicio").change(function () {
            fn_cargarCampos($(this).val());
        });

        function fn_cargarCampos(idServicio) {
            //Cargamos el combotree segun el servicio seleccionado
            if (idServicio !== "") {
                $.post("<?php echo URL ?>Laboratorios/CamposResultadosInformes/buscarCamposPadre/" + idServicio, function (data) {
                    $('#fk_id_campos_resultados_inf').combotree({
                        data: data
                    });
                    $('#fk_id_campos_resultados_inf').combotree('setValue', '<?php echo $this->modeloCamposResultadosInformes->getFkIdCamposResultadosInf(); ?>');
                }, 'json');
            }
        }

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
    });
</script>
