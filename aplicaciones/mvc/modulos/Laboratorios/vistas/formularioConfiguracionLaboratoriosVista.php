<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Laboratorios/guardarConfLab' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Configuraci&oacute;n de Laboratorios</legend>

        <div data-linea="1">
            <label for="id_direccion"> Dirección </label> <select
                id="id_direccion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones();
                ?>
            </select>
        </div>

        <div data-linea="1">
            <label for="id_laboratorio">Laboratorio</label> <select
                id="id_laboratorio" name="id_laboratorio" required>
            </select>
        </div>

        <div data-linea="2">
            <label for="fk_id_laboratorio"> Padre </label> 
            <select class="easyui-combotree" name="fk_id_laboratorio" id="fk_id_laboratorio"/>
        </div>
        <div data-linea="2">
            <label for="nombre"> Nombre </label> 
            <input type="text" id="nombre"
                   name="nombre" class="nombre"
                   value="<?php echo $this->modeloLaboratorios->getNombre(); ?>"
                   placeholder="Nombre de la dirección, laboratorio o su variable de configuración"
                   required maxlength="128" />
        </div>

        <div data-linea="3">
            <label for="descripcion"> Descripción </label> 
            <input type="text"
                   id="descripcion" name="descripcion"
                   value="<?php echo $this->modeloLaboratorios->getDescripcion(); ?>"
                   placeholder="Información complementaria de cada variable, que puede servir como ayuda en las pantallas de usuario"
                   maxlength="512" />
        </div>

        <div data-linea="3">
            <label for="tipo_campo"> Tipo de campo </label> 
            <select
                id="tipo_campo" name="tipo_campo" required>
                <option value="">Seleccionar....</option>
                <option value="CHECK">CHECK</option>
                <option value="COMBOBOX">COMBOBOX</option>
                <option value="ENTERO">ENTERO</option>
                <option value="DECIMAL">DECIMAL</option>
                <option value="ETIQUETA">ETIQUETA</option>
                <option value="FECHA">FECHA</option>
                <option value="CHECKLIST">CHECKLIST</option>
                <option value="BOOLEANO">BOOLEANO</option>
                <option value="SUBETIQUETA">SUBETIQUETA</option>
                <option value="TEXTO">TEXTO</option>
                <option value="TEXTAREA">TEXTAREA</option>
                <option value="BOTON">BOTON</option>
                <option value="PROVINCIA">PROVINCIA</option>
                <option value="CANTON">CANTON</option>
                <option value="PARROQUIA">PARROQUIA</option>
                <option value="CRONOGRAMA">CRONOGRAMA POST-REGISTRO</option>
                <option value="OCULTO">OCULTO</option>
            </select>
        </div>

        <div data-linea="4">
            <label for="ultimo_nivel"> Último nivel </label> <select
                id="ultimo_nivel" name="ultimo_nivel" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->crearComboSINO($this->modeloLaboratorios->getUltimoNivel());
                ?>
            </select>
        </div>

        <div data-linea="4">
            <label for="obligatorio"> Obligatorio </label> <select
                id="obligatorio" name="obligatorio" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->crearComboSINO($this->modeloLaboratorios->getObligatorio());
                ?>
            </select>
        </div>

        <div data-linea="5">
            <label for="nivel_acceso"> Nivel de acceso </label>
            <select
                id="nivel_acceso" name="nivel_acceso" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboNivelAcceso($this->modeloLaboratorios->getNivelAcceso());
                ?>
            </select>
        </div>

        <div data-linea="5">
            <label for="visible_en"> Donde es visible </label> <select
                id="visible_en" name="visible_en" required>
                <option value="">Seleccionar...</option>
                <option value="T">En todo</option>
                <option value="F">Solo formulario</option> 
                <!-- OT - Solo en el formulario de la solicitud para generar la orden de trabajo. -->
                <option value="OT" title="Solo en el formulario de la solicitud para generar la orden de trabajo">Solo orden de trabajo</option> 
                <!-- CA (Cargueras: Caso especial CARGUERAS para Entomologia, aplica cuando la solicitud es Multiusuario. -->
                <option value="CA" title="Caso especial CARGUERAS para Entomolog&iacute;a, aplica cuando la solicitud es Multiusuario">CARGUERAS</option>
                <!-- FR (Frutos): Caso especial para Entomologia, aplica cuando selecciona el servicio Frutos PNMMF. -->
                <option value="FR" title="Caso especial para Entomolog&iacute;a, aplica cuando selecciona el servicio Frutos PNMMF">Frutos PNMMF</option> 
                <option value="N">Ninguno</option>
            </select>
        </div>

        <div data-linea="6">
            <label for="orientacion">Orientaci&oacute;n</label> 
            <select id="orientacion" name="orientacion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDespliegue($this->modeloLaboratorios->getOrientacion());
                ?>
            </select>
        </div>

        <div data-linea="6">
            <label>Estado</label> 
            <select id="estado_registro" name="estado_registro" >

                <?php echo $this->combo2Estados($this->modeloLaboratorios->getEstadoRegistro()); ?>
            </select>
        </div>

        <div data-linea="7">
            <label for="orden"> Orden </label> 
            <input type="number" id="orden" name="orden"
                   value="<?php echo $this->modeloLaboratorios->getOrden(); ?>"
                   placeholder="Orden que se debe presentar en la pantalla" required/>
        </div>

        <div data-linea="7">
            <label for="data_linea"> Fila a desplegar </label> 
            <input type="number" id="data_linea" name="data_linea"
                   value="<?php echo $this->modeloLaboratorios->getDataLinea(); ?>"
                   placeholder="Indica como agrupar los elementos en el formulario, parte del core del sistema GUIA"/>
        </div>

        <?php
        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev')
        {
            echo '<label for="orden">Atributos (Visible en desarrollo)</label> 
                <div data-linea ="8">
             <textarea id="atributos" name="atributos" 
                      placeholder="Se puede agregar atributos en formato json/css " >' . $this->modeloLaboratorios->getAtributos() . '</textarea>
        </div>';
            echo '<label for="orden">Código ejecutable (Visible en desarrollo)</label> 
                <div data-linea ="9">
             <textarea id="codigo_ejecutable" name="codigo_ejecutable" 
                      placeholder="Se puede agregar atributos en formato html o javascript" >' . $this->modeloLaboratorios->getCodigoEjecutable() . '</textarea>
        </div>';

            echo '<div data-linea="10">
            <label for="codigo"> Código (Visible en desarrollo) </label> 
            <input type="text" id="codigo" name="codigo"
                   value="' . $this->modeloLaboratorios->getCodigo() . '"
                   placeholder="Código" required/>
        </div>';

            echo '<div data-linea="10">
            <label for="codigo_campo"> Código campo (Visible en desarrollo) </label> 
            <input type="text" id="codigo_campo" name="codigo_campo"
                   value="' . $this->modeloLaboratorios->getCodigoCampo() . '"
                   placeholder="Codigo especial para identificar el campo en los informes" required/>
        </div>';
        } else
        {
            echo ' <input type="hidden" id="atributos" name="atributos" value="' . $this->modeloLaboratorios->getAtributos() . '"/>';
        }
        ?>

        <div data-linea="11">
            <input type="hidden" name="nivel" id="nivel" value="2" />
            <input type="hidden" name="id_laboratorio" id="id_laboratorio" value="<?php echo $this->modeloLaboratorios->getIdLaboratorio() ?>">
            <button type="submit" class="guardar"> Guardar</button>
        </div>


    </fieldset>
</form>
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>

        distribuirLineas();
        $('#id_direccion option[value="<?php echo $this->direccion; ?>"]').prop('selected', true);
        $('#tipo_campo option[value="<?php echo $this->modeloLaboratorios->getTipoCampo(); ?>"]').prop('selected', true);
        $('#visible_en option[value="<?php echo $this->modeloLaboratorios->getVisibleEn(); ?>"]').prop('selected', true);

        fn_cargarLaboratorios();

        //Para cargar los laboratorios una vez sleccionado la dirección
        function fn_cargarLaboratorios() {
            var idDireccion = $("#id_direccion").val();
            if (idDireccion !== "") {
                $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboLaboratorios/" + idDireccion, function (data) {
                    $("#id_laboratorio").html(data);
                    $('#id_laboratorio option[value="<?php echo $this->laboratorio; ?>"]').prop('selected', true);
                    fn_cargarNodosHijos();
                });
            }
        }

        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#id_direccion").change(function () {
            fn_cargarLaboratorios();
        });

        //Cargamos los servicios del sistema GUIA
        $("#id_laboratorio").change(function () {
            fn_cargarNodosHijos();
        });

        //Para obtener los datos del servicio padre como el nivel
        function fn_obtenerDatosPadre(idServicio) {
            $.post("<?php echo URL ?>Laboratorios/Servicios/buscarServicio/" + idServicio, function (data) {
                $("#nivel").val(parseInt(data.nivel) + 1);
                fn_habilitarCmbSistemaGuia();
            }, 'json');
        }

        //Para los nodos hijos del laboratorio seleccionado
        function fn_cargarNodosHijos() {
            var idLaboratorio = $("#id_laboratorio").val();
            if (idLaboratorio !== "") {
                //Cargamos el combotree segun el laboratorio seleccionado
                $.post("<?php echo URL ?>Laboratorios/Laboratorios/buscarNodos/" + idLaboratorio, function (data) {
                    $('#fk_id_laboratorio').combotree({
                        data: data,
                        editable: true
                    });
                    if ('<?php echo $this->crearHijo; ?>' === '1') {
                        if ($("#id_laboratorio").val() !== auxIdPadre.toString()) {
                            $('#fk_id_laboratorio').combotree('setValue', auxIdPadre);
                        }
                    } else {
                        $('#fk_id_laboratorio').combotree('setValue', '<?php echo $this->modeloLaboratorios->getFkIdLaboratorio(); ?>');
                    }
                }, 'json');
            }
        }

        $("#formulario").submit(function (event) {

            var val = $('#fk_id_laboratorio').combotree('getValue');
            if (val === '') {
                mostrarMensaje("Campo Padre es requerido.", "FALLO");
                error = true;
                return false;
            }
            idExpandir = val;
            event.preventDefault();
            var error = false;
            if (!error) {
                var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado == 'exito')
                {
                    fn_filtrar();
                }
                $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }
        });
    });
</script>
