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
            <label for="nombre"> Nombre </label> <input
                type="text" id="nombre" name="nombre"
                value=""
                placeholder="Nombre del sevicio" required />
        </div>

        <div data-linea="2">
            <label for="codigo_analisis"> Código </label> 
            <input type="text" id="codigo_analisis" name="codigo_analisis"
                   value=""
                   placeholder="Código para identificar el nombre del servicio"
                   maxlength="64" />
        </div>
        <div data-linea="2">
            <label for="parametro"> Parámetro </label> 
            <input type="text" id="parametro" name="parametro"
                   value=""
                   placeholder="Nombre del parámetro de análisis" 
                   maxlength="128" />
        </div>

        <div data-linea="2">
            <label for="metodo"> Método </label> 
            <input type="text" id="metodo" name="metodo"
                   value=""
                   placeholder="Método del análisis" maxlength="64"/>
        </div>

        <div data-linea="6">
            <label for="tecnica"> Técnica </label> 
            <input type="text" id="tecnica" name="tecnica"
                   value=""
                   placeholder="Técnica utilizada para el análisis" 
                   maxlength="128" />
        </div>

        <div data-linea="6">
            <label for="metodo_referencia"> Método de referencia </label> 
            <input type="text" id="metodo_referencia" name="metodo_referencia"
                   value=""
                   placeholder="Método externo de referencia para el análisis" maxlength="64"/>

        </div>

        <div data-linea="7">
            <label for="orden"> Orden </label> 
            <input type="number" id="orden" name="orden"
                   value=""
                   placeholder="Orden al desplegarse en la pantalla" required
                   maxlength="3"/>
        </div>

        <div data-linea="7">
            <?php echo $this->crearRadioEstadoAI($this->modeloServicios->getEstado()); ?>
        </div>

        <label for="requisitos"> Requisitos </label>
        <div data-linea="8">
            <textarea id="requisitos" name="requisitos" 
                      placeholder=""></textarea>
        </div>
        
        <div data-linea="9">
           
            <input type="hidden" name="id_direccion" id="id_direccion" value="<?php echo $this->modeloServicios->getIdDireccion() ?>">
            <input type="hidden" name="id_laboratorio" id="id_laboratorio" value="<?php echo $this->modeloServicios->getIdLaboratorio() ?>">
            <input type="hidden" name="fk_id_servicio" id="fk_id_servicio" value="<?php echo $this->modeloServicios->getIdServicio() ?>">

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