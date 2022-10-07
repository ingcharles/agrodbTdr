<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<fieldset class="fieldsetInterna">
    <legend class="legendInterna">Vista preliminar: Formulario de resultados</legend>
    <div style="background-color: #98bf21">
        <?php echo $this->camposResultado; ?>
    </div>
</fieldset>

<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			
      data-opcion = 'CamposResultadosInformes/guardarCopia' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Copiar: Formulario de resultados</legend>


        <hr>
        <fieldset class="fieldsetInterna">			
            <legend class='legendInterna'>Seleccione el servicio donde quiere copiar</legend>
            <div data-linea="100">
                <label for="id_direccion"> Dirección </label> 
                <select id="id_direccion" name="id_direccion" required>
                    <option value="">Seleccionar....</option>
                    <?php
                    echo $this->comboDirecciones($this->modeloCamposResultadosInformes->getIdLaboratorio());
                    ?>
                </select>
            </div>

            <div data-linea="100">
                <label for="id_laboratorio">Laboratorio</label> 
                <select id="id_laboratorio" name="id_laboratorio" required>

                </select>
            </div>

            <div data-linea="101">
                <label for="id_servicio"> Servicio </label> 
                <select id="id_servicio" name="id_servicio" required>
                </select>
            </div>

            <div data-linea ="101">			
                <input type="hidden" id="id_campos_resultados_inf" name="id_campos_resultados_inf" value="<?php echo $this->idCampoRaiz ?>"/>
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
        idDireccion = $("#id_direccion").val();
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
        idLaboratorio = $("#id_laboratorio").val();
        if (idLaboratorio !== "") {
            $.post("<?php echo URL ?>Laboratorios/CamposResultadosInformes/comboServiciosSinJoinGuia/" + idLaboratorio, function (data) {
                $("#id_servicio").html(data);
                $('#id_servicio option[value="<?php echo $this->modeloCamposResultadosInformes->getIdServicio(); ?>"]').prop('selected', true);
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
