<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Servicios/guardar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Servicio</legend>

        <div data-linea="1">
            <label for="id_direccion"> Dirección </label> 
            <select id="id_direccion" name="id_direccion" required <?php echo $this->campo_disabled ?>>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloLaboratorios->getFkIdLaboratorio());
                ?>
            </select>
        </div>

        <div data-linea="1">
            <label for="id_laboratorio">Laboratorio</label> 
            <select id="id_laboratorio" name="id_laboratorio" required <?php echo $this->campo_disabled ?>>
            </select>
        </div>

        <div data-linea="2">
            <label for="fk_id_servicio"> Padre </label> 
            <select class="easyui-combotree" name="fk_id_servicio" id="fk_id_servicio" <?php echo $this->campo_disabled ?> />
        </div>


        <?php
        //Este combo es necesario únicamente cuando es un nuevo servicio nivel=0
        if ($this->visible_id_servicio_guia)
            echo'  <div data-linea="3">
            <label for="id_servicio_guia"> Sistema GUIA </label> <select
                id="id_servicio_guia" name="id_servicio_guia">
            </select>
        </div>';
        ?>

        <div data-linea="3">
            <label for="nombre"> Nombre </label> <input
                type="text" id="nombre" name="nombre"
                value="<?php echo $this->modeloServicios->getNombre(); ?>"
                placeholder="Nombre del sevicio" required />
        </div>

        <div data-linea="4">
            <label for="codigo_analisis"> C&oacute;digo </label> 
            <input type="text" id="codigo_analisis" name="codigo_analisis"
                   value="<?php echo $this->modeloServicios->getCodigoAnalisis(); ?>"
                   placeholder="Código para identificar el nombre del servicio"
                   maxlength="64" />
        </div>
        
        <div data-linea="4">
            <label for="orden"> C&oacute;digo Especial </label> 
            <input type="text" id="codigo_especial" name="codigo_especial"
                   value="<?php echo $this->modeloServicios->getCodigoEspecial(); ?>"
                   placeholder="C&oacute;digo Especial" maxlength="128"/>
        </div>
        
        <div data-linea="5">
            <label for="parametro"> Parámetro </label> 
            <input type="text" id="parametro" name="parametro"
                   value="<?php echo $this->modeloServicios->getParametro(); ?>"
                   placeholder="Nombre del parámetro de análisis" 
                   maxlength="128" />
        </div>

        <div data-linea="5">
            <label for="metodo"> Método </label> 
            <input type="text" id="metodo" name="metodo"
                   value="<?php echo $this->modeloServicios->getMetodo(); ?>"
                   placeholder="Método del análisis" maxlength="64"/>
        </div>

        <div data-linea="6">
            <label for="tecnica"> Técnica </label> 
            <input type="text" id="tecnica" name="tecnica"
                   value="<?php echo $this->modeloServicios->getTecnica(); ?>"
                   placeholder="Técnica utilizada para el análisis" 
                   maxlength="128" />
        </div>

        <div data-linea="6">
            <label for="metodo_referencia"> Método de referencia </label> 
            <input type="text" id="metodo_referencia" name="metodo_referencia"
                   value="<?php echo $this->modeloServicios->getMetodoReferencia(); ?>"
                   placeholder="Método externo de referencia para el análisis" maxlength="64"/>
        </div>

        <div data-linea="7">
            <label for="´tipo"> Tipo </label> 
            <select id="tipo" name="tipo" required>
                <option value="">Seleccione..</option>
                <option value="INDIVIDUAL" <?php echo ($this->modeloServicios->getTipo() == 'INDIVIDUAL') ? 'Selected' : ''; ?>>INDIVIDUAL</option>
                <option value="PAQUETE" <?php echo ($this->modeloServicios->getTipo() == 'PAQUETE') ? 'Selected' : ''; ?>>PAQUETE</option>
                <option value="ELEMENTO" <?php echo ($this->modeloServicios->getTipo() == 'ELEMENTO') ? 'Selected' : ''; ?>>ELEMENTO</option>
            </select>
        </div>

        <div data-linea="7">
            <label for="acreditacion"> ¿Es acreditado? </label> 
            <select
                id="acreditacion" name="acreditacion" required="true">
                <?php
                echo $this->crearComboSINO($this->modeloServicios->getAcreditacion());
                ?>
                <option value="PAQUETE" >SI (ELEMENTO DE UN PAQUETE)</option>
            </select>
        </div>

        <div data-linea="8">
            <label for="orden"> Orden </label> 
            <input type="number" id="orden" name="orden"
                   value="<?php echo $this->modeloServicios->getOrden(); ?>"
                   placeholder="Orden al desplegarse en la pantalla" required
                   maxlength="3" min="1"/>
        </div>

        <div data-linea="8">
            <label>Estado</label> 
            <select name="estado">
                <?php echo $this->comboEstadosServicios($this->modeloServicios->getEstado()); ?>
            </select>
        </div>

        <label for="requisitos"> Requisitos </label>
        <div data-linea="9">
            <textarea id="requisitos" name="requisitos" 
                      placeholder=""><?php echo $this->modeloServicios->getRequisitos(); ?></textarea>
        </div>

        <div data-linea="10">
            <input type="hidden" name="id_servicio" id="id_servicio"
                   value="<?php echo $this->modeloServicios->getIdServicio() ?>">
            <button type="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>
<!-- Código javascript -->
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();

        //Seleccionar la dirección guardada
        if (crearHijo === 1) {
            $("#id_direccion option[value=" + auxIdDireccion + "]").prop('selected', true);
        } else {
            $('#id_direccion option[value="<?php echo $this->modeloServicios->getIdDireccion(); ?>"]').prop('selected', true);
        }
        fn_cargarLaboratorios();

        tinymce.init({
            selector: '#requisitos',
            language: 'es',
            height: 80,
            menubar: false,
            plugins: [
                'advlist lists link image link'
            ],
            toolbar: 'insert | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link'
        });

        //Para habilitar el combo de servicios GUIA siempre que el Nivel sea 0
        //Necesario únicamente para relacionar el nodo raíz (nivel 0)
        function fn_habilitarCmbSistemaGuia() {
            if ('<?php echo $this->modeloServicios->getNivel(); ?>' === "0") {
                $("#id_servicio_guia").removeAttr("disabled");
                $("#id_servicio_guia").attr("required", true);
            } else {
                $("#id_servicio_guia option").attr("selected", false);
                $("#id_servicio_guia").attr("disabled", "disabled");
                $("#id_servicio_guia").attr("required", false);
            }
        }

        //Para cargar los laboratorios una vez sleccionado la dirección
        function fn_cargarLaboratorios() {
            var idDireccion = $("#id_direccion").val();
            if (idDireccion !== "") {
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboLaboratorios/" + idDireccion, function (data) {
                    $("#id_laboratorio").html(data);
                    if (crearHijo === 1) {
                        $("#id_laboratorio option[value=" + auxIdLaboratorio + "]").prop('selected', true);
                    } else {
                        $('#id_laboratorio option[value="<?php echo $this->modeloServicios->getIdLaboratorio(); ?>"]').prop('selected', true);
                    }
                    fn_cargarServicios();
                });
            }
        }

        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#id_direccion").change(function () {
            fn_cargarLaboratorios();
        });

        //Para gargar los servicios
        function fn_cargarServicios() {
            var idLaboratorio = $("#id_laboratorio").val();
            if (idLaboratorio !== "") {
                //OJO verificar
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicioGUIA/" + idLaboratorio, function (data) {
                    $("#id_servicio_guia").html(data);
                    $('#id_servicio_guia option[value="<?php echo $this->modeloServicios->getIdServicioGuia(); ?>"]').prop('selected', true);
                });

                //Cargamos el combotree segun el laboratorio seleccionado
                $.post("<?php echo URL ?>Laboratorios/Servicios/buscarServiciosPadre/" + idLaboratorio, function (data) {
                    $('#fk_id_servicio').combotree({
                        data: data,
                        editable: true,
                        onClick: function (node) {
                            fn_obtenerDatosPadre(node.id);
                        }
                    });
                    if (crearHijo === 1) {
                        $('#fk_id_servicio').combotree('setValue', auxIdPadre);
                        fn_obtenerDatosPadre(auxIdPadre);
                    } else {
                        $('#fk_id_servicio').combotree('setValue', '<?php echo $this->modeloServicios->getFkIdServicio(); ?>');
                    }
                }, 'json');
            }
        }

        //Cargamos los servicios del sistema GUIA
        $("#id_laboratorio").change(function () {
            fn_cargarServicios();
        });

        //Para obtener los datos del servicio padre como el nivel
        function fn_obtenerDatosPadre(idServicio) {
            $.post("<?php echo URL ?>Laboratorios/Servicios/buscarServicio/" + idServicio, function (data) {
                $("#nivel").val(parseInt(data.nivel) + 1);
            }, 'json');
        }

        $("#formulario").submit(function (event) {
            var val = $('#id_laboratorio').val() + "-" + $('#fk_id_servicio').combotree('getValue');
            idExpandir = val;
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