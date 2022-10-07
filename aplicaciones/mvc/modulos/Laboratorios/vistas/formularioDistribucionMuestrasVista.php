			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'	
      data-opcion = 'DistribucionMuestras/guardar' data-destino ="detalleItem"	
      data-accionEnExito ="NADA" method="post">	
    <fieldset><legend>Distribución Muestras</legend>
        <div data-linea="1">
            <label for="id_direccion"> Direcci&oacute;n </label> 
            <select id="id_direccion" name="id_direccion">
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloDistribucionMuestras->getIdDireccion());
                ?>
            </select>
        </div>
        <div data-linea="1">
            <label>Laboratorios</label> 
            <select id="id_laboratorio" name="id_laboratorio" disabled="disabled">
            </select>
        </div>

        <div data-linea="2">
            <label for="id_servicio">Servicio</label> 
            <select class="easyui-combotree" name="id_servicio" id="id_servicio">
            </select>
        </div>


        <div data-linea="3" id="div_provOrigenMuestra" class="cDatosGenerales">
            <label>Provincia del laboratorio </label> 
            <select id="id_laboratorios_provincia" name="id_laboratorios_provincia" disabled="disabled">
            </select>

        </div>

        <div data-linea="3">
            <label>Estado del registro</label> 
            <select
                id="estado_registro" name="estado_registro" >

                <?php echo $this->combo3Estados($this->modeloDistribucionMuestras->getEstadoRegistro()); ?>
            </select>

        </div>

        <div data-linea="4" id="div_provOrigenMuestra" class="cDatosGenerales">
            <label>Provincia atendida</label> 
            <select
                id="id_localizacion" name="id_localizacion" >
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboProvinciasEc($this->modeloDistribucionMuestras->getIdLocalizacion());
                ?>
            </select>
        </div>

        <label for="mensaje_publico"> Mensaje </label>
        <div data-linea="5">
            <textarea id="mensaje_publico" name="mensaje_publico"
                      placeholder="Ingrese un mensaje"><?php echo $this->modeloDistribucionMuestras->getmensajePublico(); ?></textarea>
        </div>

        <div data-linea ="6">			
            <input type ="hidden" name="id_distribucion_muestra" id="id_distribucion_muestra" value ="<?php echo $this->modeloDistribucionMuestras->getIdDistribucionMuestra() ?>">	
            <button type ="submit" class="guardar"> Guardar</button>
        </div >
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
    });

    fn_cargarLaboratorios();

    //Para cargar los laboratorios una vez sleccionado la dirección
    function fn_cargarLaboratorios() {
        var idDireccion = $("#id_direccion").val();
        if (idDireccion !== "") {
            $.post("<?php echo URL ?>Laboratorios/DistribucionMuestras/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").removeAttr("disabled");
                $("#id_laboratorio").html(data);
                $('#id_laboratorio option[value="<?php echo $this->modeloDistribucionMuestras->getIdLaboratorio(); ?>"]').prop('selected', true);
                fn_cargarServicios();
            });
        }
    }

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#id_direccion").change(function () {
        if ($(this).val !== "") {

            fn_cargarLaboratorios();
        }
    });

    function fn_cargarServicios() {
        idLaboratorio = $("#id_laboratorio").val();


        if (idLaboratorio !== "") {
            //Cargamos el combotree segun el laboratorio seleccionado
            $.post("<?php echo URL ?>Laboratorios/TiemposRespuesta/buscarServiciosPadre/" + idLaboratorio, function (data) {
                $('#id_servicio').combotree({
                    data: data
                });
                $('#id_servicio').combotree('setValue', '<?php echo $this->modeloDistribucionMuestras->getIdServicio(); ?>');

            }, 'json');

            //las provincas donde esta el laboratorio
            $.post("<?php echo URL ?>Laboratorios/DistribucionMuestras/comboLaboratoriosProvincia/" + idLaboratorio, function (data) {
                $("#id_laboratorios_provincia").removeAttr("disabled");
                $("#id_laboratorios_provincia").html(data);
                $('#id_laboratorios_provincia option[value="<?php echo $this->modeloDistribucionMuestras->getIdLaboratoriosProvincia(); ?>"]').prop('selected', true);
            });
        }
    }



    //Cuando seleccionamos un laboratorio, llenamos el combo de servicios
    $("#id_laboratorio").change(function () {
        if ($(this).val !== "") {
            fn_cargarServicios();
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
</script>
