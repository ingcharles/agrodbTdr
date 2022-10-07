			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			
      data-opcion = 'servicios/guardarCopia' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Copiar: <?php echo $this->nombreCampoRaiz ?></legend>



        <hr>
        <fieldset class="fieldsetInterna">			
            <legend class='legendInterna'>Seleccione el servicio donde quiere copiar</legend>
            <div data-linea="1">
                <label for="id_direccion"> Dirección </label> 
                <select id="id_direccion" name="id_direccion" required>
                    <option value="">Seleccionar....</option>
                    <?php
                    echo $this->comboDirecciones($this->modeloServicios->getIdLaboratorio());
                    ?>
                </select>
            </div>

            <div data-linea="1">
                <label for="id_laboratorio">Laboratorio</label> 
                <select id="id_laboratorio" name="id_laboratorio" required>

                </select>
            </div>

            <div data-linea="2">
                <label for="id_servicio"> Servicio (Nuevo no padre) </label> 
                <select class="easyui-combotree" name="id_servicio" id="id_servicio"  />
            </div>

            <div data-linea ="101">			
                <input type="hidden" id="fk_id_servicio" name="fk_id_servicio" value="<?php echo $this->idCampoRaiz ?>"/>
                <button type ="submit" class="far fa-clone"> Copiar </button>
            </div >
        </fieldset>
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
    });

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#id_direccion").change(function () {
        fn_cargarLaboratorios();
    });

    function fn_cargarLaboratorios() {
        var idDireccion = $("#id_direccion").val();
        if (idDireccion !== "") {
            //Cargamos los laboratorios
            $.post("<?php echo URL ?>Laboratorios/Servicios/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").html(data);
                $('#id_laboratorio option[value="<?php echo $this->modeloServicios->getIdLaboratorio(); ?>"]').prop('selected', true);
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
            $.post("<?php echo URL ?>Laboratorios/Servicios/comboServiciosSinJoinGuia/" + idLaboratorio, function (data) {
                $("#id_servicio").html(data);
                $('#id_servicio option[value="<?php echo $this->modeloServicios->getIdServicio(); ?>"]').prop('selected', true);
                fn_cargarCampos();
            });
        }
    }

    //Cargamos los servicios del sistema GUIA
    $("#id_laboratorio").change(function () {
        fn_cargarServicios();
    });

    //Para gargar los servicios
    function fn_cargarServicios() {
        var idLaboratorio = $("#id_laboratorio").val();

        if (idLaboratorio !== "") {

            //Cargamos el combotree segun el laboratorio seleccionado
            $.post("<?php echo URL ?>Laboratorios/Servicios/buscarServiciosPadre/" + idLaboratorio, function (data) {
                $('#id_servicio').combotree({
                    data: data,
                    editable: true,
                    onClick: function (node) {
                        fn_obtenerDatosPadre(node.id);
                    }
                });
                if (crearHijo === 1) {
                    $('#id_servicio').combotree('setValue', auxIdPadre);
                    fn_obtenerDatosPadre(auxIdPadre);
                } else {
                    $('#id_servicio').combotree('setValue', '<?php echo $this->modeloServicios->getFkIdServicio(); ?>');
                }
            }, 'json');
        }
    }

    //Para obtener los datos del servicio padre como el nivel
    function fn_obtenerDatosPadre(idServicio) {
        $.post("<?php echo URL ?>Laboratorios/Servicios/buscarServicio/" + idServicio, function (data) {
        }, 'json');
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
