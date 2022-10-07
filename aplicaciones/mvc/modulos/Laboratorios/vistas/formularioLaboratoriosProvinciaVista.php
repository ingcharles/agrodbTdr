			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'LaboratoriosProvincia/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset><legend>Laboratorios en Provincia</legend>

        <div data-linea="1">
            <label for="id_direccion"> Dirección </label> 
            <select id="id_direccion" name="id_direccion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloLaboratoriosProvincia->getIdDireccion());
                ?>
            </select>
        </div>

        <div data-linea="1">
            <label for="id_laboratorio">Laboratorio</label> <select
                id="id_laboratorio" name="id_laboratorio" required>

            </select>
        </div>

        <div data-linea="2">
            <label>Provincia del Laboratorio</label> 
            <select id="id_localizacion" name="id_localizacion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboProvinciasEc($this->modeloLaboratoriosProvincia->getIdLocalizacion());
                ?>
            </select>
        </div>

        <div data-linea = "2" >
            <label for="tipo"> Tipo de laboratorio</label> <select
                id = "tipo" name = "tipo" required>
                <option value = ""> Seleccionar....</option>
                <?php
                echo $this->tipoLaboratorio($this->modeloLaboratoriosProvincia->getTipo());
                ?>
            </select>
        </div>

        <div data-linea="3">
            <label>Estado</label> 
            <select name="estado" required>
                <?php echo $this->combo3Estados($this->modeloLaboratoriosProvincia->getEstado()); ?>
            </select>
        </div>

        <div data-linea="3"></div>

        <label for="referencia_ubicacion">Referencia de ubicación</label>
        <div data-linea="4">
            <textarea id="referencia_ubicacion" name="referencia_ubicacion"
                      placeholder="Ingrese un mensaje"><?php echo $this->modeloLaboratoriosProvincia->getReferenciaUbicacion(); ?></textarea>
        </div>
        
        <label for="mensaje_publico"> Mensaje P&uacute;blico </label>
        <div data-linea="5">
            <textarea id="mensaje_publico" name="mensaje_publico"
                      placeholder="Ingrese un mensaje"><?php echo $this->modeloLaboratoriosProvincia->getMensajePublico(); ?></textarea>
        </div>
        
        <div data-linea ="6">			
            <input type ="hidden" name="id_laboratorios_provincia" id="id_laboratorios_provincia" value ="<?php echo $this->modeloLaboratoriosProvincia->getIdLaboratoriosProvincia() ?>">
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
        fn_cargarLaboratorios();
    });

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#id_direccion").change(function () {
        fn_cargarLaboratorios();
    });

    //Para cargar los laboratorios una vez sleccionado la dirección
    function fn_cargarLaboratorios() {
        var idDireccion = $("#id_direccion").val();
        if (idDireccion !== "") {
            $.post("<?php echo URL ?>Laboratorios/LaboratoriosProvincia/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").html(data);
                $('#id_laboratorio option[value="<?php echo $this->modeloLaboratoriosProvincia->getIdLaboratorio(); ?>"]').prop('selected', true);
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
