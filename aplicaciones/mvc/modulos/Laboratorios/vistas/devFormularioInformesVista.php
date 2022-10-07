<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios' 
      data-opcion = 'Informes/guardar' data-destino ="detalleItem"
      data-accionEnExito ="NADA" method="post">
    <fieldset>
        <legend>Informes</legend>

        <div data-linea="1"> 	
            <label for="id_direccion"> Dirección </label> 
            <select id="id_direccion" name="id_direccion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloInformes->getIdDireccion());
                ?>
            </select>
        </div>      
        <div data-linea="1">
            <label>Laboratorios</label> 
            <select id="fk_id_laboratorio" name="fk_id_laboratorio" disabled="disabled" required>
            </select>
        </div>   

        <div data-linea="2" >
            <label for="fk_id_informe">Informe padre </label> 
            <select class="easyui-combotree" name="fk_id_informe" id="fk_id_informe">
            </select>
        </div>

        <div data-linea = "3" >
            <label for="tipo_campo"> Tipo de campo </label> 
            <select id="tipo_campo" name="tipo_campo" required>
                <?php
                echo $this->tipoCampoInforme($this->modeloInformes->getTipoCampo());
                ?>
            </select>
        </div >

        <div data-linea="4" id="camposOT" style="visibility: hidden">
            <label for="id_laboratorio"> Campos de la orden de trabajo </label> 
            <select class="easyui-combotree" name="id_laboratorio" id="id_laboratorio"> </select>
        </div>

        <div data-linea="5" id="camposRE" style="visibility: hidden">
            <label for="id_campos_resultados_inf"> Campos resultado de análisis </label> 
            <select class="easyui-combotree" name="id_campos_resultados_inf" id="id_campos_resultados_inf">
            </select>
        </div>

        <div data-linea ="6">
            <label for="nombre_informe"> Nombre </label> 
            <input type ="text" id="nombre_informe"
                   name ="nombre_informe" value="<?php echo $this->modeloInformes->getNombreInforme(); ?>"
                   placeholder ="Nombre de informe"
                   required  maxlength="1024" />
        </div >
        <div data-linea ="6">
            <label for="titulo_informe"> Título del informe </label> 
            <input type ="text" id="titulo_informe"
                   name ="titulo_informe" value="<?php echo $this->modeloInformes->getTituloInforme(); ?>"
                   placeholder ="Título del informe"
                    maxlength="1024" />
        </div >
        <div data-linea ="7" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="codigo"> Código </label> 
            <input type ="text" id="codigo"
                   name ="codigo" value="<?php echo $this->modeloInformes->getCodigo(); ?>"
                   placeholder ="Código del informe"
                   maxlength="32" />
        </div >


        <div data-linea ="7">
            <label for="orden"> Orden </label> 
            <input type ="number" id="orden"
                   name ="orden" value="<?php echo $this->modeloInformes->getOrden(); ?>"
                   placeholder ="Ordenan los campos del informe"
                   required  />
        </div >
        <div data-linea="8">
            <label for="estado_registro"> Estado </label> 
            <select
                id="estado_registro" name="estado_registro" >

                <?php echo $this->combo2Estados($this->modeloInformes->getEstadoRegistro()); ?>
            </select>
        </div>
        <div data-linea ="8" style="visibility: <?php echo $this->visibility($this->modeloInformes->getTipoCampo()); ?>" >
            <label for="revision"> No. de Revision </label> 
            <input type ="text" id="revision"
                   name ="revision" value="<?php echo $this->modeloInformes->getRevision(); ?>"
                   placeholder ="No. de Revisión" maxlength="16"/>
        </div >
        <div  data-linea = "8" style="visibility: <?php echo $this->visibility($this->modeloInformes->getTipoCampo()); ?>"  >
            <label for="orientacion"> Orientación </label> 
            <select id = "orientacion" name = "orientacion">
                <option value = ""> Seleccionar....</option >
                <?php
                echo $this->comboDespliegue($this->modeloInformes->getOrientacion());
                ?>
            </select>
        </div >

        <div data-linea ="9" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="codigo_sql"> Código SQL </label> 
            <textarea rows="4" cols="50" id="codigo_sql" name="codigo_sql" 
                      placeholder="Código SQL"><?php echo $this->modeloInformes->getCodigoSql(); ?></textarea>
        </div >
        <div data-linea ="10" style="visibility: <?php echo $this->devVisible(); ?>">
            <label for="atributos"> Atributos HTML </label> 
            <textarea rows="4" cols="50" id="atributos" name="atributos" 
                      placeholder="atributos HTML"><?php echo $this->modeloInformes->getAtributos(); ?></textarea>
        </div >

        <div data-linea ="11" >	
            <input type ="hidden" name="id_informe" id="id_informe" value ="<?php echo $this->modeloInformes->getIdInforme() ?>">
            <button type ="submit" class="guardar"> Guardar</button>
        </div >
    </fieldset >
</form>
<!-- Código javascript -->
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();

        //Seleccionar la dirección guardada
        if (crearHijo === 1) {
            $("#id_direccion option[value=" + auxIdDireccion + "]").prop('selected', true);
        } else {
            $('#id_direccion option[value="<?php echo $this->modeloInformes->getIdDireccion(); ?>"]').prop('selected', true);
        }
        fn_cargarLaboratorios();

        //Para cargar los laboratorios una vez seleccionado la dirección
        function fn_cargarLaboratorios() {
            var idDireccion = $("#id_direccion").val();
            if (idDireccion !== "") {
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboLaboratorios/" + idDireccion, function (data) {
                    $("#fk_id_laboratorio").html(data);
                    $("#fk_id_laboratorio").removeAttr("disabled");
                    if (crearHijo === 1) {
                        $("#fk_id_laboratorio option[value=" + auxIdLaboratorio + "]").prop('selected', true);
                    } else {
                        $('#fk_id_laboratorio option[value="<?php echo $this->modeloInformes->getFkIdLaboratorio(); ?>"]').prop('selected', true);
                        fn_cargarCampos(<?php echo $this->modeloInformes->getFkIdLaboratorio(); ?>);
                    }
                });
            }
        }

        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#id_direccion").change(function () {
            fn_cargarLaboratorios();
        });

        //Evento seleccionar un laboratorio
        $("#fk_id_laboratorio").change(function () {
            fn_cargarCampos($(this).val());
        });

        function fn_cargarCampos(idLaboratorio) {
            //Cargamos el combotree segun el servicio seleccionado
            if (idLaboratorio !== "") {
                $.post("<?php echo URL ?>Laboratorios/Informes/cargarInformes/" + idLaboratorio, function (data) {
                    $('#fk_id_informe').combotree({
                        data: data
                    });
                    $('#fk_id_informe').combotree('setValue', '<?php echo $this->modeloInformes->getFkIdInforme(); ?>');
                }, 'json');
            }
        }


        $("#tipo_campo").change(function () {
            $("#camposRE").css('visibility', 'hidden');
            $("#camposOT").css('visibility', 'hidden');
            if ($("#tipo_campo").val() == 'CAMPOOT') {
                //carga el arbol combo con los campos de la orden de trabajo camposOT

                $("#camposOT").css('visibility', 'visible');
                $('#id_laboratorio').combotree({
                    url: '<?php echo URL ?>Laboratorios/Informes/cargarCamposOT/' + $("#fk_id_laboratorio").val(),
                    editable: true
                });
                $('#id_laboratorio').combotree('setValue', <?php echo $this->modeloInformes->getIdLaboratorio(); ?>);
            } else if ($("#tipo_campo").val() == 'CAMPORE') {

                $("#camposRE").css('visibility', 'visible');
                //carga el arbol combo con los campos de la orden de trabajo
                $('#id_campos_resultados_inf').combotree({
                    url: '<?php echo URL ?>Laboratorios/Informes/cargarCamposRE/' + $("#fk_id_laboratorio").val(),
                    editable: true
                });
                $('#id_campos_resultados_inf').combotree('setValue', <?php echo $this->modeloInformes->getIdCamposResultadosInf(); ?>);
            }
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
    });
</script>