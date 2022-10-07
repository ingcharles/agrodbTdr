<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'
      data-opcion = 'Bodegas/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Bodegas</legend>
        <div data-linea="2" >
            <label>Provincia del laboratorio</label> 
            <select id="id_localizacion" name="id_localizacion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboProvinciasEc($this->modeloBodegas->getIdLocalizacion());
                ?>
            </select>
        </div>

        <div data-linea ="2">
            <label for="nombre_bodega"> Nombre de bodega </label> 
            <input type ="text" id="nombre_bodega"
                   name ="nombre_bodega" value="<?php echo $this->modeloBodegas->getNombreBodega(); ?>"
                   placeholder ="Nombre de la bodega"
                   required  maxlength="64" />
        </div >

        <div data-linea="3">
            <label>Estado</label> 
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloBodegas->getEstado()); ?>
            </select>
        </div>

        <div data-linea ="5">			
            <input type ="hidden" name="id_bodega" id="id_bodega" value ="<?php echo $this->modeloBodegas->getIdBodega() ?>">		
            <button type="submit" class="guardar"> Guardar</button>
        </div >
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        construirValidador();
        distribuirLineas();
    });
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
</script>
