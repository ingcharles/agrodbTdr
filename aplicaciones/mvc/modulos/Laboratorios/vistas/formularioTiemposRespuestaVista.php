			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios' 
      data-opcion = 'TiemposRespuesta/guardar' data-destino ="detalleItem" 
      data-accionEnExito ="NADA" method="post">

    <fieldset>			
        <legend>Tiempos de Respuesta</legend>

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
        <div data-linea="3" id="div_provOrigenMuestra" class="cDatosGenerales">
            <label>Provincia del laboratorio </label> 

            <select id="id_laboratorios_provincia" name="id_laboratorios_provincia" disabled="disabled">
            </select>

        </div>
        <div data-linea = "2" >
            <label for="tipo"> Tipo de laboratorio</label> 
            <select id = "tipo_laboratorio" name = "tipo_laboratorio" required>
                <option value = ""> Seleccionar....</option >
                <?php
                echo $this->tipoLaboratorio($this->modeloTiemposRespuesta->getTipoLaboratorio());
                ?>
            </select>
        </div>

        <div data-linea="2">
            <label>Estado</label> 
            <select
                id="estado_registro" name="estado_registro" >

                <?php echo $this->combo2Estados($this->modeloTiemposRespuesta->getEstadoRegistro()); ?>
            </select>
        </div>

        <div data-linea="3">
            <label for="id_servicio">Servicio</label> 
            <select class="easyui-combotree" name="id_servicio" id="id_servicio">
            </select>
        </div>

        <div data-linea ="4">
            <label for="operador"> Condici&oacute;n </label> 
            <select id="operador" name="operador" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->operadorComparacion(preg_replace('/[0-9]+/', '', $this->modeloTiemposRespuesta->getCondicion()));
                ?>
            </select>
        </div>

        <div data-linea ="4">
            <input type ="text" id="condicion"
                   name ="condicion" value=" <?php echo intval(preg_replace('/[^0-9]+/', '', $this->modeloTiemposRespuesta->getCondicion()), 10); ?>"
                   placeholder ="Valor. "
                   maxlength="512" />
        </div>

        <div data-linea = "4" >
            <label for="tiempo_respuesta"> Tiempo de respuesta </label> 
            <input type="number" id="tiempo_respuesta" min="1" max="99"
                   name="tiempo_respuesta"
                   value="<?php echo $this->modeloTiemposRespuesta->getTiempoRespuesta(); ?>"
                   placeholder="Entero"
                   required maxlength="2" 
                   title="El tiempo de respuesta está determinado en días"/>
        </div>

        <div data-linea="5">
            <label for="tipo_usuario"> Tipo de usuario </label> 
            <select id="tipo_usuario" name="tipo_usuario" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->tipoUsuario($this->modeloTiemposRespuesta->getTiempoRespuesta());
                ?>
            </select>
        </div>

        <div data-linea ="5">
            <label for="descripcion"> Descripci&oacute;n </label> 
            <input type ="text" id="descripcion"
                   name ="descripcion" value="<?php echo $this->modeloTiemposRespuesta->getDescripcion(); ?>"
                   placeholder ="Descripci&oacute;n del tiempo de respuesta"
                   maxlength="512" />
        </div>

        <div data-linea ="9">			
            <input type ="hidden" name="id_tiempos_respuesta" id="id_tiempos_respuesta" value ="<?php echo $this->modeloTiemposRespuesta->getIdTiemposRespuesta() ?>">			
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset >
</form >

<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
        construirValidador();
    });
    $("#formulario").submit(function (event) {
        //concatenamos la condición
        $("#condicion").val($("#operador").val() + $("#condicion").val());

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

    //Seleccionar la dirección guardada
    $('#id_direccion option[value="<?php echo $this->modeloTiemposRespuesta->getIdDireccion(); ?>"]').prop('selected', true);

    fn_cargarLaboratorios();

    function fn_cargarLaboratorios() {
        idDireccion = $("#id_direccion").val();
        if (idDireccion !== "") {
            //Cargamos los laboratorios
            $.post("<?php echo URL ?>Laboratorios/TiemposRespuesta/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").html(data);
                $('#id_laboratorio option[value="<?php echo $this->modeloTiemposRespuesta->getIdLaboratorio(); ?>"]').prop('selected', true);
                fn_cargarServicios();
            });
        }
    }

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#id_direccion").change(function () {
        fn_cargarLaboratorios();
    });

    function fn_cargarServicios() {
        idLaboratorio = $("#id_laboratorio").val();
        if (idLaboratorio !== "") {
            //Cargamos el combotree segun el laboratorio seleccionado
            $.post("<?php echo URL ?>Laboratorios/TiemposRespuesta/buscarServiciosPadre/" + idLaboratorio, function (data) {
                $('#id_servicio').combotree({
                    data: data
                });
                $('#id_servicio').combotree('setValue', '<?php echo $this->modeloTiemposRespuesta->getIdServicio(); ?>');

            }, 'json');
            
            //las provincas donde esta el laboratorio
            $.post("<?php echo URL ?>Laboratorios/DistribucionMuestras/comboLaboratoriosProvincia2/" + idLaboratorio, function (data) {
                $("#id_laboratorios_provincia").removeAttr("disabled");
                $("#id_laboratorios_provincia").html(data);
                $('#id_laboratorios_provincia option[value="<?php echo $this->modeloTiemposRespuesta->getIdLaboratoriosProvincia(); ?>"]').prop('selected', true);
            });
        }
    }

    //Cargamos los servicios
    $("#id_laboratorio").change(function () {
        fn_cargarServicios();
    });

</script>
