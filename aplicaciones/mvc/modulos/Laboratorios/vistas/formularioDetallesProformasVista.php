			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'Detallesproformas/guardar' data-destino ="detalleItem"			 data-accionEnExito ="NADA" method="post">			<fieldset>			<legend>Detallesproformas</legend>			
        <div data-linea ="1">
            <label for="id_detalle_proforma"> Identificador de la tabla detalles_proformas </label> <input type ="text" id="id_detalle_proforma"
                                                                                                           name ="id_detalle_proforma" value="<?php echo $this->modeloDetallesproformas->getIdDetalleProforma(); ?>"
                                                                                                           placeholder ="Identificador de la tabla detalles_proformas"
                                                                                                           required  maxlength="512" />
        </div >

        <div data-linea ="2">
            <label for="id_proforma"> Identificador primario de la tabla Proformas </label> <input type ="text" id="id_proforma"
                                                                                                   name ="id_proforma" value="<?php echo $this->modeloDetallesproformas->getIdProforma(); ?>"
                                                                                                   placeholder ="Identificador primario de la tabla Proformas"
                                                                                                   required  maxlength="512" />
        </div >

        <div data-linea ="3">
            <label for="nom_servicio"> Nombre del servicio/analisis </label> <input type ="text" id="nom_servicio"
                                                                                    name ="nom_servicio" value="<?php echo $this->modeloDetallesproformas->getNomServicio(); ?>"
                                                                                    placeholder ="Nombre del servicio/analisis"
                                                                                    required  maxlength="512" />
        </div >

        <div data-linea ="4">
            <label for="cantidad"> Cantidad solicitada </label> <input type ="text" id="cantidad"
                                                                       name ="cantidad" value="<?php echo $this->modeloDetallesproformas->getCantidad(); ?>"
                                                                       placeholder ="Cantidad solicitada"
                                                                       required  maxlength="512" />
        </div >

        <div data-linea ="5">
            <label for="precio_unitario"> Precio unitario del servicio </label> <input type ="text" id="precio_unitario"
                                                                                       name ="precio_unitario" value="<?php echo $this->modeloDetallesproformas->getPrecioUnitario(); ?>"
                                                                                       placeholder ="Precio unitario del servicio"
                                                                                       required  maxlength="512" />
        </div >

        <div data-linea ="6">
            <label for="precio_total"> Precio total del servicio </label> <input type ="text" id="precio_total"
                                                                                 name ="precio_total" value="<?php echo $this->modeloDetallesproformas->getPrecioTotal(); ?>"
                                                                                 placeholder ="Precio total del servicio"
                                                                                 required  maxlength="512" />
        </div >

        <div data-linea ="7">
            <button type ="submit" class="guardar"> Guardar</button>
        </div >
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
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
