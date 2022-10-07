<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias'
      data-opcion='ListaNotificacion/guardar' data-destino="detalleItem"
      data-accionEnExito="ACTUALIZAR" method="post">
     <fieldset>
        <legend>Nueva Lista Notificaciones</legend>
        
        <div data-linea="1">
            <label for="nombre_lista">Nombre Lista </label>
            <input type="hidden" id="id_lista_notificacion" name="id_lista_notificacion" />
            <input type="text" id="nombre_lista" name="nombre_lista" value="<?php echo $this->modeloListaNotificacion->getNombreLista(); ?>"
                placeholder="Nombre de la lista de notificación" required maxlength="256" />
	</div>
        
        <div data-linea="2">
            <label for="anio"> Año</label> 
            <input type="number" id="anio" value="<?php echo date("Y"); ?>"  name="anio" min="<?php echo date("Y") - 5; ?>" max="<?php echo date("Y"); ?>">
        </div>
        
        <div data-linea="3">
			<label for="mes"> Mes</label> 
                <select
                    id="mes" name="mes" required>
                    <option value="">Seleccionar....</option>
                    <option value="Enero">Enero</option>
                    <option value="Febrero">Febrero</option>
                    <option value="Marzo">Marzo</option>
                    <option value="Abril">Abril</option>
                    <option value="Mayo">Mayo</option>
                    <option value="Junio">Junio</option>
                    <option value="Julio">Julio</option>
                    <option value="Agosto">Agosto</option>
                    <option value="Septiembre">Septiembre</option>
                    <option value="Octubre">Octubre</option>
                    <option value="Noviembre">Noviembre</option>
                    <option value="Diciembre">Diciembre</option>

                </select>
	</div>
       
        <div data-linea="5">
            
            <button type="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
    });

    $("#formulario").submit(function (event) {
        event.preventDefault();
		var error = false;
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado === 'exito'){
	       		fn_filtrar();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
    });
 
</script>
